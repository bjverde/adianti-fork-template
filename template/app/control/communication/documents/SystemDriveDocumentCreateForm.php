<?php
/**
 * SystemDriveDocumentCreateForm
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDriveDocumentCreateForm extends TWindow
{
    protected $form;
    protected $folder_path;
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct($param)
    {
        parent::__construct();
        
        parent::setTitle( _t('Document') );
        parent::setModal(TRUE);
        parent::removePadding();
        parent::setSize(800, null);
        parent::setMinWidth(0.9, 800);

        $this->form = new BootstrapFormBuilder('SystemDriveDocumentUploadForm');
        $this->form->setProperty('class', 'card noborder');
        $this->form->enableClientValidation();
        $this->form->setFieldSizes('100%');
        
        $id    = new THidden('id');
        $folder_path = new THidden('system_folder_id');
        $title = new TEntry('title');
        $content_type  = new THidden('content_type');
        
        if ($param['content_type'] == 'html')
        {
            $content  = new THtmlEditor('content');
        }
        else
        {
            $content  = new TText('content');
        }
        
        $content->setSize('100%', 500);
        
        $this->form->addFields([$id]);
        $this->form->addFields([$folder_path]);
        $this->form->addFields([$content_type]);
        $this->form->addFields([new TLabel(_t('Title'))]);
        $this->form->addFields([$title]);
        $this->form->addFields([new TLabel(_t('Content'))]);
        $this->form->addFields([$content]);
        
        $title->addValidation(_t('Title'), new TRequiredValidator);
        $content->addValidation(_t('Content'), new TRequiredValidator);
        
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';

        parent::add($this->form);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave($param)
    {
        try
        {
            TTransaction::open('communication');
            
            $object = new SystemDocument;
            $object->fromArray( $param );
            $object->submission_date = date('Y-m-d H:i:s');
            $object->system_user_id = TSession::getValue('userid');
            $object->content_type = $param['content_type'];
            $object->store();
            
            TTransaction::close();

            TToast::show('success', _t('Record saved'));
            
            AdiantiCoreApplication::loadPage('SystemDriveList', 'onLoad', [
                'path' => TSession::getValue('SystemDriveListpath'),
                'filter' => 'my'
            ]);
        }
        catch (Exception $e)
        {
            TToast::show('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onNew( $param )
    {
        $data = new stdClass;
        $data->system_folder_id = $param['path'] ?? null;
        $data->content_type = $param['content_type'] ?? '';
        $this->form->setData($data);
    }
}
