<?php

$is_native_json	= false;
if ( function_exists('json_decode') ) {
	$is_native_json	= true;
} else {
	include_once("json.class.php");
	$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
}

$is_mbstring_enabled = false;
if ( function_exists('mb_substr') ) {
	$is_mbstring_enabled	= true;
} 

$is_gzip_enabled	= false;
if ( function_exists('gzfile') ) {
	$is_gzip_enabled	= true;
} 

function mb_truncate($string, $length = 80, $etc = '...', $charset='UTF-8', $break_words = false, $middle = false) {
	if ($length == 0)
        return '';
 
    if (strlen($string) > $length) {
        /*$length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length+1, $charset));
        }
        if(!$middle) {
            return mb_substr($string, 0, $length, $charset) . $etc;
        } else {
            return mb_substr($string, 0, $length/2, $charset) . $etc . mb_substr($string, -$length/2, $charset);
        }*/
		return mb_strimwidth($string, 0, $length, "...", 'UTF-8');
    } else {
        return $string;
    }
}

function sb_truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
	if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}

function str_truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false, $charset='UTF-8') {
	global $is_mbstring_enabled;
	
	if ( $is_mbstring_enabled ) {
		return mb_truncate($string, $length, $etc, $charset, $break_words, $middle);
	} else {
		return sb_truncate($string, $length, $etc, $break_words, $middle);
	}
}

function randomize_avatars($avatars) {
	$avatar_idx	= rand(0, count($avatars) - 1);
	$avatar_file	= $avatars[$avatar_idx];
	unset($avatars[$avatar_idx]);
	sort($avatars);
	return array($avatars, 'images/avatar/'.$avatar_file);
}

function get_avatar_files() {
	$avatars	= array();
	if ( !defined('BASE_PATH') ) {
		define("DS", DIRECTORY_SEPARATOR);
		$base_path	= str_replace(DS."lib", "", dirname(__FILE__));
		define("BASE_PATH", $base_path.DS);
	} 
	
	if ( !file_exists(BASE_PATH."cache".DS."data".DS."avatars_list.php") ) {
		$path 		= BASE_PATH."images".DS."avatar".DS;
		$dir_handle = @opendir($path) or die("Unable to open $path");
		$i = 0;
		while ($file = readdir($dir_handle)) 
		{
			if($file != "." && $file != ".." ) {
				$avatars[$i] = $file;
				$i++;
			}
			
		}
		closedir($dir_handle);
		
		$avatars_list = '<?php
$avatars = array(
';
	foreach($avatars as $avatar) {
		$avatars_list .= "'".$avatar."',";
	}
$avatars_list .= '
);
?>';
		
		$cfile = BASE_PATH."cache".DS."data".DS."avatars_list.php";
		$fp = fopen($cfile, "w"); 
		fwrite($fp, $avatars_list); 
		fclose($fp); 
	} else {
		include(BASE_PATH."cache".DS."data".DS."avatars_list.php");
	}
	return $avatars;
}

function cs_strtolower($string) {
	global $is_mbstring_enabled;
	if ( $is_mbstring_enabled ) {
		return mb_strtolower($string, 'UTF-8');
	} else {
		return strtolower($string);
	} 
}

function url_post_contents($req, $timeout = 10) {
	global $config;
	$cache_file = BASE_PATH.$config['xml_cache_dir'] . md5($req) . ".txt";
  
	$html	= "";
	$is_get_html	= false;
	if ( !file_exists($cache_file) ) {
		// get html contents
		$is_get_html	= true;
	} else { 
		$mtime	= time() - filemtime($cache_file);
		if ( $time_exists > $config['xml_cache_timeout'] ) {
			// get html contents
			$is_get_html	= true;
		} else {
			$html	= file_get_contents($cache_file);
		}
	}
	
	if ( $is_get_html ) {
		if ( function_exists('curl_init') ) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_ENCODING , '');
			$html = curl_exec($ch);
			if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != '200') {
				$html = "";
			}
			curl_close($ch);
		
			
		} else if ( function_exists('fsockopen') ) {
			$datas_url	= parse_url($req);
			
			$port		= (!isset($datas_url['port']) ? 80 : $datas_url['port']);
			
			$fp = fsockopen($datas_url['host'], $port, $errno, $errstr, $timeout);
			if ($fp) {
				if ( isset($datas_url['query']) && $datas_url['query'] != '' ) {
					$datas_url['path'] = $datas_url['path'] ."?". $datas_url['query'];
				}
				$out = "POST ".$datas_url['path']." HTTP/1.1\r\n";
				$out .= "Host: ".$datas_url['host']."\r\n";
				$out .= "Connection: Close\r\n\r\n";
				if ( isset($datas_url['query']) && $datas_url['query'] != '' ) {
					$out .= "Content-length: ".strlen($datas_url['query'])."\r\n\r\n"; 
					$out .= $datas_url['query']."\r\n\r\n";
				}
				
				fwrite($fp, $out);
				$is_ok	= false;
				$header	= "";
				do // loop until the end of the header
				{
					$header .= fgets ( $fp, 128 );
					if ( strpos($header, "200") !== false ) {
						$is_ok	= true;
					}

				} while ( strpos ( $header, "\r\n\r\n" ) === false );
				
				if ( $is_ok ) {
					while (!feof($fp)) {
						$html .= fgets($fp, 128);
					}
				}
				$htmls = explode("\n", $html);
			
				fclose($fp);
			}
		} else {
			$html =  file_get_contents($url);
		}
		
		if ( $html && trim($html) != '' ) {
			if ( is_writable($cache_file) ) {
				$handle = fopen($cache_file, "w");
				fwrite($handle, $html); 
				fclose($handle);
			}
		}
	}
	
	return $html;
}

