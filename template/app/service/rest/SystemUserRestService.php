<?php
/**
 * SystemUser REST Service
 */
class SystemUserRestService extends AdiantiRecordService
{
    const DATABASE      = 'permission';
    const ACTIVE_RECORD = 'SystemUser';

    /**
     * Store the objects into the database with hashed password and active status by default
     * @param $param HTTP parameter
     */
    public function store($param)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        
        TTransaction::open($database);
        
        $object = new $activeRecord;
        $pk = $object->getPrimaryKey();
        $param['data'][$pk] = $param['data']['id'] ?? NULL;
        
        $data = (array) $param['data'];
        
        // Securely hash the password if provided
        if (!empty($data['password']))
        {
            $data['password'] = SystemUser::passwordHash($data['password']);
        }
        
        // Default active to 'Y' if not specified
        if (empty($data['active']))
        {
            $data['active'] = 'Y';
        }
        
        $object->fromArray($data);
        $object->store();
        
        // Associate with default group configured in application.php
        if (empty($param['data']['id']))
        {
            $ini = AdiantiApplicationConfig::get();
            $default_group_id = $ini['permission']['default_groups'] ?? 2;
            $group = new SystemGroup($default_group_id);
            $object->addSystemUserGroup($group);
        }
        
        TTransaction::close();
        
        return $object->toArray();
    }
}