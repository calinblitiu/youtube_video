<?php
session_start();
require_once("../../../config/config.php");
require_once("../../../config/config_advanced.php");
if( isset( $config['error_reporting'] ) )
{
	error_reporting($config['error_reporting']);
}
else
{
	error_reporting( 0 );
}

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	header('Location: ../../../admin/');
	die();
}
require_once("../../../config/db_config.php");
include_once("../../../lib/db.php");
include_once('../../../admin/inc/aconfig.php');
include_once('../../../admin/inc/version.php');
include_once('../../../admin/inc/functions.php');

$act = isset($_REQUEST["act"]) ? $_REQUEST["act"] : '';

include ('banners.class.php');



	
	$group_id			= 0;
	$new_group_name		= '';
	$new_orientation	= '';
	$new_width			= '';
	$new_height			= '';
	$post_string		= '';
	
	$hasError			= false;
	$err_group_name		= '';
	$err_orientation	= '';
	$err_width			= '';
	$err_height			= '';
	$err_message		= '';
	
	$is_saved		= false;
	
	if ( isDemo() ) {
		$hasError	= true;
		$err_message	= "No changes will be made on demo.";
	}
	
	if ( isset($_POST['btnSave']) ) {
		$new_group_name	= isset($_POST['new_group_name']) ? trim($_POST['new_group_name']) : '';
		$new_orientation= isset($_POST['new_orientation']) ? trim($_POST['new_orientation']) : '';
		$new_width		= isset($_POST['new_width']) ? trim($_POST['new_width']) : '';
		$new_height		= isset($_POST['new_height']) ? trim($_POST['new_height']) : '';
		
		if ( $new_group_name == '' ) {	
			$hasError	= true;
			$err_group_name	= 'Please specify ad group name.';
		} else if ( AdGroup::is_exist_group_name($new_group_name) ) {
			$hasError	= true;
			$err_group_name	= 'Please specify with another group name';
		}
		
		if ( $new_orientation == '' ) {
			$hasError	= true;
			$err_orientation	= 'Please specify ad orientation.';
		}
		
		if ( !is_numeric($new_width) || $new_width < 0 ) {
			$hasError		= true;
			$err_width		= 'Please specify ad group width with numeric value.';
		}
		
		if ( !is_numeric($new_height) || $new_height < 0 ) {
			$hasError		= true;
			$err_height		= 'Please specify ad group height with numeric value.';
		}
		
		if ( !$hasError ) {
			$adGroupObj	= new AdGroup(0);
			$adGroupObj->group_name		= $new_group_name;
			$adGroupObj->orientation	= $new_orientation;
			$adGroupObj->width			= $new_width;
			$adGroupObj->height			= $new_height;
			$adGroupObj->save();
			
			$new_group_name	= '';
			$new_orientation= '';
			$new_width		= '';
			$new_height		= '';
			$err_message	= 'New ad group has been saved.';
		}
		
	} else if ( isset($_POST['btnEdit']) ) {
		$group_id		= isset($_POST['group_id']) ? trim($_POST['group_id']) : $group_id;
		$new_group_name	= isset($_POST['new_group_name']) ? trim($_POST['new_group_name']) : '';
		$new_orientation= isset($_POST['new_orientation']) ? trim($_POST['new_orientation']) : '';
		$new_width		= isset($_POST['new_width']) ? trim($_POST['new_width']) : '';
		$new_height		= isset($_POST['new_height']) ? trim($_POST['new_height']) : '';
		$ads_position	= isset($_POST['ads_position']) ? trim($_POST['ads_position']) : '';
		$active			= isset($_POST['opt_active']) ? trim($_POST['opt_active']) : '';
		
		
		
		
		$post_string	= "&new_group={$new_group_name}&new_orientation={$new_orientation}&new_width={$new_width}&new_height={$new_height}";
		
		if ( $new_group_name == '' ) {	
			$hasError	= true;
		} 
		
		if ( $new_orientation == '' ) {
			$hasError	= true;
		}
		
		if ( !is_numeric($new_width) || $new_width < 0 ) {
			$hasError		= true;
		}
		
		if ( !is_numeric($new_height) || $new_height < 0 ) {
			$hasError		= true;
		}
		
		if ( !$hasError ) {
			$adGroupObj	= new AdGroup($group_id);
			$adGroupObj->group_name		= $new_group_name;
			$adGroupObj->orientation	= $new_orientation;
			$adGroupObj->width			= $new_width;
			$adGroupObj->height			= $new_height;
			$adGroupObj->active		= $active;
			$adGroupObj->save();
			
			if ( $ads_position != '' ) {
				parse_str($ads_position);
				
				foreach($listItem as $key => $ad_id ) {
					$new_ad_obj		= new Ads($ad_id);
					$new_ad_obj->load();
					$new_ad_obj->position = $key;
					$new_ad_obj->save();
					
				}
			}
			
			$new_group_name	= '';
			$new_orientation= '';
			$new_width		= '';
			$new_height		= '';
			$err_message	= 'Ad group has been saved.';
		}
		
	} else if ( isset($_POST['btnSaveAds']) ) {
		
		$group_id		= isset($_POST['group_id']) ? trim($_POST['group_id']) : $group_id;
		$ads_id			= isset($_POST['ads_id']) ? trim($_POST['ads_id']) : 0;
		$new_code		= isset($_POST['new_code']) ? trim($_POST['new_code']) : '';
		$new_ads_name	= isset($_POST['new_ads_name']) ? trim($_POST['new_ads_name']) : '';
		$new_width		= isset($_POST['new_width']) ? trim($_POST['new_width']) : '';
		$new_height		= isset($_POST['new_height']) ? trim($_POST['new_height']) : '';
		$new_code		= base64_encode($new_code);
		
		
		$post_string	= "&new_code={$new_code}&new_ads_name={$new_ads_name}&new_width={$new_width}&new_height={$new_height}&trysave=1";
		
		if ( $new_ads_name == '' ) {	
			$hasError	= true;
		}
		
		if ( $ads_id == 0 ) {
			if ( Ads::is_exist_ads_name($new_ads_name) ) {
				$hasError	= true;
			}
		}
		
		if ( $new_code == '' ) {
			$hasError	= true;
		}
		
		if ( !is_numeric($new_width) || $new_width < 0 ) {
			$hasError		= true;
		}
		
		if ( !is_numeric($new_height) || $new_height < 0 ) {
			$hasError		= true;
		}
		
		
		
		if ( !$hasError ) {
			$adsObj	= new Ads($ads_id);
			$adsObj->load();
			if ( $ads_id == 0 ) {
				$adsObj->position	= 0;
			}
			
			
			$adsObj->ad_name		= $new_ads_name;
			$adsObj->code			= $new_code;
			$adsObj->ad_group_id	= $group_id;
			$adsObj->width			= $new_width;	
			$adsObj->height			= $new_height;
			$adsObj->save();

			$ads_id					= $adsObj->ad_id;
			$is_saved				= true;
			$new_group_name	= '';
			$new_orientation= '';
			$new_width		= '';
			$new_height		= '';
			$err_message	= 'Ads detail has been saved.';
			$hide_block_ads	= '$("#ads-block").hide();';
		}
	
	}
	
	//print '<pre>';print_r($_POST);print '</pre>';
		$adGroups = AdGroup::display_all();
