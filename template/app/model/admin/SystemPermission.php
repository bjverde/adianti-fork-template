<?php
/**
 * SystemPermission
 *
 * @version    8.0
 * @package    model
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPermission
{
    public static function checkPermission($class, $method = null)
    {
        $ini = AdiantiApplicationConfig::get();
        
        $public_classes = !empty($ini['permission']['public_classes']) ? $ini['permission']['public_classes'] : [];
        $public_classes[] = 'LoginForm';
        
        $programs = TSession::getValue('programs');
        $methods  = TSession::getValue('methods');
        
        $is_public = in_array($class, $public_classes);
        $has_program_permission = (isset($programs[$class]) && $programs[$class]);
        $has_method_restriction = (isset($methods[$class][$method]) && $methods[$class][$method] === false);
        
        if (!empty($method))
        {
            return ( ($has_program_permission || $is_public) && !$has_method_restriction );
        }
        else // just class
        {
            return ( $has_program_permission || $is_public );
        }
    } 
}
