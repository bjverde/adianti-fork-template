<?php
/**
 * SystemNotificationDropdown
 *
 * @version    8.0
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemNotificationDropdown extends TElement
{
    public function show()
    {
        try
        {
            // load configs
            $ini = AdiantiApplicationConfig::get();
            
            TTransaction::open('communication');
            // load the notifications to the logged user
            $system_notifications = SystemNotification::where('checked', '=', 'N')
                                                      ->where('dt_message', '<=', date('Y-m-d 23:59:59'))
                                                      ->where('system_user_to_id', '=', TSession::getValue('userid'))
                                                      ->orderBy('id', 'desc')
                                                      ->take(5)
                                                      ->load();
            
            $li = new TElement('li');
            $li->add(TElement::tag('a', _t('Notifications'), ['href'=> '#', 'class' => 'dropdown-header border-bottom'] ));
            $li->show();
            
            foreach ($system_notifications as $system_notification)
            {
                $date    = $this->getShortPastTime($system_notification->dt_message);
                $subject = $system_notification->subject;
                $icon    = $system_notification->icon ? $system_notification->icon : 'far fa-bell text-aqua';
                $icon    = str_replace( 'fa:', 'fa fa-', $icon);
                $icon    = str_replace( 'far:', 'far fa-', $icon);
                $icon    = str_replace( 'fas:', 'fas fa-', $icon);
                
                $li  = new TElement('li');
                $a   = new TElement('a');
                $div = new TElement('div');
                
                /*
                $i = new TElement('i');
                $i->{'class'} = $icon;
                $a->add($i);
                */
                
                $li->add($a);
                $a->add($div);
                
                $parameters = ['id' => $system_notification->id];
                $a->class = 'dropdown-item gap-3 py-1 border-bottom';
                $a->href = (new TAction(['SystemNotificationFormView', 'onView'], $parameters))->serialize();
                $a->generator = 'adianti';
                
                $div->{'class'} = 'gap-2 w-100 justify-content-between';
                
                $div2 = new TElement('div');
                //$div2->add( TElement::tag('h6', $name, ['class'=>'mb-0']));
                $div2->add( TElement::tag('p', substr($subject,0,30), ['class'=>'mb-0 opacity-75', 'style' => 'text-wrap: balance']));
                $div->add($div2);
                $div->add( TElement::tag('small', $date, ['class' => 'opacity-50 text-nowrap']));
                
                $li->show();
            }
            
            if ($system_notifications)
            {
                TScript::create('$("#alert_notifications").show()');
            }
            else
            {
                TScript::create('$("#alert_notifications").hide()');
            }
            
            $li = new TElement('li');
            $li->add(TElement::tag('a', _t('View all'), ['href'=> (new TAction(['SystemNotificationList', 'onReload'], ['register_state' => 'false']))->serialize(), 'generator'=>'adianti', 'class' => 'dropdown-item'] ));
            $li->show();
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function getShortPastTime($from)
    {
        $to = date('Y-m-d H:i:s');
        $start_date = new DateTime($from);
        $since_start = $start_date->diff(new DateTime($to));
        if ($since_start->y > 0)
            return $since_start->y.' years ';
        if ($since_start->m > 0)
            return $since_start->m.' months ';
        if ($since_start->d > 0)
            return $since_start->d.' days ';
        if ($since_start->h > 0)
            return $since_start->h.' hours ';
        if ($since_start->i > 0)
            return $since_start->i.' minutes ';
        if ($since_start->s > 0)
            return $since_start->s.' seconds ';    
    }
}