function YT_ListByKeyword($keyword, $page=1, $orderby = "", $is_search = false, $time = "") {
	global $config, $_COOKIE, $is_native_json;
	
	$start_index = ($page - 1) * $config['list_per_page'] + 1;
	$reqExtra = "";
	
	if ( trim($orderby) == "" ) {
		$reqExtra .= "&orderby=".$config["sort_videos_by"];
	} else {
		$reqExtra .= "&orderby=".$orderby;
	}
	
	if ( $time != '' ) {
		$reqExtra .= "&time=".$time;
	}
	
	if ( $is_search ) {
		if ( trim($config['search_term']) != '' ) {
		$keyword	= $keyword ." " .$config['search_term'];
		}
		
		if ( $config['default_filter_value'] == 'on' || ( isset($_COOKIE['filter']) && $_COOKIE['filter'] == 'on' ) ) {
			$reqExtra .= "&safeSearch=strict";
		} 
	} else {
		if ( $config['default_filter_value'] == 'on' ) {
			$reqExtra .= "&safeSearch=strict";
		}
	}

	$keyword = urlencode($keyword);
	$language_param = ""; 
	
	if ( $config["video_language_specific"] != 'all' ) {
		$language_param = "&lr=".$config["video_language_specific"];
	}

	if ( defined('IS_MOBILE') && IS_MOBILE ) {
		// $req = "http://gdata.youtube.com/feeds/mobile/videos?v=3&alt=jsonc&format=1".$language_param.
		// 	"&start-index={$start_index}".
		// 	"&max-results={$config['list_per_page']}".$reqExtra.$config['youtube_extra_params']."&q={$keyword}";
		$req = "http://gdata.youtube.com/feeds/mobile/videos?v=2&alt=jsonc&format=1".$language_param.
			"&start-index={$start_index}".
			"&max-results={$config['list_per_page']}".$reqExtra.$config['youtube_extra_params']."&q={$keyword}";
	} else {
		// $req = "http://gdata.youtube.com/feeds/api/videos?v=2&alt=jsonc&format=5".$language_param.
		// 	"&start-index={$start_index}".
		// 	"&max-results={$config['list_per_page']}".$reqExtra.$config['youtube_extra_params']."&q={$keyword}";

		$req = "https://www.googleapis.com/youtube/v3/search?part=id&q=messi&type=video&key=AIzaSyB2YP4Uh1ZaLBZ4s5Idz9QKfzEvEcHJKpM";
	}

	
	
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	//echo '<!-- '.$req.' -->';


	if($config['xml_cache_enable']) {
		//$data	= YT_JSON_RespCache($req);
		$data 	= REST_Request($req);
	}
	else {
		$data 	= REST_Request($req);
	}
	
	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}
	var_dump($data_videos);
	return $data_videos;
}

function YT_Video_Response($video_id) {
	global $is_native_json;
	
	$req	= "http://gdata.youtube.com/feeds/api/videos/{$video_id}/responses?v=2&alt=jsonc";
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}
	
	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}
	
	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}
	return $data_videos;
}

function YT_ListByFeed($feed, $time) {
	global $config, $feeds_array, $is_native_json;
	
	$reqExtra = "";
	if($time != "") {
		$reqExtra .= "?v=2&alt=jsonc&time=".$time;
		$reqExtra .= "&max-results=".$config['list_on_feed_page'];
	}else{
		$reqExtra .= "?v=2&alt=jsonc&max-results=".$config['list_on_feed_page'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$reqExtra .= "&safeSearch=strict";
	}

	$language_param = ""; 
	if ( $config["video_language_specific"] != 'all' ) {
		$language_param = "&lr=".$config["video_language_specific"];
	}

	$req = $feeds_array[$feed].$reqExtra."&format=5".$language_param.$config['youtube_extra_params'];
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}

	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}

	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}
	
	return $data_videos;
}

function YT_ListRelatedVideos($feed_url, $page = 1, $max_result = 10 ) {
	global $config;
	$start_index = ($page - 1) * $max_result + 1;
	
	$language_param = "";
	$reqExtra = "";

	if ( $config["video_language_specific"] != 'all' ) {
		$language_param = "&lr=".$config["video_language_specific"];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$reqExtra .= "&safeSearch=strict";
	}
	
	if ( defined('IS_MOBILE') && IS_MOBILE ) {
	$req	= $feed_url."?start-index={$start_index}".$reqExtra."&max-results={$max_result}&format=1".$language_param.$config['youtube_extra_params'];
	} else {
	$req	= $feed_url."?start-index={$start_index}".$reqExtra."&max-results={$max_result}&format=5".$language_param.$config['youtube_extra_params'];
	}
	
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if($config['xml_cache_enable']) {
		$data_xml = YT_GetXMLRespCache($req);
	}
	else {
		$data_xml = REST_Request($req);
	}
	return XML_unserialize($data_xml);
}


function YT_GetDetail($video_id) {
	global $config, $is_native_json;

	if ( defined('IS_MOBILE') && IS_MOBILE ) {
		$req = "http://gdata.youtube.com/feeds/mobile/videos/{$video_id}?v=2&alt=jsonc";
	} else {
		$req = "http://gdata.youtube.com/feeds/api/videos/{$video_id}?v=2&alt=jsonc";
	}
	
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req	.= "&safeSearch=strict";
	}

	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}

	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}
	$video_data	= isset($data_videos['data']) ? $data_videos['data'] : array();
	return $video_data;
}

function YT_GetComment($video_id) {
	global $config, $is_native_json;
	
	$req = "http://gdata.youtube.com/feeds/api/videos/{$video_id}/comments?v=2&alt=json";
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}
	
	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}
	
	if ( $is_native_json ) {
		$data_comments = json_decode($data, true);
	} else {
		global $json;
		$data_comments	= $json->decode($data);
	}

	return $data_comments;
}

