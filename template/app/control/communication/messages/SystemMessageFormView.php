<?php
/**
 * SystemMessageFormView
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemMessageFormView extends TPage
{
    /**
     * Show data
     */
    public function onView( $param )
    {
        $ini = AdiantiApplicationConfig::get();
        $seed = APPLICATION_NAME . (string) $ini['general']['seed'];
        
        try
        {
            // convert parameter to object
            $data = (object) $param;
            
            // load the html template
            $html = new THtmlRenderer('app/resources/system/inbox/message_form_view.html');
            $html->enableTranslation();
            $html->disableHtmlConversion();
            
            TTransaction::open('communication');
            if (isset($data->id))
            {
                // load customer identified in the form
                $object = SystemMessage::find( $data->id );
                if ($object->system_user_to_id == TSession::getValue('userid'))
                {
                    $object->viewed = 'Y';
                    $object->store();
                    TScript::create('Template.updateMessageDropdown();');
                }
                
                if ($object)
                {
                    // show message if the user is the source or the target of the message
                    if ($object->system_user_to_id == TSession::getValue('userid') OR $object->system_user_id == TSession::getValue('userid'))
                    {
                        // create one array with the customer data
                        $array_object = $object->toArray();
                        $array_object['message64'] = base64_encode($array_object['message']??'');
                        
                        TTransaction::open('permission');
                        $user = SystemUser::find($array_object['system_user_id']);
                        $user_to = SystemUser::find($array_object['system_user_to_id']);
                        $array_object['user'] = '';
                        $array_object['user_to'] = '';
                        if ($user instanceof SystemUser)
                        {
                            $array_object['user'] = $user->name;
                        }
                        if ($user_to instanceof SystemUser)
                        {
                            $array_object['user_to'] = $user_to->name;
                        }
                        TTransaction::close();
                        
                        // replace variables from the main section with the object data
                        $html->enableSection('main',  $array_object);
                        
                        if ($object->system_user_to_id == TSession::getValue('userid'))
                        {
                            $html->enableSection('forward', $array_object);
                            $html->enableSection('reply', $array_object);
                            $html->enableSection('tags', $array_object);
                            
                            if ($object->removed == 'Y')
                            {
                                // user is the target of the message, is not checked yet
                                $html->enableSection('undelete', $array_object);
                            }
                            else
                            {
                                $html->enableSection('delete', $array_object);
                                
                                if ($object->checked == 'N')
                                {
                                    // user is the target of the message, is not checked yet
                                    $html->enableSection('check', $array_object);
                                }
                                else
                                {
                                    // user is the target of the message, is already checked
                                    $html->enableSection('recover', $array_object);
                                }
                            }
                        }
                        else if ($object->system_user_id == TSession::getValue('userid'))
                        {
                            $html->enableSection('forward', $array_object);
                        }
                        
                        $attachments = explode(',', $object->attachments ?? '');
                        
                        if ($attachments)
                        {
                            $list = [];
                            foreach ($attachments as $attach)
                            {
                                if (!empty($attach))
                                {
                                    $list[] = ['file' => $attach, 'name' => basename($attach), 'hash' => md5($seed.$attach) ];
                                }
                            }
                            $html->enableSection('attachments', $list, true);
                        }
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied'));
                    }
                }
                else
                {
                    throw new Exception(_t('Object ^1 not found in ^2', $data->id, 'SystemMessage'));
                }
            }
            
            $tag_list = SystemMessageTag::getTagList();
            TTransaction::close();
            
            $folders = new THtmlRenderer('app/resources/system/inbox/message_folders.html');
            
            if ($object->removed == 'Y')
            {
                $folders->enableSection('main', ['class_trash' => 'active']);
            }
            else if ($object->checked == 'Y')
            {
                $folders->enableSection('main', ['class_archived' => 'active']);
            }
            else if ($object->checked == 'N' && $object->system_user_id == TSession::getValue('userid'))
            {
                $folders->enableSection('main', ['class_sent' => 'active']);
            }
            else
            {
                $folders->enableSection('main', ['class_inbox' => 'active']);
            }
            
            $folders->enableTranslation();
            
            $tags = new THtmlRenderer('app/resources/system/inbox/message_tags.html');
            $tags->enableSection('main', []);
            $tags->enableSection('tags', $tag_list, true);
            $tags->enableTranslation();
            
            $vbox = TVBox::pack($folders, $tags);
            $vbox->style = 'width:100%';
            
            $hbox = new THBox;
            $hbox->style = 'width:100%';
            $hbox->add($vbox)->class = 'left-mailbox';
            $hbox->add($html, '')->class = 'right-mailbox';
            
            parent::add($hbox);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     *
     */
    public static function downloadFile($param)
    {
        $file = $param['file'];
        $hash = $param['hash'];
        
        $ini = AdiantiApplicationConfig::get();
        $seed = APPLICATION_NAME . (string) $ini['general']['seed'];
        
        if (md5($seed.$file) == $hash)
        {
            SystemDocumentDownloaderService::download($file);
        }
    }
    
    /**
     * Check message as read
     */
    public function onCheck($param)
    {
        try
        {
            TTransaction::open('communication');
            $message = SystemMessage::find($param['id']);
            
            if ($message instanceof SystemMessage)
            {
                $message->checkPermission();
                $message->checked = 'Y';
                $message->store();
                TScript::create('Template.updateMessageDropdown();');
            }
            TTransaction::close();
            AdiantiCoreApplication::loadPage('SystemMessageList', 'filterInbox' );
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Check message as unread
     */
    public function onUncheck($param)
    {
        try
        {
            TTransaction::open('communication');
            $message = SystemMessage::find($param['id']);
            if ($message instanceof SystemMessage)
            {
                $message->checkPermission();
                $message->checked = 'N';
                $message->store();
                TScript::create('Template.updateMessageDropdown();');
            }
            TTransaction::close();
            AdiantiCoreApplication::loadPage('SystemMessageList', 'filterInbox' );
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Delete message
     */
    public function onDelete($param)
    {
        try
        {
            TTransaction::open('communication');
            $message = SystemMessage::find($param['id']);
            
            if ($message instanceof SystemMessage)
            {
                $message->checkPermission();
                $message->removed = 'Y';
                $message->store();
                TScript::create('Template.updateMessageDropdown();');
            }
            TTransaction::close();
            
            if ($message->checked == 'Y')
            {
                AdiantiCoreApplication::loadPage('SystemMessageList', 'filterArchived' );
            }
            else
            {
                AdiantiCoreApplication::loadPage('SystemMessageList', 'filterInbox' );
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Undelete message
     */
    public function onUndelete($param)
    {
        try
        {
            TTransaction::open('communication');
            $message = SystemMessage::find($param['id']);
            if ($message instanceof SystemMessage)
            {
                $message->checkPermission();
                $message->removed = 'N';
                $message->store();
                TScript::create('Template.updateMessageDropdown();');
            }
            TTransaction::close();
            
            if ($message->checked == 'Y')
            {
                AdiantiCoreApplication::loadPage('SystemMessageList', 'filterArchived' );
            }
            else
            {
                AdiantiCoreApplication::loadPage('SystemMessageList', 'filterInbox' );
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