?>
<html>
<head>
	<link rel="stylesheet" href="../../../admin/css/style.css">
	<script type="text/javascript" src="../../../js/jquery.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.7.1.custom.min.js"></script>
	<script type="text/javascript" src="../../../admin/js/jquery.simplemodal.js"></script>
	<script type="text/javascript" src="../../../admin/js/modalbox.js"></script>
	<script type="text/javascript">
	<!--
		$(document).ready( function() {
			ajaxDropdown2();
			
			// When the document is ready set up our sortable with it's inherant function(s)
			$("#sort-ads").sortable({
			  handle : '.handle',
			  update : function () {
					var order = $('#sort-ads').sortable('serialize');
					alert(order);
					//$("#info").load("process-sortable.php?"+order);
			  }
			});
		});
		
		function delete_ads(ads_id, group_id) {
			var is_confirm = confirm("Are you sure you want to delete this ads?")
			if (is_confirm){
				var paramString = 'ads_id='+ads_id+'&task=delete_ads';
				$.ajax({
					type: "POST",
					url: "ajax.php",
					data: paramString,
					success: function(return_data) {
						$('#ads-block').hide();
					}
				});
				ajax_ad_group(group_id);
			}
			
		}
		
		function show_new_ads_block(ads_id, group_id) {
			var post_string = '<?=$post_string;?>';
			var paramString = 'ads_id='+ads_id+'&group_id='+group_id+'&task=saveads'+post_string;
			$('#ads-block').show();
			$('#ads-block').html('<div style="float:right;"><img src="../../../admin/images/ajax-loader.gif" /></div>');
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: paramString,
				success: function(return_data) {
					
					$('#ads-block').html(return_data).serialize();
				
				}
			});
		}
		
		function ajax_ad_group(group_id) {
			var paramString = 'group_id='+group_id+'&task=editgroup';
			
			$('#group-block').html('<div style="float:left;"><img src="../../../admin/images/ajax-loader.gif" /></div>');
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: paramString,
				success: function(return_data) {
				  $('#group-block').html(return_data).serialize();
				}
			});
			$('#ads-block').hide();
		}
		
		function ajax_post_group(group_id) {
			var post_string = '<?=$post_string;?>';
			var paramString = 'group_id='+group_id+'&task=savegroup'+post_string;
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: paramString,
				success: function(return_data) {
				  $('#group-block').html(return_data).serialize();
				}
			});
		}
		
		function ajaxDropdown2() {
			
			<? if ( isset($_GET['id'])) { ?> var ctg_id = <?=$_GET['id'];?>; <? } else {?>var ctg_id = 0;<? } ?>
			var paramString = 'ctg_id='+ ctg_id+'&task=dropdown2'; 
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: paramString,
				success: function( return_data) {
				  $('#parent').html(return_data);
				}
			});
		}
		
		function ajaxPost(playlist_id) {
			
			var author = $('#users_playlist').val();
			var dataString = 'author='+ author + '&playlist_id=' + playlist_id; 
			//alert (dataString);return false;
			  $.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				success: function( return_data) {
				  $('#playlist_id').html(return_data);
				 
				}
			  });
			  return false;
		}
		
		
		function close_ads_block() {
			$("#ads-block").hide();
		}
		
		
		
  


	-->
	</script>
