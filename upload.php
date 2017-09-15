<?
// рус
@set_time_limit(0);
$is_frontend = true;
require_once("./lib/btube/btube.php");
require_once("./lib/xml.php");
include_once "init.php";

if ( $config['video_upload_enabled'] == 'false' ) {
	header("Location: ".$config['website_url']);
	exit(0);
}

@session_start();



	function yt_video_content_type($filename){
		$file_ext	= strtolower(array_pop(explode('.',$filename)));
		$file_exts	= array(
			'avi'	=> 'video/mpeg',
			'mpg'	=> 'video/mpeg',
			'mpeg' 	=> 'video/mpeg',
			'wmv' 	=> 'video/x-ms-wmv',
			'mov'	=> 'video/quicktime',
			'mp4'	=> 'video/mp4',
			'swf' 	=> 'application/x-shockwave-flash',
			'flv'	=> 'video/x-flv',
			'3gp'	=> 'video/3gpp',
		);
		if (array_key_exists($file_ext, $file_exts)) {
            return $file_exts[$file_ext];
        } else {
			return false;
		}
	}

	function yt_video_categories() {


				$catURL = 'http://gdata.youtube.com/schemas/2007/categories.cat';

				// retrieve category list using atom: namespace
				// note: you can cache this list to improve performance,
				// as it doesn't change very often!
				$cxml = simplexml_load_file($catURL);
				$cxml->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');
				$categories = $cxml->xpath('//atom:category');



			foreach ($categories as $c)
			{
				foreach($c->attributes() as $attributeskey0 => $attributesvalue1)
				{
					//echo "$attributeskey0 $attributesvalue1<br>";
					$attributesvalue0 = (string) $attributesvalue0;
					if($attributeskey0 == 'term')
					{
						$name = (string) $attributesvalue1;
					}
					if($attributeskey0 == 'label')
					{
						$valuedata = (string) $attributesvalue1;
						$tmp_array[] = Array( 'name' => $name , 'value_data' => $valuedata  );
					}
				}
			}
			//echo '<pre>';print_r( $tmp_array );exit();
			return $tmp_array;

	}

$post_string		= "";
$YOUTUBE_DEV_KEY	= $config["yt_dev_key"];
$YOUTUBE_USERNAME	= $config["yt_username"];
$YOUTUBE_PWD		= $config["yt_password"];
$categories			= yt_video_categories();


if ( substr($config["website_url"], -1, 1) == '/' ) {
	$next_url			= urlencode($config["website_url"]."upload.php");
	$yt_response_url	= $config["website_url"]."upload_yt_response.php";
} else {
	$next_url			= urlencode($config["website_url"]."/upload.php");
	$yt_response_url	= $config["website_url"]."/upload_yt_response.php";
}

$is_yt_auth			= 0;
$is_yt_data			= 0;
$err_message		= "";

//echo $YOUTUBE_DEV_KEY. " = " .$YOUTUBE_USERNAME . " = " .$YOUTUBE_PWD;
//exit(0);
unset($_SESSION['yt_auth']);
if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

	if ( !isset($_SESSION['yt_auth'])){
		$btube			= new dtube($YOUTUBE_DEV_KEY,"Prismotube", $YOUTUBE_USERNAME, $YOUTUBE_PWD);
		$auth			= $btube->login();
		//echo '<pre>';print_r( $auth );exit();
		if ( !$auth[0] ) {
			$err_message			= '<p class="msg error">'.lang('err_youtube_account').'</p>';
		} else {
			$auth 					= $auth[1];
			$_SESSION['yt_auth'] 	= $auth;
			$is_yt_auth				= 1;
		}


	} else {

		$is_yt_auth				= 1;
	}



} else if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnSubmit']) ) {
	$video_title	= isset($_POST['title']) ? $_POST['title'] : '';
	$video_desc		= isset($_POST['desc']) ? $_POST['desc'] : '';
	$video_cat		= isset($_POST['cat']) ? $_POST['cat'] : '';
	$video_kword	= isset($_POST['keyword']) ? $_POST['keyword'] : '';


	$dtube 			= new dtube($YOUTUBE_DEV_KEY, "Prismotube", $YOUTUBE_USERNAME, $YOUTUBE_PWD);
	$yt_token		= $dtube->upload($video_title,$video_desc,$video_cat,$video_kword);

	if ( !$yt_token ) {
		$err_message			= '<p class="msg error">'.lang('err_youtube_account').'</p>';
	} else {
	$is_yt_data		= 1;
	$url_token		= $yt_token[0];
	$upload_token	= $yt_token[1];
	}
}


$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';
$main_menu	= main_menu($feed_id);
// ################# Start assign template variables ##########################

$tpl->assign('video_upload_enabled',$config["video_upload_enabled"]);
$tpl->assign('categories', $categories);
$tpl->assign('is_yt_auth', $is_yt_auth);
$tpl->assign('is_yt_data', $is_yt_data);
$tpl->assign('url_token', $url_token);
$tpl->assign('upload_token', $upload_token);
$tpl->assign('yt_response_url', $yt_response_url);
$tpl->assign('main_menu', $main_menu );

$tpl->assign('lang_upload', lang('upload', 'Upload') );
$tpl->assign('lang_video', lang('video', 'Video') );
$tpl->assign('lang_keyword', lang('keyword', 'Keyword') );
$tpl->assign('lang_title', lang('title', 'Title') );

$tpl->assign('lang_upload_video', lang('upload_video', 'Upload Video') );
$tpl->assign('lang_video_category', lang('video_category', 'Video Category') );
$tpl->assign('lang_video_description', lang('video_description', 'Video Description') );

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
$tpl->assign('lang_gender', lang('gender', 'Gender') );
$tpl->assign('lang_hometown', lang('hometown', 'Hometown') );
$tpl->assign('lang_location', lang('location', 'Location') );
$tpl->assign('lang_videos_watched', lang('videos_watched', 'Videos Watched') );
$tpl->assign('lang_subscribers', lang('subscribers', 'Subscribers') );

$tpl->assign('lang_books', lang('books', 'Books') );
$tpl->assign('lang_movies', lang('movies', 'Movies') );
$tpl->assign('lang_music', lang('music', 'Music') );
$tpl->assign('lang_hobbies', lang('hobbies', 'Hobbies') );

$tpl->assign('lang_profile', lang('profile', 'Profile') );
$tpl->assign('lang_videos', lang('videos', 'Videos') );
$tpl->assign('lang_favorites', lang('favorites', 'Favorites') );
$tpl->assign('lang_playlists', lang('playlists', 'Playlists') );
$tpl->assign('lang_no_uploaded_by', lang('no_uploaded_by', 'There is no uploaded by') );
$tpl->assign('lang_no_favorite_listing_by', lang('no_favorite_listing_by', 'There is no favorite listing by') );
$tpl->assign('lang_no_video_uploaded_by', lang('no_video_uploaded_by', 'There is no videos uploaded by') );
$tpl->assign('lang_channel_views', lang('channel_views', 'Channel Views') );
$tpl->assign('lang_no_playlist_by', lang('no_playlist_by', 'There is no playlist by') );
$tpl->assign('lang_no_playlist_entry', lang('no_playlist_entry', 'There is no playlist entry in') );


$tpl->assign('err_message', $err_message );

$tpl->assign('lang_view_all', lang('view_all'));
$tpl->display('upload.html');

include "footer.php";
?>