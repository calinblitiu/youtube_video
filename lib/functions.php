<?php

if (!function_exists("stripos")) {
	function stripos($str,$needle) {
		return strpos(strtolower($str),strtolower($needle));
	}
}

function mb_wordwrap($str, $width=74, $break="\r\n")
{
    // Return short or empty strings untouched
    if(empty($str) || mb_strlen($str, 'UTF-8') <= $width)
        return $str;

    $br_width  = mb_strlen($break, 'UTF-8');
    $str_width = mb_strlen($str, 'UTF-8');
    $return = '';
    $last_space = false;

    for($i=0, $count=0; $i < $str_width; $i++, $count++)
    {
        // If we're at a break
        if (mb_substr($str, $i, $br_width, 'UTF-8') == $break || ( strpos($str, 'http://') !== false || strpos($str, 'www.') !== false ) )
        {
            $count = 0;
            $return .= mb_substr($str, $i, $br_width, 'UTF-8');
            $i += $br_width - 1;
            continue;
        }

        // Keep a track of the most recent possible break point
        if(mb_substr($str, $i, 1, 'UTF-8') == " ")
        {
            $last_space = $i;
        }

        // It's time to wrap
        if ($count > $width)
        {
            // There are no spaces to break on!  Going to truncate :(
            if(!$last_space)
            {
                $return .= $break;
                $count = 0;
            }
            else
            {
                // Work out how far back the last space was
                $drop = $i - $last_space;

                // Cutting zero chars results in an empty string, so don't do that
                if($drop > 0)
                {
                    $return = mb_substr($return, 0, -$drop);
                }

                // Add a break
                $return .= $break;

                // Update pointers
                $i = $last_space + ($br_width - 1);
                $last_space = false;
                $count = 0;
            }
        }

        // Add character from the input string to the output
        $return .= mb_substr($str, $i, 1, 'UTF-8');
    }
    return $return;
}

function wordwrapURI($str, $width = 75, $break = "\n", $cut = false)
{
    $newText = array();
    $words = explode(' ', str_replace("\n", "\n ", $str));
    foreach($words as $word) {
        if(strpos($word, 'http://') === false && strpos($word, 'www.') === false) {
            $word = wordwrap($word, $width, $break, $cut);
        } else {
			$temp_string	= strip_tags($word);
			if(empty($temp_string) || strlen($temp_string) <= 45) {
				$word	= $word;
			} else {
				$word = wordwrap($word, 45, $break, $cut);
			}
		}
        $newText[] = $word;
    }
    return implode(' ', $newText);

	// create array by deviding at each occurrence of "<a"
	  $arr = explode('<a', $str);

	  // break up long words in $arr[0] since
	  // it will never contain a hyberlink
	  $arr[0] = preg_replace('/([^\s]{'.$width.'})/i',"$1$break",$arr[0]);

	  // run loop to devide remaining elements
	  for($i = 1; $i < count($arr); $i++) {

	   // devide each element in $arr at each occurrence of "</a>"
	   $arr2 = explode('</a>', $arr[$i]);

	   // break up long words in $arr2 that does not
	   // contain hyberlinks
	   $arr2[1] = preg_replace('/([^\s]{'.$width.'})/i',"$1$break",$arr2[1]);

	   // rejoin $arr2 and assign as element in $arr
	   $arr[$i] = join('</a>', $arr2);
	  }
	  // rejoin $arr to string and return it
	  return join('<a', $arr);
}

