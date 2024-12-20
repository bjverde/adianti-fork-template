<?php
/**
 * SystemPostComment
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPostComment extends TRecord
{
    const TABLENAME = 'system_post_comment';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    const CREATEDAT = 'created_at';

    private $system_user;
    private $system_post;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('comment');
        parent::addAttribute('system_post_id');
        parent::addAttribute('system_user_id');
        parent::addAttribute('created_at');
    }

    /**
     * Return user
     */
    public function get_system_user()
    {
        if (empty($this->system_user) && ! empty($this->system_user_id))
        {
            $this->system_user = SystemUser::findInTransaction('permission', $this->system_user_id);
        }

        return $this->system_user;
    }

    /**
     * Return post
     */
    public function get_system_post()
    {
        if (empty($this->system_post) && ! empty($this->system_post_id))
        {
            $this->system_post = SystemPost::find($this->system_post_id);
        }

        return $this->system_post;
    }

    /**
     * Get date create formated
     */
    public function get_date()
    {
        return date('d/m/Y H:i', strtotime($this->created_at));
    }
    
    /**
     * Return html content formatted
     */
    public function get_html_content()
    {
        $img = new TElement('img');
        $img->src = "app/images/photos/{$this->get_system_user()->login}.jpg";
        $img->onerror = "this.onerror=null;this.src='app/images/stub.png';";
        $img->alt = "User";
        
        $div = new TElement('div');
        $div->{'class'} = 'post-comment';
        $div->add($img);
        $div->add(TElement::tag('div', $this->date, ['class' => 'post-comment-date']));
        $div->add(TElement::tag('b', $this->get_system_user()->name));
        $div->add($this->comment);
        
        return $div;
    }
}
