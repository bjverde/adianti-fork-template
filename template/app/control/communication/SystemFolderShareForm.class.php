<?php
/**
 * SystemFolderShareForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemFolderShareForm extends TWindow
{
    protected $form;
    protected $group_list;
    protected $user_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        parent::setTitle( _t('Share') );
        parent::setModal(TRUE);
        parent::removePadding();
        parent::setSize((TPage::isMobile() ? 0.9 : 0.5), null);

        $this->form = new BootstrapFormBuilder('SystemFolderShareForm');
        $this->form->setFieldSizes('100%');

        $id   = new TEntry('id');
        $id->setEditable(false);
        
        $row = $this->form->addFields([$id]);
        $row->style = 'display: none;';
        
        $this->group_list = new TCheckList('group_list');
        $this->group_list->setHeight(350);
        $this->group_list->makeScrollable();
        $this->group_list->setIdColumn('id');
        $this->group_list->addColumn('id',    'ID',    'center',  '10%');
        $col_name = $this->group_list->addColumn('name', _t('Name'),  'left', '90%');
        
        $col_name->enableSearch();
        $search_group = $col_name->getInputSearch();
        $search_group->placeholder = _t('Search');
        $search_group->style = 'margin-left: 4px; border-radius: 4px';
        
        $this->user_list = new TCheckList('user_list');
        $this->user_list->setHeight(350);
        $this->user_list->makeScrollable();
        $this->user_list->setIdColumn('id');
        $this->user_list->addColumn('id',    'ID',    'center',  '10%');
        $col_user = $this->user_list->addColumn('name', _t('Name'),    'left',   '90%');
        
        $col_user->enableSearch();
        $search_user = $col_user->getInputSearch();
        $search_user->placeholder = _t('Search');
        $search_user->style = 'margin-left: 4px; border-radius: 4px';
        
        $subform = new BootstrapFormBuilder;
        $subform->setProperty('style', 'border:none; box-shadow:none');
        
        $subform->appendPage( _t('Groups') );
        $subform->addFields( [$this->group_list] );
        
        $subform->appendPage( _t('Users') );
        $subform->addFields( [$this->user_list] );
        
        $this->form->addContent( [$subform] );
        
        TTransaction::open('permission');
        $this->group_list->addItems( SystemGroup::get() );
        $this->user_list->addItems( SystemUser::get() );
        TTransaction::close();
        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        parent::add($this->form);
    }
    
    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    public function onSave()
    {
        try
        {
            TTransaction::open('communication');
            
            $data = $this->form->getData();
            $this->form->setData($data);

            if ($data->id)
            {
                $object = SystemFolder::find($data->id);

                if ($object->system_user_id != TSession::getValue('userid'))
                {
                    throw new Exception((string) _t('Permission denied'));
                }
            }
            else
            {
                $object = new SystemFolder();
                $object->fromArray( (array) $data );
            }
            
            $object->store();

            SystemFolderGroup::where('system_folder_id', '=', $object->id)->delete();
            SystemFolderUser::where('system_folder_id', '=', $object->id)->delete();
            
            if (!empty($data->group_list))
            {
                foreach ($data->group_list as $group_id)
                {
                    TTransaction::open('permission');
                    $system_group = SystemGroup::find($group_id);
                    TTransaction::close();
                    $object->addSystemGroup($system_group);
                }
            }
            
            if (!empty($data->user_list))
            {
                foreach ($data->user_list as $user_id)
                {
                    TTransaction::open('permission');
                    $system_user = SystemUser::find($user_id);
                    TTransaction::close();
                    $object->addSystemUser($system_user);
                }
            }

            // validade bookmarks
            $criteria = new TCriteria;
            $criteria->add(new TFilter('system_folder_id', '=', $object->id)); // this folder
            $criteria->add(new TFilter('system_user_id', '!=', $object->system_user_id)); // not owner

            $criteria->add(new TFilter('system_user_id', 'not in', $data->user_list)); // not shared user
            
            if ($data->group_list)
            {
                TTransaction::open('permission');
                $users = SystemUserGroup::where('system_group_id', 'in', $data->group_list)->getIndexedArray('system_user_id', 'system_user_id');
                TTransaction::close();

                $criteria->add(new TFilter('system_user_id', 'in', $users)); // not shared group
            }
            
            $bookmarks = SystemFolderBookmark::getObjects($criteria);

            if ($bookmarks)
            {
                foreach($bookmarks as $bookmark)
                {
                    $bookmark->delete();
                }
            }

            $data = new stdClass;
            $data->id = $object->id;
            
            TTransaction::close();
            TWindow::closeWindow();
            TToast::show('success', _t('Record saved'), 'bottom right');
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * on edit share folder
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key=$param['key'];
                
                TTransaction::open('communication');
                $object = new SystemFolder($key);
                
                $groups_ids = array();
                foreach ($object->getSystemGroups() as $group)
                {
                    $groups_ids[] = $group->id;
                }
                
                $object->group_list = $groups_ids;
                
                $user_ids = array();
                foreach ($object->getSystemUsers() as $user)
                {
                    $user_ids[] = $user->id;
                }
                
                $object->user_list = $user_ids;
                
                $this->form->setData($object);
                
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
