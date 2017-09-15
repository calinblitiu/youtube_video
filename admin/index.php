<?php
@session_start();
setlocale(LC_ALL, 'en_US.UTF-8');
@define("DS", DIRECTORY_SEPARATOR);

include_once("../config/db_config.php");
include('inc/includes.php');
include_once("../config/config_filter.php");
include_once("../config/config.php");

if ( !isset($_SERVER['SCRIPT_URI']) ) {
	$curPageURL	= 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
} else {
	$curPageURL	= 'http://'.$_SERVER["SCRIPT_URI"];
}
/*
if ( stripos($curPageURL, $config['website_url']) === false ) {
	header("Location: ".$config['website_url']."admin");
	exit(0);
}
*/
$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	include('inc/auth.php');
	die();
}

$cache_dir			= '../'.$config["html_cache_dir"];

if ( isset($_GET['get_license']) && $_GET['get_license'] == 1 ) {
	if ( file_exists($cache_dir."license.html") ) {
		unlink($cache_dir."license.html");
	}
	header("Location: index.php");
	exit(0);
}

// Check New Version
$new_version		= url_get_contents("http://alurian.com/members/data.php?request=versioncheck&product=prismotube", $cache_dir."version.html");
$contents_part = explode('||', $new_version);

$contents_part[1] = trim( $contents_part[1] );


$str_version		= "";
$new_version		= (float) $contents_part[0];

if ( $new_version > PT_VERSION )
{
  if( !empty( $contents_part[1] ) )
  {
    $str_version = $contents_part[1];
  }
  else
  {
    $str_version		= "New Version ({$new_version}) is available. <a href=\"http://www.alurian.com/prismotube\" target=\"_blank\">Upgrade now</a>";
  }
}

$is_install_exists = false;

if ( file_exists("../install") ) {
	$is_install_exists	= true;
}

$datas_seconds	= array(
	'900' => '15 Minutes',
	'3600' => '1 Hour',
	'86400' => '1 Day (recommended)',
	'592200' => '1 Month',
);
$data_sort_videos_by = array(
	'relevance' => 'Relevance',
	'viewCount' => 'View Count',
	'published' => 'Publish Date (Newest First)',
	'rating' => 'Rating',
);
$str_blk_xml_cache_timeout = "";
$str_blk_tags_cache_timeout = "";
$str_blk_stats_cache_timeout = "";
$str_blk_rss_cache_timeout = "";
$str_blk_categories_cache_timeout = "";

if ( in_array($config["categories_cache_timeout"], array_flip($datas_seconds) ) ) {
	$str_blk_categories_cache_timeout = "display:none;";
} else {
	$str_blk_categories_cache_timeout = "display:block;";
}

if ( in_array($config["xml_cache_timeout"], array_flip($datas_seconds) ) ) {
	$str_blk_xml_cache_timeout = "display:none;";
} else {
	$str_blk_xml_cache_timeout = "display:block;";
}

if ( in_array($config["tags_cache_timeout"], array_flip($datas_seconds) ) ) {
	$str_blk_tags_cache_timeout = "display:none;";
} else {
	$str_blk_tags_cache_timeout = "display:block;";
}

if ( in_array($config["stats_cache_timeout"], array_flip($datas_seconds) ) ) {
	$str_blk_stats_cache_timeout = "display:none;";
} else {
	$str_blk_stats_cache_timeout = "display:block;";
}

if ( in_array($config["rss_cache_timeout"], array_flip($datas_seconds) ) ) {
	$str_blk_rss_cache_timeout = "display:none;";
} else {
	$str_blk_rss_cache_timeout = "display:block;";
}

$languages_code	= array(
	'all' => 'All Languages',
	'af' => 'Afrikaans',
	'ar' => 'Arabic',
	'bg' => 'Bulgarian',
	'my' => 'Burmese',
	'zh' => 'Chinese',
	'cs' => 'Czech',
	'da' => 'Danish',
	'nl' => 'Dutch',
	'en' => 'English',
	'fi' => 'Finnish',
	'fr' => 'France',
	'de' => 'German',
	'el' => 'Greek',
	'hi' => 'Hindi',
	'id' => 'Indonesian',
	'ga' => 'Irish',
	'it' => 'Italian',
	'ja' => 'Japanese',
	'ko' => 'Korean',
	'la' => 'Latin',
	'ms' => 'Malay',
	'no' => 'Norwegian',
	'fa' => 'Persian',
	'pt' => 'Portuguese',
	'ru' => 'Russian',
	'es' => 'Spanish',
	'sv' => 'Swedish',
	'ta' => 'Tamil',
	'tl' => 'Tagalog',
	'th' => 'Thai',
	'tr' => 'Turkish',
	'vi' => 'Vietnamese',
);

$lang_code	= array();

$logo_path	= "../images";

$lang_path	= "../language";
if ($handle = opendir($lang_path)) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        if ( $file != '' && $file != '.' && $file != '..' ) {
			if ( is_dir($lang_path.DS.$file) ) {
				if ( file_exists($lang_path.DS.$file.DS."info.txt") ) {
					$txt_info_desc	= file_get_contents($lang_path.DS.$file.DS."info.txt");
					$lang_code[$file]	= $txt_info_desc;
				}
			}
		}
    }
    closedir($handle);
}

$virtual_keyboard_languages = array(
0 => array(
'language' => 'Albanian',
'language_code' => 'sq',
'layout_code' => 'ALBANIAN',
),1 => array(
'language' => 'Arabic',
'language_code' => 'ar',
'layout_code' => 'ARABIC',
),2 => array(
'language' => 'Armenian Eastern',
'language_code' => 'hy_east',
'layout_code' => 'ARMENIAN_EASTERN',
),3 => array(
'language' => 'Armenian Western',
'language_code' => 'hy_west',
'layout_code' => 'ARMENIAN_WESTERN',
),4 => array(
'language' => 'Basque',
'language_code' => 'eu',
'layout_code' => 'BASQUE',
),5 => array(
'language' => 'Belarusian',
'language_code' => 'be',
'layout_code' => 'BELARUSIAN',
),6 => array(
'language' => 'Bengali Phonetic',
'language_code' => 'bn_phone',
'layout_code' => 'BENGALI_PHONETIC',
),7 => array(
'language' => 'Bosnian',
'language_code' => 'bs',
'layout_code' => 'BOSNIAN',
),8 => array(
'language' => 'Brazilian Portuguese',
'language_code' => 'pt_br',
'layout_code' => 'BRAZILIAN_PORTUGUESE',
),9 => array(
'language' => 'Bulgarian',
'language_code' => 'bg',
'layout_code' => 'BULGARIAN',
),10 => array(
'language' => 'Catalan',
'language_code' => 'ca',
'layout_code' => 'CATALAN',
),11 => array(
'language' => 'Croatian',
'language_code' => 'hr',
'layout_code' => 'CROATIAN',
),12 => array(
'language' => 'Czech',
'language_code' => 'cs',
'layout_code' => 'CZECH',
),13 => array(
'language' => 'Czech Qwertz',
'language_code' => 'cs_qwertz',
'layout_code' => 'CZECH_QWERTZ',
),14 => array(
'language' => 'Danish',
'language_code' => 'da',
'layout_code' => 'DANISH',
),15 => array(
'language' => 'Dari',
'language_code' => 'prs',
'layout_code' => 'DARI',
),16 => array(
'language' => 'Dutch',
'language_code' => 'nl',
'layout_code' => 'DUTCH',
),17 => array(
'language' => 'Devanagari Phonetic',
'language_code' => 'deva_phone',
'layout_code' => 'DEVANAGARI_PHONETIC',
),18 => array(
'language' => 'English',
'language_code' => 'en',
'layout_code' => 'ENGLISH',
),19 => array(
'language' => 'Estonian',
'language_code' => 'et',
'layout_code' => 'ESTONIAN',
),20 => array(
'language' => 'Ethiopic',
'language_code' => 'ethi',
'layout_code' => 'ETHIOPIC',
),21 => array(
'language' => 'Finnish',
'language_code' => 'fi',
'layout_code' => 'FINNISH',
),22 => array(
'language' => 'French',
'language_code' => 'fr',
'layout_code' => 'FRENCH',
),23 => array(
'language' => 'Galician',
'language_code' => 'gl',
'layout_code' => 'GALICIAN',
),24 => array(
'language' => 'Georgian Qwerty',
'language_code' => 'ka_qwerty',
'layout_code' => 'GEORGIAN_QWERTY',
),25 => array(
'language' => 'Georgian Typewriter',
'language_code' => 'ka_typewriter',
'layout_code' => 'GEORGIAN_TYPEWRITER',
),26 => array(
'language' => 'German',
'language_code' => 'de',
'layout_code' => 'GERMAN',
),27 => array(
'language' => 'Greek',
'language_code' => 'el',
'layout_code' => 'GREEK',
),28 => array(
'language' => 'Gujarati Phonetic',
'language_code' => 'gu_phone',
'layout_code' => 'GUJARATI_PHONETIC',
),29 => array(
'language' => 'Gurmukhi Phonetic',
'language_code' => 'guru_phone',
'layout_code' => 'GURMUKHI_PHONETIC',
),30 => array(
'language' => 'Hebrew',
'language_code' => 'he',
'layout_code' => 'HEBREW',
),31 => array(
'language' => 'Hindi',
'language_code' => 'hi',
'layout_code' => 'HINDI',
),32 => array(
'language' => 'Hungarian 101',
'language_code' => 'hu_101',
'layout_code' => 'HUNGARIAN_101',
),33 => array(
'language' => 'Icelandic',
'language_code' => 'is',
'layout_code' => 'ICELANDIC',
),34 => array(
'language' => 'Italian',
'language_code' => 'it',
'layout_code' => 'ITALIAN',
),35 => array(
'language' => 'Kannada Phonetic',
'language_code' => 'kn_phone',
'layout_code' => 'KANNADA_PHONETIC',
),36 => array(
'language' => 'Kazakh',
'language_code' => 'kk',
'layout_code' => 'KAZAKH',
),37 => array(
'language' => 'Khmer',
'language_code' => 'km',
'layout_code' => 'KHMER',
),38 => array(
'language' => 'Korean',
'language_code' => 'ko',
'layout_code' => 'KOREAN',
),39 => array(
'language' => 'Kyrgyz',
'language_code' => 'ky_cyrl',
'layout_code' => 'KYRGYZ',
),40 => array(
'language' => 'Lao',
'language_code' => 'lo',
'layout_code' => 'LAO',
),41 => array(
'language' => 'Latvian',
'language_code' => 'lv',
'layout_code' => 'LATVIAN',
),42 => array(
'language' => 'Lithuanian',
'language_code' => 'lt',
'layout_code' => 'LITHUANIAN',
),43 => array(
'language' => 'Macedonian',
'language_code' => 'mk',
'layout_code' => 'MACEDONIAN',
),44 => array(
'language' => 'Malayalam Phonetic',
'language_code' => 'ml_phone',
'layout_code' => 'MALAYALAM_PHONETIC',
),45 => array(
'language' => 'Maltese',
'language_code' => 'mt',
'layout_code' => 'MALTESE',
),46 => array(
'language' => 'Mongolian Cyrillic',
'language_code' => 'mn_cyrl',
'layout_code' => 'MONGOLIAN_CYRILLIC',
),47 => array(
'language' => 'Montenegrin',
'language_code' => 'srp',
'layout_code' => 'MONTENEGRIN',
),48 => array(
'language' => 'Norwegian',
'language_code' => 'no',
'layout_code' => 'NORWEGIAN',
),49 => array(
'language' => 'Oriya Phonetic',
'language_code' => 'or_phone',
'layout_code' => 'ORIYA_PHONETIC',
),50 => array(
'language' => 'Pan Africa Latin',
'language_code' => 'latn_002',
'layout_code' => 'PAN_AFRICA_LATIN',
),51 => array(
'language' => 'Pashto',
'language_code' => 'ps',
'layout_code' => 'PASHTO',
),52 => array(
'language' => 'Persian',
'language_code' => 'fa',
'layout_code' => 'PERSIAN',
),53 => array(
'language' => 'Polish',
'language_code' => 'pl',
'layout_code' => 'POLISH',
),54 => array(
'language' => 'Portuguese',
'language_code' => 'pt_pt',
'layout_code' => 'PORTUGUESE',
),55 => array(
'language' => 'Romani',
'language_code' => 'rom',
'layout_code' => 'ROMANI',
),56 => array(
'language' => 'Romanian',
'language_code' => 'ro',
'layout_code' => 'ROMANIAN',
),57 => array(
'language' => 'Russian',
'language_code' => 'ru',
'layout_code' => 'RUSSIAN',
),58 => array(
'language' => 'Sanskrit Phonetic',
'language_code' => 'sa_phone',
'layout_code' => 'SANSKRIT_PHONETIC',
),59 => array(
'language' => 'Serbian Cyrillic',
'language_code' => 'sr_cyrl',
'layout_code' => 'SERBIAN_CYRILLIC',
),60 => array(
'language' => 'Serbian Latin',
'language_code' => 'sr_latn',
'layout_code' => 'SERBIAN_LATIN',
),61 => array(
'language' => 'Sinhala',
'language_code' => 'si',
'layout_code' => 'SINHALA',
),62 => array(
'language' => 'Slovak',
'language_code' => 'sk',
'layout_code' => 'SLOVAK',
),63 => array(
'language' => 'Slovak Qwerty',
'language_code' => 'sk_qwerty',
'layout_code' => 'SLOVAK_QWERTY',
),64 => array(
'language' => 'Slovenian',
'language_code' => 'sl',
'layout_code' => 'SLOVENIAN',
),65 => array(
'language' => 'Southern Uzbek',
'language_code' => 'uzs',
'layout_code' => 'SOUTHERN_UZBEK',
),66 => array(
'language' => 'Spanish',
'language_code' => 'es_es',
'layout_code' => 'SPANISH',
),67 => array(
'language' => 'Swedish',
'language_code' => 'sv',
'layout_code' => 'SWEDISH',
),68 => array(
'language' => 'Tamil Phonetic',
'language_code' => 'ta_phone',
'layout_code' => 'TAMIL_PHONETIC',
),69 => array(
'language' => 'Tatar',
'language_code' => 'tt',
'layout_code' => 'TATAR',
),70 => array(
'language' => 'Telugu Phonetic',
'language_code' => 'te_phone',
'layout_code' => 'TELUGU_PHONETIC',
),71 => array(
'language' => 'Thai',
'language_code' => 'th',
'layout_code' => 'THAI',
),72 => array(
'language' => 'Turkish F',
'language_code' => 'tr_f',
'layout_code' => 'TURKISH_F',
),73 => array(
'language' => 'Turkish Q',
'language_code' => 'tr_q',
'layout_code' => 'TURKISH_Q',
),74 => array(
'language' => 'Uighur',
'language_code' => 'ug',
'layout_code' => 'UIGHUR',
),75 => array(
'language' => 'Ukrainian 101',
'language_code' => 'uk_101',
'layout_code' => 'UKRAINIAN_101',
),76 => array(
'language' => 'Urdu',
'language_code' => 'ur',
'layout_code' => 'URDU',
),77 => array(
'language' => 'Uzbek Latin',
'language_code' => 'uz_latn',
'layout_code' => 'UZBEK_LATIN',
),78 => array(
'language' => 'Uzbek Cyrillic Phonetic',
'language_code' => 'uz_cyrl_phone',
'layout_code' => 'UZBEK_CYRILLIC_PHONETIC',
),79 => array(
'language' => 'Uzbek Cyrillic Typewritter',
'language_code' => 'uz_cyrl_type',
'layout_code' => 'UZBEK_CYRILLIC_TYPEWRITTER',
),80 => array(
'language' => 'Vietnamese Tcvn',
'language_code' => 'vi_tcvn',
'layout_code' => 'VIETNAMESE_TCVN',
),81 => array(
'language' => 'Vietnamese Telex',
'language_code' => 'vi_telex',
'layout_code' => 'VIETNAMESE_TELEX',
),82 => array(
'language' => 'Vietnamese Viqr',
'language_code' => 'vi_viqr',
'layout_code' => 'VIETNAMESE_VIQR',
),
);

