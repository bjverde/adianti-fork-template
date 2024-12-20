<?php
/**
 * Program Information Service
 *
 * @version    8.0
 * @package    service
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemProgramService
{
    /**
     * Returns controller methods
     */
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
    
    /**
     * Returns service classes
     */
    public static function getCronServiceClassEntries($path = 'app/service')
    {
        $entries = [];
        
        if (!file_exists($path))
        {
            return $entries;
        }
        
        $iterator = new AppendIterator();
        $iterator->append(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST));
        
        foreach ($iterator as $arquivo)
        {
            if (substr($arquivo, -4) == '.php')
            {
                $name = $arquivo->getFileName();
                $pieces = explode('.', $name);
                $service = (string) $pieces[0];
                
                $class = new ReflectionClass($service);
                $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
                
                if ($methods)
                {
                    foreach ($methods as $method)
                    {
                        $method_name = $method->getName();
                        $entries["{$service}::{$method_name}"] = "{$service}::{$method_name}()";
                    }
                }
            }
        }
        
        ksort($entries);
        return $entries;
    }
}
