<?php
/**
 * SystemHookService
 *
 * @version    8.7
 * @package    service
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemHookService
{
    /**
     * Run Hook
     */
    private static function runHook($name)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        if (!empty($ini['hooks'][$name]) && is_array($ini['hooks'][$name]))
        {
            foreach ($ini['hooks'][$name] as $hook)
            {
                if (is_callable($hook))
                {
                    $hook([]);
                }
            }
        }
    }
    
    /**
     * Execute onAfterLogin hook
     */
    public static function onAfterLogin()
    {
        self::runHook('after_login');
    }
    
    /**
     * Execute onBeforeLogout hook
     */
    public static function onBeforeLogout()
    {
        self::runHook('before_logout');
    }
    
    /**
     * Execute onAfterUserCreation hook
     */
    public static function onAfterUserCreation($user)
    {
        $ini = AdiantiApplicationConfig::get();
        
        if (!empty($ini['hooks']['after_user_creation']))
        {
            TSession::withContext('creating_user', function() use ($user) {
                ApplicationAuthenticationService::loadMiniSessionVars($user);
                self::runHook('after_user_creation');
            });
        }
    }
    
    /**
     * Execute onAfterUnitCreation hook
     */
    public static function onAfterUnitCreation($unit)
    {
        $ini = AdiantiApplicationConfig::get();
        
        if (!empty($ini['hooks']['after_unit_creation']))
        {
            TSession::withContext('creating_unit', function() use ($unit) {
                ApplicationAuthenticationService::loadUnitMiniSessionVars($unit);
                self::runHook('after_unit_creation');
            });
        }
    }
}