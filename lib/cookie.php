<?php

// set filter
if (isset($_GET['filter'])) {
	$filter = $_GET['filter'];
	setcookie('filter',$filter,0,'');
} else if (isset($_COOKIE['filter'])) {
	$filter = $_COOKIE['filter'];	if ( $filter != 'on' && $filter != 'off' ) {		$filter = $config['default_filter_value'];	}
} else {
	$filter = $config['default_filter_value'];
	setcookie('filter',$filter,0,'');
}
$_COOKIE['filter']=$filter;
if ($_COOKIE['filter'] == "off") {
	$filterValue = "off";
	$filterStatus = "Search Filter OFF"; 
	$filterStatusButton = lang('filter_off');
	$filterStatusBackground = "#D93636";
}else{
	$filterValue = "on";
	$filterStatus = "Search Filter ON";
	$filterStatusButton = lang('filter_on');
	$filterStatusBackground = "green";
}

// set ctemplate
$containFiles = containFiles(BASE_PATH . "templates_c/");
if (isset($_GET['tpl'])) {
		deleteFiles(BASE_PATH . "templates_c/");
		$ctemplate = $_GET['tpl'];
		setcookie('ctemplate',$ctemplate,0,'');
} else if (isset($_COOKIE['ctemplate']) && $containFiles) {
		$ctemplate = $_COOKIE['ctemplate'];
} else {
		$ctemplate = $config["active_template"];
		setcookie('ctemplate',$ctemplate,0,'');
}

if (isset($_GET['theme'])) {
		deleteFiles(BASE_PATH . "templates_c/");
		$ctheme = $_GET['theme'];
		setcookie('ctheme',$ctheme,0,'');
} else if (isset($_COOKIE['ctheme']) && $containFiles) {
		$ctheme = $_COOKIE['ctheme'];
} else {
		$ctheme = $config["active_theme"];
		setcookie('ctheme',$ctheme,0,'');
}

if ( !isset($_COOKIE["list_mode"]) ) {
	$view_setting = $config["view_setting"];
	setcookie('list_mode',$view_setting,0,'');
	$_COOKIE['list_mode']=$view_setting;
	
}

$_COOKIE['ctemplate']=$ctemplate;
$_COOKIE['ctheme']=$ctheme;
$config["active_template"] = $ctemplate;
$config["active_theme"] = $ctheme;



?>