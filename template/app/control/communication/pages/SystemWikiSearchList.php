<?php
/**
 * SystemWikiSearchList
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemWikiSearchList extends TStandardList
{
    protected $form;
    protected $datagrid;
    protected $pageNavigation;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('communication');            // defines the database
        parent::setActiveRecord('SystemWikiPage');   // defines the active record
        parent::setDefaultOrder('title', 'asc');         // defines the default order
        parent::addFilterField('title', 'like', 'search', null, TExpression::OR_OPERATOR); // filterField, operator, formField
        parent::addFilterField('content', 'like', 'search', null, TExpression::OR_OPERATOR); // filterField, operator, formField
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('active', '=', 'Y'));
        $criteria->add(new TFilter('searchable', '=', 'Y'));
        
        $filterVar = TSession::getValue("usergroupids");
        $filterVar = is_array($filterVar) ? "'".implode("','", $filterVar)."'" : $filterVar;
        
        $criteria->add(new TFilter('id', 'in', "(SELECT system_wiki_page_id FROM system_wiki_share_group WHERE system_group_id in ({$filterVar}))"));
        parent::setCriteria($criteria);
        
        $this->form = new BootstrapFormBuilder('wiki_search_list');
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->form->setFormTitle("Wiki");

        $search = new TEntry('search');
        $search->setSize('100%');
        $search->placeholder = _t('Description') . '...';
        
        $row1 = $this->form->addFields([$search]);
        $row1->layout = ['col-sm-12'];

        $btn_onsearch = $this->form->addAction(_t("Search"), new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 
        
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->disableHtmlConversion();
        $this->datagrid->class = 'table';
        $this->datagrid->id = 'system-wiki-search-table';
        $this->datagrid->style = 'width: 100%';
        
        $column_title = new TDataGridColumn('title','', 'left');
        
        $column_title->setTransformer( function($value, $object, $row) {
            $content = new TElement('div');
            $content->add(TElement::tag('a', $object->title, ['href' => 'index.php?class=SystemWikiView&method=onLoad&key=' . $object->id, 'generator' => 'adianti']));
            $content->add(TElement::tag('span', substr(strip_tags($object->content),0,200),['class' => 'description']));
            $content->{'class'} = 'system-wiki-result';
            
            return $content;
        });
        
        $this->datagrid->addColumn($column_title);
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
        //$container->add(new TXMLBreadCrumb('menu.xml', 'SystemWikiSearchList' ));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);
    }
    
    /**
     *
     */
    public function Delete($param)
    {
        new TMessage('error', _t('Permission denied'));
    }
}
