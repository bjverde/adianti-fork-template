<?php
/**
 * SystemUnitForm
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemUnitForm extends TStandardForm
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        $ini  = AdiantiApplicationConfig::get();
        
        $this->setDatabase('permission');              // defines the database
        $this->setActiveRecord('SystemUnit');     // defines the active record
        $this->setAfterSaveAction( new TAction(['SystemUnitList', 'onReload']) );
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_SystemUnit');
        $this->form->setFormTitle(_t('Unit'));
        $this->form->enableClientValidation();
        
        // create the form fields
        $id = new TEntry('id');
        $name = new TEntry('name');
        $custom_code = new TEntry('custom_code');
        
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$name] );
        
        if (!empty($ini['general']['multi_database']) and $ini['general']['multi_database'] == '1')
        {
            $database = new TCombo('connection_name');
            $database->addItems( SystemDatabaseInformationService::getConnections() );
            $this->form->addFields( [new TLabel(_t('Database'))], [$database] );
            $database->setSize('100%');
        }
        
        $this->form->addFields( [new TLabel(_t('Custom code'))], [$custom_code] );
        
        $id->setEditable(FALSE);
        $id->setSize('30%');
        $name->setSize('100%');
        $name->addValidation( _t('Name'), new TRequiredValidator );
        
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('Clear'),  new TAction(array($this, 'onEdit')), 'fa:eraser red');
        //$this->form->addActionLink(_t('Back'),new TAction(array('SystemUnitList','onReload')),'far:arrow-alt-circle-left blue');
        
        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', 'SystemUnitList'));
        $container->add($this->form);
        
        parent::add($container);
    }
    
    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
