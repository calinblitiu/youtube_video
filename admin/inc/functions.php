<?php
$is_mbstring_enabled = false;
if ( function_exists('mb_substr') ) {
	$is_mbstring_enabled	= true;
} 

if ( !function_exists('get_domain_name') ) {
	function get_domain_name() {	
		global $config;
		$domain_name	= "prismotube.com";
		preg_match("/^(http:\/\/)?([^\/]+)/i", $config['website_url'], $matches);


		if ( isset($matches[2]) && trim($matches[2]) != '' ) {
			$host = $matches[2];
			// get last two segments of host name
			preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches2);
			if ( isset($matches2[0]) && trim($matches2[0]) != '' ) {
				$domain_name = $matches2[0];
			}
		}
		return $domain_name;
	}
}

if ( !function_exists('unchunkHttp11') ) {
	function unchunkHttp11($data) {
		$fp = 0;
		$outData = "";
		while ($fp < strlen($data)) {
			$rawnum = substr($data, $fp, strpos(substr($data, $fp), "\r\n") + 2);
			$num = hexdec(trim($rawnum));
			$fp += strlen($rawnum);
			$chunk = substr($data, $fp, $num);
			$outData .= $chunk;
			$fp += strlen($chunk);
		}
		return $outData;
	}
}

