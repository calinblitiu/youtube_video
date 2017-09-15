<?
@session_start();
@set_time_limit(0);
include('../../config/db_config.php');
include('../../config/admin_login.php');
include_once('../../lib/db.php');
include('../../config/license_key.php');
include('../../config/config.php');

include('../inc/version.php');
include('../inc/functions.php');
include('../../lib/modules.php');

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	die();
}

$folder	= "../../".$config['xml_cache_dir'];

if ( is_writable($folder) ) {

	deleteFiles($folder);
	
	echo '<div class="configSaved">Cache XML files has been deleted.</div>';
	exit(0);
	
} else {
	echo '<div class="error">Cache XML folder is not writable.</div>';
	exit(0);
}