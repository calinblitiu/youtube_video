<?php
// рус
$is_frontend = true;
include_once "init.php";
include_once("lib/xml.php");
include_once("includes/class/Category.inc.php");

remove_xml_cache();
// ################# Start recognize SEO Url data #############################

$category_name			= "";
$yt_response_message	= "";

$p_url     = explode('/',$_SERVER['REQUEST_URI']);

$seo_title = array_pop($p_url);



preg_match_all("|page([0-9]*).html|U", $seo_title, $out, PREG_PATTERN_ORDER);

$_GET['p'] = $out[1][0];



if (empty($_GET['p'])) {

  $_GET['p'] = 1;

}



if ($_GET['p']>0) {

  $seo_title = array_pop($p_url);

}



if(empty($seo_title)) {

  $seo_title = array_pop($p_url);

}



$seo_title = urldecode($seo_title);




$i=0;

$stop = false;

while($i<count($keyword_list)) {

  if($seo_title == SeoTitleEncode($keyword_list[$i])) {

    $keyword = $keyword_list[$i];

  }

  $i++;

}





if (empty($_GET['q'])) {

  $keyword = $seo_title;

}

if (empty($_GET['orderby'])) {
  $orderby = $config["sort_videos_by"];
}else{
  $orderby = $_GET['orderby'];
}

// ################# Start getting Youtube GData ##############################

$cat_id = (int) $_GET['id'];
$category_desc	= "";

if ( $config['tags_enabled'] == 'false' ) {
	$new_seo_title	= strtolower($seo_title);
	if ( $cat_id == 0 && !isset($_GET['q']) ) {
		header("Location: ".$config['website_url']);
		exit(0);
	}
}

if ( $cat_id > 0 ){
	$CategoryObject = new Category($cat_id);

	if ( !$CategoryObject->load() ) {
		header("Location: ".$config['website_url']);
		exit(0);
	}

	$category_desc	= stripslashes($CategoryObject->c_desc);

	$data_videos_list	= get_videos_by_category($CategoryObject, $_GET['p'], $orderby);
	$keyword			= $data_videos_list['keyword'];
	$category_name		= $data_videos_list['category_name'];
	$data_videos		= $data_videos_list['data_videos'];
	$total_videos		= $data_videos_list['total_videos'];
	$videos				= $data_videos_list['videos'];


} else if ( isset($_GET['q']) && trim($_GET['q']) != '' ) {

	$q			= $_GET['q'];
	$keyword = str_replace("-"," ", urldecode($q));
	$keyword = strip_tags($keyword);

	if ( trim($keyword) != '' && ( !isset($config['video_search_enabled']) || $config['video_search_enabled'] == 'true' ) ) {

		if ( get_magic_quotes_gpc() ) {
			$keyword	= stripslashes($keyword);
		}

		$keyword	= htmLawed($keyword, array('safe'=>1));

		if ( $config["search_log_enabled"] == "true" && isset($_GET['filter']) ) {
			$ip_address		= $_SERVER['REMOTE_ADDR'];
			$keyword_log	= $keyword;

			$sqlQuery		= "INSERT INTO `".DB_PREFIX."search_log` SET `search_term` = '".db_escape_string($keyword_log)."', `ip_address` = '{$ip_address}', `time` = ".time();

			dbQuery($sqlQuery);
		}

		$data_videos 	= YT_ListByKeyword($keyword, $_GET['p'], $orderby, true);
		$total_videos	= YT_Total_Videos($data_videos);
		$videos			= YT_Videos($data_videos);
	} else {
		header("Location: ".$config['website_url']);
		exit(0);
	}

} else if ( $keyword != '' ) {
	$keyword = str_replace("-"," ", urldecode($keyword));
	$keyword = strip_tags($keyword);

	if ( trim($keyword) != '' ) {

		if ( get_magic_quotes_gpc() ) {
			$keyword	= stripslashes($keyword);
		}

		$keyword	= htmLawed($keyword, array('safe'=>1));

		if ( $config["search_log_enabled"] == "true" && isset($_GET['filter']) ) {
			$ip_address		= $_SERVER['REMOTE_ADDR'];
			$keyword_log	= $keyword;

			$sqlQuery		= "INSERT INTO `".DB_PREFIX."search_log` SET `search_term` = '".db_escape_string($keyword_log)."', `ip_address` = '{$ip_address}', `time` = ".time();

			dbQuery($sqlQuery);
		}

		$data_videos 	= YT_ListByKeyword($keyword, $_GET['p'], $orderby, true);
		$total_videos	= YT_Total_Videos($data_videos);
		$videos			= YT_Videos($data_videos);
	} else {
		header("Location: ".$config['website_url']);
		exit(0);
	}
}

if ( !is_keyword_allowable($keyword) ) {
	header("Location: ".$config['website_url']);
	exit(0);
}


// ################# Start parse xml attributes manually ######################

if($_GET['p'] == 1) {
	//stripTags($_GET['q']);
	stripTags($keyword);
}

// ################# Start calculate pagination ###############################



if((($_GET['p']) * $config['list_per_page']) < $total_videos) {

  $next_page = $_GET['p'] + 1;

}

if($_GET['p']>1) {

  $prev_page = $_GET['p'] - 1;

}


$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';
if ( isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '' ) {
	//$feed_id = 'relevance';
}

$main_menu	= main_menu($feed_id);

// ################# Start assign template variables ##########################

if ( !isset($CategoryObject) ) {
	$CategoryObject = new Category($cat_id);
	$CategoryObject->load();
}

