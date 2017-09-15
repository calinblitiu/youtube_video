<?php
	@session_start();
	require_once("../../../config/config.php");
	$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
	if ( !$is_logged ){
		die();
	}

	@define("DS", DIRECTORY_SEPARATOR);

	require_once("../../../config/db_config.php");
	require_once("../../../lib/db.php");
	include ('categories.class.php');
	include ('functions.php');
	include ('module.php');
	require_once("../../../lib/functions.php");

	$ctg_id		= isset($_GET['ctg_id']) ? $_GET['ctg_id'] : 0;
	$ctg_id		= isset($_POST['ctg_id']) ? $_POST['ctg_id'] : $ctg_id;

	$task		= isset($_GET['task']) ? $_GET['task'] : '';
	$task		= isset($_POST['task']) ? $_POST['task'] : $task;

	$default_category_id		= isset($_GET['default_category_id']) ? $_GET['default_category_id'] : 0;
	$default_category_id		= isset($_POST['default_category_id']) ? $_POST['default_category_id'] : $default_category_id;

	$cat_parent	= isset($_GET['cat_parent']) ? $_GET['cat_parent'] : '';
	$cat_parent	= isset($_POST['cat_parent']) ? $_POST['cat_parent'] : $cat_parent;

	$default	= isset($_GET['default']) ? $_GET['default'] : 0;
	$default	= isset($_POST['default']) ? $_POST['default'] : $default;

	$categories = new categories;
	$categories->name_prefix = "&nbsp;&nbsp;";


	if ( $task == 'menu' ) {

		$categories->HtmlTree = array(

			"header" => "<div id=\"table_header\"><table width=180px border=0 cellpadding=2 cellspacing=2>",

			"BodyUnselected" => '<tr><td> [prefix] &raquo; <a href="?act=updateForm&id=[id]" id="cat_unselected">[name]</a></td></tr>',

			"BodySelected" => '<tr><td id="table_bg"> [prefix] &raquo; <a href="?act=updateForm&id=[id]" id="cat_selected">[name]</a></td></tr>',

			"footer" => '</table></div>',

		);



		$catMenu = $categories->html_output($ctg_id, 0);

		echo $catMenu;

	} else if ( $task == 'dropdown' ) {

		$option_str				= '';

		$data_all_categories	= $categories->get_categories();

		if ( $default ) {
			$option_str	= '<option value="0">Нет рубрики по умолчанию</option>';
		}

		$option_str		.= $categories->display_categories($data_all_categories, $default_category_id, $default);

		if ( sizeof($data_all_categories) == 0 ) {
			echo "Нет же рубрики";
		}

		echo $option_str;

	} else if ( $task == 'dropdown2' ) {

		$cat = $categories->fetch($ctg_id);

		$option_str	= '<option value="0" selected>ROOT</option>';

		$data_all_categories	= $categories->get_categories();

		$catids = explode(">", $cat['position']);

		$key              = count($catids)-3;

		if($key < 0) $key = 0;

		$cat_parent = $catids[$key];

		$option_str		.= $categories->display_categories($data_all_categories, $cat_parent, $ctg_id);

		echo $option_str;

	}

?>