if ($_GET["logout"] == "yes"){
    $_SESSION['logged']=FALSE;//LOGOUT
	include('templates/login/header.php');
	echo '<meta http-equiv="refresh" content="1;url='.$config["website_url"].'admin"/>';
 	echo '<div align="center" class="success">Logging out...</div>';
	include('templates/login/footer.php');
	if (isset($_GET["route"])) {
		$route = $_GET["route"];
		header("Location: $route");
	}
	exit();
}// END if

if($config["default_filter_value"] != $_POST["default_filter_value"]) {
	$filter = ($config['default_filter_value'] == "on") ? "exclude" : "include";
	setcookie('filter',$filter,0,'');
	$_COOKIE['filter']=$filter;
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( $config["view_setting"] != $_POST['view_setting'] ) {
		$view_setting = $_POST['view_setting'];
		setcookie('list_mode',$view_setting,0,'');
		$_COOKIE['list_mode']=$view_setting;
	}
	if ( !isset($_POST['links_enabled']) ){
		$_POST['links_enabled'] = $config['links_enabled'];
	}
}

//if (isset($_POST["Submit"])) {
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

if ( isset($_FILES['file_import']) && !isDemo()) {


	if ( !defined('BASE_PATH') ) {
		define("BASE_PATH", '../' );
	}

	$file_ext	= strtolower(end(explode(".", $_FILES['file_import']['name'])));
	if ( $file_ext == 'csv' ) {

		$CategoryObject = new categories();
		$CategoryObject->clear_categories_cache();

		if ( isset($_POST['opt_truncate']) && $_POST['opt_truncate'] == 1 ) {
			$sqlQuery	= "TRUNCATE TABLE `".DB_PREFIX."categories`;";
			dbQuery($sqlQuery);
		}

		$idx2 = 0;
		$idx			= 0;
		$category_datas	= array();
		$parent_cats	= array();
		$is_simple		= false;

		$handle_import_csv = fopen($_FILES['file_import']['tmp_name'], "r");
		while (($data = fgetcsv($handle_import_csv, 1024, ",")) !== FALSE) {

			if ( $idx2 == 0 && stripos($data[3], 'listing source') !== false ) {
			} else if ( count($data) == 9 ) {
			} else if ( count($data) == 2 ) {
			} else if ( $idx2 == 0 ) {
				$is_simple = true;
			}

			if(isset($data[1]) && stripos($data[1], 'keyword') == false && stripos($data[2], 'Avg. CPC') == false && stripos($data[2], 'Avg. CPC') == false && stripos($data[3], 'local searches') == false && stripos($data[4], 'global searches') == false && stripos($data[4], 'Advertiser competition') == false)
			{
				if($idx2 > 0 && isset($data[1]))
				{
					if(preg_match('/[^\w\s]/i', $data[1]) !== false)
					{
						$keyword = ucfirst(trim(preg_replace('/[^\w\s]/i', '', $data[1]), '_'));
					}
					else
					{
						$keyword = ucfirst(trim($data[1], '_'));
					}
					$category = $keyword;
					$parent = '';
					$listing_source = 'keyword';
					$user_videos	= '';
					$users_playlist	= '';
					$playlist_id	= '';
					$group			= '';
					$temp_category	= array(
						'category' => $category,
						'parent' => $parent,
						'description' => $description,
						'listing_source' => strtolower($listing_source),
						'keyword' => $keyword,
						'user_videos' => $user_videos,
						'users_playlist' => $users_playlist,
						'playlist_id' => $playlist_id,
						'group' => $group,
					);
					$category_datas[$idx] = $temp_category;
					$idx++;
				}
			}
			elseif ( isset($data[3]) && strtolower($data[3]) != 'listing source' && $data[3] != '' && !$is_simple) {
				$category		= $data[0];
				$parent			= $data[1];
				$description	= $data[2];
				$listing_source	= $data[3];
				$keyword		= $data[4];
				$user_videos	= $data[5];
				$users_playlist	= $data[6];
				$playlist_id	= $data[7];
				$group			= $data[8];

				$temp_category	= array(
					'category' => $category,
					'parent' => $parent,
					'description' => $description,
					'listing_source' => strtolower($listing_source),
					'keyword' => $keyword,
					'user_videos' => $user_videos,
					'users_playlist' => $users_playlist,
					'playlist_id' => $playlist_id,
					'group' => $group,
				);

				if ( trim($parent) == '' ) {
					if ( !in_array($parent, $parent_cats) ) {
						$parent_cats[$idx] = $category;
					}
				}
				$category_datas[$idx] = $temp_category;

				$idx++;
			} else if ( isset($data[0]) && $data[0] != 'Keyword' && $data[0] != '' && $is_simple) {

				if ( isset($data[1]) && !in_array(strtolower($data[1]), array('google', 'youtube', 'amazon', 'yahoo') ) ) {
					continue;
				}

				$category		= ucfirst($data[0]);
				$parent			= '';
				$description	= $data[0];
				$listing_source	= 'keyword';
				$keyword		= $data[0];
				$user_videos	= '';
				$users_playlist	= '';
				$playlist_id	= '';
				$group			= '';

				$temp_category	= array(
					'category' => $category,
					'parent' => $parent,
					'description' => $description,
					'listing_source' => strtolower($listing_source),
					'keyword' => $keyword,
					'user_videos' => $user_videos,
					'users_playlist' => $users_playlist,
					'playlist_id' => $playlist_id,
					'group' => $group,
				);

				$category_datas[$idx] = $temp_category;
				$idx++;
			}
			$idx2++;
		}
		fclose($handle_import_csv);

		foreach($category_datas as $key => $category_data) {
			$parents	= array();
			if ( trim($category_data['parent']) != '' ) {
				$parent_key		= array_search($category_data['parent'], $parent_cats);
				$parents		= get_parent_name($parent_key, $category_datas, array());
			}

			$parent_str			= '';
			if ( count($parents) > 0 ) {
				$parent_cat_id	= 0;
				$max	= count($parents) - 1;
				for($i = $max; $i >= 0; $i--) {
					$sqlQuery	= "SELECT `id` FROM `".DB_PREFIX."categories` WHERE LCASE(`c_name`) = '".db_escape_string($parents[$i])."'";
					$res10 		= dbQuery($sqlQuery, true);
					list($parent_cat_id) = mysql_fetch_row($res10);
					$parent_str	.= $parent_cat_id.'>';
				}
			}

			$sqlQuery			= "INSERT INTO `".DB_PREFIX."categories` SET `c_name` = '".db_escape_string($category_data['category'])."',
				`c_listing_source` = '".db_escape_string($category_data['listing_source'])."',
				`c_desc` = '".db_escape_string($category_data['description'])."',
				`c_keyword` = '".db_escape_string($category_data['keyword'])."',
				`c_user_videos` = '".db_escape_string($category_data['user_videos'])."',
				`c_playlist_id` = '".db_escape_string($category_data['playlist_id'])."',
				`c_group` = 0,
				`date_added` = '".date("Y-m-d")."'
				";
			$res10 		= dbQuery($sqlQuery);

			$new_category_id	= mysql_insert_id();

			$parent_str			.= $new_category_id.'>';
			$sqlQuery		= "UPDATE `".DB_PREFIX."categories` SET `position` = '{$parent_str}' WHERE `id` = {$new_category_id}";
			dbQuery($sqlQuery, false);
		}
	}
}

$default_category_id	= 0;
if ( isset($_POST['default_category_id']) ) {
	$default_category_id	= $_POST['default_category_id'];
}

if(isset($_POST["active_template"])) {
	deleteFiles("../templates_c/");
}
$errors = checkForm();

