<?

// Supported: Youtube, Google, Metacafe, Current, Eyespot, Dailymotion, Myspace, Break

// HTML Hyperlink-related insert constants
define("HLINK_TEMPLATE","<a href=\"[X1]\">[X2]</a>");
define("HLINK_LINK_TITLE","Click here to download the .flv video file!");

// Youtube-related constants - Don't touch this!
define("YOUTUBE_API2_METHOD_GET_VIDEO_URL","http://www.youtube.com/api2_rest?&method=youtube.videos.get_video_token&video_id=[X]");
define("YOUTUBE_GET_VIDEO_URL","http://www.youtube.com/get_video?video_id=[X1]&t=[X2]");


function f_get_headers($vurl)
{
$fhdl=fopen(trim($vurl),'r');
$vret=$http_response_header;
fclose($fhdl);
return $vret;
}

function GetDownloadLink() {

	if(isset($_REQUEST['id'])) {


		$VideoID = $_REQUEST['id'];

		if ($VideoID != "") {
			$vurl=str_replace("[X]",$VideoID,YOUTUBE_API2_METHOD_GET_VIDEO_URL);
			$pagecontent = file_get_contents($vurl);
			if (preg_match("/t[ ]*\>([^\<]+)\<[ ]*\/[ ]*t/i",$pagecontent,$matches));
			{
				$T_ID = trim($matches[1]);
				$VideoURL = str_replace("[X1]",$VideoID,YOUTUBE_GET_VIDEO_URL);
				$VideoURL = str_replace("[X2]",$T_ID,$VideoURL);

		    }
		}
		return $VideoURL;

	}
}
?>