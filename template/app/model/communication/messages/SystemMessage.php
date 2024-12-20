<?php
/**
 * SystemMessage
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemMessage extends TRecord
{
    const TABLENAME = 'system_message';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('system_user_to_id');
        parent::addAttribute('subject');
        parent::addAttribute('message');
        parent::addAttribute('dt_message');
        parent::addAttribute('checked');
        parent::addAttribute('removed');
        parent::addAttribute('viewed');
        parent::addAttribute('attachments');
    }
    
    /**
     *
     */
    public function checkPermission()
    {
        if ($this->system_user_to_id !== TSession::getValue('userid'))
        {
            throw new Exception(_t('Permission denied'));
        }
    }
    
    /**
     * Get source user
     */
    public function get_user_from()
    {
        return SystemUser::findInTransaction('permission', $this->system_user_id);
    }
    
    /**
     * Get target user
     */
    public function get_user_to()
    {
        return SystemUser::findInTransaction('permission', $this->system_user_to_id);
    }
    
    /**
     * Get the another user
     */
    public function get_user_mixed()
    {
        if ($this->system_user_id == TSession::getValue('userid'))
        {
            return $this->get_user_to();
        }
        else
        {
            return $this->get_user_from();
        }
    }
    
    /**
     * Get date create formated
     */
    public function get_date()
    {
        return date('d/m/Y H:i', strtotime($this->dt_message));
    }
}
