<?
error_reporting(0);
include('inc/aconfig.php');

include('../config/db_config.php');
include_once('../lib/db.php');
include('../config/license_key.php');
include('../config/config.php');
include('../templates/'.$config["active_template"].'/player/colors.php');
include('../config/admin_login.php');

include('inc/version.php');
include('inc/functions.php');
include_once('../lib/modules.php');

?>