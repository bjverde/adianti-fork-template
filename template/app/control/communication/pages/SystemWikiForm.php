<?php
/**
 * SystemWikiForm
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemWikiForm extends TPage
{
    protected $form;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');

        $this->form = new BootstrapFormBuilder('wiki_form');
        $this->form->setFormTitle('Wiki');

        $id = new THidden('id');
        $searchable = new TRadioGroup('searchable');
        $active = new TRadioGroup('active');
        $title = new TEntry('title');
        $content = new THtmlEditor('content');
        $tags = new TMultiEntry('tags');

        $searchable->addValidation(_t("Searchable"), new TRequiredValidator()); 
        $active->addValidation(_t("Active"), new TRequiredValidator()); 
        $title->addValidation(_t("Title"), new TRequiredValidator()); 
        $content->addValidation(_t("Content"), new TRequiredValidator()); 

        $active->addItems(['Y'=>_t('Yes'),'N'=> _t('No')]);
        $active->setLayout('horizontal');
        $active->setUseButton();
        $active->setValue('Y');
        
        $searchable->addItems(['Y'=>_t('Yes'),'N'=> _t('No')]);
        $searchable->setLayout('horizontal');
        $searchable->setUseButton();
        $searchable->setValue('Y');
        
        $title->setSize('100%');
        $active->setSize('100%');
        $tags->setSize('100%', 250);
        $searchable->setSize('100%');
        
        $content->setSize('100%', 350);
        $content->addCustomButton("wiki_link", "Template.wikiPagePicker", _t("Add wiki link"), new TImage('fab:wikipedia-w'), true);
        
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
        
        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel(_t('Title'))]);
        $this->form->addFields([$title]);
        $this->form->addFields([new TLabel(_t('Searchable'))], [new TLabel(_t('Active'))])->layout = ['col-sm-6', 'col-sm-6'];
        $this->form->addFields([$searchable], [$active])->layout = ['col-sm-6', 'col-sm-6'];
        
        $this->form->addFields([new TLabel(_t('Content'))]);
        $this->form->addFields([$content]);
        
        $this->form->appendPage(_t("Groups"));
        $this->form->addFields([$this->group_list]);
        
        $this->form->appendPage("Tags");
        $this->form->addFields([$tags]);
        
        $btn_onsave = $this->form->addAction(_t("Save"), new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 
        $btn_onshow = $this->form->addAction(_t("Back"), new TAction(['SystemWikiList', 'onReload']), 'fas:arrow-left #000000');
        
        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel(_t("Close"));
        $btnClose->setImage('fas:times red');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);
    }
    
    /**
     * on save
     */
    public function onSave() 
    {
        try
        {
            TTransaction::open('communication');
            $this->form->validate();
            $object = new SystemWikiPage;
            $data = $this->form->getData();
            
            $object->fromArray( (array) $data);
            $object->description = $object->title;
            $object->store();

            $repository = SystemWikiShareGroup::where('system_wiki_page_id', '=', $object->id);
            $repository->delete(); 

            if (!empty($data->group_list))
            {
                foreach ($data->group_list as $group_id)
                {
                    $system_wiki_share = new SystemWikiShareGroup;
                    $system_wiki_share->system_group_id = $group_id;
                    $system_wiki_share->system_wiki_page_id = $object->id;
                    $system_wiki_share->store();
                }
            }

            $repository = SystemWikiTag::where('system_wiki_page_id', '=', $object->id);
            $repository->delete(); 

            if ($data->tags) 
            {
                foreach ($data->tags as $tag_value) 
                {
                    $system_wiki_tag = new SystemWikiTag;
                    $system_wiki_tag->tag = $tag_value;
                    $system_wiki_tag->system_wiki_page_id = $object->id;
                    $system_wiki_tag->store();
                }
            }

            $data->id = $object->id; 

            $this->form->setData($data);
            TTransaction::close();

            new TMessage('info', _t("Record saved"), new TAction(['SystemWikiList', 'onReload']));

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
     * on edit
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key']; 
                TTransaction::open('communication');
                $object = new SystemWikiPage($key);
                $object->tags = SystemWikiTag::where('system_wiki_page_id', '=', $object->id)->getIndexedArray('tag', 'tag');
                $object->group_list = SystemWikiShareGroup::where('system_wiki_page_id', '=', $object->id)->getIndexedArray('system_group_id', 'system_group_id');
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
}
