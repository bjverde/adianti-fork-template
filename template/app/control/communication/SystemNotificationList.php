<?php
/**
 * SystemNotificationList
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemNotificationList extends TPage
{
    private $cards, $pageNavigation;
    
    use Adianti\Base\AdiantiStandardCollectionTrait;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        $this->setDatabase('communication');
        $this->setActiveRecord('SystemNotification');
        $this->setDefaultOrder('id', 'desc');         // defines the default order
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('system_user_to_id', '=', TSession::getValue('userid') ) );
        $this->setCriteria($criteria); // define a standard filter
        
        // creates the Card View
        $this->cards = new TCardView;
        $this->cards->setItemWidth('100%');
        $this->cards->setUseButton();
        $this->cards->setTitleAttribute('<span style="{check_style}">{subject}</span> <div style="position: absolute;right: 10px;color:var(--bs-secondary)"><i class="fa-regular fa-calendar-days"></i> {dt_message}</div>');
        $this->cards->setContentHeight('75');
        $this->setCollectionObject($this->cards);
        
        $this->cards->setItemTemplate('<span style="padding:10px;{check_style}">{message}</span>');
        
        $exec_action   = new TAction(['SystemNotificationFormView', 'onExecuteAction'], ['id'=> '{id}']);
        $this->cards->addAction($exec_action, '{action_label}', '{icon_ok}', [$this, 'displayAction']);
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup(_t('Notifications'));
        $panel->{'class'} = 'card panel noborder';
        $panel->add($this->cards);
        $panel->add($this->pageNavigation);
        
        $panel->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        // add the table inside the page
        parent::add($panel);
    }
    
    /**
     * Display condition
     */
    public function displayAction($object)
    {
        return ($object->checked !== 'Y');
    }
    
    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
