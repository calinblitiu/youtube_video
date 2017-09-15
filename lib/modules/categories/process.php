<?php
	include_once("../../../init.php");
	@session_start();
	//require_once("../../../config/config.php");
	$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
	if ( !$is_logged ){
		die();
	}

	@define("DS", DIRECTORY_SEPARATOR);
	$root_path	= str_replace(DS."lib".DS."modules".DS."categories", "",  dirname(__FILE__));
	if ( !defined('BASE_PATH') ) {
		define("BASE_PATH", $root_path.DS);
	}

	$author		= isset($_GET['author']) ? $_GET['author'] : '';
	$author		= isset($_POST['author']) ? $_POST['author'] : $author;

	$playlist_id= isset($_GET['playlist_id']) ? $_GET['playlist_id'] : '';
	$playlist_id= isset($_POST['playlist_id']) ? $_POST['playlist_id'] : $playlist_id;
	$option_str	= "<option value=\"0\">Пожалуйста, выберите Ваш плейлист</option>";

	if ( $author != '' ) {
		$data_videos	= YT_GetUserPlaylists($author);
		$videos			= YT_Videos($data_videos);

		foreach($videos as $video) {
			$playid		= $video['id'];
			$selected	= "";

			if ($playid == $playlist_id) {
			$selected	= " selected";
			}
			$option_str .= "<option value=\"{$playid}\"{$selected}>{$video['title']}</option>";
		}
	}
	echo $option_str;
?>