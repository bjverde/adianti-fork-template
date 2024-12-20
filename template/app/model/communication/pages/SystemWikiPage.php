<?php
/**
 * SystemWikiPage
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemWikiPage extends TRecord
{
    const TABLENAME = 'system_wiki_page';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    const CREATEDBY = 'system_user_id';
    const CREATEDAT = 'created_at';
    const UPDATEDBY = 'updated_by';
    const UPDATEDAT = 'updated_at';
    
    private $creator;
    private $updater;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('title');
        parent::addAttribute('description');
        parent::addAttribute('content');
        parent::addAttribute('active');
        parent::addAttribute('searchable');
        parent::addAttribute('updated_by');
    }

    /**
     * Remove parts
     */
    public function clearParts()
    {
        SystemWikiTag::where('system_wiki_page_id', '=', $this->id)->delete();
        SystemWikiShareGroup::where('system_wiki_page_id', '=', $this->id)->delete();
    }

    /**
     * Return user
     */
    public function get_author()
    {
        if (empty($this->creator) && !empty($this->system_user_id))
        {
            $this->creator = SystemUser::findInTransaction('permission', $this->system_user_id);
        }

        return $this->creator;
    }

    /**
     * Return user
     */
    public function get_updater()
    {
        if (empty($this->updater) && ! empty($this->updated_by))
        {
            $this->updater = SystemUser::findInTransaction('permission', $this->updated_by);
        }

        return $this->updater;
    }

    /**
     * Get date create formated
     */
    public function get_date_created()
    {
        return date('d/m/Y H:i', strtotime($this->created_at));
    }

    /**
     * Get date updated formated
     */
    public function get_date_updated()
    {
        if ($this->updated_at)
        {
            return date('d/m/Y H:i', strtotime($this->updated_at));
        }

        return $this->get_date_created();
    }

    /**
     * List tags
     */
    public function getTags()
    {
        return SystemWikiTag::where('system_wiki_page_id', '=', $this->id)->getIndexedArray('tag', 'tag');
    }
}
