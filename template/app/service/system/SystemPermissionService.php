<?php
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
