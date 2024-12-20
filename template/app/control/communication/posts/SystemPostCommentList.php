<?php
/**
 * SystemPostCommentList
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPostCommentList extends TPage
{
    private $container;
    private $loaded;

    public function __construct($param = null)
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        
        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'position: absolute; top: 10px; right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel(_t("Close"));
        $btnClose->setImage('fas:times red');
        
        $this->container = new TElement('div');
        $this->container->{'class'} = 'post-comments';
        
        $panel = new TPanelGroup(_t('Comments') );
        $panel->setProperty('class', 'card noborder');
        $panel->addHeaderWidget($btnClose);
        $panel->add($this->container);
        $panel->getBody()->class .= ' system_post';
        
        $float_button = new TElement('a');
        $float_button->href = (new TAction(['SystemPostCommentForm', 'onShow'], ['system_post_id' => $param['system_post_id'] ] ))->serialize() . '&noscroll=1&register_state=false';
        $float_button->generator = 'adianti';
        $float_button->class = 'float-button bg-primary';
        $float_button->title = _t('Comment');
        $float_button->add('<i class="fas fa-plus internal-float-button"></i>');
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($panel);
        $container->add($float_button);

        parent::add($container);
    }
    
    /**
     * on reload comment
     */
    public function onReload($param = NULL)
    {
        try
        {
            TTransaction::open('communication');
            $repository = new TRepository('SystemPostComment');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('system_post_id', '=', $param['system_post_id']));
            
            if (empty($param['order']))
            {
                $param['order'] = 'created_at';    
            }
            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param);
            $criteria->setProperty('limit', $this->limit);
            $objects = $repository->load($criteria, FALSE);
            
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $this->container->add($object->html_content);
                }
            }
            TTransaction::close();
            $this->loaded = true;

            return $objects;
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
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
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
