<?php
/**
 * SystemDocument
 *
 * @version    7.6
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDocument extends TRecord
{
    const TABLENAME = 'system_document';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('title');
        parent::addAttribute('description');
        parent::addAttribute('category_id');
        parent::addAttribute('submission_date');
        parent::addAttribute('archive_date');
        parent::addAttribute('filename');
        parent::addAttribute('system_folder_id');
        parent::addAttribute('in_trash');
    }

    /**
     * Return category
     */
    public function get_category()
    {
        return SystemDocumentCategory::find($this->category_id);
    }

    /**
     * Return folder
     */
    public function get_system_folder()
    {
        return SystemFolder::find($this->system_folder_id);
    }
    
    /*
     * Return is bookmark
     */
    public function isBookmark($userid)
    {
        return SystemDocumentBookmark::where('system_user_id', '=', $userid)->where('system_document_id', '=', $this->id)->count() > 0;
    }
    
    /**
     * Return user
     */
    public function get_system_user()
    {
        TTransaction::open('permission');
        $user = SystemUser::find($this->system_user_id);
        TTransaction::close();
        return $user;
    }
    
    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        if ($this->id)
        {
            // delete the related System_userSystem_user_group objects
            $criteria = new TCriteria;
            $criteria->add(new TFilter('document_id', '=', $this->id));
            
            $repository = new TRepository('SystemDocumentUser');
            $repository->delete($criteria);
            
            $repository = new TRepository('SystemDocumentGroup');
            $repository->delete($criteria);

            $repository = new TRepository('SystemDocumentBookmark');
            $repository->where('system_document_id', '=', $this->id);
            $repository->delete();
        }   
    }
    
    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        // delete the related System_groupSystem_program objects
        $id = isset($id) ? $id : $this->id;
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('document_id', '=', $id));
        
        $repository = new TRepository('SystemDocumentUser');
        $repository->delete($criteria);
        
        $repository = new TRepository('SystemDocumentGroup');
        $repository->delete($criteria);
        
        $repository = new TRepository('SystemDocumentBookmark');
        $repository->where('system_document_id', '=', $id);
        $repository->delete();

        $path = "files/documents/{$id}/".$this->filename; 
        if (file_exists($path))
        {
            unlink($path);
        }
        
        // delete the object itself
        parent::delete($id);
    }
    
    /**
     * Add a SystemGroup
     * @param $object Instance of SystemGroup
     */
    public function addSystemGroup(SystemGroup $systemgroup)
    {
        $object = new SystemDocumentGroup;
        $object->system_group_id = $systemgroup->id;
        $object->document_id = $this->id;
        $object->store();
    }
    
    /**
     * Add a SystemUser
     * @param $object Instance of SystemUser
     */
    public function addSystemUser(SystemUser $systemuser)
    {
        $object = new SystemDocumentUser;
        $object->system_user_id = $systemuser->id;
        $object->document_id = $this->id;
        $object->store();
    }
    
    /**
     * @return Collection of SystemGroup
     */
    public function getSystemGroups()
    {
        $groups = array();
        $document_groups = SystemDocumentGroup::where('document_id', '=', $this->id)->load();
        if ($document_groups)
        {
            TTransaction::open('permission');
            foreach ($document_groups as $document_group)
            {
                $groups[] = new SystemGroup( $document_group->system_group_id );
            }
            TTransaction::close();
        }
        return $groups;
    }
    
    /**
     * @return Collection of SystemUser' ids
     */
    public function getSystemUsersIds()
    {
        $users = $this->getSystemUsers();
        $user_ids = array();
        if ($users)
        {
            foreach ($users as $user)
            {
                $user_ids[] = $user->id;
            }
        }
        return $user_ids;
    }
    
    /**
     * @return Collection of SystemGroup' ids
     */
    public function getSystemGroupsIds()
    {
        $groups = $this->getSystemGroups();
        $group_ids = array();
        if ($groups)
        {
            foreach ($groups as $group)
            {
                $group_ids[] = $group->id;
            }
        }
        return $group_ids;
    }
    
    /**
     * @return Collection of SystemUserGroup
     */
    public function getSystemUsers()
    {
        $users = array();
        $document_users = SystemDocumentUser::where('document_id', '=', $this->id)->load();
        if ($document_users)
        {
            TTransaction::open('permission');
            foreach ($document_users as $document_user)
            {
                $users[] = new SystemUser( $document_user->system_user_id );
            }
            TTransaction::close();
        }
        return $users;
    }

    public function hasPermission($userid, $usergroupids)
    {
        if ($this->system_user_id == $userid)
        {
            return true;
        }

        if ($this->hasUserAccess($userid) || $this->hasGroupAccess($usergroupids))
        {
            return true;
        }

        if ($this->get_system_folder()->hasPermission($userid, $usergroupids))
        {
            return true;
        }

        return false;
    }
    
    /**
     * Check if the user has access to the document
     */
    public function hasUserAccess($userid)
    {
        return (SystemDocumentUser::where('system_user_id','=', $userid)
                                  ->where('document_id', '=', $this->id)->count() >0);
    }

    /**
     * Check if the group has access to the document
     */
    public function hasGroupAccess($usergroupids)
    {
        return (SystemDocumentGroup::where('system_group_id','IN', $usergroupids)
                                   ->where('document_id', '=', $this->id)->count() >0);
    }
}
