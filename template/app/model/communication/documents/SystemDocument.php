<?php
/**
 * SystemDocument
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDocument extends TRecord
{
    const TABLENAME  = 'system_document';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('title');
        parent::addAttribute('description');
        parent::addAttribute('submission_date');
        parent::addAttribute('archive_date');
        parent::addAttribute('filename');
        parent::addAttribute('system_folder_id');
        parent::addAttribute('in_trash');
        parent::addAttribute('content');
        parent::addAttribute('content_type');
    }
    
    /**
     *
     */
    public function checkPermission()
    {
        if ($this->system_user_id !== TSession::getValue('userid'))
        {
            throw new Exception(_t('Permission denied'));
        }
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

        if (!empty($this->filename))
        {
            $path = "files/system/documents/{$id}/".$this->filename; 
            
            if (file_exists($path))
            {
                unlink($path);
            }
        }
        
        // delete the object itself
        parent::delete($id);
    }
    
    /**
     * @return Collection of SystemGroup
     */
    public function getSystemGroups()
    {
        return SystemDocumentGroup::where('document_id', '=', $this->id)->getIndexedArray('system_group_id', 'system_group_id');
    }
    
    /**
     * @return Collection of SystemUserGroup
     */
    public function getSystemUsers()
    {
        $users = array();
        return SystemDocumentUser::where('document_id', '=', $this->id)->getIndexedArray('system_user_id', 'system_user_id');
    }
    
    /**
     *
     */
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