function checkForm() {

	global $config;

	$website_name	= trim($_POST["website_name"]);
	$website_url	= trim($_POST["website_url"]);
	$username = trim($_POST["admin_username"]);
	$email = trim($_POST["admin_email"]);
	$current_pass = $config["admin_pass"];
	$old_pass = $_POST["old_pass"];
	$confirm_pass = $_POST["confirm_pass"];
	$new_pass = $_POST["new_pass"];
	$pstrength	= trim(strtolower($_POST["pstrength"]));
	$error = "";
	
	$db_name	= trim($_POST['db_name']);
	$db_user	= trim($_POST['db_user']);
	$db_pass	= trim($_POST['db_pass']);
	$db_host	= trim($_POST['db_host']);
	
	
	
	$website_logo = $_FILES['website_logo'];
	
	$temp_errors	= array();
	$logo_exts				= array('gif', 'jpeg', 'jpg', 'png');
	
	if ( $db_name == '' ) {
		$temp_errors[] = "Please specify database name";
	}
	
	if ( $db_user == '' ) {
		$temp_errors[] = "Please specify database user";
	} 
	
	if ( $db_host == '' ) {
		$temp_errors[] = "Please specify database host";
	}
	
	if ( $db_host != '' && $db_user != '' && $db_name != '' ) {
		$db_link	= @mysql_connect($db_host, $db_user, $db_pass);
		if ( !$db_link ) {
			$temp_errors[] = "Unknown Database Connection";
		} else {
			$db_selected = @mysql_select_db($db_name, $db_link);
			if (!$db_selected) {
			  $temp_errors[] 	= "Unknown Database";
			}
		}
	}
	
	if($website_name == "") {
		$temp_errors[] = "Please specify website name";
	}
	
	if($website_url == "") {
		$temp_errors[] = "Please specify website url";
	}
	
	if($username == "") {
		$temp_errors[] = "Please assign an Admin Username";
	}else{

		if($email == "") {
			$temp_errors[] = "Please enter an email address";
		}else{
			
		}
	}

	
		if ( $old_pass != '' && $current_pass != '' && $confirm_pass != '' ) {
			if ( (strpos($pstrength, "medium") === false) && (strpos($pstrength, "strong") === false) ){ 
				$temp_errors[]	= 'Password strength has to be medium or strong.';
			} else {
				if($old_pass != $current_pass && $current_pass != "" && $old_pass != "") {
					$temp_errors[] = "Current Password is invalid!";
				}else{

					if($new_pass != $confirm_pass) {
						$temp_errors[] = "Password does not match!";
					}else{

						if(strlen($new_pass) < 3 && $current_pass != "" && $old_pass != "") {
							$temp_errors[] = "New password must be a minimum of 4 characters";
						}
					}
				}
			}
		}
	
	
	if ( isset($_POST['twitter_enabled']) && $_POST['twitter_enabled'] == 'true' ) {
		if ( trim($_POST['twitter_key']) == '' ) {
			if ( $_POST['rd_twitter_type'] == 'author' ) {
				$temp_errors[]	= "Please enter twitter author";
			} else if ( $_POST['rd_twitter_type'] == 'keyword' ) {
				$temp_errors[]	= "Please enter twitter keyword";
			}
		}
		
		if ( !is_numeric($_POST['num_twitter_display']) ) {
			$temp_errors[] = 'Please specify twitter display with numeric.';
		} else if ( $_POST['num_twitter_display'] <= 0 ) {
			$temp_errors[]	= 'Please specify twitter display with positive number or more than zero.';
		} else if ( $_POST['num_twitter_display'] > 15 ) {
			$temp_errors[]	= 'Please specify twitter display with max 15 feeds.';
		}
	}
	
	if ( isset($_POST['shoutbox_enabled']) && $_POST['shoutbox_enabled'] == 'true' ) {
		if ( trim($_POST['shoutbox_code']) == '' ) {
			$temp_errors[]	= "Please enter shoutbox code";
		}
	}
	
	if ( isset($_POST['flickr_enabled']) && $_POST['flickr_enabled'] == 'true' ) {
		if ( trim($_POST['flickr_key']) == '' ) {
			if ( $_POST['rd_flickr_type'] == 'user_id' ) {
				$temp_errors[]	= "Please enter Flickr UserID.";
			} else if ( $_POST['rd_flickr_type'] == 'keyword' ) {
				$temp_errors[]	= "Please enter Flickr keyword.";
			}
		}
		
		/*if ( !is_numeric($_POST['num_flickr_display']) ) {
			$temp_errors[] = 'Please specify Flickr display with numeric.';
		} else if ( $_POST['num_flickr_display'] <= 0 ) {
			$temp_errors[]	= 'Please specify Flickr display with positive number or more than zero.';
		} else if ( $_POST['num_flickr_display'] > 10 ) {
			$temp_errors[]	= 'Please specify Flickr display with max 10 photos.';
		}*/
	}
	
	if ( isset($_POST['facebook_enabled']) && $_POST['facebook_enabled'] == 'true' ) {
		if ( trim($_POST['facebook_page_url']) == '' ) {
			$temp_errors[]	= "Please enter facebook page URL";
		}
	}

	if ( isset($_POST['categories_cache_enable']) && $_POST['categories_cache_enable'] == 'true' ) {
		$sqlQuery	= "SELECT COUNT(*) AS `total` FROM `".DB_PREFIX."categories` WHERE `position` RLIKE '^[0-9]+>[0-9]+>[0-9]+>$'";
		$sqlResult	= dbQuery($sqlQuery);
		$data		= mysql_fetch_array($sqlResult);
		if ( $data['total'] > 0 ) {
			$temp_errors[]	= "Categories Cache could not be enabled because it can only support categories up to 2 levels deep";
		}
	}
	
	if ( $_POST['tag_cloud_enabled'] == 'true' && $_POST['tags_enabled'] == 'false' ) {
		$temp_errors[]	= "Please enable Display Tags in Display Settings page. Tag Cloud setting is depend on Display Tags.";
	} 
	
	if ( isset($_POST['video_upload_enabled']) && $_POST['video_upload_enabled'] == 'true' ) 
	{
		
		if ( isset($_POST['yt_username']) && trim($_POST['yt_username']) == '' ) 
		{
			$temp_errors[]	= "Please enter youtube username";
		}
		if ( isset($_POST['yt_password']) && trim($_POST['yt_password']) == '' ) 
		{
			$temp_errors[]	= "Please enter youtube password";
		}
		if ( empty( $config["yt_developer_key"] ) ) 
		{
			$temp_errors[]	= "Please enter youtube developer key in Main Settings tab";
		} 
		
		// Only query gdata if switching from FALSE to TRUE, otherwise it will happen on every admin page load
		if( ($config['video_upload_enabled'] == 'false' ) && $_POST['video_upload_enabled'] == 'true' ) 
		{
      
			$cache_file	= '../'.$config['xml_cache_dir']."test_yt_developer_key.txt";
			
			$data_jsons	= url_get_contents("http://gdata.youtube.com/feeds/api/videos?v=2&alt=jsonc&format=1&q=videos&start-index=1&max-results=10&key=".$_POST['yt_developer_key'], $cache_file, 50, 10, true);
			$data_jsons	= strtolower($data_jsons);
			
			if ( strpos($data_jsons, "invalid developer key") ) {
				$temp_errors[] = "Please enter a valid youtube developer key in Main Settings tab";
			}
		
			if ( file_exists($cache_file) ) 
			{
				unlink($cache_file);
			}
		}
		
	}
	
	$is_gd_installed		= false;
	if (extension_loaded('gd') && function_exists('gd_info')) {
		$is_gd_installed	= true;
	}
	
	if ( isset($website_logo['size']) && $website_logo['size'] > 0 ) {
		if ( $is_gd_installed ) {
		
			$image_object	= @getimagesize($website_logo['tmp_name']);
			
			if ( !$image_object ) {
				$hasError	= true;
				$temp_errors[]	= 'Please upload site logo with image type gif, jpg or png.';
			} else {
				if ( !in_array($image_object[2], array(1,2,3) ) ) {
					$hasError	= true;
					$temp_errors[]	= 'Please upload site logo with image type gif, jpg or png.';
				} else {
					$logo_width		= $image_object[0];
					$logo_height	= $image_object[1];
					$logo_size		= $website_logo['size'];
					
					if ( $website_logo['size'] > 1000000 ) {
						$hasError	= true;
						$temp_errors[]	= 'Please upload site logo with max filesize 1MB.';
					} else {
					
						if ( $logo_width > 900 ) {
							$hasError	= true;
							$temp_errors[]	= 'Please upload site logo with max width 900px.';
						}
						
						if ( $logo_height > 500 ) {
							$hasError	= true;
							$temp_errors[]	= 'Please upload site logo with max height 500px.';
						}
					}
				}
			}
			
		} else {
			$file_ext	= end(explode(".", $website_logo['name']));
			if ( !in_array( strtolower($file_ext), $logo_exts) ) {
				$hasError	= true;
				$temp_errors[]	= 'Please upload site logo with image type gif, jpg or png.';
			} else {
				if ( $website_logo['size'] > 1000000 ) {
					$hasError	= true;
					$temp_errors[]	= 'Please upload site logo with max filesize 1MB.';
				}
			}
		}
		

		$logo_path	= "../images";
		
		if ( !is_writable($logo_path) ) {
			$hasError	= true;
			$temp_errors[]	= 'Please make folder images writable.';
		} 
		
	}
	
	
	$num_enabled	= 0;
	
	if ( $_POST['wibiya_enabled'] == 'true' ) {
		$num_enabled++;
	}
	
	if ( $_POST['skysa_bar_enabled'] == 'true' ) {
		$num_enabled++;
	}
	
	if ( $num_enabled > 1 ) {
		$hasError	= true;
		$temp_errors[]	= 'You can only enable one of the bar widgets ( Wibiya or Skysa bar )';
	}
	
	if ( $num_enabled == 1 ) {
		
		if ( $_POST['wibiya_enabled'] == 'true' ) {
			if ( trim($_POST['wibiya_code']) == '' ) {
				$hasError = true;
				$temp_errors[]	= 'Please specify wibiya code.';
			}
		}
		
		if ( $_POST['skysa_bar_enabled'] == 'true' ) {
			if ( trim($_POST['skysa_bar_code']) == '' ) {
				$hasError = true;
				$temp_errors[]	= 'Please specify Skysa Bar code.';
			}
		}
	}
	
	
	if ( count($temp_errors) > 0 ) {
		$error	= join("<br />", $temp_errors);
	}
	
	return $error;
}

