<?php
class SystemUserCliService
{
    /**
     * @param $request HTTP request
     */
    public static function create( $request )
    {
        /*
        
        TTransaction::open('permission');
        $response = [];
        
        $request['password'] = SystemUser::passwordHash($request['password']);
        
        $user = new SystemUser;
        $user->fromArray($request);
        $user->store();
        TTransaction::close();
        return $user;
        
        */
    }
}