if ( !isset($videos) || $total_videos <= 0 ) {
	include_once("config/admin_login.php");
	if ( isset($_GET['id']) && $config['empty_categories_notification_enabled'] == 'true'){
		$purpose	= "no video source";

		if ( !isset($CategoryObject) ) {
			$CategoryObject = new Category($cat_id);
			$CategoryObject->load();
		}

		if ( $CategoryObject->load() ) {
			$category_parent	= $CategoryObject->position;
			$cat_parents		= explode(">", $category_parent);
			$category_title_str	= stripslashes($CategoryObject->c_name);
			if ( count($cat_parents) > 1 ) {
				foreach($cat_parents as $cat_parent_id) {
					$cat_parent_id	= trim($cat_parent_id);
					if ($cat_parent_id != '' && $cat_parent_id != $cat_id) {
						$catParentObj	= new Category($cat_parent_id);
						$catParentObj->load();
						$category_title_str	.= " > ".stripslashes($catParentObj->c_name);
					}
				}
			}

			if ( isset($_SESSION['yt_response']) && trim($_SESSION['yt_response']) != '') {
				$subject	= "Youtube Response for {$category_title_str}";
				$message	= $_SESSION['yt_response']. " for this category:\r\n{$category_title_str}\r\nURL: {$request_url}\r\n----";
				unset($_SESSION['yt_response']);
			} else {
				$subject	= "Error retrieving videos source from Youtube for {$category_title_str}";
				$message	= "There was an error retrieving the source of videos for this category:\r\n{$category_title_str}\r\nURL: {$request_url}\r\n----";
			}


			$request_url= curPageURL();

			if ( !is_null($cat_id) ) {
			$send_mail = send_mail_notification($config['admin_email'], $cat_id, $purpose, $message, $subject);
			}
		}
	}
}


$tpl->assign('total',ceil($total_videos / $config['list_per_page']));
$tpl->assign('videos',$videos);

if ( isset($_GET['id']) ) {
	if ( get_magic_quotes_gpc() ) {
		$tpl->assign('keyword', stripslashes(prismo_print($keyword)) );
	} else {
		$tpl->assign('keyword', prismo_print($keyword));
	}
} else {
	$tpl->assign('keyword', prismo_print($keyword));
}

if ( trim($category_name) == '' ) {
	$category_name = $keyword;
}

$tpl->assign('category_name', prismo_print($category_name));
$tpl->assign('next_page',$next_page);
$tpl->assign('prev_page',$prev_page);
$tpl->assign('curr_page',$_GET['p']);
$tpl->assign('cid',$_GET['id']);
$tpl->assign('orderby',$orderby);
$tpl->assign('video_upload_enabled',$config["video_upload_enabled"]);
$tpl->assign('list_mode',$config["search_videos_view_mode"]);
$tpl->assign('main_menu', $main_menu );

$tpl->assign('lang_logout', lang('logout', 'Logout') );
$tpl->assign('lang_admin_cp', lang('admin_cp', 'Admin CP') );
$tpl->assign('lang_add_to_favorites', lang('add_to_favorites', 'Add To Favorites') );

$tpl->assign('lang_categories', lang('categories', 'Categories') );
$tpl->assign('lang_top_rated', lang('top_rated', 'Top Rated') );
$tpl->assign('lang_top_favorites', lang('top_favorites', 'Top Favorites') );
$tpl->assign('lang_most_viewed', lang('most_viewed', 'Most Viewed') );
$tpl->assign('lang_most_recent', lang('most_recent', 'Most Recent') );
$tpl->assign('lang_most_discussed', lang('most_discussed', 'Most Discussed') );
$tpl->assign('lang_most_linked', lang('most_linked', 'Most Linked') );
$tpl->assign('lang_most_responded', lang('most_responded', 'Most Responded') );
$tpl->assign('lang_recently_featured', lang('recently_featured', 'Recently Featured') );
$tpl->assign('lang_upload', lang('upload', 'Upload') );

$tpl->assign('lang_get_flash_player', lang('get_flash_player', 'Get the latest Flash Player') );
$tpl->assign('lang_to_see_video', lang('to_see_video', 'to see this video.') );
$tpl->assign('lang_all_rights_reserved', lang('all_rights_reserved', 'All Rights Reserved') );

$tpl->assign('lang_display', lang('display', 'Display') );
$tpl->assign('lang_list_view', lang('list_view', 'List View') );
$tpl->assign('lang_grid_view', lang('grid_view', 'Grid View') );

$tpl->assign('lang_order_by', lang('order_by', 'Order By') );
$tpl->assign('lang_time', lang('time', 'Time') );
$tpl->assign('lang_today', lang('today', 'Today') );
$tpl->assign('lang_this_week', lang('this_week', 'This Week') );
$tpl->assign('lang_this_month', lang('this_month', 'This Month') );
$tpl->assign('lang_all_time', lang('all_time', 'All Time') );
$tpl->assign('lang_video_tags', lang('video_tags', 'Video Tags') );
$tpl->assign('lang_links', lang('links', 'Links') );
$tpl->assign('lang_download_video', lang('download_video', 'Download Video') );
$tpl->assign('lang_video_details', lang('video_details', 'Video Details') );


$tpl->assign('lang_views', lang('views', 'views') );
$tpl->assign('lang_Views', ucfirst(lang('views', 'views')) );
$tpl->assign('lang_length', lang('length', 'Length') );
$tpl->assign('lang_tags', lang('tags', 'Tags') );
$tpl->assign('feed_id', $feed_id );
$tpl->assign('lang_view_all', lang('view_all'));

$tpl->assign('category_desc', nl2br($category_desc));

$tpl->display('list.html');

include "footer.php";

?>