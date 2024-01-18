<?php
/**
 * Program Information Service
 *
 * @version    7.6
 * @package    service
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemProgramService
{
    public static function getProgramMethods($controller)
    {
        $class = new ReflectionClass($controller);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $traits = $class->getTraitNames();
        
        $exposed_methods = [];
        $grants = [];
        if (in_array('Adianti\Base\AdiantiStandardListTrait', $traits) || $class->isSubclassOf('TStandardList'))
        {
            $exposed_methods[] = 'onDelete';
            $exposed_methods[] = 'Delete';
        }
        if (in_array('Adianti\Base\AdiantiStandardFormTrait', $traits) || $class->isSubclassOf('TStandardForm'))
        {
            $exposed_methods[] = 'onEdit';
            $exposed_methods[] = 'onSave';
        } 
        
        foreach ($methods as $method)
        {
            if (in_array($method->getDeclaringClass()->getShortName(), [$controller]))
            {
                if ($method->getName() !== '__construct' && !$method->isStatic())
                {
                    $exposed_methods[] = $method->getName();
                }
            }
        }
        
        $exposed_methods = array_unique($exposed_methods);
        
        return $exposed_methods;
    }
}
