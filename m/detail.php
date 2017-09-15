<?php
	define("IS_MOBILE", true);
	require_once("../init.php");
	
	block_googlebots_mobile();
	
	$video_id		= isset($_GET['v']) ? urldecode($_GET['v']) : '';
	$video_datas	= YT_GetDetail($video_id);
	
	$video_title	= isset($video_datas['title']) ? $video_datas['title'] : '';
	
	$video_url		= "";
	
	foreach ( $video_datas['content'] as $media_content ) {
		if ( $media_content != '' ) {
			$video_url	= $media_content;
		}
	}
	
	$is_iphone		= false;
	preg_match('/(iphone|iPad)/i', $_SERVER['HTTP_USER_AGENT'], $matches);
	if ( isset($matches[1]) && $matches[1] != '' ) {
		$is_iphone	= true;
	}

	if ( $is_iphone ) {
		if ( $config['yt_developer_key'] != '' ) {
			$video_url	= "http://www.youtube.com/v/{$video_id}&f=gdata_videos&d=".$config['yt_developer_key'];
		} else {
			$video_url	= "http://www.youtube.com/v/{$video_id}&f=gdata_videos";
		}
	}
	
	$duration		= isset($video_datas['duration']) ? $video_datas['duration'] : 0;
	if( $duration > 0 ) {
		$duration	= setSecondsToMinute($duration);
	}
	
	$view_count		= isset($video_datas['viewCount']) ? $video_datas['viewCount'] : 0;
	$published_date	= "";
	
	$published		= isset($video_datas['published']) ? $video_datas['published'] : '';
	if ( $published != '' ) {
		$published_date	= date("M j ,Y", strtotime($published));
	}
	
	$video_average					= isset($video_datas['rating']) ? $video_datas['rating'] : 0;
	if ( $video_average > 0 ) {
		$video_average	= round(($video_average * 100)/5);
	}
	
	$author_name		= isset($video_datas['uploader']) ? $video_datas['uploader'] : '';
	$video_description	= isset($video_datas['description']) ? $video_datas['description'] : '';
	
	$vc_datas			= YT_GetComment($video_id);
	$total_comments		= isset($vc_datas['feed']['openSearch$totalResults']['$t']) ? $vc_datas['feed']['openSearch$totalResults']['$t'] : 0;
	
	$related_videos		= array();
	$link_related		= "";
	$video_links		= isset($video_datas['entry']['link']) ? $video_datas['entry']['link'] : array();
	$video_related_url	= "";
	foreach($video_links as $video_link_attr) {
		if ( is_array($video_link_attr) ) {
			$href_attr	= $video_link_attr['href'];
			if ( strpos($href_attr, 'related') !== false ) {
				$video_related_url	= $href_attr;
				break;
			}
		}
	}
	
	$data_related_videos 	= YT_ListByRelated($video_id, 1, 5);
	

	
	if ( isset($data_related_videos['data']['items']) ) {
		$idx	= 0;
		foreach($data_related_videos['data']['items'] as $data_related_videos) {
			$related_videos[$idx]['video_id']	= $data_related_videos['id'];
			$related_videos[$idx]['title']		= $data_related_videos['title'];
			$related_videos[$idx]['accesskey']	= $idx+1;
			$related_video_url	= "";
			foreach($data_related_videos['content'] as $related_media_content) {
				if ( $related_media_content != '' ) {
					$related_video_url	= $related_media_content;
				}
			}
			
			$duration	= isset($data_related_videos['duration']) ? $data_related_videos['duration'] : 0;
		
			if( $duration > 0 ) {
				$duration	= setSecondsToMinute($duration);
			} 
			
			$related_videos[$idx]['view']		= isset($data_related_videos['viewCount']) ? $data_related_videos['viewCount'] : 0;
			$related_videos[$idx]['duration']	= $duration;
			$related_videos[$idx]['video_url']	= $related_video_url;
			
			$average					= isset($data_related_videos['rating']) ? $data_related_videos['rating'] : 0;
		
			if ( $average > 0 ) {
				$related_videos[$idx]['average']	= round(($average * 100)/5);
			} else {
				$related_videos[$idx]['average']	= 0;
			}
			$idx++;
		}
	}
	
	/*
	print '<pre>';
	print_r($video_datasw);
	print_r($related_videos);
	print '</pre>';
	exit(0);
	*/
	
	$lang_views = lang('views', 'views');
	$lang_in	= lang('in');
	$lang_video = lang('video');
	$lang_watch_video	= lang('watch_video');
	$lang_related_videos	= lang('related_videos');
	$lang_comments	= lang('comments');
	$lang_view	= lang('view');
	$lang_view_all_related_videos	= lang('view_all_related_videos');
	$lang_browse_categories	= lang('browse_categories');
	
	include("templates/default/detail.php");
?>