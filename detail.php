<?php
// рус
$is_frontend = true;
include_once "init.php";
require_once("includes/class/Videos.inc.php");
require_once("includes/class/class-timezone-conversion.php");

// ################# Start getting Youtube GData ##############################

$avatars		= get_avatar_files();

$vid		 	= $_GET['vid'];

$video_data    	= YT_GetDetail($vid);

$is_filtered	= is_yt_thumbnail_filtered($video_data);
if ( $is_filtered ) {
	header("Location: ".$config['website_url']);
	exit(0);
}

$video_comment = YT_GetComment($vid);
$local_video_comment = getComments($vid,$config["local_comments_per_page"]);

$totalYoutubeComments = isset($video_comment['feed']['openSearch$totalResults']['$t']) ? $video_comment['feed']['openSearch$totalResults']['$t'] : 0;
$totalLocalComments =  $local_video_comment["total"];
$totalComments = $totalYoutubeComments + $totalLocalComments;

// ################# Start splitting keywords #################################
$video_kwords	= isset($video_data['tags']) ? $video_data['tags'] : array();
//print '<pre>'; print_r($video_data);print '</pre>';

$i=0;
$encodedKeywords = "";
foreach($video_kwords as $k => $v) {
  $keywords[$i]['keyword'] = cs_strtolower($v);
  if($i < 3)
  	$encodedKeywords .= trim($v)." ";
  $i++;
}

$video_keywords	= "";

if ( count($video_kwords) > 0 ) {
	$video_keywords	= implode(",", $video_kwords);
}

$comment_video_count	= 0;

$favorite_count		= isset($video_data['favoriteCount']) ? $video_data['favoriteCount'] : 0;
$view_count			= isset($video_data['viewCount']) ? $video_data['viewCount']: 0;
$rating_num_raters	= isset($video_data['ratingCount']) ? $video_data['ratingCount'] : 0;
$rating_average		= isset($video_data['rating']) ? $video_data['rating'] : 0;
$duration			= isset($video_data['duration']) ? $video_data['duration'] : 0;

$permalink = $config['website_url']."video/".$_GET['vid']."/".SeoKeywordEncode($video_data['title']).".html";

$video_response	= YT_Video_Response($_GET['vid']);
$response_count	= isset($video_response['data']['totalItems']) ? $video_response['data']['totalItems'] : 0;

$phpversion	= phpversion();
// v4 ?
if ( substr($phpversion, 0, 1) < 5) {
	$tz = new TimezoneConversion();
	$tz->setProperty('DateTime', $video_data['uploaded']);
	$time_published	= strtotime($tz->convertDateTime());
} else {
	$time_published	= strtotime($video_data['uploaded']);
}

if ( is_browsers_user_agent() ) {
	$VideoObject	= Videos::find_by_video_id($_GET['vid'], $time_published);
	$VideoObject->count_increment('view_count');
	$VideoObject->load();
	$VideoObject->response_count = $response_count;
	$VideoObject->author		= isset($video_data['uploader']) ? $video_data['uploader'] : '';
	$VideoObject->description	= isset($video_data['description']) ? $video_data['description'] : '';
	$VideoObject->category		= isset($video_data['category']) ? $video_data['category'] : '';
	$VideoObject->keywords		= $video_keywords;
	$VideoObject->title			= isset($video_data['title']) ? $video_data['title'] : '';
	$VideoObject->last_viewed 	= time();
	$VideoObject->duration		= $duration;
	$VideoObject->favorite_count= $favorite_count;
	$VideoObject->comment_count	= (isset($video_comment['feed']['openSearch:totalResults']) ? $video_comment['feed']['openSearch:totalResults'] : 0);
	$VideoObject->rating_count	= (isset($rating_num_raters) ? $rating_num_raters : 0);
	$VideoObject->rating		= round($rating_average, 2);
	$VideoObject->save();
}

// Youtube player
	if($config['youtube_player'])
	{
		if(isset($_GET["fmt"]))
			$download_link = "http://www.youtube.com/v/".$_GET['vid']."&ap=%2526fmt%3D".$_GET['fmt'];
		else
			$download_link = "http://www.youtube.com/v/".$_GET['vid'];

		$player_file = $download_link;
	}
