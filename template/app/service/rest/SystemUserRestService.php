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
        
        $pk = (new $activeRecord)->getPrimaryKey();
        $id = $param['data']['id'] ?? ($param['data'][$pk] ?? NULL);
        
        // Load existing record if id is present, preventing partial updates from wiping out other columns
        $object = new $activeRecord($id);
        
        $data = (array) $param['data'];
        
        // Securely hash the password if provided
        if (!empty($data['password']))
        {
            $data['password'] = SystemUser::passwordHash($data['password']);
        }
        
        // Default active to 'Y' if not specified and creating a new record
        if (empty($id) && empty($data['active']))
        {
            $data['active'] = 'Y';
        }
        
        $object->fromArray($data);
        $object->store();
        
        // Associate with default group configured in application.php
        if (empty($id))
        {
            $ini = AdiantiApplicationConfig::get();
            $default_group_id = $ini['permission']['default_groups'] ?? 2;
            $group = new SystemGroup($default_group_id);
            $object->addSystemUserGroup($group);
        }
        
        TTransaction::close();
        
        return $object->toArray();
    }

    /**
     * Change the password of an existing user securely
     * @param $param HTTP parameter
     */
    public static function changePassword($param)
    {
        $id = $param['id'] ?? ($param['data']['id'] ?? null);
        $newPassword = $param['new_password'] ?? ($param['password'] ?? ($param['data']['password'] ?? null));
        
        if (empty($id))
        {
            throw new Exception("ID do usuário é obrigatório.");
        }
        
        if (empty($newPassword))
        {
            throw new Exception("Nova senha é obrigatória.");
        }
        
        TTransaction::open(static::DATABASE);
        
        $user = new SystemUser($id);
        
        if (empty($user->id))
        {
            throw new Exception("Usuário não encontrado.");
        }
        
        if ($user->active == 'N')
        {
            throw new Exception("Usuário inativo.");
        }
        
        // Security check: ensure the logged-in user is changing their own password, or is the admin
        $loggedUserId = TSession::getValue('userid');
        $loggedUserLogin = TSession::getValue('login');
        
        if ($loggedUserLogin !== 'admin' && $loggedUserId != $user->id)
        {
            throw new Exception("Permissão negada para alterar a senha deste usuário.");
        }
        
        // Optionally verify current password if passed in 'current_password'
        $currentPassword = $param['current_password'] ?? ($param['data']['current_password'] ?? null);
        if (!empty($currentPassword))
        {
            SystemUser::authenticate($user->login, $currentPassword);
        }
        
        // Validate strong password policy if enabled
        $ini = AdiantiApplicationConfig::get();
        if (isset($ini['general']['validate_strong_pass']) && $ini['general']['validate_strong_pass'] == '1')
        {
            (new TStrongPasswordValidator)->validate(_t('Password'), $newPassword);
        }
        
        // Enforce old password validations and record usage history
        SystemUserOldPassword::validate($user->id, $newPassword);
        SystemUserOldPassword::register($user->id, $newPassword);
        
        $user->password = SystemUser::passwordHash($newPassword);
        $user->store();
        
        TTransaction::close();
        
        return [
            'status' => 'success',
            'message' => 'Senha alterada com sucesso.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'login' => $user->login,
                'email' => $user->email
            ]
        ];
    }
}