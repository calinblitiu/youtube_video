<?php
	define("IS_MOBILE", true);
	require_once("../init.php");
	//require_once(dirname(__FILE__)."../includes/class/Category.inc.php");
	
	block_googlebots_mobile();
	
	$randID		= isset($_GET['catid']) ? $_GET['catid'] : 0;
	$lang_views = lang('views', 'views');
	$lang_in	= lang('in');
	$lang_prev	= lang('prev');
	$lang_next	= lang('next');
	$lang_browse_categories	= lang('browse_categories');
	$title		= $lang_browse_categories;
	$lang_categories	= lang('categories');
	
	$categories = new categories;
	$categories->name_prefix = "&nbsp;&nbsp;";
	$categories_list = $categories->get_root_categories(0);
	
	if ( count($categories_list) > 0 ) {
		$output	= '<ul>';
		foreach( $categories_list as $c ) {		
			$output	.= '<li><a href="index.php?catid='.$c['id'].'" title="'.prismo_print($c["c_name_raw"]).'">'.prismo_print($c["c_name_raw"]).'</a></li>';
		}
		$output	.= '</ul>';
	} else {
		$output = lang('nothing_found');
	}
	
	
	
	include("templates/default/categories.php");
?>