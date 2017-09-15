<?php

if ( defined('DB_HOST') && defined('DB_USER') && defined('DB_PASS') && defined('DB_NAME') ) {
$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");
mysql_select_db(DB_NAME, $conn) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");

	if ( function_exists( 'mysql_set_charset' ) ) {
		mysql_set_charset('utf8', $conn);
	} else {
		mysql_query("SET NAMES utf8", $conn);
		mysql_query("COLLATE utf8_general_ci", $conn);
	}
}

function get_db_conn() {
	global $conn;
	if ( !is_resource($conn) ) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");
		mysql_select_db(DB_NAME, $conn) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");

		if ( function_exists( 'mysql_set_charset' ) ) {
			mysql_set_charset('utf8', $conn);
		} else {
			mysql_query("SET NAMES utf8", $conn);
			mysql_query("COLLATE utf8_general_ci", $conn);
		}

	}
	return $conn;
}

function dbQuery($sql, $silence = true)
{
	global $config;
	//echo '$sql='.$sql;
	$time = $start_time = $finish_time = $total_exec_time = $slow_query_warning = '';

	$conn = get_db_conn();

  // Start benchmark
  if ( $config['debug_mode_enabled'] == 'true' )
	{
    $time = explode(' ', microtime() );
    $start_time = $time[1] + $time[0];
  }

	$query = mysql_query($sql,$conn) or die( mysql_error());

  // Stop benchmark
  if ( $config['debug_mode_enabled'] == 'true' )
	{
    $time = explode(' ', microtime() );
    $finish_time = $time[1] + $time[0];
    $total_exec_time = number_format( (float) ($finish_time - $start_time) , 2, '.' , '');

    // Slow Query Warning : If over 2 secs
    if( $total_exec_time > 2 )
    {
      $slow_query_warning = '## slow_query';
    }
  }



	if ( $config['debug_mode_enabled'] == 'true' )
	{
		$pt_path	= BASE_PATH;
		$debug_file	= $pt_path.$config['logs_cache_dir']."debug".md5($config['license_key']).".txt";

		if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
			$fh = @fopen($debug_file, 'a');
			@fwrite($fh, $sql." ( ${total_exec_time} ) $slow_query_warning\n");
			@fclose($fh);
		}
	}

	if(!$query && !$silence)
	{
		echo 'Query Error', mysql_error().'<br />Query String: '.$sql;

		return false;
	}

	//mysql_close($conn);

	return $query;
}

function dbQuery_cache($sql, $timeout, $cache_file) {
	global $config;
	$filename = BASE_PATH.$config['xml_cache_dir'] . $cache_file;


	$purpose	= "File / Folder Cache is not writable.";


	if ( is_writable(BASE_PATH.$config['xml_cache_dir']) ) {
		if ( !file_exists($filename) ) {

			if ((file_exists($filename) && (filemtime($filename) < $timeout)) || !file_exists($filename)) {
				//create new cache file.

				$sql_result = dbQuery($sql);
				$datas	= array();
				$i	= 0;
				while($sql_data = mysql_fetch_array($sql_result)) {
					foreach($sql_data as $key => $value ) {
						$datas[$i][$key] = $value;
					}
					$i++;
				}

				if ( count($datas) > 0 ) {
					$data_content	= serialize($datas);
					WriteToFile($filename, $data_content);
				}

				return $datas;
			} else {
				//return cached data.

				$data_content = file_get_contents($filename);
				$datas		= unserialize($data_content);
				return $datas;
			}
		} else if ( file_exists($filename) && is_writable($filename) ) {

				$data_content = file_get_contents($filename);
				$datas		= unserialize($data_content);
				return $datas;
		} else {
			//send email to administrator
			$subject	= "File ".$config['xml_cache_dir'].$cache_file." is not writable.";
			$message	= "File ".$config['xml_cache_dir'].$cache_file." is not writable.\r\nPlease change file permission to 777.";
			$send_mail = send_mail_notification($config['admin_email'], -1, $purpose, $message, $subject);

			$sql_result = dbQuery($sql);
			$datas	= array();
			$i	= 0;
			while($sql_data = mysql_fetch_array($sql_result)) {
				foreach($sql_data as $key => $value ) {
					$datas[$i][$key] = $value;
				}
				$i++;
			}
			return $datas;
		}
	} else {
		//send email to administrator
		$subject	= "Folder ".$config['xml_cache_dir']." is not writable.";
		$message	= "Folder ".$config['xml_cache_dir'].$cache_file." is not writable.\r\nPlease change folder permission to 777.";
		$send_mail = send_mail_notification($config['admin_email'], -1, $purpose, $message, $subject);

		$sql_result = dbQuery($sql);
		$datas	= array();
		$i	= 0;
		while($sql_data = mysql_fetch_array($sql_result)) {
			foreach($sql_data as $key => $value ) {
				$datas[$i][$key] = $value;
			}
			$i++;
		}
		return $datas;
	}
}



function dbTableExist ($table) {

	$result = dbQuery("show tables like '$table'") or die ('error reading database');

	if (mysql_num_rows ($result)>0)
		return true;
	else
		return false;
}


function check_db_conn($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME) {

  	if($conn = @mysql_connect($DB_HOST,$DB_USER,$DB_PASS)) {

  		if (mysql_select_db($DB_NAME, $conn))
			return true;
		else
  			return false;


	}else
  		return false;
}

?>