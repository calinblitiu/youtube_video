<?

include_once(BASE_PATH."includes/class/Category.inc.php");
include_once("htmLawed.php");


$str_breadcrumb	= get_breadcrumbs();

$tpl->assign('html_cache_dir', BASE_PATH.$config['html_cache_dir'] );
$tpl->assign('web_html_cache_dir', $config['html_cache_dir'] );
$tpl->assign('website_name', $config['website_name'] );
$tpl->assign('website_url', $config['website_url'] );
$tpl->assign('website_slogan', $config['website_slogan'] );

$cat_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ( $cat_id > 0 ) {
	$CategoryObject = new Category($cat_id);
	$CategoryObject->load();
	$n_category_desc	= stripslashes($CategoryObject->c_desc);
	$c_name				= $CategoryObject->c_name;
	
	if ( trim($n_category_desc) == '' && trim($c_name) == '' ) {
		$n_category_desc	= $config['meta_description'];
	} else if ( trim($n_category_desc) == '' && trim($c_name) != '' ) {
		$n_category_desc	= $c_name;
	}
	
	if ( trim($c_name) == '' ) {
		$c_name	= $config['meta_keywords'];
	}
	
	$tpl->assign('meta_keywords', $c_name );
	$tpl->assign('meta_description', $n_category_desc );
} else {
	$tpl->assign('meta_keywords', $config['meta_keywords'] );
	$tpl->assign('meta_description', $config['meta_description'] );
}

$tpl->assign('ads_468', $config['ads_468'] );
$tpl->assign('ads_728', $config['ads_728'] );
$tpl->assign('ads_300', $config['ads_300'] );
$tpl->assign('header_code', $config['header_code'] );
$tpl->assign('footer_code', $config['footer_code'] );
$tpl->assign('filterStatus', $filterStatus );
$tpl->assign('opt_safe_search', $config['default_filter_value'] );


if ($_COOKIE['filter'] == "off") {
	$filterStatusButton = lang('filter_off');
} else {
	$filterStatusButton = lang('filter_on');
}
$tpl->assign('filterStatusButton', $filterStatusButton );
$tpl->assign('filterStatusBackground', $filterStatusBackground );
$tpl->assign('filterValue', $filterValue );
$tpl->assign('js_vars', $js_vars );
$tpl->assign('active_template', $config["active_template"] );
$tpl->assign('active_theme', $config["active_theme"] );

if ( $config["wibiya_enabled"] == 'true' ) {
$tpl->assign('wibiya_code', $config["wibiya_code"] );
} else {
$tpl->assign('wibiya_code', '' );
}

$tpl->assign('google_web_fonts_enabled', $config["google_web_fonts_enabled"] );

$tpl->assign('shoutbox_enabled', $config["shoutbox_enabled"] );
if ( $config["shoutbox_enabled"] == 'true' ) {
$tpl->assign('shoutbox_code', $config["shoutbox_code"] );
} else {
$tpl->assign('shoutbox', '' );
}

if ( $config["skysa_bar_enabled"] == 'true' ) {
$tpl->assign('skysa_bar_code', $config["skysa_bar_code"] );
} else {
$tpl->assign('skysa_bar_code', '' );
}

if ( $config["tags_enabled"] == 'true' ) {
$tpl->assign('tags_enabled', $config["tags_enabled"] );
} else {
$tpl->assign('tags_enabled', '' );
}

$tpl->assign('virtual_keyboard_enabled', $config['virtual_keyboard_enabled'] );
$tpl->assign('virtual_keyboard_default_language', $config['virtual_keyboard_default_language'] );
$tpl->assign('virtual_keyboard_layout_code', $config['virtual_keyboard_layout_code'] );

$logo_path	= str_replace(DS."lib", "",  dirname(__FILE__));
if ( $config['website_logo'] != '' && file_exists($logo_path.DS."images".DS.$config['website_logo']) ) {
	$tpl->assign('website_logo', "images/".$config['website_logo']);
} else {
	$tpl->assign('website_logo', "");
}