function YT_User_Data($user_data) { 
	$user_datas	= array();
	$user_datas['gender']		= isset($user_data['entry']['yt$gender']['$t']) ? $user_data['entry']['yt$gender']['$t'] : '';
	$user_datas['published']	= isset($user_data['entry']['published']['$t']) ? $user_data['entry']['published']['$t'] : 0;
	$user_datas['title']	= isset($user_data['entry']['title']['$t']) ? $user_data['entry']['title']['$t'] : '';
	$user_datas['name']		= isset($user_data['entry']['author'][0]['name']['$t']) ? $user_data['entry']['author'][0]['name']['$t'] : '';
	$user_datas['username']	= isset($user_data['entry']['yt$username']['$t']) ? $user_data['entry']['yt$username']['$t'] : '';
	
	$user_datas['books']	= isset($user_data['entry']['yt$books']['$t']) ? $user_data['entry']['yt$books']['$t'] : '';
	$user_datas['hobbies']	= isset($user_data['entry']['yt$hobbies']['$t']) ? $user_data['entry']['yt$hobbies']['$t'] : '';
	$user_datas['location']	= isset($user_data['entry']['yt$location']['$t']) ? $user_data['entry']['yt$location']['$t'] : '';
	$user_datas['movies']	= isset($user_data['entry']['yt$movies']['$t']) ? $user_data['entry']['yt$movies']['$t'] : '';
	$user_datas['music']	= isset($user_data['entry']['yt$music']['$t']) ? $user_data['entry']['yt$music']['$t'] : '';
	
	$user_datas['description']	= isset($user_data['entry']['yt$description']['$t']) ? $user_data['entry']['yt$description']['$t'] : '';
	$user_datas['hometown']	= isset($user_data['entry']['yt$hometown']['$t']) ? $user_data['entry']['yt$hometown']['$t'] : '';
	
	$user_datas['thumbnail']	= isset($user_data['entry']['media$thumbnail']['url']) ? $user_data['entry']['media$thumbnail']['url'] : '';
	$user_datas['viewCount']	= isset($user_data['entry']['yt$statistics']['viewCount']) ? $user_data['entry']['yt$statistics']['viewCount'] : 0;
	$user_datas['subscriberCount']	= isset($user_data['entry']['yt$statistics']['subscriberCount']) ? $user_data['entry']['yt$statistics']['subscriberCount'] : 0;
	$user_datas['videoWatchCount']	= isset($user_data['entry']['yt$statistics']['videoWatchCount']) ? $user_data['entry']['yt$statistics']['videoWatchCount'] : 0;
	$user_datas['lastWebAccess']	= isset($user_data['entry']['yt$statistics']['lastWebAccess']) ? $user_data['entry']['yt$statistics']['lastWebAccess'] : 0;
	
	return $user_datas;
}

function YT_GetUserProfile($user) {
	global $config, $is_native_json;
	
	$req = "http://gdata.youtube.com/feeds/api/users/{$user}?v=2&alt=json";
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}
	
	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}

	if ( $is_native_json ) {
		$user_data = json_decode($data, true);
	} else {
		global $json;
		$user_data	= $json->decode($data);
	}
	
	$user_datas	= YT_User_Data($user_data);

	return $user_datas;
}

function YT_GetUserUpload($user, $page=1, $max_result=10) {
	global $config, $is_native_json;
	
	$start_index = ($page - 1) * $max_result + 1;
	
	if ( defined('IS_MOBILE') && IS_MOBILE ) {
		$req = "http://gdata.youtube.com/feeds/api/users/{$user}/uploads?v=2&alt=jsonc&format=1&start-index={$start_index}".
		 "&max-results={$max_result}".$config['youtube_extra_params'];
	} else {
		$req = "http://gdata.youtube.com/feeds/api/users/{$user}/uploads".
		 "?v=2&alt=jsonc&start-index={$start_index}".
		 "&max-results={$max_result}".
		 "&orderby=updated&format=5".$config['youtube_extra_params'];
	}
	
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}

	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}

	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}

	return $data_videos;
}

function YT_GetUserFavorites($user, $page=1, $max_result=10) {
	global $config, $is_native_json;
	
	$start_index = ($page - 1) * $max_result + 1;

	$language_param = ""; 
	if ( $config["video_language_specific"] != 'all' ) {
		$language_param = "&lr=".$config["video_language_specific"];
	}

	$req = "http://gdata.youtube.com/feeds/api/users/{$user}/favorites".
		 "?v=2&alt=jsonc&start-index={$start_index}".
		 "&max-results={$max_result}".
		 "&orderby=updated&format=5".$language_param.$config['youtube_extra_params'];
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}
	
	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}

	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}

	return $data_videos;
}

function YT_GetUserPlaylists($user, $page=1, $max_result=10) {
	global $config, $is_native_json;
	
	$start_index = ($page - 1) * $max_result + 1;

	$language_param = ""; 
	if ( $config["video_language_specific"] != 'all' ) {
		$language_param = "&lr=".$config["video_language_specific"];
	}

	$req = "http://gdata.youtube.com/feeds/api/users/{$user}/playlists".
		 "?v=2&alt=jsonc&start-index={$start_index}".
		 "&max-results={$max_result}&format=5".$language_param.$config['youtube_extra_params'];
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}
		
	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}

	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}

	return $data_videos;
}

function YT_GetUserPlaylistsEntry($id, $page=1, $max_result=10) {
	global $config, $is_native_json;

	$start_index = ($page - 1) * $max_result + 1;
	$language_param = ""; 
	if ( $config["video_language_specific"] != 'all' ) {
		$language_param = "&lr=".$config["video_language_specific"];
	}

	$req = "http://gdata.youtube.com/feeds/api/playlists/{$id}".
		 "?v=2&alt=jsonc&start-index={$start_index}".
		 "&max-results={$max_result}&format=5".$language_param.$config['youtube_extra_params'];
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}
	
	if($config['xml_cache_enable']) {
	$data = YT_JSON_RespCache($req);
	}
	else {
	$data = REST_Request($req);
	}

	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}

	return $data_videos;
}

