<?php
	define("IS_MOBILE", true);
	require_once("../init.php");
	
	block_googlebots_mobile();
	
	$q				= isset($_GET['q']) ? trim(urldecode($_GET['q'])) : '';
	$q				= isset($_POST['q']) ? trim($_POST['q']) : $q;
	$q				= stripslashes($q);
	
	$page			= isset($_GET['p']) ? $_GET['p'] : 1;
	
	$start_index 	= ($page - 1) * $config['list_per_page'] + 1;
	$videos			= array();
	
	if ( $q != '' ) {
		$video_datas	= YT_ListByKeyword($q, $page, 'relevance', true);
		
		//$req			= "http://gdata.youtube.com/feeds/mobile/videos?q=".urlencode($q)."&format=1&start-index={$start_index}&max-results=12&orderby=relevance";
		//$xml_data		= YT_GetXMLRespCache($req);		
		//$video_datas	= XML_unserialize($xml_data);
		
		$total_videos	= YT_Total_Videos($video_datas);
		
		if ( (($page+1) * $config['list_per_page']) < $total_videos ) {
			$new_page 	= $page + 1;
			$lang_page	= lang('next_page');
		} else if ( $page > 1 ) {
			$new_page = $page - 1;
			$lang_page	= lang('previous_page');
		}
		
		
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
					if ( $media_content != '' ){
						$rtsp_url	= $media_content;
					}
				}
				$average					= isset($video_data['rating']) ? $video_data['rating'] : 0;
				
				$videos[$idx]['rtsp_url']	= $rtsp_url;
				$videos[$idx]['duration']	= $duration;
				if ( $average > 0 ) {
					$videos[$idx]['average']	= round( ($average / 5 ) * 100 );
				} else {
					$videos[$idx]['average']	= 0;
				}
				$idx++;
			}
		}
		
		$next_page		= 0;
		$prev_page		= 0;
		
		if( ($page * $config['list_per_page'])  < $total_videos) {
			$next_page = $page + 1;
		}

		if ( $page > 1 ) {
			$prev_page = $page - 1;
		}
	
	}
	
	$lang_views = lang('views', 'views');
	$lang_in	= lang('in');
	$lang_prev	= lang('prev');
	$lang_next	= lang('next');
	$lang_browse_categories	= lang('browse_categories');
	
	include("templates/default/search.php");
?>