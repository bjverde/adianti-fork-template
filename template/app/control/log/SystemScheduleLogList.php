<?php
/**
 * SystemScheduleLogList
 *
 * @version    8.0
 * @package    control
 * @subpackage log
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemScheduleLogList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('log');            // defines the database
        parent::setActiveRecord('SystemScheduleLog');   // defines the active record
        parent::setDefaultOrder('id', 'desc');         // defines the default order
        parent::addFilterField('login', 'like'); // add a filter field
        parent::addFilterField('title', 'like'); // add a filter field
        parent::addFilterField('class_name', 'like'); // add a filter field
        parent::setLimit(20);
        
        // creates the form, with a table inside
        $this->form = new BootstrapFormBuilder('form_search_SystemScheduleLog');
        $this->form->setFormTitle(_t('Schedules'));
        
        // create the form fields
        $title  = new TEntry('title');
        $class_name = new TEntry('class_name');
        
        // add the fields
        $this->form->addFields( [new TLabel(_t('Title'))], [$title], [new TLabel(_t('Class'))], [$class_name] );
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemScheduleLog_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->enablePopover('', '{message}');
        // creates the datagrid columns
        $this->datagrid->addQuickColumn('id', 'id', 'center');
        $this->datagrid->addQuickColumn(_t('Time'), 'logdate', 'center');
        $this->datagrid->addQuickColumn(_t('Title'), 'title', 'left');
        $this->datagrid->addQuickColumn(_t('Method'), '{class_name}::{method}()', 'center');
        $col_status = $this->datagrid->addQuickColumn(_t('Status'), 'status', 'center');
        
        $col_status->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:10pt;";
            $div->add($label);
            return TVBox::pack($div, $object->message);
        });
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
    
    /**
     *
     */
    public function Delete($param)
    {
        new TMessage('error', _t('Permission denied'));
    }
}