function YT_GetUserPlaylistsEntry_XML($id, $page=1, $max_result=10) {
	global $config, $is_native_json;
	
	$max_result	= $config['list_per_page'];
	$start_index = ($page - 1) * $max_result + 1;
	$language_param = ""; 
	if ( $config["video_language_specific"] != 'all' ) {
		$language_param = "&lr=".$config["video_language_specific"];
	}

	$req = "http://gdata.youtube.com/feeds/api/playlists/{$id}".
	 "?v=2&alt=jsonc&start-index={$start_index}".
	 "&max-results={$max_result}&format=5".$language_param.$config['youtube_extra_params'];
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}
	
	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}
	
	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}

	return $data_videos;
}

function YT_GetXMLRespCache($req) {
	global $config;
	$filename 		= BASE_PATH.$config['xml_cache_dir'] . md5($req) . ".txt";
	
	if ( !file_exists($filename) ) {
		$xml 	= REST_Request_Common($req);
		WriteToFile($filename, $xml);
	} else {
		$time_cached	= time() - filemtime($filename);
		if ( $time_cached < $config['xml_cache_timeout'] ) {
			$xml	= file_get_contents($filename);
		} else {
			$xml 	= REST_Request_Common($req);
			WriteToFile($filename, $xml);
		}
	}
	return $xml;
}

function YT_JSON_RespCache($req) {
	global $config, $is_gzip_enabled;
	$yt_timeout_txt		= BASE_PATH.DS."cache".DS."data".DS."youtube_reset_quota_timeout";
	
	if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
		$filename 		= BASE_PATH.$config['xml_cache_dir'] . md5($req) . ".gz";
	} else {
		$filename 		= BASE_PATH.$config['xml_cache_dir'] . md5($req) . ".txt";
	}
	
	$is_browser			= is_browsers_user_agent();
	
	if ( !file_exists($filename) && $is_browser ) {
	
		$data 	= REST_Request($req);
		//WriteToFile($filename, $data);
		
	} else if ( !file_exists($filename) && !$is_browser ) {
		$is_allowable = true;
		if ( file_exists($yt_timeout_txt) ) {
			$yt_timeout	= file_get_contents($yt_timeout_txt);
			if ( ( time() - $yt_timeout ) < $config['youtube_reset_quota_timeout'] ) {
				$is_allowable = false;
			}
		}
		
		if ( $is_allowable ) {
			$data 	= REST_Request($req);
			if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
				$datas		= gzfile($filename);
				$json_data	= implode('', $datas);
			} else {
				$json_data	= $data;
			}
			
			$yt_response	= yt_api_response($req, $data, $json_data);
			if ( $yt_response == 'too_many_recent_calls' ) {
				WriteToFile($yt_timeout_txt, time());
				
				header("Location: ".$config['website_url']."error.php?ref=too_many_recent_calls");
				exit(0);
			}
		} else {
			$_SESSION['yt_response'] = lang('youtube_quota_violation');
			header("Location: ".$config['website_url']."error.php?ref=youtube_quota_violation");
			exit(0);
		}
	} else if ( file_exists($filename) && !$is_browser ) {
		if ( $config['cache_storage_format'] != 'auto' && $config['cache_storage_format'] != 'gzip' ) {
			$data	= file_get_contents($filename);	
		}
	} else {
		$time_cached	= time() - filemtime($filename);
		if ( $time_cached < $config['xml_cache_timeout'] ) {
		
			if ( $config['cache_storage_format'] != 'auto' && $config['cache_storage_format'] != 'gzip' ) {
				$data	= file_get_contents($filename);	
			}
			
		} else {
			$data 	= REST_Request($req);
			//WriteToFile($filename, $data);
		}
	}

	if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
		$datas		= gzfile($filename);
		$json_data	= implode('', $datas);
	} else {
		$json_data	= $data;
	}

	$yt_response	= yt_api_response($req, $data, $json_data);
	if ( $yt_response == 'too_many_recent_calls' && $is_browser ) {
		$time_cached	= time() - filemtime($filename);
		if ( $time_cached > $config['youtube_reset_quota_timeout'] ) {
			$data 	= REST_Request($req);
			if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
				$datas		= gzfile($filename);
				$json_data	= implode('', $datas);
			} else {
				$json_data	= $data;
			}
		}
	}

	return $json_data;
}

function YT_ListByRelated($video_id, $page=1, $max_result = 10) {
	global $config, $_COOKIE, $is_native_json;

	$start_index = ($page - 1) * $max_result + 1;

	$language_param = ""; 
	if ( $config["video_language_specific"] != 'all' ) {
		$language_param = "&lr=".$config["video_language_specific"];
	}

	if ( defined('IS_MOBILE') && IS_MOBILE ) {
		$req = "http://gdata.youtube.com/feeds/mobile/videos/{$video_id}/related?v=2&alt=jsonc&format=1".$language_param.
			"&start-index={$start_index}".
			"&max-results={$max_result}".$config['youtube_extra_params'];
	} else {
		$req = "http://gdata.youtube.com/feeds/api/videos/{$video_id}/related?v=2&alt=jsonc&format=5".$language_param.
			"&start-index={$start_index}".
			"&max-results={$max_result}".$config['youtube_extra_params'];
	}
	if ( trim($config['yt_developer_key']) != '' ) {
		$req	.= "&key=".$config['yt_developer_key'];
	}
	
	if ( $config['default_filter_value'] == 'on' ) {
		$req .= "&safeSearch=strict";
	}
	
	if($config['xml_cache_enable']) {
		$data = YT_JSON_RespCache($req);
	}
	else {
		$data = REST_Request($req);
	}
	
	if ( $is_native_json ) {
		$data_videos = json_decode($data, true);
	} else {
		global $json;
		$data_videos	= $json->decode($data);
	}
	return $data_videos;
}

