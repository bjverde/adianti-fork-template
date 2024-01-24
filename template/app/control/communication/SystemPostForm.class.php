<?php
/**
 * SystemPostForm
 *
 * @version    7.6
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPostForm extends TPage
{
    protected $form;
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        $this->form = new BootstrapFormBuilder('post_form');
        $this->form->setFormTitle("Post");

        $id = new THidden('id');
        $title = new TEntry('title');
        $content = new THtmlEditor('content');
        $tags = new TMultiEntry('tags');
        
        $title->setSize('100%');
        $content->setSize('100%', 350);
        $tags->setSize('100%', 250);
        
        $this->group_list = new TCheckList('group_list');
        $this->group_list->setIdColumn('id');
        $this->group_list->addColumn('id',    'ID',    'center',  '10%');
        $this->group_list->addColumn('name', _t('Name'),    'left',   '50%');
        $this->group_list->setHeight(350);
        $this->group_list->makeScrollable();

        TTransaction::open('permission');
        $this->group_list->addItems( SystemGroup::get() );
        TTransaction::close();
        
        $this->form->appendPage(_t("Details"));
        
        $row1 = $this->form->addFields([$id]);
        $row1->layout = [' col-sm-3',' col-sm-4','col-sm-2',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel(_t('Title') .":", '#ff0000', '14px', null, '100%'), $title]);
        $row3->layout = [' col-sm-12'];
        
        $row3 = $this->form->addFields([new TLabel(_t('Content') .":", '#ff0000', '14px', null, '100%'), $content]);
        $row3->layout = [' col-sm-12'];

        $this->form->appendPage(_t('Groups'));
        $row4 = $this->form->addFields([$this->group_list]);
        $row4->layout = [' col-sm-12'];

        $this->form->appendPage("Tags");
        $row5 = $this->form->addFields([$tags]);
        $row5->layout = [' col-sm-12'];

        $btn_onsave = $this->form->addAction(_t("Save"), new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 
        $btn_onclear = $this->form->addAction(_t("Clear form"), new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $btn_onshow = $this->form->addAction(_t("Back"), new TAction(['SystemPostList', 'onReload']), 'fas:arrow-left #000000');
        
        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel(_t("Close"));
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);
        
        parent::add($this->form);
    }
    
    /**
     * on save post
     */
    public function onSave() 
    {
        try
        {
            TTransaction::open('communication');
            
            $this->form->validate();
            $object = new SystemPost();
            $data = $this->form->getData();
            $object->fromArray( (array) $data);
            $object->system_user_id = TSession::getValue('userid');
            
            $object->store();
            
            $repository = SystemPostShareGroup::where('system_post_id', '=', $object->id);
            $repository->delete(); 

            if (!empty($data->group_list))
            {
                foreach ($data->group_list as $group_id)
                {
                    $system_group_tag = new SystemPostShareGroup;

                    $system_group_tag->system_group_id = $group_id;
                    $system_group_tag->system_post_id = $object->id;
                    $system_group_tag->store();
                }
            }

            $repository = SystemPostTag::where('system_post_id', '=', $object->id);
            $repository->delete(); 

            if ($data->tags) 
            {
                foreach ($data->tags as $tag_value) 
                {
                    $system_wiki_tag = new SystemPostTag;
                    $system_wiki_tag->tag = $tag_value;
                    $system_wiki_tag->system_post_id = $object->id;
                    $system_wiki_tag->store();
                }
            }
            
            $messageAction = new TAction(['SystemPostList', 'onReload']);
            
            $data->id = $object->id; 

            $this->form->setData($data);
            TTransaction::close();

            new TMessage('info', _t("Record saved"), $messageAction); 

            TScript::create("Template.closeRightPanel();"); 
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
    
    /**
     * on edit post
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];
                TTransaction::open('communication');
                $object = new SystemPost($key);
                $object->group_list = SystemPostShareGroup::where('system_post_id', '=', $object->id)->getIndexedArray('system_group_id', 'system_group_id');
                $object->tags = SystemPostTag::where('system_post_id', '=', $object->id)->getIndexedArray('tag', 'tag');
                $this->form->setData($object);
                TTransaction::close();
            }
            else
            {
                $this->form->clear(true);
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear()
    {
        $this->form->clear(true);
    }
}
