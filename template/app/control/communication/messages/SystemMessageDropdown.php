<?php
/**
 * MessageList
 *
 * @version    8.0
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemMessageDropdown
{
    public function show()
    {
        try
        {
            // load configs
            $ini = AdiantiApplicationConfig::get();
            
            TTransaction::open('communication');
            
            // load the messages to the logged user
            $system_messages = SystemMessage::where('viewed', '<>', 'Y')
                                            ->where('removed', '<>', 'Y')
                                            ->where('checked', '<>', 'Y')
                                            ->where('system_user_to_id', '=', TSession::getValue('userid'))
                                            ->orderBy('id', 'desc')
                                            ->take(5)
                                            ->load();
            
            $li = new TElement('li');
            $li->add(TElement::tag('a', _t('Messages'), ['href'=> '#', 'class' => 'dropdown-header border-bottom'] ));
            $li->show();
            
            TTransaction::open('permission');
            foreach ($system_messages as $system_message)
            {
                $name    = SystemUser::find($system_message->system_user_id)->name;
                $date    = $this->getShortPastTime($system_message->dt_message);
                $subject = htmlspecialchars($system_message->subject, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                $li  = new TElement('li');
                $a   = new TElement('a');
                $div = new TElement('div');
                
                $li->add($a);
                $a->add($div);
                
                $parameters = ['id' => $system_message->id];
                $a->class = 'dropdown-item gap-3 py-1 border-bottom';
                $a->href = (new TAction(['SystemMessageFormView', 'onView'], $parameters))->serialize();
                $a->generator = 'adianti';
                
                $div->{'class'} = 'gap-2 w-100 justify-content-between';
                
                $div2 = new TElement('div');
                $div2->add( TElement::tag('h6', $name, ['class'=>'mb-0']));
                $div2->add( TElement::tag('p', substr($subject,0,30), ['class'=>'mb-0 opacity-75', 'style' => 'text-wrap: balance']));
                $div->add($div2);
                $div->add( TElement::tag('small', $date, ['class' => 'opacity-50 text-nowrap']));
                
                $li->show();
            }
            
            if ($system_messages)
            {
                TScript::create('$("#alert_messages").show()');
            }
            else
            {
                TScript::create('$("#alert_messages").hide()');
            }
            
            TTransaction::close(); // close permission
            TTransaction::close(); // close communication
            
            $li = new TElement('li');
            $li->add(TElement::tag('a', _t('Messages'), ['href'=> (new TAction(['SystemMessageList', 'filterInbox']))->serialize(), 'generator'=>'adianti', 'class' => 'dropdown-item'] ));
            $li->show();
            
            $li = new TElement('li');
            $li->add(TElement::tag('a', _t('Send message'), ['href'=> (new TAction(['SystemMessageForm', 'onClear'], ['register_state' => 'false']))->serialize(), 'generator'=>'adianti', 'class' => 'dropdown-item'] ));
            $li->show();
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
