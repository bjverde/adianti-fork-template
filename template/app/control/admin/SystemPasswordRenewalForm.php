<?php

use Adianti\Control\TAction;
use Adianti\Registry\TSession;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Form\TLabel;

/**
 * SystemPasswordRenewalForm
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Lucas Tomasi
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPasswordRenewalForm extends TPage
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
        $this->form = new BootstrapFormBuilder('form_password_renewal');
        $this->form->setFormTitle( _t('Renewal password') );
        
        // create the form fields
        $password1 = new TPassword('password1');
        $password2 = new TPassword('password2');
        
        // define the sizes
        $password1->setSize('70%', 40);
        $password2->setSize('70%', 40);
        
        $locker = '<span style="float:left;margin-left:44px;height:35px;" class="login-avatar"><span class="fa fa-lock"></span></span>';
        $password1->style = 'height:35px; font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';
        $password2->style = 'height:35px; font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';
        
        $password1->placeholder = _t('Password');
        $password2->placeholder = _t('Password confirmation');
        
        $this->form->addContent([TElement::tag('div', _t('You need to renew your password, as we have identified that you have not changed it for more than ^1 days', SystemUserOldPassword::getDays()), ['style' => 'margin-bottom: 15px; font-size: 16px; text-align: center;'])]);
        $this->form->addFields( [$locker, $password1] );
        $this->form->addFields( [$locker, $password2] );
        
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onRenewal')), '');
        $btn->class = 'btn btn-primary';
        $btn->style = 'height: 40px;width: 90%;display: block;margin: auto;font-size:17px;';
        
        $wrapper = new TElement('div');
        $wrapper->style = 'margin:auto; margin-top:100px;max-width:460px;';
        $wrapper->id    = 'login-wrapper';
        $wrapper->add($this->form);

        if (! TSession::getValue('need_renewal_password'))
        {
            new TMessage('error', _t('Permission denied'), new TAction(['LoginForm', 'onLoad']));
        }
        
        // add the form to the page
        parent::add($wrapper);
    }

    /**
     * Authenticate the User
     */
    public static function onRenewal($param)
    {
        try
        {
            $ini = AdiantiApplicationConfig::get();
            
            if (empty($param['password1']))
            {
                throw new Exception('Senha vazia');
            }
            
            if( $param['password1'] !== $param['password2'] )
            {
                throw new Exception(_t('The passwords do not match'));
            }
            
            if (isset($ini['general']['validate_strong_pass']) && $ini['general']['validate_strong_pass'] == '1')
            {
                (new TStrongPasswordValidator)->validate(_t('Password'), $param['password1']);
            }
            
            if (! TSession::getValue('need_renewal_password'))
            {
                throw new Exception('Permission denied');
            }

            TTransaction::open('permission');
            $user  = SystemUser::find(TSession::getValue('userid'));
            
            if ($user instanceof SystemUser)
            {
                if ($user->active == 'N')
                {
                    throw new Exception(_t('Inactive user'));
                }
                else
                {
                    SystemUserOldPassword::validate($user->id, $param['password1']);
                    SystemUserOldPassword::register($user->id, $param['password1']);
                    $user->password = SystemUser::passwordHash($param['password1']);
                    $user->store();
                    
                    TSession::clear();
                    new TMessage('info', _t('The password has been changed'), new TAction(['LoginForm', 'onLoad']));
                }
            }
            else
            {
                throw new Exception(_t('User not found'));
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
}