if($errors == "" && !$aConfig["ADMIN_DEMO"]) {
	$website_logo_file = $_FILES['website_logo'];


	if ( isset($website_logo_file['size']) && $website_logo_file['size'] > 0 && is_writable($logo_path) ) {
		if ( file_exists($logo_path.DS.$config['website_logo']) ) {
			unlink($logo_path.DS.$config['website_logo']);
		}
		$file_ext		= end(explode(".", $website_logo_file['name']));
		$logo_filename	= basename($website_logo_file['name'], ".".$file_ext);
		$logo_filename	= generate_seo_link($logo_filename);
		$dest_path		= $logo_path.DS.$logo_filename.".".$file_ext;

		@move_uploaded_file($website_logo_file['tmp_name'], $dest_path);
		$config['website_logo'] = $logo_filename.".".$file_ext;
	}

if ( isset($_POST['categories_cache_timeout_opt']) && trim($_POST['categories_cache_timeout_opt']) != '' ) {
	$categories_cache_timeout	= $_POST['categories_cache_timeout_opt'];
} else {
	$categories_cache_timeout	= $_POST['categories_cache_timeout'];
}

if ( isset($_POST['xml_cache_timeout_opt']) && trim($_POST['xml_cache_timeout_opt']) != '' ) {
	$xml_cache_timeout	= $_POST['xml_cache_timeout_opt'];
} else {
	$xml_cache_timeout	= $_POST['xml_cache_timeout'];
}

if ( isset($_POST['tags_cache_timeout_opt']) && trim($_POST['tags_cache_timeout_opt']) != '' ) {
	$tags_cache_timeout	= $_POST['tags_cache_timeout_opt'];
} else {
	$tags_cache_timeout	= $_POST['tags_cache_timeout'];
}

if ( isset($_POST['stats_cache_timeout_opt']) && trim($_POST['stats_cache_timeout_opt']) != '' ) {
	$stats_cache_timeout= $_POST['stats_cache_timeout_opt'];
} else {
	$stats_cache_timeout	= $_POST['stats_cache_timeout'];
}

if ( isset($_POST['rss_cache_timeout_opt']) && trim($_POST['rss_cache_timeout_opt']) != '' ) {
	$rss_cache_timeout	= $_POST['rss_cache_timeout_opt'];
} else {
	$rss_cache_timeout	= $_POST['rss_cache_timeout'];
}

if ( !get_magic_quotes_gpc() ) {

	$website_name			= $_POST['website_name'];
	$website_slogan			= $_POST['website_slogan'];
	$meta_description			= $_POST['meta_description'];
	$meta_keywords			= $_POST['meta_keywords'];

	$website_main_keyword	= addslashes($_POST["website_main_keyword"]);
} else {

	$website_name			= stripslashes($_POST['website_name']);
	$website_slogan			= stripslashes($_POST['website_slogan']);
	$meta_description		= stripslashes($_POST['meta_description']);
	$meta_keywords			= stripslashes($_POST['meta_keywords']);
	$website_main_keyword	= $_POST["website_main_keyword"];
}

if ( trim($xml_cache_timeout) == '' ) $xml_cache_timeout = 0;
if ( trim($categories_cache_timeout) == '' ) $categories_cache_timeout = 0;
if ( trim($tags_cache_timeout) == '' ) $tags_cache_timeout = 0;
if ( trim($stats_cache_timeout) == '' ) $stats_cache_timeout = 0;
if ( trim($rss_cache_timeout) == '' ) $rss_cache_timeout = 0;

$new_website_url	= "";
if ( substr($_POST["website_url"], -1, 1) == '/' ) {
	$new_website_url = $_POST['website_url'];
} else {
	$new_website_url = $_POST['website_url'].'/';
}

$licenseData = '<?php
### License Key ###
// License Key
$config["license_key"] = "'. $_POST["license_key"]. '";
?>';

$configData = '<?php
#### PLEASE DO NOT EDIT THIS FILE MANUALLY ####
#### If you want to edit the configuration, do it by logging in to the admin interface ####
#### Editing manually may cause this file to be corrupted and the site will not load as a result ####

### Main Settings ###
// Website Name
$config["website_name"] = "'. htmlspecialchars($website_name). '";
// Website Slogan
$config["website_slogan"] = "'. htmlspecialchars($website_slogan). '";
// Website Url with slash
$config["website_url"] = "'. $new_website_url. '";
// Website Description
$config["meta_description"] = "'. htmlspecialchars($meta_description). '";
// Website Keywords
$config["meta_keywords"] = "'. htmlspecialchars($meta_keywords). '";
$config["website_main_keyword"] = "'. $website_main_keyword. '";

### Player Settings ###
// [true / false] if false, will play in a regular flash player
$config["youtube_player"] = '. $_POST["youtube_player"]. ';
// [true / false] -> only if using custom player
$config["enable_player_playlist"] = '. $_POST["enable_player_playlist"]. ';
$config["enable_home_player"] = '. $_POST["enable_home_player"]. ';
$config["cplayer_skin"] = "'. $_POST["cplayer_skin"]. '";
$config["player_custom_plugins_home"] = "'. cleanCode($_POST["player_custom_plugins_home"]). '";
$config["player_custom_plugins_detail"] = "'. cleanCode($_POST["player_custom_plugins_detail"]). '";
$config["player_custom_plugins_detail_playlist"] = "'. cleanCode($_POST["player_custom_plugins_detail_playlist"]). '";
// [true / false]
$config["enable_player_colors"] = '. $_POST["enable_player_colors"]. ';
$config["yt_developer_key"] = "'. $_POST["yt_developer_key"]. '";

### Cache Settings ###
$config["categories_cache_enable"] = '. $_POST["categories_cache_enable"]. ';
$config["xml_cache_enable"] = true;
// Cache XML Directory Path with slash
$config["xml_cache_dir"] = "'.$_POST["xml_cache_dir"]. '";
// Cache HTML Directory Path with slash
$config["html_cache_dir"] = "'.$_POST["html_cache_dir"]. '";
$config["logs_cache_dir"] = "cache/logs/";
$config["cache_storage_format"] = "'.$_POST["cache_storage_format"]. '";
$config["categories_cache_timeout"] = '. $categories_cache_timeout. ';
$config["xml_cache_timeout"] = '. $xml_cache_timeout. ';
// [1 to 50] how often cache tags on every page load
$config["tags_cache_timeout"] = '. $tags_cache_timeout. ';
// [1 to 50] how often cache stats on every page load
$config["stats_cache_timeout"] = 900;
// [1 to 50] how often cache new videos for a specific tag, 1 will cache on every page load
$config["rss_cache_timeout"] = '. $rss_cache_timeout. ';

### Display Settings ###
// [on / off]
$config["default_filter_value"] = "'. $_POST["default_filter_value"]. '";
$config["web_default_language"] = "'. $_POST["web_default_language"]. '";
$config["list_per_page"] = '. $_POST["list_per_page"]. ';
$config["list_on_home_page"] = '. $_POST["list_on_home_page"]. ';
$config["list_on_feed_page"] = '. $_POST["list_on_feed_page"]. ';
$config["user_uploaded_list_per_page"] = '. $_POST["user_uploaded_list_per_page"]. ';
$config["user_favorites_list_per_page"] = '. $_POST["user_favorites_list_per_page"]. ';
$config["related_videos_position"] = "'. $_POST["related_videos_position"]. '";
$config["active_template"] = "'. $_POST["active_template"]. '";
$config["active_theme"] = "";
$config["view_setting"] = "'. $_POST["view_setting"]. '";
$config["sort_videos_by"] = "'. $_POST["sort_videos_by"]. '";

### Tags Settings ###
$config["tags_selection"] = ' . (isset($_POST["tags_selection"]) ? '"'.$_POST["tags_selection"].'"' : 'random') . ';
$config["tag_cloud_enabled"] = '. (isset($_POST["tag_cloud_enabled"]) ? $_POST["tag_cloud_enabled"] : 'false') . ';
// Maximum tags to display
$config["tags_max_display"] = '. (isset($_POST["tags_max_display"]) ? $_POST["tags_max_display"] : 30). ';
//tags font size settings
$config["tag_max_size"] = '. (isset($_POST["tag_max_size"]) ? $_POST["tag_max_size"] : 150). ';
$config["tag_min_size"] = '. (isset($_POST["tag_min_size"]) ? $_POST["tag_min_size"] : 90). ';

### Comments ###
$config["facebook_comments_enabled"] = ' . (isset($_POST["facebook_comments_enabled"]) ? $_POST["facebook_comments_enabled"] : false). ';
$config["facebook_app_id"] = '. (isset($_POST["facebook_app_id"]) ? '"'.$_POST["facebook_app_id"].'"' : "") .';
$config["local_comments_enabled"] = '. (isset($_POST["local_comments_enabled"]) ? $_POST["local_comments_enabled"] : 'false'). ';
$config["local_comments_per_page"] = '. (isset($_POST["local_comments_per_page"]) ? $_POST["local_comments_per_page"] : 10). ';
$config["youtube_comments_enabled"] = '. (isset($_POST["youtube_comments_enabled"]) ? $_POST["youtube_comments_enabled"] : 'false'). ';

### Links ###
$config["links_enabled"] = '. $_POST["links_enabled"]. ';

### Longtail video ads ###
$config["longtail_enabled"] = '. (isset($_POST["longtail_enabled"]) ? $_POST["longtail_enabled"] : 'false'). ';
$config["longtail_channel"] = "'. $_POST["longtail_channel"]. '";

### Tracking Code ###
$config["header_code"] = "'. cleanCode($_POST["header_code"]). '";
$config["footer_code"] = "'. cleanCode($_POST["footer_code"]). '";
$config["google_web_fonts_enabled"] = "'. $_POST["google_web_fonts_enabled"]. '";
$config["video_search_enabled"] = "'. $_POST["video_search_enabled"]. '";
$config["video_upload_enabled"] = "'. $_POST["video_upload_enabled"]. '";
$config["yt_username"] = "'. $_POST["yt_username"]. '";
$config["yt_password"] = "'. $_POST["yt_password"]. '";
$config["yt_dev_key"] =  "'. $_POST["yt_developer_key"]. '";
$config["video_language_specific"] =  "'. $_POST["video_language_specific"]. '";
$config["default_category_id"] =  "'. $default_category_id. '";
$config["dropdown_limit"] = "'.(isset($config["dropdown_limit"]) ? $config["dropdown_limit"] : 15).'";
$config["tags_enabled"] = "'. $_POST["tags_enabled"]. '";
$config["facebook_enabled"] = "'. $_POST["facebook_enabled"]. '";
$config["facebook_stream"] = "'. $_POST["facebook_stream"]. '";
$config["facebook_page_url"] = "'. $_POST["facebook_page_url"]. '";
$config["allow_spiders"] = '.((isset($_POST['allow_spiders']) && $_POST['allow_spiders'] == 2) ? 2 : 1).';
$config["search_log_enabled"] = "'. $_POST["search_log_enabled"]. '";
$config["empty_categories_notification_enabled"] = "'. $_POST["empty_categories_notification_enabled"]. '";
$config["wibiya_enabled"] = "'. $_POST["wibiya_enabled"]. '";
$config["wibiya_code"] = "'. cleanCode($_POST["wibiya_code"]). '";
$config["website_logo"] = "'. $config["website_logo"]. '";
$config["debug_mode_enabled"] = "'. $_POST["debug_mode_enabled"]. '";
$config["shoutbox_enabled"] = "'. $_POST["shoutbox_enabled"]. '";
$config["shoutbox_code"] = "'. cleanCode($_POST["shoutbox_code"]). '";
$config["addthis_enabled"] = '. $_POST["addthis_enabled"] .';
$config["addthis_profile_id"] = "'. $_POST["addthis_profile_id"] .'";
$config["addthis_style"] = "'. (isset($_POST["addthis_style"]) ? $_POST["addthis_style"] : 3) .'";
$config["skysa_bar_enabled"] = "'. $_POST["skysa_bar_enabled"]. '";
$config["skysa_bar_code"] = "'. cleanCode($_POST["skysa_bar_code"]). '";
$config["search_term"] = "'. cleanCode($_POST["search_term"]). '";
$config["virtual_keyboard_enabled"] = "'.(isset($_POST["virtual_keyboard_enabled"]) ? $_POST["virtual_keyboard_enabled"] : 'false').'";
$config["virtual_keyboard_default_language"] = "'. ((isset($_POST["virtual_keyboard_default_language"])) ? $_POST["virtual_keyboard_default_language"] : 0). '";
$config["virtual_keyboard_layout_code"] = "'. ((isset($virtual_keyboard_languages[$_POST["virtual_keyboard_default_language"]]['layout_code'])) ? $virtual_keyboard_languages[$_POST["virtual_keyboard_default_language"]]['layout_code'] : ''). '";';

/* for version 3.5 */
if ( isset($_POST['twitter_enabled']) ) {
$configData .= '
$config["twitter_enabled"] =  "'. $_POST['twitter_enabled']. '";
$config["rd_twitter_type"] =  "'. $_POST['rd_twitter_type']. '";
$config["twitter_key"] =  "'. $_POST['twitter_key']. '";
$config["twitter_search_mode"] =  "'. $_POST['twitter_search_mode']. '";
$config["num_twitter_display"] = "'. $_POST['num_twitter_display']. '";
';
	if ( isset($_POST['twitter_auto_keyword']) && $_POST['twitter_auto_keyword'] == 1 ) {
$configData .= '$config["twitter_auto_keyword"] = "1";
';
	} else {
$configData .= '$config["twitter_auto_keyword"] = "0";
';
	}
}

if ( isset($_POST['flickr_enabled']) ) {
$configData .= '
$config["flickr_enabled"] =  "'. $_POST['flickr_enabled']. '";
$config["rd_flickr_type"] =  "'. $_POST['rd_flickr_type']. '";
$config["flickr_key"] =  "'. $_POST['flickr_key']. '";
$config["flickr_search_mode"] =  "'. $_POST['flickr_search_mode']. '";
$config["num_flickr_display"] = "12";
$config["flickr_api_key"] = "'. $_POST['flickr_api_key']. '";
$config["flickr_secret"] = "'. $_POST['flickr_secret']. '";
';
	if ( isset($_POST['flickr_auto_keyword']) && $_POST['flickr_auto_keyword'] == 1 ) {
$configData .= '$config["flickr_auto_keyword"] = "1";
';
	} else {
$configData .= '$config["flickr_auto_keyword"] = "0";
';
	}
}

$new_kword_filter	= array();
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


$new_keyword_filter	= isset($_POST['new_keyword_filter']) ? trim($_POST['new_keyword_filter']) : '';
$data_kwords		= explode(",", $new_keyword_filter);


foreach($data_kwords as $kword) {
	if ( $is_mbstring_enabled ) {
		$kword	= mb_strtolower($kword);
	}
	$kword	= trim($kword);
	if ( $kword != '' && !in_array($kword, $new_kword_filter) ) {
		$new_kword_filter[]	= $kword;
	}

	if ( $kword != '' && !in_array($kword, $new_kword_filter_pt) ) {
		$new_kword_filter_pt[]	= $kword;
	}
}



if ( isset($_POST['keyword_filter_enabled']) ) {

add_keyword_filter($new_kword_filter);

$configData .= '
$config["keyword_filter_enabled"] = "'. $_POST['keyword_filter_enabled']. '";
$config["keyword_filter_match"] = "'. $_POST['keyword_filter_match']. '";
';
}

$chk_contribute	= (isset($_POST['chk_contribute']) ? 1 : 0);

if ( $chk_contribute ) {
	if ( count($new_kword_filter_pt) > 0 ) {
	$kword_params	= implode(", ", $new_kword_filter_pt);
	post_keyword_filter($kword_params);
	}
}


$configData .= '
?>';

$dbPlayerData = '<?php
### Player Colors ###
$config["player_backcolor"] = "'. cleanColor($_POST["player_backcolor"]). '";
$config["player_frontcolor"] = "'. cleanColor($_POST["player_frontcolor"]). '";
$config["player_lightcolor"] = "'. cleanColor($_POST["player_lightcolor"]). '";
$config["player_screencolor"] = "'. cleanColor($_POST["player_screencolor"]). '";
?>';

$adminLoginData = '<?php
### Admin Login ###
$config["admin_username"] = "'. $_POST["admin_username"]. '";
$config["admin_pass"] = "'. (($_POST["new_pass"] == "") ? $config["admin_pass"] : $_POST["new_pass"]). '";
$config["admin_email"] = "'. $_POST["admin_email"]. '";
?>';

$dbConfigData = '<?php
/**
 * The script\'s database configuration file. Use this file to modify your
 * database login information. You will need to modify specific portions of this
 * file upon initial installation.
 *
 */
//-----
// Database Settings
//-----
/**
 * Database username
 *
 * Note: Change this before installation
 */
define("DB_USER", "'. $_POST["db_user"]. '");
/**
 * Database password
 *
 * Note: Change this before installation
 */
define("DB_PASS", "'. $_POST["db_pass"]. '");
/**
 * Database name
 *
 * Note: Change this before installation
 */
define("DB_NAME", "'. $_POST["db_name"]. '");
define("DB_PREFIX", "'. $_POST["db_prefix"]. '");
/**
 * Database host
 *
 * This value will rarely need to be changed. Only change if you know what you are doing.
 */
define("DB_HOST", "'. $_POST["db_host"]. '");
?>';

	$cfile = "../config/license_key.php";
	$fp = fopen($cfile, "w");
	fwrite($fp, $licenseData);
	fclose($fp);
	$cfile = "../config/config.php";
	$fp = fopen($cfile, "w") or die("Cannot save to file: $cfile, Please make this file writable.");
	fwrite($fp, $configData);
	fclose($fp);
	$cfile = "../templates/".$config['active_template']."/player/colors.php";
	$fp = fopen($cfile, "w") or die("Cannot save to file: $cfile, Please make this file writable.");
	fwrite($fp, $dbPlayerData);
	fclose($fp);
	$cfile = "../config/admin_login.php";
	$fp = fopen($cfile, "w") or die("Cannot save to file: $cfile, Please make this file writable.");
	fwrite($fp, $adminLoginData);
	fclose($fp);
	$cfile = "../config/db_config.php";
	$fp = fopen($cfile, "w") or die("Cannot save to file: $cfile, Please make this file writable.");
	fwrite($fp, $dbConfigData);
	fclose($fp);

	header("Location: index.php?configsaved=true");
	exit(0);
  }
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $aConfig["ADMIN_DEMO"]) {
	include('templates/header.php');
	echo '<div id="ajaxVersionInfo"></div>';
	echo '<div class="error">Configuration Not Saved (Demo Mode)</div>';

}else if($_GET["admindemo"] == "true" || $_GET["admindemo"] == "1") {
	include('templates/header.php');
	echo '<div id="ajaxVersionInfo"></div>';
	echo '<div class="error">Configuration Not Saved (Demo Mode)</div>';

}else if($errors != "") {
	header("Location: index.php?m=".urlencode($errors)."&t=error");
	exit(0);
}else if($_GET["m"] != "") {
	include('templates/header.php');
	echo '<div id="ajaxVersionInfo"></div>';

	if($_GET["t"] == "success")
		echo '<div class="success">'.$_GET["m"].'</div>';
	else
		echo '<div class="error">'.$_GET["m"].'</div>';
}else if ($_GET["configsaved"] == "true") {
	include('templates/header.php');
	echo '<div id="ajaxVersionInfo"></div>';

	echo '<div class="configSaved">Configuration Saved</div>';
} else {
	include('templates/header.php');
	echo '<div id="ajaxVersionInfo"></div>';
}




$str_license_html='_lic_info_';//	= url_get_contents("http://alurian.com/members/data.php?request=lic_info&product=prismotube&key=".$config['license_key'], $cache_dir."license.html");
$str_support_html='<a href="/install/upgrade.php">upgrade</a>';//	= url_get_contents("http://www.alurian.com/members/data.php?request=support_resources&product=prismotube", $cache_dir."support.html", 15 * 86400);
$str_credits_html='_credits_';//	= url_get_contents("http://alurian.com/members/data.php?request=credits&product=prismotube", $cache_dir."credits.html", 15 * 86400);


$str_license_last_updated	= "";
if ( file_exists($cache_dir."license.html") ) {
	$str_license_last_updated	= "Last Updated: ". date("j F Y", filemtime($cache_dir."license.html"));
}


$str_default_category_id = "";
if ( isset($config['default_category_id']) && $config['default_category_id'] != '0' ) {
	$CategoriesObject = new categories;
	$CategoriesData		= $CategoriesObject->fetch($config['default_category_id']);

	$str_default_category_id = '<option value="'.$config['default_category_id'].'" selected>'.stripslashes($CategoriesData['c_name']).'</option>';
}



