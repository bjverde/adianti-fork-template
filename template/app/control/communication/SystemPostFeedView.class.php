<?php
/**
 * SystemPostViewList
 *
 * @version    1.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemPostFeedView extends TPage
{
    private $form;
    private $cardView;
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('post_feed_search_form');
        $this->form->setFormTitle(_t("Notices"));

        $content = new TEntry('content');
        $content->{'placeholder'} = _t('Content');
        $content->setSize('100%');
        
        $row1 = $this->form->addFields([$content]);
        $row1->layout = ['col-sm-12'];
        
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction(_t("Search"), new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        $this->cardView = new TCardView;
        $this->cardView->setProperty('class', 'card-wrapper system_post');
        $this->cardView->setTitleTemplate($this->getTitleTemplate());
        $this->cardView->setItemTemplate($this->getPostTemplate());
        $this->cardView->setItemDatabase('communication');

        $filterVar = TSession::getValue("usergroupids");
        $filterVar = is_array($filterVar) ? "'".implode("','", $filterVar)."'" : $filterVar;

        $this->filter_criteria = new TCriteria;
        $this->filter_criteria->add(new TFilter('id', 'in', "(SELECT system_post_id FROM system_post_share_group WHERE system_group_id in ({$filterVar}))"));
        $this->filter_criteria->add(new TFilter('active', '=', 'Y'));

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        
        $container = new TVBox;
        $container->style = 'width: 100%;';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($this->cardView);
        $container->add($this->pageNavigation);

        parent::add($container);
    }
    
    /**
     * Return template of title post, with picture, user and tags
     */
    public function getTitleTemplate()
    {
        $div = new TElement('div');
        $img = new TElement('img');
        $title = new TElement('div');

        $div->{'class'} = 'title-system-post';

        $img->src = "app/images/photos/{system_user->login}.jpg";
        $img->onerror = "this.onerror=null;this.src='app/templates/theme4/images/user.png';";
        $img->alt = "User";

        $title->add(TElement::tag('span', '{system_user->name}'));
        $title->add(TElement::tag('small', '{date}'));
        
        $div->add($img);
        $div->add($title);
        $div->add(TElement::tag('div', '{tags_formatted}', ['style' => 'display:block;text-align:right']));

        return $div->getContents();
    }

    /**
     * Return template of content post, with actions, comments
     */
    public function getPostTemplate()
    {
        $div = new TElement('div');
        $actions = new TElement('div');
        $actions->{'class'} = 'post-actions';
        
        $infos = new TElement('div');
        $infos->{'class'} = 'post-info';
        
        $infos->add( TElement::tag('div', '{count_likes} ' . _t('Likes') . ' - {count_comments} ' . _t('Comments')));

        $actionMoreComment = new TAction(['SystemPostCommentList', 'onReload']);

        $button = new TElement('a');
        $button->{'href'} = $actionMoreComment->serialize() . '&system_post_id={id}&noscroll=1&register_state=false';
        $button->{'generator'} = 'adianti';
        $button->{'class'} = 'see_more_comments';
        $button->add( _t("See more")); 

        $div->add(TElement::tag('div', '{title}', ['class' => 'post-title']));
        $div->add(TElement::tag('div', '{content}', ['class' => 'post-content']));
        $div->add($infos);
        $div->add($actions);
        $div->add("{last_comments_formated}");
        $div->add($button);

        $actionLike = new TAction(['SystemPostFeedView', 'onLike']);

        $button = new TElement('a');
        $button->{'href'} = $actionLike->serialize() . '&key={id}&noscroll=1&register_state=false';
        $button->{'generator'} = 'adianti';
        $button->{'liked'} = '{liked}';
        $button->{'class'} = 'like';
        $button->add(new TImage('far:thumbs-up'));
        $button->add(new TImage('fas:thumbs-up'));
        $button->add( _t("Like")); 

        $actions->add($button);

        $actionComment = new TAction(['SystemPostCommentForm', 'onShow']);

        $button = new TElement('a');
        $button->{'href'} = $actionComment->serialize() . '&system_post_id={id}&noscroll=1&register_state=false';
        $button->{'generator'} = 'adianti';
        $button->{'class'} = 'comment';
        $button->add(new TImage('far:comment-dots'));
        $button->add( _t("Comment")); 
        
        $actions->add($button);
        
        return $div->getContents();
    }

    /**
     * Like/Dislike
     */
    public function onLike($param = null) 
    { 
        try
        {
            TTransaction::open('communication');
            $post = SystemPost::find($param['key']);

            if (! $post->liked )
            {
                $object = new SystemPostLike(); 
                $object->system_post_id = $post->id;
                $object->system_user_id = TSession::getValue('userid');
                $object->store();
            }
            else
            {
                SystemPostLike::where('system_post_id', '=', $param['key'])->where('system_user_id', '=', TSession::getValue('userid'))->delete();
            }

            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }  
    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->content) AND ( (is_scalar($data->content) AND $data->content !== '') OR (is_array($data->content) AND (!empty($data->content)) )) )
        {
            $filters[] = new TFilter('content', 'like', "%{$data->content}%");
            $filters[] = new TFilter('title', 'like', "%{$data->content}%");
        }

        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;

        $this->form->setData($data);

        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload($param);
    }
    
    /**
     * on reload view
     */
    public function onReload($param = NULL)
    {
        try
        {
            TTransaction::open('communication');

            $repository = new TRepository('SystemPost');
            $limit = 30;

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);

            $subcriteria = new TCriteria;
            
            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $subcriteria->add($filter, TExpression::OR_OPERATOR);
                }
            }
            
            if (!$subcriteria->isEmpty())
            {
                $criteria->add($subcriteria);
            }
            
            $objects = $repository->load($criteria, FALSE);

            $this->cardView->clear();

            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $this->cardView->addItem($object);
                }
            }

            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'], ['onReload', 'onSearch'] ))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
