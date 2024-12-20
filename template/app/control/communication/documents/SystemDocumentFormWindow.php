<?php
/**
 * SystemDocumentFormWindow
 *
 * @version    8.0
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
        parent::setSize(600, null);
        parent::setMinWidth(0.8, 600);
        
        $this->form = new BootstrapFormBuilder('SystemDocumentFormWindow');
        $this->form->setProperty('class', 'card noborder');
        $this->form->setFieldSizes('100%');
        
        $id = new THidden('id');
        $title = new TEntry('title');
        $folder_path = new THidden('system_folder_id');
        $description = new TText('description');
        $submission_date = new TDate('submission_date');
        $submission_date->setEditable(false);
        $submission_date->setMask('dd/mm/yyyy');
        $submission_date->setDatabaseMask('yyyy-mm-dd');

        $this->form->addFields([$id] );
        $this->form->addFields([new TLabel(_t('Title'))]);
        $this->form->addFields([$title]);
        $this->form->addFields([$folder_path]);
        $this->form->addFields([new TLabel(_t('Description'))]);
        $this->form->addFields([$description]);
        $this->form->addFields([new TLabel(_t('Submission date'))]);
        $this->form->addFields([$submission_date] );
        
        parent::add($this->form);
    }
    
    /**
     * Save form data
     * @param $param Request
     */
    public static function onSave($param)
    {
        try
        {
            TTransaction::open('communication');
            $object = SystemDocument::find($param['id']);
            $object->fromArray( $param );
            $object->checkPermission();
            $object->store();
            
            TTransaction::close();

            TToast::show('success', _t('Record saved'));
            
            AdiantiCoreApplication::loadPage('SystemDriveList', 'onLoad', [
                'path' => TSession::getValue('SystemDriveListpath'),
                'filter' => TSession::getValue('SystemDriveListfilter')
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
            TToast::show('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
