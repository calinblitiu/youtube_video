<?php
function is_keyword_allowable( $keyword )
{
    global $config;
    $is_allowable = true;
    if ( $config['keyword_filter_enabled'] == "true" && $keyword != "" )
    {
        if ( $config['keyword_filter_match'] == "exact" )
        {
            $filter_kwords = explode( "|", $config['keyword_filter_list'] );
            $regex_filter_kwords = array( );
            foreach ( $filter_kwords as $filter_kword )
            {
                $regex_filter_kwords[] = "\\b".$filter_kword."\\b";
            }
            $str_filter = implode( "|", $regex_filter_kwords );
            $banned_keywords = "/(".$str_filter.")/i";
        }
        else
        {
            $banned_keywords = "/(".$config['keyword_filter_list'].")/i";
        }
        preg_match( $banned_keywords, $keyword, $matches );
        if ( isset( $matches[1] ) && trim( $matches[1] ) != "" )
        {
            $sqlQuery = "INSERT INTO `".DB_PREFIX."filter_match` SET `url` =\r\n'".addslashes( $_SERVER['REQUEST_URI'] )."', `term` =\r\n'".addslashes( $matches[1] )."'";
            dbquery( $sqlQuery );
            $is_allowable = false;
        }
    }
    return $is_allowable;
}

class iono_keys
{
  var $license_key = null;
  var $home_url_site = 'alurian.com';
  var $home_url_port = 80;
  var $home_url_iono = '/members/remote.php';
  var $key_location = null;
  var $remote_auth = null;
  var $key_age = null;
  var $key_data = null;
  var $now = null;
  var $result = null;
  var $product_group = '1';
  function iono_keys ($license_key, $remote_auth, $key_location = 'key.php', $key_age = 1296000)
  {
    $this->license_key = $license_key;
    $this->remote_auth = $remote_auth;
    $this->key_location = $key_location;
    $this->key_age = $key_age;
    $this->now = time ();
    $this->install_path = dirname (dirname ($_SERVER['REQUEST_URI']));
    if (empty ($license_key))
    {
      $this->result = 4;
      return false;
    }

    if (empty ($remote_auth))
    {
      $this->result = 4;
      return false;
    }

    if (file_exists ($this->key_location))
    {
      $this->result = $this->read_key ();
    }
    else
    {
      $this->result = $this->generate_key ();
      if (empty ($this->result))
      {
        $this->result = $this->read_key ();
      }
    }

    unset ($this[remote_auth]);
    return true;
  }

