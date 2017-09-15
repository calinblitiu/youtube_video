<?php 
@define("DS", DIRECTORY_SEPARATOR);
@session_start();

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	include('inc/auth.php');
	die();
}

include('inc/includes.php');
include('../lib/services.php');
$debug_file	= '../'.$config['logs_cache_dir']."debug".md5($config['license_key']).".txt";




if( $_GET['action'] == "view_debug_log" )
{
	if( is_file( $debug_file ) )
	{
		header("Location: $debug_file");
	}
	else
	{
		die('Debug file doesn\'t exist. It will only be created when you turn on the Debug Mode in your Admin section');
	}
}

// Count the Cache Files
	$ctr_cache		= 0;
	$path_xml_cache_dir	= '../'.$config["xml_cache_dir"];
	if ($handle = @opendir($path_xml_cache_dir)) {
		/* This is the correct way to loop over the directory. */
		while (false !== ($file = readdir($handle))) {
			if ( $file != '' && $file != '.' && $file != '..' ) {
				if ( !is_dir($path_xml_cache_dir.DS.$file) ) {
					$ctr_cache++;
				}
			}
			if ( $ctr_cache > 1000 ) {
				break;
			}
		}
		closedir($handle);
		
	}


$url_datas	= REST_Request("http://gdata.youtube.com/feeds/api/videos?category=bass%2Cfishing&v=2&alt=jsonc");

if ( !$url_datas ) {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;Unable to access Youtube API.</div><div>&nbsp;</div>";
} else {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />Your website is able to access Youtube API.</div><div>&nbsp;</div>";
}

if ( !$config['xml_cache_enable'] ) {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;Enable Cache XML is strongly recommended.</div><div>&nbsp;</div>";
} else {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />Cache XML has been enabled.</div><div>&nbsp;</div>";
}

if ( !is_writable('../'.$config['xml_cache_dir']) ) {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;Cache XML Folder is not writable.</div><div>&nbsp;</div>";
} else {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />Cache XML Folder is writable.</div><div>&nbsp;</div>";
}

if ( $config["xml_cache_timeout"] < 3600 ) {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;XML Cache Timeout at least 3600 seconds is strongly recommended.</div><div>&nbsp;</div>";
} else {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />XML Cache Timeout is {$config["xml_cache_timeout"]} seconds.</div><div>&nbsp;</div>";
}

if ( file_exists( '../'."license".DS."key.php") ) {
	if ( !is_writable('../'.$config['html_cache_dir']) ) {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;license/key.php is not writable. Please make it writable.</div><div>&nbsp;</div>";
	} else {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />license/key.php is exist and writable.</div><div>&nbsp;</div>";
	}
} else {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;license/key.php doesn't exist.</div><div>&nbsp;</div>";
}

if ( isset($config['categories_cache_enable']) && $config['categories_cache_enable'] == 'true' ) {
	$sqlQuery	= "SELECT COUNT(*) AS `total` FROM `".DB_PREFIX."categories` WHERE `position` RLIKE '^[0-9]+>[0-9]+>[0-9]+>$'";
	$sqlResult	= dbQuery($sqlQuery);
	$data		= mysql_fetch_array($sqlResult);
	if ( $data['total'] > 0 ) {
		echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />Categories Cache can only support categories up to 2 levels deep</div>";
	}
}

if ( file_exists("../.htaccess") ) {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />.htaccess file exists.</div><div>&nbsp;</div>";
} else {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;.htaccess file does not exist.</div><div>&nbsp;</div>";
}



$filename 	= "../.htaccess";
$handle	 	= fopen($filename, "r");
$contents 	= fread($handle, filesize($filename));
fclose($handle);

$is_check_pass	= false;
$line_contents	= explode("\n", $contents);

if ( isset($line_contents[0]) ) {
	$first_line = strtolower($line_contents[0]);
	if ( strpos($first_line, '# begin prismotube') !== false ) {
		$is_check_pass	= true;
	}
}

if ( count($line_contents) < 10 ) {
	$is_check_pass	= false;
} else {
	$is_check_pass	= true;
}

if ( $is_check_pass ) {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />.htaccess check passed.</div><div>&nbsp;</div>";
} else {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;There might be a problem with your htaccess, please re-upload or correct it.</div><div>&nbsp;</div>";
}


$sef_url		= $config['website_url']."feed/most_recent.html";
$common_url		= $config['website_url']."feed.php?fid=most_recent";

$content_sef	= REST_Request($sef_url);
$content_common = REST_Request($common_url);

if ( strlen($content_sef) == strlen($content_common) ) {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />.htaccess works.</div><div>&nbsp;</div>";
} else {
	echo "<div style=\"color:red;\"><img src=\"images/ico-warning.gif\" />&nbsp;.htaccess does not work.</div><div>&nbsp;</div>";
}

if ( $is_mbstring_enabled ) {
	echo "<div style=\"color:green;\"><img src=\"images/ico-done.gif\" />&nbsp;'<a href=\"http://www.php.net/manual/en/intro.mbstring.php\" target=\"_blank\" title=\"mbstring\">mbstring</a>' PHP module is enabled on the server.</div><div>&nbsp;</div>";
} else {
	echo "<div style=\"color:black;\"><img src=\"images/ico-warning.gif\" />&nbsp;'<a href=\"http://www.php.net/manual/en/intro.mbstring.php\" target=\"_blank\" title=\"mbstring\">mbstring</a>' PHP module is not enabled on your server. Please contact your webhost.</div><div>&nbsp;</div>";
}


if( is_file( $debug_file ) )
{
	echo "<div style='color:green;'><img src='images/ico-done.gif' />Debug Log exists. <a href='?action=view_debug_log'>View</a></div><div>&nbsp;</div>";
}



if ( $ctr_cache > 1000 ) { 
?>
<div id="block_cache_log">
Cache XML Folder has more than 1000 files. Please click <a href="<?php echo $config['website_url'].'remove_xml_cache.php';?>" target="_blank">here</a> to remove files.
</div>
<?php
}
?>