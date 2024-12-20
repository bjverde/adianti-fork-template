<?php
/**
 * SystemSupportForm
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemSupportForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        parent::setTargetContainer('adianti_right_panel');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_SystemMessage');
        $this->form->enableClientValidation();
        $this->form->setFormTitle(_t('Open ticket'));
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setProperty('style', 'border:0');
        
        // define the form title
        $this->form->setFormTitle(_t('Ticket'));
        
        // create the form fields
        $subject = new TEntry('subject');
        $message = new THtmlEditor('message');
        $attachments = new TMultiFile('attachments');
        $message->setSize('100%', 300);
        
        // add the fields
        $this->form->addFields([new TLabel(_t('Subject'))]);
        $this->form->addFields([$subject]);
        $this->form->addFields([new TLabel(_t('Message'))]);
        $this->form->addFields([$message]);
        $this->form->addFields([new TLabel(_t('Attachments'))]);
        $this->form->addFields([$attachments]);
        
        $subject->addValidation(_t('Subject'), new TRequiredValidator);
        $message->addValidation(_t('Message'), new TRequiredValidator);
        
        // create the form actions
        $btn = $this->form->addAction(_t('Send'), new TAction(array($this, 'onSend')), 'far:envelope');
        $btn->class = 'btn btn-sm btn-primary';

        
        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        parent::add($this->form);
    }
    
    public function onSend($param)
    {
        try
        {
            // get the form data
            $data = $this->form->getData();
            // validate data
            $this->form->validate();
            
            TTransaction::open('permission');
            $preferences = SystemPreference::getAllPreferences();
            TTransaction::close();
            
            if (empty($preferences['mail_support']))
            {
                throw new Exception(_t('No support e-mail configured'));
            }
            
            $list = [];
            if ($data->attachments)
            {
                foreach ($data->attachments as $attach)
                {
                    $list[] = [ 'tmp/'.$attach, $attach ];
                }
            }
            
            MailService::send( $preferences['mail_support'], $data->subject, $data->message, 'html', $list );
            
            if ($data->attachments)
            {
                foreach ($data->attachments as $attach)
                {
                    unlink('tmp/'.$attach);
                }
            }
            
            // shows the success message
            new TMessage('info', _t('Message sent successfully'));
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
