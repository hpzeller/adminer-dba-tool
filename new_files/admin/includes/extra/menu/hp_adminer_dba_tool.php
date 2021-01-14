<?php
/**
 * --------------------------------------------------------------
 * File: adminer_dba_tool.php
 * Version: 0.1
 * Date: 16.12.2020
 *
 * Author: Hanspeter Zeller
 * Copyright: (c) 2020 - Hanspeter Zeller
 * Web: https://xos-shop.com
 * Contact: info@xos-shop.com
 * --------------------------------------------------------------
 * Released under the GNU General Public License
 * --------------------------------------------------------------
 */

defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

switch ($_SESSION['language_code']) {
    case 'de':
        define('MENU_NAME_ADMINER_DBA_TOOL','Adminer DBA-Tool');
        break;
    default:
        define('MENU_NAME_ADMINER_DBA_TOOL','Adminer DBA Tool');
        break;
}
if ((defined('MODULE_HP_ADMINER_DBA_TOOL_STATUS')) && (MODULE_HP_ADMINER_DBA_TOOL_STATUS == 'true')){
        $add_contents[BOX_HEADING_TOOLS][MENU_NAME_ADMINER_DBA_TOOL][] = array(
            'admin_access_name' => 'hp_adminer_dba_tool',
            'filename' => FILENAME_ADMINER_DBA_TOOL,
            'boxname' => MENU_NAME_ADMINER_DBA_TOOL,
            'parameters' => '',
            'ssl' => '',
        );
}