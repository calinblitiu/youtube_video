<?php
$is_frontend = true;
include_once "init.php";
require_once("includes/class/VideosUploadLog.inc.php");
require_once("includes/class/geoip.inc");
include "admin/inc/aconfig.php";

$ip_addr		= $_SERVER['REMOTE_ADDR'];
$geoip	 		= geoip_open(dirname(__FILE__)."/lib/geoip/GeoIP.dat",GEOIP_STANDARD);
$country_code	= geoip_country_code_by_addr($geoip, $_SERVER['REMOTE_ADDR']);
$status		= isset($_GET['status'] ) ? $_GET['status'] : '';
$id			= isset($_GET['id']) ? $_GET['id'] : '';

$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';

$main_menu	= main_menu($feed_id);

$video_url	= '';
if ( $status == 200 ) {

	$video_data    = YT_GetDetail($_GET['id']);
	
	$VideosUploadLogObject	= new VideosUploadLog(0);
	$VideosUploadLogObject->title			= $video_data['title'];
	$VideosUploadLogObject->description		= $video_data['description'];
	$VideosUploadLogObject->category		= $video_data['category'];
	$tags									= isset($video_data['tags']) ? $video_data['tags'] : array();
	$VideosUploadLogObject->keywords		= implode(",", $tags);
	$VideosUploadLogObject->date_upload		= time();
	$VideosUploadLogObject->country_code	= $country_code;
	$VideosUploadLogObject->ip_addr			= $ip_addr;
	$VideosUploadLogObject->youtube_id		= $_GET['id'];
	$VideosUploadLogObject->save();
	
	if ( substr($config["website_url"], -1, 1) == '/' ) { 
		$video_url	= "<a href=\"{$config["website_url"]}video/{$id}/watch.html\">Watch</a>";
	} else {
		$video_url	= "<a href=\"{$config["website_url"]}/video/{$id}/watch.html\">Watch</a>";
	}
	$upload_video_response	= '<p class="msg done">'.lang('upload_video_success').'</p>';
} else {
	$upload_video_response	= '<p class="msg error">'.lang('upload_video_error').'</p>';
}

$tpl->assign('video_upload_enabled',$config["video_upload_enabled"]);
$tpl->assign('video_url', $video_url);

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

$tpl->assign('lang_upload_video_response', $upload_video_response );
$tpl->assign('lang_display', lang('display', 'Display') );
$tpl->assign('lang_list_view', lang('list_view', 'List View') );
$tpl->assign('lang_grid_view', lang('grid_view', 'Grid View') );
$tpl->assign('lang_view_count', lang('view_count', 'View Count') );
$tpl->assign('lang_relevance', lang('relevance', 'Relevance') );
$tpl->assign('lang_updated', lang('updated', 'Updated') );
$tpl->assign('lang_rating', lang('rating', 'Rating') );
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

$tpl->assign('lang_no_video_available', lang('no_video_available', 'There is no video available') );
$tpl->assign('lang_no_related_videos', lang('no_related_videos', 'There is no related videos') );
$tpl->assign('lang_views', lang('views', 'views') );
$tpl->assign('lang_Views', ucfirst(lang('views', 'views')) );
$tpl->assign('lang_length', lang('length', 'Length') );
$tpl->assign('lang_tags', lang('tags', 'Tags') );

$tpl->assign('video_upload_notice', '');

if ( $aConfig['ADMIN_DEMO'] ) {
	$sqlQuery	= "SELECT * FROM `".DB_PREFIX."categories` WHERE `c_user_videos` = 'prismov3'";
	$sqlResult	= dbQuery($sqlQuery);
	if ( mysql_num_rows($sqlResult) > 0 ) {
	$prismov3_category	= mysql_fetch_array($sqlResult);
	$prismov3_category_name	= stripslashes($prismov3_category['c_name']);
	$tpl->assign('video_upload_notice', '<b>Notice for Prismotube Demo user:</b><br />
Your uploaded videos will automatically appear on the video listing for the category \'<a href="categories/'.SeoKeywordEncode($prismov3_category_name).'/'.$prismov3_category["id"].'/page1.html">'.$prismov3_category_name.'</a>\'<br />
It will take about 24 hours due to the caching process. Please check back in 24 hours !');
	}
}

$tpl->display('upload_yt_response.html');

include "footer.php";
?>