</head>
<body>
<?

  if(!isset($_GET["id"])) $_GET["id"] = 0;

		$ctg_id = $_GET["id"];

		

		if(isset($msg)) {

			echo'<div style="margin:5px 0 5px 10px; font-size:15px; color:green;">'.$msg.'</div>';

		}
		?>

		<div class="gradientBg" style="width:250px; float:left; margin:10px;">
			
			<!-- div style="color:#EF952C; text-align:center;"><strong><font color="#ffffff">Ad Group</font></strong></div -->
			<H2 style="color:#EF952C"> Ad Group </H2>
			<div id="category-menu-list">
				<?php foreach($adGroups as $adGroup) { ?>
					&raquo;&nbsp;<a href="javascript:ajax_ad_group(<?=$adGroup->ad_group_id;?>);"><font color="#313131"><?=$adGroup->group_name;?></font></a><br />
				<?php } ?>
			</div>
			<br />
			<!-- div style="text-align:center;"><a href="javascript:ajax_ad_group(0);"><font color="#313131">Add New Group</font></a></div -->
			<div style="text-align:center;"> <input type="button" onclick="javascript:ajax_ad_group(0);" value="Add New Group" name="Button" class="button" style="width:150px;" /></div>
		</div>
		
		<div class="gradientBg" style="margin:10px 10px 0 10px; float:right;" id="group-block">
			<form name="form1" method="post" action="">
				<table width="430"  border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3"><H2 style="color:#EF952C"> New Ad Group </H2></td>
				</tr>
				<tr><td colspan="3"><br><?=display_error($err_message);?></td></tr>
				<tr>
					<td width="31%">Group Name</td>
					<td width="1%"></td>
					<td width="68%">
						<input name="new_group_name" type="text" class="input" id="new_group_name" value="<?=$new_group_name?>" size="40"><?=display_error($err_group_name);?>
					</td>
				</tr>
				<tr>
					<td>Orientation</td>
					<td></td>
					<td><input type="radio" name="new_orientation" value="horizontal" <? if ( $new_orientation == 'horizontal' ) echo 'checked';?> />Horizontal 
					<input type="radio" name="new_orientation" class="input" value="vertical" <? if ( $new_orientation == 'vertical' ) echo 'checked';?> />Vertical <?=display_error($err_orientation);?>
					</td>
				</tr>
				<tr>
					<td>Width</td>
					<td></td>
					<td><input name="new_width" type="text" id="new_width" class="input" value="<?=$new_width?>" size="20"><?=display_error($err_width);?></td>
				</tr>
				<tr>
				  <td>Height</td>
				  <td></td>
				  <td>
					<input name="new_height" type="text" id="new_height" class="input" value="<?=$new_height?>" size="20"><?=display_error($err_height);?>
				</td>
				</tr>
				
				<tr><td colspan="3"><br></td></tr>
				<tr>
					<td></td>
					<td>
						&nbsp;
					</td>
					<td><div align="left">
						<input class="button" type="submit" name="btnSave" value="Save">
						</div></td>
				</tr>
				</table>
				
			</form>
		</div>
	
	
	
		<div class="gradientBg" style="margin:10px 10px 0 10px; float:right;display:none;" id="ads-block">
		</div>

