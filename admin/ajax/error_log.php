<?
@session_start();
include('../../config/db_config.php');
include('../../config/admin_login.php');
include_once('../../lib/db.php');
include('../../config/license_key.php');
include('../../config/config.php');
include('../inc/version.php');
include('../inc/aconfig.php');
include('../inc/functions.php');
include('../../lib/modules.php');

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	
	echo '<div class="error">It seems like your session has already expired. Please login again</div><ul class="nice_btn_blue" style="padding-left: 420px;">
          <li><a class="current" href="'.$config['website_url'].'admin"><span> OK </span></a></li>
        </ul>';
	exit(0);
}


if ( isset($_GET['delete']) && $_GET['delete'] == 1 ) {
	if ( !isDemo() ) {
		$filename	= "../../error_log";
		$is_deleted 	= @unlink($filename);
		
		if ( $is_deleted ) {
			echo '<div class="configSaved">Error Log has been deleted.</div>';
			exit(0);
		} else {
			echo '<div class="error">Unable to delete error log. Please delete it manually.</div>';
			exit(0);
		}
	} else {
		echo '<div class="error">Error Log is not removed. (Demo Mode)</div>';
		exit(0);
	}
}


function get_error_log() {
	$filename	= "../../error_log";
	if ( file_exists($filename) ) {
		if ( function_exists('file_get_contents') ) {
			$file_content	= file_get_contents($filename);
		} else {
			$handle = fopen($filename, "r");
			$file_content = fread($handle, filesize($filename));
			fclose($handle);
		}

		$error_logs	= array();
		$datas		= nl2br($file_content);
		$datas		= explode("<br />", $datas);
		$i			= 0;
		foreach($datas as $data) {
			if ( trim($data) != '' ) {
				$data_ori		= $data;
				preg_match('/.*?\[(.*?)\].*?/si', $data, $matches);
				$strdate	= '';
				if ( isset($matches[1]) ) {
					$strdate	= $matches[1];
				}
				
				$data2	= str_replace("<br />", "", $data);
				$data2	= str_replace("[".$strdate."]", "", $data2);
			
				$error_logs[$i]['date']	= $strdate;
				$error_logs[$i]['msg']	= $data2;
				$i++;
			}
		}
	}
	return $error_logs;
}

$error_logs = get_error_log();
if ( count($error_logs) > 0 ) {
?>
<table width="100%" class="tablesorter">
<thead>

<tr>
	<th width="180"><b>Date</b></th>
	
	<th><b>Error Message</b></th>
</tr>
</thead>
<tbody>

<?
foreach($error_logs as $key => $error_log) {
	$cls_row = ' class="even"';
	if ( ($key % 2 ) != 0 ) {
		$cls_row = ' class="odd"';
	}
	
	echo "<tr{$cls_row}><td><div style='overflow:hidden;'>{$error_log['date']}</div></td><td align=\"center\">{$error_log['msg']}</td></tr>"; 
}
?>

<tr>
<td colspan="2">
<input type="button" name="btnDeleteErrorLog" id="btnDeleteErrorLog" value="Delete Error Log" onclick="javascript:ajaxGet('ajax/error_log.php?delete=', 'block_error_log', '1', 'blk-error-log');"  />
&nbsp;<span id="blk-error-log"></span>
</td>
</tr>

</tbody>
</table>

<? } else { ?>
Nothing Found!
<? } ?>