function cs_wordwrap($string, $width, $break = "<br />") {
	global $is_mbstring_enabled;
	if ( $is_mbstring_enabled ) {
		if ( mb_detect_encoding($string, "auto") == 'UTF-8' ) {
			$string	= mb_wordwrap($string, $width, $break);
		} else {
			$string	= wordwrapURI($string, 30, $break, true);
		}
	} else {
		$string	= wordwrapURI($string, $width, $break, true);
	}
	return $string;
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

function removeDash($s) {
	$s = str_replace("_", ' ', $s);
	return $s;
}

function deleteFiles($path) {
	//using the opendir function
	$dir_handle = @opendir($path) or die("Не удается открыть $path");

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

function containFiles($path) {
	//using the opendir function
	$dir_handle = @opendir($path) or die("Не удается открыть $path");

	//running the while loop
	while ($file = readdir($dir_handle))
	{
		if($file!="." && $file!="..") {
			return true;
		}

	}

	//closing the directory
	closedir($dir_handle);

	return false;
}

function setSecondsToMinute($seconds) {
	$minutes = floor($seconds/60);
	$secondsleft = $seconds%60;

	if($secondsleft<10)
		$secondsleft = "0" . $secondsleft;
    return "{$minutes}:{$secondsleft}";
}

function ads_list($group_id) {
	$ads_array	= array();
	$sqlQuery	= "SELECT code, position, a.width, a.height, ad_name, ad_id
		FROM `".DB_PREFIX."ads` a INNER JOIN `".DB_PREFIX."ad_group` b ON a.ad_group_id = b.ad_group_id
	WHERE a.ad_group_id = {$group_id} AND b.`active` = 1 ORDER BY position";

	$sqlResult	= dbQuery($sqlQuery);
	$ctr		= 0;
	while($ads_arr = mysql_fetch_array($sqlResult)){
		$ads_array[$ctr]['ad_id']	= $ads_arr['ad_id'];
		$ads_array[$ctr]['ad_name']	= stripslashes($ads_arr['ad_name']);
		$ads_array[$ctr]['position']= stripslashes($ads_arr['position']);
		$ads_array[$ctr]['code']	= stripslashes($ads_arr['code']);
		$ads_array[$ctr]['width']	= stripslashes($ads_arr['width']);
		$ads_array[$ctr]['height']	= stripslashes($ads_arr['height']);
		$ctr++;
	}
	return $ads_array;
}

function ads_group($group_id) {
	$ads_array	= array();
	$sqlQuery	= "SELECT width, height, group_name, orientation, ad_group_id FROM `".DB_PREFIX."ad_group` WHERE ad_group_id = {$group_id}";
	$sqlResult	= dbQuery($sqlQuery);
	while($ads_arr = mysql_fetch_array($sqlResult)){
		$ads_array['ad_group_id']		= $ads_arr['ad_group_id'];
		$ads_array['group_name']		= stripslashes($ads_arr['group_name']);
		$ads_array['orientation']		= stripslashes($ads_arr['orientation']);
		$ads_array['width']				= stripslashes($ads_arr['width']);
		$ads_array['height']			= stripslashes($ads_arr['height']);
	}
	return $ads_array;
}

function all_ads_list() {
	$ads_array	= array();
	$sqlQuery	= "SELECT code, position, a.width, a.height, ad_name, ad_id, b.ad_group_id
		FROM `".DB_PREFIX."ads` a INNER JOIN `".DB_PREFIX."ad_group` b ON a.ad_group_id = b.ad_group_id
	WHERE b.`active` = 1 ORDER BY b.ad_group_id, position";

	$sqlResult	= dbQuery($sqlQuery);
	$ctr		= 0;
	while($ads_arr = mysql_fetch_array($sqlResult)){
		$ad_group_id	= $ads_arr['ad_group_id'];
		$ads_array2		= array();

		if ( !isset($ads_array[$ad_group_id]) ) {
			$ads_array[$ad_group_id] = array();
		}

		$ads_array2['ad_id']	= $ads_arr['ad_id'];
		$ads_array2['ad_name']	= stripslashes($ads_arr['ad_name']);
		$ads_array2['position']	= stripslashes($ads_arr['position']);
		$ads_array2['code']		= stripslashes($ads_arr['code']);
		$ads_array2['width']	= stripslashes($ads_arr['width']);
		$ads_array2['height']	= stripslashes($ads_arr['height']);

		$ads_array[$ad_group_id][] = $ads_array2;
	}
	return $ads_array;
}

function all_ads_group() {
	$ads_array	= array();
	$sqlQuery	= "SELECT width, height, group_name, orientation, ad_group_id FROM `".DB_PREFIX."ad_group` WHERE active = 1";
	$sqlResult	= dbQuery($sqlQuery);
	while($ads_arr = mysql_fetch_array($sqlResult)){
		$ad_group_id	= $ads_arr['ad_group_id'];
		$ads_array[$ad_group_id]['ad_group_id']		= $ads_arr['ad_group_id'];
		$ads_array[$ad_group_id]['group_name']		= stripslashes($ads_arr['group_name']);
		$ads_array[$ad_group_id]['orientation']		= stripslashes($ads_arr['orientation']);
		$ads_array[$ad_group_id]['width']				= stripslashes($ads_arr['width']);
		$ads_array[$ad_group_id]['height']			= stripslashes($ads_arr['height']);
	}
	return $ads_array;
}

function get_all_ad_group($group_id) {
	global $all_ads_group, $all_ads_list;
	$ads_lists	= isset($all_ads_list[$group_id]) ? $all_ads_list[$group_id] : array();
	$ads_group	= isset($all_ads_group[$group_id]) ? $all_ads_group[$group_id] : array();
	$str_ads	= '';
	if ( count($ads_lists) >0 && count($ads_group) > 0 ) {
		$str_width	= "";
		$str_height	= "";

		if ( $ads_group['width'] > 0){
			$str_width	= "width:{$ads_group['width']}px;";
		}
		if ( $ads_group['height'] > 0){
			$str_height	= "height:{$ads_group['height']}px;";
		}

		//debug.. remove $ads_group['group_name']
		$str_ads 	.= "<div style=\"{$str_width}{$str_height}\">";
		foreach($ads_lists as $key => $ads_list) {

			$str_width	= "";
			$str_height	= "";

			if ( $ads_list['width'] > 0){
				$str_width	= "width:{$ads_list['width']}px;";
			}
			if ( $ads_list['height'] > 0){
				$str_height	= "height:{$ads_list['height']}px;";
			}

			if ($ads_group['orientation'] == 'horizontal' && $key != 0) {
				$str_ads 	.= "<div style=\"{$str_width}{$str_height}\">{$ads_list['code']}</div>";
			}else {
				$str_ads 	.= "<div style=\"{$str_width}{$str_height}\">{$ads_list['code']}</div>";
			}
		}
		$str_ads	.= "</div>";
	}
	return $str_ads;
}

function get_ad_group($group_id) {
	$ads_lists	= ads_list($group_id);
	$ads_group	= ads_group($group_id);
	$str_ads	= '';
	if ( count($ads_lists) >0 && count($ads_group) > 0 ) {
		$str_width	= "";
		$str_height	= "";

		if ( $ads_group['width'] > 0){
			$str_width	= "width:{$ads_group['width']}px;";
		}
		if ( $ads_group['height'] > 0){
			$str_height	= "height:{$ads_group['height']}px;";
		}

		//debug.. remove $ads_group['group_name']
		$str_ads 	.= "<div style=\"{$str_width}{$str_height}\">";
		foreach($ads_lists as $key => $ads_list) {

			$str_width	= "";
			$str_height	= "";

			if ( $ads_list['width'] > 0){
				$str_width	= "width:{$ads_list['width']}px;";
			}
			if ( $ads_list['height'] > 0){
				$str_height	= "height:{$ads_list['height']}px;";
			}

			if ($ads_group['orientation'] == 'horizontal' && $key != 0) {
				$str_ads 	.= "<div style=\"{$str_width}{$str_height}\">{$ads_list['code']}</div>";
			}else {
				$str_ads 	.= "<div style=\"{$str_width}{$str_height}\">{$ads_list['code']}</div>";
			}
		}
		$str_ads	.= "</div>";
	}
	return $str_ads;
}


function get_default_languages() {
	global $config;

	@define("DS", DIRECTORY_SEPARATOR);
	$path				= BASE_PATH;

	$files				= array();
	$default_language	= $config['web_default_language'];

	if ( file_exists($path."language".DS.$default_language.DS."frontend.php") ) {
		$files['frontend']	= $path."language".DS.$default_language.DS."frontend.php";
		$files['default']	= $path."language".DS."en".DS."frontend.php";
	} else {
		$files['frontend']	= $path."language".DS."en".DS."frontend.php";
	}
	return $files;
}

function lang($key, $default_value = '') {
	global $lang, $config;
	if ( isset($lang[$key]) ) {
		return addslashes($lang[$key]);
	} else {
		return $default_value;
	}
}

function main_menu($feed_id) {
	global $config;
	$menus		= array();
	$request_uri= $_SERVER['REQUEST_URI'];
	$cur_page	= curPageURL();
	$sqlQuery	= "SELECT * FROM `".DB_PREFIX."main_menu` ORDER BY `order`";
	$sqlResult	= dbQuery($sqlQuery);
	while($menu_arr = mysql_fetch_array($sqlResult)){
		$temp_menu['class']				= stripslashes($menu_arr['class']);
		$temp_menu['title']				= stripslashes($menu_arr['title']);

		$menu_title	= $temp_menu['title'];

		if ( $menu_title == 'Top Rated' ) {
			$menu_title	= lang('top_rated');
		} else if ( $menu_title == 'Top Favorites' ) {
			$menu_title	= lang('top_favorites');
		} else if ( $menu_title == 'Most Viewed' ) {
			$menu_title	= lang('most_viewed');
		} else if ( $menu_title == 'Most Recent' ) {
			$menu_title	= lang('most_recent');
		} else if ( $menu_title == 'Most Discussed' ) {
			$menu_title	= lang('most_discussed');
		} else if ( $menu_title == 'Most Responded' ) {
			$menu_title	= lang('most_responded');
		} else if ( $menu_title == 'Recently Featured' ) {
			$menu_title	= lang('recently_featured');
		}

		$temp_menu['title']				= $menu_title;
		$temp_menu['url']				= stripslashes($menu_arr['url']);
		$temp_menu['new_window']		= stripslashes($menu_arr['new_window']);
		$temp_menu['on']				= 0;

		//echo $_SERVER['REQUEST_URI'].'-'.$temp_menu['url'];echo '<br />';

		if ( isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/' && strpos($temp_menu['url'], $_SERVER['REQUEST_URI']) !== false && $temp_menu['url'] != $config['website_url'] ) {
			$temp_menu['on']			= 1;
		} else if ( $feed_id != '' && strpos($temp_menu['url'], $feed_id) != false ) {
			$temp_menu['on']			= 1;
		}
		$menus[]						= $temp_menu;
	}
	return $menus;
}

function send_mail_notification($email, $category_id, $purpose, $message, $subject) {
	$date		= date('Y-m-d');
	$sqlQuery	= "SELECT * FROM `".DB_PREFIX."mail_notification_log` WHERE category_id = {$category_id} AND `date` = '{$date}'";
	$sqlResult	= dbQuery($sqlQuery);
	if ( mysql_num_rows($sqlResult) <= 0 ) {
		$sqlQuery	= "INSERT INTO `".DB_PREFIX."mail_notification_log` SET `category_id` = {$category_id}, `purpose` = '".db_escape_string($purpose)."',
			`date` = '{$date}', `message` = '".db_escape_string($message)."', `subject` = '".db_escape_string($subject)."'";
		dbQuery($sqlQuery, false);
		return mail($email, $subject, $message, "From: no-reply@".$_SERVER['HTTP_HOST']."\r\n");
	}
	return NULL;
}

function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function get_headers_php4($url, $format=0) {
	$headers = array();
	$url = parse_url($url);
	$host = isset($url['host']) ? $url['host'] : '';
	$port = isset($url['port']) ? $url['port'] : 80;
	$path = (isset($url['path']) ? $url['path'] : '/') . (isset($url['query']) ? '?' . $url['query'] : '');
	$fp = fsockopen($host, $port, $errno, $errstr, 3);
	if ($fp)
	{
		$hdr = "GET $path HTTP/1.1\r\n";
		$hdr .= "Host: $host \r\n";
		$hdr .= "Connection: Close\r\n\r\n";
		fwrite($fp, $hdr);
		while (!feof($fp) && $line = trim(fgets($fp, 1024)))
		{
			if ($line == "\r\n") break;
			list($key, $val) = explode(': ', $line, 2);
			if ($format)
				if ($val) $headers[$key] = $val;
				else $headers[] = $key;
			else $headers[] = $line;
		}
		fclose($fp);
		return $headers;
	}
	return false;
}

function is_valid_email($email) {
	// Test for the minimum length the email can be
	if ( strlen( $email ) < 3 ) {
		return false;
	}

	// Test for an @ character after the first position
	if ( strpos( $email, '@', 1 ) === false ) {
		return false;
	}

	// Split out the local and domain parts
	list( $local, $domain ) = explode( '@', $email, 2 );

	// LOCAL PART
	// Test for invalid characters
	if ( !preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) {
		return false;
	}

	// Test for sequences of periods
	if ( preg_match( '/\.{2,}/', $domain ) ) {
		return false;
	}

	// Test for leading and trailing periods and whitespace
	if ( trim( $domain, " \t\n\r\0\x0B." ) !== $domain ) {
		return false;
	}

	// Split the domain into subs
	$subs = explode( '.', $domain );

	// Assume the domain will have at least two subs
	if ( 2 > count( $subs ) ) {
		return false;
	}

	// Loop through each sub
	foreach ( $subs as $sub ) {
		// Test for leading and trailing hyphens and whitespace
		if ( trim( $sub, " \t\n\r\0\x0B-" ) !== $sub ) {
			return false;
		}

		// Test for invalid characters
		if ( !preg_match('/^[a-z0-9-]+$/i', $sub ) ) {
			return false;
		}
	}

	// Congratulations your email made it!
	return true;
}

function get_breadcrumbs() {
	global $config;
	$str_breadcrumb	= "";
	$script_name	= $_SERVER['SCRIPT_FILENAME'];
	$i				= 0;
	$lang_home		= lang('home');

	if ( strpos($script_name, "contact_us.php") !== false ) {
		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a> &raquo; <a href="contact_us.php">'.lang('contact_us').'</a>';
	} else if ( strpos($script_name, "all_categories.php") !== false ) {
		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a> &raquo; <a href="categories/all.html">'.lang('all_categories').'</a>';
	} else if ( strpos($script_name, "list.php") !== false ) {
		if (empty($_GET['orderby'])) {
		  $orderby = $config["sort_videos_by"];
		}else{
		  $orderby = $_GET['orderby'];
		}
		$cat_id	= (int) $_GET['id'];

		if ( $cat_id > 0 ) {

			$category_object	= new categories;
			$cat_position		= $category_object->get_position($cat_id);

			$str_positions		= explode(">", $cat_position);
			$temp_cats			= array();
			foreach( $str_positions as $position_id ){
				if ( $position_id != '' ) {
					$category_data	= $category_object->fetch($position_id);
					$cat_name		= stripslashes($category_data['c_name']);
					$cat_name		= prismo_print($cat_name);
					$temp_cats[]	= '<a href="categories/'.$cat_name.'/'.$position_id.'/page1.html">'.$cat_name.'</a>';
				}
			}

			$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo; '. join(' &raquo; ', $temp_cats);

		} else if ( isset($_GET['q']) ) {
			$kword				= urldecode($_GET['q']);
			if ( get_magic_quotes_gpc() ) {
				$kword	= stripslashes($kword);
			}

			$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo;
			<a href="tag/'.urlencode($kword).'/orderby-'.$orderby.'/page1.html">'.$kword.'</a>';
		} else {


			$p_url     = explode('/',$_SERVER['REQUEST_URI']);
			global $config;


			$seo_title = array_pop($p_url);

			preg_match_all("|page([0-9]*).html|U", $seo_title, $out, PREG_PATTERN_ORDER);

			$p = $out[1][0];

			if (empty($p)) {
				$p = 1;
			}

			if ($p>0) {
				$seo_title = array_pop($p_url);
				$seo_title_ori = array_pop($p_url);
			}

			if(empty($seo_title)) {
				$seo_title = array_pop($p_url);
				$seo_title_ori = array_pop($p_url);
			}

			$seo_title = urldecode($seo_title);

			$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo;
			<a href="'.$seo_title.'/">'.str_replace("-"," ",$seo_title) .'</a>';
		}
	} else if ( strpos($script_name, "feed.php") !== false ) {
		$feed_id	= $_GET['fid'];
		$sqlQuery	= "SELECT `title`, `url` FROM `".DB_PREFIX."main_menu` WHERE `url` LIKE '%{$feed_id}%'";
		$res 		= dbQuery($sqlQuery);

		$record = mysql_fetch_array($res);

		$menu_title	= stripslashes($record['title']);

		if ( $menu_title == 'Top Rated' ) {
			$menu_title	= lang('top_rated');
		} else if ( $menu_title == 'Top Favorites' ) {
			$menu_title	= lang('top_favorites');
		} else if ( $menu_title == 'Most Viewed' ) {
			$menu_title	= lang('most_viewed');
		} else if ( $menu_title == 'Most Recent' ) {
			$menu_title	= lang('most_recent');
		} else if ( $menu_title == 'Most Discussed' ) {
			$menu_title	= lang('most_discussed');
		} else if ( $menu_title == 'Most Linked' ) {
			$menu_title	= lang('most_linked');
		} else if ( $menu_title == 'Most Responded' ) {
			$menu_title	= lang('most_responded');
		} else if ( $menu_title == 'Recently Featured' ) {
			$menu_title	= lang('recently_featured');
		}

		$record['title']				= $menu_title;



		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
		&raquo;
		<a href="'.$record['url'].'">'.$record['title'].'</a>';

	} else if ( strpos($script_name, "user_profile.php") !== false ) {
		$profile_id			= $_GET['u'];
		$user_data           = YT_GetUserProfile($_GET['u']);
		$author_name		= $user_data['name'];
		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo;
			<a href="profile/'.$profile_id.'/">'.$author_name.'</a>';
	} else if ( strpos($script_name, "user_videos.php") !== false ) {
		$profile_id			= $_GET['u'];
		$user_data           = YT_GetUserProfile($_GET['u']);
		$author_name		= $user_data['name'];
		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo;
			<a href="profile/'.$profile_id.'/videos/">'.$author_name.'</a>';
	} else if ( strpos($script_name, "user_favorites.php") !== false ) {
		$profile_id			= $_GET['u'];
		$user_data           = YT_GetUserProfile($_GET['u']);
		$author_name		= $user_data['name'];
		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo; '.lang('favorites').' &raquo;
			<a href="profile/'.$profile_id.'/favorites/">'.$author_name.'</a>';
	} else if ( strpos($script_name, "user_playlists.php") !== false ) {
		$profile_id			= $_GET['u'];
		$user_data           = YT_GetUserProfile($_GET['u']);
		$author_name		= $user_data['name'];
		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo;
			<a href="profile/'.$profile_id.'/playlists/page-1.html">'.$author_name.'</a>';
	} else if ( strpos($script_name, "user_playlists_entry.php") !== false ) {
		$profile_id			= $_GET['u'];
		$user_data          = YT_GetUserProfile($_GET['u']);
		$author_name		= $user_data['name'];
		$data_videos 		= YT_GetUserPlaylistsEntry($_GET['lid'], 1, $config['list_per_page']);

		$feed_title			= isset($data_videos['data']['title']) ? $data_videos['data']['title']: '';

		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo;
			<a href="profile/'.$profile_id.'/playlists/page-1.html">'.$author_name.'</a>
			&raquo;
			<a href="profile/'.$profile_id.'/playlists/'.$_GET['lid'].'/'.$feed_title.'/page-1.html">'.$feed_title.'</a>';
	} else if ( strpos($script_name, "upload.php") !== false || strpos($script_name, "upload_yt_response.php") !== false ) {
		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
		&raquo;
		<a href="upload/video.html">'.lang('upload_video').'</a>';
	} else if ( strpos($script_name, "detail.php") !== false ) {
		$video_data    = YT_GetDetail($_GET['vid']);

		$vid_title	= isset($video_data['title']) ? $video_data['title']: '';
		$vid_title2	= SeoTitleEncode($vid_title);

		$str_breadcrumb		.= '<a href="'.$config['website_url'].'">'.$lang_home.'</a>
			&raquo;
			<a href="video/'.$_GET['vid'].'/'.$vid_title2.'.html">'.$vid_title.'</a>';
	}

	return $str_breadcrumb;

}

function update_cache_html($guests_file ) {
	$minusHour = strtotime("-1 hour");
	$delr = "DELETE FROM `".DB_PREFIX."guest_log` WHERE time < $minusHour";
	dbQuery($delr);

	$sqlQuery	= "SELECT COUNT(*) FROM `".DB_PREFIX."guest_log`";
	$sqlResult	= dbQuery($sqlQuery);

	list($total) = mysql_fetch_row($sqlResult);

	$handle = fopen($guests_file, "w");
	fwrite($handle, $total);
	fclose($handle);
}

function auto_link_text($text)
{
   $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
   $callback = create_function('$matches', '
       $url       = array_shift($matches);
       $url_parts = parse_url($url);

       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);

	   if ( strpos($url, "http://") === false && strpos($url, "https://") === false ) {
			$url = "http://".$url;
	   }

       return sprintf(\'<a rel="nofollow" target="_blank" href="%s">%s</a>\', $url, $text);
   ');

   return preg_replace_callback($pattern, $callback, $text);
}

function autolink( &$text, $target='_blank', $nofollow=true )
{
	// grab anything that looks like a URL...
	$urls  =  _autolink_find_URLS( $text );

	if( !empty($urls) ) // i.e. there were some URLS found in the text
	{
		array_walk( $urls, '_autolink_create_html_tags', array('target'=>$target, 'nofollow'=>$nofollow) );
		$text  =  strtr( $text, $urls );
	}
}

function _autolink_find_URLS( $text )
{
	// build the patterns
	$scheme         =       '(http:\/\/|https:\/\/)';
	$www            =       '';
	$ip             =       '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
	$subdomain      =       '[-a-z0-9_]+\.';
	$name           =       '[a-z][-a-z0-9]+\.';
	$tld            =       '[a-z]+(\.[a-z]{2,2})?';
	$the_rest       =       '\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1}';
	$pattern        =       "$scheme?(?(1)($ip|($subdomain)?$name$tld)|($www$name$tld))$the_rest";

	$pattern        =       '/'.$pattern.'/is';
	$c              =       preg_match_all( $pattern, $text, $m );

	unset( $text, $scheme, $www, $ip, $subdomain, $name, $tld, $the_rest, $pattern );
	if( $c )
	{
	return( array_flip($m[0]) );
	}
	return( array() );
}

function _autolink_create_html_tags( &$value, $key, $other=null )
{
	$target = $nofollow = null;
	if( is_array($other) ) {
		$target      =  ( $other['target']   ? " target=\"$other[target]\"" : null );
		// see: http://www.google.com/googleblog/2005/01/preventing-comment-spam.html
		$nofollow    =  ( $other['nofollow'] ? ' rel="nofollow"'            : null );
	}

	$key2 = $key;
	if ( stripos($key, 'http://') === false && stripos($key, 'https://') === false ) {
		$key2	= 'http://'.$key;
	}
	$value = "<a href=\"$key2\"$target$nofollow>$key</a>";
}


function remove_xml_cache()
{
  // Please only call this function from root directory

	global $config;

  $last_cache_clearing_file		= "cache/.last_cache_clearing.txt";
  $last_cache_clearing_time	  = file_get_contents($last_cache_clearing_file);
  $time_since_last_cleared    = time() - $last_cache_clearing_time;
  $clear_it                   = false ;

	// If running for first time
	if ( !file_exists($last_cache_clearing_file) )
	{
		$clear_it = true ;
	}
	else
	{
		if ( $time_since_last_cleared > 3600 ) // Clear every 1 hour
		{
      $clear_it = true ;
		}
	}

  if( $clear_it )
  {
    @file_get_contents($config['website_url']."remove_xml_cache.php");
  }

	return;
}

function is_browsers_user_agent() {
	global $config;
	$is_browser	= false;

	preg_match( "/(".$config["browsers_user_agent"].")/i", $_SERVER['HTTP_USER_AGENT'], $matches );
	if ( isset($matches[1]) ) {
		$is_browser = true;
	}

	return $is_browser;
}

function is_stop_words($string) {
	global $config;
	$pattern        = "/(".$config["stop_words"].")/i";

	preg_match( $pattern, $string, $matches );
	if ( isset($matches[1]) && trim($matches[1]) != '' ) {
		return false;
	}
	return true;
}

function create_category_ids_cache($filename) {
	$sqlQuery	= "SELECT `id` FROM `".DB_PREFIX."categories` WHERE c_group = '0'
		AND ( enable_publishdate = 0 OR (enable_publishdate = 1 AND publishdate <= ".time()."))";
	$sqlResult	= dbQuery($sqlQuery);

	if ( mysql_num_rows($sqlResult) !== false ) {
	$temp_data	= '<?php
$cache_category_ids = array(
	';
	while($row	= mysql_fetch_array($sqlResult) ) {
		$temp_data	.= $row['id'].',';
	}
	$temp_data	.= ');
	?>';

	$fp = fopen($filename, "w");
	fwrite($fp, $temp_data);
	fclose($fp);
	}
}

if ( !function_exists('WriteToFile') ) {
function WriteToFile($filename, $content) {
	$handle = fopen($filename, 'w+');
	fwrite($handle, $content);
	fclose($handle);
}
}

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

function has_anchor_tag($string) {
	preg_match('/.*?\<a.*?\>(.*?)\<\/a\>.*?/i', $string, $matches);
	if ( isset($matches[1]) && trim($matches[1]) != '' ) {
		return true;
	}
	return false;
}

function prismo_print($string) {
	return $string;
}

function is_url_subdirectory($website_url) {
	$data_urls = parse_url($website_url);
	if ( ( isset($data_urls['path']) && trim($data_urls['path']) == '/' ) || !isset($data_urls['path']) ) {
		return false;
	}
	return true;
}

function is_spider() {
	$http_user_agent	= $_SERVER['HTTP_USER_AGENT'];

	if (!preg_match("/(bot|spider|yahoo|slurp)/i", $http_user_agent)) {
        return false;
    }
	return true;
}

function is_three_major_se() {
	$http_user_agent	= $_SERVER['HTTP_USER_AGENT'];
	$spiders    = array(
		'Googlebot', 'Yahoo', 'Slurp', 'msnbot', 'bingbot'
	);
	if ( is_spider() ) {
		foreach($spiders as $spider) {
			if ( preg_match("/".$spider."/i", $http_user_agent) ) {
				return true;
			}
		}
	}
	return false;
}

// Backup method for blocking useless search engines for prismotube installed under subdirectories.
// This is bcoz robots.txt isn't being processed in subdirs.
function block_nonmajor_searchengines() {
	global $config;

	if ( is_url_subdirectory($config['website_url']) )
	{
		if ( is_spider() )
		{
			if ( $config['allow_spiders'] == 2 && !is_three_major_se() ) {
				exit(0);
			}
		}
	}
}

// Block Google bots from entering Prismotube Mobile
function block_googlebots_mobile() {
	$http_user_agent = $_SERVER['HTTP_USER_AGENT'];

	if ( defined('IS_MOBILE') && IS_MOBILE ) {
		if ( preg_match("/google web preview|googlebot/i", $http_user_agent) ) {
			exit(0);
		}
	}
}

function get_time_ago($time_difference)
{
	if ($time_difference >= 60 * 60 * 24 * 365.242199)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 365.242199 days/year
		 * This means that the time difference is 1 year or more
		 */
		return get_time_ago_string($time_difference, 60 * 60 * 24 * 365.242199, 'year');
	}
	elseif ($time_difference >= 60 * 60 * 24 * 30.4368499)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 30.4368499 days/month
		 * This means that the time difference is 1 month or more
		 */
		return get_time_ago_string($time_difference, 60 * 60 * 24 * 30.4368499, 'month');
	}
	elseif ($time_difference >= 60 * 60 * 24 * 7)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 7 days/week
		 * This means that the time difference is 1 week or more
		 */
		return get_time_ago_string($time_difference, 60 * 60 * 24 * 7, 'week');
	}
	elseif ($time_difference >= 60 * 60 * 24)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour * 24 hours/day
		 * This means that the time difference is 1 day or more
		 */
		return get_time_ago_string($time_difference, 60 * 60 * 24, 'day');
	}
	elseif ($time_difference >= 60 * 60)
	{
		/*
		 * 60 seconds/minute * 60 minutes/hour
		 * This means that the time difference is 1 hour or more
		 */
		return get_time_ago_string($time_difference, 60 * 60, 'hour');
	}
	else
	{
		/*
		 * 60 seconds/minute
		 * This means that the time difference is a matter of minutes
		 */
		return get_time_ago_string($time_difference, 60, 'minute');
	}
}

function get_time_ago_string($time_difference, $divisor, $time_unit)
{
	$time_units      = floor($time_difference / $divisor);

	settype($time_units, 'string');

	if ($time_units === '0')
	{
		return 'less than 1 ' . $time_unit . ' ago';
	}
	elseif ($time_units === '1')
	{
		return '1 ' . $time_unit . ' ago';
	}
	else
	{
		/*
		 * More than "1" $time_unit. This is the "plural" message.
		 */
		// TODO: This pluralizes the time unit, which is done by adding "s" at the end; this will not work for i18n!
		return $time_units . ' ' . $time_unit . 's ago';
	}
}


?>