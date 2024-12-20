<?php
/**
 * SystemMessageList
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemMessageList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $folders;
    protected $tags;
    
    use Adianti\Base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct($param)
    {
        parent::__construct();
        
        $this->setDatabase('communication');            // defines the database
        $this->setActiveRecord('SystemMessage');   // defines the active record
        $this->setDefaultOrder('id', 'desc');         // defines the default order
        $this->addFilterField('checked', 'like', 'checked'); // filterField, operator, formField
        $this->addFilterField('subject', 'like', 'subject'); // filterField, operator, formField
        $this->addFilterField('message', 'like', 'message'); // filterField, operator, formField
        
        // apply session filters.
        $this->filterSessionUser();
        
        // creates the form
        $this->form = new TForm('form_search_SystemMessage');
        
        // create the form fields
        $subject = new TEntry('subject');
        $message = new TEntry('message');
        $button  = TButton::create( 'search', array($this, 'onSearch'), _t('Find'), 'fa:search');
        
        $subject->placeholder = _t('Subject');
        $message->placeholder = _t('Message');
        
        $table = new TTable;
        $table->style = 'width: 100%';
        $table->addRowSet( $subject, $message, $button );

        $subject->setSize('100%');
        $message->setSize('100%');
        
        $this->form->add($table);
        
        // define logic form fields
        $this->form->setFields( [$subject, $message, $button] );
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemMessage_filter_data') );
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->class = 'table table-hover vertical-middle';
        $this->datagrid->style = 'width: 100%';

        // creates the datagrid columns
        $column_from    = new TDataGridColumn('user_mixed?->name', _t('User'), 'left', '140');
        $column_message = new TDataGridColumn('message', _t('Message'), 'left');
        $column_date    = new TDataGridColumn('dt_message', _t('Date'), 'center', '100');
        $column_from->enableAutoHide(500);
        $column_date->enableAutoHide(500);
        
        $column_from->setTransformer( function($value, $object, $row) {
            if ($object->viewed == 'N')
            {
                $value = '<b>'. $value . '</b>';
            }
            return $value;
        });
        
        $column_message->setTransformer( function($value, $object, $row) {
            $message = '';
            if ($object->viewed == 'Y')
            {
                $message .= $object->subject;
            }
            else
            {
                $message .= '<b>'.$object->subject.'</b> ';
            }
            
            if (!empty($object->attachments))
            {
                $message .= ' <i class="fas fa-paperclip gray"></i> ';
            }
            $message .= '<br>';
            
            if ($object->viewed == 'Y')
            {
                $message .= substr(strip_tags($value),0,200);
            }
            else
            {
                $message .= '<b>'.substr(strip_tags($value),0,200).'</b>';
            }
            return $message;
        });
        
        $column_date->setTransformer( function($value, $object, $row) {
            return '<i class="far fa-calendar-alt red"></i> '
                    .TDateTime::convertToMask($value,'yyyy-mm-dd hh:ii', 'dd/mm/yyyy')
                    .'<br>'
                    .TDateTime::convertToMask($value,'yyyy-mm-dd hh:ii', 'hh:ii');
        });
        
        $action = new TDataGridAction(array('SystemMessageFormView', 'onView'));
        $action->setField('id');
        $action->setImage('fa:search');
        $this->datagrid->addAction($action);
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_from);
        $this->datagrid->addColumn($column_message);
        $this->datagrid->addColumn($column_date);

        $order = new TAction(array($this, 'onReload'));
        $order->setParameter('order', 'dt_message');
        $column_message->setAction($order);
        
        //parent::setTransformer( array($this, 'onBeforeLoad') );
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $panel = new TPanelGroup('&nbsp;');
        $panel->addHeaderWidget($this->form);
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        $this->folders = new THtmlRenderer('app/resources/system/inbox/message_folders.html');
        $this->folders->enableSection('main', []);
        $this->folders->enableTranslation();
        
        TTransaction::open('communication');
        $tag_list = SystemMessageTag::getTagList();
        TTransaction::close();
        
        $this->tags = new THtmlRenderer('app/resources/system/inbox/message_tags.html');
        $this->tags->enableSection('main', []);
        $this->tags->enableSection('tags', $tag_list, true);
        $this->tags->enableTranslation();
        
        $vbox = TVBox::pack($this->folders, $this->tags);
        $vbox->style = 'width:100%';
        
        $hbox = new THBox;
        $hbox->style = 'width:100%';
        $hbox->add( $vbox, '')->class = 'left-mailbox';
        $hbox->add($panel, '')->class = 'right-mailbox nopadding';
        
        parent::add($hbox);
    }
    
    /**
     * Apply session filters
     */
    public function filterSessionUser()
    {
        $criteria = new TCriteria;
        $subcriteria = new TCriteria;
        $subcriteria->add(new TFilter('system_user_to_id', '=', TSession::getValue('userid') ) );
        $subcriteria->add(new TFilter('system_user_id', '=', TSession::getValue('userid') ), TExpression::OR_OPERATOR );
        $criteria->add($subcriteria);
       
        if (!empty(TSession::getValue('inbox_criteria')))
        {
            $criteria->add(TSession::getValue('inbox_criteria'));
        }
        
        $this->setCriteria( $criteria );
    }
    
    /**
     * show inbox folder
     */
    public function filterTag($param)
    {
        $tag = $param['tag'];
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_user_to_id', '=', TSession::getValue('userid') ) );
        $criteria->add(new TFilter('removed', '<>', 'Y' ) );
        $criteria->add(new TFilter('id', 'in', "(SELECT system_message_id FROM system_message_tag WHERE system_message.id=system_message_tag.system_message_id AND tag=:[{$tag}]:)" ) );
        
        TSession::setValue('inbox_criteria', $criteria);
        TSession::setValue('inbox_criteria_type', 'tag');
        
        $this->filterSessionUser();
        
        $this->folders->enableSection('main', ['class_inbox'    => '',
                                               'class_sent'     => '',
                                               'class_archived' => '']);
        
        $this->onReload($param);
    }
    
    /**
     * show inbox folder
     */
    public function filterInbox($param)
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_user_to_id', '=', TSession::getValue('userid') ) );
        $criteria->add(new TFilter('checked', '<>', 'Y' ) );
        $criteria->add(new TFilter('removed', '<>', 'Y' ) );
        TSession::setValue('inbox_criteria', $criteria);
        TSession::setValue('inbox_criteria_type', 'inbox');
        
        $this->filterSessionUser();
        
        $this->folders->enableSection('main', ['class_inbox'    => 'active',
                                               'class_sent'     => '',
                                               'class_archived' => '']);
        
        $this->onReload($param);
    }
    
    /**
     * show archived folder
     */
    public function filterArchived($param)
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_user_to_id', '=', TSession::getValue('userid') ) );
        $criteria->add(new TFilter('checked', '=', 'Y' ) );
        $criteria->add(new TFilter('removed', '<>', 'Y' ) );
        TSession::setValue('inbox_criteria', $criteria);
        TSession::setValue('inbox_criteria_type', 'archived');
        
        $this->filterSessionUser();
        
        $this->folders->enableSection('main', ['class_inbox'    => '',
                                               'class_sent'     => '',
                                               'class_archived' => 'active']);
        
        $this->onReload($param);
    }
    
    /**
     * show archived folder
     */
    public function filterTrash($param)
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_user_to_id', '=', TSession::getValue('userid') ) );
        $criteria->add(new TFilter('removed', '=', 'Y' ) );
        TSession::setValue('inbox_criteria', $criteria);
        TSession::setValue('inbox_criteria_type', 'removed');
        
        $this->filterSessionUser();
        
        $this->folders->enableSection('main', ['class_inbox'    => '',
                                               'class_sent'     => '',
                                               'class_archived' => '',
                                               'class_trash' => 'active']);
        
        $this->onReload($param);
    }
    
    /**
     * show sent folder
     */
    public function filterSent($param)
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_user_id', '=', TSession::getValue('userid') ) );
        $criteria->add(new TFilter('removed', '<>', 'Y' ) );
        TSession::setValue('inbox_criteria', $criteria);
        TSession::setValue('inbox_criteria_type', 'sent');
        
        $this->filterSessionUser();
        
        $this->folders->enableSection('main', ['class_inbox'    => '',
                                               'class_sent'     => 'active',
                                               'class_archived' => '']);
        
        $this->onReload($param);
    }
    
    /**
     *
     */
    public function Delete($param)
    {
        new TMessage('error', _t('Permission denied'));
    }
}
