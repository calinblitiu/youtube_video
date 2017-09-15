<?php
include_once("init.php");
include_once("includes/class/Category.inc.php");

if($PLAYER_FIRST == "") {
	$config['list_per_page']   = 15; 
}else{
	$config['list_per_page']   = 6; 
}

if (empty($_GET['p'])) {
  $_GET['p'] = 1;
}

if (empty($_GET['orderby'])) {
  $orderby = $config["sort_videos_by"];
}else{
  $orderby = $_GET['orderby'];
}

$page		= $_GET['p'];
$randID		= isset($_GET['randID']) ? (int) $_GET['randID'] : 0;

if ( ( !isset($config['default_category_id']) || $config['default_category_id'] == 0 || trim($config['default_category_id']) == '' ) && $randID == 0 ) {
	$tag = urldecode($_GET['tag']);
	$data_videos 	= YT_ListByKeyword($tag, $_GET['p'], $orderby);
	$total_videos	= YT_Total_Videos($data_videos);
	$videos			= YT_Videos($data_videos);
} else {	
	if ( $randID == 0 ){
		$randID	= $config['default_category_id'];
	} 

	$CategoryObject = new Category($randID);
	$CategoryObject->load();
	$category_desc	= stripslashes($CategoryObject->c_desc);
	$randKeyword	= $CategoryObject->c_keyword;
	

	if ( $CategoryObject->c_listing_source == 'keyword' ) {
		$keyword = str_replace("-"," ", $CategoryObject->c_keyword); 
		$data_videos 	= YT_ListByKeyword($keyword, $_GET['p'], $orderby);
	
		$total_videos	= YT_Total_Videos($data_videos);
		$videos			= YT_Videos($data_videos);
	} else if ( $CategoryObject->c_listing_source == 'author' ) {
		$keyword = str_replace("-"," ", $CategoryObject->c_name); 
		$data_videos = YT_GetUserUpload($CategoryObject->c_user_videos, $page, $config['list_on_home_page']);
		$total_videos	= YT_Total_Videos($data_videos);
		$videos			= YT_Videos($data_videos);
	} else if ( $CategoryObject->c_listing_source == 'playlist_id' ) {
		$keyword 		= str_replace("-"," ", $CategoryObject->c_name); 
		$data_videos 	= YT_GetUserPlaylistsEntry_XML($CategoryObject->c_playlist_id, $_GET['p'], $config['list_on_home_page']);
		$total_videos	= YT_Total_Videos($data_videos);
		$videos			= YT_Videos($data_videos, 'playlists_entry');
	}

}

// ################# Start getting Youtube GData ##############################
//print '<pre>';print_r($videos);print '</pre>';exit(0);
$xmlData  = '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">' . "\r\n";
$xmlData .= '<channel>' . "\r\n";

	if($PLAYER_FIRST != "") {
		$decoded = base64_decode($PLAYER_FIRST);
		$info = explode("||", $decoded);
		$videoID = $info[0];
		$videoTitle = $info[1];
		if ( $is_mbstring_enabled ) {
			$videoDescription = mb_substr($info[2],0,100, 'utf-8');
		} else {
			$videoDescription = substr($info[2],0,100);
		}
		$videoAuthor = $info[3];
		$videoDuration = $info[4];
		$url = $config['website_url'].'video/'.$videoID.'/watch.html';
		$file = "http://www.youtube.com/watch?v=".$videoID;
		$thumb = 'http://i.ytimg.com/vi/'.$videoID.'/0.jpg';

		$xmlData .= '    <item>' . "\r\n";
		$xmlData .= '        <title><![CDATA[' . $videoTitle . ']]></title>' . "\r\n";
		$xmlData .= '        <link><![CDATA[' . $file . ']]></link>' . "\r\n";
		$xmlData .= '        <description><![CDATA[' . $videoDescription . ']]></description>' . "\r\n";
		$xmlData .= '        <media:content url="' . htmlspecialchars($file) . '" type="video/x-flv" duration="'.$videoDuration.'"/>' . "\r\n";
		$xmlData .= '        <media:credit role="author"><![CDATA['.$videoAuthor.']]></media:credit>' . "\r\n";
		$xmlData .= '        <media:thumbnail url="' . $thumb . '"/>' . "\r\n";
		$xmlData .= '    </item>' . "\r\n";
	}

	$is_allowed 	= false;
	$tempXMLData	= '';
	
	// loop through each video in the list and display it for design purposes
	foreach ($videos as $video) {
		if ( !$video['is_filtered'] ) {
			$is_allowed = true;
		
			$url = $config['website_url'].'video/'.$video['id'].'/'.SeoKeywordEncode($video['title']).'.html';
			$file = "http://www.youtube.com/watch?v=".$video['id'];
			$thumb = $video['thumbnail'][0];

			$tempXMLData .= '    <item>' . "\r\n";
			$tempXMLData .= '        <link><![CDATA[' . $url . ']]></link>' . "\r\n";

			$tempXMLData .= '        <title><![CDATA[' . $video['title'] . ']]></title>' . "\r\n";
			$tempXMLData .= '        <description><![CDATA[' . $video['description'] . ']]></description>' . "\r\n";
			$tempXMLData .= '        <media:content url="' . htmlspecialchars($file) . '" type="video/x-flv" duration="'.$video['duration'].'"/>' . "\r\n";
			
			$tempXMLData .= '        <media:credit role="author"><![CDATA['.$video['author'].']]></media:credit>' . "\r\n";
			$tempXMLData .= '        <media:thumbnail url="' . $thumb . '"/>' . "\r\n";
			$tempXMLData .= '    </item>' . "\r\n";
		}
	}
	
	if ( !$is_allowed ) {
		$xmlData .= '    <item>' . "\r\n";
		$xmlData .= '        <link><![CDATA[' . $config['website_url'].'player/videos_blocked.flv' . ']]></link>' . "\r\n";
		
		
		$xmlData .= '        <title><![CDATA['.lang('videos_blocked').']]></title>' . "\r\n";
		$xmlData .= '        <description><![CDATA['.lang('videos_blocked_desc').']]></description>' . "\r\n";
		
		$xmlData .= '        <media:content url="' . htmlspecialchars($config['website_url'].'player/videos_blocked.flv') . '" type="video/x-flv" duration="4"/>' . "\r\n";
		
		
		$xmlData .= '        <media:credit role="author"><![CDATA[Prismotube]]></media:credit>' . "\r\n";
		$xmlData .= '        <media:thumbnail url="' . $config['website_url'] . 'images/disallowed_thumbnail.gif"/>' . "\r\n";
		$xmlData .= '    </item>' . "\r\n";
	} else {
		$xmlData .= $tempXMLData;
	}

$xmlData .= '</channel>' . "\r\n";
$xmlData .= '</rss>' . "\r\n";

header('Content-type: text/xml');
header('Content-disposition: inline; filename=playlist.xml');
echo $xmlData;
?>