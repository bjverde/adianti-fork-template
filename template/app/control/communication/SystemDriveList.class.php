<?php
/**
 * SystemDriveList
 *
 * @version    7.6
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDriveList extends TPage
{
    private $html;

    public function __construct($param = null)
    {
        parent::__construct();
    
        $this->html = new THtmlRenderer('app/resources/system_drive.html');
        $this->html->enableTranslation();

        $actions = [];
        $actions[] = [ _t('Open'), [$this, 'onOpen'], 'fa:search gray fa-fw', [$this, 'onDisplayOpen'] ];
        $actions[] = [ _t('Back'), [$this, 'onOpen'], 'fas:chevron-left gray fa-fw', [$this, 'onDisplayBack'] ];
        $actions[] = [ _t('Send to trash'), [$this, 'onTrash'], 'far:trash-alt red fa-fw', [$this, 'onDisplayTrash'] ];
        $actions[] = [ _t('Permanently delete'), [$this, 'onPermanentlyDelete'], 'fa:times red fa-fw', [$this, 'onDisplayPermanentlyDelete'] ];
        $actions[] = [ _t('Remove from trash'), [$this, 'onRemoveTrash'], 'fas:trash-restore red fa-fw', [$this, 'onDisplayRemoveTrash'] ];
        $actions[] = [ _t('Set bookmark'), [$this, 'onBookmark'], 'fas:star orange fa-fw', [$this, 'onDisplayBookmark'] ];
        $actions[] = [ _t('Remove from bookmark'), [$this, 'onRemoveBookmark'], 'far:star orange fa-fw', [$this, 'onDisplayRemoveBookmark'] ];
        $actions[] = [ _t('Share'), [$this, 'onShare'], 'fa:share-alt #4caf50 fa-fw', [$this, 'onDisplayShare'] ];
        $actions[] = [ _t('Properties'), [$this, 'onProperties'], 'fa:cog #2196f3 fa-fw', [$this, 'onDisplayProperties'] ];
        
        $this->dataview = new TIconView;
        $this->dataview->enableDoubleClick();
        $this->dataview->setIconAttribute('icon');
        $this->dataview->setLabelAttribute('title');
        $this->dataview->setInfoAttributes(['type', 'id', 'title']);
        $this->dataview->setItemTemplate( file_get_contents( 'app/resources/system_file_item.html') );
        $this->dataview->enableMoveAction( new TAction( [$this, 'onDragMove'] ), ['type' => ['file', 'folder']], ['type'=> ['folder', 'folder_back']] );
        
        $path = $param['path'] ?? '';
        $name = $param['name'] ?? TSession::getValue(__CLASS__. 'name');
        $type = $param['filter'] ?? TSession::getValue(__CLASS__. 'filter') ?? 'my';

        $parameters = ['register_state'  => 'false', 'static' => 1, 'path' => $path, 'filter' => $type];
        
        foreach ($actions as $action)
        {
            $action_label     = $action[0];
            $action_callback  = $action[1];
            $action_icon      = $action[2];
            $action_condition = isset($action[3]) ? $action[3] : null;

            $this->dataview->addContextMenuOption($action_label, new TAction($action_callback, $parameters), $action_icon, $action_condition);
        }
        
        $fuse = new TEntry('fuse');
        $fuse->setProperty("autocomplete", "off");
        $fuse->setProperty('placeholder', _t('Search'));

        $global = new TEntry('global');
        $global->setProperty("autocomplete", "off");
        $global->setProperty('placeholder', _t('Global search'));
        $global->setExitAction(new TAction([$this, 'onSearchGlobal'], $parameters));
        $global->setValue($name);

        if (! $name)
        {
            $global->{'style'} = 'display: none';
        }
        
        $more = new TButton('more');
        $more->type = 'button';
        $more->addFunction("$('[name=global]').toggle();");
        $more->setLabel(_t('More'));
        $more->setImage('fas:sliders-h #017bff');

        $formLine = new TElement('div');
        $formLine->add($fuse);
        $formLine->add($more);
        $formLine->add($global);
        $formLine->{'style'} = 'display: flex; flex-direction: row; justify-content: flex-end;position: relative; gap: 5px;';
        
        $this->form = new TForm('form_document_list');
        $this->form->add($formLine);
        $this->form->addField($fuse);
        $this->form->addField($global);

        $this->html->enableSection('main', [
            'breadcrumb' => $this->getBreadCrumb($type, $path),
            'path' => $path,
            'active_my' => $type == 'my'? 'active' : '',
            'active_shared' => $type == 'shared' ? 'active' : '',
            'active_trash' => $type == 'in_trash'? 'active' : '',
            'active_bookmark' => $type == 'bookmark' ? 'active' : '',
            'form' => $this->form,
            'list' => $this->dataview
        ]);

        parent::add($this->html);
    }

    /**
     * Make breadcrumb
     */
    private function getBreadCrumb($type, $path)
    {
        $breadcrumb = new TBreadCrumb();
        $breadcrumb->{'style'} = 'display: flex;';

        try
        {
            $homes = [
                'my' => _t('My documents'),
                'shared' => _t('Shared with me'),
                'trash' => _t('Trash'),
                'bookmark' => _t('Bookmarks')
            ];
            
            $breadcrumb->addItem($homes[$type], empty($path));
            
            if ($path)
            {
                TTransaction::open('communication');
                $items = $this->addStepBreadCrumb($path, []);
                arsort($items);
                foreach($items as $key => $name)
                {
                    $breadcrumb->addItem($name, $key == 0);
                }
                TTransaction::close();
            }
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }

        return $breadcrumb;
    }

    /**
     * Make folders steps
     */
    private function addStepBreadCrumb($path, $breadcrumb)
    {
        if ($path)
        {
            $folder = SystemFolder::find($path);
            
            $breadcrumb[] = $folder->name;

            $breadcrumb = $this->addStepBreadCrumb($folder->system_folder_parent_id, $breadcrumb);
        }

        return $breadcrumb;
    }

    /**
     * Process drop
     */
    public static function onDragMove($param)
    {
        try
        {
            TTransaction::open('communication');

            if ($param['source_type'] == 'folder' && $param['target_type'] == 'folder')
            {
                self::moveFolderIntoFolder($param);
            }
            
            if ($param['source_type'] == 'folder' && $param['target_type'] == 'folder_back')
            {
                self::moveFolderOut($param);
            }

            if ($param['source_type'] == 'file' && $param['target_type'] == 'folder_back')
            {
                self::moveFileOut($param);
            }

            if ($param['source_type'] == 'file' && $param['target_type'] == 'folder')
            {
                self::moveFileIntoFolder($param);
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Move folder into folder
     */
    private static function moveFolderIntoFolder($param)
    {
        $folder_target_id = (int) str_replace('system_folder_', '', $param['target_id']);
        $folder_source_id = (int) str_replace('system_folder_', '', $param['source_id']);

        $folder = SystemFolder::find($folder_source_id);
        $folder->system_folder_parent_id = $folder_target_id;
        $folder->store();

        $folder = SystemFolder::find($folder_target_id);
        
        TScript::create("$('#{$param['source_id']}').remove()");
        TToast::show('success', _t('Sent to ^1', $folder->name), 'bottom right');
    }

    /**
     * Move folder out
     */
    private static function moveFolderOut($param)
    {
        $folder_id = (int) str_replace('system_folder_', '', $param['source_id']);

        $folder = SystemFolder::find($folder_id);
        $folder->system_folder_parent_id = $folder->system_folder_parent->system_folder_parent_id;
        $folder->store();
        
        TScript::create("$('#{$param['source_id']}').remove()");
        TToast::show('success', _t('Sent out'), 'bottom right');
    }

    /**
     * Move file into folder
     */
    private static function moveFileIntoFolder($param)
    {
        $folder_id = (int) str_replace('system_folder_', '', $param['target_id']);
        $file_id = (int) str_replace('system_document_', '', $param['source_id']);

        $file = SystemDocument::find($file_id);
        $file->system_folder_id = $folder_id;
        $file->store();

        $folder = SystemFolder::find($folder_id);

        TScript::create("$('#{$param['source_id']}').remove()");
        TToast::show('success', _t('Sent to ^1', $folder->name), 'bottom right');
    }

    /**
     * Move file out
     */
    private static function moveFileOut($param)
    {
        $file_id = (int) str_replace('system_document_', '', $param['source_id']);

        $file = SystemDocument::find($file_id);
        $file->system_folder_id = $file->system_folder->system_folder_parent_id;
        $file->store();
        
        TScript::create("$('#{$param['source_id']}').remove()");
        TToast::show('success', _t('Sent out'), 'bottom right');
    }

    /**
     * Can display open
     */
    public function onDisplayOpen($object)
    {
        return $object->in_trash != 'Y' && $object->type != 'folder_back';
    }

    /**
     * Can display infos
     */
    public function onDisplayProperties($object)
    {
        return $object->in_trash != 'Y' && $object->type != 'folder_back';
    }

    /**
     * Can display share
     */
    public function onDisplayShare($object)
    {
        return $object->in_trash != 'Y' && $object->type != 'folder_back' &&  $object->system_user_id == TSession::getValue('userid');
    }

    /**
     * Can display permanently delete
     */
    public function onDisplayPermanentlyDelete($object)
    {
        return $object->type != 'folder_back' && $object->in_trash == 'Y' && $object->system_user_id == TSession::getValue('userid');
    }

    /**
     * Can display bookmark
     */
    public function onDisplayBookmark($object)
    {
        return $object->in_trash != 'Y' && $object->type != 'folder_back' && ! $object->bookmark;
    }

    /**
     * Can display remove bookmark
     */
    public function onDisplayRemoveBookmark($object)
    {
        return $object->in_trash != 'Y' && $object->type != 'folder_back' && $object->bookmark;
    }

    /**
     * Can display back folder
     */
    public function onDisplayBack($object)
    {
        return $object->in_trash != 'Y' && $object->type == 'folder_back';
    }

    /**
     * Can display move to trash
     */
    public function onDisplayTrash($object)
    {
        return $object->type != 'folder_back' && $object->in_trash != 'Y' && $object->system_user_id == TSession::getValue('userid');
    }
    
    /**
     * Can display move to out trash
     */
    public function onDisplayRemoveTrash($object)
    {
        return $object->type != 'folder_back' && $object->in_trash == 'Y' && $object->system_user_id == TSession::getValue('userid');
    }

    /**
     * Open folder/document
     */
    public static function onOpen($param)
    {
        try
        {
            TTransaction::open('communication');

            if (! empty($param['type']) && $param['type'] == 'folder')
            {
                $id = (int) str_replace('system_folder_', '', $param['id']);

                AdiantiCoreApplication::loadPage(__CLASS__, 'onLoad', ['path' => $id, 'filter' => $param['filter']]);
            }
            elseif (! empty($param['type']) && $param['type'] == 'folder_back')
            {
                $id = (int) str_replace('folder_back_', '', $param['id']);
                $folder = SystemFolder::find($id);

                AdiantiCoreApplication::loadPage(__CLASS__, 'onLoad', ['path' => $folder->system_folder_parent_id ?? '', 'filter' => $param['filter']]);
            }
            else
            {
                $id = (int) str_replace('system_document_', '', $param['id']);

                $object = new SystemDocument($id); // instantiates the Active Record
                
                if ($object->hasPermission(TSession::getValue('userid'), TSession::getValue('usergroupids')))
                {
                    if (strtolower(substr($object->filename, -4)) == 'html')
                    {
                        $win = TWindow::create( $object->filename, 0.8, 0.8 );
                        $win->add( file_get_contents( "files/documents/{$id}/".$object->filename ) );
                        $win->show();
                    }
                    else
                    {
                        TPage::openFile("files/documents/{$id}/".$object->filename);
                    }
                }
                else
                {
                    new TMessage('error', _t('Permission denied'));
                }
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }        
    }

    /**
     * Show info folder/document
     */
    public static function onProperties($param)
    {
        if (! empty($param['type']) && $param['type'] == 'folder')
        {
            $id = (int) str_replace('system_folder_', '', $param['id']);

            AdiantiCoreApplication::loadPage('SystemFolderFormView', 'onEdit', ['key' => $id]);
        }
        else
        {
            $id = (int) str_replace('system_document_', '', $param['id']);

            AdiantiCoreApplication::loadPage('SystemDocumentFormWindow', 'onEdit', ['key' => $id]);
        }
    }

    /**
     * Untag favorite
     */
    public static function onRemoveBookmark($param)
    {
        try
        {
            TTransaction::open('communication');
            if ($param['type'] == 'file')
            {
                $id = (int) str_replace('system_document_', '', $param['id']);

                SystemDocumentBookmark::where('system_document_id', '=', $id)->where('system_user_id', '=', TSession::getValue('userid'))->delete();
            }
            else if ($param['type'] == 'folder')
            {
                $id = (int) str_replace('system_folder_', '', $param['id']);

                SystemFolderBookmark::where('system_folder_id', '=', $id)->where('system_user_id', '=', TSession::getValue('userid'))->delete();
            }
            
            TTransaction::close();

            unset($param['static']);
            AdiantiCoreApplication::loadPage(__CLASS__, 'onLoad', $param);
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Share
     */
    public static function onShare($param)
    {
        try
        {
            if ($param['type'] == 'file')
            {
                $id = (int) str_replace('system_document_', '', $param['id']);

                unset($param['static']);
                AdiantiCoreApplication::loadPage('SystemDocumentShareForm', 'onEdit', ['key' => $id]);
            }
            else if ($param['type'] == 'folder')
            {
                $id = (int) str_replace('system_folder_', '', $param['id']);
                
                unset($param['static']);
                AdiantiCoreApplication::loadPage('SystemFolderShareForm', 'onEdit', ['key' => $id]);
            }
        }
        catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Tag favorite
     */
    public static function onBookmark($param)
    {
        try
        {
            TTransaction::open('communication');
            if ($param['type'] == 'file')
            {
                $id = (int) str_replace('system_document_', '', $param['id']);

                $book = new  SystemDocumentBookmark();
                $book->system_document_id = $id;
                $book->system_user_id = TSession::getValue('userid');
                $book->store();
            }
            else if ($param['type'] == 'folder')
            {
                $id = (int) str_replace('system_folder_', '', $param['id']);

                $book = new  SystemFolderBookmark();
                $book->system_folder_id = $id;
                $book->system_user_id = TSession::getValue('userid');
                $book->store();
            }
            
            TTransaction::close();
            
            unset($param['static']);
            AdiantiCoreApplication::loadPage(__CLASS__, 'onLoad', $param);
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Ask before deletion
     */
    public static function onPermanentlyDelete($param)
    {
        try
        {
            TTransaction::open('communication');

            if (! empty($param['permanently_delete']) && $param['permanently_delete'] == 1)
            {
                if ($param['type'] == 'file')
                {
                    $id = (int) str_replace('system_document_', '', $param['id']);
                
                    $object = new SystemDocument($id, FALSE);

                    if ($object->system_user_id == TSession::getValue('userid'))
                    {
                        $object->delete();

                        TScript::create("$('#{$param['id']}').remove()");
                        TToast::show('success', _t('Record deleted'), 'bottom right');
                    }
                    else
                    {
                        throw new Exception((string) _t('Permission denied'));
                    }
                }
                else
                {
                    $id = (int) str_replace('system_folder_', '', $param['id']);
                
                    $object = new SystemFolder($id, FALSE);

                    if ($object->system_user_id == TSession::getValue('userid'))
                    {
                        $object->delete();

                        TScript::create("$('#{$param['id']}').remove()");
                        TToast::show('success', _t('Record deleted'), 'bottom right');
                    }
                    else
                    {
                        throw new Exception((string) _t('Permission denied'));
                    }
                }
            }
            else
            {
                $param['permanently_delete'] = 1;
                $action = new TAction([__CLASS__, 'onPermanentlyDelete'], $param);

                $complement = $param['type'] == 'folder' ?  _t('This will remove all the contents of the folder') : '';

                new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?') . '<br/>' . $complement, $action);
            }

            TTransaction::close();
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Move to trash
     */
    public function onTrash($param)
    {
        try
        {
            TTransaction::open('communication');
            if ($param['type'] == 'file')
            {
                $id = (int) str_replace('system_document_', '', $param['id']);

                $document = SystemDocument::find($id);
                $document->in_trash = 'Y';
                $document->store();
            }
            else if ($param['type'] == 'folder')
            {
                $id = (int) str_replace('system_folder_', '', $param['id']);

                $folder = SystemFolder::find($id);
                $folder->in_trash = 'Y';
                $folder->store();
            }
            
            TTransaction::close();
            
            TScript::create("$('#{$param['id']}').remove()");
            TToast::show('success', _t('Sent to trash'), 'bottom right');
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Remove from trash
     */
    public function onRemoveTrash($param)
    {
        try
        {
            TTransaction::open('communication');
            if ($param['type'] == 'file')
            {
                $id = (int) str_replace('system_document_', '', $param['id']);

                $document = SystemDocument::find($id);
                $document->in_trash = 'N';
                $document->store();
            }
            else if ($param['type'] == 'folder')
            {
                $id = (int) str_replace('system_folder_', '', $param['id']);

                $folder = SystemFolder::find($id);
                $folder->in_trash = 'N';
                $folder->store();
            }
            
            TTransaction::close();
            
            TScript::create("$('#{$param['id']}').remove()");
            TToast::show('success', _t('Restored'), 'bottom right');
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Define session variables
     */
    private function setSessionVariables($param)
    {
        if (isset($param['path']))
        {
            TSession::setValue(__CLASS__. 'path', $param['path']);
        }

        if (isset($param['filter']))
        {
            TSession::setValue(__CLASS__. 'filter', $param['filter']);
        }
        else
        {
            TSession::setValue(__CLASS__. 'filter', 'my');
        }
    }

    /**
     * Add back button
     */
    private function addBackButton()
    {
        if (TSession::getValue(__CLASS__. 'path'))
        {
            $object = new stdClass;
            $object->{'icon'} = 'fas fa-chevron-left';
            $object->{'info'} = '';
            $object->{'title'} = _t('Back');
            $object->{'connector'} = '';
            $object->{'type'} = 'folder_back';
            $object->{'id'} = 'folder_back_'.TSession::getValue(__CLASS__. 'path');
            $object->{'object_type'} = '';
            $object->{'in_trash'} = false;
            $object->{'date'} = '';
            $object->{'key'} = $object->{'info'};
            $this->dataview->addItem($object);
        }
    }

    public static function onSearchGlobal($param)
    {
        $parameters = ['path' => '', 'filter' => $param['filter']];
        
        if (! empty($param['global']))
        {
            $parameters['name'] = $param['global'];
        }

        TSession::setValue(__CLASS__. 'name', $param['global'] ?? null);

        AdiantiCoreApplication::loadPage(__CLASS__, 'onLoad', $parameters);
    }

    /**
     * Load folders
     */
    private function loadFolders()
    {
        $folderRepository = new TRepository('SystemFolder');
        
        $name  = TSession::getValue(__CLASS__. 'name');
        $userid = (int) TSession::getValue('userid');
        $usergroupids  = TSession::getValue('usergroupids');
        $path = TSession::getValue(__CLASS__. 'path');
        $filter = TSession::getValue(__CLASS__. 'filter');
        $groupsids  = implode(',',$usergroupids);

        $criteria = new TCriteria;

        if ($filter == 'my')
        {
            $criteria->add(new TFilter('system_user_id', '=', $userid));
            
            if ($name)
            {
                $criteria->add(new TFilter('name', 'like', '%' . $name .'%'));
            }
            else
            {
                if (! $path)
                {
                    $criteria->add(new TFilter('system_folder_parent_id', 'IS', NULL));
                }
                else
                {
                    $criteria->add(new TFilter('system_folder_parent_id', '=', $path));
                }
            }
        }
        else if ($filter == 'bookmark')
        {
            $criteria->add(new TFilter('id', 'in', "(SELECT system_folder_id FROM system_folder_bookmark WHERE system_user_id = {$userid})"));
        }
        else if ($filter == 'trash')
        {
            $criteria->add(new TFilter('system_user_id', '=', $userid));
            $criteria->add(new TFilter('in_trash', '=', 'Y'));
        }
        else
        {
            $criteriaPermission = new TCriteria;

            $criteria->add(new TFilter('system_user_id', '!=', $userid));

            // is shared to user
            $criteriaPermission->add(new TFilter('id', 'in', "(SELECT system_folder_id FROM system_folder_user WHERE system_user_id = {$userid})"));
            // is shared to group
            $criteriaPermission->add(new TFilter('id', 'in', "(SELECT system_folder_id FROM system_folder_group WHERE system_group_id in ({$groupsids}) )"), TExpression::OR_OPERATOR);
            
            if ($name)
            {
                $criteria->add(new TFilter('name', 'like', '%' . $name .'%'));
            }
            else
            {
                if ($path)
                {
                    $criteria->add(new TFilter('system_folder_parent_id', '=', $path));
    
                    // validate permission
                    $folder  = SystemFolder::find($path);
    
                    if ($folder->isShared($userid, $usergroupids))
                    {
                        $criteriaPermission->add(new TFilter('system_folder_parent_id', '=', $path), TExpression::OR_OPERATOR);
                    }
                }
            }

            $criteria->add($criteriaPermission);
        }
        
        if (in_array($filter, ['my', 'shared', 'bookmark']))
        {
            $criteriaTrash = new TCriteria;
            $criteriaTrash->add(new TFilter('in_trash', '=', 'N'));
            $criteriaTrash->add(new TFilter('in_trash', 'IS', NULL), TExpression::OR_OPERATOR);

            $criteria->add($criteriaTrash);
        }

        $folders = $folderRepository->load($criteria, FALSE);

        if ($folders)
        {
            foreach($folders as $folder)
            {
                if (in_array($filter, ['my', 'shared', 'bookmark']) && $folder->isTrashed() )
                {
                    continue;
                }

                $object = new stdClass;
                $object->{'title'} = AdiantiStringConversion::assureUnicode($folder->name);
                $object->{'type'} = 'folder';
                $object->{'id'} = 'system_folder_' . $folder->id;
                $object->{'icon'} = 'far fa-folder';
                $object->{'system_user_id'} = $folder->system_user_id;
                $object->{'bookmark'} = $folder->isBookmark(TSession::getValue('userid'));
                $object->{'in_trash'} = $folder->in_trash;

                $this->dataview->addItem($object);
            }
        }
    }

    /**
     * Load files
     */
    private function loadFiles()
    {
        $repository = new TRepository('SystemDocument');

        $name  = TSession::getValue(__CLASS__. 'name');
        $filter  = TSession::getValue(__CLASS__. 'filter');
        $userid = (int) TSession::getValue('userid');
        $usergroupids  = TSession::getValue('usergroupids');
        $groupsids  = implode(',',$usergroupids);
        $path = TSession::getValue(__CLASS__. 'path');

        $criteria = new TCriteria;
        
        if ($filter == 'my')
        {
            $criteria->add(new TFilter('system_user_id', '=',$userid));

            if ($name)
            {
                $criteriaName = new TCriteria;
                $criteriaName->add(new TFilter('title', 'like', '%' . $name .'%'));
                $criteriaName->add(new TFilter('filename', 'like', '%' . $name .'%'), TExpression::OR_OPERATOR);
                
                $criteria->add($criteriaName);
            }
            else
            {
                if (! $path)
                {
                    $criteria->add(new TFilter('system_folder_id', 'IS', NULL));
                }
                else
                {
                    $criteria->add(new TFilter('system_folder_id', '=', $path));
                }
            }
        }
        else if ($filter == 'bookmark')
        {
            $criteria->add(new TFilter('id', 'in', "(SELECT system_document_id FROM system_document_bookmark WHERE system_user_id = {$userid})"));
        }
        else if ($filter == 'trash')
        {
            $criteria->add(new TFilter('system_user_id', '=', $userid));
            $criteria->add(new TFilter('in_trash', '=', 'Y'));
        }
        else
        {
            $criteriaPermission = new TCriteria;
            
            $criteria->add(new TFilter('system_user_id', '!=',$userid));
            
            // is shared to user
            $criteriaPermission->add(new TFilter('id', 'in', "(SELECT document_id FROM system_document_user WHERE system_user_id = {$userid})"));
            // is shared to group
            $criteriaPermission->add(new TFilter('id', 'in', "(SELECT document_id FROM system_document_group WHERE system_group_id in ({$groupsids}) )"), TExpression::OR_OPERATOR);

            if ($name)
            {
                $criteriaName = new TCriteria;
                $criteriaName->add(new TFilter('title', 'like', '%' . $name .'%'));
                $criteriaName->add(new TFilter('filename', 'like', '%' . $name .'%'), TExpression::OR_OPERATOR);
                
                $criteria->add($criteriaName);
            }
            else
            {
                if ($path)
                {
                    // validate permission
                    $folder  = SystemFolder::find($path);
                    
                    if ($folder->isShared($userid, $usergroupids))
                    {
                        $criteriaPermission->add(new TFilter('system_folder_id', '=', $path), TExpression::OR_OPERATOR);
                    }
    
                    $criteria->add(new TFilter('system_folder_id', '=', $path));
                }
            }

            $criteria->add($criteriaPermission);
        }

        if (in_array($filter, ['my', 'shared', 'bookmark']))
        {
            $criteriaTrash = new TCriteria;
            $criteriaTrash->add(new TFilter('in_trash', '=', 'N'));
            $criteriaTrash->add(new TFilter('in_trash', 'IS', NULL), TExpression::OR_OPERATOR);

            $criteria->add($criteriaTrash);
        }

        $documents = $repository->load($criteria, FALSE);
        
        if ($documents)
        {
            foreach ($documents as $document)
            {
                $object = new stdClass;
                $object->{'title'} = AdiantiStringConversion::assureUnicode($document->title);
                $object->{'type'} = 'file';
                $object->{'system_user_id'} = $document->system_user_id;
                $object->{'id'} = 'system_document_' . $document->id;
                $object->{'bookmark'} = $document->isBookmark(TSession::getValue('userid'));
                $object->{'icon'} = SystemDocumentUploaderService::getFontAwesomeIcon("files/documents/{$document->id}/$document->filename");
                $object->{'in_trash'} = $document->in_trash;

                $this->dataview->addItem($object);
            }
        }
    }

    /**
     * Load folders e documents
     */
    public function onLoad($param)
    {
        try
        {
            TTransaction::open('communication');
            $this->setSessionVariables($param);
            
            $this->addBackButton();
            $this->loadFolders();
            $this->loadFiles();
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onLoad', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onLoad( func_get_arg(0) );
            }
            else
            {
                $this->onLoad([]);
            }
        }

        parent::show();
    }
}
