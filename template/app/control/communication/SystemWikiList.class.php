<?php
/**
 * SystemWikiList
 *
 * @version    7.6
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
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
        
        $criteria = new TCriteria;
        if (TSession::getValue('login') !== 'admin')
        {
            $criteria->add(new TFilter('system_user_id', '=', TSession::getValue('userid')));
        }
        
        parent::setCriteria($criteria);
        
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

        $row2 = $this->form->addFields([new TLabel("ID:", null, '14px', null, '100%'),$id],
                                       [new TLabel(_t("Title") . ":", null, '14px', null, '100%'),$title],
                                       [new TLabel(_t("Active") . ":", null, '14px', null, '100%'),$active],
                                       [new TLabel(_t("Searchable") . ":", null, '14px', null, '100%'),$searchable]);
        $row2->layout = [' col-sm-1', 'col-sm-7',' col-sm-2',' col-sm-2'];

        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction(_t("Search"), new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 
        
        $btn_onshow = $this->form->addAction(_t("New"), new TAction(['SystemWikiForm', 'onEdit']), 'fas:plus #69aa46');
        
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        
        $column_id = new TDataGridColumn('id', "ID", 'center' , '70px');
        $column_title = new TDataGridColumn('title', _t("Title"), 'left');
        $column_description = new TDataGridColumn('description', _t("Description"), 'left');
        $column_system_user_name = new TDataGridColumn('{system_user->name}', _t("Created by"), 'center' , '250px');
        $column_active_transformed = new TDataGridColumn('active', _t("Active"), 'center' , '100px');
        $column_searchable_transformed = new TDataGridColumn('searchable', _t("Searchable"), 'center' , '100px');
        $column_date_created = new TDataGridColumn('date_created', _t("Created at"), 'center' , '150px');
        $column_date_updated = new TDataGridColumn('date_updated', _t("Updated at"), 'center' , '150px');

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
        $this->datagrid->addColumn($column_description);
        $this->datagrid->addColumn($column_system_user_name);
        $this->datagrid->addColumn($column_active_transformed);
        $this->datagrid->addColumn($column_searchable_transformed);
        $this->datagrid->addColumn($column_date_created);
        $this->datagrid->addColumn($column_date_updated);

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

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'SystemWikiList' ));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);
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