?>
<div class="error" id="ajax-error-block" style="display:none;"></div>
<div class="tabber" id="tab1">
<div class="tabbertab" title="Main">
    <fieldset>

    <legend> Main Settings </legend>

    <? if ( $is_install_exists ) { ?>

      <div id="install_block" style="margin:10px 0 0 10px;">Please click here to <a href="javascript:rename_folder();">rename</a> install folder.</div>

    <? } ?>
      <p>
        <div class="settingTitle">Website Name</div>
        <input class="input" name="website_name" type="text" id="website_name" value="<?=$config["website_name"]?>">
      </p>
	  <? if ( $config['website_logo'] != '' && file_exists($logo_path.'/'.$config['website_logo']) ) { ?>
		<p>
        <div class="settingTitle">&nbsp;</div>
		<img src="../images/<?=$config['website_logo']?>" id="mylogo" />
		</p>

	  <?
	  }
	  ?>
	  <? /*
	  <p>
        <div class="settingTitle">Website Logo</div>
		<div id="divinputfile">
        <input class="input" name="website_logo" type="file" id="website_logo" onchange="document.getElementById('fakefilepc').value = this.value;" />
		<div id="fakeinputfile"><input type="text" id="fakefilepc" name="fakefilepc"/></div>
		</div>
      </p>
	  */?>
		<p>
				<div class="settingTitle" style="float:left;">Website Logo</div>
				<div id="divinputfile">
					<input class="input" name="website_logo" type="file" size="40" id="filepc" style="width:350px;" />

				</div>
		</p>

      <p>
        <div class="settingTitle">Website Slogan</div>
        <input class="input" name="website_slogan" type="text" id="website_name" value="<?=$config["website_slogan"]?>">
      </p>
      <p>
        <div class="settingTitle">Website Url </div>
        <input class="input" name="website_url" type="text" id="website_url" value="<?=$config["website_url"]?>">
      </p>
      <p>
        <div class="settingTitle">Meta Description</div>
        <textarea class="textarea" name="meta_description" type="text" id="meta_description"><?=$config["meta_description"]?></textarea>
      </p>
      <p>
        <div class="settingTitle">Meta Keywords</div>
        <input class="input" name="meta_keywords" type="text" id="meta_keywords" value="<?=$config["meta_keywords"]?>">
      </p>
	  <p>
        <div class="settingTitle">Main Website Keyphrase</div>
        <input class="input" name="website_main_keyword" type="text" id="website_main_keyword" value="<?=htmlspecialchars($config["website_main_keyword"])?>">
      </p>
      <p>
        <div class="settingTitle">License Key</div>
        <input class="input" name="license_key" type="text" id="license_key" value="<?=$config["license_key"]?>">
      </p>
	  <p>
        <div class="settingTitle">Youtube Developer key <br>
        <span style="font-size:10px;">* Optional but Recommended . <a href='https://developers.google.com/youtube/2.0/developers_guide_protocol#Developer_Key' target='_blank'>More info</a></span>


        </div>
        <input class="input" name="yt_developer_key" type="text" id="yt_developer_key" value="<?=$config["yt_developer_key"]?>">
      </p>
    </fieldset>
    <fieldset>

    <legend> Admin Info </legend>

      <p>
        <div class="settingTitle">Username</div>
        <input class="input" name="admin_username" type="text" id="admin_username" value="<?=$config["admin_username"]?>">
      </p>
      <p>
        <div class="settingTitle">Email</div>
        <input class="input" name="admin_email" type="text" id="admin_email" value="<?=$config["admin_email"]?>">
      </p>
      <p>
        <div class="settingTitle">Current Password</div>
        <input class="input" name="old_pass" type="password" autocomplete="off" id="old_pass">
      </p>
      <p>
        <div class="settingTitle">New Password</div>
        <input class="input password" name="new_pass" type="password" autocomplete="off" id="new_pass">
      </p>
      <p>
        <div class="settingTitle">Confirm Password</div>
        <input class="input" name="confirm_pass" type="password" autocomplete="off" id="confirm_pass">
      </p>

      <input type="hidden" value="" name="pstrength" id="pstrength" />

    </fieldset>

      <p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

</div>

	    <!--##########  SECTION : CATEGORIES  ##########-->
      <div class="tabbertab" title="Categories">

        <div id="manage_categories">
          <br>
            <div class="settingTitle">Manage Categories</div>
          <br>

          <iframe id="frame_categories" name="frame_categories" src="../lib/modules/categories/admin.php?act=add" width="100%" height="510" name="categories" frameborder="0" vspace="0" hspace="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" noresize></iframe>
        </div>

        <center><div id="import_categories_btn" class='shiny-blue'>Import Categories</div></center>


        <div id="import_categories" style="display:none;">

          <fieldset>
          <legend> Import Categories </legend>

              <p>
                <a href="csv/sample_simple_csv_file.csv" target="_blank" title="Download Simple Sample CSV File">Download Sample CSV ( Simple )</a><br />
                <a href="csv/sample_csv_file.csv" target="_blank" title="Download Sample CSV File">Download Sample CSV ( Extended )</a><br />
                <a href="http://alurian.com/go/longtailpro" target="_blank" title="LongTailPro CSV">LongTailPro CSV</a>
              </p>

              <p>
                <div class="settingTitle">Select CSV File:</div>
                <input type="file" class="input" name="file_import" id="file_import" />
              </p>

              <p>
              <div class="settingTitle">Do you want to delete existing categories?</div>
                <input name="opt_truncate" type="radio" id="opt_truncate" value="0" checked/>  No
                <input name="opt_truncate" type="radio" id="opt_truncate" value="1" />  Yes
              </p>

              <p id="saveConfig">
                <input type="image" name="Submit" value="Save" src="images/save.png">
              </p>

          </fieldset>
          <br><br><br>
          <center><div id="manage_categories_btn" class='shiny-blue'><< Back to 'Manage Categories'</div></center>



        </div>


      </div>


		  <script type="text/javascript">
		  <!--
		  $('#import_categories_btn').click(function()
		  {
			$('#import_categories').slideToggle('1000', function() {});
			$('#manage_categories').slideToggle('fast', function() {});
			$('#import_categories_btn').hide();
		  });
		  $('#manage_categories_btn').click(function()
		  {
			$('#import_categories').slideToggle('1000', function() {});
			$('#manage_categories').slideToggle('fast', function() {});
			$('#import_categories_btn').show();
		  });
		  -->
		  </script>


	    <!--##########  SECTION : PAGES  ##########-->
		<div class="tabbertab" title="Pages">


				<fieldset>
				<legend> Custom Pages </legend>
				<?
				if(isset($_GET["rmpageid"]) && !isDemo())
				{
					$rmpageid = $_GET["rmpageid"];
					$query = "DELETE FROM `".DB_PREFIX."pages` WHERE `page_id` = $rmpageid";
					dbQuery($query, false);
				}

				$str_sidebar_links = "";
				$query = 'SELECT `page_id`, `page_title`, `page_sef_url`, `page_status` FROM `'.DB_PREFIX.'pages`';

				$result = dbQuery($query, false);
				$count = 0;
				while (list($pageID, $pageTitle, $pageURL, $pageStatus) = @mysql_fetch_row($result))
				{

					$pageTitle 	= stripslashes($pageTitle);
					$pageURL 	= "page/".stripslashes($pageURL).".html";
					$cls_row 	= '';
					if ( ($count % 2 ) != 0 )
					{
						$cls_row = ' class="odd"';
					}

					$status_str	= 'Inactive';
					if ( $pageStatus == 1 )
					{
						$status_str	= 'Active';
					}

					$str_sidebar_links .= "<tr{$cls_row}><td>$pageTitle</td><td>$pageURL</td><td>$status_str</td><td><a href=\"pages.php?id=$pageID\" target=\"_blank\">Edit</a></td><td><a href=\"index.php?rmpageid=".$pageID."\">Delete</a></td></tr>";
					$count++;
				}
				?>

				<? if ( $str_sidebar_links != '' ) { ?>
				<table width="100%" class="tablesorter">
				<thead>
				<tr><th><b>Page Title</b></th><th><b>Page URL</b></th><th><b>Page Status</b></th><th>&nbsp;</th><th>&nbsp;</th></tr>
				</thead>
				<tbody>
				<? echo $str_sidebar_links?>
				</tbody>
				</table>
				<? } else { ?>
				<div>Nothing Found!</div>
				<? } ?>
						<br>

				</fieldset>

          <a href="pages.php?id=0" target="_blank" style='width:150px'><center><div class='shiny-blue'>Add New Page</div></center></a>


		</div>



	    <!--##########  SECTION : LINKS  ##########-->


      <div class="tabbertab" id="links" title="Links">


    <fieldset>
    <legend> Sidebar Links </legend>

  		<p>
    		<div class="settingTitle">Sidebar Links Enabled</div>
 			<select class="select" name="links_enabled" id="links_enabled">
				<option <? echo ($config["links_enabled"] == true) ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["links_enabled"] == false) ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>

<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

		</fieldset>

<hr>
<?
if(isset($_GET["rmlink"]) && !isDemo()) {
	$rmLink = $_GET["rmlink"];
	$query = "DELETE FROM `".DB_PREFIX."links` WHERE `id` = $rmLink";
	dbQuery($query, false);
}

if(isset($_POST["updatelink"]) && !isDemo()) {
	$editLink = $_POST["updatelink"];
	if(isset($_POST["edit_ad_link_title"]) || isset($_POST["edit_ad_link_url"])){
		$linkTitle = $_POST["edit_ad_link_title"];

		if ( get_magic_quotes_gpc() ) {
			$linkTitle		= stripslashes($linkTitle);
		}

		$query = "UPDATE ".DB_PREFIX."links SET title = '".db_escape_string($linkTitle)."' WHERE `id` = '".$editLink."'";
		dbQuery($query, false);
		$linkURL = $_POST["edit_ad_link_url"];

		if ( get_magic_quotes_gpc() ) {
			$linkURL		= stripslashes($linkURL);
		}

		$query = "UPDATE ".DB_PREFIX."links SET `url` = '".db_escape_string($linkURL)."' WHERE `id` = '".$editLink."'";
		dbQuery($query, false);
	}
}

if(isset($_GET["editlink"])) {
	$editLink = $_GET["editlink"];
	echo '<div class="settingTitle">Edit Link #'.$editLink.'</div>';
	echo '<br><br>';
	$query = "SELECT * FROM `".DB_PREFIX."links` WHERE id = $editLink";
	$result = dbQuery($query, false);
	$count = 0;
	list ($link_id_r, $link_title_r, $link_url_r) = @mysql_fetch_row($result);

		$linkID = $link_id_r;
		$linkTitle = stripslashes($link_title_r);
		$linkURL = stripslashes($link_url_r);
		?>


			<div id="blk_new_menu_link2" style="margin:10px 10px 0 150px;width:550px;" class="gradientBg" width="550" align="center">
		<table width="520" border="0" align="center" cellpadding="2" cellspacing="0">
			<tr>
			  <td colspan="3" bgcolor="#CD2532"><h1>Edit Link # <?=$editLink?></h1></td>
			</tr>
			<tr><td colspan="3"><br></td></tr>

			<tr>
			  <td width="100">Link Title</td>
			  <td width="10"></td>
			  <td width="300">
				<input class="input" name="edit_ad_link_title" type="text" id="edit_ad_link_title" value="<?=$linkTitle?>">
			  </td>
			</tr>

			<tr>
				<td>Link URL</td>
				<td></td>
				<td><input class="input" name="edit_ad_link_url" type="text" id="edit_ad_link_url" value="<?=$linkURL?>">  </td>
			</tr>
			<tr>
				<td colspan="3"><div align="right">
				<input type="submit" value="Save Link" name="Submit" class="button"/>
				</div></td>
				</tr>

			<input type="hidden" name="updatelink" value="<?=$_GET["editlink"]?>">

		</table>
		</div>
		<br><br>
		<a href="index.php">Back</a><br><br>

		<?

}else{
?>
<div class="settingTitle">Manage Sidebar Links</div>
<br><br>
<?
$admindemo = "";
if(isDemo())
	$admindemo = "&admindemo=true";

$str_sidebar_links = "";
$query = 'SELECT * FROM `'.DB_PREFIX.'links`';

$result = dbQuery($query, false);
$count = 0;
while (list ($link_id_r, $link_title_r, $link_url_r) = @mysql_fetch_row($result))  {

	$linkID = $link_id_r;
	$linkTitle = stripslashes($link_title_r);
	$linkURL = stripslashes($link_url_r);
	$cls_row = '';
	if ( ($count % 2 ) != 0 ) {
		$cls_row = ' class="odd"';
	}
	$str_sidebar_links .= "<tr{$cls_row}><td>$linkID</td><td>$linkTitle</td><td><a href=\"{$linkURL}\" >$linkURL</a></td><td><a href=\"id=$linkID\" id=\"edit-link\">Edit</a></td><td><a href=\"?rmlink=".$linkID.$admindemo."\">Delete</a></td></tr>";
	$count++;
}
?>

<? if ( $str_sidebar_links != '' ) { ?>
<table width="100%" class="tablesorter">
<thead>
<tr><th><b>Link ID</b></th><th><b>Link Title</b></th><th><b>Link URL</b></th><th>&nbsp;</th><th>&nbsp;</th></tr>
</thead>
<tbody>
<? echo $str_sidebar_links?>
</tbody>
</table>
<? } else { ?>
<div>Nothing Found!</div>
<? } ?>
		<br>

		<p align="center" style="text-align:center;">

    <center><div id="btn_add_new_link" class='shiny-blue'>Add New Link</div></center>
		</p>



<?
}
?>


<hr>

	  <?
if (isset($_POST["mm_link_title"])) {

	for($i = 0 ; $i < sizeof($_POST["mm_link_url"]) ; $i++) {
		if($_POST["mm_link_title"][$i] != "" && $_POST["mm_link_url"][$i] != "" && !isDemo()) {
			$linkTitle = $_POST["mm_link_title"][$i];
			$linkUrl = $_POST["mm_link_url"][$i];
			$linkClass = $_POST["mm_link_class"][$i];
			$linkNW = $_POST["mm_link_new_window"][$i];
			$linkOrder = $_POST["mm_link_order"][$i];

			if ( get_magic_quotes_gpc() ) {
				$linkTitle	= stripslashes($linkTitle);
				$linkUrl	= stripslashes($linkUrl);
				$linkClass	= stripslashes($linkClass);
				$linkNW		= stripslashes($linkNW);
				$linkOrder	= stripslashes($linkOrder);
			}

			$query = "INSERT INTO `".DB_PREFIX."main_menu` (`title`, `url`, `class`, `new_window`, `order`, `time`) VALUES
				('".db_escape_string($linkTitle)."', '".db_escape_string($linkUrl)."', '".db_escape_string($linkClass)."', '".db_escape_string($linkNW)."', '".db_escape_string($linkOrder)."', ".time().")";
			dbQuery($query, false);
		}
	}
}

?>


<?
if(isset($_GET["rm_mmlink"]) && !isDemo()) {
	$rmLink = $_GET["rm_mmlink"];
	$query = "DELETE FROM `".DB_PREFIX."main_menu` WHERE `id` = {$rmLink}";
	dbQuery($query, false);
}

if(isset($_POST["update_mmlink"]) && !isDemo()) {
	$editLink = $_POST["update_mmlink"];
	if(isset($_POST["edit_mm_link_title"]) || isset($_POST["edit_mm_link_url"])){
		$linkTitle = $_POST["edit_mm_link_title"];
		$linkURL = $_POST["edit_mm_link_url"];
		$linkOrder = $_POST["edit_mm_link_order"];
		$linkClass = $_POST["edit_mm_link_class"];
		$linkNewWindow = $_POST["edit_mm_link_new_window"];

		if ( get_magic_quotes_gpc() ) {
			$linkTitle	= stripslashes($linkTitle);
			$linkURL	= stripslashes($linkURL);
			$linkClass	= stripslashes($linkClass);
		}

		$query = "UPDATE `".DB_PREFIX."main_menu` SET title = '".db_escape_string($linkTitle)."',
			`url` = '".db_escape_string($linkURL)."',
			`class` = '".db_escape_string($linkClass)."',
			`new_window` = ".$linkNewWindow.",
			`order` = ".$linkOrder."
			WHERE `id` = '".$editLink."'";
		dbQuery($query, false);

	}
}

