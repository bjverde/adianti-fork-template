<?php
/**
 * SystemDocumentFormWindow
 *
 * @version    7.6
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDocumentFormWindow extends TWindow
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

        $this->form = new BootstrapFormBuilder('SystemDocumentFormWindow');
        $this->form->setFieldSizes('100%');   
        
        $id = new THidden('id');
        $title = new TEntry('title');
        $this->folder_path = new TCombo('system_folder_id');
        $description = new TText('description');
        $filename = new TEntry('filename');
        $category_id = new TDBCombo('category_id', 'communication', 'SystemDocumentCategory', 'id', 'name');
        $submission_date = new TDate('submission_date');
        $submission_date->setEditable(false);
        $submission_date->setMask('dd/mm/yyyy');
        $submission_date->setDatabaseMask('yyyy-mm-dd');

        $this->form->addFields([$id] )->style = 'display:none';
        $row1 = $this->form->addFields([new TLabel(_t('Title')), $title], [new TLabel(_t('Name')), $filename] );
        $row2 = $this->form->addFields([new TLabel(_t('Folder')), $this->folder_path], [new TLabel(_t('Category')), $category_id] ,[new TLabel(_t('Submission date')), $submission_date] );
        $row3 = $this->form->addFields([new TLabel(_t('Description')), $description]);

        $row1->layout = ['col-sm-6', 'col-sm-6'];
        $row2->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];
        $row3->layout = ['col-sm-12'];
        
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

            $object = SystemDocument::find($data->id); 
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
                
                $object = new SystemDocument($key);
                
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
