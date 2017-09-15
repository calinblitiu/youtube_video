<?php
include_once "../init.php";
$config['list_per_page']   = 48; 
$feed_title = removeDash($_GET['fid']);
$feed_title = ucwords($feed_title);

// ################# Start getting Youtube GData ##############################
$data_videos 	= YT_ListByFeed($_GET['fid']);

$total_videos	= isset($data_videos['feed']['openSearch:totalResults']) ? $data_videos['feed']['openSearch:totalResults'] : 0;
$videos			= YT_Videos($data_videos);
// ################# Start parse xml attributes manually ######################

$xmlData  = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
$xmlData .= '<?xml-stylesheet type="text/css" href="'.$config['website_url'].'rss/rss.css" ?>'."\r\n";
$xmlData .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\r\n";
$xmlData .= '<channel>' . "\r\n";
$xmlData .= '	<title>'.$config["website_name"].' - '.urldecode(stripslashes($feed_title)).' videos</title>' . "\r\n"; 
$xmlData .= '	<link>'.$config['website_url'].'feed/'.$_GET["fid"].'.html</link>' . "\r\n"; 
$xmlData .= '	<description>'.$config["website_name"].' - '.urldecode(stripslashes($feed_title)).' videos</description>' . "\r\n"; 
$xmlData .= '	<language>'.$config["web_default_language"].'</language>' . "\r\n"; 
$xmlData .= '	<copyright>Copyright '.$config["website_name"].'</copyright>' . "\r\n"; 
$xmlData .= '	<lastBuildDate>'.date("D, d M Y H:i:s T", time()).'</lastBuildDate>' . "\r\n"; 

// loop through each video in the list and display it for design purposes
	
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
		$xmlData .= '        <description><![CDATA[<div id="description">' . htmlspecialchars($video['description']) . '</div><br clear="all"><div id="screenshots">'.$screenshots.'</div><br clear="all"><br><div id="tags">Tags: '.$keywords.'</div>]]></description>' . "\r\n";
		//$xmlData .= '        <thumb>' . $thumb . '</thumb>' . "\r\n";
		$xmlData .= '    </item>' . "\r\n";
	}

$xmlData .= '</channel>' . "\r\n";
$xmlData .= '</rss>' . "\r\n";

header("Content-Type: application/xml;charset=utf-8"); 
header('Content-disposition: inline; filename=prismotube.xml');
echo $xmlData;
?>