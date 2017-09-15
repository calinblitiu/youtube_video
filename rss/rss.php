<?php

include_once "../init.php";
include_once("../config/config_filter.php");
include_once("../includes/class/Category.inc.php");

$config['list_per_page']   = 48;  
// ################# Start recognize SEO Url data #############################
if (empty($_GET['p'])) {
	$_GET['p'] = 1;
}
$page	= $_GET['p'];
$tag = urldecode($_GET['tag']);
$catid	= isset($_GET['catid']) ? (int) $_GET['catid'] : 0;
// ################# Start getting Youtube GData ##############################

if ( $catid > 0 ) {
	$CategoryObject = new Category($catid);
	$CategoryObject->load();
	$category_desc	= stripslashes($CategoryObject->c_desc);

	if ( $CategoryObject->c_listing_source == 'keyword' ) {

		$keyword = str_replace("-"," ", $CategoryObject->c_keyword); 
		$data_videos 	= YT_ListByKeyword($keyword, $page, 'relevance');
		$total_videos	= YT_Total_Videos($data_videos);
		$videos			= YT_Videos($data_videos);
		
	} else if ( $CategoryObject->c_listing_source == 'author' ) {
		$keyword = str_replace("-"," ", $CategoryObject->c_name); 
		$data_videos = YT_GetUserUpload($CategoryObject->c_user_videos, $page, $config['list_per_page']);
		$total_videos	= YT_Total_Videos($data_videos);
		$videos			= YT_Videos($data_videos);
		
	} else if ( $CategoryObject->c_listing_source == 'playlist_id' ) {
		$keyword 		= str_replace("-"," ", $CategoryObject->c_name); 
		$data_videos 	= YT_GetUserPlaylistsEntry_XML($CategoryObject->c_playlist_id, $page, $config['list_per_page']);
		$total_videos	= YT_Total_Videos($data_videos);
		$videos			= YT_Videos($data_videos, 'playlists_entry');
	}
} else {
	$data_videos 	= YT_ListByKeyword($tag,  $page, 'relevance', false);
	$total_videos	= YT_Total_Videos($data_videos);
	$videos			= YT_Videos($data_videos);
}


$total = ceil($total_videos / $config['list_per_page']);

$xmlData  = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
$xmlData .= '<?xml-stylesheet type="text/css" href="'.$config['website_url'].'rss/rss.css" ?>'."\r\n";
$xmlData  .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\r\n";
$xmlData .= '<channel>' . "\r\n";
$xmlData .= '	<title>'.$config["website_name"].' - '.urldecode(stripslashes($tag)).' videos, Page: '.$page.' of '.$total.'</title>' . "\r\n"; 
$xmlData .= '	<link>'.$config['website_url'].''.urlencode(stripslashes($tag)).'/page'.$page.'.html</link>' . "\r\n"; 
$xmlData .= '	<description>'.$config["website_name"].' - '.urldecode(stripslashes($tag)).' videos</description>' . "\r\n"; 
$xmlData .= '	<language>'.$config["web_default_language"].'</language>' . "\r\n"; 
$xmlData .= '	<copyright>Copyright '.$config["website_name"].'</copyright>' . "\r\n"; 
$xmlData .= '	<category>'.urldecode(stripslashes($_GET['q'])).'</category>' . "\r\n";
$xmlData .= '	<lastBuildDate>'.date("D, d M Y H:i:s T", time()).'</lastBuildDate>' . "\r\n"; 
// loop through each video in the list and display it for design purposes
	$i = 0;
	
	foreach ($videos as $video) {
		$url = $config['website_url'].'video/'.$video['id'].'/'.SeoTitleEncode($video['title']).'.html';
		$file = 'http://www.youtube.com/v/'.$video['id'];
		
		foreach($video['thumbnail'] as $thumb_key => $thumb_val ) {
			if ( stripos($thumb_val, 'http://') === false ) {
				$video['thumbnail'][$thumb_key] = $config['website_url'].$thumb_val;
			}
		}
		
		$thumb = $video['thumbnail'][3];
		
		
		
		$screenshots = '<a href="'.$url.'"><img src="'.$video['thumbnail'][1].'" border="0" style="margin-right:5px; float:left;width:120px;height:90px;"><img src="'.$video['thumbnail'][2].'" border="0" style="margin-right:5px; float:left;width:120px;height:90px;"><img src="'.$video['thumbnail'][3].'" border="0" style="margin-right:5px; float:left;width:120px;height:90px;"></a>';
		$keywords	= "";		
		foreach($video['keywords'] as $video_k) {			
			$keywords	.= '<a href="'.$config['website_url'].$video_k.'/">'.$video_k.'</a>&nbsp;';		
		}
		$xmlData .= '    <item>' . "\r\n";
		$xmlData .= '        <guid isPermaLink="false"><![CDATA[' . $url . ']]></guid>' . "\r\n";
		$xmlData .= '        <title>' . htmlspecialchars($video['title']) . '</title>' . "\r\n";
		$xmlData .= '        <description><![CDATA[<div id="description">' . htmlspecialchars($video['description']) . '</div><br clear="all"><div id="screenshots">'.$screenshots.'</div><br clear="all"><br><div id="tags">Tags:&nbsp;'.$keywords.'</div>]]></description>' . "\r\n";
		
		if ( $video['uploaded'] != '' ) {
		$xmlData .= '		<pubDate>'.date("D, d M Y H:i:s T", strtotime($video['uploaded'])).'</pubDate>' . "\r\n";
		} else if ( $video['updated'] != '' ) {
		$xmlData .= '		<pubDate>'.date("D, d M Y H:i:s T", strtotime($video['updated'])).'</pubDate>' . "\r\n";
		}
		//$xmlData .= '        <thumb><![CDATA[' . $thumb . ']]></thumb>' . "\r\n";
		$xmlData .= '    </item>' . "\r\n";
	}
$xmlData .= '</channel>' . "\r\n";
$xmlData .= '</rss>' . "\r\n";

//header('Content-type: text/xml');
header("Content-Type: application/xml;charset=utf-8"); 
header('Content-disposition: inline; filename=rss.xml');
echo $xmlData;
?>