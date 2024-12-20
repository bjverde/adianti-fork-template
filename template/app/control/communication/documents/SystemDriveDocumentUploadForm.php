<?php
/**
 * SystemDriveDocumentUploadForm
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDriveDocumentUploadForm extends TWindow
{
    protected $form;
    protected $folder_path;
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setTitle( _t('Document') );
        parent::setModal(TRUE);
        parent::removePadding();
        parent::setSize(500, null);
        parent::setMinWidth(0.8, 500);

        $this->form = new BootstrapFormBuilder('SystemDriveDocumentUploadForm');
        $this->form->setProperty('class', 'card noborder');
        $this->form->enableClientValidation();
        $this->form->setFieldSizes('100%');
        
        $id    = new THidden('id');
        $folder_path = new THidden('system_folder_id');
        $title = new TEntry('title');
        $file  = new TFile('filename');
        $description = new TText('description');
        
        $this->form->addFields([$id]);
        $this->form->addFields([$folder_path]);
        $this->form->addFields([new TLabel(_t('File'))]);
        $this->form->addFields([$file]);
        $this->form->addFields([new TLabel(_t('Title'))]);
        $this->form->addFields([$title]);
        $this->form->addFields([new TLabel(_t('Description'))]);
        $this->form->addFields([$description]);
        
        $file->addValidation(_t('File'), new TRequiredValidator);
        
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
            $object->title = $object->title ? $object->title : $object->filename;
            $object->store();
            
            $source_file   = 'tmp/' . $object->filename;
            $target_path   = 'files/system/documents/' . $object->id;
            $target_file   =  $target_path . '/' . $object->filename;
            
            if (file_exists($source_file))
            {
                if (!file_exists($target_path))
                {
                    if (!@mkdir($target_path, 0777, true))
                    {
                        throw new Exception(_t('Permission denied'). ': '. $target_path);
                    }
                }
                else
                {
                    foreach (glob("$target_path/*") as $file)
                    {
                        unlink($file);
                    }
                }
                
                // if the user uploaded a source file
                if (file_exists($target_path))
                {
                    // move to the target directory
                    rename($source_file, $target_file);
                }
            }
            
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
        $this->form->setData($data);
    }
}