$theme_base	= "templates/".$config["active_template"]."/";
$tpl->assign('theme_base', $theme_base );

$tpl->assign('tpl_base', $tpl_base );
$tpl->assign('pt_version', PT_VERSION );
$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
$tpl->assign('admin_logged', $is_logged );
$tpl->assign('showTagCloud', $config["tag_cloud_enabled"] );
$tpl->assign('showLinks', $config["links_enabled"] );
$tpl->assign('url_base', $config['website_url']);

$tpl->assign('video_search_enabled', (!isset($config["video_search_enabled"]) ? 'true' : $config["video_search_enabled"] ) );

$tpl->assign('facebook_enabled', $config["facebook_enabled"] );
$tpl->assign('facebook_page_url', $config["facebook_page_url"] );
$tpl->assign('facebook_stream', $config["facebook_stream"] );
if ( $config["facebook_stream"] == 'true' ) {
	$tpl->assign('facebook_height', "558" );
	$tpl->assign('facebook_iframe_height', "515" );
} else {
	$tpl->assign('facebook_height', "258" );
	$tpl->assign('facebook_iframe_height', "215" );
}

$twitter_auto_kword	= "";
if ( isset($config['twitter_auto_keyword']) && $config['twitter_auto_keyword'] == 1 && strpos($_SERVER['SCRIPT_FILENAME'], 'list.php') !== false ) {
	if ( isset($_GET['id']) ) {
		$catid	= $_GET['id'];
		$CategoryObject = new Category($catid);
		$CategoryObject->load();
		if ( $CategoryObject->c_listing_source == 'keyword' ) {
			$twitter_auto_kword = str_replace("-"," ", $CategoryObject->c_keyword); 
		} else {
			$twitter_auto_kword = str_replace("-"," ", $CategoryObject->c_name); 
		}
	} else if ( isset($_GET['q']) ) {
		$kword	= $_GET['q'];
		$twitter_auto_kword 	= str_replace("-"," ",urldecode($kword)); 
	}
}

$twitter_feeds = get_tweeter_feeds($twitter_auto_kword);

$flickr_auto_keyword	= "";
if ( isset($config['flickr_auto_keyword']) && $config['flickr_auto_keyword'] == 1 && strpos($_SERVER['SCRIPT_FILENAME'], 'list.php') !== false ) {
	if ( isset($_GET['id']) ) {
		$catid	= $_GET['id'];
		$CategoryObject = new Category($catid);
		$CategoryObject->load();
		if ( $CategoryObject->c_listing_source == 'keyword' ) {
			$flickr_auto_keyword = str_replace("-"," ", $CategoryObject->c_keyword); 
		} else {
			$flickr_auto_keyword = str_replace("-"," ", $CategoryObject->c_name); 
		}
	} else if ( isset($_GET['q']) ) {
		$kword	= $_GET['q'];
		$flickr_auto_keyword 	= str_replace("-"," ",urldecode($kword)); 
	}
}
$flickr_feeds = get_flickr_photos($flickr_auto_kword);

$is_display_captcha		= 1;
if ( is_spider() ) {
	if ( !is_three_major_se() ) {
		$is_display_captcha	= 0;
	}
}
$tpl->assign('is_display_captcha', $is_display_captcha);

$catid	= isset($_GET['id']) ? (int) $_GET['id'] : 0;
$tpl->assign('catid', $catid);

$tpl->assign('twitter_feeds', $twitter_feeds);
$tpl->assign('flickr_feeds', $flickr_feeds);

$tpl->assign('data_links', getLinks_data());

$tpl->assign('is_show_twitter_feeds', ((count($twitter_feeds) > 0 ) ? 1: 0));
$tpl->assign('lang_twitter_feeds', lang('twitter_feeds') );

