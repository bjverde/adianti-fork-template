<?php
/**
 * SystemFolderForm
 *
 * @version    8.0
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
        parent::setSize(500, null);
        parent::setMinWidth(0.8, 500);
        
        $this->form = new BootstrapFormBuilder('form_SystemFolder');
        $this->form->setProperty('class', 'card noborder');
        $this->form->setFieldSizes('100%');
        $this->form->enableClientValidation();
        
        $id   = new THidden('id');
        $name = new TEntry('name');
        $system_folder_parent_id = new THidden('system_folder_parent_id');
        
        $system_folder_parent_id->setValue($param['path'] ?? null);
        
        $this->form->addFields( [$id] );
        $this->form->addFields( [$system_folder_parent_id] );
        $this->form->addFields( [new TLabel(_t('Name'))] );
        $this->form->addFields( [$name] );
        
        $name->addValidation( _t('Name'), new TRequiredValidator );
        
        $btn = $this->form->addAction(_t('Create'), new TAction(array($this, 'onSave')), 'fa:check');
        $btn->class = 'btn btn-sm btn-primary';
        
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
            
            $object = new SystemFolder; 
            $object->fromArray( $param );
            $object->system_user_id = TSession::getValue('userid');
            
            $repos = SystemFolder::where('system_user_id', '=', TSession::getValue('userid'))
                                 ->where('name', '=', $param['name']);
            
            if (!empty($param['system_folder_parent_id']))
            {
               $repos->where('system_folder_parent_id', '=', $param['system_folder_parent_id']);
            }
            
            if ($repos->first() instanceof SystemFolder)
            {
                throw new Exception(_t('This folder already exists'));
            }
            
            if (!empty($object->id) && $object->id == $object->system_folder_parent_id)
            {
                throw new Exception((string) _t('This operation is not allowed'));
            }
            
            $object->store();
            TTransaction::close();
            
            TToast::show('info', _t('Record saved'));
            
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
    public function onLoad( $param )
    {
        $object = new stdClass;
        $object->path = $param['path'] ?? null;
        $this->form->setData($object);
    }
}
