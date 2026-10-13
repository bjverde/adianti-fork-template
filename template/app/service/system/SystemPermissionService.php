<?php
/**
 * SystemPermissionService
 *
 * @version    8.7
 * @package    service
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPermissionService
{
    public static function reloadPermissions($goto = true)
    {
        TTransaction::open('permission');
        $user = SystemUser::newFromLogin( TSession::getValue('login') );
        
        if ($user)
        {
            $unit_id = TSession::getValue('userunitid');
            ApplicationAuthenticationService::loadSessionVars($user);
            
            if (!empty($unit_id))
            {
                ApplicationAuthenticationService::setUnit( $unit_id );
            }
            
            if ($goto)
            {
                $frontpage = $user->frontpage;
                if ($frontpage instanceof SystemProgram AND $frontpage->controller)
                {
                    TApplication::gotoPage($frontpage->controller); // reload
                }
                else
                {
                    TApplication::gotoPage('EmptyPage'); // reload
                }
            }
        }
        TTransaction::close();
    }
}
