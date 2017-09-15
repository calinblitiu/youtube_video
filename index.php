<?php
$is_frontend = true;
include_once "init.php";
include_once("config/admin_login.php");

remove_xml_cache();

$config['list_per_page'] = $config['list_on_home_page'];

// ################# Start recognize SEO Url data #############################
preg_match_all("|page([0-9]*).html|U", $seo_title, $out, PREG_PATTERN_ORDER);

$_GET['p'] = $out[1][0];



if (empty($_GET['p'])) {

  $_GET['p'] = 1;

}



if ($_GET['p']>1) {

  $seo_title = array_pop($p_url);

}

$videos			= array();
$category_desc	= "";
if ( !isset($config['default_category_id']) || $config['default_category_id'] == 0 || trim($config['default_category_id']) == '' ) {

	$randID	= get_random_category_id();

} else {
	$randID	= $config['default_category_id'];
}

if (empty($_GET['orderby'])) {
  $orderby = $config['sort_videos_by'];
}else{
  $orderby = $_GET['orderby'];
}


// ################# Start getting Youtube GData ##############################
$CategoryObject = new Category($randID);
$CategoryObject->load();
$category_desc	= stripslashes($CategoryObject->c_desc);
$randKeyword	= $CategoryObject->c_keyword;

if ( empty($randID) ) {
	$CategoryObject->c_listing_source = "keyword";
	$CategoryObject->c_keyword = "Youtube Celebrities";
	$CategoryObject->c_name = "Youtube Celebrities";
	$randKeyword	= "Youtube Celebrities";
	$keyword		= "Youtube Celebrities";
}

if ( !is_keyword_allowable($randKeyword) ) {
	$retry	= 0;


	do {
		$retry++;

		$subject	= "Категория под названием '".$randKeyword."' has triggered the keyword filter.";
		$message	= "Категория под названием '".$randKeyword."' has triggered the keyword filter.\r\n
Please either modify the category title so that it doesn't trigger the filter or turn off the keyword filter.\r\n
Otherwise, visitors who visit this category page will always get redirected away from that page.";
		send_mail_notification($config['admin_email'], $randID, "keyword filtered", $message, $subject);

		$CategoriesObject			= new categories;
		$data_cache_files			= $CategoriesObject->categories_cache_files();
		$cache_category_ids_file	= $data_cache_files['category_ids'];
		if ( file_exists($cache_category_ids_file) ) {
			$time_cached	= time() - filemtime($cache_category_ids_file);
			if ( $time_cached > 86400 ) {
				create_category_ids_cache($cache_category_ids_file);
			}
		} else {
			create_category_ids_cache($cache_category_ids_file);
		}
		if ( file_exists($cache_category_ids_file) ) {
			include_once($cache_category_ids_file);
		}

		if ( is_array($cache_category_ids) && count($cache_category_ids) > 0 ) {
			$category_ids_idx	= rand(0, count($cache_category_ids) - 1);
			$randID	= $cache_category_ids[$category_ids_idx];
		} else {
			break;
		}

		$CategoryObject = new Category($randID);
		$CategoryObject->load();
		$category_desc	= stripslashes($CategoryObject->c_desc);
		$randKeyword	= $CategoryObject->c_keyword;
	} while(!is_keyword_allowable($randKeyword) && $retry < $config['max_kwfilter_redir']);

	if ( $retry == $config['max_kwfilter_redir'] || (is_array($cache_category_ids) && count($cache_category_ids) == 0)) {
		$CategoryObject->c_listing_source = "keyword";
		$CategoryObject->c_keyword = "Youtube Celebrities";
		$CategoryObject->c_name = "Youtube Celebrities";
		$randKeyword	= "Youtube Celebrities";
		$keyword		= "Youtube Celebrities";
		$randID			= 0;
		$category_name	= $CategoryObject->c_name;
	}
}

$data_videos_list	= get_videos_by_category($CategoryObject, $_GET['p'], $orderby);
$keyword			= $data_videos_list['keyword'];
$category_name		= $data_videos_list['category_name'];
$data_videos		= $data_videos_list['data_videos'];
$total_videos		= $data_videos_list['total_videos'];
$videos				= $data_videos_list['videos'];

$first_video_id	= isset($videos[0]['id']) ? $videos[0]['id'] : '';
// ################# Start parse xml attributes manually ######################

$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';

$main_menu	= main_menu($feed_id);
// ################# Start calculate pagination ###############################


