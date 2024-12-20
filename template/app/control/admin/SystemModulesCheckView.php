<?php
/**
 * SystemModulesCheckView
 *
 * @version    8.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemModulesCheckView extends TPage
{
    function __construct()
    {
        parent::__construct();
        parent::setTargetContainer('adianti_right_panel');
        
        try 
        {
            $extensions = ['general' =>
                            ['mbstring' => 'MBString',
                             'curl' => 'CURL',
                             'dom' => 'DOM',
                             'xml' => 'XML',
                             'zip' => 'ZIP',
                             'json' => 'JSON',
                             'libxml' => 'LibXML',
                             'openssl' => 'OpenSSL',
                             'zip' => 'ZIP',
                             'SimpleXML' => 'SimpleXML',
                             'fileinfo' => 'FileInfo'],
                          'database' =>
                            ['PDO' => 'PDO',
                             'pdo_sqlite' => 'PDO SQLite',
                             'pdo_mysql' => 'PDO MySql',
                             'pdo_pgsql' => 'PDO PostgreSQL',
                             'pdo_oci' => 'PDO Oracle',
                             'pdo_dblib' => 'PDO Sql Server via dblib',
                             'pdo_sqlsrv' => 'PDO Sql Server via sqlsrv',
                             'PDO_Firebird' => 'PDO Firebird',
                             'odbc' => 'PDO ODBC']];
            
            $framework_extensions = array_keys( array_merge( $extensions['general'], $extensions['database'] ));
            
            $panel1 = new TPanelGroup('PHP Info');
            $panel1->class = 'card panel noborder nomargin';
            $panel1->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
            
            $this->datagrid = new BootstrapDatagridWrapper(new TQuickGrid);
            $this->datagrid->width = '100%';
            $this->datagrid->disableHtmlConversion();
            
            // add the columns
            $this->datagrid->addQuickColumn('DIRECTIVE',    'directive',   'center', '20%');
            $this->datagrid->addQuickColumn('CURRENT',      'current',     'center', '25%');
            $this->datagrid->addQuickColumn('DEVELOPMENT',  'development', 'center', '25%');
            $this->datagrid->addQuickColumn('PRODUCTION',   'production',  'center', '30%');
            $this->datagrid->createModel();
            
            $warning = '&nbsp;<i class="fa fa-exclamation-triangle red" aria-hidden="true"></i>';
            $success = '&nbsp;<i class="far fa-check-circle green" aria-hidden="true"></i>';
            
            $error_level_tostring = function($intval, $separator = ',') {
                
                $error_levels = array(
                    E_ALL => 'E_ALL',
                    E_USER_DEPRECATED => 'E_USER_DEPRECATED',
                    E_DEPRECATED => 'E_DEPRECATED',
                    E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
                    2048 => 'E_STRICT', /* using number avoid deprecated in php8.4 */
                    E_USER_NOTICE => 'E_USER_NOTICE',
                    E_USER_WARNING => 'E_USER_WARNING',
                    E_USER_ERROR => 'E_USER_ERROR',
                    E_COMPILE_WARNING => 'E_COMPILE_WARNING',
                    E_COMPILE_ERROR => 'E_COMPILE_ERROR',
                    E_CORE_WARNING => 'E_CORE_WARNING',
                    E_CORE_ERROR => 'E_CORE_ERROR',
                    E_NOTICE => 'E_NOTICE',
                    E_PARSE => 'E_PARSE',
                    E_WARNING => 'E_WARNING',
                    E_ERROR => 'E_ERROR');
                $used = [];
                foreach($error_levels as $number => $name)
                {
                    if (($intval & $number) == $number) {
                        $used[] = $name;
                    }
                }
                
                $all_but_e_all = $error_levels;
                unset($all_but_e_all[E_ALL]);
                $notused = array_diff($all_but_e_all, $used);
                
                if (!empty($notused))
                {
                    return 'E_ALL & ~' . implode(' & ~', $notused);
                }
                return 'E_ALL';
            };
            
            $item = new stdClass;
            $item->directive   = 'error_reporting';
            $item->current     = '<span><b>'.$error_level_tostring(ini_get($item->directive)).'</b></span>';
            $item->development = ini_get($item->directive) == E_ALL ?
                                '<span class="green"><b>E_ALL</b></span>' . $success:
                                '<span class="red"><b>E_ALL</b></span>' . $warning;
            $item->production  = ini_get($item->directive) == E_ALL - E_DEPRECATED ?
                                '<span class="green"><b>E_ALL & ~E_DEPRECATED</b></span>' . $success:
                                '<span class="red"><b> E_ALL & ~E_DEPRECATED</b></span>' . $warning;
            $this->datagrid->addItem($item);
            
            $item = new stdClass;
            $item->directive   = 'display_errors';
            $item->current     = '<span><b>' . (ini_get($item->directive) ? 'On' : 'Off' ) . '</b></span>';
            $item->development = ini_get($item->directive)  ? '<span class="green"><b>On</b></span>' . $success : '<span class="red"><b>On</b></span>' . $warning;
            $item->production  = !ini_get($item->directive) ? '<span class="green"><b>Off</b></span>' . $success: '<span class="red"><b>Off</b></span>' . $warning;
            $this->datagrid->addItem($item);
            
            $item = new stdClass;
            $item->directive   = 'log_errors';
            $item->current     = '<span><b>' . (ini_get($item->directive) ? 'On' : 'Off' ) . '</b></span>';
            $item->development = ini_get($item->directive) ? '<span class="green"><b>On</b></span>' . $success: '<span class="red"><b>On</b></span>' . $warning;
            $item->production  = ini_get($item->directive) ? '<span class="green"><b>On</b></span>' . $success: '<span class="red"><b>On</b></span>' . $warning;
            $this->datagrid->addItem($item);
            
            $item = new stdClass;
            $item->directive   = 'output_buffering';
            $item->current     = '<span><b>' . ini_get($item->directive) . '</b></span>';
            $item->development = ini_get($item->directive) == '4096' ? '<span class="green"><b>4096</b></span>' . $success : '<span class="red"><b>4096</b></span>' . $warning;
            $item->production  = ini_get($item->directive) == '4096' ? '<span class="green"><b>4096</b></span>' . $success : '<span class="red"><b>4096</b></span>' . $warning;
            $this->datagrid->addItem($item);
            
            $item = new stdClass;
            $item->directive   = 'session.use_only_cookies';
            $item->current     = '<span><b>' . (ini_get($item->directive) ? 'On' : 'Off' ) . '</b></span>';
            $item->development = ini_get($item->directive)  ? '<span class="green"><b>On</b></span>' . $success : '<span class="red"><b>On</b></span>' . $warning;
            $item->production  = ini_get($item->directive)  ? '<span class="green"><b>On</b></span>' . $success : '<span class="red"><b>On</b></span>' . $warning;
            $this->datagrid->addItem($item);
            
            $item = new stdClass;
            $item->directive   = 'session.cookie_httponly';
            $item->current     = '<span><b>' . (ini_get($item->directive) ? 'On' : 'Off' ) . '</b></span>';
            $item->development = ini_get($item->directive)  ? '<span class="green"><b>On</b></span>' . $success : '<span class="red"><b>On</b></span>' . $warning;
            $item->production  = ini_get($item->directive)  ? '<span class="green"><b>On</b></span>' . $success : '<span class="red"><b>On</b></span>' . $warning;
            $this->datagrid->addItem($item);
            
            $item = new stdClass;
            $item->directive   = 'request_order';
            $item->current     = '<span><b>' . ini_get($item->directive) . '</b></span>';
            $item->development = ini_get($item->directive) == 'GP' ? '<span class="green"><b>GP</b></span>' . $success : '<span class="red"><b>GP</b></span>' . $warning;
            $item->production  = ini_get($item->directive) == 'GP' ? '<span class="green"><b>GP</b></span>' . $success : '<span class="red"><b>GP</b></span>' . $warning;
            $this->datagrid->addItem($item);
            
            $item = new stdClass;
            $item->directive   = 'opcache.enable';
            $item->current     = ini_get($item->directive) ? '<span><b>On</b></span>': '<span><b>Off</b></span>';
            $item->development = ini_get($item->directive) ? '<span class="green"><b>On</b></span>' . $success: '<span class="red"><b>On</b></span>' . $warning;
            $item->production  = ini_get($item->directive) ? '<span class="green"><b>On</b></span>' . $success: '<span class="red"><b>On</b></span>' . $warning;
            $this->datagrid->addItem($item);
            
            $panel1->add($this->datagrid);
            $panel1->addFooter(new TAlert('info', _t('The php.ini current location is <b>^1</b>', php_ini_loaded_file())) .
                               new TAlert('warning', '<b>Note</b>: error_reporting and display_errors are automatic enabled when debug=1 in application.php'));
            
            $panel2 = new TPanelGroup('PHP Modules');
            $panel2->class = 'card panel noborder nomargin';
            
            $row = new TElement('div');
            $row->class = 'row';
            $row->style = 'margin:0';
            
            foreach ($extensions as $type => $modules)
            {
                $module_block = new TElement('div');
                $module_block->style = 'font-size:17px; padding-left: 20px';
                $module_block->class = 'col-sm-6';
                $module_block->add( '<b>' . strtoupper($type) . '</b>');
                
                foreach ($modules as $extension => $name) 
                {
                    if (extension_loaded($extension))
                    {
                        $element = new TElement('div');
                        $element->style = 'font-size:17px; padding: 5px';
                        $element->add( TElement::tag('i', '', ['class' => 'fa fa-check green fa-fw']) );
                        $element->add("{$name} ({$extension})");
                    }
                    else
                    {
                        $element = new TElement('div');
                        $element->style = 'font-size:17px; padding: 5px';
                        $element->add( TElement::tag('i', '', ['class' => 'fa fa-times red fa-fw']) );
                        $element->add("{$name} ({$extension})");
                    }
                    
                    $module_block->add($element);
                }
                $row->add($module_block);
            }
            
            $panel2->add($row);
            // $panel2->addFooter(new TAlert('info', _t('The php.ini current location is <b>^1</b>', php_ini_loaded_file())));
            
            $panel3 = new TPanelGroup('Another Modules');
            $panel3->class = 'card panel noborder nomargin';
            
            $extensions = get_loaded_extensions();
            $another_ext = array_diff($extensions, $framework_extensions);
            $another_ext = array_unique(array_merge($another_ext, ['session', 'date', 'zlib', 'gd', 'Phar']));
            natcasesort($another_ext);
            
            $row = new TElement('div');
            $row->class = 'row';
            $row->style = 'margin:0';
            
            foreach ($another_ext as $extension)
            {
                if (extension_loaded($extension))
                {
                    $element = new TElement('div');
                    $element->style = 'font-size:17px; padding: 5px';
                    $element->class = 'col-sm-3';
                    $element->add( TElement::tag('i', '', ['class' => 'fa fa-check green fa-fw']) );
                    $element->add("{$extension}");
                }
                else
                {
                    $element = new TElement('div');
                    $element->style = 'font-size:17px; padding: 5px';
                    $element->class = 'col-sm-3';
                    $element->add( TElement::tag('i', '', ['class' => 'fa fa-times red fa-fw']) );
                    $element->add("{$extension}");
                }
                
                $row->add($element);
            }
            $panel3->add($row);
            // $panel3->addFooter(new TAlert('info', _t('The php.ini current location is <b>^1</b>', php_ini_loaded_file())));
            
            $include_path = get_include_path();
            
            ob_start();
            phpinfo();
            $content = ob_get_contents();
            $content = str_replace($include_path, str_replace([':', '/'], [': ', '/ '], $include_path), $content);
            $content = str_replace(',', ', ', $content);
            $content = str_replace(':/', ': /', $content);
            ob_end_clean();
            $content = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$'.'1',$content);
            $div = new TElement('div');
            $div->{'id'} = 'phpinfo';
            
            // Está assim (um por linha), por que o parser ignora linhas que iniciam com "#"
            $styles = '<style type="text/css">';
            $styles.= '#phpinfo pre {margin: 0px; font-family: monospace;} ';
            $styles.= '#phpinfo a:link {color: #000099; text-decoration: none; background-color: #ffffff;} ';
            $styles.= '#phpinfo a:hover {text-decoration: underline;} ';
            $styles.= '#phpinfo table {border-collapse: collapse;} ';
            $styles.= '#phpinfo .center {text-align: center;} ';
            $styles.= '#phpinfo .center table { margin-left: auto; margin-right: auto; text-align: left;} ';
            $styles.= '#phpinfo .center th { text-align: center !important; } ';
            $styles.= '#phpinfo td, #phpinfo th { border: 1px solid gray; font-size: 75%; vertical-align: baseline; padding: 5px} ';
            $styles.= '#phpinfo h1 {font-size: 150%;} ';
            $styles.= '#phpinfo h2 {font-size: 125%;} ';
            $styles.= '#phpinfo .p {text-align: left;} ';
            $styles.= '#phpinfo .e {background-color: whiteSmoke; font-weight: bold; color: #000000;} ';
            $styles.= '#phpinfo .h {background-color: #888888; font-weight: bold; color: white;} ';
            $styles.= '#phpinfo .v {background-color: white; color: #000000;} ';
            $styles.= '#phpinfo i {color: #666666; background-color: #cccccc;} ';
            $styles.= '#phpinfo img {float: right; border: 0px;} ';
            $styles.= '#phpinfo hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;} ';
            $styles.= '#phpinfo table { width: 100%;} ';
            $styles.= '#phpinfo td, #phpinfo th{ font-size: 90% !important;} ';
            $styles.= '#phpinfo td.e {    width: 30%;} </style>';
    
            $div->add($styles);
            $div->add($content);
            
            $panel4 = new TPanelGroup('PHP Info');
            $panel4->class = 'card panel noborder nomargin';
            $panel4->add($div);
            
            $panel4->getBody()->style = "overflow-x:auto;";
            
            $container = new TVBox;
            $container->style = 'width: 100%';
            $container->add($panel1);
            $container->add($panel2);
            $container->add($panel3);
            $container->add($panel4);
            
            parent::add($container);
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());
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
