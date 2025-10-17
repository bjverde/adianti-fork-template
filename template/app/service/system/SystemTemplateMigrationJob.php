<?php
/**
 * SystemTemplateMigrationJob
 *
 * @version    8.3
 * @package    service
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemTemplateMigrationJob implements AdiantiJob
{
    /**
     * Run all migrations
     */
    public static function run($param)
    {
        if (PHP_SAPI !== 'cli')
        {
            throw new Exception(_t('Permission denied'));
        }
        
        echo "\n";
        echo "* Migrating Permissions\n";
        self::migratePermission($param);
        
        echo "* Migrating Programs\n";
        self::migratePrograms($param);
        
        echo "* Migrating Configs\n";
        self::migrateConfigs($param);
        
        echo "* Migrating Menu\n";
        self::migrateMenu($param);
        
        echo "* Migrating SQLites\n";
        self::migrateSQLites($param);
        
        echo "* Migrating Images\n";
        self::migrateImages($param);
        
        echo "* Migrating Translations\n";
        self::migrateTranslations($param);
        
        echo "\n";
    }
    
    /**
     * Permission migration
     */
    private static function migratePermission($param)
    {
        $cwd = getcwd();
        
        $target = TTransaction::open('permission');
        // clear permission tables in target (just to load table from the right path)
        TDatabase::clearData($target, 'system_program_method_role');
        
        // open source connection
        chdir($param['from']);
        
        $system_user_table = (@file_get_contents('lib/VERSION') >= '7.6') ? 'system_users' : 'system_user';
        
        //TConnection::setConfigPath($param['from'].'/app/config');
        $source = TTransaction::open('permission');
        
        // define structure fields to be migrated
        $structures = [];
        $structures['system_group'] = ['id', 'name'];
        $structures['system_program'] = ['id', 'name', 'controller'];
        $structures['system_unit'] = ['id', 'name'];
        $structures["{$system_user_table}:system_users"] = ['id', 'login', 'name', 'password', 'email', 'active', 'system_unit_id'];
        $structures['system_group_program'] = [ 'id', 'system_group_id', 'system_program_id'];
        $structures['system_user_group'] = [ 'id', 'system_user_id', 'system_group_id'];
        $structures['system_user_program'] = [ 'id', 'system_user_id', 'system_program_id'];
        $structures['system_user_unit'] = [ 'id', 'system_user_id', 'system_unit_id'];
        
        $uniqs = [];
        $uniqs['system_program'] = 'controller';
        $uniqs['system_users'] = 'login';
        
        $fkeys = [];
        $fkeys['system_group_id'] = 'system_group';
        $fkeys['system_program_id'] = 'system_program';
        $fkeys['system_user_id'] = 'system_users';
        $fkeys['system_unit_id'] = 'system_unit';
        
        $id_map = [];
        
        // run migrations
        foreach ($structures as $table => $fields)
        {
            $from_table = $table;
            $to_table   = $table;
            
            if (strpos($table,':') !== false)
            {
                list($from_table,$to_table) = explode(':', $table);
            }
            
            echo "    * $from_table\n";
            //echo "============================================\n";
            
            // initialize id_map
            $id_map[$to_table] = [];
            
            // column map
            $mapping = array_map(fn($item) => [$item, $item], $fields);
            
            // source query
            $query = 'SELECT '. implode(',', $fields) . ' FROM '. $from_table;
            
            // get data from source and insert data into target using lambda function
            TDatabase::getData($source, $query, $mapping, null, function($values) use ($target, $to_table, $fkeys, $uniqs, &$id_map) {
                $old_id = $values['id'];
                
                // remap foreign keys
                foreach ($fkeys as $fkey => $fkey_source)
                {
                    if (isset($values[$fkey]))
                    {
                        $old_fk_value = $values[$fkey];
                        if (isset($id_map[$fkey_source][$old_fk_value]))
                        {
                            $values[$fkey] = $id_map[$fkey_source][$old_fk_value];
                        }
                        else
                        {
                            // if not found, ignore record
                            return;
                        }
                        // echo "    Remap $to_table.$fkey $old_fk_value to {$id_map[$fkey_source][$old_fk_value]} \n";
                    }
                }
                // prepare new id
                $new_id = TDatabase::getData($target, 'SELECT coalesce(max(id),0)+1 FROM '. $to_table)[0][0];
                
                // fill id_map with new id
                $id_map[$to_table][$old_id] = $new_id;
                $values['id'] = $new_id;
                
                $found = false;
                
                // if there's a unique constraint (user.login, program.controller)
                if (isset($uniqs[$to_table]))
                {
                    $uniq_field = $uniqs[$to_table];
                    $found = TDatabase::getData($target, "SELECT * FROM {$to_table} WHERE $uniq_field = '{$values[$uniq_field]}'");
                    
                    // found an existing record with that unique constraint
                    if ($found)
                    {
                        // fill id map with found record
                        $id_map[$to_table][$old_id] = $found[0]['id'];
                    }
                }
                
                if (!$found)
                {
                    // insert new record
                    // echo "    Insert into $to_table (" . implode(',', $values) . ") \n";
                    TDatabase::insertData($target, $to_table, $values);
                }
                else
                {
                    // debug found record
                    // echo "    Found $to_table (" . $found[0]['id'] . ',' . $found[0][$uniq_field] . ") \n";
                }
            });
        }
        
        // back to target path
        chdir($cwd);
        TConnection::setConfigPath($cwd.'/app/config');
        
        TTransaction::close();
        TTransaction::close();
    }
    
    /**
     * Programs migration
     */
    private static function migratePrograms($param)
    {
        $from = $param['from'];
        $to   = getcwd();
        
        $migrate = [];
        $migrate[] = 'app/control';
        $migrate[] = 'app/model';
        $migrate[] = 'app/service';
        $migrate[] = 'app/view';
        
        $exclusions = [];
        $exclusions['app/control'] = ['admin', 'communication', 'log', 'SearchBox.php', 'SearchBox.class.php', 'MessageList.class.php', 'NotificationList.class.php'];
        $exclusions['app/model']   = ['admin', 'communication', 'log'];
        $exclusions['app/service'] = ['auth','cli','jobs','log','rest','system'];
        
        foreach ($migrate as $folder)
        {
            $source = "$from/$folder";
            $destination = "$to/$folder";
            
            if (is_dir($source))
            {
                self::copyRecursive($source, $destination, $exclusions[$folder] ?? []);
            }
        }
    }
    
    /**
     * Config migration
     */
    private static function migrateConfigs($param)
    {
        $from = $param['from'];
        $to   = getcwd();
        
        $migrate = [];
        $migrate[] = 'app/config';
        
        $exclusions = [];
        $exclusions['app/config'] = ['application.ini', 'application.php', 'communication.php', 'communication.ini', 'log.php', 'log.ini', 'permission.ini', 'permission.php', 'framework_hashes.php'];
        
        foreach ($migrate as $folder)
        {
            $source = "$from/$folder";
            $destination = "$to/$folder";
            
            if (is_dir($source))
            {
                self::copyRecursive($source, $destination, $exclusions[$folder] ?? []);
            }
        }
        
        if (file_exists("$from/app/config/application.ini"))
        {
            $ini = parse_ini_file("$from/app/config/application.ini", true);
        }
        else if (file_exists("$from/app/config/application.php"))
        {
            $ini = require_once "$from/app/config/application.php";
        }
        
        if (!empty($ini))
        {
            $application = $ini['general']['application'];
            $title = $ini['general']['title'] ?? 'Aplicação';
            
            $new = file_get_contents('app/config/application.php');
            $new = str_replace("'application' => 'template',", "'application' => '{$application}',", $new);
            $new = str_replace("'title' => 'Adianti Template 8.3',", "'title' => '{$title}',", $new);
            file_put_contents('app/config/application.php', $new);
        }
    }
    
    /**
     * Menu migration
     */
    private static function migrateMenu($param)
    {
        $from = $param['from'];
        $to   = getcwd();
        
        copy("{$from}/menu.xml", "{$to}/menu.xml");
    }
    
    /**
     * SQLite migration
     */
    private static function migrateSQLites($param)
    {
        $from = $param['from'].'/app/database';
        $to   = getcwd().'/app/database';
        
        $exclusions = ['communication.db', 'log.db', 'permission.db'];
        
        foreach (glob($from."/*.db") as $entry)
        {
            if (!in_array(basename($entry), $exclusions))
            {
                $target = $to . '/' . basename($entry);
                @copy($entry, $target);
                chmod($target, 0777);
            }
        }
    }
    
    /**
     * Config migration
     */
    private static function migrateImages($param)
    {
        $from = $param['from'];
        $to   = getcwd();
        
        self::copyRecursive("$from/app/images", "$to/app/images", []);
    }
    
    /**
     * Translation migration
     */
    private static function migrateTranslations($param)
    {
        $from = $param['from'];
        $to   = getcwd();
        
        $source = "$from/app/lib/util/ApplicationTranslator.class.php";
        
        if (file_exists("$from/app/lib/util/ApplicationTranslator.php"))
        {
            $source = "$from/app/lib/util/ApplicationTranslator.php";
        }
        
        $terms = [];
        $terms[] = '        $this->messages = [];';
        
        foreach(file($source) as $row)
        {
            if (substr(trim($row),0,17) == '$this->messages[\'')
            {
                $terms[] = str_replace(["\n", "\r"],['',''],$row);
            }
        }
        
        $new = file_get_contents("$to/app/lib/util/ApplicationTranslator.php");
        $new = str_replace('//<entry-point>', implode("\n", $terms) . "\n".  '//<entry-point>', $new);
        file_put_contents("$to/app/lib/util/ApplicationTranslator.php", $new);
    }
    
    /**
     * Copy recursive between two folders
     */
    private static function copyRecursive($source, $destination, $exclusions)
    {
        if (!is_dir($destination))
        {
            mkdir($destination, 0755, true);
        }

        $items = scandir($source);

        foreach ($items as $item)
        {
            if ($item === '.' || $item === '..' || in_array($item, $exclusions))
            {
                continue;
            }

            $sourceItem = "$source/$item";
            $destinationItem = "$destination/$item";
            
            if (is_dir($sourceItem))
            {
                self::copyRecursive($sourceItem, $destinationItem, $exclusions);
            }
            else
            {
                copy($sourceItem, $destinationItem);
            }
        }
    }
}
