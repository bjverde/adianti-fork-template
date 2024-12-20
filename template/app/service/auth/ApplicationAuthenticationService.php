<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class ApplicationAuthenticationService
{
    /**
     * Authenticate user and load permissions
     */
    public static function authenticate($login, $password, $load_session_vars = true)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        TTransaction::open('permission');
        $user = SystemUser::validate( $login );
        
        // call loaders to made available this attrs outside transactions
        $user->get_unit();
        $user->get_frontpage();
        
        if ($user)
        {
            if (!empty($ini['permission']['auth_service']) and class_exists($ini['permission']['auth_service']))
            {
                $service = $ini['permission']['auth_service'];
                $service::authenticate( $login, $password );
            }
            else
            {
                SystemUser::authenticate( $login, $password );
            }

            if ($load_session_vars)
            {
                self::loadSessionVars($user);
            }
            
            TTransaction::close();
            
            return $user;
        }
        
        TTransaction::close();
    }
    
    /**
     * Set Unit when multi unit is turned on
     * @param $unit_id Unit id
     */
    public static function setUnit($unit_id)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        if (!empty($ini['general']['multiunit']) && $ini['general']['multiunit'] == '1' && !empty($unit_id))
        {
            TTransaction::openFake('permission');
            $is_valid = in_array($unit_id, SystemUser::newFromLogin( TSession::getValue('login') )->getSystemUserUnitIds());
            TTransaction::close();
            
            if (!$is_valid)
            {
                throw new Exception(_t('Unauthorized access to that unit'));
            }
            
            TSession::setValue('userunitid',   $unit_id );
            TSession::setValue('userunitname', SystemUnit::findInTransaction('permission', $unit_id)->name);
            TSession::setValue('userunitcustomcode', SystemUnit::findInTransaction('permission', $unit_id)->custom_code);
            
            if (!empty($ini['general']['multi_database']) and $ini['general']['multi_database'] == '1')
            {
                TSession::setValue('unit_database', SystemUnit::findInTransaction('permission', $unit_id)->connection_name );
            }
        }
    }
    
    /**
     * Set language when multi language is turned on
     * @param $lang_id Language id
     */
    public static function setLang($lang_id)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        if (!empty($ini['general']['multi_lang']) and $ini['general']['multi_lang'] == '1' and !empty($lang_id))
        {
            TSession::setValue('user_language', $lang_id );
        }
    }
    
    /**
     * Load user session variables
     */
    public static function loadSessionVars($user, $open_transaction = false)
    {
        if ($open_transaction)
        {
            TTransaction::open('permission');
        }
        
        $programs = $user->getPrograms();
        $programs['LoginForm'] = TRUE;
        
        TSession::setValue('need_renewal_password', false);
        TSession::setValue('logged', TRUE);
        TSession::setValue('login', $user->login);
        TSession::setValue('userid', $user->id);
        TSession::setValue('usergroupids', $user->getSystemUserGroupIds());
        TSession::setValue('userunitids', $user->getSystemUserUnitIds());
        TSession::setValue('userroleids', $user->getSystemUserRoleIds());
        TSession::setValue('userroles', $user->getSystemUserRoleCodes());
        TSession::setValue('username', $user->name);
        TSession::setValue('usermail', $user->email);
        TSession::setValue('usercustomcode', $user->custom_code);
        TSession::setValue('frontpage', '');
        TSession::setValue('programs',$programs);
        TSession::setValue('methods', $user->getMethods());
        
        if (!empty($user->unit))
        {
            TSession::setValue('userunitid',$user->unit->id);
            TSession::setValue('userunitname', $user->unit->name);
            TSession::setValue('userunitcustomcode', $user->unit->custom_code);
        }
        
        if ($open_transaction)
        {
            TTransaction::close();
        }
        
        if (TAPCache::enabled())
        {
            TAPCache::setValue('session_'.TSession::getValue('login'), session_id());
        }
    }
    
    /**
     * Authenticate user from JWT token
     */
    public static function fromToken($token)
    {
        $ini = AdiantiApplicationConfig::get();
        $key = APPLICATION_NAME . $ini['general']['seed'];
        
        if (empty($ini['general']['seed']))
        {
            throw new Exception('Application seed not defined');
        }
        
        $token = (array) JWT::decode($token, new Key($key, 'HS256'));
        
        $login   = $token['user'];
        $userid  = $token['userid'];
        $name    = $token['username'];
        $email   = $token['usermail'];
        $expires = $token['expires'];
        
        if ($expires < strtotime('now'))
        {
            throw new Exception('Token expired. This operation is not allowed');
        }
        
        TSession::setValue('logged',   TRUE);
        TSession::setValue('login',    $login);
        TSession::setValue('userid',   $userid);
        TSession::setValue('username', $name);
        TSession::setValue('usermail', $email);
    }
    
    /**
     * Check multi session
     */
    public static function checkMultiSession()
    {
        $ini = AdiantiApplicationConfig::get();
        
        if (!TSession::getValue('logged'))
        {
            return;
        }
        
        if (!isset($ini['general']['concurrent_sessions']))
        {
            return;
        }
        
        if ($ini['general']['concurrent_sessions'] == '1')
        {
            return;
        }
        
        if (!TAPCache::enabled())
        {
            new TMessage('error', AdiantiCoreTranslator::translate('PHP Module not found'). ': APCU');
            return;
        }
        
        $current_session = TAPCache::getValue('session_'.TSession::getValue('login'));
        if ($current_session)
        {
            if ($current_session !== session_id())
            {
                SystemAccessLogService::registerLogout();
                TSession::freeSession();
                
                $class = 'LoginForm';
                AdiantiCoreApplication::gotoPage($class, 'onLoad');
                return;
            }
        }
    }
}
