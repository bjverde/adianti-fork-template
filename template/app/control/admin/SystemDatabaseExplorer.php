<?php
/**
 * SystemDatabaseExplorer
 *
 * @version    8.2
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemDatabaseExplorer extends TPage
{
    private $datagrid;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        $panel = new TPanelGroup(_t('Database'));
        $panel->style = 'padding-bottom:8px; height: 100%;';
        $panel->getBody()->style = 'overflow-y:auto;';
        $panel->addHeaderActionLink(_t('SQL Panel'), new TAction(['SystemSQLPanel', 'onLoad']), 'fa:code purple');
        
        // create datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
        $this->datagrid->style = 'width: 100%';
        $db_col = $this->datagrid->addQuickColumn('Database', 'database', 'left', '90%');
        
        // create action
        $action1 = new TDataGridAction(array('SystemTableList', 'onLoad'));
        $action1->setParameter('register_state', 'false');
        $action1->setImage('fa:search');
        $action1->setField('database');
        $action1->setLabel(_t('Open'));
        
        $action2 = new TDataGridAction(array($this, 'onExportCSV'));
        $action2->setParameter('register_state', 'false');
        $action2->setImage('fa:download');
        $action2->setField('database');
        $action2->setLabel(_t('Download as CSV'));
        
        $action3 = new TDataGridAction(array($this, 'onExportSQL'));
        $action3->setParameter('register_state', 'false');
        $action3->setImage('fa:download');
        $action3->setField('database');
        $action3->setLabel(_t('Download as SQL'));
        
        $action4 = new TDataGridAction(array($this, 'onReimportSQL'));
        $action4->setParameter('register_state', 'false');
        $action4->setImage('fa:cloud-arrow-up');
        $action4->setField('database');
        $action4->setLabel(_t('Import SQL'));
        $action4->setDisplayCondition([$this, 'onDisplayReimport']);
        
        $agroup = new TDataGridActionGroup( null, 'fa:list');
        $agroup->addAction($action1);
        $agroup->addAction($action2);
        $agroup->addAction($action3);
        $agroup->addAction($action4);
        
        $this->datagrid->addActionGroup($agroup);
        
        $this->datagrid->createModel( false );
        $panel->add($this->datagrid);
        
        // transformer to format database name
        $db_col->setTransformer( function ($value, $object, $cell) {
            $cell->{'style'} = 'position:relative';
            return "<div style='float:left'>{$value}</div><div style='position: absolute;right: 10px;'><span class='badge text-bg-secondary'>{$object->type}</span></div>";
        });
        
        // load database connections into datagrid
        $list = scandir('app/config');
        
        $options = [];
        foreach ($list as $entry)
        {
            if ( (substr($entry, -4) == '.ini') || (substr($entry, -4) == '.php') )
            {
                $connector = str_replace(['.ini', '.php'], ['', ''], $entry);
                $ini = TConnection::getDatabaseInfo($connector);

                if (!empty($ini['type']) && in_array($ini['type'], ['pgsql', 'mysql', 'sqlite', 'oracle', 'mssql', 'fbird']))
                {
                    $options[ $connector ] = $connector;
                    $this->datagrid->addItem( (object) ['database' => $connector, 'type' => $ini['type']] );
                }
            }
        }
        
        // render html
        $replaces['database_browser'] = $panel;
        $html = new THtmlRenderer('app/resources/system/database/browser.html');
        $html->enableSection('main', $replaces);
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($html);
        
        // fix height
        //TScript::create("$('#database_browser_container .panel-body').height( (($(window).height()-260)/2)-100);");
        parent::add($vbox);
    }
    
    /**
     * Display condition for reimport options
     */
    public function onDisplayReimport($object)
    {
        return $object->type == 'sqlite';
    }
    
    /**
     * Export database
     */
    public static function onExportCSV($param)
    {
        try
        {
            $database = $param['database'];
            $files = [];
            
            if (!is_writable('tmp'))
            {
                throw new Exception( _t('Permission denied') . ': tmp');
            }
            
            if (!extension_loaded('zip'))
            {
                throw new Exception( AdiantiCoreTranslator::translate('PHP Module not found') . ': zip' );
            }
            
            $zip = new ZipArchive();
            $output = 'tmp/' . $database. '.zip';
            if (file_exists($output))
            {
                unlink($output);
            }
            
            if (!$zip->open($output, ZIPARCHIVE::CREATE))
            {
                throw new Exception( _t('Permission denied') . ': ' . $output);
            }
            
            // open transaction
            TTransaction::open( $database );
            $conn = TTransaction::get();
            
            $tables = SystemDatabaseInformationService::getDatabaseTables( $database );
            if ($tables)
            {
                foreach ($tables as $table)
                {
                    // run the main query
                    $sql = new TSqlSelect;
                    $sql->setCriteria(new TCriteria);
                    $sql->addColumn('*');
                    $sql->setEntity($table);
                    $result = $conn->query( $sql->getInstruction() );
                    
                    $file = 'tmp/' . $table . '.csv';
                    $files[] = $file;
                    
                    $handler = fopen($file, 'w');
                    
                    $first_row = $result->fetch( PDO::FETCH_ASSOC );
                    if ($first_row)
                    {
                        // CSV headers
                        fputcsv($handler, array_keys($first_row), ',', "\"", '', "\n");
                        fputcsv($handler, $first_row, ',', "\"", '', "\n");
                        
                        // add other rows
                        while ($row = $result->fetch( PDO::FETCH_ASSOC ))
                        {
                            fputcsv($handler, $row, ',', "\"", '', "\n");
                        }
                        fclose($handler);
                        $zip->addFile($file);
                    }
                }
                $zip->close();
                parent::openFile($output);
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Export database
     */
    public static function onExportSQL($param)
    {
        try
        {
            $database = $param['database'];
            $files = [];

            if (!is_writable('tmp'))
            {
                throw new Exception( _t('Permission denied') . ': tmp');
            }

            if (!extension_loaded('zip'))
            {
                throw new Exception( AdiantiCoreTranslator::translate('PHP Module not found') . ': zip' );
            }
            
            $zip = new ZipArchive();
            $output = 'tmp/' . $database. '.zip';
            if (file_exists($output))
            {
                unlink($output);
            }
            if (!$zip->open($output, ZIPARCHIVE::CREATE))
            {
                throw new Exception( _t('Permission denied') . ': ' . $output);
            }

            // open transaction
            TTransaction::open( $database );
            $conn = TTransaction::get();

            $tables = SystemDatabaseInformationService::getDatabaseTables( $database );
            if ($tables)
            {
                foreach ($tables as $table)
                {
                    // run the main query
                    $sql = new TSqlSelect;
                    $sql->setCriteria(new TCriteria);
                    $sql->addColumn('*');
                    $sql->setEntity($table);
                    $result = $conn->query( $sql->getInstruction() );
                    
                    $file = 'tmp/' . $table . '.sql';
                    $files[] = $file;
                    
                    $handler = fopen($file, 'w');
                    
                    $addquotes = function($value) {
                                    if(!is_numeric($value)) {
                                        return "'{$value}'";
                                    } else {
                                        return $value;
                                    }
                                };
                                
                    $first_row = $result->fetch( PDO::FETCH_ASSOC );
                    if ($first_row)
                    {
                        $columns = implode(',', array_keys($first_row));
                        $values  = implode(',', array_map($addquotes, array_values($first_row)));
                        fwrite($handler, "INSERT INTO {$table} ({$columns}) VALUES ({$values});\n");
                        
                        // add other rows
                        while ($row = $result->fetch( PDO::FETCH_ASSOC ))
                        {
                            $values  = implode(',', array_map($addquotes, array_values($row)));
                            fwrite($handler, "INSERT INTO {$table} ({$columns}) VALUES ({$values});\n");
                        }
                        fclose($handler);
                        $zip->addFile($file);
                    }
                }
                $zip->close();
                parent::openFile($output);
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * RE Import SQL File
     */
    public static function onReimportSQL($param)
    {
        $form = new BootstrapFormBuilder('import_sql');

        $db = new THidden('database');
        $file = new TFile('file');
        $file->setAllowedExtensions(['zip']);
        $db->setValue($param['database']);

        $form->addFields( [$db]);
        $form->addFields( [$file]);

        $form->addAction(_t('Import SQL'), new TAction([__CLASS__, 'onConfirmImport']), 'fa:check');

        // show the input dialog
        new TInputDialog(_t('Import SQL'), $form);
    }
    
    /**
     * Import SQL instructions from uploaded file
     */
    public static function onConfirmImport($param)
    {
        try
        {
            $file = 'tmp/'.$param['file'];
            if (file_exists($file))
            {
                $dbinfo = TConnection::getDatabaseInfo($param['database']);
                $dbinfo['fkey'] = '0';
                $conn = TTransaction::open(null, $dbinfo);
                $conn-> query ('PRAGMA foreign_keys = OFF');

                $zip = new ZipArchive();

                if ($zip->open($file) === TRUE)
                {
                    for ($i = 0; $i < $zip->numFiles; $i++)
                    {
                        $table    = $zip->getNameIndex($i);
                        $commands = array_filter(explode(";\n", $zip->getFromIndex($i)));

                        if ($commands)
                        {
                            foreach ($commands as $command)
                            {
                                $result = $conn->query($command);
                                if (!$result)
                                {
                                    throw new Exception('error', _t('Error') . ' ' . $command);
                                }
                            }
                        }
                    }
                    $zip->close();
                }
                else
                {
                    throw new Exception('error', _t('Permission denied') . ': ' . $file);
                }
                TTransaction::close();
                new TMessage('info', _t('Records imported successfully'));
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage() . ' in <b>' . $table . '</b>');
        }
    }
}
