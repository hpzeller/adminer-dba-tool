<?php
/**
 * --------------------------------------------------------------
 * File: adminer_dba_tool.php
 * Version: 0.2
 * Date: 03.06.2022
 *
 * Author: Hanspeter Zeller
 * Copyright: (c) 2022 - Hanspeter Zeller
 * Web: https://xos-shop.com
 * Contact: info@xos-shop.com
 * --------------------------------------------------------------
 * Released under the GNU General Public License
 * --------------------------------------------------------------
 */
 
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

class hp_adminer_dba_tool 
{
    var $code, $title, $description, $enabled, $sort_order;

    public function __construct() 
    {
        $this->code = 'hp_adminer_dba_tool';
        $this->title = MODULE_HP_ADMINER_DBA_TOOL_TITLE;
        $this->description = MODULE_HP_ADMINER_DBA_TOOL_LONG_DESCRIPTION;
        $this->sort_order = 0;
        $this->enabled = ((defined('MODULE_HP_ADMINER_DBA_TOOL_STATUS') && MODULE_HP_ADMINER_DBA_TOOL_STATUS == 'true') ? true : false);                                
    }

    public function process() 
    {
        //do nothing
    }
    
    public function display() 
    {
        return array('text' => '<br /><div align="center">' . xtc_button(BUTTON_SAVE) .
          xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=' . $this->code)) . "</div>");
    }    
    
    public function check() 
    {
        if (!isset($this->_check)) { 
            $check_query = xtc_db_query("SELECT configuration_value
                                           FROM " . TABLE_CONFIGURATION . "
                                          WHERE configuration_key = 'MODULE_HP_ADMINER_DBA_TOOL_STATUS'");
            $this->_check = xtc_db_num_rows($check_query);
        }
        return $this->_check;
    }    

    public function install() 
    {
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added)
              				VALUES ('MODULE_HP_ADMINER_DBA_TOOL_STATUS', 'true',  '6', '1', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added)
              				VALUES ('MODULE_HP_ADMINER_DBA_TOOL_FILL_LOGIN', 'true',  '6', '2', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added)
              				VALUES ('MODULE_HP_ADMINER_DBA_TOOL_LOG_SQL', 'true',  '6', '3', 'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");  
        xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added)
              				VALUES ('MODULE_HP_ADMINER_DBA_TOOL_PASSWORD', '',  '6', '4', 'xtc_cfg_password_field_module(', now())");                                                                    

        xtc_db_query("ALTER TABLE `" . TABLE_ADMIN_ACCESS . "` ADD `" . $this->code . "` INT(1) NOT NULL DEFAULT '0'");
        xtc_db_query("UPDATE `" . TABLE_ADMIN_ACCESS . "` SET `" . $this->code . "` = '1' WHERE customers_id = '1'");
        
        require_once(DIR_FS_INC . 'xtc_random_charcode.inc.php');
        
        $random_charcode_1 = xtc_random_charcode(10);
        $random_charcode_2 = xtc_random_charcode(10);
        
        rename(DIR_FS_CATALOG . DIR_ADMIN . (defined('DIR_ADMINER') ? DIR_ADMINER : 'adminer') . '/' . (defined('ADMINER_INDEX_FILE') ? ADMINER_INDEX_FILE : 'index.php'), DIR_FS_CATALOG . DIR_ADMIN . (defined('DIR_ADMINER') ? DIR_ADMINER : 'adminer') . '/index_' . $random_charcode_2 . '.php');        
        rename(DIR_FS_CATALOG . DIR_ADMIN . (defined('DIR_ADMINER') ? DIR_ADMINER : 'adminer'), DIR_FS_CATALOG . DIR_ADMIN . 'adminer_' . $random_charcode_1);
                                            
        $file_contents = '<?php' . "\n" .                    
                         '' . "\n" .
                         'define(\'ADMINER_DBA_TOOL_TOKEN\', \'' . xtc_random_charcode(10) . '\');' . "\n" .
                         'define(\'ADMINER_DBA_TOOL_LOG_SQL\', \'true\');' . "\n" .
                         'define(\'DIR_ADMINER\', \'adminer_' . $random_charcode_1 . '\');' . "\n" .
                         'define(\'ADMINER_INDEX_FILE\', \'index_' . $random_charcode_2 . '.php\');';                             
                        
        $fp = fopen(DIR_FS_CATALOG . 'includes/extra/configure/hp_adminer_dba_tool.php', 'w');
        fputs($fp, $file_contents);
        fclose($fp);    
        @chmod(DIR_FS_CATALOG . 'includes/extra/configure/hp_adminer_dba_tool.php', 0644);
    }

    public function remove() 
    {
        xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE 'MODULE_HP_ADMINER_DBA_TOOL_%'");
      
        $query_result = xtc_db_query("SHOW COLUMNS FROM `" . TABLE_ADMIN_ACCESS . "`");
        $db_table_rows = [];
        while ($row = xtc_db_fetch_array($query_result)) {
          $db_table_rows[] = $row['Field'];
        }
        
        if (in_array($this->code, $db_table_rows)) {
          xtc_db_query("ALTER TABLE `" . TABLE_ADMIN_ACCESS . "` DROP `" . $this->code . "`;");
        }      
                
        if (defined('DIR_ADMINER') && defined('ADMINER_INDEX_FILE')) { 
        
          rename(DIR_FS_CATALOG . DIR_ADMIN . DIR_ADMINER . '/' . ADMINER_INDEX_FILE, DIR_FS_CATALOG . DIR_ADMIN . DIR_ADMINER . '/index.php');        
          rename(DIR_FS_CATALOG . DIR_ADMIN . DIR_ADMINER , DIR_FS_CATALOG . DIR_ADMIN . 'adminer');        
                                    
          unlink(DIR_FS_CATALOG . 'includes/extra/configure/hp_adminer_dba_tool.php');                            
       }
    }
    
    public function keys() 
    {
        return ['MODULE_HP_ADMINER_DBA_TOOL_STATUS','MODULE_HP_ADMINER_DBA_TOOL_FILL_LOGIN','MODULE_HP_ADMINER_DBA_TOOL_LOG_SQL','MODULE_HP_ADMINER_DBA_TOOL_PASSWORD'];
    }    
}