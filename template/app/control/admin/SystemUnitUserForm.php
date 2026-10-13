<?php
/**
 * SystemUnitUserForm
 *
 * @version    8.7
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemUnitUserForm extends TPage
{
    protected $form; // form
    protected $group_list;
    protected $role_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
        parent::setTargetContainer('adianti_right_panel');
        $ini  = AdiantiApplicationConfig::get();
        $add_fields = (!isset($ini['permission']['additional_user_fields']) || $ini['permission']['additional_user_fields'] == '1');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_System_user');
        $this->form->setFormTitle( _t('User') );
        $this->form->enableClientValidation();
        
        // create the form fields
        $id            = new TEntry('id');
        $name          = new TEntry('name');
        $login         = new TEntry('login');
        $password      = new TPassword('password');
        $repassword    = new TPassword('repassword');
        $email         = new TEntry('email');
        $frontpage_id  = new TDBUniqueSearch('frontpage_id', 'permission', 'SystemProgram', 'id', 'name', 'name');
        
        if ($add_fields)
        {
            $phone         = new TEntry('phone');
            $address       = new TEntry('address');
            $function_name = new TEntry('function_name');
            $about         = new TEntry('about');
        }
        
        $custom_code   = new TEntry('custom_code');
        
        $password->disableAutoComplete();
        $repassword->disableAutoComplete();
        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'fa:check');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        
        // define the sizes
        $id->setSize('50%');
        $name->setSize('100%');
        $login->setSize('100%');
        $password->setSize('100%');
        $repassword->setSize('100%');
        $email->setSize('100%');
        $frontpage_id->setSize('100%');
        $frontpage_id->setMinLength(1);
        
        // outros
        $id->setEditable(false);
        
        // validations
        $name->addValidation(_t('Name'), new TRequiredValidator);
        $login->addValidation('Login', new TRequiredValidator);
        $email->addValidation('Email', new TEmailValidator);
        
        $this->form->addFields( [new TLabel('ID')], [$id],  [new TLabel(_t('Name'))], [$name] );
        $this->form->addFields( [new TLabel(_t('Login'))], [$login],  [new TLabel(_t('Email'))], [$email] );
        if ($add_fields)
        {
            $this->form->addFields( [new TLabel(_t('Address'))], [$address],  [new TLabel(_t('Phone'))], [$phone] );
            $this->form->addFields( [new TLabel(_t('Function'))], [$function_name],  [new TLabel(_t('About'))], [$about] );
        }
        
        $this->form->addFields( [new TLabel(_t('Front page'))], [$frontpage_id] );
        $this->form->addFields( [new TLabel(_t('Password'))], [$password],  [new TLabel(_t('Password confirmation'))], [$repassword] );
        $this->form->addFields( [new TLabel(_t('Custom code'))], [$custom_code] );
        
        $subform = new BootstrapFormBuilder('form_System_user_subform');
        $subform->setFieldSizes('100%');
        $subform->setProperty('style', 'border:none');
        
        $groups_criteria = new TCriteria;
        if (!empty($ini['permission']['admin_groups']))
        {
            $groups_criteria->add(new TFilter('id', 'not in', $ini['permission']['admin_groups']));
        }
        if (!empty($ini['permission']['allowed_unit_groups']))
        {
            $groups_criteria->add(new TFilter('id', 'in', $ini['permission']['allowed_unit_groups']));
        }
        $subform->appendPage( _t('Groups') );
        $this->group_list = new TDBCheckList('group_list', 'permission', 'SystemGroup', 'id', 'name', 'id', $groups_criteria);
        $this->group_list->makeScrollable();
        $this->group_list->setHeight(210);
        $subform->addFields( [$this->group_list] );
        
        
        $subform->appendPage( _t('Roles') );
        $this->role_list = new TDBCheckList('role_list', 'permission', 'SystemRole', 'id', 'name');
        $this->role_list->makeScrollable();
        $this->role_list->setHeight(210);
        
        $subform->addFields( [$this->role_list] );
        
        $this->form->addContent([$subform]);
        
        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
    }

    /**
     * Validate if the user is within the master unit
     */
    private function validateUserUnitIsolation($user)
    {
        if ((int) $user->system_unit_id !== (int) TSession::getValue('userunitid'))
        {
            throw new Exception(_t('Permission denied'), 901);
        }
    }
    
    /**
     * Save user data
     */
    public function onSave($param)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        try
        {
            // open a transaction with database 'permission'
            TTransaction::open('permission');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $object = new SystemUser;
            $object->fromArray( (array) $data );
            
            $object->system_unit_id = TSession::getValue('userunitid'); // keep main unit untouchable
            $this->validateUserUnitIsolation($object);
            
            unset($object->accepted_term_policy);

            $senha = $object->password;
            
            if( empty($object->login) )
            {
                throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Login')));
            }
            
            $new_user = false;
            if( empty($object->id) )
            {
                $new_user = true;
                
                if (SystemUser::newFromLogin($object->login) instanceof SystemUser)
                {
                    throw new Exception(_t('An user with this login is already registered'));
                }
                
                if (SystemUser::newFromEmail($object->email) instanceof SystemUser)
                {
                    throw new Exception(_t('An user with this e-mail is already registered'));
                }
                
                if ( empty($object->password) )
                {
                    throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Password')));
                }
                
                $object->active = 'Y';
            }
            else
            {
                if (SystemUser::where('login', '=', $object->login)->where('id', '<>', $object->id)->first() instanceof SystemUser)
                {
                    throw new Exception(_t('An user with this login is already registered'));
                }
            }
            
            if ( $object->password )
            {
                if (isset($ini['general']['validate_strong_pass']) && $ini['general']['validate_strong_pass'] == '1')
                {
                    (new TStrongPasswordValidator)->validate(_t('Password'), $object->password);
                }
                
                if( $object->password !== $param['repassword'] )
                {
                    throw new Exception(_t('The passwords do not match'));
                }
                
                $object->password = SystemUser::passwordHash($object->password);

                if ($object->id)
                {
                    SystemUserOldPassword::validate($object->id, $object->password);
                }
            }
            else
            {
                unset($object->password);
            }
            
            $object->store();

            if ($object->password)
            {
                SystemUserOldPassword::register($object->id, $object->password);
            }
            $object->clearParts();
            
            if( !empty($data->group_list) )
            {
                foreach( $data->group_list as $group_id )
                {
                    $object->addSystemUserGroup( new SystemGroup($group_id) );
                }
            }
            
            if( !empty($data->role_list) )
            {
                foreach( $data->role_list as $role_id )
                {
                    $object->addSystemUserRole( new SystemRole($role_id) );
                }
            }
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_System_user', $data);
            
            if ($new_user)
            {
                SystemHookService::onAfterUserCreation($object);
            }
            
            // close the transaction
            TTransaction::close();
            
            TToast::show('info', _t('Record saved'));
            AdiantiCoreApplication::loadPage('SystemUnitUsersList', 'onReload');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    public function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'permission'
                TTransaction::open('permission');
                
                // instantiates object System_user
                $object = new SystemUser($key);
                
                $this->validateUserUnitIsolation($object);
                
                unset($object->password);
                
                $groups = array();
                
                if( $groups_db = $object->getSystemUserGroups() )
                {
                    foreach( $groups_db as $group )
                    {
                        $groups[] = $group->id;
                    }
                }
                
                $role_ids = array();
                foreach ($object->getSystemUserRoles() as $role)
                {
                    $role_ids[] = $role->id;
                }
                
                $object->group_list   = $groups;
                $object->role_list    = $role_ids;
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            if ($e->getCode() == 901)
            {
                TScript::create("Template.closeRightPanel()");
            }
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
