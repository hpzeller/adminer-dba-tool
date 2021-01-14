<?php

/**
 * @author Hanspeter Zeller, https://xos-shop.com [info@xos-shop.com]
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class AdminerForModified
{

    public function __construct()
    {
      if (
           !defined('ADMINER_DBA_TOOL_TOKEN') || 
           ADMINER_DBA_TOOL_TOKEN == 'false' || 
           (
              !isset($_GET['adminer_token']) && !isset($_GET['server']) && !isset($_GET['username']) && !isset($_GET['db'])
           ) || 
           (
              isset($_GET['adminer_token']) && $_GET['adminer_token'] != ADMINER_DBA_TOOL_TOKEN
           ) || 
           (
              !empty($_GET['server']) && $_GET['server'] != DB_SERVER
           ) || 
           (
              !empty($_GET['username']) && $_GET['username'] != DB_SERVER_USERNAME
           ) || 
           (
              !empty($_GET['db']) && $_GET['db'] != DB_DATABASE
           )
      ) redirect(HTTP_SERVER . DIR_WS_CATALOG . 'logoff.php');
    } 

    public function head()
    {
?>
<style<?php echo nonce(); ?>>
 /*  body {
        visibility:hidden;
    } */   
    ul#logins, a[href$="donation/"] {
        display:none;
    }      
</style>
<script<?php echo nonce(); ?>> 
    (function(document) {
        "use strict"; 
            
        if ( window.location === window.parent.location ) 
        {            
            window.location = "<?php echo HTTP_SERVER . DIR_WS_CATALOG; ?>logoff.php"  
        }          
                           
        window.addEventListener('message', function(e) {
            if (typeof e.data !== 'string') return;
            var da = e.data.split('|');
            if (e.origin !== da[4]) return;  
            if (document.getElementsByName('auth[server]').length>0 && 
                document.getElementsByName('auth[username]').length>0 && 
                document.getElementsByName('auth[password]').length>0 && 
                document.getElementsByName('auth[db]').length>0 &&
                da[5] == 'true') {
                document.getElementsByName('auth[server]')[0].value=da[0];
                document.getElementsByName('auth[username]')[0].value=da[1];
                document.getElementsByName('auth[password]')[0].value=da[2];
                document.getElementsByName('auth[db]')[0].value=da[3]; 
            }
            window.parent.postMessage(document.body.scrollHeight, da[4]);
            window.onresize = function(e) {window.parent.postMessage(document.body.scrollHeight, da[4]);};
        //  document.getElementsByTagName("body")[0].style.visibility = "visible";    
        }, false);
                        
    })(document);
    
</script> 
<noscript>
    This page needs JavaScript activated to work.
    <meta http-equiv="refresh" content="2; URL=<?php echo HTTP_SERVER . DIR_WS_CATALOG; ?>logoff.php"> 
    <style>div { display:none; }</style>
</noscript>

<?php
    }

    public function loginFormField($C,$_d,$Y)
    {
        return $_d.$Y;
    }
                
    public function loginForm()
    {         
    		echo "<table cellspacing='0' class='layout'>\n";
    		echo $this->loginFormField('driver', '<tr><th>' . lang('System') . '<td>', html_select("auth[driver]", ["server"=>"MySQL"], DRIVER, "loginDriver(this);") . "\n");
    		echo $this->loginFormField('server', '<tr><th>' . lang('Server') . '<td>', '<input name="auth[server]" value="' . h(SERVER) . '" title="hostname[:port]" placeholder="localhost" autocapitalize="off">' . "\n");
    		echo $this->loginFormField('username', '<tr><th>' . lang('Username') . '<td>', '<input name="auth[username]" id="username" value="' . h($_GET["username"]) . '" autocomplete="off" autocapitalize="off">');
    		echo $this->loginFormField('password', '<tr><th>' . lang('Password') . '<td>', '<input type="password" name="auth[password]" autocomplete="current-password">' . "\n");
    		echo $this->loginFormField('db', '<tr><th>' . lang('Database') . '<td>', '<input name="auth[db]" value="' . h($_GET["db"]) . '" autocapitalize="off">' . "\n");
    		echo "</table>\n";
    		echo "<p><input type='submit' value='" . lang('Login') . "'>\n";
        return false;
    }               
}
