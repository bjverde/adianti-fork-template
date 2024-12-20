<?php
/**
 * SystemProgram
 *
 * @version    8.0
 * @package    model
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemProgram extends TRecord
{
    const TABLENAME  = 'system_program';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'max'; // {max, serial}
    
    // use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('name');
        parent::addAttribute('controller');
    }
    
    /**
     * Find program by controller
     */
    public static function findByController($controller)
    {
        $objects = SystemProgram::where('controller', '=', $controller)->load();
        if (count($objects)>0)
        {
            return $objects[0];
        }
    }
    
    /**
     * Add a SystemGroup to the SystemProgram
     * @param $object Instance of SystemGroup
     */
    public function addSystemGroup(SystemGroup $systemgroup)
    {
        $object = new SystemGroupProgram;
        $object->system_program_id = $this->id;
        $object->system_group_id = $systemgroup->id;
        $object->store();
    }
    
    /**
     * Add a SystemGroup to the SystemProgram
     * @param $object Instance of SystemGroup
     */
    public function addSystemMethodRole($method_name, SystemRole $systemrole)
    {
        $object = new SystemProgramMethodRole;
        $object->system_program_id = $this->id;
        $object->system_role_id = $systemrole->id;
        $object->method_name = $method_name;
        $object->store();
    }
    
    /**
     * Return the SystemGroup's
     * @return Collection of SystemGroup
     */
    public function getSystemGroups()
    {
        $system_groups = array();
        
        // load the related System_program objects
        $system_group_system_programs = SystemGroupProgram::where('system_program_id', '=', $this->id)->load();
        
        if ($system_group_system_programs)
        {
            foreach ($system_group_system_programs as $system_group_system_program)
            {
                $system_groups[] = new SystemGroup( $system_group_system_program->system_group_id );
            }
        }
        
        return $system_groups;
    }
    
    /**
     * Return the program's methods
     * @return Collection of SystemProgramMethodRole
     */
    public function getSystemMethodRoles()
    {
        return SystemProgramMethodRole::where('system_program_id', '=', $this->id)->load();
    }
    
    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        // delete the related objects
        SystemGroupProgram::where('system_program_id', '=', $this->id)->delete();
        SystemProgramMethodRole::where('system_program_id', '=', $this->id)->delete();
    }
    
    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        // delete the related System_groupSystem_program objects
        $id = isset($id) ? $id : $this->id;
        
        SystemGroupProgram::where('system_program_id', '=', $id)->delete();
        SystemUserProgram::where('system_program_id', '=', $id)->delete();
        SystemProgramMethodRole::where('system_program_id', '=', $id)->delete();
        
        // delete the object itself
        parent::delete($id);
    }
}
