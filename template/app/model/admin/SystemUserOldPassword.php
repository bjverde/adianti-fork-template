<?php
/**
 * SystemUserOldPassword
 *
 * @version    8.0
 * @package    model
 * @subpackage admin
 * @author     Lucas Tomasi
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemUserOldPassword extends TRecord
{
    const TABLENAME  = 'system_user_old_password';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'max'; // {max, serial}
    const CREATEDAT  = 'created_at';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('password');
        parent::addAttribute('created_at');
    }
    
    /**
     * Return the renewal interval
     */
    public static function getDays()
    {
        $ini  = AdiantiApplicationConfig::get();
        
        return $ini['general']['password_renewal_interval'];
    }
    
    /**
     * Register password change
     */
    public static function register($userId, $newPassword)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        if (empty($ini['general']['password_renewal_interval']))
        {
            return true;
        }
        
        $days = $ini['general']['password_renewal_interval'];

        if ($days <= 0)
        {
            return true;
        }
        
        $self = new self;
        $self->system_user_id = $userId;
        $self->password = md5($newPassword);
        $self->store();
    }
    
    /**
     * Validate password
     */
    public static function validate($userId, $newPassword)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        if (empty($ini['general']['password_renewal_interval']))
        {
            return true;
        }
        
        $days = $ini['general']['password_renewal_interval'];

        if ($days <= 0)
        {
            return true;
        }

        $lastPasswords = self::where('system_user_id', '=', $userId)->load();
        $user = SystemUser::find($userId);

        if (md5($newPassword) == $user->password || (password_verify($newPassword, $user->password) ))
        {
            throw new Exception(_t('You have already registered this password'));
        }

        if ($lastPasswords)
        {
            foreach($lastPasswords as $lastPassword)
            {
                if (md5($newPassword) == $lastPassword->password)
                {
                    throw new Exception(_t('You have already registered this password'));
                }
            }
        }

        return true;
    }
    
    /**
     * Check if needs renewal
     */
    public static function needRenewal($userId)
    {
        $ini  = AdiantiApplicationConfig::get();
        
        if (empty($ini['general']['password_renewal_interval']))
        {
            return FALSE;
        }
        
        $days = $ini['general']['password_renewal_interval'];

        if ($days <= 0)
        {
            return FALSE;
        }

        $last = self::where('system_user_id', '=', $userId)->orderBy('created_at', 'desc')->first();

        if (! $last)
        {
            return true;
        }

        $last_create = new DateTime($last->created_at);
        $date_now = new DateTime(date('Y-m-d H:i:s'));
        $interval = $last_create->diff($date_now);

        $days_diff = $interval->days;

        return ($days_diff >= $days);
    }
}
