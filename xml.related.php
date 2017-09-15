<?php
include_once("init.php");
include_once("includes/class/Category.inc.php");

$config['list_per_page']   = 6;
$video_id	= isset($_GET['vid']) ? urldecode($_GET['vid']) : '';

$video_detail	= YT_GetDetail($video_id);


$data_videos 	= YT_ListByRelated($video_id, 1, $config['list_per_page']);

$total_videos	= YT_Total_Videos($data_videos);
$videos			= YT_Videos($data_videos);


// ################# Start getting Youtube GData ##############################

$xmlData  = '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">' . "\r\n";
$xmlData .= '<channel>' . "\r\n";

	if($video_id != "") {
		
		$videoID = $video_id;
		$videoTitle = $video_detail['title'];
		
		if ( $is_mbstring_enabled ) {
			$videoDescription = mb_substr($video_detail['description'],0,100, 'utf-8');
		} else {
			$videoDescription = substr($video_detail['description'],0,100);
		}
	
		$videoAuthor = $video_detail['uploader'];
		$videoDuration = $video_detail['duration'];
		$url = $config['website_url'].'video/'.$videoID.'/watch.html';
		$file = "http://www.youtube.com/watch?v=".$videoID;
		$thumb = 'http://i.ytimg.com/vi/'.$videoID.'/0.jpg';

		$xmlData .= '    <item>' . "\r\n";
		$xmlData .= '        <title><![CDATA[' . $videoTitle . ']]></title>' . "\r\n";
		$xmlData .= '        <link><![CDATA[' . $file . ']]></link>' . "\r\n";
		$xmlData .= '        <description><![CDATA[' . str_truncate($videoDescription,100) . ']]></description>' . "\r\n";
		$xmlData .= '        <media:content url="' . htmlspecialchars($file) . '" type="video/x-flv" duration="'.$videoDuration.'"/>' . "\r\n";
		$xmlData .= '        <media:credit role="author"><![CDATA['.$videoAuthor.']]></media:credit>' . "\r\n";
		$xmlData .= '        <media:thumbnail url="' . $thumb . '"/>' . "\r\n";
		$xmlData .= '    </item>' . "\r\n";
	}

	// loop through each video in the list and display it for design purposes
	foreach ($videos as $video) {
		if ( !$video['is_filtered'] ) {
			$url = $config['website_url'].'video/'.$video['id'].'/'.SeoKeywordEncode($video['title']).'.html';
			$file = "http://www.youtube.com/watch?v=".$video['id'];
			$thumb = $video['thumbnail'][0];

			$xmlData .= '    <item>' . "\r\n";
			$xmlData .= '        <link><![CDATA[' . $url . ']]></link>' . "\r\n";
			$xmlData .= '        <title><![CDATA[' . $video['title'] . ']]></title>' . "\r\n";
			$xmlData .= '        <description><![CDATA[' . str_truncate($video['description'], 100) . ']]></description>' . "\r\n";
			$xmlData .= '        <media:content url="' . htmlspecialchars($file) . '" type="video/x-flv" duration="'.$video['duration'].'"/>' . "\r\n";
			$xmlData .= '        <media:credit role="author"><![CDATA['.$video['author'].']]></media:credit>' . "\r\n";
			$xmlData .= '        <media:thumbnail url="' . $thumb . '"/>' . "\r\n";
			$xmlData .= '    </item>' . "\r\n";
		}
	}

$xmlData .= '</channel>' . "\r\n";
$xmlData .= '</rss>' . "\r\n";

header('Content-type: text/xml');
header('Content-disposition: inline; filename=playlist.xml');
echo $xmlData;
?>