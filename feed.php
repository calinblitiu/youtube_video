<?php
$is_frontend = true;
include_once "init.php";
require_once("includes/class/Videos.inc.php");
$config['list_per_page'] = $config['list_on_feed_page'];

// ################# Start recognize SEO Url data #############################

$seo_title = removeDash($_GET['fid']);
$seo_title = ucwords($seo_title);

if($_GET['fid'] == "most_recent" || $_GET['fid'] == "recently_featured"){
	$time = "";
}else if (empty($_GET['time'])) {
  $time = "today";
}else{
  $time = $_GET['time'];
}

if (empty($_GET['p'])) {

  $_GET['p'] = 1;

}

$videos	= array();
$total_videos = 0;
$keyword	= "";


// ################# Start getting Youtube GData ##############################
if ( $_GET['fid'] == 'most_linked' || $_GET['fid'] == 'recently_featured' ) { 
	$data_videos 	= YT_ListByFeed($_GET['fid'], $time);
	$total_videos	= YT_Total_Videos($data_videos);
	$videos			= YT_Videos($data_videos);
} else if ( $_GET['fid'] == 'top_rated' || $_GET['fid'] == 'most_viewed' || $_GET['fid'] == 'most_recent' ) {
	if ( isset($config['website_main_keyword']) && $config['website_main_keyword'] != '' ) {
		$keyword = $config['website_main_keyword'];
	}
	
	if ( $_GET['fid'] == 'top_rated' ) {
		$data_videos 	= YT_ListByKeyword($keyword, $_GET['p'], 'rating', false, $time);
	} else if ( $_GET['fid'] == 'most_viewed' ) {
		$data_videos 	= YT_ListByKeyword($keyword, $_GET['p'], 'viewCount', false, $time);
	} else if ( $_GET['fid'] == 'most_recent' ) {
		$data_videos 	= YT_ListByKeyword($keyword, $_GET['p'], 'published', false, $time);
	}
	$total_videos	= YT_Total_Videos($data_videos);
	$videos			= YT_Videos($data_videos);
	var_dump($videos);
	
} else {
	$VideoLists	= Videos::FindVideoList($_GET['fid'], $config['list_per_page'], $time);
	var_dump($VideoLists);
}

// ################# Start parse xml attributes manually ######################
if((($_GET['p']) * $config['list_per_page']) < $total_videos) {

  $next_page = $_GET['p'] + 1;

}

if($_GET['p']>1) {

  $prev_page = $_GET['p'] - 1;

}

// ################# Start construct video data ###############################
if ( $_GET['fid'] != 'most_linked' && $_GET['fid'] != 'recently_featured' && $_GET['fid'] != 'top_rated' && $_GET['fid'] != 'most_viewed' && $_GET['fid'] != 'most_recent' ) { 
	$videos = array();
	foreach( $VideoLists as $k => $VideoObject ) {
		$videos[$k]['id'] 		= $VideoObject->video_id;
		$videos[$k]['author']	= $VideoObject->author;
		$videos[$k]['description']	= $VideoObject->description;
		$videos[$k]['title']	= $VideoObject->title;
		$videos[$k]['category']	= $VideoObject->category;
		$videos[$k]['short_author']	= str_truncate($VideoObject->author, 12);
		$videos[$k]['short_title']	= str_truncate($VideoObject->title, 12);
		
		$videos[$k]['minute_format']	= setSecondsToMinute($VideoObject->duration);
		$videos[$k]['view_count']	= $VideoObject->view_count;
		$videos[$k]['rating_avg']	= floor ( ( $VideoObject->rating /  5 ) * 100 );
		if ( substr($config["website_url"], -1, 1) == '/' ) { 
			$videos[$k][author_url]	  = "profile/".$VideoObject->author;
		} else {
			$videos[$k][author_url]	  = "/profile/".$VideoObject->author;
		}
		
		$vid_data	= array();
		$vid_data['title']	= $videos[$k]['title'];
		$vid_data['description']	= $videos[$k]['description'];
		
		$is_filtered		= is_yt_thumbnail_filtered($vid_data);
		
		$videos[$k]['thumbnail'] = YT_Video_Thumbnails($VideoObject->video_id, $is_filtered);
		$videos[$k]['is_filtered'] = ( $is_filtered ) ? 1 : 0;
		
		$keywords_raw = explode(',', $VideoObject->keywords);
		$i=0;
		foreach($keywords_raw as $k2 => $v2) {
		  $videos[$k]['keywords'][$i] = trim($v2);
		  $i++;
		}  
	}

}
	
// ################# Start assign template variables ##########################
$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';
$main_menu	= main_menu($feed_id);

$menu_title	= $seo_title;
		
if ( $menu_title == 'Top Rated' ) {
	$menu_title	= lang('top_rated');
} else if ( $menu_title == 'Top Favorites' ) {
	$menu_title	= lang('top_favorites');
} else if ( $menu_title == 'Most Viewed' ) {
	$menu_title	= lang('most_viewed');
} else if ( $menu_title == 'Most Recent' ) {
	$menu_title	= lang('most_recent');
} else if ( $menu_title == 'Most Discussed' ) {
	$menu_title	= lang('most_discussed');
} else if ( $menu_title == 'Most Linked' ) {
	$menu_title	= lang('most_linked');
} else if ( $menu_title == 'Most Responded' ) {
	$menu_title	= lang('most_responded');
} else if ( $menu_title == 'Recently Featured' ) {
	$menu_title	= lang('recently_featured');
} 

var_dump($videos);

$seo_title				= $menu_title;

//$tpl->assign('total',ceil($total_videos / $config['list_per_page']));
$tpl->assign('total', 0);
$tpl->assign('videos',$videos);
$tpl->assign('feed_title',$seo_title);
//$tpl->assign('keyword',$seo_title);
$tpl->assign('feed_id',$_GET['fid']);
$tpl->assign('time',$time);
$tpl->assign('list_mode',$config["search_videos_view_mode"]);
$tpl->assign('video_upload_enabled',$config["video_upload_enabled"]);
$tpl->assign('main_menu', $main_menu );
/*$tpl->assign('curr_page',$_GET['p']);
$tpl->assign('next_page',$next_page);
$tpl->assign('prev_page',$prev_page);*/

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
$tpl->assign('lang_page', lang('page', 'Page') );
$tpl->assign('lang_of', lang('of', 'of') );
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
$tpl->assign('lang_view_all', lang('view_all'));

$tpl->display('feed.html');
?>