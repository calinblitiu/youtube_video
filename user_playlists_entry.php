<?php
$is_frontend = true;
include_once "init.php";

if (empty($_GET['p'])) {
  $_GET['p'] = 1;
}

// ################# Start getting Youtube GData ##############################

$profile_id				= $_GET['u'];
$user_data           	= YT_GetUserProfile($_GET['u']);

if ( trim($user_data['name']) == '' && trim($user_data['title']) == '' ) {
	if ( $_GET['p'] == 1 ) {
		$ref = '/profile/'.$profile_id.'/playlists/';
	} else {
		$ref = '/profile/'.$profile_id.'/playlists/page'.$_GET['p'].'.html';
	}
	
	header("Location: ".$config['website_url']."error.php?ref=".$ref);
	exit(0);
}

$data_videos		 	= YT_GetUserPlaylistsEntry($_GET['lid'], $_GET['p'], $config['list_per_page']);

$total_videos	= YT_Total_Videos($data_videos);
$user_playlists_entry				= YT_Videos($data_videos, 'playlists_entry');

if ( $user_data['gender'] == 'm' ) $gender = lang('male');
elseif ( $user_data['gender'] == 'f' ) $gender = lang('female');
// ################# Start calculate pagination ###############################

if((($_GET['p']) * $config['list_per_page']) <= $total_videos) {
  $next_page = $_GET['p'] + 1;
}
if($_GET['p']>1) {
  $prev_page = $_GET['p'] - 1;
}

$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';
$main_menu	= main_menu($feed_id);
// ################# Start assign template variables ##########################

$tpl->assign('join_date', $user_data['published'] );
$tpl->assign('title', $user_data['title'] );
$tpl->assign('name', $user_data['name'] );
$tpl->assign('username', $user_data['username'] );
$tpl->assign('books', $user_data['books'] );

$tpl->assign('gender', $gender);
$tpl->assign('hobbies', $user_data['hobbies'] );
$tpl->assign('location', $user_data['location'] );
$tpl->assign('movies', $user_data['movies'] );
$tpl->assign('music', $user_data['music'] );
$tpl->assign('description', $user_data['description'] );
$tpl->assign('hometown', $user_data['hometown'] );

$tpl->assign('thumbnail', $user_data['thumbnail'] );
$tpl->assign('view_count', $user_data['viewCount'] );
$tpl->assign('subscriber_count', $user_data['subscriberCount'] );
$tpl->assign('view_watch_count', $user_data['videoWatchCount'] );
$tpl->assign('last_web_access', $user_data['lastWebAccess'] );

$tpl->assign('favorites_count',$favorites_count);
$tpl->assign('contacts_count',$contacts_count);
$tpl->assign('subscriptions_count',$subscriptions_count);
$tpl->assign('user_playlists_entry', $user_playlists_entry);
$tpl->assign('total',ceil($total_videos / $config['list_per_page']));
$tpl->assign('next_page',$next_page);
$tpl->assign('prev_page',$prev_page);
$tpl->assign('curr_page',$_GET['p']);
$tpl->assign('playlist_id',SeoTitleEncode($_GET['lid']));
$tpl->assign('playlist_title', $data_videos['feed']['title'] );
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

$tpl->assign('lang_previous_page', lang('previous_page', 'Previous Page') );
$tpl->assign('lang_next_page', lang('next_page', 'Next Page') );
$tpl->assign('lang_no_video_available', lang('no_video_available', 'There is no video available') );
$tpl->assign('lang_no_related_videos', lang('no_related_videos', 'There is no related videos') );
$tpl->assign('lang_views', lang('views', 'views') );
$tpl->assign('lang_Views', ucfirst(lang('views', 'views')) );
$tpl->assign('lang_length', lang('length', 'Length') );
$tpl->assign('lang_tags', lang('tags', 'Tags') );

$tpl->assign('lang_join', lang('join', 'Join') );
$tpl->assign('lang_name', lang('name', 'Name') );
$tpl->assign('lang_hometown', lang('hometown', 'Hometown') );
$tpl->assign('lang_subscribers', lang('subscribers', 'Subscribers') );

$tpl->assign('lang_books', lang('books', 'Books') );
$tpl->assign('lang_movies', lang('movies', 'Movies') );
$tpl->assign('lang_music', lang('music', 'Music') );
$tpl->assign('lang_hobbies', lang('hobbies', 'Hobbies') );

$tpl->assign('lang_profile', lang('profile', 'Profile') );
$tpl->assign('lang_favorites', lang('favorites', 'Favorites') );
$tpl->assign('lang_playlists', lang('playlists', 'Playlists') );
$tpl->assign('lang_no_uploaded_by', lang('no_uploaded_by', 'There is no uploaded by') );
$tpl->assign('lang_no_favorite_listing_by', lang('no_favorite_listing_by', 'There is no favorite listing by') );
$tpl->assign('lang_no_video_uploaded_by', lang('no_video_uploaded_by', 'There is no videos uploaded by') );
$tpl->assign('lang_channel_views', lang('channel_views', 'Channel Views') );

$tpl->assign('lang_view_all', lang('view_all'));

$tpl->display('user_playlists_entry.html');


include "footer.php";
?>