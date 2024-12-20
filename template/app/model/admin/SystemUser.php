<?php
/**
 * SystemUser
 *
 * @version    8.0
 * @package    model
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemUser extends TRecord
{
    const TABLENAME = 'system_users';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    // use SystemChangeLogTrait;
    
    private $frontpage;
    private $unit;
    private $system_user_groups = [];
    private $system_user_programs = [];
    private $system_user_units = [];
    private $system_user_roles = [];

    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('name');
        parent::addAttribute('login');
        parent::addAttribute('password');
        parent::addAttribute('email');
        parent::addAttribute('phone');
        parent::addAttribute('address');
        parent::addAttribute('function_name');
        parent::addAttribute('about');
        parent::addAttribute('frontpage_id');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('active');
        parent::addAttribute('accepted_term_policy');
        parent::addAttribute('accepted_term_policy_at');
        parent::addAttribute('accepted_term_policy_data');
        parent::addAttribute('custom_code');
        parent::addAttribute('otp_secret');
    }
    
    /**
     * Returns the phone trimmed
     */
    public function get_phone_trim()
    {
        return preg_replace("/[^0-9]/", '', (string) $this->phone );
    }
    
    /**
     * Clone the entire object and related ones
     */
    public function cloneUser()
    {
        $groups   = $this->getSystemUserGroups();
        $units    = $this->getSystemUserUnits();
        $programs = $this->getSystemUserPrograms();
        $roles    = $this->getSystemUserRoles();
        
        unset($this->id);
        $this->name .= ' (clone)';
        $this->store();
        
        if ($groups)
        {
            foreach ($groups as $group)
            {
                $this->addSystemUserGroup( $group );
            }
        }
        
        if ($units)
        {
            foreach ($units as $unit)
            {
                $this->addSystemUserUnit( $unit );
            }
        }
        
        if ($programs)
        {
            foreach ($programs as $program)
            {
                $this->addSystemUserProgram( $program );
            }
        }
        
        if ($roles)
        {
            foreach ($roles as $role)
            {
                $this->addSystemUserRole( $role );
            }
        }
    }
    
    /**
     * Returns the frontpage name
     */
    public function get_frontpage_name()
    {
        // loads the associated object
        if (empty($this->frontpage))
            $this->frontpage = new SystemProgram($this->frontpage_id);
    
        // returns the associated object
        return $this->frontpage->name;
    }
    
    /**
     * Returns the frontpage
     */
    public function get_frontpage()
    {
        // loads the associated object
        if (empty($this->frontpage))
            $this->frontpage = new SystemProgram($this->frontpage_id);
    
        // returns the associated object
        return $this->frontpage;
    }
    
   /**
     * Returns the unit
     */
    public function get_unit()
    {
        // loads the associated object
        if (empty($this->unit))
            $this->unit = new SystemUnit($this->system_unit_id);
    
        // returns the associated object
        return $this->unit;
    }
    
    /**
     * Add a Group to the user
     * @param $object Instance of SystemGroup
     */
    public function addSystemUserGroup(SystemGroup $systemgroup)
    {
        $object = new SystemUserGroup;
        $object->system_group_id = $systemgroup->id;
        $object->system_user_id = $this->id;
        $object->store();
    }
    
    /**
     * Add a Unit to the user
     * @param $object Instance of SystemUnit
     */
    public function addSystemUserUnit(SystemUnit $systemunit)
    {
        $object = new SystemUserUnit;
        $object->system_unit_id = $systemunit->id;
        $object->system_user_id = $this->id;
        $object->store();
    }
    
    /**
     * Add a Role to the user
     * @param $object Instance of SystemRole
     */
    public function addSystemUserRole(SystemRole $systemrole)
    {
        $object = new SystemUserRole;
        $object->system_role_id = $systemrole->id;
        $object->system_user_id = $this->id;
        $object->store();
    }
    
    /**
     * Return the user' group's
     * @return Collection of SystemGroup
     */
    public function getSystemUserGroups()
    {
        return parent::loadAggregate('SystemGroup', 'SystemUserGroup', 'system_user_id', 'system_group_id', $this->id);
    }
    
    /**
     * Return the user' unit's
     * @return Collection of SystemUnit
     */
    public function getSystemUserUnits()
    {
        return parent::loadAggregate('SystemUnit', 'SystemUserUnit', 'system_user_id', 'system_unit_id', $this->id);
    }
    
    /**
     * Return the user' role's
     * @return Collection of SystemRole
     */
    public function getSystemUserRoles()
    {
        return parent::loadAggregate('SystemRole', 'SystemUserRole', 'system_user_id', 'system_role_id', $this->id);
    }
    
    /**
     * Add a program to the user
     * @param $object Instance of SystemProgram
     */
    public function addSystemUserProgram(SystemProgram $systemprogram)
    {
        $object = new SystemUserProgram;
        $object->system_program_id = $systemprogram->id;
        $object->system_user_id = $this->id;
        $object->store();
    }
    
    /**
     * Return the user' program's
     * @return Collection of SystemProgram
     */
    public function getSystemUserPrograms()
    {
        return parent::loadAggregate('SystemProgram', 'SystemUserProgram', 'system_user_id', 'system_program_id', $this->id);
    }
    
    /**
     * Get user group ids
     */
    public function getSystemUserGroupIds( $as_string = false )
    {
        $groupids = array();
        $groups = $this->getSystemUserGroups();
        if ($groups)
        {
            foreach ($groups as $group)
            {
                $groupids[] = $group->id;
            }
        }
        
        if ($as_string)
        {
            return implode(',', $groupids);
        }
        
        return $groupids;
    }
    
    /**
     * Get user role ids
     */
    public function getSystemUserRoleIds( $as_string = false )
    {
        $roleids = array();
        $roles = $this->getSystemUserRoles();
        if ($roles)
        {
            foreach ($roles as $role)
            {
                $roleids[] = $role->id;
            }
        }
        
        if ($as_string)
        {
            return implode(',', $roleids);
        }
        
        return $roleids;
    }
    
    /**
     * Get user unit ids
     */
    public function getSystemUserUnitIds( $as_string = false )
    {
        $unitids = array();
        $units = $this->getSystemUserUnits();
        if ($units)
        {
            foreach ($units as $unit)
            {
                $unitids[] = $unit->id;
            }
        }
        
        if ($as_string)
        {
            return implode(',', $unitids);
        }
        
        return $unitids;
    }
    
    /**
     * Get user group names
     */
    public function getSystemUserGroupNames()
    {
        $groupnames = array();
        $groups = $this->getSystemUserGroups();
        if ($groups)
        {
            foreach ($groups as $group)
            {
                $groupnames[] = $group->name;
            }
        }
        
        return implode(',', $groupnames);
    }
    
    /**
     * Get user role codes
     */
    public function getSystemUserRoleCodes( $as_string = false )
    {
        $roleids = array();
        $roles = $this->getSystemUserRoles();
        if ($roles)
        {
            foreach ($roles as $role)
            {
                $roleids[] = $role->custom_code;
            }
        }
        
        if ($as_string)
        {
            return implode(',', $roleids);
        }
        
        return $roleids;
    }
    
    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        SystemUserGroup::where('system_user_id', '=', $this->id)->delete();
        SystemUserUnit::where('system_user_id', '=', $this->id)->delete();
        SystemUserProgram::where('system_user_id', '=', $this->id)->delete();
        SystemUserRole::where('system_user_id', '=', $this->id)->delete();
    }
    
    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        // delete the related System_userSystem_user_group objects
        $id = isset($id) ? $id : $this->id;
        
        SystemUserGroup::where('system_user_id', '=', $id)->delete();
        SystemUserUnit::where('system_user_id', '=', $id)->delete();
        SystemUserProgram::where('system_user_id', '=', $id)->delete();
        SystemUserRole::where('system_user_id', '=', $id)->delete();
        SystemUserOldPassword::where('system_user_id', '=', $id)->delete();
        
        // delete the object itself
        parent::delete($id);
    }
    
    /**
     * Validate user login
     * @param $login String with user login
     */
    public static function validate($login)
    {
        $user = self::newFromLogin($login);
        
        if ($user instanceof SystemUser)
        {
            if ($user->active == 'N')
            {
                throw new Exception(_t('Inactive user'));
            }
        }
        else
        {
            throw new Exception(_t('User not found or wrong password'));
        }
        
        return $user;
    }
    
    /**
     * Hash the user password
     */
    public static function passwordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Authenticate the user
     * @param $login String with user login
     * @param $password String with user password
     * @returns SystemUser if the password matches, otherwise throw Exception
     */
    public static function authenticate($login, $password)
    {
        $user = self::newFromLogin($login);
        
        if (substr($user->password, 0, 1) == "$")
        {
            // password_hash generated
            if (password_verify($password, $user->password) )
            {
                if (password_needs_rehash($user->password, PASSWORD_DEFAULT))
                {
                    $user->password = self::passwordHash($password);
                    $user->store();
                }
                return $user;
            }
        }
        else
        {
            // OLD MD5 support and conversion
            if (hash_equals($user->password, md5($password)))
            {
                $user->password = self::passwordHash($password);
                $user->store();
                return $user;
            }
        }
        
        throw new Exception(_t('User not found or wrong password'));
    }
    
    /**
     * Returns a SystemUser object based on its login
     * @param $login String with user login
     */
    static public function newFromLogin($login)
    {
        return SystemUser::where('login', '=', $login)->first();
    }
    
    /**
     * Returns a SystemUser object based on its e-mail
     * @param $email String with user email
     */
    static public function newFromEmail($email)
    {
        return SystemUser::where('email', '=', $email)->first();
    }
    
    /**
     * Returns a list of user's SystemProgram collection
     */
    public function getAllPrograms()
    {
        $programs = [];
        
        foreach( $this->getSystemUserGroups() as $group )
        {
            foreach( $group->getSystemPrograms() as $program )
            {
                $programs[] = $program;
            }
        }
                
        foreach( $this->getSystemUserPrograms() as $program )
        {
            $programs[] = $program;
        }
        
        return $programs;
    }
    
    /**
     * Return the programs the user has permission to run
     */
    public function getPrograms()
    {
        $programs = $this->getAllPrograms();
        $filtered_programs = [];
        
        foreach ($programs as $program)
        {
            $filtered_programs[$program->controller] = true;
        }
        
        return $filtered_programs;
    }
    
    /**
     * Return the programs the user has permission to run
     */
    public function getMethods()
    {
        $user_roles = $this->getSystemUserRoleIds();
        $programs   = $this->getAllPrograms();
        $filtered_methods = [];
        
        foreach ($programs as $program)
        {
            $filtered_methods[$program->controller] = [];
            
            $methods = $program->getSystemMethodRoles();
            
            foreach ($methods as $method)
            {
                $filtered_methods[$program->controller][$method->method_name] = false;
            }
            
            foreach ($methods as $method)
            {
                if (in_array($method->system_role_id, $user_roles)) // check user role
                {
                    $filtered_methods[$program->controller][$method->method_name] = true;
                }
            }
        }
        
        return $filtered_methods;
    }
    
    /**
     * Return the programs the user has permission to run
     */
    public function getProgramsList()
    {
        $programs = array();
        
        foreach( $this->getSystemUserGroups() as $group )
        {
            foreach( $group->getSystemPrograms() as $prog )
            {
                $programs[$prog->controller] = $prog->name;
            }
        }
                
        foreach( $this->getSystemUserPrograms() as $prog )
        {
            $programs[$prog->controller] = $prog->name;
        }
        
        asort($programs);
        return $programs;
    }
    
    /**
     * Check if the user is within a group
     */
    public function checkInGroup( SystemGroup $group )
    {
        $user_groups = array();
        foreach( $this->getSystemUserGroups() as $user_group )
        {
            $user_groups[] = $user_group->id;
        }
    
        return in_array($group->id, $user_groups);
    }
    
    /**
     * Return user inside these groups
     */
    public static function getInGroups( $groups )
    {
        $collection = [];
        $users = self::all();
        if ($users)
        {
            foreach ($users as $user)
            {
                foreach ($groups as $group)
                {
                    if ($user->checkInGroup($group))
                    {
                        $collection[] = $user;
                    }
                }
            }
        }
        return $collection;
    }
}