function REST_Request_Common($url) { 
	global $config, $is_gzip_enabled;
		
	if ( !defined("BASE_PATH") ) {
		$base_path	= str_replace(DS."lib", "", dirname(__FILE__) );
		$pt_path	= $base_path.DS;
	} else {
		$pt_path	= BASE_PATH;
	}
	
	$debug_file	= $pt_path.$config['logs_cache_dir']."debug".md5($config['license_key']).".txt";
	
	if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
		$filename 		= $pt_path.$config['xml_cache_dir'] . md5($url) . ".gz";
	} else {
		$filename 		= $pt_path.$config['xml_cache_dir'] . md5($url) . ".txt";
	}
	
	$timeout	= 10;
	$xml		= "";
	$errno		= 0;
	
	if ( $config['debug_mode_enabled'] == 'true' ) {

		if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
			$fh = @fopen($debug_file, 'a');
			@fwrite($fh, $url."\n");
			@fclose($fh);
		}
	}

	$is_curl_enabled	= false;
	if ( function_exists('curl_exec') ) {
		$is_curl_enabled	= true;
	}

	for( $i = 1; $i <= 3; $i++ ) {
		$time_start	= microtime();
		
		if ( $is_curl_enabled ) {
	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
		
			if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Prismotube (gzip)', 'Accept-Encoding: gzip'));
			} else {
				curl_setopt($ch, CURLOPT_ENCODING , '');
			}
			
			$xml = curl_exec($ch);
			if(curl_errno($ch))
			{

				if ( $config['debug_mode_enabled'] == 'true' ) {
					
					$time_end	= microtime();
					$errno		= curl_errno($ch);
					$errstr		= curl_error($ch);
					
					if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
						$fh = @fopen($debug_file, 'a');
						@fwrite($fh, "\ntime start: " .$time_start. " -- " .$url. " -- " .$errno." - ".$errstr. " -- Time end: ".$time_end.' Cache file:  '.md5($url)."\n");
						@fclose($fh);
					}
				}
				$xml	= "";
			} else {
					
				if ( $is_gzip_enabled ) {
					$fh = @fopen($filename, 'w+');
					@fwrite($fh, $xml);
					@fclose($fh);
					$datas		= gzfile($filename);
					$xml	= implode('', $datas);
				} 
			
			}
			curl_close($ch);
			
			if ( $xml ) {
				break;
			}

		}
		else {
			//$xml =  file_get_contents($url);
			$datas_url	= parse_url($url);
				
			$port		= (!isset($datas_url['port']) ? 80 : $datas_url['port']);
			
			$fp = fsockopen($datas_url['host'], $port, $errno, $errstr, $timeout);
			if ($fp) {
				if ( isset($datas_url['query']) && $datas_url['query'] != '' ) {
					$datas_url['path'] = $datas_url['path'] ."?". $datas_url['query'];
				}
				$out = "GET ".$datas_url['path']." HTTP/1.1\r\n";
				$out .= "Host: ".$datas_url['host']."\r\n";
				
				if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
					$out .= "User-Agent: Prismotube (gzip)\r\n";
					$out .= "Accept-Encoding: gzip\r\n";
				}
				
				$out .= "Connection: Close\r\n\r\n";
				if ( isset($datas_url['query']) && $datas_url['query'] != '' ) {
					$out .= "Content-length: ".strlen($datas_url['query'])."\r\n\r\n"; 
					$out .= $datas_url['query']."\r\n\r\n";
				}
				fwrite($fp, $out);
				
				while (!feof($fp)) {
					$xml .= fgets($fp, 8192);
				}
				$xml	= parseHttpResponse($xml);
				fclose($fp);
				
				if ( $is_gzip_enabled ) {
					$fh = @fopen($filename, 'w+');
					@fwrite($fh, $xml);
					@fclose($fh);
					$datas		= gzfile($filename);
					$xml		= implode('', $datas);
				}
				
				if ( $xml ) {
					break;
				}
			} else {
				if ( $config['debug_mode_enabled'] == 'true' ) {
					$time_end	= microtime();
					
					if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
						$fh = @fopen($debug_file, 'a');
						@fwrite($fh, "\ntime start: " .$time_start. " -- " .$url. " -- " .$errno." - ".$errstr. " -- Time end: ".$time_end.' Cache file:  '.md5($url)."\n");
						@fclose($fh);
					}
				}
			}
		}
	}
	
	if( !$config['xml_cache_enable'] && file_exists($filename) ) {
		unlink($filename);
	}

	return $xml;
}

