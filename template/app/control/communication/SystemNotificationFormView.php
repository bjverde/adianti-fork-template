<?php
/**
 * SystemNotificationFormView
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemNotificationFormView extends TWindow
{
    public function __construct($param)
    {
        parent::__construct();
        parent::setSize(600, null);
        parent::setMinWidth(0.9, 600);
        parent::setTitle(_t('Notification'));
    }
    
    /**
     * Show data
     */
    public function onView( $param )
    {
        try
        {
            // convert parameter to object
            $data = (object) $param;
            
            // load the html template
            $html = new THtmlRenderer('app/resources/system/admin/notification_view.html');
            $html->enableTranslation(TRUE);
            
            TTransaction::open('communication');
            if (isset($data->id))
            {
                // load customer identified in the form
                $object = SystemNotification::find( $data->id );
                if ($object)
                {
                    if ($object->system_user_to_id == TSession::getValue('userid'))
                    {
                        // create one array with the customer data
                        $array_object = $object->toArray();
                        
                        // replace variables from the main section with the object data
                        $html->enableSection('main',  $array_object);
                        
                        if ($object->checked == 'N')
                        {
                            $html->enableSection('check', $array_object);
                        }
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied'));
                    }
                }
                else
                {
                    throw new Exception(_t('Object ^1 not found in ^2', $data->id, 'SystemNotification'));
                }
            }
            
            TTransaction::close();
            
            parent::add($html);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Check message as read
     */
    public static function onExecuteAction($param)
    {
        try
        {
            TTransaction::open('communication');
            
            $notification = SystemNotification::find($param['id']);
            if ($notification)
            {
                if ($notification->system_user_to_id == TSession::getValue('userid'))
                {
                    $notification->checked = 'Y';
                    $notification->store();
            
                    $query_string = $notification->action_url;
                    
                    if (!empty($query_string))
                    {
                        if (AdiantiCoreApplication::getRouter())
                        {
                            AdiantiCoreApplication::loadPageURL($query_string);
                        }
                        else
                        {
                            parse_str($query_string, $query_params);
                            $class  = $query_params['class'];
                            $method = isset($query_params['method']) ? $query_params['method'] : null;
                            unset($query_params['class']);
                            unset($query_params['method']);
                            AdiantiCoreApplication::loadPage( $class, $method, $query_params);
                        }
                    }
                    else
                    {
                        parent::closeWindow();
                    }
                    
                    TScript::create('Template.updateNotificationDropdown();');
                }
                else
                {
                    throw new Exception(_t('Permission denied'));
                }
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