if(isset($_GET["edit_mmlink"])) {
	$editLink = $_GET["edit_mmlink"];
	echo '<div class="settingTitle">Edit Main Menu Link #'.$editLink.'</div>';
	echo '<br><br>';
	$query = "SELECT * FROM `".DB_PREFIX."main_menu` WHERE id = $editLink";
	$result = dbQuery($query, false);
	$count = 0;
	list ($link_id_r, $link_title_r, $link_url_r, $linkClass, $linkNewWindow, $linkOrder) = @mysql_fetch_row($result);

		$linkID = $link_id_r;
		$linkTitle = $link_title_r;
		$linkURL = $link_url_r;
		?>
		<div id="blk_new_menu_link" style="margin:10px 10px 0 150px;width:550px;" class="gradientBg" width="550" align="center">
		<table width="520" border="0" align="center" cellpadding="2" cellspacing="0">
			<tr>
			  <td colspan="3"><h1>Edit Main Menu Link # <?=$editLink?></h1></td>
			</tr>
			<tr><td colspan="3"><br></td></tr>

			<tr>
			  <td width="100">Link Title</td>
			  <td width="10"></td>
			  <td width="300">
				<input class="input" name="edit_mm_link_title" type="text" id="edit_mm_link_title" value="<?=$linkTitle?>">
			  </td>
			</tr>

			<tr>
				<td>Link URL</td>
				<td></td>
				<td><input class="input" name="edit_mm_link_url" type="text" id="edit_mm_link_url" value="<?=$linkURL?>">  </td>
			</tr>

			<tr>
				<td>Link Order</td>
				<td></td>
				<td><input class="input" name="edit_mm_link_order" type="text" id="edit_mm_link_order" value="<?=$linkOrder?>"></td>
			</tr>

			<tr>
				<td>Link  CSS Class</td>
				<td></td>
				<td><input class="input" name="edit_mm_link_class" type="text" id="edit_mm_link_class" value="<?=$linkClass?>"></td>
			</tr>

			<tr>
				<td>Link Open New Window</td>
				<td></td>
				<td><select name="edit_mm_link_new_window" class="input">
					<option value="0" <? if ( !$linkNewWindow ) echo "selected";?>>No</option>
					<option value="1" <? if ( $linkNewWindow ) echo "selected";?>>Yes</option>
				</select>
				</td>
			</tr>
			<input type="hidden" name="update_mmlink" value="<?=$_GET["edit_mmlink"]?>">
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" class="button" name="Submit" value="Save Link"/></td>
			</tr>

		</table>
		</div>
		<br><br>
		<a href="index.php">Back</a><br><br>


		<?

}else{
?>
<div class="settingTitle">Manage Main Menu Links</div>
<br><br>
<?
$admindemo = "";
if(isDemo())
	$admindemo = "&admindemo=true";

	$str_mainmenu_links	= "";
$query = "SELECT * FROM `".DB_PREFIX."main_menu`";
$result = dbQuery($query, false);
$count = 0;
while (list ($link_id_r, $link_title_r, $link_url_r,$linkClass, $linkNewWindow) = @mysql_fetch_row($result))  {

	$linkID = $link_id_r;
	$linkTitle = stripslashes($link_title_r);
	$linkURL = $link_url_r;
	$strLinkNewWindow = ($linkNewWindow) ? 'Yes' : 'No';
	$cls_row = '';
	if ( ($count % 2 ) != 0 ) {
		$cls_row = ' class="odd"';
	}
	$str_mainmenu_links	.= "<tr{$cls_row}><td>$linkID</td><td>$linkTitle</td><td>$linkURL</td><td>$strLinkNewWindow</td><td><a href=\"id=$linkID\" id=\"edit-main-menu-link\">Edit</a></td><td><a href=\"?rm_mmlink=".$linkID.$admindemo."\">Delete</a></td></tr>";
	$count++;
}
?>

<? if ( $str_mainmenu_links != '' ) { ?>
<table width="100%" class="tablesorter">
<thead>
<tr><th><b>Link ID</b></th><th><b>Link Title</b></th><th><b>Link URL</b></th><th><b>New Window</b></th><th>&nbsp;</th><th>&nbsp;</th></tr>
</thead>
<tbody>
<?=$str_mainmenu_links?>
</tbody>
</table>
<? } else { ?>
<div>Nothing Found!</div>
<? } ?>
		<br>
		<p align="center" style="text-align:center;">


    <center><div id="btn_add_mainmenu_link" class='shiny-blue'>Add New Main Menu Link</div></center>

		</p>
<?
}
?>



      </div>


	    <!--##########  SECTION : PLAYER  ##########-->
			<div class="tabbertab" title="Player">

			<fieldset>
			<legend> Player Settings </legend>

			  <p>
				<div class="settingTitle">Youtube Player <br><font color="darkred"><small>If False, Custom Player will be activated</small></font></div>
				<select class="select" name="youtube_player" id="youtube_player">
				  <option <? echo ($config["youtube_player"] == true) ? "selected" : ""; ?> value="true">True</option>
				  <option <? echo ($config["youtube_player"] == false) ? "selected" : ""; ?> value="false">False</option>
				</select>
			  </p>
			  <p>
				<div class="settingTitle">Show Video Player On Home Page</div>
				<select class="select" name="enable_home_player" id="enable_home_player">
				  <option <? echo ($config["enable_home_player"] == true) ? "selected" : ""; ?> value="true">True</option>
				  <option <? echo ($config["enable_home_player"] == false) ? "selected" : ""; ?> value="false">False</option>
				</select>
			  </p>
			</fieldset>
			<fieldset>
			<legend> Custom Player Settings </legend>

			  <p>
				<div class="settingTitle">Enable Player Playlist </div>
				<select class="select" name="enable_player_playlist" id="enable_player_playlist">
				  <option <? echo ($config["enable_player_playlist"] == true) ? "selected" : ""; ?> value="true">True</option>
				  <option <? echo ($config["enable_player_playlist"] == false) ? "selected" : ""; ?> value="false">False</option>
				</select>
			  </p>

			<?
			// Loading cplayer skins
				// template list
				//define the path as relative
				$path = "../player/skins/";
				//using the opendir function
				$dir_handle = @opendir($path) or die("Unable to open $path");
				//running the while loop
				$i = 0;
				while ($file = readdir($dir_handle))
				{
					if($file!="." && $file!=".." && $file!="thumbs" && $file != 'build.xml' ) {
						//$CPLAYER_SKINS[$i] = substr($file,0,strlen($file)-4);
						$CPLAYER_SKINS[$i] = $file;
						$i++;
					}

				}
				sort($CPLAYER_SKINS);
				//closing the directory
				closedir($dir_handle);
			?>
			  <p>
				<div class="settingTitle">Custom Player Skin</div>
				<table border="0">
				<tr>
				<td>
				<select class="select_list" name="cplayer_skin" id="cplayer_skin" size="10" onchange="setSkinThumb('cplayer_skin_thumb', this.form.cplayer_skin.options[this.form.cplayer_skin.selectedIndex].value);">
					<?
					for ($i = 0 ; $i < sizeof($CPLAYER_SKINS) ; $i++) {
					?>
					<option <? echo ($config["cplayer_skin"] == $CPLAYER_SKINS[$i]) ? "selected" : ""; ?> value="<?=$CPLAYER_SKINS[$i]?>"><?=ucfirst($CPLAYER_SKINS[$i])?></option>
					<?
					}
					?>
				</select>
				</td>
				<td>&nbsp;</td>
				<td>
					<img id="cplayer_skin_thumb" src="../player/skins/thumbs/<?=$config["cplayer_skin"]?>.png" border="0">
				</td>
				</tr>
				</table>
			  </p>
			  <p>
				<div class="settingTitle">Enable Player Custom Colors</div>
				<select class="select" name="enable_player_colors" id="enable_player_colors" onchange="togglePlayerColors('player_colors', this.form.enable_player_colors.options[this.form.enable_player_colors.selectedIndex].value);">
					<option <? echo ($config["enable_player_colors"] == true) ? "selected" : ""; ?> value="true">True</option>
					<option <? echo ($config["enable_player_colors"] == false) ? "selected" : ""; ?> value="false">False</option>
				</select>
			  </p>
			  <?
				include ("../templates/".$config['active_template']."/player/colors.default.php");
				if ($config["enable_player_colors"])
					$displayColors = "display:block;";
				else
					$displayColors = "display:none;";
			  ?>
			  <div id="player_colors" style="<?=$displayColors?> width:410px; border:3px solid #999999; padding:5px 5px 25px; margin:5px 20px 25px 400px;background-color:#DFEBFF;">
			  <p>Player Colors for the current active template : <font color="darkred"><b><?=$config["active_template"]?></b></font></p>
			  <p>
				<div class="settingTitle_small">Player BackColor</div>
				<div id="colorpicker301" class="colorpicker301"></div>
					<input class="color input_small" name="player_backcolor" type="text" id="player_backcolor" value="#<?=$config["player_backcolor"]?>">
				<input type="hidden" id="default_player_backcolor" value="<?=$defaults["player_backcolor"]?>">

			  </p>
			  <p>
				<div class="settingTitle_small">Player FrontColor</div>
				<div id="colorpicker301" class="colorpicker301"></div>
					<input class="color input_small" name="player_frontcolor" type="text" id="player_frontcolor" value="#<?=$config["player_frontcolor"]?>">
				<input type="hidden" id="default_player_frontcolor" value="<?=$defaults["player_frontcolor"]?>">

			  </p>
			  <p>
				<div class="settingTitle_small">Player LightColor</div>
				<div id="colorpicker301" class="colorpicker301"></div>
				<input class="color input_small" name="player_lightcolor" type="text" id="player_lightcolor" value="#<?=$config["player_lightcolor"]?>">
				<input type="hidden" id="default_player_lightcolor" value="<?=$defaults["player_lightcolor"]?>">

			  </p>
			  <p>
				<div class="settingTitle_small">Player ScreenColor</div>
				<div id="colorpicker301" class="colorpicker301"></div>
				<input class="color input_small" name="player_screencolor" type="text" id="player_screencolor" value="#<?=$config["player_screencolor"]?>">
				<input type="hidden" id="default_player_screencolor" value="<?=$defaults["player_screencolor"]?>">

			  </p>
				<div style="float:right; margin:0 15px 0 0;"><a href="javascript:revertDefaults('player-colors');">Revert to default colors</a></div>
			  </div>

			  <?
				 include ("../player/settings.default.php");
			  ?>

			  <div class="settingTitle">Advanced Parameters</div>

			  <div id="player_settings" style="border:3px solid #BFB68F; padding:5px 5px 25px; margin:5px 20px 25px 400px;background-color:#FFF9DF;">
			  <div style="margin:10px;"><h1>For Advanced Users Only</h1>
			  <a href="http://developer.longtailvideo.com/trac/wiki/FlashVars" target="_blank">Supported FlashVars</a> | <a href="../player/info.pdf" target="_blank">Supported FlashVars (PDF)</a> | <a href="http://www.longtailvideo.com/addons/plugins" target="_blank">Browse JW Player Plugins</a><br><br></div>
			  <div class="clear"></div>
			  <p>
				<div class="settingTitle">Player Custom Settings / Plugins <br><font color="darkred">(Home Page)</font></div>
				<textarea class="textarea" name="player_custom_plugins_home" type="text" id="player_custom_plugins_home"><?=$config["player_custom_plugins_home"]?></textarea>
				<textarea style="display:none;" type="text" id="default_player_custom_plugins_home"><?=$defaults["player_custom_plugins_home"]?></textarea>
				<div style="float:right; margin:0 15px 0 0;"><a href="javascript:revertDefaults('player-plugins-home');">Revert to default settings</a></div>
			  </p>
			  <p>
				<div class="settingTitle">Player Custom Settings / Plugins <br><font color="darkred">(On Video Page)</font></div>
				<textarea class="textarea" name="player_custom_plugins_detail" type="text" id="player_custom_plugins_detail"><?=$config["player_custom_plugins_detail"]?></textarea>
				<textarea style="display:none;" type="text" id="default_player_custom_plugins_detail"><?=$defaults["player_custom_plugins_detail"]?></textarea>
				<div style="float:right; margin:0 15px 0 0;"><a href="javascript:revertDefaults('player-plugins-detail');">Revert to default settings</a></div>
			  </p>
			  <p>
				<div class="settingTitle">Player Custom Settings / Plugins <br><font color="darkred">(On Video Page if playlist enabled)</font></div>
				<textarea class="textarea" name="player_custom_plugins_detail_playlist" type="text" id="player_custom_plugins_detail_playlist"><?=$config["player_custom_plugins_detail_playlist"]?></textarea>
				<textarea style="display:none;" type="text" id="default_player_custom_plugins_detail_playlist"><?=$defaults["player_custom_plugins_detail_playlist"]?></textarea>
				<div style="float:right; margin:0 15px 0 0;"><a href="javascript:revertDefaults('player-plugins-detail-playlist');">Revert to default settings</a></div>
			  </p>


			  </div>

			</fieldset>

			  <p id="saveConfig">
					<input type="image" name="Submit" value="Save" src="images/save.png">
				  </p>

			</div>
<div class="tabbertab" title="Cache">
<fieldset>
<legend> Cache Settings </legend>
	<? /*
  <p>
    <div class="settingTitle">Enable XML Cache</div>
 	<select class="select" name="xml_cache_enable" id="xml_cache_enable">
		<option <? echo ($config["xml_cache_enable"] == true) ? "selected" : ""; ?> value="true">True (recommended)</option>
		<option <? echo ($config["xml_cache_enable"] == false) ? "selected" : ""; ?> value="false">False</option>
	</select>
  </p>
  */ ?>
  <p>
    <div class="settingTitle">Enable Categories Cache<br>
    		<span style="font-size:10px;">* For performance if you have a lot of categories (>1000)
    		</span></div>
 	<select class="select" name="categories_cache_enable" id="categories_cache_enable">
		<option <? echo ($config["categories_cache_enable"] == true) ? "selected" : ""; ?> value="true">True (recommended)</option>
		<option <? echo ($config["categories_cache_enable"] == false) ? "selected" : ""; ?> value="false">False</option>
	</select>
  </p>
  <p>
    <div class="settingTitle">Cache Storage Format</div>
 	<select class="select" name="cache_storage_format" id="cache_storage_format">
		<option <? echo ($config["cache_storage_format"] == 'auto') ? "selected" : ""; ?> value="auto">Auto (recommended)</option>
		<option <? echo ($config["cache_storage_format"] == 'gzip') ? "selected" : ""; ?> value="gzip">Gzip</option>
		<option <? echo ($config["cache_storage_format"] == 'json') ? "selected" : ""; ?> value="json">JSON</option>
	</select>
  </p>
  <p>
    <div class="settingTitle">XML Cache Directory</div>
    <input class="input" name="xml_cache_dir" type="text" id="xml_cache_dir" value="<?=$config["xml_cache_dir"]?>">
  </p>
  <p>
    <div class="settingTitle">HTML Cache Directory</div>
    <input class="input" name="html_cache_dir" type="text" id="html_cache_dir" value="<?=$config["html_cache_dir"]?>">
  </p>

