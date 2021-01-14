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


require('includes/application_top.php');
 
if (MODULE_HP_ADMINER_DBA_TOOL_STATUS == 'true'){

    require (DIR_WS_INCLUDES.'head.php');
    
    $file_contents = '<?php' . "\n" .                    
                     '' . "\n" .
                     'define(\'ADMINER_DBA_TOOL_TOKEN\', \'' . ADMINER_DBA_TOOL_TOKEN . '\');' . "\n" .
                     'define(\'ADMINER_DBA_TOOL_LOG_SQL\', \'' . MODULE_HP_ADMINER_DBA_TOOL_LOG_SQL . '\');' . "\n" .
                     'define(\'ADMINER_DBA_TOOL_PASSWORD\', \'' . MODULE_HP_ADMINER_DBA_TOOL_PASSWORD . '\');' . "\n" .
                     'define(\'DIR_ADMINER\', \'' . DIR_ADMINER . '\');' . "\n" .
                     'define(\'ADMINER_INDEX_FILE\', \'' . ADMINER_INDEX_FILE . '\');';
                     
    $fp = fopen(DIR_FS_CATALOG . 'includes/extra/configure/hp_adminer_dba_tool.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);    
    @chmod(DIR_FS_CATALOG . 'includes/extra/configure/hp_adminer_dba_tool.php', 0644); 
?>
</head>
<body>
<!-- header //-->
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<script>
  function sendMessageToIframe() { 
    var frameWindow = document.getElementById('adminer_iframe').contentWindow;
    frameWindow.postMessage('<?php echo DB_SERVER.'|'.DB_SERVER_USERNAME.'|'.(!empty(DB_SERVER_PASSWORD) ? DB_SERVER_PASSWORD : MODULE_HP_ADMINER_DBA_TOOL_PASSWORD).'|'.DB_DATABASE.'|'.HTTP_SERVER.'|'.MODULE_HP_ADMINER_DBA_TOOL_FILL_LOGIN; ?>', '<?php echo HTTP_SERVER; ?>');
  }
  function resizeCrossDomainIframe(id, other_domain) { 
    var iframe = document.getElementById(id);
    window.addEventListener('message', function(event) {
      if (event.origin !== other_domain) return; // only accept messages from the specified domain
      if (typeof event.data != 'number') return; // only accept something which can be parsed as a number 
      var height = parseInt(event.data) + 32; // add some extra height to avoid scrollbar 
      iframe.height = height + "px";
    }, false);
  }
</script>
<iframe src="<?php echo DIR_ADMINER . '/' . ADMINER_INDEX_FILE . '?adminer_token=' . ADMINER_DBA_TOOL_TOKEN; ?>" id="adminer_iframe" frameborder="0" width="100%" height="1000px" onload="sendMessageToIframe(); resizeCrossDomainIframe('adminer_iframe', '<?php echo HTTP_SERVER; ?>');"></iframe>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');
}else{
    xtc_redirect(xtc_href_link('start.php'));
}
?>