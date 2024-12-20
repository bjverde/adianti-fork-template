<?php
/**
 * SystemPostLike
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemPostLike extends TRecord
{
    const TABLENAME = 'system_post_like';
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
            TTransaction::open('permission');
            $this->system_user = SystemUser::find($this->system_user_id);
            TTransaction::close();
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
}
