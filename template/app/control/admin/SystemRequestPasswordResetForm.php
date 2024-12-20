<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

/**
 * SystemRequestPasswordResetForm
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemRequestPasswordResetForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();
        
        $this->style = 'clear:both';
        
        // creates the form
        $this->form = new TModalForm('form_login');
        $this->form->setFormTitle( _t('Reset password') );
        
        // create the form fields
        $login = new TEntry('login');
        
        // define the sizes
        $login->setSize('100%');
        
        $login->placeholder = _t('User');
        $this->form->addRowField(_t('Login'), $login, true);
        
        $this->form->addAction(_t('Send'), new TAction(array($this, 'onRequest')), '');
        
        parent::add($this->form);
    }
    
    /**
     *
     */
    public function onLoad($param)
    {
    }
    
    /**
     * Authenticate the User
     */
    public static function onRequest($param)
    {
        $ini = AdiantiApplicationConfig::get();
        
        try
        {
            if ($ini['permission']['reset_password'] !== '1')
            {
                throw new Exception( _t('The password reset is disabled') );
            }
            
            if (empty($ini['general']['seed']) OR $ini['general']['seed'] == 's8dkld83kf73kf094')
            {
                throw new Exception(_t('A new seed is required in the application.ini for security reasons'));
            }
            
            TTransaction::open('permission');
            
            $login = $param['login'];
            $user  = SystemUser::newFromLogin($login);
            
            if ($user instanceof SystemUser)
            {
                if ($user->active == 'N')
                {
                    throw new Exception(_t('Inactive user'));
                }
                else
                {
                    $key = APPLICATION_NAME . $ini['general']['seed'];
                    
                    $token = array(
                        "user" => $user->login,
                        "expires" => strtotime("+ 3 hours")
                    );
                    
                    $jwt = JWT::encode($token, $key, 'HS256');
                    
                    $referer = $_SERVER['HTTP_REFERER'];
                    $url = substr($referer, 0, strpos($referer, 'index.php'));
                    $url .= 'index.php?class=SystemPasswordResetForm&method=onLoad&jwt='.$jwt;
                    
                    $replaces = [];
                    $replaces['name'] = $user->name;
                    $replaces['link'] = $url;
                    $html = new THtmlRenderer('app/resources/system/admin/reset_password.html');
                    $html->enableSection('main', $replaces);
                    
                    MailService::send( $user->email, _t('Password reset'), $html->getContents(), 'html' );
                    new TMessage('info', _t('Message sent successfully'));
                }
            }
            else
            {
                throw new Exception(_t('User not found'));
            }
        }
        catch (Exception $e)
        {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
}