// Custom Player
else
{
	// Video Format Specified?
		if(isset($_GET["fmt"]))
		{
			$download_link = "http://www.youtube.com/watch?v=".$_GET['vid']."&fmt=".$_GET['fmt'];
		}
		else
		{
			$download_link = "http://www.youtube.com/watch?v=".$_GET['vid'];
		}

	// Longtail Video Ad
		if($config['longtail_enabled']) {

			//$player_longtail_param = '&ltas.cc='.$config["longtail_channel"].'&plugins=ltas,clickproxy&clickproxy.listener=get_video_url&icons=false';
			$player_longtail_param = '&plugins=ltas&ltas.cc='.$config["longtail_channel"].'&icons=false';
			$player_autostart = "true";

		}else{

			//$player_longtail_param = '&plugins=clickproxy&clickproxy.listener=get_video_url&icons=false';
			$player_longtail_param = '&icons=false';
			$player_autostart = "true";
		}

	// Longtail Player Skin
		if($config['cplayer_skin'] == "default") {

			$player_skin = "";

		} else {

			if ( file_exists(BASE_PATH."player".DS."skins".DS.$config["cplayer_skin"]."/".$config["cplayer_skin"].".swf") ) {
				$player_skin = "&skin=".$config["website_url"]."player/skins/".$config["cplayer_skin"]."/".$config["cplayer_skin"].".swf";
			} else {
				$player_skin = "&skin=".$config["website_url"]."player/skins/".$config["cplayer_skin"]."/".$config["cplayer_skin"].".zip";
			}
		}


	$showDownload = ($config['enable_download'] == true) ? "true" : "false";

	if($config['enable_player_playlist']) {

		//$playerfirst = base64_encode($_GET['vid']."||".$video_data['title']."||".$video_data['description']."||".$video_data['uploader']."||".$duration);
		//$player_file = urlencode($config["website_url"].'xml.playlist.php?tag='.urldecode($encodedKeywords).'&playerfirst='.$playerfirst);
		$player_file = urlencode($config["website_url"].'xml.related.php?vid='.urlencode($_GET['vid']));

	}
	else
	{
		$player_file = urlencode($config['website_url'].$download_link);
		$is_filtered	= is_yt_thumbnail_filtered($video_data);
	}
	$player_colors = '&backcolor='. $config["player_backcolor"]. '&frontcolor='. $config["player_frontcolor"]. '&lightcolor='. $config["player_lightcolor"]. '&screencolor='. $config["player_screencolor"];
}


// Set the image_src in metatag so that FB social sharing can use it as thumbnail
	if ( !$is_filtered ) {
		$player_image= 'http://img.youtube.com/vi/' . $_GET['vid'] . '/0.jpg';
	} else {
		$player_image= $config['website_url'] . 'images/disallowed_thumbnail.gif';
	}

$showDescription = ($video_data['description'] != "") ? true : false;

$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';
$main_menu	= main_menu($feed_id);



// GD enabled?
	$is_gd_enabled	= 0;
	if (function_exists("gd_info")) {
		$is_gd_enabled = 1;
	}

// Random Avatars
	$temp_avatars					= $avatars;
	$yt_comments	= array();
	if ( isset($video_comment['feed']['entry']) && count($video_comment['feed']['entry']) > 0 ) {
		foreach($video_comment['feed']['entry'] as $key => $yt_comment) {
			$yt_comments[$key]['published']	= isset($yt_comment['published']['$t']) ? $yt_comment['published']['$t'] : 0;
			$yt_comments[$key]['content']	= isset($yt_comment['content']['$t']) ? $yt_comment['content']['$t'] : '';
			$yt_comments[$key]['author'][0]['name']	= isset($yt_comment['author'][0]['name']['$t']) ? $yt_comment['author'][0]['name']['$t'] : '';
			$yt_comments[$key]['author_url']	= 'profile/'.$yt_comments[$key]['author'][0]['name'].'/';
			$yt_comments[$key]["avatar"]	= "";
			if ( count($temp_avatars) > 0 ) {
				list($temp_avatars, $yt_comments[$key]["avatar"]) = randomize_avatars($temp_avatars);
			} else {
				$temp_avatars					= $avatars;
				list($temp_avatars, $yt_comments[$key]["avatar"]) = randomize_avatars($temp_avatars);
			}
		}
	}

// ################# Start assign template variables ##########################

$tpl->assign('showDescription',$showDescription);
$tpl->assign('enable_download',$config['enable_download']);
$tpl->assign('permalink',$permalink);
$tpl->assign('download_link',$download_link);
$tpl->assign('main_menu', $main_menu );

$tpl->assign('youtube_player',$config['youtube_player']);
$tpl->assign('fmt',$_GET["fmt"]);
$tpl->assign('video_upload_enabled',$config["video_upload_enabled"]);
$tpl->assign('player_file',$player_file);

//echo urldecode($player_file);
if ( !isset($player_image) ) {
	$tpl->assign('player_image', 'http://img.youtube.com/vi/' . $_GET['vid'] . '/0.jpg');
}
$tpl->assign('player_image', $player_image);


if( empty( $config["addthis_profile_id"]  ) )
{
  $config["addthis_profile_id"] = 'ra-506591410520c128';
}

if(!$config['youtube_player']) {

	$tpl->assign('enable_player_playlist',$config["enable_player_playlist"]);
	$tpl->assign('player_skin',$player_skin);
	$tpl->assign('player_custom_plugins_detail',$config["player_custom_plugins_detail"]);
	$tpl->assign('player_custom_plugins_detail_playlist',$config["player_custom_plugins_detail_playlist"]);
	$tpl->assign('enable_player_colors',$config["enable_player_colors"]);
	$tpl->assign('player_colors',$player_colors);
	$tpl->assign('player_longtail_param',$player_longtail_param);

	if ( $config["enable_player_playlist"] ) {
		parse_str($config["player_custom_plugins_detail_playlist"], $output);
	} else {
		parse_str($config["player_custom_plugins_detail"], $output);
	}

	$player_autostart	= isset($output['autostart']) ? $output['autostart'] : $player_autostart;

	$tpl->assign('player_autostart',$player_autostart);
}