if ( !function_exists('SeoTitleEncode') ) {
function SeoTitleEncode($s) {
  $c = array (' ','-','/','\\',',','.','#',':',';','\'','"','[',']','{',
      '}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');

  $s = str_replace($c, '-', $s);

  $s = preg_replace(
        array('/-+/',
              '/-$/',
              '/-ytmsinternsignature/'),
        array('-',
              '',
              'ytmsinternsignature') ,
        $s);
  return $s;
}
}

if ( !function_exists('cleanCode') ) {
	function cleanCode($code) {

		$cleanCode = "";

		for($i = 0 ; $i < strlen($code) ; $i++) {

			if($code[$i] == '\\') {
				$cleanCode .= '';
			}else{
				$cleanCode .= $code[$i];
			}

		}

		$cleanCode2 = "";

		for($i = 0 ; $i < strlen($cleanCode) ; $i++) {

			if($cleanCode[$i] == '"') {
				$cleanCode2 .= '\"';
			}else{
				$cleanCode2 .= $cleanCode[$i];
			}

		}

		return $cleanCode2;
	}
}

if ( !function_exists('deleteFiles') ) {
function deleteFiles($path) {


	//using the opendir function
	$dir_handle = @opendir($path) or die("Unable to open $path");


	//running the while loop
	while ($file = readdir($dir_handle)) 
	{
  		if($file!="." && $file!="..") {
	   		unlink($path.$file);
		}
		
	}

	//closing the directory
	closedir($dir_handle);


}
}

