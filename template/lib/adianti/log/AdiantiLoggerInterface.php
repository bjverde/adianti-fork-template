<?php
namespace Adianti\Log;

/**
 * Log Interface
 *
 * @version    8.0
 * @package    log
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license
 */
interface AdiantiLoggerInterface
{
    function write($message);
}
