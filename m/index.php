<?php
	define("IS_MOBILE", true);
	require_once("../init.php");
	//require_once(dirname(__FILE__)."../includes/class/Category.inc.php");
	
	block_googlebots_mobile();
	
	$page			= isset($_GET['p']) ? $_GET['p'] : 1;
	$randID			= isset($_GET['catid']) ? $_GET['catid'] : 0;

	$categoryObj	= new categories();
	$type			= '';
	
	if ( $randID == 0 ) {
		if ( !isset($config['default_category_id']) || $config['default_category_id'] == 0 || trim($config['default_category_id']) == '' ) {
			$randomData = categories_getRandom();
			$exploded = explode("||",$randomData);
			$randID = $exploded[0];
			$randKeyword = $exploded[1];
			$data_category	= $categoryObj->fetch($randID);	
			$category_title	= stripslashes($data_category['c_name']);
		} else {	
			$randID	= $config['default_category_id'];	
			$data_category	= $categoryObj->fetch($randID);	
			$randKeyword	= stripslashes($data_category['c_keyword']);
			$category_title	= stripslashes($data_category['c_name']);
		}
	} else {
		$data_category	= $categoryObj->fetch($randID);	
	}
	
	if ( $data_category['c_listing_source'] == 'keyword' ) {
		$category_title = str_replace("-"," ", stripslashes($data_category['c_keyword']));
		$video_datas 	= YT_ListByKeyword($category_title, $page);

	} else if ( $data_category['c_listing_source'] == 'author' ) {
		$category_title = str_replace("-"," ", stripslashes($data_category['c_name'])); 
		$video_datas 	= YT_GetUserUpload($data_category['c_user_videos'], $page, $config['list_per_page']);

	} else if ( $data_category['c_listing_source'] == 'playlist_id' ) {
		$category_title = str_replace("-"," ", stripslashes($data_category['c_name'])); 
		$video_datas 	= YT_GetUserPlaylistsEntry_XML($data_category['c_playlist_id'], $page, $config['list_per_page']);
		
		$type			= 'playlists_entry';
	}
	
	$total_videos			= YT_Total_Videos($video_datas);
	
	$next_page		= 0;
	$prev_page		= 0;
	
	if( ($page * $config['list_per_page'])  < $total_videos) {
		$next_page = $page + 1;
	}

	if ( $page > 1 ) {
		$prev_page = $page - 1;
	}

	$videos			= array();
	$idx			= 0;
	
	if ( isset($video_datas['data']['items']) ) {
		
		if ( $type == 'playlists_entry' ) {
			$items	= array();
			foreach($video_datas['data']['items'] as $key => $item) {
				$items[$key] = $item['video'];
			}
		} else {
			$items	= $video_datas['data']['items'];
		}

		foreach($items as $video_data) {
			
			$videos[$idx]['title']	= $video_data['title'];
			$videos[$idx]['id']		= $video_data['id'];
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
	$lang_views = lang('views', 'views');
	$lang_in	= lang('in');
	$lang_prev	= lang('prev');
	$lang_next	= lang('next');
	$lang_browse_categories	= lang('browse_categories');
	
	include("templates/default/index.php");
?>