if ( !function_exists('WriteToFile') ) {
function WriteToFile($filename, $content) {
	$handle = fopen($filename, 'w+');
	fwrite($handle, $content);
	fclose($handle);
}
}

function cleanColor($color) {

	$cleanColor = "";

	for($i = 0 ; $i < strlen($color) ; $i++) {

		if($color[$i] == '#') {
			$cleanColor .= '';
		}else{
			$cleanColor .= $color[$i];
		}

	}
	return $cleanColor;
}

function copyfiles($file, $newfile){ 


	if(file_exists($file) == false){ 
		die("file '$file' doesn't exist!"); 
	} 

	//copy the file if the file exist 
	$result = copy($file, $newfile); 

	//Let the user know the result of the file copy 
	if($result == true){ 
		return true; 
	}else{ 
		return false; 
	} 
} 


function isDemo() {
	global $aConfig;
		return $aConfig["ADMIN_DEMO"];
}

function generate_seo_link($input,$replace = '-')
{
	//make it lowercase, remove punctuation, remove multiple/leading/ending spaces
	$return = trim(str_replace('+','',preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input))));
	return str_replace(' ', $replace, $return);
}

function post_keyword_filter($params, $timeout = 15) {
	$url = 'http://alurian.com/members/data.php?request=insert_keyword_filter&product=prismotube';
	
	if ( function_exists('curl_init') ) {

		//set POST variables
		
		$fields = array(
			'keywords' => urlencode($params),
			'ip' => urlencode($_SERVER['REMOTE_ADDR']),
		);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_ENCODING , '');

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);
	} else if ( function_exists('fsockopen') ) {
		$datas_url	= parse_url($url);
		
		$fields = array(
			'keywords' => urlencode($params),
			'ip' => urlencode($_SERVER['REMOTE_ADDR']),
		);
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');
		
		$port		= (!isset($datas_url['port']) ? 80 : $datas_url['port']);
		$host		= $datas_url['host'];
		$path		= $datas_url['path'];
		$d			= "";
		
		$fp = @fsockopen($host, $port, $errno, $errstr, $timeout); 
		if($fp) { 
			@fputs($fp, "POST $path HTTP/1.1\r\n"); 
			@fputs($fp, "Host: $host\r\n"); 
			@fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
			@fputs($fp, "Content-length: ".strlen($fields_string)."\r\n"); 
			@fputs($fp, "Connection: close\r\n\r\n"); 
			@fputs($fp, $fields_string."\r\n\r\n"); 
			
			while(!@feof($fp)) $d .= @fgets($fp,4096); 
			fclose($fp); 
		} 
	}
}

function unchunk($result) {
    return preg_replace_callback(
        '/(?:(?:\r\n|\n)|^)([0-9A-F]+)(?:\r\n|\n){1,2}(.*?)'.
        '((?:\r\n|\n)(?:[0-9A-F]+(?:\r\n|\n))|$)/si',
        create_function(
            '$matches', 
            'return hexdec($matches[1]) == strlen($matches[2]) ? $matches[2] : $matches[0];'
        ), 
        $result
    );
}