<p>
    <div class="settingTitle">Categories Cache Timeout <br><small>how many seconds categories are being cached</small></div>
	<select class="select" name="categories_cache_timeout_opt" id="categories_cache_timeout_opt" onchange="document.config.categories_cache_timeout.value = document.config.categories_cache_timeout_opt.options[document.config.categories_cache_timeout_opt.selectedIndex].value;set_cache_variables('categories_cache');">

		<? foreach($datas_seconds as $data_second => $data_display) { ?>
			<option <? echo ($config["categories_cache_timeout"] == $data_second) ? "selected" : ""; ?> value="<?=$data_second?>"><?=$data_display?></option>
		<? } ?>
		<option value="" <? if (!in_array($config["categories_cache_timeout"], array_flip($datas_seconds))) { echo "selected"; } ?>>Custom</option>
	</select>



		<div class="settingTitle" style="<?=$str_blk_categories_cache_timeout?>" id="categories_setting_title">&nbsp;</div>
		<input class="input" name="categories_cache_timeout" type="text" id="categories_cache_timeout" value="<?=$config["categories_cache_timeout"]?>" style="width:140px;padding:9px;display:none;float:left;<?=$str_blk_categories_cache_timeout?>"> <span id="categories_seconds" style="<?=$str_blk_categories_cache_timeout?>">Seconds</span>
	</p>

  <p>
    <div class="settingTitle">XML Cache Timeout <br><small>how many seconds contents are being cached</small></div>
	<select class="select" name="xml_cache_timeout_opt" id="xml_cache_timeout_opt" onchange="document.config.xml_cache_timeout.value = document.config.xml_cache_timeout_opt.options[document.config.xml_cache_timeout_opt.selectedIndex].value;set_cache_variables('xml_cache');">

		<? foreach($datas_seconds as $data_second => $data_display) { ?>
			<option <? echo ($config["xml_cache_timeout"] == $data_second) ? "selected" : ""; ?> value="<?=$data_second?>"><?=$data_display?></option>
		<? } ?>
		<option value="" <? if (!in_array($config["xml_cache_timeout"], array_flip($datas_seconds))) { echo "selected"; } ?>>Custom</option>
	</select>



		<div class="settingTitle" style="<?=$str_blk_xml_cache_timeout?>" id="xml_setting_title">&nbsp;</div>
		<input class="input" name="xml_cache_timeout" type="text" id="xml_cache_timeout" value="<?=$config["xml_cache_timeout"]?>" style="width:140px;padding:9px;display:none;float:left;<?=$str_blk_xml_cache_timeout?>"> <span id="xml_seconds" style="<?=$str_blk_xml_cache_timeout?>">Seconds</span>
	</p>


  <p>
    <div class="settingTitle">Tags Cache Timeout <br><small>how many seconds tags are being cached</small></div>
	<select class="select" name="tags_cache_timeout_opt" id="tags_cache_timeout_opt" onchange="document.config.tags_cache_timeout.value = document.config.tags_cache_timeout_opt.options[document.config.tags_cache_timeout_opt.selectedIndex].value;set_cache_variables('tags_cache');">

		<? foreach($datas_seconds as $data_second => $data_display) { ?>
			<option <? echo ($config["tags_cache_timeout"] == $data_second) ? "selected" : ""; ?> value="<?=$data_second?>"><?=$data_display?></option>
		<? } ?>
		<option value="" <? if (!in_array($config["tags_cache_timeout"], array_flip($datas_seconds))) { echo "selected"; } ?>>Custom</option>
	</select>

		<div class="settingTitle" style="<?=$str_blk_tags_cache_timeout?>" id="tags_setting_title">&nbsp;</div>
		 <input class="input" name="tags_cache_timeout" type="text" id="tags_cache_timeout" value="<?=$config["tags_cache_timeout"]?>" style="width:140px;padding:9px;position:relative;float:left;<?=$str_blk_tags_cache_timeout?>"> <span id="tags_seconds" style="<?=$str_blk_tags_cache_timeout?>">Seconds</span>
	</p>


  <p>
    <div class="settingTitle">RSS Cache Timeout <br><small>how many seconds rss are being cached</small></div>
	<select class="select" name="rss_cache_timeout_opt" id="rss_cache_timeout_opt" onchange="document.config.rss_cache_timeout.value = document.config.rss_cache_timeout_opt.options[document.config.rss_cache_timeout_opt.selectedIndex].value;set_cache_variables('rss_cache');">

		<? foreach($datas_seconds as $data_second => $data_display) { ?>
			<option <? echo ($config["rss_cache_timeout"] == $data_second) ? "selected" : ""; ?> value="<?=$data_second?>"><?=$data_display?></option>
		<? } ?>
		<option value="" <? if (!in_array($config["rss_cache_timeout"], array_flip($datas_seconds))) { echo "selected"; } ?>>Custom</option>
	</select>


		<div class="settingTitle" style="<?=$str_blk_rss_cache_timeout?>" id="rss_setting_title">&nbsp;</div>
		<input class="input" name="rss_cache_timeout" type="text" id="rss_cache_timeout" value="<?=$config["rss_cache_timeout"]?>" style="width:140px;padding:9px;float:left;<?=$str_blk_rss_cache_timeout?>" > <span id="rss_seconds" style="<?=$str_blk_rss_cache_timeout?>">Seconds</span>
	</p>
 <br />
<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

</fieldset>

</div>
<div class="tabbertab" title="Display">
<fieldset>
<legend> Display Settings </legend>

