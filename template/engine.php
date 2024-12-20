<?php
require_once 'init.php';

// AdiantiCoreApplication::setRouter(array('AdiantiRouteTranslator', 'translate'));
AdiantiCoreApplication::setActionVerification(['SystemPermission', 'checkPermission']);

class TApplication extends AdiantiCoreApplication
{
    public static function run($debug = null)
    {
        new TSession;
        ApplicationAuthenticationService::checkMultiSession();
        ApplicationTranslator::setLanguage( TSession::getValue('user_language'), true ); // multi-lang
        
        if ($_REQUEST)
        {
            $ini = AdiantiApplicationConfig::get();
            
            $class  = isset($_REQUEST['class']) ? $_REQUEST['class'] : '';
            $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';
            $public = in_array($class, !empty($ini['permission']['public_classes']) ? $ini['permission']['public_classes'] : []);
            $debug  = is_null($debug)? $ini['general']['debug'] : $debug;
            
            if (TSession::getValue('logged')) // logged
            {
                if ( SystemPermission::checkPermission($class, $method) )
                {
                    parent::run($debug);
                }
                else if (self::hasDefaultPermissions($class))
                {
                    parent::run($debug);
                }
                else
                {
                    http_response_code(401);
                    new TMessage('error', _t('Permission denied') );
                }
            }
            else if ($class == 'LoginForm' || $public )
            {
                parent::run($debug);
            }
            else
            {
                http_response_code(401);
                new TMessage('error', _t('Permission denied'), new TAction(array('LoginForm','onLogout')) );
            }
        }
    }
    
    /**
     * Return default programs for logged users
     */
    public static function hasDefaultPermissions($class)
    {
        $default_permissions = ['Adianti\Base\TStandardSeek' => TRUE,
                                'LoginForm' => TRUE,
                                'SystemPermissionController' => TRUE,
                                'AdiantiMultiSearchService' => TRUE,
                                'AdiantiUploaderService' => TRUE,
                                'AdiantiAutocompleteService' => TRUE,
                                'SystemDocumentUploaderService' => TRUE,
                                'SystemMessageDropdown' => TRUE,
                                'SystemNotificationDropdown' => TRUE,
                                'EmptyPage' => TRUE,
                                'SearchBox' => TRUE];
        
        return (isset($default_permissions[$class]) && $default_permissions[$class]);
    } 
}

TApplication::run();
