<?php
@session_start();

include_once("../config/db_config.php");
include('inc/includes.php');
include_once("../config/config_filter.php");
include_once("../config/config.php");
include_once("../lib/services.php");

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	include('inc/auth.php');
	die();
}

$id 			= isset($_GET['id']) ? trim($_GET['id']) : 0;
$id				= (int) $id;
$page_status	= 1;
$hasError		= false;
$errors			= array();

if ( $id != 0 ) {
	$sqlQuery	= "SELECT `page_title`, `page_sef_url`, `page_status`, `page_content` FROM `".DB_PREFIX."pages` WHERE `page_id` = {$id}";
	$sqlResult	= dbQuery($sqlQuery);

	list($page_title, $page_url, $page_status, $page_content) = mysql_fetch_row($sqlResult);
	$page_title	= stripslashes($page_title);
	$page_url	= stripslashes($page_url);
	$page_content	= stripslashes($page_content);
}

if ( isset($_POST['Submit']) ) {
	$page_title		= isset($_POST['page_title']) ? trim($_POST['page_title']) : '';
	$page_status	= isset($_POST['page_status']) ? trim($_POST['page_status']) : 0;
	$page_content	= isset($_POST['page_content']) ? trim($_POST['page_content']) : '';

	if ( $page_title == '' ) {
		$hasError	= true;
		$errors[]	= 'Please specify page title.';
	}

	if ( $page_content == '' ) {
		$hasError	= true;
		$errors[]	= 'Please specify page content.';
	}

	if ( get_magic_quotes_gpc() ) {
		$page_title		= stripslashes($page_title);
		$page_content	= stripslashes($page_content);
	}

	if ( !$hasError ) {
		if( !isDemo() ) {
			if ( $id == 0 ) {
				$sqlQuery	= "INSERT INTO `".DB_PREFIX."pages` SET `page_title` = '".db_escape_string($page_title)."', `page_status` = {$page_status},
					`page_content` = '".db_escape_string($page_content)."', `time` = ".time();
			} else {
				$sqlQuery	= "UPDATE `".DB_PREFIX."pages` SET `page_title` = '".db_escape_string($page_title)."', `page_status` = {$page_status},
					`page_content` = '".db_escape_string($page_content)."' WHERE `page_id` = {$id}";
			}

			dbQuery($sqlQuery);

			if ( $id == 0 ) {
				$new_id	= mysql_insert_id();
				$page_sef_title	= $page_title;
				if ( $is_mbstring_enabled ) {
					$page_sef_title	= mb_strtolower($page_title);
				}
				$page_sef_title	= SeoTitleEncode($page_title);
				$sqlQuery	= "SELECT * FROM `".DB_PREFIX."pages` WHERE `page_sef_url` LIKE '%".db_escape_string($page_sef_title)."%'";
				$sqlResult	= dbQuery($sqlQuery);

				$num_rows	= mysql_num_rows($sqlResult);
				if ( $num_rows > 0 ) {
					$page_sef_title	= $page_sef_title."-".($num_rows+1);
				}

				$sqlQuery	= "UPDATE `".DB_PREFIX."pages` SET `page_sef_url` = '".db_escape_string($page_sef_title)."' WHERE `page_id` = {$new_id}";
				dbQuery($sqlQuery);


			}
		}

		echo '<script type="text/javascript">window.opener.location.href = window.opener.location.href;window.close();</script>';
		exit(0);

	}

}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="<?=$config["website_url"]?>favicon.ico?8458" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?=$config["website_name"]?> - Administration</title>
<link rel="stylesheet" href="css/style.css?495400540" TYPE="text/css" MEDIA="screen">

<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>




</head>
<body class="mainBody">
<form action="" method="post" name="config" id="config" enctype="multipart/form-data">
<div class="wrap">

		<?php if ( count($errors) > 0 ) { ?>
			<div class="error">
			<?php echo implode('<br />', $errors);?>
			</div>
		<?php } ?>

		<fieldset>
			<?php if ( $id == 0 ) { ?>
				<legend> Add New Page </legend>
			<?php } else { ?>
				<legend> Edit Page </legend>
			<?php } ?>

			<?php if ( $id != 0 ) { ?>
			<p>
				<div class="settingTitle">Page URL</div>
				<?php echo "page/".$page_url.".html"?>
			</p>
			<?php } ?>

			<p>
				<div class="settingTitle">Page Title</div>
				<input class="input" name="page_title" type="text" id="page_title" value="<?=$page_title?>">
			</p>

			<p>
				<div class="settingTitle">Page Status</div>
				<select class="select" name="page_status" id="page_status">
					<option <? echo ($page_status == 1) ? "selected" : ""; ?> value="1">Active</option>
					<option <? echo ($page_status == 0) ? "selected" : ""; ?> value="0">Inactive</option>
				</select>
			</p>

			<p>
				<div class="settingTitle">Page Content</div>
				<div style="clear:both;"></div>
			</p>

			<p>
				<textarea name="page_content" class="mceEditor" style="width:650px;height:450px;"><?php echo $page_content?></textarea>
			</p>

			<p id="saveConfig">
				<input type="submit" name="Submit" value="Save" style="padding:5px;" />
			</p>

		</fieldset>

</div>
</form>
<script type="text/javascript">

tinyMCE.init({

	// General options

	mode : "specific_textareas",
	editor_selector : "mceEditor",

	theme : "advanced",

	plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",



	// Theme options

	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",

	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",

	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",

	theme_advanced_toolbar_location : "top",

	theme_advanced_toolbar_align : "left",

	theme_advanced_statusbar_location : "bottom",

	theme_advanced_resizing : true,




	// Example content CSS (should be your site CSS)

	content_css : "css/example.css",



	// Drop lists for link/image/media/template dialogs

	template_external_list_url : "js/template_list.js",

	external_link_list_url : "js/link_list.js",

	external_image_list_url : "js/image_list.js",

	media_external_list_url : "js/media_list.js",



	// Replace values for the template plugin

	template_replace_values : {

		username : "Some User",

		staffid : "991234"

	}

});

</script>
<div class="footer_wrap">
<div class="footer">
	<div class="copyright">
		Copyright &copy; 2008 - <?=date("Y")?> <a href="<?=$config["website_url"]?>"><?=$config["website_name"]?></a> All Rights Reserved
	</div>
	<div class="poweredby">
	</div>
</div>