$vid_title	= (isset($video_data['title']) ? $video_data['title'] : '');

if ( !is_keyword_allowable($vid_title) ) {
	header("Location: ".$config['website_url']);
	exit(0);
}

if ( trim($vid_title) == '' ) {
	$vid_title = "&nbsp;";
}

if($config['longtail_enabled']) {
	$tpl->assign('longtail_channel',$config['longtail_channel']);
} else {
	$tpl->assign('longtail_channel',$config['longtail_channel']);
}
$tpl->assign('player_backcolor',$config['player_backcolor']);
$tpl->assign('player_frontcolor',$config['player_frontcolor']);
$tpl->assign('player_lightcolor',$config['player_lightcolor']);
$tpl->assign('player_screencolor',$config['player_screencolor']);

$tpl->assign('id',$_GET['vid']);
$tpl->assign('published', (isset($video_data['uploaded']) ? $video_data['uploaded'] : 0) );
$tpl->assign('updated', (isset($video_data['updated']) ? $video_data['updated'] : 0) );
$tpl->assign('title', $vid_title);
$tpl->assign('author', (isset($video_data['uploader']) ? $video_data['uploader'] : '') );

$video_object_description	= (isset($video_data['description']) ? $video_data['description'] : '');
autolink($video_object_description);

$tpl->assign('description', cs_wordwrap($video_object_description, 40));
$tpl->assign('description_raw', (isset($video_data['description']) ? htmlspecialchars($video_data['description']) : '') );
$tpl->assign('short_description', (isset($video_data['description']) ? cs_wordwrap(str_truncate(strip_tags($video_data['description']), 300), 40) : '') );

$tpl->assign('category', (isset($video_data['category']) ? $video_data['category'] : ''));

$tpl->assign('keywords', $keywords);

if ( count($video_kwords) > 0 ) {
	$temp_kwords = array();
	foreach($video_kwords as $video_kword) {
		$video_kword = trim($video_kword);
		if ( $video_kword != '' ) {
			$temp_kwords[] = htmlspecialchars($video_kword);
		}
	}
	$tpl->assign('meta_keywords', implode(", ", $temp_kwords));
}

$tpl->assign('duration',$duration);
$tpl->assign('view_count',$view_count);
$tpl->assign('rating_num_raters',$rating_num_raters);
$tpl->assign('rating_average',$rating_average);

/* comments */
$tpl->assign('comments',$yt_comments);

if($config['facebook_comments_enabled'] == true)
{
	$tpl->assign('facebook_comments', true);
	$tpl->assign('facebook_app_id', $config['facebook_app_id']);
	$tpl->assign('lang_facebook_comment', lang('facebook_comment', 'Facebook Comments'));
}

$tpl->assign('local_comments',$local_video_comment);
$tpl->assign('totalYoutubeComments',$totalYoutubeComments);
$tpl->assign('totalLocalComments',$totalLocalComments);
$tpl->assign('totalComments',$totalComments);

$tpl->assign('local_comments_enabled', $config["local_comments_enabled"]);
$tpl->assign('youtube_comments_enabled', $config["youtube_comments_enabled"]);

$tpl->assign('relatedVideosPosition', $config["related_videos_position"]);

$tpl->assign('addthis_enabled', $config["addthis_enabled"] );
$tpl->assign('addthis_profile_id', $config["addthis_profile_id"] );
$tpl->assign('addthis_style', $config["addthis_style"] );

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

$tpl->assign('lang_hd_format_no_available', lang('hd_format_no_available', 'HD format might not be available') );
$tpl->assign('lang_play_in', lang('play_in', 'Play in'));
$tpl->assign('lang_low_quality', lang('low_quality', 'Low Quality'));
$tpl->assign('lang_high_quality', lang('high_quality', 'High Quality'));
$tpl->assign('lang_hd_quality', lang('hd_quality', 'HD Quality'));
$tpl->assign('lang_post_a_comment', lang('post_a_comment', 'Post a comment'));
$tpl->assign('lang_local_comments', lang('local_comments', 'Local Comments') );
$tpl->assign('lang_youtube_comments', lang('youtube_comments', 'Youtube Comments') );
$tpl->assign('lang_says', lang('says', 'Says'));
$tpl->assign('lang_guest_name', lang('guest_name', 'Guest Name'));
$tpl->assign('lang_comment', lang('comment', 'Comment') );
$tpl->assign('lang_click_for_new_code', lang('click_for_new_code', 'Click for new code') );
$tpl->assign('lang_submit', lang('submit', 'Submit'));
$tpl->assign('lang_loading_please_wait', lang('loading_please_wait'));
$tpl->assign('lang_please_enabled_gd_library', lang('please_enabled_gd_library'));
$tpl->assign('is_gd_enabled', $is_gd_enabled);
$tpl->assign('lang_view_all', lang('view_all'));

$tpl->display('detail.html');

?>