<? 
	if ( $_SERVER['REQUEST_METHOD'] == 'GET' && $group_id == 0 ) { 
		$group_id = 1;
?>
	<script type="text/javascript">
	$(document).ready( function() {
		ajax_ad_group(<?=$group_id?>);
	});
	</script>
<? } ?>
	
<?php if ( isset($_POST['btnEdit']) && $group_id != 0 ) { ?>
	<script type="text/javascript">
	$(document).ready( function() {
		ajax_post_group(<?=$group_id?>);
	});
	</script>
<?php }  else if ( isset($_POST['btnSaveAds']) ) {?>
	<script type="text/javascript">
	function ajax_ad_group(group_id) {
		var paramString = 'group_id='+group_id+'&task=editgroup';
		$.ajax({
			type: "POST",
			url: "ajax.php",
			data: paramString,
			success: function(return_data) {
			  $('#group-block').html(return_data).serialize();
			}
		});
		$('#ads-block').hide();
	}
	
	function show_new_ads_block(ads_id, group_id) {
		var paramString = 'ads_id='+ads_id+'&group_id='+group_id+'&task=saveads';
		$.ajax({
			type: "POST",
			url: "ajax.php",
			data: paramString,
			success: function(return_data) {
				$('#ads-block').show();
				$('#ads-block').html(return_data).serialize();
			
			}
		});
	}
	
	function close_ads_block() {
		$("#ads-block").hide();
	}
		
	$(document).ready( function() {
		ajax_ad_group(<?=$group_id?>);
		<? if ( $is_saved ) { ?>
			$("#ads-block").hide();
		<? } else { ?>
		show_new_ads_block(<?=$ads_id?>,<?=$group_id?>);
		<? } ?>
	});
	</script>
<?php } ?>

</body>
</html>