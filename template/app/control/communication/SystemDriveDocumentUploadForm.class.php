<?php
/**
 * SystemDriveDocumentUploadForm
 *
 * @version    1.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
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
        parent::setSize((TPage::isMobile() ? 0.9 : 0.5), null);

        $this->form = new BootstrapFormBuilder('SystemDriveDocumentUploadForm');
        $this->form->setFieldSizes('100%');   
        
        $id = new THidden('id');
        $title = new TEntry('title');
        $file = new TFile('filename');
        $this->folder_path = new TCombo('system_folder_id');
        $description = new TText('description');
        $category_id = new TDBCombo('category_id', 'communication', 'SystemDocumentCategory', 'id', 'name');

        $this->form->addFields([$id] )->style = 'display:none';
        $row0 = $this->form->addFields([new TLabel(_t('File').'*'), $file]);
        $row1 = $this->form->addFields([new TLabel(_t('Title').'*'), $title], [new TLabel(_t('Folder')), $this->folder_path], [new TLabel(_t('Category')), $category_id]);
        $row3 = $this->form->addFields([new TLabel(_t('Description')), $description]);

        $file->addValidation(_t('File'), new TRequiredValidator);

        $row0->layout = ['col-sm-12'];
        $row1->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];
        $row3->layout = ['col-sm-12'];
     
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';

        parent::add($this->form);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave()
    {
        try
        {
            TTransaction::open('communication');
            $this->form->validate();
            
            $data = $this->form->getData();

            $object = new SystemDocument(); 
            $object->fromArray( (array) $data);
            $object->submission_date = date('Y-m-d H:i:s');
            $object->system_user_id = TSession::getValue('userid');
            $object->title = $object->title ? $object->title : $object->filename;
            $object->store();

            $source_file   = 'tmp/' . $object->filename;
            $target_path   = 'files/documents/' . $object->id;
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
            TWindow::closeWindow();

            AdiantiCoreApplication::loadPage('SystemDriveList', 'onLoad', [
                'path' => TSession::getValue('SystemDriveListpath'),
                'filter' => TSession::getValue('SystemDriveListfilter')
            ]);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onNew( $param )
    {
        try
        {
            TTransaction::open('communication');

            if (! empty($param))
            {
                $data = new stdClass;
                $data->system_folder_id = $param['path'];

                $this->form->setData($data);
            }

            $this->folder_path->addItems(SystemFolder::getFolders(TSession::getValue('userid'), TSession::getValue('usergroupids')));
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
}
