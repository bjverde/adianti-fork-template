<?php
/**
 * SystemSchedule
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemSchedule extends TRecord
{
    const TABLENAME  = 'system_schedule';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('title');
        parent::addAttribute('class_name');
        parent::addAttribute('method');
        parent::addAttribute('monthday');
        parent::addAttribute('weekday');
        parent::addAttribute('hour');
        parent::addAttribute('minute');
        parent::addAttribute('active');
        parent::addAttribute('schedule_type');
    }
}
