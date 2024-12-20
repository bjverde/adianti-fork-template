<?php
/**
 * SystemScheduleForm
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemScheduleForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_schedule');
        $this->form->setFormTitle( _t('Schedule') );
        $this->form->enableClientValidation();
        
        // create the form fields
        $id   = new THidden('id');
        $title = new TEntry('title');
        $schedule_type = new TRadioGroup('schedule_type');
        $class_method = new TCombo('class_method');
        $monthday = new TCombo('monthday');
        $weekday = new TCombo('weekday');
        $hour = new TCombo('hour');
        $minute = new TCombo('minute');
        $active = new TRadioGroup('active');
        
        $id->setSize('30%');
        $title->setSize('100%');
        
        $active->addItems( ['Y' => _t('Yes'), 'N' => _t('No') ] );
        $active->setUseButton();
        $active->setLayout('horizontal');
        
        $days = [];
        for ($n = 1; $n <= 31; $n++)
        {
            $day_pad = str_pad($n, 2, '0', STR_PAD_LEFT);
            $days[$day_pad] = $day_pad;
        }
        
        $hours = [];
        for ($n = 0; $n <= 23; $n++)
        {
            $hour_pad = str_pad($n, 2, '0', STR_PAD_LEFT);
            $hours[$hour_pad] = $hour_pad;
        }
        
        $minutes = [];
        for ($n = 0; $n <= 55; $n += 5)
        {
            $min_pad = str_pad($n, 2, '0', STR_PAD_LEFT);
            $minutes[$min_pad] = $min_pad;
        }
        
        $monthday->addItems($days);
        $monthday->enableSearch();
        $monthday->setSize('100%');
        
        $hour->addItems($hours);
        $hour->enableSearch();
        $hour->setSize('100%');
        
        $minute->addItems($minutes);
        $minute->enableSearch();
        $minute->setSize('100%');
        
        $schedule_type->addItems(['M' => _t('Once a month'), 'W' => _t('Once a week'), 'D' => _t('Once a day'), 'F' => _t('Each five minutes')]);
        $schedule_type->setLayout('horizontal');
        $schedule_type->setUseButton();
        $schedule_type->setSize('100%');
        
        $class_method->addItems(SystemProgramService::getCronServiceClassEntries('app/service/jobs'));
        $class_method->enableSearch();
        $class_method->setSize('100%');
        
        $week_day_items = [];
        $week_day_items['1'] = _t('Sunday');
        $week_day_items['2'] = _t('Monday');
        $week_day_items['3'] = _t('Tuesday');
        $week_day_items['4'] = _t('Wednesday');
        $week_day_items['5'] = _t('Thursday');
        $week_day_items['6'] = _t('Friday');
        $week_day_items['7'] = _t('Saturday');
        $weekday->addItems($week_day_items);
        $weekday->enableSearch();
        $weekday->setSize('100%');
        
        $active->setValue('Y');
        
        $schedule_type->setValue('D');
        $schedule_type->setChangeAction(new TAction([$this, 'onChangeType']));
        
        // validations
        $title->addValidation(_t('Title'), new TRequiredValidator);
        $class_method->addValidation(_t('Method'), new TRequiredValidator);
        $schedule_type->addValidation(_t('Schedule type'), new TRequiredValidator);
        
        // outras propriedades
        $id->setEditable(false);
        
        $this->form->addFields( [$id]);
        $this->form->addFields( [new TLabel(_t('Title'))], [$title]);
        $this->form->addFields( [new TLabel(_t('Method'))], [$class_method]);
        $this->form->addFields( [new TLabel(_t('Type'))], [$schedule_type]);
        $this->form->addFields( [new TLabel(_t('Month day'))], [$monthday]);
        $this->form->addFields( [new TLabel(_t('Week day'))], [$weekday]);
        $this->form->addFields( [new TLabel(_t('Hour'))], [$hour]);
        $this->form->addFields( [new TLabel(_t('Minute'))], [$minute]);
        $this->form->addFields( [new TLabel(_t('Active'))], [$active]);
        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        
        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        
        $container = new TVBox;
        $container->style = 'width:100%';
        $container->add($this->form);
        
        // add the form to the page
        parent::add($container);
    }
    
    public static function onChangeType($param)
    {
        switch ($param['schedule_type'])
        {
            case 'D':
                TCombo::disableField('form_schedule', 'monthday');
                TCombo::disableField('form_schedule', 'weekday');
                TCombo::enableField('form_schedule', 'hour');
                TCombo::enableField('form_schedule', 'minute');
                break;
            case 'W':
                TCombo::disableField('form_schedule', 'monthday');
                TCombo::enableField('form_schedule', 'weekday');
                TCombo::enableField('form_schedule', 'hour');
                TCombo::enableField('form_schedule', 'minute');
                break;
            case 'M':
                TCombo::disableField('form_schedule', 'weekday');
                TCombo::enableField('form_schedule', 'monthday');
                TCombo::enableField('form_schedule', 'hour');
                TCombo::enableField('form_schedule', 'minute');
                break;
            case 'F':
                TCombo::disableField('form_schedule', 'monthday');
                TCombo::disableField('form_schedule', 'weekday');
                TCombo::disableField('form_schedule', 'hour');
                TCombo::disableField('form_schedule', 'minute');
                break;
        }
    }
    
    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    public static function onSave($param)
    {
        try
        {
            // open a transaction with database 'communication'
            TTransaction::open('communication');
            
            $class_method = explode('::', $param['class_method']);
            
            // get the form data
            $object = new SystemSchedule;
            $object->fromArray( $param );
            $object->class_name = $class_method[0] ?? '';
            $object->method = $class_method[1] ?? '';
            $object->store();
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_System_schedule', $data);
            
            self::onChangeType(array('schedule_type' => $param['schedule_type']));
            
            TTransaction::close(); // close the transaction
            
            $pos_action = new TAction(['SystemScheduleList', 'onReload']);
            new TMessage('info', _t('Record saved'), $pos_action); // shows the success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    public function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'communication'
                TTransaction::open('communication');
                
                // instantiates object
                $object = new SystemSchedule($key);
                
                self::onChangeType(array('schedule_type' => $object->{'schedule_type'}));
                
                $object->class_method = $object->class_name . '::' . $object->method;
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear(true);
                self::onChangeType(array('schedule_type' => 'D'));
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