if((($_GET['p']+1) * $config['list_per_page']) < $total_videos) {
	$next_page = $_GET['p'] + 1;
}

if($_GET['p']>1) {
	$prev_page = $_GET['p'] - 1;
}


// ################# Start assign template variables ##########################

if($config["enable_home_player"]) {
	if($config['longtail_enabled']) {
		//$player_longtail_param = '&ltas.cc='.$config["longtail_channel"].'&plugins=ltas,clickproxy&clickproxy.listener=get_video_url&icons=false';
		$player_longtail_param = '&plugins=ltas&ltas.cc='.$config["longtail_channel"].'&icons=false';
		$player_autostart = "true";

	}else{
		//$player_longtail_param = '&plugins=clickproxy&clickproxy.listener=get_video_url&icons=false';
		$player_longtail_param = '&icons=false';
		$player_autostart = "true";
	}

	if($config['cplayer_skin'] == "default") {
		$player_skin = "";
	} else {
		if ( file_exists(BASE_PATH."player".DS."skins".DS.$config["cplayer_skin"]."/".$config["cplayer_skin"].".swf") ) {
			$player_skin = "&skin=".$config["website_url"]."player/skins/".$config["cplayer_skin"]."/".$config["cplayer_skin"].".swf";
		} else {
			$player_skin = "&skin=".$config["website_url"]."player/skins/".$config["cplayer_skin"]."/".$config["cplayer_skin"].".zip";
		}
	}

	$player_file = urlencode($config["website_url"].'xml.playlist.php?tag='.stripslashes($randKeyword).'&randID='.$randID);
	$player_colors = '&backcolor='. $config["player_backcolor"]. '&frontcolor='. $config["player_frontcolor"]. '&lightcolor='. $config["player_lightcolor"]. '&screencolor='. $config["player_screencolor"];
}

$lang_array = array('bg', 'cs', 'es', 'it', 'ro', 'ru', 'sk', 'sq', 'uk');
if(in_array($config['web_default_language'], $lang_array))
{
	$def_language = true;
}
else
{
	$def_language = false;
}
$tpl->assign('def_language', $def_language);

$tpl->assign('catid', $randID);
$tpl->assign('cid', $randID);
$tpl->assign('enable_home_player',$config["enable_home_player"]);

if($config["enable_home_player"]) {
	$tpl->assign('player_file',$player_file);
	$tpl->assign('player_skin',$player_skin);
	$tpl->assign('player_custom_plugins_home',$config["player_custom_plugins_home"]);
	$tpl->assign('enable_player_colors',$config["enable_player_colors"]);
	$tpl->assign('player_colors',$player_colors);
	$tpl->assign('player_longtail_param',$player_longtail_param);
	$tpl->assign('player_autostart',$player_autostart);
}
$tpl->assign('total',ceil($total_videos / $config['list_per_page']));
$tpl->assign('videos',$videos);
$tpl->assign('first_video_id',$first_video_id);
$tpl->assign('keyword', prismo_print($keyword));
$tpl->assign('category_name', prismo_print($category_name));
$tpl->assign('next_page',$next_page);
$tpl->assign('prev_page',$prev_page);
$tpl->assign('curr_page',$_GET['p']);
$tpl->assign('orderby',$orderby);
$tpl->assign('list_mode',$config["home_videos_view_mode"]);
$tpl->assign('video_upload_enabled',$config["video_upload_enabled"]);
$tpl->assign('main_menu', $main_menu );

$tpl->assign('lang_logout', lang('logout', 'Logout') );
$tpl->assign('lang_admin_cp', lang('admin_cp', 'Admin CP') );
$tpl->assign('lang_add_to_favorites', lang('add_to_favorites', 'Add To Favorites') );

$tpl->assign('lang_online_watchers', lang('online_watchers', 'Online Watchers') );
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

$tpl->assign('lang_search_for_videos', lang('search_for_videos', 'Search for videos...') );
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
$tpl->assign('lang_view_all', lang('view_all'));
$tpl->assign('lang_views', lang('views', 'views') );
$tpl->assign('lang_Views', ucfirst(lang('views', 'views')) );
$tpl->assign('lang_length', lang('length', 'Length') );
$tpl->assign('lang_tags', lang('tags', 'Tags') );
$tpl->assign('randID', $randID );

$tpl->assign('category_desc', nl2br($category_desc));


header('Content-Type: text/html; charset=utf-8');
$tpl->display('home.html');

$tpl->assign('ctg_id', $randID);
include "footer.php";
?>