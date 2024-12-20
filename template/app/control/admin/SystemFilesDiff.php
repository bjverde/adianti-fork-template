<?php
/**
 * SystemFilesDiff
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemFilesDiff extends TPage
{
    private static $formName = 'form_SystemFilesDiff';
    private $datagrid; // listing
    private $datagrid_form;  // form listing
    private $loaded;
    private $alert;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        $file = new TEntry('file');
        $file->setSize('100%');
        $file->exitOnEnter();
        $file->setExitAction(new TAction([$this, 'onSearch'], ['static'=>'1']));

        $status = new TCombo('status');
        $status->setSize(150);
        $status->addItems([
            AdiantiFileHashGeneratorService::REMOVED =>  _t('Removed'),
            AdiantiFileHashGeneratorService::MODIFIED => _t('Modified'),
            /*AdiantiFileHashGeneratorService::EQUAL =>    _t('Equal'),*/
        ]);
        $status->setChangeAction(new TAction([$this, 'onSearch'], ['static'=>'1']));

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $column_file = new TDataGridColumn('file', _t("File"), 'left');
        $column_status = new TDataGridColumn('status', "Status", 'center','150px');
        
        $this->datagrid->addColumn($column_status);
        $this->datagrid->addColumn($column_file);
        
        $column_status->setTransformer(function($status) {
            $div = new TElement('div');
            $div->{'style'} = 'font-size: 14px';

            if($status == AdiantiFileHashGeneratorService::REMOVED)
            {
                $div->add( _t('Removed'));
                $div->{'class'} = 'bg-danger badge';
            }
            else if($status == AdiantiFileHashGeneratorService::EQUAL)
            {
                $div->add( _t('Equal'));
                $div->{'class'} = 'bg-primary badge';
            }
            else
            {
                $div->add( _t('Modified'));
                $div->{'class'} = 'bg-warning badge';
            }

            return $div;
        });

        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr);

        $tr->add(TElement::tag('td', $status));
        $tr->add(TElement::tag('td', $file));

        $this->datagrid_form->addField($status);
        $this->datagrid_form->addField($file);
        $this->datagrid_form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $this->datagrid_form->add($this->datagrid);
        
        $this->alert = new TElement('div');
        
        $panel = new TPanelGroup(_t('Framework information') . ' <span class="badge rounded-pill text-bg-info text-light"> v'.file_get_contents('lib/VERSION').'</span>');
        $panel->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        $panel->add($this->alert);
        $panel->add($this->datagrid_form);
        
        parent::add($panel);
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        $data = $this->datagrid_form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if(! empty($data->file))
        {
            $filters['file'] = $data->file;
        }

        if(! empty($data->status))
        {
            $filters['status'] = $data->status;
        }

        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        AdiantiCoreApplication::loadPage('SystemFilesDiff', 'onReload', ['offset' => 0, 'first_page' => 1, 'register_state' => 'false']);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            $balance = $all = AdiantiFileHashGeneratorService::compare(true);
            
            if ($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $key => $filter) 
                {
                    if($key == 'file')
                    {
                        $balance = array_filter(
                            $balance,
                            function($key) use ($filter) {
                                return strpos( strtolower($key), strtolower($filter) ) !== FALSE;
                            },
                            ARRAY_FILTER_USE_KEY
                        );
                    }
                    
                    if($key == 'status')
                    {
                        $balance = array_filter(
                            $balance,
                            function($key) use ($filter) {
                                return $key == $filter;
                            }
                        );
                    }
                }
            }

            $this->datagrid->clear();

            if ($balance)
            {
                foreach ($balance as $file => $status)
                {
                    $object = new stdClass;
                    $object->status = $status;
                    $object->file   = $file;

                    $this->datagrid->addItem($object);
                }
            }
            
            if ($all)
            {
                $this->alert->clearChildren();
                $this->alert->add(new TAlert('warning', _t('The following files have been modified from the original framework')));
            }
            else
            {
                $this->alert->clearChildren();
                $this->alert->add(new TAlert('success', '<i class="fa-solid fa-certificate"></i> ' . _t('All framework files are in their original state')));
            }
            
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        if (!$this->loaded)
        {
            $this->onReload();
        }

        parent::show();
    }
    
    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}