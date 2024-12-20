<?php
/**
 * SystemProfileView
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemProfileView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        parent::setTargetContainer('adianti_right_panel');
        
        $html = new THtmlRenderer('app/resources/system/admin/profile.html');
        $replaces = array();
        
        try
        {
            TTransaction::open('permission');
            
            $user= SystemUser::newFromLogin(TSession::getValue('login'));
            $replaces = $user->toArray();
            $replaces['rand'] = mt_rand(1000000000, 1999999999);
            $replaces['frontpage'] = $user->frontpage_name;
            $replaces['groupnames'] = $user->getSystemUserGroupNames();
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
        
        $html->enableSection('main', $replaces);
        $html->enableTranslation();
        
        $container = TVBox::pack($html);
        $container->style = 'width: 100%';
        parent::add($container);
    }
    
    public function onLoad($param)
    {
    }
}
