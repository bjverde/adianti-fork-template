<?php
/**
 * SystemNotification
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemNotification extends TRecord
{
    const TABLENAME = 'system_notification';
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
        parent::addAttribute('action_url');
        parent::addAttribute('action_label');
        parent::addAttribute('icon');
        parent::addAttribute('checked');
    }
    
    /**
     *
     */
    public function get_icon_ok()
    {
        $icon = $this->icon;
        $icon = str_replace( 'fa fa-', 'fa:', (string) $icon);
        $icon = str_replace( 'far fa-', 'far:', (string) $icon);
        $icon = str_replace( 'fas fa-', 'fas:', (string) $icon);
        return $icon;
    }
    
    /**
     *
     */
    public function get_check_style()
    {
        if ($this->checked == 'Y')
        {
            return 'text-decoration:line-through;color: var(--bs-secondary);';
        }
        return '';
    }
    
    /**
     * Register notification
     */
    public static function register( $user_to, $subject, $message, $action, $label, $icon = null, $date = null)
    {
        if ($action instanceof TAction)
        {
            $action = $action->serialize(false);
        }
        
        TTransaction::open('communication');
        $object = new self;
        $object->system_user_id    = TSession::getValue('userid');
        $object->system_user_to_id = $user_to;
        $object->subject           = $subject;
        $object->message           = $message;
        $object->dt_message        = empty($date) ? date("Y-m-d H:i:s") : $date;
        $object->action_url        = $action;
        $object->action_label      = $label;
        $object->icon              = $icon;
        $object->checked           = 'N';
        $object->store();
        TTransaction::close();
    }
}
