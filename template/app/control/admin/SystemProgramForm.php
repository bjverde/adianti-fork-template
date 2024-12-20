<?php
/**
 * SystemProgramForm
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemProgramForm extends TStandardForm
{
    protected $form; // form
    private $group_list;
    private $methods_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_SystemProgram');
        $this->form->setFormTitle(_t('Program'));
        $this->form->enableClientValidation();
        
        // defines the database
        parent::setDatabase('permission');
        
        // defines the active record
        parent::setActiveRecord('SystemProgram');
        
        // create the form fields
        $id                = new TEntry('id');
        $controller        = new TUniqueSearch('controller');
        $name              = new TEntry('name');
        
        $this->group_list = new TDBCheckList('group_list', 'permission', 'SystemGroup', 'id', 'name');
        $this->group_list->makeScrollable();
        $this->group_list->setHeight(210);
        
        $id->setEditable(false);
        $controller->addItems($this->getPrograms( empty($param['id']) ));
        $controller->setMinLength(0);
        $controller->setChangeAction(new TAction([$this, 'onChangeController']));
        
        // add the fields
        $this->form->addFields( [new TLabel('ID')], [$id] );
        $this->form->addFields( [new TLabel(_t('Controller'))], [$controller] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$name] );
        $this->form->addFields( [new TFormSeparator(_t('Permission'))] );
        
        $id->setSize('30%');
        $name->setSize('100%');
        $controller->setSize('100%');
        
        
        $method_name = new TCombo('method_name[]');
        $method_name->enableSearch();
        $method_name->setSize('100%');
        
        $granted_role = new TCombo('granted_role[]');
        $granted_role->enableSearch();
        $granted_role->addItems( ['Y' => _t('Yes'), 'N' => _t('No') ] );
        $granted_role->setSize('100%');
        
        $granted_role->addItems( SystemRole::getIndexedArrayInTransaction('permission', 'id', 'name') );
        
        $this->methods_list = new TFieldList;
        $this->methods_list->generateAria();
        $this->methods_list->width = '100%';
        $this->methods_list->name  = 'methods_list';
        $this->methods_list->addField( _t('Method'), $method_name, ['width' => '50%'] );
        $this->methods_list->addField( _t('Roles'),  $granted_role,  ['width' => '50%'] );
        
        $this->form->addField($method_name);
        $this->form->addField($granted_role);
        
        $this->methods_list->addHeader();
        $this->methods_list->addDetail( new stdClass );
        $this->methods_list->addCloneAction();
        
        $subform = new BootstrapFormBuilder;
        $subform->setFieldSizes('100%');
        $subform->setProperty('style', 'border:none');
        
        $subform->appendPage( _t('Groups') );
        $subform->addFields( [$this->group_list] );
        
        $subform->appendPage( _t('Restricted methods') );
        $subform->addFields( [$this->methods_list] );
        
        $this->form->addContent([$subform]);
        
        
        // validations
        $name->addValidation(_t('Name'), new TRequiredValidator);
        $controller->addValidation(('Controller'), new TRequiredValidator);

        // add form actions
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addActionLink(_t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        //$this->form->addActionLink(_t('Back'),new TAction(array('SystemProgramList','onReload')),'far:arrow-alt-circle-left blue');
        
        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml','SystemProgramList'));
        $container->add($this->form);
        
        // add the container to the page
        parent::add($container);
    }
    
    /**
     * Change controller, generate name
     */
    public static function onChangeController($param)
    {
        try
        {
            if (!empty($param['controller']) AND empty($param['name']))
            {
                $obj = new stdClass;
                $obj->name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $param['controller']);
                TForm::sendData('form_SystemProgram', $obj);
            }
            
            self::fillMethods($param['controller']);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Fill class methods
     */
    public static function fillMethods($controller, $program_id = null)
    {
        $exposed_methods  = SystemProgramService::getProgramMethods($controller);
        $found_db_methods = false;
        $combo_options    = array_combine(array_values($exposed_methods), array_values($exposed_methods));
        
        if (!empty($program_id))
        {
            TTransaction::open('permission');
            
            $method_roles = SystemProgramMethodRole::where('system_program_id','=',$program_id)->load();
            
            $granted_methods = [];
            $granted_roles   = [];
            if ($method_roles)
            {
                $found_db_methods = true;
                foreach ($method_roles as $method_role)
                {
                    $granted_methods[] = $method_role->method_name;
                    $granted_roles[]   = $method_role->system_role_id;
                }
            }
            TTransaction::close();
            
            $data = new stdClass;
            $data->method_name  = $granted_methods;
            $data->granted_role = $granted_roles;
            
            TCombo::reload('form_SystemProgram', 'method_name[]', $combo_options, true);
            
            TFieldList::clear('methods_list');
            if (count($granted_methods) > 0)
            {
                TFieldList::addRows('methods_list', count($granted_methods)-1, 10);
                TForm::sendData('form_SystemProgram', $data, false, true, 400); // 400 ms of timeout after recreate rows!
            }
        }
        
        if (!$found_db_methods)
        {
            TFieldList::clear('methods_list');
            TCombo::reload('form_SystemProgram', 'method_name[]', $combo_options, true);
        }
    }
    
    /**
     * Return all the programs under app/control
     */
    public function getPrograms( $just_new_programs = false )
    {
        try
        {
            TTransaction::open('permission');
            $registered_programs = SystemProgram::getIndexedArray('id', 'controller');
            TTransaction::close();
            
            $entries = array();
            $iterator = new AppendIterator();
            $iterator->append(new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/control'), RecursiveIteratorIterator::CHILD_FIRST));
            $iterator->append(new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/view'),    RecursiveIteratorIterator::CHILD_FIRST));
            
            foreach ($iterator as $arquivo)
            {
                if (substr($arquivo, -4) == '.php')
                {
                    $name = $arquivo->getFileName();
                    $pieces = explode('.', $name);
                    $class = (string) $pieces[0];
                    
                    if ($just_new_programs)
                    {
                        if (!in_array($class, $registered_programs) AND !TApplication::hasDefaultPermissions($class) AND substr($class,0,6) !== 'System')
                        {
                            $entries[$class] = $class;
                        } 
                    }
                    else
                    {
                        $entries[$class] = $class;
                    }
                }
            }
            
            ksort($entries);
            return $entries;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     * @param  $param An array containing the GET ($_GET) parameters
     */
    public function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key=$param['key'];
                
                TTransaction::open($this->database);
                $class = $this->activeRecord;
                $object = new $class($key);
                
                $groups = [];
                
                if( $groups_db = $object->getSystemGroups() )
                {
                    foreach( $groups_db as $group )
                    {
                        $groups[] = $group->id;
                    }
                }
                
                $object->group_list = $groups;
                $this->form->setData($object);
                
                self::fillMethods($object->controller, $object->id);
                
                TTransaction::close();
                
                return $object;
            }
            else
            {
                $this->form->clear(true);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    public function onSave()
    {
        try
        {
            TTransaction::open($this->database);
            
            $data = $this->form->getData();
            
            $object = new SystemProgram;
            $object->id = $data->id;
            $object->name = $data->name;
            $object->controller = $data->controller;
            
            $this->form->validate();
            $object->store();
            $data->id = $object->id;
            $this->form->setData($data);
            
            $object->clearParts();
            
            if( !empty($data->group_list) )
            {
                foreach( $data->group_list as $group_id )
                {
                    $object->addSystemGroup( new SystemGroup($group_id) );
                }
            }
            
            if ( $this->methods_list->getPostData() )
            {
                foreach( $this->methods_list->getPostData() as $method )
                {
                    if (!empty($method->method_name) && !empty($method->granted_role))
                    {
                        $object->addSystemMethodRole($method->method_name, new SystemRole($method->granted_role));
                    }
                }
            }
            
            TTransaction::close();
            $pos_action = new TAction(['SystemProgramList', 'onReload']);
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $pos_action);
            
            return $object;
        }
        catch (Exception $e) // in case of exception
        {
            // get the form data
            $object = $this->form->getData($this->activeRecord);
            $this->form->setData($object);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
