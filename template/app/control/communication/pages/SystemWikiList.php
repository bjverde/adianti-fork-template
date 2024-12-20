<?php
/**
 * SystemWikiList
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemWikiList extends TStandardList
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
        parent::setActiveRecord('SystemWikiPage');   // defines the active record
        parent::setDefaultOrder('id', 'desc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('title', 'like', 'title'); // filterField, operator, formField
        parent::addFilterField('active', '=', 'active'); // filterField, operator, formField
        parent::addFilterField('searchable', '=', 'searchable'); // filterField, operator, formField
        
        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );
        
        $this->form = new BootstrapFormBuilder('wiki_list_form');
        $this->form->setFormTitle("Wiki");
        
        $id = new TEntry('id');
        $title = new TEntry('title');
        $active = new TCombo('active');
        $searchable = new TCombo('searchable');
        $active->addItems(['Y'=>_t('Yes'),'N'=> _t('No')]);
        $searchable->addItems(['Y'=>_t('Yes'),'N'=> _t('No')]);

        $id->setSize('100%');
        $title->setSize('100%');
        $active->setSize('100%');
        $searchable->setSize('100%');

        $this->form->addFields([new TLabel('ID')]);
        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel(_t('Title'))]);
        $this->form->addFields([$title]);
        $this->form->addFields([new TLabel(_t('Active'))]);
        $this->form->addFields([$active]);
        $this->form->addFields([new TLabel(_t('Searchable'))]);
        $this->form->addFields([$searchable]);
        
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction(_t("Search"), new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 
        
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        
        $column_id = new TDataGridColumn('id', "ID", 'center' , '70px');
        $column_title = new TDataGridColumn('title', _t("Title"), 'left');
        $column_active_transformed = new TDataGridColumn('active', _t("Active"), 'center' , '100px');
        $column_searchable_transformed = new TDataGridColumn('searchable', _t("Searchable"), 'center' , '100px');
        $column_date_created = new TDataGridColumn('created_at', _t("Created at"), 'center' , '150px');
        $column_date_updated = new TDataGridColumn('updated_at', _t("Updated at"), 'center' , '150px');
        
        $column_title->setTransformer(function($value, $object, $row)
        {
            return '<b>'.$value.'</b> <br>' . substr(strip_tags($object->content),0,70) . '...';
        });
        
        $column_date_created->setTransformer(function($value, $object, $row)
        {
            $output = $object->author->name;
            
            if(!empty(trim($value)))
            {
                $date = new DateTime($value);
                $output .= '<br>' . $date->format('d/m/Y H:i');
            }
            
            return $output;
        });
        
        $column_date_updated->setTransformer(function($value, $object, $row)
        {
            $output = $object->updater?->name;
            
            if(!empty(trim($value)))
            {
                $date = new DateTime($value);
                $output .= '<br>' . $date->format('d/m/Y H:i');
            }
            
            return $output;
        });
        
        $column_active_transformed->setTransformer(function($value) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:10pt;";
            $div->add($label);
            return $div;
        });

        $column_searchable_transformed->setTransformer(function($value) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:10pt;";
            $div->add($label);
            return $div;
        });

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_title);
        $this->datagrid->addColumn($column_date_created);
        $this->datagrid->addColumn($column_date_updated);
        $this->datagrid->addColumn($column_searchable_transformed);
        $this->datagrid->addColumn($column_active_transformed);

        $action_onEdit = new TDataGridAction(['SystemWikiForm', 'onEdit'], ['register_state' => 'false']);
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel(_t("Edit"));
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField('id');
        
        $action_onDelete = new TDataGridAction(['SystemWikiList', 'onDelete'], [ 'register_state' => 'false']);
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel(_t("Delete"));
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
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
        
        $panel->addHeaderActionLink('', new TAction(['SystemWikiForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus');
        $this->filter_label = $panel->addHeaderActionLink(_t('Filters'), new TAction([$this, 'onShowCurtainFilters']), 'fa:filter');
        
        if (TSession::getValue(get_class($this).'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel(_t('Filters') . ' ('. TSession::getValue(get_class($this).'_filter_counter').')');
        }
        
        

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'SystemWikiList' ));
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
     * Ask before delete record
     */
    public function onDelete($param = null) 
    { 
        if (isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                $key = $param['key'];
                TTransaction::open('communication');
                $object = new SystemWikiPage($key, FALSE); 
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
            $action = new TAction(array($this, 'onDelete'), [ 'register_state' => 'false']);
            $action->setParameters($param);
            $action->setParameter('delete', 1);
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }
}
