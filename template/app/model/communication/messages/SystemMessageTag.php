<?php
/**
 * SystemMessageTag
 *
 * @version    8.0
 * @package    model
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemMessageTag extends TRecord
{
    const TABLENAME = 'system_message_tag';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_message_id');
        parent::addAttribute('tag');
    }
    
    /**
     *
     */
    public static function getTagList()
    {
        $tag_list = [];
        
        $user_id = TSession::getValue('userid');
        $tags = SystemMessageTag::where('system_message_id', 'in', "(select id from system_message where system_user_to_id = :[$user_id]:)")->getIndexedArray('tag', 'tag');
        if ($tags)
        {
            foreach ($tags as $tag)
            {
                $tag_list[] = ['tag' => $tag];
            }
        }
        
        return $tag_list;
    }
}
