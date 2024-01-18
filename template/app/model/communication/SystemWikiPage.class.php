<?php
/**
 * SystemWikiPage
 *
 * @version    7.6
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
    const CREATEDAT = 'created_at';
    const UPDATEDAT = 'updated_at';
    
    private $system_user;

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
    public function get_system_user()
    {
        if (empty($this->system_user) && ! empty($this->system_user_id))
        {
            TTransaction::open('permission');
            $this->system_user = SystemUser::find($this->system_user_id);
            TTransaction::close();
        }

        return $this->system_user;
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
