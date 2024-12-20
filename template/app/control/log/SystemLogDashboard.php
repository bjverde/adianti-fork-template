<?php
/**
 * SystemLogDashboard
 *
 * @version    8.0
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
            $indicator1 = new THtmlRenderer('app/resources/info-box.html');
            $indicator2 = new THtmlRenderer('app/resources/info-box.html');
            $indicator3 = new THtmlRenderer('app/resources/info-box.html');
            $indicator4 = new THtmlRenderer('app/resources/info-box.html');
            
            $accesses = SystemAccessLog::where('login_year','=',date('Y'))
                                       ->where('login_month','=',date('m'))
                                       ->where('login_day','=',date('d'))
                                       ->count();
            $sqllogs = SystemSqlLog::where('log_year','=',date('Y'))
                                   ->where('log_month','=',date('m'))
                                   ->where('log_day','=',date('d'))
                                   ->count();
            $reqlogs = SystemRequestLog::where('log_year','=',date('Y'))
                                       ->where('log_month','=',date('m'))
                                       ->where('log_day','=',date('d'))
                                       ->count();
            $reqavg = SystemRequestLog::where('log_year','=',date('Y'))
                                      ->where('log_month','=',date('m'))
                                      ->where('log_day','=',date('d'))
                                      ->avgBy('request_duration');
            
            $indicator1->enableSection('main', ['title' => _t('Accesses today'),       'icon' => 'sign-in-alt',   'background' => 'green',  'value' => $accesses]);
            $indicator2->enableSection('main', ['title' => _t('Requests today'),       'icon' => 'globe',         'background' => 'purple', 'value' => $reqlogs]);
            $indicator3->enableSection('main', ['title' => _t('Request time average'), 'icon' => 'hourglass-end', 'background' => 'orange', 'value' => round( (float) $reqavg,2) . ' ms']);
            $indicator4->enableSection('main', ['title' => _t('SQL DML Statements'),   'icon' => 'database',      'background' => 'blue',   'value' => $sqllogs]);
            
            $chart1 = new THtmlRenderer('app/resources/google_column_chart.html');
            $data1 = [];
            $data1[] = [ _t('Day'), _t('Count') ];
            
            $stats1 = SystemAccessLog::groupBy('login_day')
                                     ->where('login_year', '=', date('Y'))
                                     ->where('login_month', '=', date('m'))
                                     ->orderBy('login_day')
                                     ->countBy('id', 'count');
            if ($stats1)
            {
                foreach ($stats1 as $row)
                {
                    $data1[] = [ $row->login_day, (int) $row->count];
                }
            }
            
            // replace the main section variables
            $chart1->enableSection('main', ['data'   => json_encode($data1),
                                            'width'  => '100%',          'height'  => '300px',
                                            'title'  => _t('Accesses by day'),  'uniqid' => uniqid(),
                                            'ytitle' => _t('Accesses by day'),  'xtitle' => _t('Count'),
                                            ]);
            
            $chart2 = new THtmlRenderer('app/resources/google_column_chart.html');
            $data2 = [];
            $data2[] = [ _t('Day'), _t('Count') ];
            
            $stats2 = SystemRequestLog::groupBy('log_day')
                                      ->where('log_year', '=', date('Y'))
                                      ->where('log_month', '=', date('m'))
                                      ->orderBy('log_day')
                                      ->countBy('id', 'count');
            
            if ($stats2)
            {
                foreach ($stats2 as $row)
                {
                    $data2[] = [ $row->log_day, (int) $row->count];
                }
            }
            
            // replace the main section variables
            $chart2->enableSection('main', ['data'   => json_encode($data2),
                                            'width'  => '100%',         'height'  => '300px',
                                            'title'  => _t('Requests by day'),  'uniqid' => uniqid(),
                                            'ytitle' => _t('Requests by day'),   'xtitle' => _t('Count'),
                                            ]);

            $chart3 = new THtmlRenderer('app/resources/google_column_chart.html');
            $data3 = [];
            $data3[] = [ _t('Day'), _t('Sum') ];
            
            $stats3 = SystemRequestLog::groupBy('log_day')
                                      ->where('log_year', '=', date('Y'))
                                      ->where('log_month', '=', date('m'))
                                      ->orderBy('log_day')
                                      ->avgBy('request_duration', 'avg');
            
            if ($stats3)
            {
                foreach ($stats3 as $row)
                {
                    $data3[] = [ $row->log_day, (int) $row->avg];
                }
            }
            
            // replace the main section variables
            $chart3->enableSection('main', ['data'   => json_encode($data3),
                                            'width'  => '100%',         'height'  => '300px',
                                            'title'  => _t('Request time average') . ' (ms)',  'uniqid' => uniqid(),
                                            'ytitle' => _t('Request time average'),   'xtitle' => _t('Count'),
                                            ]);


            $chart4 = new THtmlRenderer('app/resources/google_column_chart.html');
            $data4 = [];
            $data4[] = [ _t('Day'), _t('Count') ];
            
            $stats4 = SystemSqlLog::groupBy('log_day')
                                  ->where('log_year', '=', date('Y'))
                                  ->where('log_month', '=', date('m'))
                                  ->orderBy('log_day')
                                  ->countBy('id', 'count');
            
            if ($stats4)
            {
                foreach ($stats4 as $row)
                {
                    $data4[] = [ $row->log_day, (int) $row->count];
                }
            }
            
            // replace the main section variables
            $chart4->enableSection('main', ['data'   => json_encode($data4),
                                            'width'  => '100%',         'height'  => '400px',
                                            'title'  => _t('SQL statements by day'),  'uniqid' => uniqid(),
                                            'ytitle' => _t('SQL statements by day'),   'xtitle' => _t('Count'),
                                            ]);
            
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
                                          'chart1'     => $stats1 ? $chart1 : TPanelGroup::pack(_t('Accesses by day'),'No logs'),
                                          'chart2'     => $stats2 ? $chart2 : TPanelGroup::pack(_t('Requests by day'),'No logs'),
                                          'chart3'     => $stats3 ? $chart3 : TPanelGroup::pack(_t('Request time average'),'No logs'),
                                          'chart4'     => $stats4 ? $chart4 : TPanelGroup::pack(_t('SQL statements by day'),'No logs'),
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
