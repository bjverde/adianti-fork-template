<?php
/**
 * SystemFolderForm
 *
 * @version    7.6
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemFolderForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setTitle(_t('Folder'));
        parent::setModal(TRUE);
        parent::removePadding();
        parent::setSize((TPage::isMobile() ? 0.9 : 0.5), null);
        
        $this->form = new BootstrapFormBuilder('form_SystemFolder');
        $this->form->setFieldSizes('100%');

        $id = new THidden('id');
        $name = new TEntry('name');
        $system_folder_parent_id = new TDBCombo('system_folder_parent_id', 'communication', 'SystemFolder', 'id', 'name');
        
        $system_folder_parent_id->setValue($param['path']??null);
        
        $this->form->addFields( [$id] )->style = 'display:none';
        $row1 = $this->form->addFields( [$ln=new TLabel(_t('Name')), $name], [new TLabel(_t('Parent folder')), $system_folder_parent_id] );
        $row1->layout = ['col-sm-8', 'col-sm-4'];
        
        $name->addValidation( _t('Name'), new TRequiredValidator );
        $ln->setFontColor('red');
        
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        
        parent::add($container);
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
            
            $object = new SystemFolder; 
            $data = $this->form->getData();
            $object->fromArray( (array) $data);
            $object->system_user_id = TSession::getValue('userid');

            if ($object->id && $object->id == $object->system_folder_parent_id)
            {
                throw new Exception((string) _t('This operation is not allowed'));
            }

            $object->store();

            $data->id = $object->id;
            
            $this->form->setData($data);
            TTransaction::close();
            
            TToast::show('info', _t('Record saved'));
            AdiantiCoreApplication::loadPage('SystemDriveList', 'onLoad', [
                'path' => TSession::getValue('SystemDriveListpath'),
                'filter' => TSession::getValue('SystemDriveListfilter')
            ]);
            TWindow::closeWindow();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear();
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];
                TTransaction::open('communication');
                $object = new SystemFolder($key);
                
                if ($object->system_user_id == TSession::getValue('userid'))
                {
                    $object->user_ids = $object->getSystemUsersIds();
                    $object->group_ids = $object->getSystemGroupsIds();
                    $this->form->setData($object);
                }
                else
                {
                    throw new Exception((string) _t('Permission denied'));
                }
                TTransaction::close();
                
                if (empty($param['hasfile']))
                {
                    TSession::setValue('system_document_upload_file', $object->filename);
                }
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
