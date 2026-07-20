<?php
/**
 * WelcomeView
 *
 * @version    8.6
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemInvalidAccessView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct($param)
    {
        parent::__construct();
        
        $message = '';
        if (!empty($param['message64']))
        {
            $message = '<br><br><b>' . base64_decode($param['message64']) . '</b>';
        }
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/system/public/invalid_access.html');
        $this->html->disableHtmlConversion();
        
        $ini = AdiantiApplicationConfig::get();
        
        $replaces = ['title' => _t('Invalid access'),
                     'content' => _t('Token is invalid or expired. Please log in again') . $message];
        
        // replace the main section variables
        $this->html->enableSection('main', $replaces);
        
        parent::add( $this->html );
    }
}
