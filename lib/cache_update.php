<?php

if ( defined('BASE_PATH') ) {
	

	$is_update_cache	= isset($_GET['cache']) ? true : false;
	$guests_file		= BASE_PATH.$config['html_cache_dir']."guests.html";
	
	if ( !isset($_COOKIE['prismotube']) ) {
	
		if ( !is_spider() ) {
			$ip	= $_SERVER['REMOTE_ADDR'];
			$guestips=dbQuery("SELECT ip FROM `".DB_PREFIX."guest_log` WHERE (ip='$ip')", true);
			
			$ipisnotfound=(mysql_num_rows($guestips)==0) ? true : false;
			if ($ipisnotfound)
			{
				dbQuery("INSERT INTO `".DB_PREFIX."guest_log` ( `time` , `ip` ) VALUES ('".time()."', '$ip')", true);
			}	
		}
	
		setcookie('prismotube', 1);
	}
	
	if ( function_exists('update_cache_html') ) {
		if ( !$is_update_cache ) {
			if ( file_exists($guests_file) ) {
				$time_exists	= time() - filemtime($guests_file);
				if ( $time_exists > $config['stats_cache_timeout']) {
					update_cache_html($guests_file );
				}
			} else {
				update_cache_html($guests_file);
			}
		} else {
			update_cache_html($guests_file );
		}
	}
	
}
?>