function url_get_contents($url, $cache_file, $cache_expiry = 86400, $timeout = 10, $is_return_data = false) {
	$html	= "";
	$is_get_html	= false;
	if ( !file_exists($cache_file) ) {
		// get html contents
		$is_get_html	= true;
	} else { 
		$mtime	= time() - filemtime($cache_file);
		if ( $mtime > $cache_expiry ) {
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
			if ( !$is_return_data) {
				if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != '200') {
					$html = "";
				}
			}
			curl_close($ch);
		
			
		} else if ( function_exists('fsockopen') ) {
			$datas_url	= parse_url($url);
				
			$port		= (!isset($datas_url['port']) ? 80 : $datas_url['port']);
			
			$fp = fsockopen($datas_url['host'], $port, $errno, $errstr, $timeout);
			if ($fp) {
				if ( isset($datas_url['query']) && $datas_url['query'] != '' ) {
					$datas_url['path'] = $datas_url['path'] ."?". $datas_url['query'];
				}
				$out = "GET ".$datas_url['path']." HTTP/1.1\r\n";
				$out .= "Host: ".$datas_url['host']."\r\n";
				$out .= "Connection: Close\r\n\r\n";
				if ( isset($datas_url['query']) && $datas_url['query'] != '' ) {
					$out .= "Content-length: ".strlen($datas_url['query'])."\r\n\r\n"; 
					$out .= $datas_url['query']."\r\n\r\n";
				}
				
				fwrite($fp, $out);
				$is_ok	= false;
				$header	= "";
				/*do // loop until the end of the header
				{
					$header .= fgets ( $fp, 128 );
					if ( strpos($header, "200") !== false ) {
						$is_ok	= true;
					}

				} while ( strpos ( $header, "\r\n\r\n" ) === false );
				
				if ( $is_ok ) {*/
					while (!feof($fp)) {
						$html .= fgets($fp, 128);
					}
				//}
				
				$data = substr($html, (strpos($html, "\r\n\r\n")+4));
				if (strpos(strtolower($html), "transfer-encoding: chunked") !== FALSE) {
					$data = unchunkHttp11($data);
				}
				$html = $data;
				
			
				fclose($fp);
			}
		} else {
			$html =  file_get_contents($url);
		}
		
		if ( $html && trim($html) != '' ) {
			
			
			
			$handle = fopen($cache_file, "w");
			fwrite($handle, $html); 
			fclose($handle);
		}
	}
	
	return $html;
}

function remove_headers($string) { 
	$headers = array(
		"/HTTP\/\:/i",
		"/from\:/i",
		"/bcc\:/i",
		"/cc\:/i",
		"/Content\-Transfer\-Encoding\:/i",
		"/Content\-Type\:/i",
		"/Mime\-Version\:/i" 
	); 
	$string = preg_replace($headers, '', $string);
	return strip_tags($string);
} 

function delete_keyword_filter($keyword_id) {
	global $config;
	$sqlQuery	= "DELETE FROM `".DB_PREFIX."filter` WHERE `id` = {$keyword_id}";
	dbQuery($sqlQuery);
	
	$sqlQuery	= "SELECT `keyword` FROM `".DB_PREFIX."filter` ORDER BY `keyword`";
	$sqlResult	= dbQuery($sqlQuery);
	$keywords	= array();
	while($row	= mysql_fetch_array($sqlResult)) {
		$keywords[] = escape_string_for_regex(stripslashes($row['keyword']));
	}

	$str_keywords = '<?php  
$config["keyword_filter_list"] = "'.implode("|", $keywords). '";
?>'; 


	$config_path	= "../../config/config_filter.php";
	
	$fp = fopen($config_path, "w"); 
	fwrite($fp, $str_keywords); 
	fclose($fp);
}