function REST_Request($url) { 
	global $config, $is_gzip_enabled, $is_native_json;
	
	if ( !defined("BASE_PATH") ) {
		$base_path	= str_replace(DS."lib", "", dirname(__FILE__) );
		$pt_path	= $base_path.DS;
	} else {
		$pt_path	= BASE_PATH;
	}
	
	$debug_file	= $pt_path.$config['logs_cache_dir']."debug".md5($config['license_key']).".txt";

	if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
		$filename 		= $pt_path.$config['xml_cache_dir'] . md5($url) . ".gz";
	} else {
		$filename 		= $pt_path.$config['xml_cache_dir'] . md5($url) . ".txt";
	}
	
	$timeout	= 30;
	$xml		= "";
	$errno		= 0;
	$errstr		= "";
	
	if ( $config['debug_mode_enabled'] == 'true' ) {

		if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
			$fh = @fopen($debug_file, 'a');
			@fwrite($fh, $url." -- ".$_SERVER['HTTP_USER_AGENT']."\n");
			@fclose($fh);
		}
	}

	$is_curl_enabled	= false;
	if ( function_exists('curl_exec') ) {
		$is_curl_enabled	= true;
	}
	
	for( $i = 1; $i <= 3; $i++ ) {
		$time_start	= microtime();
		
		if ( $is_curl_enabled ) {
	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
		
			if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Prismotube (gzip)', 'Accept-Encoding: gzip'));
			} else {
				curl_setopt($ch, CURLOPT_ENCODING , '');
			}
			
			$xml = curl_exec($ch);
			
			if(curl_errno($ch))
			{

				if ( $config['debug_mode_enabled'] == 'true' ) {
					
					$time_end	= microtime();
					$errno		= curl_errno($ch);
					$errstr		= curl_error($ch);
					
					if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
						$fh = @fopen($debug_file, 'a');
						@fwrite($fh, "\ntime start: " .$time_start. " -- " .$url. " -- " .$errno." - ".$errstr. " -- Time end: ".$time_end.' Cache file:  '.md5($url)." -- ".$_SERVER['HTTP_USER_AGENT']."\n");
						@fclose($fh);
					}
				}
				$xml	= "";
			} else {
				
				if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
					$fh = @fopen($filename, 'w+');
					@fwrite($fh, $xml);
					@fclose($fh);
					$datas		= gzfile($filename);
					$json_data	= implode('', $datas);
				} else {
					$json_data	= $xml;
					WriteToFile($filename, $xml);
				}
				
				if ( $is_native_json ) {
					$data_videos = json_decode($json_data, true);
				} else {
					global $json;
					$data_videos	= $json->decode($json_data);
				}
	
				$errno		= curl_errno($ch);
				$errstr		= curl_error($ch);
				$xml		= yt_api_response($url, $xml, $json_data, $data_videos, $time_start, $errno, $errstr);
				
				
			}
			curl_close($ch);
			
			if ( trim($xml) != '' ) {
				break;
			}

		}
		else {
		
			$datas_url	= parse_url($url);
			$port		= (!isset($datas_url['port']) ? 80 : $datas_url['port']);
			
			$fp = fsockopen($datas_url['host'], $port, $errno, $errstr, $timeout);
			if ($fp) {
				if ( isset($datas_url['query']) && $datas_url['query'] != '' ) {
					$datas_url['path'] = $datas_url['path'] ."?". $datas_url['query'];
				}
				$out = "GET ".$datas_url['path']." HTTP/1.1\r\n";
				$out .= "Host: ".$datas_url['host']."\r\n";
				$out .= "Content-Type: application/json";
				
				if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
					$out .= "User-Agent: Prismotube (gzip)\r\n";
					$out .= "Accept-Encoding: gzip\r\n";
				}
				
				$out .= "Connection: Close\r\n\r\n";
				if ( isset($datas_url['query']) && $datas_url['query'] != '' ) {
					$out .= "Content-length: ".strlen($datas_url['query'])."\r\n\r\n"; 
					$out .= $datas_url['query']."\r\n\r\n";
				}
				fwrite($fp, $out);
				
				while (!feof($fp)) {
					$xml .= fgets($fp, 16384);
				}
				$xml	= parseHttpResponse($xml);
				fclose($fp);
				
				if ( $is_gzip_enabled ) {
					$fh = @fopen($filename, 'w+');
					@fwrite($fh, $xml);
					@fclose($fh);
					$datas		= gzfile($filename);
					$json_data	= implode('', $datas);
				} else {
					$json_data	= $xml;
				}
	
				if ( $is_native_json ) {
					$data_videos = json_decode($json_data, true);
				} else {
					global $json;
					$data_videos	= $json->decode($json_data);
				}
				
				if ( !is_array($data_videos) ) {
					
					if ( $config['debug_mode_enabled'] == 'true' ) {
						
						$time_end	= microtime();
						
						if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
							$fh = @fopen($debug_file, 'a');
							@fwrite($fh, "\ntime start: " .$time_start. " -- " .$xml . " -- " .$url. " -- " .$errno." - ".$errstr. " -- Time end: ".$time_end.' Cache file:  '.md5($url)." -- ".$_SERVER['HTTP_USER_AGENT']."\n");
							@fclose($fh);
						}
					}
					$xml	= "";
				}
				
				if ( $xml ) {
					break;
				}
			} else {
				if ( $config['debug_mode_enabled'] == 'true' ) {
					$time_end	= microtime();
					
					if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
						$fh = @fopen($debug_file, 'a');
						@fwrite($fh, "\ntime start: " .$time_start. " -- " .$url. " -- " .$errno." - ".$errstr. " -- Time end: ".$time_end.' Cache file:  '.md5($url)." -- ".$_SERVER['HTTP_USER_AGENT']."\n");
						@fclose($fh);
					}
				}
			}
		}
	}
	
	if( !$config['xml_cache_enable'] && file_exists($filename) ) {
		unlink($filename);
	} else {
		if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
			$datas		= gzfile($filename);
			$xml	= implode('', $datas);
		} else {
			$xml		= file_get_contents($filename);
		}
	}
	
	//var_dump($xml); echo $url."<br />";
	return $xml;
}

function validateHttpResponse($headers=null) {
	if (!is_array($headers) or count($headers) < 1) { return false; }
	switch(trim(strtolower($headers[0]))) {
		case 'http/1.0 100 ok':
		case 'http/1.0 200 ok':
		case 'http/1.1 100 ok':
		case 'http/1.1 200 ok':
			return true;
		break;
		}
	return false;
}

function parseHttpResponse($content=null) {
	if (empty($content)) { return false; }
	// split into array, headers and content.
	$hunks = explode("\r\n\r\n",trim($content));
	if (!is_array($hunks) or count($hunks) < 2) {
		return false;
		}
	$header  = $hunks[count($hunks) - 2];
	$body    = $hunks[count($hunks) - 1];
	$headers = explode("\n",$header);
	unset($hunks);
	unset($header);
	if (!validateHttpResponse($headers)) { return false; }
	if (in_array('Transfer-Coding: chunked',$headers)) {
	return trim(unchunkHttpResponse($body));
	} else {
	return trim($body);
	}
}

function unchunkHttpResponse($str=null) {
    if (!is_string($str) or strlen($str) < 1) { return false; }
    $eol = "\r\n";
    $add = strlen($eol);
    $tmp = $str;
    $str = '';
    do {
        $tmp = ltrim($tmp);
        $pos = strpos($tmp, $eol);
        if ($pos === false) { return false; }
        $len = hexdec(substr($tmp,0,$pos));
        if (!is_numeric($len) or $len < 0) { return false; }
        $str .= substr($tmp, ($pos + $add), $len);
        $tmp  = substr($tmp, ($len + $pos + $add));
        $check = trim($tmp);
        } while(!empty($check));
    unset($tmp);
    return $str;
}

