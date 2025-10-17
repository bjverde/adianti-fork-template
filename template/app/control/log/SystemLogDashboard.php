<?php
/**
 * SystemLogDashboard
 *
 * @version    8.3
 * @package    control
 * @subpackage log
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemLogDashboard extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        try
        {
            $html = new THtmlRenderer('app/resources/system/log/dashboard.html');
            
            TTransaction::open('log');
            $indicator1 = new TNumericIndicator;
            $indicator1->setTitle(_t('Accesses today'));
            $indicator1->setValue(SystemAccessLog::where('login_year','=',date('Y'))
                                       ->where('login_month','=',date('m'))
                                       ->where('login_day','=',date('d'))
                                       ->count());
            $indicator1->setIcon('sign-in-alt');
            $indicator1->setColor('#00a65a');
            $indicator1->setNumericMask(0, ',', '.');
            
            $indicator2 = new TNumericIndicator;
            $indicator2->setTitle(_t('Requests today'));
            $indicator2->setValue(SystemRequestLog::where('log_year','=',date('Y'))
                                       ->where('log_month','=',date('m'))
                                       ->where('log_day','=',date('d'))
                                       ->count());
            $indicator2->setIcon('globe');
            $indicator2->setColor('#605ca8');
            $indicator2->setNumericMask(0, ',', '.');

            $indicator3 = new TNumericIndicator;
            $indicator3->setTitle(_t('Request time average') . ' (ms)');
            $indicator3->setValue(round( (float) SystemRequestLog::where('log_year','=',date('Y'))
                                      ->where('log_month','=',date('m'))
                                      ->where('log_day','=',date('d'))
                                      ->avgBy('request_duration'), 2) );
            $indicator3->setIcon('hourglass-end');
            $indicator3->setColor('#ff851b');
            $indicator3->setNumericMask(2, ',', '.');

            $indicator4 = new TNumericIndicator;
            $indicator4->setTitle(_t('SQL DML Statements'));
            $indicator4->setValue(SystemSqlLog::where('log_year','=',date('Y'))
                                   ->where('log_month','=',date('m'))
                                   ->where('log_day','=',date('d'))
                                   ->count());
            $indicator4->setIcon('database');
            $indicator4->setColor('#0073b7');
            $indicator4->setNumericMask(0, ',', '.');
            
            $month_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            $xlabels_std = [];
            $data_std = [];
            
            // Inicializa todos os dias do mês com zero
            for ($day = 1; $day <= $month_days; $day++) {
                $day_ok = str_pad($day, 2, '0', STR_PAD_LEFT); // Ex: '01', '02', ...
                $xlabels_std[$day_ok] = $day_ok;
                $data_std[$day_ok] = 0;
            }
            
            /*********** Acess by day *************/
            
            $chart1 = new TBarChart;
            $chart1->setTitle(_t('Accesses by day'));
            $chart1->setHeight(300);
            $chart1->setXLabels([_t('Count')]);
            
            $stats1 = SystemAccessLog::groupBy('login_day')
                                     ->where('login_year', '=', date('Y'))
                                     ->where('login_month', '=', date('m'))
                                     ->orderBy('login_day')
                                     ->countBy('id', 'count');
            $xlabels = $xlabels_std;
            $data = $data_std;
            
            if ($stats1)
            {
                foreach ($stats1 as $row)
                {
                    $day = str_pad($row->login_day, 2, '0', STR_PAD_LEFT);
                    $data[$day] = (int) $row->count;
                }
            }
            $xlabels = array_values($xlabels);
            $data = array_values($data);
            
            $chart1->setXLabels($xlabels);
            $chart1->addDataset(_t('Count'), $data);
            
            
            
            /*********** Requests by day *************/
            
            $chart2 = new TBarChart;
            $chart2->setTitle(_t('Requests by day'));
            $chart2->setHeight(300);
            $chart2->setXLabels([_t('Count')]);
            
            $stats2 = SystemRequestLog::groupBy('log_day')
                                      ->where('log_year', '=', date('Y'))
                                      ->where('log_month', '=', date('m'))
                                      ->orderBy('log_day')
                                      ->countBy('id', 'count');
            $xlabels = $xlabels_std;
            $data = $data_std;
            
            if ($stats2)
            {
                foreach ($stats2 as $row)
                {
                    $day = str_pad($row->log_day, 2, '0', STR_PAD_LEFT);
                    $data[$day] = (int) $row->count;
                }
            }
            $xlabels = array_values($xlabels);
            $data = array_values($data);
            
            $chart2->setXLabels($xlabels);
            $chart2->addDataset(_t('Count'), $data);
            
            /*********** Request time average *************/

            $chart3 = new TBarChart;
            $chart3->setTitle(_t('Request time average'));
            $chart3->setHeight(300);
            $chart3->setXLabels([_t('Sum')]);
            
            $stats3 = SystemRequestLog::groupBy('log_day')
                                      ->where('log_year', '=', date('Y'))
                                      ->where('log_month', '=', date('m'))
                                      ->orderBy('log_day')
                                      ->avgBy('request_duration', 'avg');
            $xlabels = $xlabels_std;
            $data = $data_std;
            if ($stats3)
            {
                foreach ($stats3 as $row)
                {
                    $day = str_pad($row->log_day, 2, '0', STR_PAD_LEFT);
                    $data[$day] = (int) $row->avg;
                }
            }
            $xlabels = array_values($xlabels);
            $data = array_values($data);
            
            $chart3->setXLabels($xlabels);
            $chart3->addDataset(_t('Sum'), $data);


            /*********** SQL Statements by day *************/

            $chart4 = new TBarChart;
            $chart4->setTitle(_t('SQL statements by day'));
            $chart4->setHeight(300);
            $chart4->setXLabels([_t('Count')]);
            
            $stats4 = SystemSqlLog::groupBy('log_day')
                                  ->where('log_year', '=', date('Y'))
                                  ->where('log_month', '=', date('m'))
                                  ->orderBy('log_day')
                                  ->countBy('id', 'count');
            $xlabels = $xlabels_std;
            $data = $data_std;
            if ($stats4)
            {
                foreach ($stats4 as $row)
                {
                    $day = str_pad($row->log_day, 2, '0', STR_PAD_LEFT);
                    $data[$day] = (int) $row->count;
                }
            }
            
            $xlabels = array_values($xlabels);
            $data = array_values($data);
            
            $chart4->setXLabels($xlabels);
            $chart4->addDataset(_t('Count'), $data);
            
            
            /*********** Tables *************/
            
            $stats5 = SystemRequestLog::groupBy('class_name')
                                      ->where('log_year', '=', date('Y'))
                                      ->where('log_month', '=', date('m'))
                                      ->orderBy('avg', 'desc')
                                      ->sumByAnd('request_duration', 'total')
                                      ->take(10)
                                      ->avgBy('request_duration', 'avg', function($v) { return round($v,2); });
            
            $stats5 = $stats5 ? (array) json_decode(json_encode($stats5), true) : null;
            
            $table1 = TPanelGroup::pack(_t('Slower pages'), $stats5 ? TTable::fromData( $stats5, ['width' => '100%'], ['style' => 'font-weight:bold'], []) : 'No logs');
            
            $stats6 = SystemRequestLog::groupBy(['class_name', 'class_method'])
                                      ->where('log_year', '=', date('Y'))
                                      ->where('log_month', '=', date('m'))
                                      ->orderBy('avg', 'desc')
                                      ->sumByAnd('request_duration', 'total')
                                      ->take(10)
                                      ->avgBy('request_duration', 'avg', function($v) { return round($v,2); });
            
            $stats6 = $stats6 ? (array) json_decode(json_encode($stats6), true) : null;
            $table2 = TPanelGroup::pack(_t('Slower methods'), $stats6 ? TTable::fromData( $stats6, ['width' => '100%'], ['style' => 'font-weight:bold'], []) : 'No logs');
            
            $html->enableSection('main', ['indicator1' => $indicator1,
                                          'indicator2' => $indicator2,
                                          'indicator3' => $indicator3,
                                          'indicator4' => $indicator4,
                                          'chart1'     => $stats1 ? TPanelGroup::pack('', $chart1) : TPanelGroup::pack(_t('Accesses by day'),'No logs'),
                                          'chart2'     => $stats2 ? TPanelGroup::pack('', $chart2) : TPanelGroup::pack(_t('Requests by day'),'No logs'),
                                          'chart3'     => $stats3 ? TPanelGroup::pack('', $chart3) : TPanelGroup::pack(_t('Request time average'),'No logs'),
                                          'chart4'     => $stats4 ? TPanelGroup::pack('', $chart4) : TPanelGroup::pack(_t('SQL statements by day'),'No logs'),
                                          'table1'     => $stats5 ? $table1 : TPanelGroup::pack(_t('Slower pages'),'No logs'),
                                          'table2'     => $stats6 ? $table2 : TPanelGroup::pack(_t('Slower methods'),'No logs'),
                                          ] );
                                          
            $container = new TVBox;
            $container->style = 'width: 100%';
            $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $container->add($html);
            
            parent::add($container);
            TTransaction::close();
        }
        catch (Exception $e)
        {
            parent::add($e->getMessage());
        }
    }
}