function add_keyword_filter($keywords) {
	global $is_mbstring_enabled;
	
	if ( is_array($keywords) && count($keywords) > 0 ) {
		foreach($keywords as $keyword) {
			if ( $is_mbstring_enabled ) {
				$keyword	= mb_strtolower($keyword, 'UTF-8');
			}
			$keyword	= trim($keyword);
			
			$sqlQuery	= "SELECT * FROM `".DB_PREFIX."filter` WHERE LCASE(`keyword`) = '".db_escape_string($keyword)."'";
			$sqlResult	= dbQuery($sqlQuery);
			
			if ( mysql_num_rows($sqlResult) <= 0 ) {
				$sqlQuery	= "INSERT INTO `".DB_PREFIX."filter` SET `keyword` = '".db_escape_string($keyword)."'";
				dbQuery($sqlQuery);
			}
		}
	} else {
		if ( $keywords != '' && !is_array($keywords) ) {
		$sqlQuery	= "SELECT * FROM `".DB_PREFIX."filter` WHERE LCASE(`keyword`) = '".db_escape_string($keywords)."'";
		$sqlResult	= dbQuery($sqlQuery);
		
		if ( mysql_num_rows($sqlResult) <= 0 ) {
			$sqlQuery	= "INSERT INTO `".DB_PREFIX."filter` SET `keyword` = '".db_escape_string($keywords)."'";
			dbQuery($sqlQuery);
		}
		}
	}
	
	$sqlQuery	= "SELECT `keyword` FROM `".DB_PREFIX."filter` ORDER BY `keyword`";
	$sqlResult	= dbQuery($sqlQuery);
	$keywords2	= array();
	while($row	= mysql_fetch_array($sqlResult)) {
		$keywords2[] = escape_string_for_regex(stripslashes($row['keyword']));
	}

	$str_keywords = '<?php  
$config["keyword_filter_list"] = "'.implode("|", $keywords2). '";
?>'; 


	$config_path	= "../../config/config_filter.php";
	
	$fp = fopen($config_path, "w"); 
	fwrite($fp, $str_keywords); 
	fclose($fp);
}

function escape_string_for_regex($str)
{
        //All regex special chars (according to arkani at iol dot pt below):
        // \ ^ . $ | ( ) [ ]
        // * + ? { } , " '
		
		$str	= str_replace( array("|", '"', '"'), array('', '', ''), $str);
        
        $patterns = array('/\//', '/\^/', '/\./', '/\$/', '/\|/',
 '/\(/', '/\)/', '/\[/', '/\]/', '/\*/', '/\+/', 
'/\?/', '/\{/', '/\}/', '/\,/', '/\"/', "/\'/");
        $replace = array('\/', '\^', '\.', '\$', '\|', '\(', '\)', 
'\[', '\]', '\*', '\+', '\?', '\{', '\}', '\,', '\"', "\'");
        
        return preg_replace($patterns,$replace, $str);
}

function add_new_keyword_filter($new_keyword) {
	global $config, $is_mbstring_enabled;
	$new_kword_filter	= array();
	$new_kword_filter2	= array();
	$new_kword_filter_pt= array();
	if ( isset($config['keyword_filter_list']) && trim($config['keyword_filter_list']) != '' ) {
		$data_kwords	= explode("|", $config['keyword_filter_list']);
		foreach($data_kwords as $kword) {
			if ( $is_mbstring_enabled ) {
				$kword	= mb_strtolower($kword);
			}
			
			$kword	= trim($kword);
			if ( $kword != '' && !in_array($kword, $new_kword_filter) ) {
				$new_kword_filter[]	= $kword;
			}
		}
	}

	if ( $is_mbstring_enabled ) {
		$new_keyword	= mb_strtolower($new_keyword);
	}
	$new_keyword	= trim($new_keyword);
	if ( $new_keyword != '' && !in_array($new_keyword, $new_kword_filter) ) {
		$new_kword_filter2[]	= $new_keyword;
	}

	add_keyword_filter($new_kword_filter2);
}

if ( !function_exists('db_escape_string') ) {
	function db_escape_string($data) {
		global $conn;
		if ( !is_resource($conn) ) {
			$conn = get_db_conn();
		}
		if (get_magic_quotes_gpc()) {
			$data = stripslashes($data);
		}
		return mysql_real_escape_string($data, $conn);
	}
}

function get_parent_name($parent_key, $category_datas, $ancestors) {
	global $parent_cats;
	if ( !in_array($category_datas[$parent_key]['category'], $ancestors) ) {
		$ancestors[]	= $category_datas[$parent_key]['category'];
	}
	
	if ( trim($category_datas[$parent_key]['parent']) != '' ) {
		$parent_key		= array_search($category_datas[$parent_key]['parent'], $parent_cats);
		$ancestors = get_parent_name($parent_key, $category_datas, $ancestors);
	}
	
	return $ancestors;
}

if ( !function_exists('is_browsers_user_agent') ) {
	function is_browsers_user_agent() {
		global $config;
		$is_browser	= false;
		
		preg_match( "/(".$config["browsers_user_agent"].")/i", $_SERVER['HTTP_USER_AGENT'], $matches );
		if ( isset($matches[1]) ) {
			$is_browser = true;
		}
		
		return $is_browser;
	}
}
?>