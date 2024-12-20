<?php
/**
 * SystemFolder
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemFolder extends TRecord
{
    const TABLENAME = 'system_folder';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    const CREATEDAT = 'created_at';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('name');
        parent::addAttribute('created_at');
        parent::addAttribute('system_folder_parent_id');
        parent::addAttribute('in_trash');
    }

    /*
     * Return is bookmark
     */
    public function isBookmark($userid)
    {
        return SystemFolderBookmark::where('system_user_id', '=', $userid)->where('system_folder_id', '=', $this->id)->count() > 0;
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
     * Return parent folder
     */
    public function get_system_folder_parent()
    {
        if ( $this->system_folder_parent_id )
        {
            return SystemFolder::find($this->system_folder_parent_id);
        }

        return null;
    }
    
    /**
     * Return the user/group folders
     */
    public static function getFolders($userid = null, $group_ids = null)
    {
        $folders = self::where('system_folder_parent_id', 'is', null)->where('', '', "NOESC: (in_trash is null OR in_trash = 'N')")->orderBy('name', 'asc')->load();

        $paths = [];

        if ($folders)
        {
            foreach($folders as $folder)
            {
                $paths = self::getRecursivePaths($folder, $paths, '', $userid , $group_ids); 
            }
        }

        return $paths;
    }
    
    /**
     * Get recursive folder paths that a user/group have permission
     */
    private static function getRecursivePaths($folder, $paths, $oldName, $userid = null, $group_ids = null)
    {
        if ($userid && $group_ids)
        {
            if ($folder->isShared($userid, $group_ids) || $folder->system_user_id == $userid)
            {
                $paths[$folder->id] = $oldName . $folder->name;
            }
        }
        else
        {
            $paths[$folder->id] = $oldName . $folder->name;
        }

        $childs = self::where('system_folder_parent_id', '=', $folder->id)->where('', '', "NOESC: (in_trash is null OR in_trash = 'N')")->orderBy('name', 'asc')->load();

        if ($childs)
        {
            foreach($childs as $child)
            {
                $paths = self::getRecursivePaths($child, $paths, $oldName . $folder->name . '/');
            }
        }

        return $paths;
    }
    
    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        if ($this->id)
        {
            // delete the related objects
            $criteria = new TCriteria;
            $criteria->add(new TFilter('system_folder_id', '=', $this->id));
            
            $repository = new TRepository('SystemFolderUser');
            $repository->delete($criteria);
            
            $repository = new TRepository('SystemFolderGroup');
            $repository->delete($criteria);

            $repository = new TRepository('SystemFolderBookmark');
            $repository->delete($criteria);
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
        $criteria->add(new TFilter('system_folder_id', '=', $id));
        
        $repository = new TRepository('SystemFolderUser');
        $repository->delete($criteria);
        
        $repository = new TRepository('SystemFolderGroup');
        $repository->delete($criteria); 
        
        $repository = new TRepository('SystemFolderBookmark');
        $repository->delete($criteria);

        $files = $this->getFiles();

        if ($files)
        {
            foreach($files as $file)
            {
                $file->delete();
            }
        }

        $folders = $this->getChildFolders();

        if ($folders)
        {
            foreach($folders as $folder)
            {
                $folder->delete();
            }
        }
        
        // delete the object itself
        parent::delete($id);
    }
    
    /**
     * Get folder files
     */
    public function getFiles()
    {
        return SystemDocument::where('system_folder_id', '=', $this->id)->load();
    }
    
    /**
     * Get folder child folders
     */
    public function getChildFolders()
    {
        return SystemFolder::where('system_folder_parent_id', '=', $this->id)->load();
    }
    
    /**
     * @return Collection of SystemGroup
     */
    public function getSystemGroups()
    {
        return SystemFolderGroup::where('system_folder_id', '=', $this->id)->getIndexedArray('system_group_id', 'system_group_id');
    }
    
    /**
     * @return Collection of SystemUserGroup
     */
    public function getSystemUsers()
    {
        return SystemFolderUser::where('system_folder_id', '=', $this->id)->getIndexedArray('system_user_id', 'system_user_id');
    }
    
    /**
     * Check if the user/group has folder permmission
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

        if ($this->get_system_folder_parent()->hasPermission($userid))
        {
            return true;
        }

        return false;
    }
    
    /**
     * check if the folder is shared with the user/group
     */
    public function isShared($userid, $usergroupids)
    {
        if ($this->in_trash !== 'Y')
        {
            if ($this->hasUserAccess($userid) || $this->hasGroupAccess($usergroupids))
            {
                return true;
            }
    
            if ($this->get_system_folder_parent() && $this->get_system_folder_parent()->isShared($userid, $usergroupids))
            {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Check if the folder is in trash
     */
    public function isTrashed()
    {
        if ($this->in_trash == 'Y')
        {
            return true;
        }

        if ($this->get_system_folder_parent() && $this->get_system_folder_parent()->isTrashed())
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
        return (SystemFolderUser::where('system_user_id','=', $userid)
                                ->where('system_folder_id', '=', $this->id)->count() > 0);
    }
    
    /**
     * Check if the group has access to the document
     */
    public function hasGroupAccess($usergroupids)
    {
        return (SystemFolderGroup::where('system_group_id','IN', $usergroupids)
                                 ->where('system_folder_id', '=', $this->id)->count() > 0);
    }
}
