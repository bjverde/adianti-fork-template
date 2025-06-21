<?php
/**
 * WelcomeView
 *
 * @version    8.1
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemConcurrentAccessView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/system/public/concurrent_access.html');
        
        $ini = AdiantiApplicationConfig::get();
        
        $replaces = ['title' => _t('Session Closed'),
                     'content' => _t('We have verified that your account was accessed in another session. Since our application does not allow concurrent logins, you were automatically logged out of this session. Please log in again to continue using our services')];
        
        // replace the main section variables
        $this->html->enableSection('main', $replaces);
        
        parent::add( $this->html );
    }
}
