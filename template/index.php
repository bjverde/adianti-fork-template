<?php
require_once 'init.php';

$ini = AdiantiApplicationConfig::get();
$theme  = $ini['general']['theme'];
$class  = isset($_REQUEST['class']) ? $_REQUEST['class'] : '';
$public = in_array($class, !empty($ini['permission']['public_classes']) ? $ini['permission']['public_classes'] : []);

// AdiantiCoreApplication::setRouter(array('AdiantiRouteTranslator', 'translate'));

new TSession;
ApplicationAuthenticationService::checkMultiSession();
ApplicationTranslator::setLanguage( TSession::getValue('user_language'), true );


if ( TSession::getValue('logged') )
{
    if (isset($_REQUEST['template']) AND $_REQUEST['template'] == 'iframe')
    {
        $content = file_get_contents("app/templates/{$theme}/iframe.html");
    }
    else
    {
        $content = file_get_contents("app/templates/{$theme}/layout.html");
        $menu    = AdiantiMenuBuilder::parse('menu.xml', $theme);
        $content = str_replace('{MENU}', $menu, $content);
        $content = str_replace('{MENUTOP}', AdiantiMenuBuilder::parseNavBar('menu-top.xml', $theme), $content);
        $content = str_replace('{MENUBOTTOM}', AdiantiMenuBuilder::parseNavBar('menu-bottom.xml', $theme), $content);
    }
}
else
{
    if (isset($ini['general']['public_view']) && $ini['general']['public_view'] == '1')
    {
        $content = file_get_contents("app/templates/{$theme}/public.html");
        $menu    = AdiantiMenuBuilder::parse('menu-public.xml', $theme);
        $content = str_replace('{MENU}', $menu, $content);
        $content = str_replace('{MENUTOP}', AdiantiMenuBuilder::parseNavBar('menu-top-public.xml', $theme), $content);
        $content = str_replace('{MENUBOTTOM}', AdiantiMenuBuilder::parseNavBar('menu-bottom-public.xml', $theme), $content);
    }
    else
    {
        $content = file_get_contents("app/templates/{$theme}/login.html");
    }
}
//--- START: TEMA ADMINBS5_V3  ---------------------------------------------------------
$system_version = $ini['system']['system_version'];
$title       = $ini['general']['title'];
$head_title  = $title.' - v'.$system_version;

$content = str_replace('{head_title}', $head_title, $content);
$content = str_replace('{title}', $title, $content);
$content = str_replace('{system_version}', $system_version, $content);
//--- END: TEMA ADMINBS5_V3 ------------------------------------------------------------

$content = ApplicationTranslator::translateTemplate($content);
$content = AdiantiTemplateParser::parse($content);

echo $content;

if (TSession::getValue('logged') OR $public)
{
    if ($class)
    {
        $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : NULL;
        AdiantiCoreApplication::loadPage($class, $method, $_REQUEST);
    }
}
else
{
    if (isset($ini['general']['public_view']) && $ini['general']['public_view'] == '1')
    {
        if (!empty($ini['general']['public_entry']))
        {
            AdiantiCoreApplication::loadPage($ini['general']['public_entry'], '', $_REQUEST);
        }
    }
    else
    {
        AdiantiCoreApplication::loadPage('LoginForm', '', $_REQUEST);
    }
}