$tpl->assign('is_show_flickr_feeds', ((count($flickr_feeds) > 0 ) ? 1: 0));
$tpl->assign('lang_flickr', lang('flickr') );
$tpl->assign('is_categories_cache', ($config['categories_cache_enable'] ? 1: 0));

$options_sort_by = array(
	0 => array(
		'key' => 'relevance',
		'desc' => lang('relevance', 'Relevance'),
	),
	1 => array(
		'key' => 'viewCount',
		'desc' => lang('view_count', 'View Count'),
	), 
	2 => array(
		'key' => 'published',
		'desc' => lang('published'),
	),
	3 => array(
		'key' => 'rating',
		'desc' => lang('rating', 'Rating'),
	),
);

$tpl->assign('options_sort_by', $options_sort_by );

$tpl->assign('lang_view_tweet', lang('view_tweet') );
$tpl->assign('lang_you_are_here', lang('you_are_here') );
$tpl->assign('lang_sitemap', lang('sitemap') );
$tpl->assign('lang_all_categories', lang('all_categories') );
$tpl->assign('lang_contact_us', lang('contact_us') );
$tpl->assign('lang_facebook', lang('facebook') );
$tpl->assign('lang_home', lang('home') );
$tpl->assign('lang_next_page', lang('next_page') );
$tpl->assign('lang_previous_page', lang('previous_page') );
$tpl->assign('lang_page', lang('page') );
$tpl->assign('lang_of', lang('of') );
$tpl->assign('lang_more', lang('more') );
$tpl->assign('lang_less', lang('less') );
$tpl->assign('lang_tags', lang('tags') );
$tpl->assign('lang_rating', lang('rating') );
$tpl->assign('lang_related_videos', lang('related_videos') );
$tpl->assign('lang_nothing_found', lang('nothing_found') );
$tpl->assign('lang_go', lang('go') );
$tpl->assign('lang_filter_on', lang('filter_on') );
$tpl->assign('lang_filter_off', lang('filter_off') );
$tpl->assign('lang_search', lang('search'));
$tpl->assign('lang_by', lang('by') );
$tpl->assign('lang_permalink', lang('permalink') );
$tpl->assign('lang_views', lang('views') );
$tpl->assign('lang_added', lang('added') );
$tpl->assign('lang_channel', lang('channel') );
$tpl->assign('lang_duration', lang('duration') );
$tpl->assign('lang_videos', lang('videos') );
$tpl->assign('lang_when_you_need_to_type_english', lang('when_you_need_to_type_english') );

$tpl->assign('lang_users_watching_videos', lang('users_watching_videos') );
$tpl->assign('lang_videos_played_today', lang('videos_played_today') );
$tpl->assign('lang_your_video_url', lang('your_video_url') );

$tpl->assign('lang_please_enter_guest_and_comment', lang('please_enter_guest_and_comment') );
$tpl->assign('lang_please_enter_the_image_code', lang('please_enter_the_image_code') );
$tpl->assign('lang_wrong_image_code', lang('wrong_image_code') );
$tpl->assign('lang_upload_video_response_title', lang('upload_video_response') );

$tpl->assign('lang_step_1', lang('step_1') );
$tpl->assign('lang_step_2', lang('step_2') );
$tpl->assign('lang_enter_video_detail', lang('enter_video_detail') );

