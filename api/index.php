<?php
$is_frontend = true;
include_once "../init.php";
include_once "temp.php";
include_once "../admin/inc/version.php";

$default_apikey	= "JUI8494UO9289";

$format		= isset($_GET['format']) ? $_GET['format'] : 'json';
$format		= isset($_POST['format']) ? $_POST['format'] : $format;

$action		= isset($_GET['action']) ? $_GET['action'] : '';
$action		= isset($_POST['action']) ? $_POST['action'] : $action;

$page		= isset($_GET['page']) ? $_GET['page'] : 1;
$page		= isset($_POST['page']) ? $_POST['page'] : $page;

$cat		= isset($_GET['cat']) ? $_GET['cat'] : 0;
$cat		= isset($_POST['cat']) ? $_POST['cat'] : $cat;
$cat		= (int) $cat;

$orderby	= isset($_GET['orderby']) ? $_GET['orderby'] : $config['sort_videos_by'];
$orderby	= isset($_POST['orderby']) ? $_POST['orderby'] : $orderby;

$vid		= isset($_GET['vid']) ? $_GET['vid'] : '';
$vid		= isset($_POST['vid']) ? $_POST['vid'] : $vid;

$apikey		= isset($_GET['apikey']) ? $_GET['apikey'] : '';
$apikey		= isset($_POST['apikey']) ? $_POST['apikey'] : $apikey;

if ( $action != 'getapikey' && $apikey != $default_apikey ) {
	$data['errors'] = 'Invalid Prismotube API Key';
	echo prismo_json_encode($data);
	exit(0);
}

if ( $action == 'getvideoslist' ) 
{
	
	if ( $cat == 0 && (!isset($config['default_category_id']) || $config['default_category_id'] == "0" || trim($config['default_category_id']) == '') ) 
	{
		$cat	= get_random_category_id();
	}
	else if ( isset($config['default_category_id']) && $config['default_category_id'] != "0" && trim($config['default_category_id']) != '' ) 
	{
		$cat 	= $config['default_category_id'];
	}

	$CategoryObject = new Category($cat);
	$CategoryObject->load();
	
	$data_videos_list	= get_videos_by_category($CategoryObject, $page, $orderby);

	$videos				= $data_videos_list['videos'];
	
	$data_videos		= array();
	foreach($videos as $key => $video) 
	{
		unset($video['description']);
		$data_videos['videos'][$key] = $video;
	}
	
	$data_videos['category_name']	= $data_videos_list['category_name'];
	$data_videos['total_videos']	= $data_videos_list['total_videos'];
	$data_videos['page']			= $data_videos_list['page'];
	
	echo prismo_json_encode($data_videos);
	
} 
else if ( $action == 'getvideocategories' ) 
{

	$CategoryObject			= new categories;
	$data_all_categories	= $CategoryObject->get_categories();
	
	echo prismo_json_encode($data_all_categories);
	
} 
else if ( $action == 'getvideodata' ) 
{
	if ( $vid != '' ) 
	{
		$video_data    = YT_GetDetail($vid);
		echo prismo_json_encode($video_data);
	}

} 
else if ( $action == 'getvideocomments' ) 
{
	if ( $vid != '' )
	{
		$video_comments	= YT_GetComment($vid);
		$yt_comments	= array();
		
		$avatars		= get_avatar_files();
		$temp_avatars	= $avatars;
		
		if ( isset($video_comments['feed']['entry']) && count($video_comments['feed']['entry']) > 0 ) {
			foreach($video_comments['feed']['entry'] as $key => $yt_comment) {
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

		echo prismo_json_encode($yt_comments);
	}
} 
else if ( $action == 'getrelatedvideos' ) 
{
	if ( $vid != '' ) 
	{
		$related_videos	= YT_ListByRelated($vid);
		echo prismo_json_encode($related_videos);
	}
} 
else if ( $action == 'getversion' ) 
{
	$data_version['PT_VERSION'] = PT_VERSION;
	echo prismo_json_encode($data_version);
}
else if ( $action == 'getapikey' ) {
	$data['apikey'] = $default_apikey;
	echo prismo_json_encode($data);
}
exit(0);
?>