<?php
/**
 * SystemScheduleLog
 *
 * @version    8.0
 * @package    model
 * @subpackage log
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemScheduleLog extends TRecord
{
    const TABLENAME = 'system_schedule_log';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('logdate');
        parent::addAttribute('title');
        parent::addAttribute('class_name');
        parent::addAttribute('method');
        parent::addAttribute('status');
        parent::addAttribute('message');
    }
}