$tpl->assign('lang_view_count', lang('view_count', 'View Count') );
$tpl->assign('lang_relevance', lang('relevance', 'Relevance') );
$tpl->assign('lang_updated', lang('updated', 'Updated') );
$tpl->assign('lang_rating', lang('rating', 'Rating') );
$tpl->assign('lang_search_for_videos', lang('search_for_videos', 'Search for videos...') );
$tpl->assign('lang_gender', lang('gender', 'Gender') );
$tpl->assign('lang_location', lang('location', 'Location') );
$tpl->assign('lang_videos_watched', lang('videos_watched', 'Videos Watched') );
$tpl->assign('lang_no_favorite_listing_by', lang('no_favorite_listing_by', 'There is no favorite listing by') );
$tpl->assign('lang_no_favorite_videos', lang('no_favorite_videos', 'has no favorite videos') );
$tpl->assign('lang_make_your_homepage', lang('make_your_homepage', 'Make Your Homepage') );
$tpl->assign('lang_no_playlist_by', lang('no_playlist_by', 'There is no playlist by') );
$tpl->assign('lang_no_playlist_entry', lang('no_playlist_entry', 'There is no playlist entry in') );
$tpl->assign('lang_click_for_new_code', lang('click_for_new_code', 'Click for new code') );
$tpl->assign('lang_no_video_available', lang('no_video_available', 'There is no video available') );
$tpl->assign('lang_no_related_videos', lang('no_related_videos', 'There is no related videos') );

$tpl->assign('lang_online_watchers', lang('online_watchers') );

$tpl->assign('lang_please_specify_video_title', lang('please_specify_video_title') );
$tpl->assign('lang_please_specify_video_desc', lang('please_specify_video_desc') );
$tpl->assign('lang_please_specify_video_keyword', lang('please_specify_video_keyword') );
$tpl->assign('lang_please_specify_video_file', lang('please_specify_video_file') );

$tpl->assign('lang_action_not_allowed', lang('action_not_allowed') );

$tpl->assign('longtail_channel', $config["longtail_channel"]);
$tpl->assign('longtail_enabled', (($config["longtail_enabled"]) ? '1' : '0'));
$tpl->assign('default_filter_value', (isset($_COOKIE['filter']) ? $_COOKIE['filter'] : $config['default_filter_value']) );
$tpl->assign('youtube_player', $config["youtube_player"]);

if ( $str_breadcrumb != '' ) {
$tpl->assign('breadcrumb', lang('you_are_here'). ' ' .$str_breadcrumb );
} else {
$tpl->assign('breadcrumb', '' );
}

if ( $config["enable_player_colors"] ) {
	$tpl->assign('str_player_backcolor', 'background-color:#'.$config["player_backcolor"].';');
	$tpl->assign('str_player_frontcolor', 'color:#'.$config["player_frontcolor"].';');
} else {
	$tpl->assign('str_player_backcolor', "");
	$tpl->assign('str_player_frontcolor', "");
}

if ( isset($is_frontend) && $is_frontend === true ) {
	$all_ads_group	= all_ads_group();
	$all_ads_list	= all_ads_list();

	$top_ads	= get_all_ad_group(1);
	$tpl->assign('top_ads', $top_ads);

	$above_related_ads	= get_all_ad_group(2);
	$tpl->assign('above_related_ads', $above_related_ads);

	$below_video_ads	= get_all_ad_group(3);
	$tpl->assign('below_video_ads', $below_video_ads);

	$list_ads	= get_all_ad_group(4);
	$tpl->assign('list_ads', $list_ads);

	$bottom_ads	= get_all_ad_group(5);
	$tpl->assign('bottom_ads', $bottom_ads);

	$leaderboard_ads	= get_all_ad_group(6);
	$tpl->assign('leaderboard_ads', $leaderboard_ads);

	$above_video_ads	= get_all_ad_group(7);
	$tpl->assign('above_video_ads', $above_video_ads);

	$above_video_detail_ads	= get_all_ad_group(8);
	$tpl->assign('above_video_detail_ads', $above_video_detail_ads);

	$sitewide_above_category	= get_all_ad_group(9);
	$tpl->assign('sitewide_above_category', $sitewide_above_category);

	$sitewide_above_video_tags	= get_all_ad_group(10);
	$tpl->assign('sitewide_above_video_tags', $sitewide_above_video_tags);
}

if(!isset($_GET["id"])) {
	$ctg_id = 0;
} else {
	$ctg_id = $_GET["id"];
}

$tpl->assign('ctg_id', $ctg_id);
?>