  function generate_key ()
  {
    $request = 'remote=licenses&type=5&license_key=' . urlencode (base64_encode ($this->license_key));
    $request .= '&host_ip=' . urlencode (base64_encode ($_SERVER['SERVER_ADDR'])) . '&host_name=' . urlencode (base64_encode ($this->get_domain ())) . '&install_path=' . urlencode (base64_encode ($this->install_path));
    $request .= '&hash=' . urlencode (base64_encode (md5 ($request)));
    $request = $this->home_url_iono . '?' . $request;
    $header = ('' . 'GET ' . $request . ' HTTP/1.0
Host: ' . $this->home_url_site . '
') . '
Connection: Close
User-Agent: iono (www.olate.co.uk/iono)
';
    $header .= '

';
    $fpointer = @fsockopen ($this->home_url_site, $this->home_url_port, $errno, $errstr, 5);
    $return = '';
    if ($fpointer)
    {
      @fwrite ($fpointer, $header);
      while (!@feof ($fpointer))
      {
        $return .= @fread ($fpointer, 1024);
      }

      @fclose ($fpointer);
    }
    else
    {
      return 12;
    }

    $content = explode ('

', $return);
    $content = explode ($content[0], $return);
    $string = urldecode ($content[1]);
    $exploded = explode ('|', $string);
    switch ($exploded[0])
    {
      case 0:
      {
      }
    }

    return 8;
  }

  function read_key ()
  {
    $key = file_get_contents ($this->key_location);
    if ($key !== false)
    {
      $key = str_replace ('
', '', $key);
      $key_string = substr ($key, 0, strlen ($key) - 40);
      $key_sha_hash = substr ($key, strlen ($key) - 40, strlen ($key));
      if (sha1 ($key_string . $this->remote_auth) == $key_sha_hash)
      {
        $key = strrev ($key_string);
        $key_hash = substr ($key, 0, 32);
        $key_data = substr ($key, 32);
        $key_data = base64_decode ($key_data);
        $key_data = unserialize ($key_data);
        if (md5 ($key_data['timestamp'] . $this->remote_auth) == $key_hash)
        {
          if ($this->key_age <= $this->now - $key_data['timestamp'])
          {
            unlink ($this->key_location);
            $this->result = $this->generate_key ();
            if (empty ($this->result))
            {
              $this->result = $this->read_key ();
            }

            return 1;
          }

          $this->key_data = $key_data;
          if ($key_data['license_key'] != $this->license_key)
          {
            return 4;
          }

          if (($key_data['expiry'] <= $this->now AND $key_data['expiry'] != 1))
          {
            return 5;
          }

          if (substr_count ($key_data['hostname'], ',') == 0)
          {
            if (($key_data['hostname'] != $this->get_domain () AND !isset ($key_data['hostname'])))
            {
              return 6;
            }
          }
          else
          {
            $hostnames = explode (',', $key_data['hostname']);
            if (!in_array ($this->get_domain (), $hostnames))
            {
              return 6;
            }
          }

          if (substr_count ($key_data['ip'], ',') == 0)
          {
            if (($key_data['ip'] != $_SERVER['SERVER_ADDR'] AND !isset ($key_data['ip'])))
            {
              return 7;
            }
          }
          else
          {
            $ips = explode (',', $key_data['ip']);
            if (!in_array ($_SERVER['SERVER_ADDR'], $ips))
            {
              return 7;
            }
          }

          if ($key_data['product_group'] !== $this->product_group)
          {
            return 13;
          }

          return 1;
        }

        return 3;
      }

      return 2;
    }

    return 0;
  }

  function get_domain ()
  {
    $url = ereg_replace ('www\\.', '', $_SERVER['SERVER_NAME']);
    if (empty ($url))
    {
      echo 'Hostname is empty. Please contact us to resolve this problem.';
      exit ();
    }

    return $url;
  }

  function get_data ()
  {
    return $this->key_data;
  }
}

define ('BASE_PATH', dirname (__FILE__) . '/');
session_start ();
$token = md5 (uniqid (rand (), true));
$_SESSION['token'] = $token;
ob_start ();
//error_reporting (E_ERROR | E_PARSE);
/*if ( file_exists( "install" ) && $_SERVER['HTTP_HOST'] !== "localhost" )
{
    echo "<b>PrismoTube кажется, не быть установлен! <br><br>If you have already installed it, please delete the install/ directory. <br><br>Otherwise, please navigate to <a href=\"install/index.php\">install/index.php</a> to install it.</b>";
    exit( );
}*/

if (basename ($_SERVER['SCRIPT_FILENAME']) == 'init.php')
{
  exit ();
}

@include_once( BASE_PATH."/config/license_key.php" );
@include_once( BASE_PATH."/config/config.php" );
@include_once( BASE_PATH."/config/config_advanced.php" );
@include_once( BASE_PATH."/config/db_config.php" );
/*if ( !isset( $config['error_reporting'] ) )
	{
	    error_reporting( 0 );
	}
	else
	{
	    error_reporting( $config['error_reporting'] );
	}*/
if ( !isset( $_GET['ajaxcall'] ) && !isset( $_POST['ajaxcall'] ) )
{
  $key_string = $config['license_key'];

  $characters = ’0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ’;
  $remote_auth = '';
  for ($i = 0; $i < 12; $i++) $remote_auth = $characters[rand(0, strlen($characters))];

  $key_location = BASE_PATH . '/license/key.php';
  $key_age = 15 * 86400;
  $http_hostname = str_replace ('www.', '', $_SERVER['HTTP_HOST']);
  /*if ((((($http_hostname == 'localhost' OR $http_hostname == 'anto.pflux.com') OR $http_hostname == 'anto.brilliantconcept.com') OR $http_hostname == 'demo.alurian.com') OR $http_hostname == 'soccergoalsvideo.com'))
  {
    $licensing->result = 1;
  }
  else
  {
    $licensing = new iono_keys ($key_string, $remote_auth, $key_location, $key_age);
  }
  */
  $licensing->result = 1;

  if (($licensing->result != 1 AND $licensing->result != 7))
  {
    unlink ($key_location);
  }

  switch ($licensing->result)
  {
    case 0:
    {
      exit ('Unable to read key');
      break;
    }

    case 1:
    {
      break;
    }

    case 2:
    {
      exit ('SHA1 hash incorrect');
      break;
    }

    case 3:
    {
      exit ('MD5 hash incorrect');
      break;
    }

    case 4:
    {
      exit ('Invalid License key, please insert a valid key within the admin area.');
      break;
    }

    case 5:
    {
      exit ('License has expired');
      break;
    }

    case 6:
    {
      exit ('Host name does not match key file');
      break;
    }

    case 7:
    {
      break;
    }

    case 8:
    {
      exit ('License disabled');
      break;
    }

    case 9:
    {
      exit ('License suspended');
      break;
    }

    case 10:
    {
      exit ('Unable to open file for writing');
      break;
    }

    case 11:
    {
      exit ('Unable to write to file');
      break;
    }

    case 12:
    {
      exit ('Unable to communicate with iono');
      break;
    }

    case 13:
    {
      exit ('Wrong Product Group');
    }
  }
}

$ip = $_SERVER['REMOTE_ADDR'];
require_once BASE_PATH . 'lib/db.php';
if (!dbtableexist (DB_PREFIX . 'video_tags'))
{
  echo '<b>PrismoTube <font color="red">mySQL tables</font> does not appear to be installed, please reinstall it.</b>';
  exit ();
}

include_once( BASE_PATH."lib/smarty/Smarty.class.php" );
include_once( BASE_PATH."lib/functions.php" );
require_once( BASE_PATH."lib/cache_update.php" );
include_once( BASE_PATH."lib/template.php" );
include_once( BASE_PATH."lib/services.php" );
include_once( BASE_PATH."lib/feeds.php" );
include_once( BASE_PATH."lib/modules.php" );
include_once( BASE_PATH."admin/inc/version.php" );
require_once( BASE_PATH."lib/cookie.php" );
require_once( BASE_PATH."/templates/".$config['active_template']."/player/colors.php" );
include_once( BASE_PATH."/config/config_filter.php" );
$file_langs = get_default_languages( );
if ( isset( $file_langs['default'] ) )
{
    include( $file_langs['default'] );
}

include( $file_langs['frontend'] );
$TAG_CACHE = isset( $_GET['tagcache'] ) && $_GET['tagcache'] == "true" ? true : false;
$RSS_CACHE = isset( $_GET['rsscache'] ) && $_GET['rsscache'] == "true" ? true : false;
$PLAYER_FIRST = isset( $_GET['playerfirst'] ) ? $_GET['playerfirst'] : "";
$ADMIN = isset( $_GET['admin'] ) && $_GET['admin'] == "true" ? true : false;
$default_player_skin_path = BASE_PATH."player".DS."skins".DS.$config['cplayer_skin']."/";

if ( !file_exists( $default_player_skin_path.$config['cplayer_skin'].".swf" ) && !file_exists( $default_player_skin_path.$config['cplayer_skin'].".zip" ) )
{
    include_once( BASE_PATH."player/settings.default.php" );
    $file_config_contents = file_get_contents( BASE_PATH."config".DS."config.php" );
    $file_config_contents = preg_replace( "/(.*?)(\\\$config\\[\\\"cplayer\\_skin\\\"\\] \\= \\\")(.*?)(\\\"\\;)(.*?)/si", "\$1\$2".$defaults['default_skin']."\$4\$5", $file_config_contents );
    $config['cplayer_skin'] = $defaults['default_skin'];
    $cfile = BASE_PATH."config/config.php";
    if ( !( $fp = fopen( $cfile, "w" ) ) )
    {
        exit( "Cannot save to file: {$cfile}, Please make this file writable." );
    }
    fwrite( $fp, $file_config_contents );
    fclose( $fp );
}
$theme_base = $config['website_url']."templates/".$config['active_template']."/";
$tpl_base = $config['website_url']."templates/".$config['active_template']."/";
$js_vars = "<script type=\"text/javascript\">var CURRENT_TEMPLATE = \"".$config['active_template']."\"; var website_url = \"".$config['website_url']."\"; var theme_base = \"".$theme_base."\"; var tpl_base = \"".$tpl_base."\"; </script>";
//$Var_7512->Template( );
//$tpl = $Var_7512;
$tpl = new Template ();
include( "lib/assign_global_vars.php" );
if ( !defined( "IS_MOBILE" ) && preg_match( "/(blackberry|android|iphone|ipad|webos|nokia)/i", $_SERVER['HTTP_USER_AGENT'] ) )
{
    header( "Location: ".$config['website_url']."m/" );
    exit( 0 );
}
block_nonmajor_searchengines( );
?>