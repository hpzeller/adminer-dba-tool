<?php
  
	function adminer_object()
	{
    
		include_once "../../includes/configure.php"; 
        
		include_once "./plugins/plugin.php";
    
		foreach (glob("plugins/*.php") as $filename) {
			include_once "./$filename";
		} 

		$plugins = [ 
          
      new AdminerForModified(),      
          
      new AdminerDumpJson(),
      
      new AdminerDumpXml(),
      
      new AdminerDumpArray(),            
      
      new AdminerFrames(true),
      
      new AdminerDatabaseDisplay([DB_DATABASE]),
      
			new AdminerTheme(),			
			// Color variant can by specified in constructor parameter.
			// new AdminerTheme("default-orange"),
			// new AdminerTheme("default-blue"),
			// new AdminerTheme("default-green", ["192.168.0.1" => "default-orange"]),

  		// TODO: inline the result of password_hash() so that the password is not visible in source codes
  		new AdminerLoginPasswordLess(password_hash(ADMINER_DBA_TOOL_PASSWORD, PASSWORD_DEFAULT)),      
      
      (ADMINER_DBA_TOOL_LOG_SQL == 'true' ? new AdminerSqlLog(DIR_FS_LOG . "adminer_sql_log_" . date('Y-m-d') . ".sql") : '')                                 
		];
    
		return new AdminerPlugin($plugins);
	}

  define('_VALID_ADMINER', 'true');

	include "./adminer.php";