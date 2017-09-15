<?php
	define("IS_MOBILE", true);
	require_once("../init.php");
	
	block_googlebots_mobile();
	
	$video_id		= isset($_GET['v']) ? urldecode($_GET['v']) : '';
	$page			= isset($_GET['p']) ? $_GET['p'] : 1;
	
	

	$video_datas	= YT_GetDetail($video_id);
	
	$video_title	= isset($video_datas['title']) ? $video_datas['title'] : '';
	
	$start_index 	= ($page - 1) * $config['list_per_page'] + 1;

	$data_related_videos 	= YT_ListByRelated($video_id, $page, $config['list_per_page']);
	$total_video_related	= YT_Total_Videos($data_related_videos);
	
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
			$related_videos[$idx]['rtsp_url']	= $related_video_url;
			
			$average					= isset($data_related_videos['rating']) ? $data_related_videos['rating'] : 0;
		
			if ( $average > 0 ) {
				$related_videos[$idx]['average']	= round(($average * 100)/5);
			} else {
				$related_videos[$idx]['average']	= 0;
			}
			$idx++;
		}
	}
	
	$next_page		= 0;
	$prev_page		= 0;
	if((($page+1) * $config['list_per_page']) < $total_video_related) {
		$next_page = $page + 1;
	}

	if ( $page > 1 ) {
		$prev_page = $page - 1;
	}

	/*
	print '<pre>';
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
	$lang_videos_related_to	= lang('videos_related_to');
	$lang_prev	= lang('prev');
	$lang_next	= lang('next');
	$lang_browse_categories	= lang('browse_categories');
	
	include("templates/default/related.php");
?>