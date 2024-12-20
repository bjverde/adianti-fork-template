<?php
/**
 * SystemPostList
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPostList extends TStandardList
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();
        
        parent::setDatabase('communication');            // defines the database
        parent::setActiveRecord('SystemPost');   // defines the active record
        parent::setDefaultOrder('id', 'desc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('title', 'like', 'title'); // filterField, operator, formField
        parent::addFilterField('content', 'like', 'content'); // filterField, operator, formField
        parent::addFilterField('system_user_id', '=', 'created_by'); // filterField, operator, formField
        parent::addFilterField('active', '=', 'active'); // filterField, operator, formField
        
        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );
        
        $this->form = new BootstrapFormBuilder('post_list');
        $this->form->setFormTitle(_t("Posts"));
        
        $id = new TEntry('id');
        $title = new TEntry('title');
        $content = new TEntry('content');
        $created_by = new TDBCombo('created_by', 'permission', 'SystemUser', 'id', '{name}','name asc'  );
        $active = new TCombo('active');
        
        $id->setSize('100%');
        $title->setSize('100%');
        $content->setSize('100%');
        $active->setSize('100%');
        $created_by->setSize('100%');
        $active->addItems(['Y'=>_t('Yes'),'N'=>_t('No')]);
        
        $this->form->addFields([new TLabel('ID')]);
        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel(_t('Title'))]);
        $this->form->addFields([$title]);
        $this->form->addFields([new TLabel(_t('Content'))]);
        $this->form->addFields([$content]);
        $this->form->addFields([new TLabel(_t('Created by'))]);
        $this->form->addFields([$created_by]);
        $this->form->addFields([new TLabel(_t('Active'))]);
        $this->form->addFields([$active]);
        
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        $btn_onsearch = $this->form->addAction(_t("Search"), new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary');
        
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        
        $column_id = new TDataGridColumn('id', "ID", 'center' , '70');
        $column_title = new TDataGridColumn('title', _t("Title"), 'left');
        $column_created_at_transformed = new TDataGridColumn('created_at', _t("Created at"), 'center' , '150px');
        $column_updated_at_transformed = new TDataGridColumn('updated_at', _t("Updated at"), 'center' , '150px');
        $column_active_transformed = new TDataGridColumn('active', _t("Active"), 'left' , '100px');
        
        $column_title->setTransformer(function($value, $object, $row)
        {
            return '<b>'.$value.'</b> <br>' . substr(strip_tags($object->content),0,70) . '...';
        });
        
        $column_created_at_transformed->setTransformer(function($value, $object, $row)
        {
            $output = $object->author->name;
            
            if(!empty(trim($value)))
            {
                $date = new DateTime($value);
                $output .= '<br>' . $date->format('d/m/Y H:i');
            }
            
            return $output;
        });
        
        $column_updated_at_transformed->setTransformer(function($value, $object, $row)
        {
            $output = $object->updater?->name;
            
            if(!empty(trim($value)))
            {
                $date = new DateTime($value);
                $output .= '<br>' . $date->format('d/m/Y H:i');
            }
            
            return $output;
        });
        
        $column_active_transformed->setTransformer( function($value) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:10pt;";
            $div->add($label);
            return $div;
        });
        
        $column_id->setAction(new TAction(array($this, 'onReload'), ['order' => 'id'] ));
        $column_title->setAction(new TAction(array($this, 'onReload'), ['order' => 'title'] ));
        
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_title);
        //$this->datagrid->addColumn($column_system_user_name);
        $this->datagrid->addColumn($column_created_at_transformed);
        $this->datagrid->addColumn($column_updated_at_transformed);
        $this->datagrid->addColumn($column_active_transformed);
        
        $action_onEdit = new TDataGridAction(['SystemPostForm', 'onEdit'], ['register_state' => 'false'] );
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel(_t('Edit'));
        $action_onEdit->setImage('far:edit blue');
        $action_onEdit->setField('id');
        
        $action_onDelete = new TDataGridAction(['SystemPostList', 'onDelete'], ['register_state' => 'false']);
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel(_t('Delete'));
        $action_onDelete->setImage('fas:trash-alt red');
        $action_onDelete->setField('id');
        
        $this->datagrid->addAction($action_onEdit);
        $this->datagrid->addAction($action_onDelete);
        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);
        
        $btnf = TButton::create('find', [$this, 'onSearch'], '', 'fa:search');
        $btnf->style= 'height: 37px; margin-right:4px;';
        
        $form_search = new TForm('form_search_name');
        $form_search->style = 'float:left;display:flex';
        $form_search->add($title, true);
        $form_search->add($btnf, true);
        
        $panel->addHeaderWidget($form_search);
        
        $panel->addHeaderActionLink('', new TAction(['SystemPostForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus');
        $this->filter_label = $panel->addHeaderActionLink(_t('Filters'), new TAction([$this, 'onShowCurtainFilters']), 'fa:filter');
        
        if (TSession::getValue(get_class($this).'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel(_t('Filters') . ' ('. TSession::getValue(get_class($this).'_filter_counter').')');
        }
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        //$container->add($this->form);
        $container->add($panel);

        parent::add($container);
    }
    
    /**
     *
     */
    public function onAfterSearch($datagrid, $options)
    {
        if (TSession::getValue(get_class($this).'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel(_t('Filters') . ' ('. TSession::getValue(get_class($this).'_filter_counter').')');
        }
        else
        {
            $this->filter_label->class = 'btn btn-default';
            $this->filter_label->setLabel(_t('Filters'));
        }
        
        if (!empty(TSession::getValue(get_class($this).'_filter_data')))
        {
            $obj = new stdClass;
            $obj->title = TSession::getValue(get_class($this).'_filter_data')->title;
            TForm::sendData('form_search_name', $obj);
        }
    }
    
    /**
     *
     */
    public static function onShowCurtainFilters($param = null)
    {
        try
        {
            // create empty page for right panel
            $page = TPage::create();
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('override', 'true');
            $page->setPageName(__CLASS__);
            
            $btn_close = new TButton('closeCurtain');
            $btn_close->onClick = "Template.closeRightPanel();";
            $btn_close->setLabel("Fechar");
            $btn_close->setImage('fas:times');
            
            // instantiate self class, populate filters in construct 
            $embed = new self;
            $embed->form->addHeaderWidget($btn_close);
            
            // embed form inside curtain
            $page->add($embed->form);
            $page->show();
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    
    /**
     * on delete post
     */
    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                $key = $param['key'];
                TTransaction::open($this->database);
                $object = new SystemPost($key, FALSE); 
                $object->clearParts();
                $object->delete();
                TTransaction::close();
                $this->onReload( $param );

                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e)
            {
                new TMessage('error', $e->getMessage());
                TTransaction::rollback();
            }
        }
        else
        {
            $action = new TAction([$this, 'onDelete'], ['register_state' => 'false']);
            $action->setParameters($param);
            $action->setParameter('delete', 1);

            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }
}