function YT_Videos($data_videos, $type = '', $max_title_length = 12) {
	$videos	= array();
	global $config;
	
	if ( isset($data_videos['data']['items']) && count($data_videos['data']['items']) > 0 ) {
	
		if ( $type == 'playlists_entry' || $type == 'user_favorites' ) {
			$items	= array();
			foreach($data_videos['data']['items'] as $key => $item) {
				$items[$key] = $item['video'];
			}
		} else {
			$items	= $data_videos['data']['items'];
		}
		
		foreach($items as $key => $entries) {
			$video_id			= isset($entries['id']) ? $entries['id'] : 0;
			$duration			= isset($entries['duration']) ? $entries['duration'] : 0;
			$view_count			= isset($entries['viewCount']) ? $entries['viewCount'] : 0;
			$favorite_count		= isset($entries['favoriteCount']) ? $entries['favoriteCount'] : 0;
			$rating_num_raters	= isset($entries['ratingCount']) ? $entries['ratingCount'] : 0;
			$rating_average		= isset($entries['rating']) ? $entries['rating'] : 0;
			$video_description	= isset($entries['description']) ? $entries['description'] : '';
			$video_category		= isset($entries['category']) ? $entries['category'] : '';
			$tags				= isset($entries['tags']) ? $entries['tags'] : array();
			$uploader			= isset($entries['uploader']) ? $entries['uploader'] : '';
			$video_title		= isset($entries['title']) ? $entries['title'] : '';
			$sqDefault			= isset($entries['thumbnail']['sqDefault']) ? $entries['thumbnail']['sqDefault'] : '';
			$hqDefault			= isset($entries['thumbnail']['hqDefault']) ? $entries['thumbnail']['hqDefault'] : '';
			
			$is_filtered		= is_yt_thumbnail_filtered($entries);
			
			$data_thumbnails	= YT_Video_Thumbnails($video_id, $is_filtered);
			
			$rating_max	= 5;
		
			if ( substr($config["website_url"], -1, 1) == '/' ) { 
				$video_author_url	  = "profile/".$uploader;
			} else {
				$video_author_url  = "/profile/".$uploader;
			}
			
			if ( $type == 'playlists_entry' ) {
				$link_video	= $entries['link']['0 attr']['href'];
				if ( $link_video != '' ) {
					$datas_url	= parse_url($link_video);
					parse_str($datas_url['query']);
					$videos[$key]['id'] = $v;
				}
			}
		
			$videos[$key]['is_filtered']	= ( $is_filtered ) ? 1 : 0;
			$videos[$key]['author']			= $uploader;
			$videos[$key]['short_author']	= str_truncate($uploader, $max_title_length);
			
			$videos[$key]['sqDefault']		= $sqDefault;
			$videos[$key]['hqDefault']		= $hqDefault;
			$videos[$key]['id']				= $video_id;
			$videos[$key]['title']			= $video_title;
			$videos[$key]['short_title']	= str_truncate($video_title, 18);
			$videos[$key]['short_description']	= str_truncate(strip_tags($video_description), 300);
			$videos[$key]['description']	= htmlspecialchars($video_description);
			$videos[$key]['category']		= $video_category;
			$videos[$key]['author_url']		= $video_author_url;
			$videos[$key]['thumbnail']		= $data_thumbnails;
			$videos[$key]['duration']		= $duration;
			$videos[$key]['minute_format']	= setSecondsToMinute($duration);
			$videos[$key]['view_count']		= $view_count;
			$videos[$key]['favorite_count']		= $favorite_count;
			$videos[$key]['rating_num_raters']	= $rating_num_raters;
			$videos[$key]['rating_average']		= $rating_average;
			$videos[$key]['rating_avg']			= round(($rating_average / $rating_max ) * 100);
			$videos[$key]['favorite_count']		= $favorite_count;
			$videos[$key]['keywords'] 			= $tags;
			$videos[$key]['size']				= isset($entries['size']) ? $entries['size'] : 0;
			
			$videos[$key]['uploaded']			= isset($entries['uploaded']) ? $entries['uploaded'] : '';
			$videos[$key]['updated']			= isset($entries['updated']) ? $entries['updated'] : '';
		}
		
	}
	return $videos;
}

$yt_idx	= 1;
function YT_Video_Thumbnails($video_id, $is_filtered = false) {
	global $yt_idx;
	
	if ( !$is_filtered ) {
		if ( $yt_idx == 4 ) {
			$yt_idx = 1;
		}
		
		$thumbnails	= array();
		$thumbnails[0]	= 'http://i'.$yt_idx.'.ytimg.com/vi/'.$video_id.'/0.jpg';
		$thumbnails[1]	= 'http://i'.$yt_idx.'.ytimg.com/vi/'.$video_id.'/1.jpg';
		$yt_idx++;
		
		if ( $yt_idx == 4 ) {
			$yt_idx = 1;
		}
		
		$thumbnails[2]	= 'http://i'.$yt_idx.'.ytimg.com/vi/'.$video_id.'/2.jpg';
		$thumbnails[3]	= 'http://i'.$yt_idx.'.ytimg.com/vi/'.$video_id.'/3.jpg';
		$yt_idx++;
	} else {
		$image_filename		= 'disallowed_thumbnail.gif';
		$thumbnails	= array(
			0 => 'images/'.$image_filename,
			1 => 'images/'.$image_filename,
			2 => 'images/'.$image_filename,
			3 => 'images/'.$image_filename,
		);
	}
	return $thumbnails;
}

function YT_Total_Videos($data_videos) {
	$total_videos	= isset($data_videos['data']['totalItems']) ? $data_videos['data']['totalItems'] : 0;
	return $total_videos;
}

