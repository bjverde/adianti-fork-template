<?php
/**
 * SystemPostCommentList
 *
 * @version    1.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemPostCommentList extends TPage
{
    private $container;
    private $loaded;
    private static $database = 'communication';
    private static $activeRecord = 'SystemPostComment';
    private $limit = 0;

    public function __construct($param = null)
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        
        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'position: absolute; top: 10px; right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel(_t("Close"));
        $btnClose->setImage('fas:times');
        
        $this->container = new TElement('div');
        $this->container->{'class'} = 'post-comments';
        
        $panel = new TPanelGroup(_t('Comments'). $btnClose);
        $panel->add($this->container);
        $panel->getBody()->class .= ' table-responsive system_post';
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($panel);

        parent::add($container);
    }
    
    /**
     * on reload comment
     */
    public function onReload($param = NULL)
    {
        try
        {
            TTransaction::open(self::$database);
            $repository = new TRepository(self::$activeRecord);
            $criteria = new TCriteria;

            if(!empty($param['system_post_id']))
            {
                TSession::setValue(__CLASS__.'load_filter_system_post_id', $param['system_post_id']);
            }
            $filterVar = TSession::getValue(__CLASS__.'load_filter_system_post_id');
            $criteria->add(new TFilter('system_post_id', '=', $filterVar));

            if (empty($param['order']))
            {
                $param['order'] = 'created_at';    
            }
            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param);
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            $objects = $repository->load($criteria, FALSE);
            
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $this->container->add($object->html_content);
                }
            }
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
