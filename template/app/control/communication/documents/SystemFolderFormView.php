<?php
/**
 * SystemFolderFormView
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemFolderFormView extends TWindow
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

        parent::setTitle( _t('Details') );
        parent::setModal(TRUE);
        parent::removePadding();
        parent::setSize(500, null);
        parent::setMinWidth(0.8, 500);
        
        $this->form = new BootstrapFormBuilder('SystemFolderFormView');
        $this->form->setProperty('class', 'card noborder');
        $this->form->setFieldSizes('100%');   
        
        $id = new THidden('id');
        $name = new TEntry('name');
        $folder_path = new THidden('system_folder_parent_id');
        $created_at = new TDate('created_at');
        $created_at->setMask('dd/mm/yyyy');
        $created_at->setDatabaseMask('yyyy-mm-dd');
        $created_at->setEditable(FALSE);
        
        $this->form->addFields([$id]);
        $this->form->addFields([$folder_path]);
        $this->form->addFields([new TLabel(_t('Name'))]);
        $this->form->addFields([$name]);
        $this->form->addFields([new TLabel(_t('Created at'))]);
        $this->form->addFields([$created_at]);
        
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
            
            $object = SystemFolder::find($data->id);
            $object->checkPermission(); 
            $object->fromArray( (array) $data);
            $object->store();
            
            TTransaction::close();

            TToast::show('success', _t('Record saved'));
            
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
                $this->form->setData($object);
                
                if ($object->system_user_id == TSession::getValue('userid'))
                {
                    $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'far:save');
                    $btn->class = 'btn btn-sm btn-primary';
                }
                else
                {
                    $this->form->setEditable(FALSE);
                }

                TTransaction::close();
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