function is_yt_thumbnail_filtered($video_data) {
	global $config;
	$is_filtered	= false;
	
	if ( $config["keyword_filter_enabled"] == "true" ) {
	
		$video_title	= isset($video_data['title']) ? $video_data['title'] : '';
		$video_description	= isset($video_data['description']) ? $video_data['description'] : '';
		
		if ( !is_keyword_allowable($video_title) ) {
			$is_filtered = true;
		} else {
			if ( !is_keyword_allowable($video_description) ) {
				$is_filtered = true;
			} else {
				$video_tags	= isset($video_data['tags']) ? $video_data['tags'] : array();
				
				if ( count($video_tags) > 0 ) {
					foreach($video_tags as $tag) {
						if ( !is_keyword_allowable($tag) ) {
							$is_filtered = true;
							break;
						}
					}
				}
			}
		}
	}
			
	return $is_filtered;
}

function yt_api_response($url, $xml, $json_data, $data_videos = array(), $time_start = 0, $errno = 0, $errstr = '' ) {
	global $config;
	if ( !defined("BASE_PATH") ) {
		$base_path	= str_replace(DS."lib", "", dirname(__FILE__) );
		$pt_path	= $base_path.DS;
	} else {
		$pt_path	= BASE_PATH;
	}
	
	$debug_file	= $pt_path.$config['logs_cache_dir']."debug".md5($config['license_key']).".txt";
	
	$yt_data	= strtolower($json_data);
	$yt_response	= $xml;
	$yt_response2	= $xml;
	
	if ( !isset($data_videos['entry']['title']) && !isset($data_videos['data']['items']) && !isset($data_videos['data']['title'])  ) {
		
		if ( strpos($yt_data, 'too_many_recent_calls') !== false ) {
			$yt_response 	= lang('youtube_quota_violation');
			$yt_response2	= 'too_many_recent_calls';
		} else if ( strpos($yt_data, 'resourcenotfoundexception') !== false ) {
		
			preg_match('/.*?\<internalReason\>(.*?)\<\/internalReason\>.*?/si', $json_data, $matches);
			if ( isset($matches[1]) && trim($matches[1]) != '' ) {
				$yt_response	= $matches[1];
			} else {
				$yt_response	= lang('videos_not_available');
			}
			$yt_response2		= $yt_response;
		} else {
		
			$yt_response	= lang('videos_not_available');
			$yt_response2	= $yt_response;
		}

		if ( $config['debug_mode_enabled'] == 'true' ) {
			
			$time_end	= microtime();
			if ( file_exists($pt_path.$config['logs_cache_dir']) && is_writable($pt_path.$config['logs_cache_dir']) ) {
				$fh = @fopen($debug_file, 'a');
				@fwrite($fh, "\ntime start: " .$time_start. " -- " .$xml . " -- " .$url. " -- " .$errno." - ".$errstr. " -- Time end: ".$time_end.' Cache file:  '.md5($url)." -- ".$_SERVER['HTTP_USER_AGENT']."\n");
				@fclose($fh);
			}
		}
			
		$_SESSION['yt_response'] = $yt_response;
	}
	
	return $yt_response2;
}

function get_random_category_id() {
	$CategoriesObject			= new categories;
	$data_cache_files			= $CategoriesObject->categories_cache_files();
		
	$cache_category_ids_file	= $data_cache_files['category_ids'];
	
	if ( file_exists($cache_category_ids_file) ) {
		$time_cached	= time() - filemtime($cache_category_ids_file);
		if ( $time_cached > 86400 ) {
			create_category_ids_cache($cache_category_ids_file);
		}
	} else {
		create_category_ids_cache($cache_category_ids_file);
	}
	if ( file_exists($cache_category_ids_file) ) {
		include_once($cache_category_ids_file);
	}
	
	if ( is_array($cache_category_ids) && count($cache_category_ids) > 0 ) {
		$category_ids_idx	= rand(0, count($cache_category_ids) - 1);
		$category_id	= $cache_category_ids[$category_ids_idx];
	} else {
		$category_id	= 0;
	}

	return $category_id;
}

function get_videos_by_category($CategoryObject, $page = 1, $orderby) {
	global $config;
	
	$data	= array();
	$data['keyword']	= '';
	$data['category_name']	= '';
	$data['data_videos']	= array();
	$data['total_videos']	= 0;
	$data['videos']			= array();
	$data['page']			= $page;
	
	if ( $CategoryObject->c_listing_source == 'keyword' ) {
		$data['keyword'] 		= str_replace("-"," ", $CategoryObject->c_keyword); 
		$data['category_name']	= $CategoryObject->c_name;
		$data['data_videos'] 	= YT_ListByKeyword($data['keyword'], $page, $orderby);
		$data['total_videos']	= YT_Total_Videos($data['data_videos']);
		$data['videos']			= YT_Videos($data['data_videos']);
		
	} else if ( $CategoryObject->c_listing_source == 'author' ) {
		$data['keyword'] 		= str_replace("-"," ", $CategoryObject->c_name); 
		$data['category_name']	= $CategoryObject->c_name;
		$data['data_videos'] 	= YT_GetUserUpload($CategoryObject->c_user_videos, $page, $config['list_on_home_page']);
		$data['total_videos']	= YT_Total_Videos($data['data_videos']);
		$data['videos']			= YT_Videos($data['data_videos']);
		
	} else if ( $CategoryObject->c_listing_source == 'playlist_id' ) {
		$data['keyword']		= str_replace("-"," ", $CategoryObject->c_name); 
		$data['category_name']	= $CategoryObject->c_name;
		$data['data_videos'] 	= YT_GetUserPlaylistsEntry_XML($CategoryObject->c_playlist_id, $page, $config['list_on_home_page']);
		$data['total_videos']	= YT_Total_Videos($data['data_videos']);
		$data['videos']			= YT_Videos($data['data_videos'], 'playlists_entry');
	}
	
	return $data;
}

function htmltag_to_js($var) {
  // json_encode() does not escape <, > and &, so we do it with str_replace()
  return str_replace(array("<", ">", "&"), array('\x3c', '\x3e', '\x26'), json_encode($var));
}

function prismo_json_encode($data) {
	global $is_native_json;
	
	if ( $is_native_json ) {
		return json_encode($data);
	} else {
		global $json;
		return $json->encode($data);
	}
}

?>