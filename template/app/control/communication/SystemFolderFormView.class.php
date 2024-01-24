<?php
/**
 * SystemFolderFormView
 *
 * @version    7.6
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
        parent::setSize((TPage::isMobile() ? 0.9 : 0.5), null);

        $this->form = new BootstrapFormBuilder('SystemFolderFormView');
        $this->form->setFieldSizes('100%');   
        
        $id = new THidden('id');
        $name = new TEntry('name');
        $this->folder_path = new TCombo('system_folder_parent_id');
        $created_at = new TDate('created_at');
        $created_at->setMask('dd/mm/yyyy');
        $created_at->setDatabaseMask('yyyy-mm-dd');
        $created_at->setEditable(FALSE);
        
        $this->form->addFields([$id] )->style = 'display:none';
        $row1 = $this->form->addFields([new TLabel(_t('Name')), $name], [new TLabel(_t('Folder')), $this->folder_path], [new TLabel(_t('Created at')), $created_at]);

        $row1->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];
        
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
            $object->fromArray( (array) $data);
            $object->store();
            
            TTransaction::close();

            TToast::show('success', _t('Record saved'));

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
                    $this->folder_path->addItems(SystemFolder::getFolders(TSession::getValue('userid'), TSession::getValue('usergroupids')));
                    $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'far:save');
                    $btn->class = 'btn btn-sm btn-primary';
                }
                else
                {
                    $this->folder_path->addItems(SystemFolder::getFolders());
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