<p>
    <div class="settingTitle">Home Page Default Category</div>
	<select class="select" name="default_category_id" id="default_category_id">
	<?php echo $str_default_category_id?>
	<option value="0" class="loading-icon">Loading ...</option>
	</select>
	<div class="settingTitle">&nbsp;</div><div style="height:7px;">&nbsp;<img src="images/ajax-loader.gif" id="ajax-loader" style="display:none;" /></div>
	<? //<img src="images/ajax-loader.gif" id="ajax-loader" style="display:none;" /> ?>
  </p>
	<p>
    <div class="settingTitle">Default Language</div>
	<select class="select" name="web_default_language" id="web_default_language">
		<?php foreach( $lang_code as $key_lang => $language_name ) { ?>
			<option <? echo ($config["web_default_language"] == $key_lang) ? "selected" : ""; ?> value="<?=$key_lang;?>"><?=$language_name;?></option>
		<?php } ?>
	</select>
  </p>
   <p>
    <div class="settingTitle">Retrieve only videos of this language</div>
	<select class="select" name="video_language_specific" id="video_language_specific">
		<?php foreach( $languages_code as $video_language_code => $language_name ) { ?>
			<option <? echo ($config["video_language_specific"] == $video_language_code) ? "selected" : ""; ?> value="<?=$video_language_code;?>"><?=$language_name;?></option>
		<?php } ?>
	</select>
  </p>
  <p>
    <div class="settingTitle">Safe Search</div>
 	<select class="select" name="default_filter_value" id="default_filter_value">
  		<option <? echo ($config["default_filter_value"] == "on") ? "selected" : ""; ?> value="on">ON</option>
		<option <? echo ($config["default_filter_value"] == "off") ? "selected" : ""; ?> value="off">OFF</option>
	</select>
  </p>
  <p>
    <div class="settingTitle">Videos Per Page (Search Results)</div>
 	<select class="select" name="list_per_page" id="list_per_page">
	<?
		for($i = 6 ; $i < 49 ; $i++) {
			echo '<option '.(($config["list_per_page"] == $i) ? "selected" : "") .' value="'.$i.'">'.$i.'</option>';
		}
	?>
	</select>
  </p>
  <p>
    <div class="settingTitle">Videos Per Page (Home Page Results)</div>
 	<select class="select" name="list_on_home_page" id="list_on_home_page">
	<?
		for($i = 6 ; $i < 49 ; $i++) {
			echo '<option '.(($config["list_on_home_page"] == $i) ? "selected" : "") .' value="'.$i.'">'.$i.'</option>';
		}
	?>
	</select>
  </p>
  <p>
    <div class="settingTitle">Videos Per Page (Standard Feed Results)</div>
 	<select class="select" name="list_on_feed_page" id="list_on_feed_page">
	<?
		for($i = 6 ; $i < 49 ; $i++) {
			echo '<option '.(($config["list_on_feed_page"] == $i) ? "selected" : "") .' value="'.$i.'">'.$i.'</option>';
		}
	?>
	</select>
  </p>
  <p>
    <div class="settingTitle">Videos Per Page (User Uploaded)</div>
    <select class="select" name="user_uploaded_list_per_page" id="user_uploaded_list_per_page">
	<?
		for($i = 3 ; $i < 49 ; $i++) {
			echo '<option '.(($config["user_uploaded_list_per_page"] == $i) ? "selected" : "") .' value="'.$i.'">'.$i.'</option>';
		}
	?>
	</select>
  </p>
  <p>
    <div class="settingTitle">Videos Per Page (User Favorites)</div>
    <select class="select" name="user_favorites_list_per_page" id="user_favorites_list_per_page">
	<?
		for($i = 3 ; $i < 49 ; $i++) {
			echo '<option '.(($config["user_favorites_list_per_page"] == $i) ? "selected" : "") .' value="'.$i.'">'.$i.'</option>';
		}
	?>
	</select>
  </p>
  <p>
    <div class="settingTitle">Related Videos Position</div>
 	<select class="select" name="related_videos_position" id="related_videos_position">
		<option <? echo ($config["related_videos_position"] == "middle") ? "selected" : ""; ?> value="middle">Middle</option>
		<option <? echo ($config["related_videos_position"] == "sidebar") ? "selected" : ""; ?> value="sidebar">Sidebar</option>
	</select>
  </p>
  <? /*
  <p>
    <div class="settingTitle">Enable Video Download</div>
	<select class="select" name="enable_download" id="enable_download">
 		<option <? echo ($config["enable_download"] == true) ? "selected" : ""; ?> value="true">True</option>
		<option <? echo ($config["enable_download"] == false) ? "selected" : ""; ?> value="false">False</option>
	</select>
  </p>
  */?>
  <p>
    <div class="settingTitle">Default Video Listing</div>
	<select class="select" name="view_setting" id="view_setting">
 		<option <? echo ($config["view_setting"] == "list") ? "selected" : ""; ?> value="list">List View</option>
		<option <? echo ($config["view_setting"] == "grid") ? "selected" : ""; ?> value="grid">Grid View</option>
	</select>
  </p>

	<p>
		<div class="settingTitle">Sort Videos By</div>
		<select class="select" name="sort_videos_by" id="sort_videos_by">
			<?php foreach($data_sort_videos_by as $key_sv => $sort_option ) { ?>
			<option value="<?php echo $key_sv?>" <?php if ( $key_sv == $config["sort_videos_by"] ) { echo "selected"; }?>><?php echo $sort_option?></option>
			<?php } ?>
		</select>
	</p>

	<p>
		<div class="settingTitle">Display Tags</div>
		<select class="select" name="tags_enabled" id="tags_enabled">
			<option <? echo ($config["tags_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
			<option <? echo ($config["tags_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
		</select>
	</p>
	<p>
		<div class="settingTitle">Google Web Fonts Enabled</div>
		<select class="select" name="google_web_fonts_enabled" id="google_web_fonts_enabled">
			<option <? echo ($config["google_web_fonts_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
			<option <? echo ($config["google_web_fonts_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
		</select>
	</p>
	<p>
		<div class="settingTitle">Videos Search Enabled<br>
    		<span style="font-size:10px;">* If you want to enable search on your site</span>
    		</div>
		<select class="select" name="video_search_enabled" id="video_search_enabled">
			<option <? echo ($config["video_search_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
			<option <? echo ($config["video_search_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
		</select>
	</p>
<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

</fieldset>

</div>
<div class="tabbertab" title="Modules">

  <div class="tabber" id="tab1-1">
      <div class="tabbertab" title="Templates">

<?
// Loading Templates
	// template list
	//define the path as relative
	$path = "../templates/";
	//using the opendir function
	$dir_handle = @opendir($path) or die("Unable to open $path");
	//running the while loop
	$i = 0;
	while ($file = readdir($dir_handle))
	{
  		if($file!="." && $file!=".." && $file!="index.php") {
	   		$TEMPLATES[$i] = $file;
			$i++;
		}

	}
	sort($TEMPLATES);
	//closing the directory
	closedir($dir_handle);

?>
  		<p>

		<?
		$screenshot = "../templates/".$config["active_template"]."/screenshot.jpg?".time();
		?>
		<div id="theme_no_screenshot" class="theme_no_screenshot"></div>

		<div align="center">
			<br clear="all"><br>
			<img id="theme_screenshot" class="theme_screenshot" src="<?=$screenshot?>" border="0" onLoad="setContent('theme_no_screenshot',''); this.style.display='block';" onError="setContent('theme_no_screenshot','No screenshot found for this theme!'); this.style.display='none';">
		</div>

		<br clear="all">

    	<div class="settingTitle">Active Template</div>
 		<select class="select" onchange="setThemeScreenshot(this.form.active_template.options[this.form.active_template.selectedIndex].value);" name="active_template" id="active_template">
			<?
			for ($i = 0 ; $i < sizeof($TEMPLATES) ; $i++) {
			?>
			<option <? echo ($config["active_template"] == $TEMPLATES[$i]) ? "selected" : ""; ?> value="<?=$TEMPLATES[$i]?>"><?=ucfirst($TEMPLATES[$i])?></option>
			<?
			}
			?>
		</select>
		<br clear="all"><br clear="all">

 	 	</p>

<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

	  </div>
<?
if (isset($_POST["ad_link_title"])) {

	for($i = 0 ; $i < sizeof($_POST["ad_link_url"]) ; $i++) {
		if($_POST["ad_link_title"][$i] != "" && $_POST["ad_link_url"][$i] != "" && !isDemo()) {
			$linkTitle = $_POST["ad_link_title"][$i];
			$linkUrl = $_POST["ad_link_url"][$i];

			if ( get_magic_quotes_gpc() ) {
				$linkTitle		= stripslashes($linkTitle);
				$linkUrl		= stripslashes($linkUrl);
			}

			$query = "INSERT INTO `".DB_PREFIX."links` (`title`, `url`) VALUES ('".db_escape_string($linkTitle)."', '".db_escape_string($linkUrl)."')";
			dbQuery($query, false);
		}
	}
}

?>




	    <!--##########  SECTION : TAGS  ##########-->
	  <div class="tabbertab" title="Tags">


    <fieldset>
    <legend> Tag Cloud </legend>

  		<p>
    		<div class="settingTitle">Tags Selection</div>
			<select class="select" name="tags_selection" id="tags_selection">
    			<option value="top" <?php if(isset($config['tags_selection']) && $config['tags_selection'] == "top") { echo "selected"; } else { echo ""; } ?>>Top Tags</option>
    			<option value="random" <?php if(isset($config['tags_selection']) && $config['tags_selection'] == "random") { echo "selected"; } else { echo ""; } ?>>Random</option>
    		</select>
  		</p>
  		<p>
    		<div class="settingTitle">Tag Cloud Enabled</div>
 			<select class="select" name="tag_cloud_enabled" id="tag_cloud_enabled">
				<option <? echo ($config["tag_cloud_enabled"] == true) ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["tag_cloud_enabled"] == false) ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
  		<p>
    		<div class="settingTitle">Tags Max Display</div>
    		<input class="input" name="tags_max_display" type="text" id="tags_max_display" value="<?=$config["tags_max_display"]?>">
  		</p>
  		<p>
    		<div class="settingTitle">Tag Max Font Size ( in % )</div>
    		<input class="input" name="tag_max_size" type="text" id="tag_max_size" value="<?=$config["tag_max_size"]?>">
  		</p>
  		<p>
    		<div class="settingTitle">Tag Min Font Size ( in % )</div>
    		<input class="input" name="tag_min_size" type="text" id="tag_min_size" value="<?=$config["tag_min_size"]?>">
  		</p>

<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

		</fieldset>

		<fieldset>
	        <legend> Browse Tags </legend>
			<div id="block-loading-browse-tags"></div>
			<div id="block_browse_tags">

			</div>
		</fieldset>

		<fieldset>
        <legend> Remove Tags from Tag Cloud</legend>


    <center>
		<div id="block-tags-cloud">
			<p id="warning_limit_tags">
			<?php
			$sqlQuery	= "SELECT COUNT(*) AS `total` FROM `".DB_PREFIX."video_tags`";
			$sqlResult	= dbQuery($sqlQuery);

			$data_match	= mysql_fetch_array($sqlResult);
			$total		= 0;
			if ( isset($data_match['total']) && (int)$data_match['total'] > 500000 ) {
				echo '
					<p style="border: 1px solid #99B2DF; padding:5px; color: #000;">
					<img src="images/alert.png">
						<font>You currently have more than 500,000 tags in your database.</font><br>
			(We strongly encourage you to cut it down to at most 500,000 for performance reasons, unless you have a beefy server)
					</p>
				';
			}
			?>
			</p>
			Remove tags except top <input type="text" name="txt_num_tags" id="txt_num_tags" value="1000" /> tags.

			<span id="block-removing-tags-log"></span>

		</div>
		<div id="block-tags2-cloud">
		</div>

    <br>



    <div id="btn_add_new_link" class='shiny-blue' onclick='javascript:remove_tags();'>Remove Tags</div>

    </center>


		</fieldset>

	  </div>



	    <!--##########  SECTION : VIDEO UPLOAD  ##########-->
	  <div class="tabbertab" title="Video Upload">


    <fieldset>
	<?php
		if( isDemo() )
		{
			$config["yt_username"] = "prismov3";
			$config["yt_password"] = "not_revealed_in_demo";
			$config["yt_dev_key"] =  "not_revealed_in_demo";
		}

	?>
    <legend> Video Upload </legend>



		<p>
    		<div class="settingTitle">Video Upload Enabled</div>
			<select class="select" name="video_upload_enabled" id="video_upload_enabled">
				<option <? echo ($config["video_upload_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["video_upload_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
		<p>
    		<div class="settingTitle">Youtube Username</div>
 			<input class="input" name="yt_username" type="text" id="yt_username" value="<?=$config["yt_username"]?>">
  		</p>
		<p>
    		<div class="settingTitle">Youtube Password</div>
 			<input class="input" name="yt_password" type="password" id="yt_password" value="<?=$config["yt_password"]?>">
  		</p>

<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>
		</fieldset>


		 <fieldset>
		    <legend> Video Upload Log </legend>
			<div id="block-loading-video-upload-log"></div>
			<div id="block_video_upload_log">
			</div>
		</fieldset>
	  </div>

      <div class="tabbertab" title="Video Comments">
        <fieldset>
        <legend> Video Comments </legend>

            <p>
				<div class="settingTitle">Facebook Comments</div>
				<select class="select" name="facebook_comments_enabled" id="facebook_comments_enabled" onchange="javascript:set_facebook_app_id();">
					<option <? echo ($config["facebook_comments_enabled"] == true) ? "selected" : ""; ?> value="true">Enabled</option>
					<option <? echo ($config["facebook_comments_enabled"] == false) ? "selected" : ""; ?> value="false">Disabled</option>
				</select>
            </p>
		<? $fb_style = '';
			if ( $config['facebook_comments_enabled'] == false ) {
				$fb_style = 'style="display:none;"';
			}
		?>
		<p>
    		<div class="settingTitle" id="facebook_app_id_mode" <?=$fb_style?>>Facebook App Id <a href='http://support.alurian.com/index.php?/Knowledgebase/Article/View/75/1/how-to-create-a-facebook-app-id-and-setup-facebook-comments-for-prismotube' target='_blank'>( Help? )</a></div>
 			<input class="input" name="facebook_app_id" type="text" id="facebook_app_id" value="<?=$config["facebook_app_id"]?>" <?=$fb_style?>>
  		</p>
            <p>
			<div class="settingTitle">Local Comments Enabled</div>
				<select class="select" name="local_comments_enabled" id="local_comments_enabled">
					<option <? echo ($config["local_comments_enabled"] == true) ? "selected" : ""; ?> value="true">True</option>
					<option <? echo ($config["local_comments_enabled"] == false) ? "selected" : ""; ?> value="false">False</option>
				</select>
			</p>
			<p>
			<div class="settingTitle">Local Comments Max Per Page</div>
				<select class="select" name="local_comments_per_page" id="local_comments_per_page">
				<?
				for($i = 1 ; $i < 49 ; $i++) {
				echo '<option '.(($config["local_comments_per_page"] == $i) ? "selected" : "") .' value="'.$i.'">'.$i.'</option>';
				}
				?>
				</select>
			</p>
            <p>
              <div class="settingTitle">Youtube Comments Enabled</div>
            <select class="select" name="youtube_comments_enabled" id="youtube_comments_enabled">
              <option <? echo ($config["youtube_comments_enabled"] == true) ? "selected" : ""; ?> value="true">True</option>
              <option <? echo ($config["youtube_comments_enabled"] == false) ? "selected" : ""; ?> value="false">False</option>
            </select>
            </p>
<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

        </fieldset>

		<hr>
		<br clear="all">
<?
if(isset($_GET["rmcm"])) {
	$rmCm = $_GET["rmcm"];
	removeComment($rmCm);
}
$comments = getCommentsVideoID(10);
?>
<div class="settingTitle">Manage Local Comments By VideoID</div>
<br><br>
<div id="local_comments2">
<? if ( $comments["total"] > 0 ) { ?>
	<table width="100%" class="tablesorter">
	<thead>
	<tr><th><b>Thumbnail</b></th><th><b>Video ID</b></th><th><b>Manage</b></th></tr>
	</thead>
	<tbody>


	<?
	for ($i = 0 ; $i < $comments["total"] ; $i++)  {
		//$id = $comments[$i]["id"];
		$vid = $comments[$i]["vid"];
		///$user = $comments[$i]["user"];
		//$comment= $comments[$i]["comment"];
		//$posted= date('Y-m-d / H:s', $comments[$i]["posted"]);
		$cls_row = '';
		if ( ($i % 2 ) != 0 ) {
			$cls_row = ' class="odd"';
		}

		echo "
			<tr{$cls_row}>
				<td>
					<a href=\"".$config["website_url"]."video/{$vid}/comment-preview.html\" target=\"_blank\">
						<img src=\"http://img.youtube.com/vi/{$vid}/1.jpg\" width=\"60\" border=\"0\">
					</a>
				</td>
				<td>{$vid}</td>
				<td>
					<a href=\"javascript:ajaxGet('ajax/comments_local.php?vid=$vid&page=1', 'local_comments', false, 'block-manage-video-{$vid}' , '#local_comments-anchor' );\">
						Manage Comments for this video
					</a>
					&nbsp;
					<span id=\"block-manage-video-{$vid}\"></span>
				</td>
			</tr>";
	}
	?>
	</tbody>
	</table>
<? } ?>
<br>
<?=$comments["pagination"]?>
&nbsp;<span id="blk_video_paging"></span>
</div>

<a name="local_comments-anchor"></a>
<div id="local_comments" style="margin-top:15px;">
</div>
    </div>


	<? /* for version 3.5 */ ?>
	<div class="tabbertab" title="Widgets">


    <fieldset>
    <legend> Facebook </legend>

		<p>
    		<div class="settingTitle">Facebook Enabled</div>
			<select class="select" name="facebook_enabled" id="facebook_enabled">
				<option <? echo ($config["facebook_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["facebook_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>

		<? /*
		<iframe src="http://www.facebook.com/plugins/likebox.php?id=185550966885&amp;width=292&amp;connections=10&amp;stream=true&amp;header=false&amp;height=587" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:587px;" allowTransparency="true"></iframe>
		*/ ?>
		<p>
    		<div class="settingTitle">Facebook Page URL</div>
			<input class="input" name="facebook_page_url" type="text" id="facebook_page_url" value="<?=$config["facebook_page_url"]?>">
  		</p>

		<p>
    		<div class="settingTitle">Facebook Stream</div>
			<select class="select" name="facebook_stream" id="facebook_stream">
				<option <? echo ($config["facebook_stream"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["facebook_stream"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
      </fieldset>

	  	   <fieldset>
    <legend> Shoutbox </legend>

		<p> You can add shoutboxes by various providers. e.g.  <a href="http://saybox.co.uk/" target="_blank">Saybox</a>
    		<div class="settingTitle">Shoutbox Enabled</div>
			<select class="select" name="shoutbox_enabled" id="shoutbox_enabled">
				<option <? echo ($config["shoutbox_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["shoutbox_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
		<p>
			<div class="settingTitle">Shoutbox Code</div>
			<textarea class="textarea" name="shoutbox_code" type="text" id="shoutbox_code"><?=stripslashes($config["shoutbox_code"])?></textarea>
		 </p>


      </fieldset>


    <fieldset>
    <legend> Twitter </legend>
		<p>
    		<div class="settingTitle">Twitter Enabled</div>
			<select class="select" name="twitter_enabled" id="twitter_enabled">
				<option <? echo ($config["twitter_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["twitter_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
		<p>
    		<div class="settingTitle">Number of Twitter feeds to display ( max: 15 )</div>
			<input class="input" name="num_twitter_display" type="text" id="num_twitter_display" value="<?=$config["num_twitter_display"]?>">
  		</p>

		<p>
			<div class="settingTitle">Use Category's Keyword<br>
    		<span style="font-size:10px;">* Pull results based on category's keyword on category page<br></span>
    		</div>
			<input style="margin-top:5px;" name="twitter_auto_keyword" type="checkbox" id="twitter_auto_keyword" value="1" <? if ( $config['twitter_auto_keyword'] == '1' ) { echo 'checked'; } ?> />
		</p>

		<p>
			<div class="settingTitle">Retrieve Feeds based on:</div>
			<select class="select" name="rd_twitter_type" id="twitter_search_mode" onchange="javascript:set_keyword_search_mode();">
				<option <? echo ($config["rd_twitter_type"] == 'author') ? "selected" : ""; ?> value="author">Author</option>
				<option <? echo ($config["rd_twitter_type"] == 'keyword') ? "selected" : ""; ?> value="keyword">Keyword</option>
			</select>
			<div style="clear:both;"></div>
			<div class="settingTitle">&nbsp;</div>
			<input style="margin-top:5px;" class="input" name="twitter_key" type="text" id="twitter_key" value="<?=$config["twitter_key"]?>" />

		</p>

		<? $mode_style = '';
			if ( $config['rd_twitter_type'] == 'author' ) {
				$mode_style = 'style="display:none;"';
			}
		?>

		<p>
    		<div class="settingTitle" id="keyword_search_mode" <?=$mode_style?>>Keyword Search Mode</div>
 			<select class="select" name="twitter_search_mode" id="twitter_search_mode2" <?=$mode_style?>>
				<option <? echo ($config["twitter_search_mode"] == 'exact') ? "selected" : ""; ?> value="exact">Exact Match</option>
				<option <? echo ($config["twitter_search_mode"] == 'loose') ? "selected" : ""; ?> value="loose">Broad Match</option>
			</select>
  		</p>
    </fieldset>

    <fieldset>
    	<legend> AddThis Sharing Plugin </legend>
    	<p>
    	Share buttons that appear on the video details page, either on the side or below the video. <br>
    	For more info, visit <a href="http://www.addthis.com" target="_blank">www.addthis.com</a>
    		<div class="settingTitle">AddThis Sharing Plugin Enabled</div>
			<select class="select" name="addthis_enabled" id="addthis_enabled">
				<option <? echo ($config["addthis_enabled"] == true) ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["addthis_enabled"] == false) ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
    <p>
      <div class="settingTitle">Profile ID (Optional, for analytics/tracking )      <br>
      <a href="https://www.addthis.com/settings/publisher" target="_blank">Get it here</a></div>
      <input class="input" name="addthis_profile_id" type="text" id="addthis_profile_id" value="<?=$config["flickr_api_key"]?>">
    </p>
		<p>

		<p>
    		<div class="settingTitle">Select style</div>
        <div class="radioGroup">

            <div class="radio">
            <input name="addthis_style" type="radio" value="1"<?php if($config['addthis_style'] == 1) { echo " checked"; } ?>>
            <img src="images/addthis_1.png">
            </div>
            <div class="radio">
            <input name="addthis_style" type="radio" value="2"<?php if($config['addthis_style'] == 2) { echo " checked"; } ?>>
            <img src="images/addthis_2.png">
            </div>
            <div class="radio">
            <input name="addthis_style" type="radio" value="3"<?php if($config['addthis_style'] == 3) { echo ' checked'; } ?>>
            <img src="images/addthis_3.png">
            </div>

            <input name="addthis_style" type="radio" value="4"<?php if($config['addthis_style'] == 4) { echo " checked"; } ?>>
            <img src="images/addthis_4.png">

            <input name="addthis_style" type="radio" value="5"<?php if($config['addthis_style'] == 5) { echo " checked"; } ?>>
            <img src="images/addthis_5.png">

            <input name="addthis_style" type="radio" value="6"<?php if($config['addthis_style'] == 6) { echo " checked"; } ?>>
            <img src="images/addthis_6.png">

            <div class="radio">
            <input name="addthis_style" type="radio" value="7"<?php if($config['addthis_style'] == 7) { echo " checked"; } ?>>
            <img src="images/addthis_7.png">
            </div>

        </div>
  		</p>
    </fieldset>


	<fieldset>
    <legend> Flickr </legend>
		<p>
    		<div class="settingTitle">Flickr Enabled</div>
			<select class="select" name="flickr_enabled" id="flickr_enabled">
				<option <? echo ($config["flickr_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["flickr_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>

		<p>
    		<div class="settingTitle">Flickr API Key</div>
			<input class="input" name="flickr_api_key" type="text" id="flickr_api_key" value="<?=$config["flickr_api_key"]?>">
  		</p>

		<p>
    		<div class="settingTitle">Flickr Secret</div>
			<input class="input" name="flickr_secret" type="text" id="flickr_secret" value="<?=$config["flickr_secret"]?>">
  		</p>

		<p>
			<div class="settingTitle">Use Category's Keyword<br>
    		<span style="font-size:10px;">* Pull results based on category's keyword on category page<br></span>
    		</div>
			<input style="margin-top:5px;" name="flickr_auto_keyword" type="checkbox" id="flickr_auto_keyword" value="1" <? if ( $config['flickr_auto_keyword'] == '1' ) { echo 'checked'; } ?> />
		</p>

		<p>
			<div class="settingTitle">Retrieve Photos based on:</div>
			<select class="select" name="rd_flickr_type" id="rd_flickr_type" onchange="javascript:set_flickr_keyword_search_mode();">
				<option <? echo ($config["rd_flickr_type"] == 'user_id') ? "selected" : ""; ?> value="user_id">User ID</option>
				<option <? echo ($config["rd_flickr_type"] == 'keyword') ? "selected" : ""; ?> value="keyword">Keyword</option>
			</select>
			<div style="clear:both;"></div>
			<div class="settingTitle">&nbsp;</div>
			<input style="margin-top:5px;" class="input" name="flickr_key" type="text" id="flickr_key" value="<?=$config["flickr_key"]?>" />

		</p>

		<? $mode_style = '';
			if ( $config['rd_flickr_type'] == 'user_id' ) {
				$mode_style = 'style="display:none;"';
			}
		?>

		<p>
    		<div class="settingTitle" id="flickr_keyword_search_mode" <?=$mode_style?>>Keyword Search Mode</div>
 			<select class="select" name="flickr_search_mode" id="flickr_search_mode" <?=$mode_style?>>
				<option <? echo ($config["flickr_search_mode"] == 'exact') ? "selected" : ""; ?> value="exact">Exact Match</option>
				<option <? echo ($config["flickr_search_mode"] == 'loose') ? "selected" : ""; ?> value="loose">Broad Match</option>
			</select>
  		</p>
    </fieldset>


    <fieldset>
    <legend> Wibiya </legend>

		<p>
    		<div class="settingTitle">Wibiya Enabled</div>
			<select class="select" name="wibiya_enabled" id="wibiya_enabled">
				<option <? echo ($config["wibiya_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["wibiya_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
		<p>
			<div class="settingTitle">Wibiya Code</div>
			<textarea class="textarea" name="wibiya_code" type="text" id="wibiya_code"><?=$config["wibiya_code"]?></textarea>
		  </p>

      </fieldset>


	    <fieldset>
	    <legend> Skysa Bar </legend>

		<p>
    		<div class="settingTitle">Skysa Bar Enabled</div>
			<select class="select" name="skysa_bar_enabled" id="skysa_bar_enabled">
				<option <? echo ($config["skysa_bar_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["skysa_bar_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
		<p>
			<div class="settingTitle">Skysa Bar Code</div>
			<textarea class="textarea" name="skysa_bar_code" type="text" id="skysa_bar_code"><?=$config["skysa_bar_code"]?></textarea>
		 </p>


      </fieldset>


	   <fieldset>
	    <legend> Virtual Keyboard </legend>

		<p>
    		<div class="settingTitle">Virtual Keyboard Enabled</div>
			<select class="select" name="virtual_keyboard_enabled" id="virtual_keyboard_enabled">
				<option <? echo ($config["virtual_keyboard_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["virtual_keyboard_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>
		<p>
			<div class="settingTitle">Virtual Keyboard Default Language</div>
			<select class="select" name="virtual_keyboard_default_language" id="virtual_keyboard_default_language">
				<?php foreach($virtual_keyboard_languages as $idx => $vk_language ) { ?>
				<option <? echo ($idx == $config['virtual_keyboard_default_language']) ? "selected" : ""; ?> value="<?=$idx?>"><?=$vk_language["language"]?></option>
				<? } ?>

			</select>
		 </p>


      </fieldset>


<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>
	  </div>








  </div>





</div>



<div class="tabbertab" title="Code">

  <div class="tabber" id="tab1-1">



	  <div class="tabbertab" title="Header and Footer Code">


        <fieldset>
        <legend> Header and Footer </legend>

        <p>
          <div class="settingTitle">Header Code <br> <small>(Code to be placed between the &#60;HEAD&#62; tags )</small></div>
          <textarea class="textarea" name="header_code" type="text" id="header_code"><?=$config["header_code"]?></textarea>
          </p>
        <p>
          <div class="settingTitle">Footer Code <br> <small>(Google Analytics etc...)</small></div>
          <textarea class="textarea" name="footer_code" type="text" id="footer_code"><?=$config["footer_code"]?></textarea>
          </p>
        <p id="saveConfig">
        <input type="submit" name="Submit" value="" class="submitbutton">
      </p>

        </fieldset>

	  </div>


      <div class="tabbertab" title="Ads Management">

        <iframe src="../lib/modules/banners/admin.php" width="90%" height="950" name="banners" frameborder="0" vspace="0" hspace="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" noresize></iframe>

      </div>


      <div class="tabbertab" title="Longtail Video Ads">


        <fieldset>
        <legend> Longtail Video Ads </legend>

          <em style="float:left; margin: 10px; 0 10px 0;">Signup with <a href="http://longtailvideo.com" target="_blank">Longtail Video Ads</a> and earn money from ads showing inside the custom video player</em>
          <div class="clear"></div>
          <p>
            <div class="settingTitle">Longtail Enabled</div>
          <select class="select" name="longtail_enabled" id="longtail_enabled">
            <option <? echo ($config["longtail_enabled"] == true) ? "selected" : ""; ?> value="true">True</option>
            <option <? echo ($config["longtail_enabled"] == false) ? "selected" : ""; ?> value="false">False</option>
          </select>
          </p>
          <p>
            <div class="settingTitle">Longtail Ad Channel <br> <small>This would be your channel's ID</small></div>
            <input class="input" name="longtail_channel" type="text" id="longtail_channel" value="<?=$config["longtail_channel"]?>">
          </p>

<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

        </fieldset>

	  </div>


	</div>
</div>


<div class="tabbertab" title="Tools">

  <div class="tabber" id="tab1-1">
	<div class="tabbertab" title="Advanced Settings">

        <fieldset>
        <legend> Advanced Settings </legend>

		<p>
    		<div class="settingTitle">Notification of Empty Categories Video List<br>
    		<span style="font-size:10px;">* Get notified when one of your categories returns 0 video results</span>
    		</div>
			<select class="select" name="empty_categories_notification_enabled" id="empty_categories_notification_enabled">
				<option <? echo ($config["empty_categories_notification_enabled"] == 'true') ? "selected" : ""; ?> value="true">Enabled</option>
				<option <? echo ($config["empty_categories_notification_enabled"] == 'false') ? "selected" : ""; ?> value="false">Disabled (Recommended)</option>
			</select>
  		</p>

		<p>
    		<div class="settingTitle">Append Search Term<br>
    		<span style="font-size:10px;">* Keyword to improve relevance of video results</span>
    		</div>
			<input class="input" name="search_term" type="text" id="search_term" value="<?=$config["search_term"]?>">
  		</p>

		<p>
    		<div class="settingTitle">Debug Mode<br>
    		<span style="font-size:10px;">* For troubleshooting <a href='diagnostics.php?action=view_debug_log' target="_blank">View Debug Log</a>
    		</span>
    		</div>
			<select class="select" name="debug_mode_enabled" id="debug_mode_enabled">
				<option <? echo ($config["debug_mode_enabled"] == 'true') ? "selected" : ""; ?> value="true">Enabled</option>
				<option <? echo ($config["debug_mode_enabled"] == 'false') ? "selected" : ""; ?> value="false">Disabled (recommended)</option>
			</select>
  		</p>

		<p>
    		<div class="settingTitle">Search Engines Crawler Access<br>
    		<span style="font-size:10px;">* Block useless bots to conserve resources
    		</span>
    		</div>
			<input type="radio" name="allow_spiders" value="1" <?php if ( $config["allow_spiders"] == 1 || !isset($config["allow_spiders"]) ) { echo 'checked'; } ?> /> Allow All
			<input type="radio" name="allow_spiders" value="2" <?php if ( $config["allow_spiders"] == 2 ) { echo 'checked'; } ?> /> Allow Google, Yahoo and Bing only
  		</p>

<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

		</fieldset>

	  </div>

      <div class="tabbertab" title="Search Log">

        <fieldset>
        <legend> Search Log </legend>

		<p>
    		<div class="settingTitle">Search Log Enabled<br>
    		<span style="font-size:10px;">* Records searches done on your site
    		</span></div>
			<select class="select" name="search_log_enabled" id="search_log_enabled">
				<option <? echo ($config["search_log_enabled"] == 'true') ? "selected" : ""; ?> value="true">True</option>
				<option <? echo ($config["search_log_enabled"] == 'false') ? "selected" : ""; ?> value="false">False</option>
			</select>
  		</p>

		<p>
			<a href="javascript:ajaxGet('ajax/search_log.php?page=1', 'block_search_log', false, 'block-loading-search-log');" style="margin-left:10px;" id="btn-search-log">View Search Log</a> <span id="block-loading-search-log"></span>
		</p>

		<div id="block_search_log">
		</div>



<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

		</fieldset>

	  </div>

	  <div class="tabbertab" title="Diagnostics">

        <fieldset>
        <legend> Diagnostics</legend>



		<p>
			<a href="diagnostics.php" style="margin-left:10px;" id="btn-search-log" target="_blank">Diagnostics</a>
		</p>

		<p>
			<a href="javascript:ajaxGet('ajax/error_log.php', 'block_error_log', false, 'block-loading-error-log');" style="margin-left:10px;" id="btn-search-log">View Error Log</a> <span id="block-loading-error-log"></span>
		</p>

		<div id="block_error_log">
		</div>

		<p>
			<a href="javascript:ajaxGet('ajax/tools.php?task=remove_html_cache', 'block_html_log', false, 'block-loading-html-log');" style="margin-left:10px;" id="btn-search-log">Remove HTML templates cache</a> <span id="block-loading-html-log"></span>
		</p>

		<div id="block_html_log">
		</div>

		<?
			$root_path	= dirname(__FILE__);

			$debug_filename	= "debug".md5($config['license_key']).".txt";
			$debug_file	= '../'.$config['logs_cache_dir'].$debug_filename;
			if ($config['debug_mode_enabled'] == 'true' && file_exists($debug_file) ) {
		?>
		<p>
			<a href="diagnostics.php?action=view_debug_log" target="_blank" style="margin-left:10px;" >View Debug Log</a>
		</p>
		<? } ?>

		<p>
			<a href="<?php echo $config['website_url']."remove_xml_cache.php?report&xml_cache_timeout=0"?>" target="_blank" style="margin-left:10px;" >Remove All Youtube cache files</a>
		</p>

		<p>
			<a href="<?php echo $config['website_url']."google_sitemap_xml.php"?>" target="_blank" style="margin-left:10px;" >View Sitemap XML</a>
		</p>


		<p>
			<a href="javascript:ajaxGet('ajax/tools.php?task=optimize_db', 'block_optimize_db', false, 'block-loading-optimize-db');" style="margin-left:10px;" id="btn-search-log">Optimize Database</a> <span id="block-loading-optimize-db"></span>
		</p>

		<div id="block_optimize_db">
		</div>


		</fieldset>



	  </div>



	  <div class="tabbertab" title="Filter">

        <fieldset>
        <legend> Filter </legend>

        Visitors accessing URLs that contain any of the keywords below will be redirected to the main page.

		<p>
    		<div class="settingTitle">Keyword Filter</div>
			<select class="select" name="keyword_filter_enabled" id="keyword_filter_enabled">
				<option <? echo ($config["keyword_filter_enabled"] == 'true') ? "selected" : ""; ?> value="true">Enabled</option>
				<option <? echo ($config["keyword_filter_enabled"] == 'false') ? "selected" : ""; ?> value="false">Disabled</option>
			</select>
  		</p>

		<p>
    		<div class="settingTitle">Keyword Filter Match</div>
 			<select class="select" name="keyword_filter_match" id="keyword_filter_match">
				<option <? echo ($config["keyword_filter_match"] == 'exact') ? "selected" : ""; ?> value="exact">Exact Match</option>
				<option <? echo ($config["keyword_filter_match"] == 'loose') ? "selected" : ""; ?> value="loose">Broad Match</option>
			</select>
  		</p>

		<p>
    		<div class="settingTitle">Keyword List</div>
			<div id="block_keywords_list">
				<? if ( trim($config['keyword_filter_list']) == '' ) { echo '<ul id="navigation-filter" class="list-tabs">&nbsp;</ul>';
				} else {
					$sqlQuery		= "SELECT `id`, `keyword` FROM `".DB_PREFIX."filter` ORDER BY `keyword`";
					$sqlResult3		= dbQuery($sqlQuery);
					$str_li			= "";

					while( $row_k	= mysql_fetch_array($sqlResult3) ) {
						$kword_data	= stripslashes($row_k['keyword']);
						$kword_id	= $row_k['id'];
				?>
				<? /*<span><?php echo $kword_data?> <a href="javascript:ajaxGet('ajax/tools.php?task=delete_keyword&id=<?php echo $kword_id?>', 'block_keywords_list', false, 'block-removing-keyword-filter');"><img src="images/ico-delete.gif" border="0" /></a>&nbsp;</span>  */ ?>
				<? /*$str_li .= "<li>{$kword_data} <a href=\"ajax/tools.php?task=delete_keyword&id={$kword_id}\" id="{$kword_id}"><img src=\"images/ico-delete.gif\" border=\"0\" title=\"ajax/tools.php?task=delete_keyword&id={$kword_id}\" /></a>&nbsp;</li>"; */?>
				<? $str_li .= "<li>{$kword_data} <a href=\"#\" title=\"{$kword_id}\"><img src=\"images/ico-delete.gif\" border=\"0\" title=\"{$kword_id}\" /></a>&nbsp;</li>";?>
				<?php

					}
					?>
				<ul id="navigation-filter" class="list-tabs"><?=$str_li?></ul>

					<?
				} ?>
			</div>
  		</p>

		<div style="clear:both;"></div>
		<div id="block-removing-keyword-filter" style="padding-left:420px;"></div>

        <ul class="nice_btn_blue" style="padding-left: 420px;">
            <center>
            <div class='shiny-blue' id="btn-keyword-list" onclick="javascript:show_block_keyword_list();">Add New Keyword(s)</div>
            </center>
        </ul>

      <br><br><br><br>

<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>

		</fieldset>


		<fieldset>
        <legend> Browse Keyword Matches </legend>
		<div id="block-loading-keyword-matches"></div>
		<div id="block_keyword_matches">
		</div>

		</fieldset>

	  </div>


	</div>
</div>





	    <!--##########  SECTION : Database  ##########-->
			<div class="tabbertab" title="Database">

				<fieldset>

				<legend> Database Settings </legend>
					<p>
					  <img src="images/alert.png"/> If you don't know what you're doing, please do not change anything.
					</p>
					<p>
					  <div class="settingTitle">DB Name</div>
					  <input class="input" name="db_name" type="text" id="db_name" value="<?=DB_NAME?>">
					</p>
					<p>
					  <div class="settingTitle">DB User</div>
					  <input class="input" name="db_user" type="text" id="db_user" value="<?=DB_USER?>">
					</p>
					<p>
					  <div class="settingTitle">DB Pass</div>
					  <input class="input" name="db_pass" type="password" id="db_pass" value="<?=DB_PASS?>">
					</p>
					<p>
					  <div class="settingTitle">DB Host</div>
					  <input class="input" name="db_host" type="text" id="db_host" value="<?=DB_HOST?>">
					</p>
					<p>
					  <div class="settingTitle">DB Prefix</div>
					  <input class="input" name="db_prefix" type="text" id="db_prefix" value="<?=DB_PREFIX?>">
					</p>
				   <p id="saveConfig">
					<input type="image" name="Submit" value="Save" src="images/save.png">
				  </p>

				</fieldset>


				  <br><br>

				  <a href="phpminiadmin/" target="_blank" style='width:150px'><center><div class='shiny-blue'>Click here to browse database</div></center></a>
				  <br><br>

			</div>




<?php if (!isDemo()) { ?>
<div class="tabbertab" title="Help">

  <div class="tabber" id="tab1-1">

	  <div class="tabbertab" title="License Info">


        <fieldset>
        <legend> License Info </legend>

			<?php echo $str_license_html?>

			<div id="blk-license-last-updated"><?php echo $str_license_last_updated?></div>
			<ul class="nice_btn_blue" style="padding-left: 420px;">

			</ul>

        </fieldset>
            <center>
            <div class='shiny-blue' id="btn-remove-license" onclick='javascript:window.location.href="index.php?get_license=1"'>Refresh</div>
            </center>
	  </div>


      <div class="tabbertab" title="Support &amp; Resources">

        <fieldset>
        <legend> Support &amp; Resources </legend>

			<?php echo $str_support_html?>

        </fieldset>
      </div>


      <div class="tabbertab" title="Credits">


        <fieldset>
        <legend> Credits </legend>

			<?php echo $str_credits_html?>

        </fieldset>

	  </div>


	</div>
</div>
<?php } ?>




	<? /*
<p id="saveConfig">
        <input type="image" name="Submit" value="Save" src="images/save.png">
      </p>
  */ ?>
</div>
</div>
</form>
<?if( isset( $_GET['login'] ) ){?>

  <div id="loading_msg" style="background:#FFFCDF none repeat scroll 0 0;font-size:2em;border:10px solid #EFEAB3;margin:0 auto;padding:10px;overflow:hidden;width:950px;">
  Please wait while we load some stuff... Patience is a virtue....
  </div>

<?}?>

<div class="footer_wrap">
<?
include('templates/footer.php');
?>