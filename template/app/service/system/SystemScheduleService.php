<?php
/**
 * SystemScheduleService
 *
 * @version    8.0
 * @package    service
 * @subpackage cli
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemScheduleService
{
    public static function run($request)
    {
        TTransaction::open('communication');
        
        $monthday  = date('d');
        $weekday   = (string) (date('N') +1);
        $hour      = date('H');
        $minute    = date('i');
        
        $s1 = SystemSchedule::where('schedule_type', '=', 'M')
                            ->where('monthday',      '=', $monthday)
                            ->where('hour',          '=', $hour)
                            ->where('minute',        '=', $minute)
                            ->where('active',        '=', 'Y')->load();
        
        $s2 = SystemSchedule::where('schedule_type', '=', 'W')
                            ->where('weekday',       '=', $weekday)
                            ->where('hour',          '=', $hour)
                            ->where('minute',        '=', $minute)
                            ->where('active',        '=', 'Y')->load();
        
        $s3 = SystemSchedule::where('schedule_type', '=', 'D')
                            ->where('hour',          '=', $hour)
                            ->where('minute',        '=', $minute)
                            ->where('active',        '=', 'Y')->load();
        
        $s4 = SystemSchedule::where('schedule_type', '=', 'F')
                            ->where('active',        '=', 'Y')->load();
        
        $schedules = array_merge($s1, $s2, $s3, $s4);
        TTransaction::close();
        
        foreach ($schedules as $schedule)
        {
            $log = new SystemScheduleLog;
            $log->logdate = date('Y-m-d H:i:s');
            $log->title = $schedule->title;
            $log->class_name = $schedule->class_name;
            $log->method = $schedule->method;
            
            try
            {
                AdiantiCoreApplication::execute($schedule->class_name, $schedule->method, $request, 'cli');
                
                TTransaction::open('log');
                $log->status = 'Y';
                $log->store();
                TTransaction::close();
            }
            catch (Exception $e)
            {
                TTransaction::open('log');
                $log->status = 'N';
                $log->message = $e->getMessage();
                $log->store();
                TTransaction::close();
            }
            catch (Error $e)
            {
                TTransaction::open('log');
                $log->status = 'N';
                $log->message = $e->getMessage();
                $log->store();
                TTransaction::close();
            }
        }
    }
}
