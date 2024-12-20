<?php
class SystemUserExampleService
{
    public static function run()
    {
        TTransaction::open('permission');
        $users = SystemUser::where('active', '=', 'Y')->load();
        TTransaction::close();
        
        if ($users)
        {
            foreach ($users as $user)
            {
                echo $user->id;
                // MailService::send($user->email, 'Title', 'Message');
            }
        }
    }
}
