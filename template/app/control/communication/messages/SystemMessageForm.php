<?php
/**
 * SystemMessageForm
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemMessageForm extends TPage
{
    protected $form; // form
    
    use Adianti\Base\AdiantiFileSaveTrait;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
        parent::setTargetContainer('adianti_right_panel');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_SystemMessage');
        $this->form->setFormTitle(_t('Send message'));
        $this->form->enableClientValidation();
        
        // create the form fields
        $system_user_to_id = new TDBMultiSearch('system_user_to_id', 'permission', 'SystemUser', 'id', 'name');
        $subject = new TEntry('subject');
        $message = new THtmlEditor('message');
        $attachments = new TMultiFile('attachments');
        $attachments->enableFileHandling();
        $system_user_to_id->setMinLength(2);
        
        // add the fields
        $this->form->addFields([_t('To')]);
        $this->form->addFields([$system_user_to_id]);
        $this->form->addFields([_t('Subject')]);
        $this->form->addFields([$subject]);
        $this->form->addFields([_t('Message')]);
        $this->form->addFields([$message]);
        $this->form->addFields([_t('Attachments')]);
        $this->form->addFields([$attachments]);
        
        $system_user_to_id->setMinLength(0);
        $system_user_to_id->setSize('100%', 70);
        $subject->setSize('100%');
        $message->setSize('100%', '350');
        
        $system_user_to_id->addValidation(_t('To'), new TRequiredValidator);
        $subject->addValidation(_t('Subject'), new TRequiredValidator);
        $message->addValidation(_t('Message'), new TRequiredValidator);
        
        // create the form actions
        $btn = $this->form->addAction(_t('Send'), new TAction(array($this, 'onSend')), 'far:envelope');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('Clear form'),  new TAction(array($this, 'onClear')), 'fa:eraser red');
        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        parent::add($this->form);
    }
    
    public function onLoad($param)
    {
        $data = new stdClass;
        $data->system_user_to_id = !empty($param['system_user_to_id']) ? [$param['system_user_to_id']] : null;

        TForm::sendData('form_SystemMessage', $data);
    }
    
    public function onReply($param)
    {
        try
        {
            // open a transaction with database
            TTransaction::open('communication');
            
            $object = SystemMessage::find($param['id']);
            if ($object->system_user_id == TSession::getValue('userid') || $object->system_user_to_id == TSession::getValue('userid'))
            {
                $data = new stdClass;
                $data->system_user_to_id = [ $object->system_user_id ];
                $data->subject = 'RE: ' . $object->subject;
                $data->message = "<br><br>" . '---------- Replied message ---------' . "<br>" . 
                                 _t('From') . ': ' . $object->user_from->name . "<br>".
                                 _t('Date') . ': ' . $object->dt_message . "<br>".
                                 _t('Subject') . ': ' . $object->subject . "<br>".
                                 _t('To') . ': ' . $object->user_to->name . "<br><br>".
                                 $object->message;
                $this->form->setData($data);
            }
            
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function onForward($param)
    {
        try
        {
            // open a transaction with database
            TTransaction::open('communication');
            
            $object = SystemMessage::find($param['id']);
            if ($object->system_user_id == TSession::getValue('userid') || $object->system_user_to_id == TSession::getValue('userid'))
            {
                $data = new stdClass;
                $data->subject = 'FWD: ' . $object->subject;
                $data->message = '---------- Forwarded message ---------' . "<br>" . 
                                 _t('From') . ': ' . $object->user_from->name . "<br>".
                                 _t('Date') . ': ' . $object->dt_message . "<br>".
                                 _t('Subject') . ': ' . $object->subject . "<br>".
                                 _t('To') . ': ' . $object->user_to->name . "<br><br>".
                                 $object->message;
                $this->form->setData($data);
            }
            
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function onClear($param)
    {
    
    }
    
    public function onSend($param)
    {
        try
        {
            // open a transaction with database
            TTransaction::open('communication');
            
            // get the form data
            $data = $this->form->getData();
            
            // validate data
            $this->form->validate();
            
            if ($data->system_user_to_id)
            {
                foreach ($data->system_user_to_id as $target)
                {
                    $object = new SystemMessage;
                    $object->system_user_id = TSession::getValue('userid');
                    $object->system_user_to_id = $target;
                    $object->subject = $data->subject;
                    $object->message = $data->message;
                    $object->dt_message = date('Y-m-d H:i:s');
                    //$object->attachments = implode(',', $data->attachments);
                    $object->checked = 'N';
                    $object->removed = 'N';
                    $object->viewed  = 'N';
                    
                    // stores the object
                    $object->store();
                    
                    $this->saveFilesByComma($object, $data, 'attachments', 'files/system/messages');
                    
                    // fill the form with the active record data
                    $this->form->setData($data);
                }
                // close the transaction
                TTransaction::close();
            }
            
            // shows the success message
            TToast::show('success', _t("Message sent successfully"));
            
            TScript::create('Template.closeRightPanel()');
            
            return $object;
        }
        catch (Exception $e) // in case of exception
        {
            // get the form data
            $object = $this->form->getData();
            
            // fill the form with the active record data
            $this->form->setData($object);
            
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
