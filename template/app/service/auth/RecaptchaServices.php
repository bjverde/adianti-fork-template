<?php
class RecaptchaServices
{
    /**
     * Validate recaptcha
     */
    public static function validate()
    {
        if ($_REQUEST["g-recaptcha-response"])
        {
            $ini  = AdiantiApplicationConfig::get();
            
            require_once 'app/lib/util/RecaptchaTools.php';
            $tools = new RecaptchaTools($ini['recaptcha']['secret']);
    
            $response = $tools->verifyResponse($_SERVER["REMOTE_ADDR"], $_REQUEST["g-recaptcha-response"]);
    
            if ($response && $response->success)
            {
                return TRUE;
            }
        }
        
        throw new Exception(_t('Invalid captcha'));
    }
    
    /**
     * Reset recaptcha
     */
    public static function reset()
    {
        TScript::create('grecaptcha.reset()', true, 100);
    }
}
