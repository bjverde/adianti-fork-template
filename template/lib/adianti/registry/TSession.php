<?php
namespace Adianti\Registry;

use SessionHandlerInterface;
use Adianti\Registry\AdiantiRegistryInterface;
use Adianti\Core\AdiantiCoreTranslator;
use Exception;

/**
 * Session Data Handler
 *
 * @version    8.7
 * @package    registry
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license
 */
class TSession implements AdiantiRegistryInterface
{
    private static $contexts = [];

    /**
     * Class Constructor
     */
    public function __construct(?SessionHandlerInterface $handler = NULL, $path = NULL)
    {
        if ($path)
        {
            session_save_path($path);
        }
        
        if ($handler)
        {
            session_set_save_handler($handler, true);
        }
		
        // if there's no opened session
        if (!session_id())
        {
            session_start();
        }
    }
    
    /**
     * Returns if the service is active
     */
    public static function enabled()
    {
        if (!session_id())
        {
            return session_start();
        }
        return TRUE;
    }
    
    /**
     * Enter session context
     */
    private static function enterContext($name)
    {
        self::$contexts[] = $name;
    }
    
    /**
     * Exit session context
     */
    private static function exitContext()
    {
        if (empty(self::$contexts))
        {
            throw new Exception(AdiantiCoreTranslator::translate('There is no active session context to exit'));
        }
        array_pop(self::$contexts);
    }

    /**
     * Run function inside session context
     */
    public static function withContext($name, callable $callback)
    {
        self::enterContext($name);
        try
        {
            return $callback();
        }
        finally
        {
            self::exitContext();
        }
    }

    /**
     * Build session key, using context (when inside a context)
     */
    private static function buildKey($var)
    {
        return self::$contexts ? implode('.', self::$contexts) . '.' . $var : $var;
    }

    /**
     * Define the value for a variable
     * @param $var   Variable Name
     * @param $value Variable Value
     */
    public static function setValue($var, $value)
    {
        $key = self::buildKey($var);

        if (defined('APPLICATION_NAME'))
        {
            $_SESSION[APPLICATION_NAME][$key] = $value;
        }
        else
        {
            $_SESSION[$key] = $value;
        }
    }
    
    /**
     * Returns the value for a variable
     * @param $var Variable Name
     */
    public static function getValue($var)
    {
        $key = self::buildKey($var);

        if (defined('APPLICATION_NAME'))
        {
            return $_SESSION[APPLICATION_NAME][$key] ?? NULL;
        }
        else
        {
            return $_SESSION[$key] ?? NULL;
        }
    }

    /**
     * Clear the value for a variable
     * @param $var   Variable Name
     */
    public static function delValue($var)
    {
        $key = self::buildKey($var);

        if (defined('APPLICATION_NAME'))
        {
            unset($_SESSION[APPLICATION_NAME][$key]);
        }
        else
        {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Regenerate id
     */
    public static function regenerate()
    {
        session_regenerate_id();
    }
    
    /**
     * Clear session
     */
    public static function clear()
    {
        self::freeSession();
    }
    
    /**
     * Destroy the session data
     * Backward compatibility
     */
    public static function freeSession()
    {
        if (defined('APPLICATION_NAME'))
        {
            $_SESSION[APPLICATION_NAME] = array();
        }
        else
        {
            $_SESSION[] = array();
        }
    }
}
