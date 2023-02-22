<?php
/**
 * SystemPostList
 *
 * @version    1.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemPostList extends TStandardList
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;

    // trait com onReload, onSearch, onDelete...
    use Adianti\Base\AdiantiStandardListTrait;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();
        
        parent::setDatabase('communication');            // defines the database
        parent::setActiveRecord('SystemPost');   // defines the active record
        parent::setDefaultOrder('id', 'desc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('title', 'like', 'title'); // filterField, operator, formField
        parent::addFilterField('content', 'like', 'content'); // filterField, operator, formField
        parent::addFilterField('created_by', '=', 'created_by'); // filterField, operator, formField
        parent::addFilterField('active', '=', 'active'); // filterField, operator, formField

        $this->form = new BootstrapFormBuilder('post_list');
        $this->form->setFormTitle(_t("Posts"));
        
        $id = new TEntry('id');
        $title = new TEntry('title');
        $content = new TEntry('content');
        $system_user_id = new TDBCombo('system_user_id', 'permission', 'SystemUser', 'id', '{name}','name asc'  );
        $active = new TCombo('active');
        
        $id->setSize('100%');
        $title->setSize('100%');
        $content->setSize('100%');
        $active->setSize('100%');
        $system_user_id->setSize('100%');
        $active->addItems(['Y'=>_t('Yes'),'N'=>_t('No')]);
        
        $row1 = $this->form->addFields([new TLabel("ID:", null, '14px', null, '100%'), $id],
                                       [new TLabel(_t("Title") . ":", null, '14px', null, '100%'), $title],
                                       [new TLabel(_t("Content") . ":", null, '14px', null, '100%'), $content]);
        $row1->layout = ['col-sm-2', 'col-sm-4', 'col-sm-5'];
        
        $row2 = $this->form->addFields([new TLabel(_t("Created by"). ":", null, '14px', null, '100%'),$system_user_id],
                                       [new TLabel(_t("Active") . ":", null, '14px', null, '100%'),$active]);
        $row2->layout = [' col-sm-6',' col-sm-3',' col-sm-3'];
        
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        $btn_onsearch = $this->form->addAction(_t("Search"), new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary');
        $this->form->addActionLink(_t('Clear'), new TAction([$this, 'clear']), 'fa:eraser red');
        $btn_onshow = $this->form->addAction(_t("New"), new TAction(['SystemPostForm', 'onEdit']), 'fas:plus #69aa46');
        
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        
        $column_id = new TDataGridColumn('id', "ID", 'center' , '70');
        $column_title = new TDataGridColumn('title', _t("Title"), 'left' , '40%');
        $column_system_user_name = new TDataGridColumn('system_user->name', _t("Created by"), 'center' , '20%');
        $column_created_at_transformed = new TDataGridColumn('created_at', _t("Created at"), 'center' , '15%');
        $column_active_transformed = new TDataGridColumn('active', _t("Active"), 'left' , '10%');
        
        $column_created_at_transformed->setTransformer(function($value, $object, $row)
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_active_transformed->setTransformer( function($value) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:10pt;";
            $div->add($label);
            return $div;
        });
        
        $column_id->setAction(new TAction(array($this, 'onReload'), ['order' => 'id'] ));
        $column_title->setAction(new TAction(array($this, 'onReload'), ['order' => 'title'] ));
        
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_title);
        $this->datagrid->addColumn($column_system_user_name);
        $this->datagrid->addColumn($column_created_at_transformed);
        $this->datagrid->addColumn($column_active_transformed);
        
        $action_onEdit = new TDataGridAction(['SystemPostForm', 'onEdit'], ['register_state' => 'false'] );
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel(_t('Edit'));
        $action_onEdit->setImage('far:edit blue');
        $action_onEdit->setField('id');
        
        $action_onDelete = new TDataGridAction(['SystemPostList', 'onDelete'], ['register_state' => 'false']);
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel(_t('Delete'));
        $action_onDelete->setImage('fas:trash-alt red');
        $action_onDelete->setField('id');
        
        $this->datagrid->addAction($action_onEdit);
        $this->datagrid->addAction($action_onDelete);
        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);
    }
    
    /**
     * on delete post
     */
    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                $key = $param['key'];
                TTransaction::open(self::$database);
                $object = new SystemPost($key, FALSE); 
                $object->clearParts();
                $object->delete();
                TTransaction::close();
                $this->onReload( $param );

                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e)
            {
                new TMessage('error', $e->getMessage());
                TTransaction::rollback();
            }
        }
        else
        {
            $action = new TAction([$this, 'onDelete'], ['register_state' => 'false']);
            $action->setParameters($param);
            $action->setParameter('delete', 1);

            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }

    /**
    * Clear filters
    */
    public function clear()
    {
        $this->clearFilters();
        $this->onReload();
    }
}