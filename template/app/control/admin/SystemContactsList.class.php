<?php
/**
 * SystemContactsList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemContactsList extends TPage
{
    private $form, $cards, $datagrid, $pageNavigation;
    
    use Adianti\Base\AdiantiStandardCollectionTrait;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('permission');
        $this->setActiveRecord('SystemUser');
        $this->addFilterField('name', 'like', 'name');
        $this->addFilterField('email', 'like', 'email');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Product');
        $this->form->setFormTitle(_t('Contacts'));
        
        $name = new TEntry('name');
        $email = new TEntry('email');
        
        $name->placeholder = _t('Name');
        $email->placeholder = _t('Email');
        
        $row = $this->form->addFields( [$name], [$email] );
        $row->layout = ['col-sm-6', 'col-sm-6'];
        
        $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search blue');
        $this->form->addActionLink(_t('Clear'), new TAction([$this, 'clear']), 'fa:eraser red');

        // keep the form filled with the search data
        $name->setValue( TSession::getValue( 'SystemUser_filter_name' ) );
        
        // creates the Card View
        $this->cards = new TCardView;
		$this->cards->setContentHeight(170);
		//$this->cards->setTitleAttribute('{description} (#{id})');
		$this->setCollectionObject($this->cards);
		
        $this->cards->setContentHeight(170);
        $this->cards->setProperty('class', 'card-wrapper system_contacts');
        $this->cards->setItemClass('col-sm-12 col-md-6 col-xl-4 system-contact');
        $this->cards->setItemTemplate(file_get_contents('app/resources/system_contact.html'));
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        // creates the page structure using a table
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form); // add a row to the form
        $vbox->add(TPanelGroup::pack('', $this->cards, $this->pageNavigation)); // add a row for page navigation
        
        // add the table inside the page
        parent::add($vbox);
    }
    
    public function onDelete($param)
    {
    }
    
    public function Delete($param)
    {
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
