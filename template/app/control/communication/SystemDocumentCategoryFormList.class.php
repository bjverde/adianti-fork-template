<?php
/**
 * SystemDocumentCategoryFormList
 *
 * @version    7.6
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDocumentCategoryFormList extends TPage
{
    protected $form; // form
    protected $datagrid; // datagrid
    protected $pageNavigation;
    
    use Adianti\Base\AdiantiStandardFormListTrait; // standard form/list methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('communication');            // defines the database
        $this->setActiveRecord('SystemDocumentCategory');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(-1); // turn off limit for datagrid
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_SystemDocumentCategory');
        $this->form->setFormTitle(_t('Categories'));
        
        // create the form fields
        $id = new TEntry('id');
        $name = new TEntry('name');
        $id->setEditable(FALSE);

        // add the fields
        $this->form->addFields( [new TLabel('ID')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$name] );
        $id->setSize('30%');
        $name->setSize('70%');
        
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('Clear form'),  new TAction(array($this, 'onEditCurtain')), 'fa:eraser red');
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = '100%';
        
        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left', 50);
        $column_name = new TDataGridColumn('name', 'Name', 'left');
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        
        $column_id->setAction( new TAction([$this, 'onReload']),   ['order' => 'id']);
        $column_name->setAction( new TAction([$this, 'onReload']), ['order' => 'name']);
        
        // define row actions
        $action1 = new TDataGridAction([$this, 'onEditCurtain'],   ['key' => '{id}'] );
        $action2 = new TDataGridAction([$this, 'onDelete'], ['key' => '{id}'] );
        
        $this->datagrid->addAction($action1, 'Edit',   'far:edit blue');
        $this->datagrid->addAction($action2, 'Delete', 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // wrap objects inside a table
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        
        //$vbox->add($this->form);
        
        $vbox->add($panel = TPanelGroup::pack('', $this->datagrid));
        
        
        
        // search box
        $input_search = new TEntry('input_search');
        $input_search->placeholder = _t('Search');
        $input_search->setSize('100%');
        
        // enable fuse search by column name
        $this->datagrid->enableSearch($input_search, 'id, name');
        $panel->addHeaderWidget($input_search);
        
        $panel->addHeaderActionLink(_t('New'), new TAction([$this, 'onEditCurtain']), 'fa:plus green');
        
        // pack the table inside the page
        parent::add($vbox);
    }
    
    /**
     *
     */
    public static function onEditCurtain($param = null) 
    {
        try
        {
            $page = new TPage;
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('override', 'true');
            $page->setPageName(__CLASS__);
            
            $btn_close = new TButton('closeCurtain');
            $btn_close->onClick = "Template.closeRightPanel();";
            $btn_close->setLabel("Fechar");
            $btn_close->setImage('fas:times');
            
            $filter = new self($param);
            $filter->form->addHeaderWidget($btn_close);
            $filter->onEdit($param);
            
            $page->add($filter->form);
            $page->setIsWrapped(true);
            $page->show();
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
}
