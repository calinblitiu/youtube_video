<?php
	define("IS_MOBILE", true);
	require_once("../init.php");
	
	block_googlebots_mobile();
	
	$author			= isset($_GET['author']) ? urldecode($_GET['author']) : '';
	$page			= isset($_GET['p']) ? $_GET['p'] : 1;
	$start_index 	= ($page - 1) * $config['list_per_page'] + 1;
	$videos			= array();
	$next_page		= 0;
	$prev_page		= 0;
	$published		= 0;
	
	if ( trim($author) != '' ) {
		$author_datas	= YT_GetUserProfile($author);
		$published		= isset($author_datas['published']) ? $author_datas['published'] : 0;
		$thumbnail		= isset($author_datas['thumbnail']) ? $author_datas['thumbnail'] : '';
		$view_count		= isset($author_datas['viewCount']) ? $author_datas['viewCount'] : 0;
		$subscriber_count = isset($author_datas['subscriberCount']) ? $author_datas['subscriberCount'] : 0;
		$view_watch_count	= isset($author_datas['videoWatchCount']) ? $author_datas['videoWatchCount'] : 0;
		$last_web_access	= isset($author_datas['lastWebAccess']) ? $author_datas['lastWebAccess'] : 0;

		$video_datas	= YT_GetUserUpload($author, $page, $config['list_per_page']);
		
		//$req 			= "http://gdata.youtube.com/feeds/mobile/videos?user={$author}&format=1&start-index={$start_index}&max-results=".$config['list_per_page'];
		//$xml_data		= YT_GetXMLRespCache($req);		
		//$video_datas	= XML_unserialize($xml_data);
		
		$idx			= 0;
		if ( isset($video_datas['data']['items']) ) {
			foreach($video_datas['data']['items'] as $video_data) {
				$video_id	= $video_data['id'];
			
				$videos[$idx]['title']	= $video_data['title'];
				$videos[$idx]['id']		= $video_id;
				$videos[$idx]['accesskey']	= $idx+1;
				$videos[$idx]['view']		= isset($video_data['viewCount']) ? $video_data['viewCount'] : 0;
				$duration	= isset($video_data['duration']) ? $video_data['duration'] : 0;
				
				if( $duration > 0 ) {
					$duration	= setSecondsToMinute($duration);
				} 
				
				$rtsp_url		= "";
				foreach ( $video_data['content'] as $media_content ) {
					if ( $media_content != '' ) {
						$rtsp_url	= $media_content;
					}
				}
				
				$average					= isset($video_data['rating']) ? $video_data['rating'] : 0;
				
				$videos[$idx]['rtsp_url']	= $rtsp_url;
				$videos[$idx]['duration']	= $duration;
				if ( $average > 0 ) {
					$videos[$idx]['average']	= round(($average * 100)/5);
				} else {
					$videos[$idx]['average']	= 0;
				}
				$idx++;
			}
		}
		
		$total_videos	= YT_Total_Videos($video_datas);
		
		if((($page+1) * $config['list_per_page']) < $total_videos) {
			$next_page = $page + 1;
		}

		if ( $page > 1 ) {
			$prev_page = $page - 1;
		}
	}
	
	$lang_views = lang('views', 'views');
	$lang_in	= lang('in');
	$lang_video = lang('video');
	$lang_watch_video	= lang('watch_video');
	$lang_related_videos	= lang('related_videos');
	$lang_comments	= lang('comments');
	$lang_prev	= lang('prev');
	$lang_next	= lang('next');
	$lang_channel	= lang('channel');
	$lang_videos	= lang('videos');
	$lang_subscribers	= lang('subscribers');
	$lang_channel_views	= lang('channel_views');
	$lang_joined	= lang('joined');
	$lang_browse_categories	= lang('browse_categories');
	
	include("templates/default/author.php");
?>