<?php
/**
 * SystemAdministrationDashboard
 *
 * @version    8.3
 * @package    control
 * @subpackage log
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemAdministrationDashboard extends TPage
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
            $html = new THtmlRenderer('app/resources/system/admin/dashboard.html');
            
            TTransaction::open('permission');
            
            $indicator1 = new TNumericIndicator;
            $indicator1->setTitle(_t('Users'));
            $indicator1->setValue(SystemUser::count());
            $indicator1->setIcon('user');
            $indicator1->setColor('#ff851b');
            $indicator1->setNumericMask(0, ',', '.');
            
            $indicator2 = new TNumericIndicator;
            $indicator2->setTitle(_t('Groups'));
            $indicator2->setValue(SystemGroup::count());
            $indicator2->setIcon('users');
            $indicator2->setColor('#0073b7');
            $indicator2->setNumericMask(0, ',', '.');
            
            $indicator3 = new TNumericIndicator;
            $indicator3->setTitle(_t('Units'));
            $indicator3->setValue(SystemUnit::count());
            $indicator3->setIcon('university');
            $indicator3->setColor('#605ca8');
            $indicator3->setNumericMask(0, ',', '.');
            
            $indicator4 = new TNumericIndicator;
            $indicator4->setTitle(_t('Programs'));
            $indicator4->setValue(SystemProgram::count());
            $indicator4->setIcon('code');
            $indicator4->setColor('#00a65a');
            $indicator4->setNumericMask(0, ',', '.');
            
            $chart1 = new TBarChart;
            $chart1->setTitle(_t('Users by group'));
            $chart1->setHeight(500);
            $chart1->setXLabels([_t('Users')]);
            
            $stats1 = SystemUserGroup::groupBy('system_group_id')->countBy('system_user_id', 'count');
            if ($stats1)
            {
                foreach ($stats1 as $row)
                {
                    $chart1->addDataset( SystemGroup::find($row->system_group_id)->name, [ (int) $row->count] );
                }
            }
            
            $chart2 = new TPieChart;
            $chart2->setTitle(_t('Users by unit'));
            $chart2->setHeight(500);
            
            $stats2 = SystemUserUnit::groupBy('system_unit_id')->countBy('system_user_id', 'count');
            
            if ($stats2)
            {
                foreach ($stats2 as $row)
                {
                    $chart2->addSlice( SystemUnit::find($row->system_unit_id)->name, (int) $row->count );
                }
            }
            
            $html->enableSection('main', ['indicator1' => $indicator1,
                                          'indicator2' => $indicator2,
                                          'indicator3' => $indicator3,
                                          'indicator4' => $indicator4,
                                          'chart1'     => TPanelGroup::pack('', $chart1),
                                          'chart2'     => TPanelGroup::pack('', $chart2)] );
            
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
