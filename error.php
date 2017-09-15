<?php
$is_frontend = true;
include_once "init.php";
include_once("config/admin_login.php");

if ( is_spider() ) {
	exit(0);
}

remove_xml_cache();

$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';
$main_menu	= main_menu($feed_id); 

$title		= lang('videos_not_available');
if ( isset($_SESSION['yt_response']) ) {
	$title	= $_SESSION['yt_response'];
	unset($_SESSION['yt_response']);
}

$tpl->assign('title', $title );
$tpl->assign('title_inner', $title );

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
$tpl->assign('main_menu', $main_menu );


$tpl->display('error.html');

include "footer.php";
?>