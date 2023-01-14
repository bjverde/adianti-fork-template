<?php
/**
 * SystemPost
 *
 * @version    1.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemPost extends TRecord
{
    const TABLENAME = 'system_post';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    const CREATEDAT = 'created_at';
    
    private $system_user;

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
        $userid = $userid??TSession::getValue('userid');

        return SystemPostLike::where('system_post_id', '=', $this->id)->where('system_user_id', '=', $userid)->count() > 0;
    }

    /**
     * Return last 3 html comments 
     */
    public function get_last_comments_formated()
    {
        $divs = new TElement('div');
        $divs->{'class'} = 'post-comments';
        
        $lastComments = SystemPostComment::where('system_post_id', '=', $this->id)->orderBy('created_at', 'desc')->take(3)->load();
        
        if ($lastComments)
        {
            foreach($lastComments as $comment)
            {
                $divs->add($comment->html_content);
            }
        }

        return $divs->getContents();
    }
    
    /**
     * List tags
     */
    public function getTags()
    {
        return SystemPostTag::where('system_post_id', '=', $this->id)->getIndexedArray('tag', 'tag');
    }
    
    public function get_tags_formatted()
    {
        return implode(' ', array_map(function($tag) {
            return TElement::tag('span', $tag, ['class' => 'badge bg-green']);
        }, $this->getTags()));
    }
}
