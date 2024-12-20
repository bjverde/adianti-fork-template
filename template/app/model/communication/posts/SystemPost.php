<?php
/**
 * SystemPost
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPost extends TRecord
{
    const TABLENAME = 'system_post';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    const CREATEDAT = 'created_at';
    const UPDATEDAT = 'updated_at';
    const CREATEDBY = 'system_user_id';
    const UPDATEDBY = 'updated_by';
    
    private $creator;
    private $updater;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('title');
        parent::addAttribute('content');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('updated_by');
        parent::addAttribute('active');
    }
    
    /**
     * Remove parts
     */
    public function clearParts()
    {
        SystemPostTag::where('system_post_id', '=', $this->id)->delete();
        SystemPostShareGroup::where('system_post_id', '=', $this->id)->delete();
        SystemPostComment::where('system_post_id', '=', $this->id)->delete();
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
            TTransaction::open('permission');
            $this->updater = SystemUser::findInTransaction('permission', $this->updated_by);
            TTransaction::close();
        }

        return $this->updater;
    }
    
    /**
     * Get number of likes
     */
    public function get_count_likes()
    {
        return SystemPostLike::where('system_post_id', '=', $this->id)->count();
    }

    /**
     * Get number of comments
     */
    public function get_count_comments()
    {
        return SystemPostComment::where('system_post_id', '=', $this->id)->count();
    }
    
    /**
     * Get date create formated
     */
    public function get_date()
    {
        return date('d/m/Y H:i', strtotime($this->created_at));
    }

    /**
     * Get is liked by userid or loggeduser
     */
    public function get_liked($userid = null)
    {
        $userid = $userid ?? TSession::getValue('userid');

        return SystemPostLike::where('system_post_id', '=', $this->id)->where('system_user_id', '=', $userid)->count() > 0;
    }
    
    /**
     * List tags
     */
    public function getTags()
    {
        return SystemPostTag::where('system_post_id', '=', $this->id)->getIndexedArray('tag', 'tag');
    }
    
    /**
     *
     */
    public function get_tags_formatted()
    {
        return implode(' ', array_map(function($tag) {
            return TElement::tag('span', $tag, ['class' => 'badge bg-green']);
        }, $this->getTags()));
    }
}
