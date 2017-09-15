<?php
	@session_start();
	include_once("../../../init.php");
	include_once("banners.class.php");
	include_once('../../../admin/inc/aconfig.php');


	$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
	if ( !$is_logged ){
		die();
	}
	
	$task			= isset($_POST['task']) ? $_POST['task'] : '';
	$group_id		= isset($_POST['group_id']) ? $_POST['group_id'] : 0;
	$ads_id			= isset($_POST['ads_id']) ? $_POST['ads_id'] : 0;
	$hor_or			= '';
	$ver_or			= '';
	
	$err_group_name	= '';
	$err_orientation= '';
	$err_width		= '';
	$err_height		= '';
	$err_message	= '';
	$err_code		= '';
	$err_ads_name	= '';
	
	if ( isset($aConfig["ADMIN_DEMO"]) && $aConfig["ADMIN_DEMO"] ) {
		$hasError = true;
		$err_message	= display_error("No changes will be made on demo");
	}
	
	if ( $task == 'editgroup') {
		$adGroupObj	= new AdGroup($group_id);
		$adGroupObj->load();
		
		$new_group_name		= isset($_POST['new_group']) ? $_POST['new_group'] : $adGroupObj->group_name;
		$new_orientation	= isset($_POST['new_orientation']) ? $_POST['new_orientation'] : $adGroupObj->orientation;
		$new_width			= isset($_POST['new_width']) ? $_POST['new_width'] : $adGroupObj->width;
		$new_height			= isset($_POST['new_height']) ? $_POST['new_height'] : $adGroupObj->height;
		$opt_active			= isset($_POST['opt_active']) ? $_POST['opt_active'] : $adGroupObj->active;
		
		if ( $new_orientation == 'horizontal' ) {
			$hor_or			= 'checked';
		} else if ( $new_orientation == 'vertical' ) {
			$ver_or			= 'checked';
		}
		
		$js_sortlist		= '';
		$ads_list_str		= '';
		$sort_list			= '&nbsp;';
		$ads_objs			= Ads::findByGroupID($group_id);
		//print '<pre>';print_r($ads_objs);print '</pre>';
		if ( count($ads_objs) > 0 ) {
			$sort_list		= '<ul id="sort-ads">';
			foreach($ads_objs as $key => $ads_obj) {
				$sort_list	.= '<li id="listItem_'.$ads_obj->ad_id.'"><img src="'.$config['website_url'].'admin/images/arrow.png" alt="move" width="16" height="16" class="handle" />
				<strong><a href="javascript:show_new_ads_block('.$ads_obj->ad_id.','.$group_id.');">'.$ads_obj->ad_name.'</a></strong>
				<div style="float:right;"><a href="javascript:show_new_ads_block('.$ads_obj->ad_id.','.$group_id.');">Edit</a> | <a href="javascript:delete_ads('.$ads_obj->ad_id.','.$group_id.');">Delete</a></div></li>';
			}
			$sort_list		.= '</ul>';
			
			$ads_list_str	= "<tr><td colspan=\"3\"><b>Ads of this Group: (Set Position by Drag n Drop)</b></td></tr>";
			
			$js_sortlist	= '<script type="text/javascript">
				// When the document is ready set up our sortable with it\'s inherant function(s)
				$(document).ready(function() {
					$("#sort-ads").sortable({
						handle : \'.handle\',
						update : function () {
							var order = $(\'#sort-ads\').sortable(\'serialize\');
							$("#ads_position").val(order);
						}
					});
				});
			</script>';
	
		}
		
		
		$blk_active		= "";
		if ( $group_id > 0 ) {
			$block_title	= 'Edit Ad Group';
			
			$opt_yes		= "";
			$opt_no			= "";
			
			if ( $opt_active ) {
				$opt_yes	= "selected";
			} else {
				$opt_no	= "selected";
			}
			
			$blk_active			= "<tr>
				  <td>Active</td>
				  <td></td>
				  <td>
					<select name=\"opt_active\" style=\"width:100px;\" class=\"input\">
					<option value=\"1\" {$opt_yes}>Yes</option>
					<option value=\"0\" {$opt_no}>No</option>
					</select>
				</td>
				</tr>";
		} else {
			$block_title	= 'Add New Group';
		}
		
		$string = <<<__CONTENT__
	<form name="form1" method="post" action="">
				<table width="430"  border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3"><H2 style="color:#EF952C"> {$block_title}: {$new_group_name} </H2></td>
				</tr>
				<tr><td colspan="3"><br>{$err_message}</td></tr>
				<tr>
					<td width="31%">Group Name</td>
					<td width="1%"></td>
					<td width="68%">
						<input name="new_group_name" class="input" type="text" id="new_group_name" value="{$new_group_name}" size="40">{$err_group_name}
					</td>
				</tr>
				<tr>
					<td>Orientation</td>
					<td></td>
					<td><input type="radio" id="new_orientation" name="new_orientation" value="horizontal" {$hor_or} />Horizontal 
					<input type="radio" id="new_orientation" name="new_orientation" value="vertical" {$ver_or} />Vertical {$err_orientation}
					</td>
				</tr>
				<tr>
					<td>Width</td>
					<td></td>
					<td><input name="new_width" type="text" id="new_width" class="input" value="{$new_width}" size="20">{$err_width}</td>
				</tr>
				<tr>
				  <td>Height</td>
				  <td></td>
				  <td>
					<input name="new_height" type="text" id="new_height" class="input" value="{$new_height}" size="20">{$err_height}
				</td>
				</tr>
				{$blk_active}
				
				{$ads_list_str}
				<tr><td colspan="3">{$sort_list}</td></tr>
				
				<tr><td colspan="3" align="center">
						
		<a href="javascript:show_new_ads_block(0, {$group_id});">Add new Ad</a>
					</td></tr>
				<tr><td colspan="3">&nbsp;</td></tr>
				<tr>
					<td></td>
					<td>
						&nbsp;
					</td>
					<td><div align="left">
						
						<input class="button" type="submit" name="btnEdit" value="Save">
						<a href="javascript:ajax_ad_group({$group_id});">Reset</a>
						<input type="hidden" name="group_id" value="{$group_id}" />
						<input type="hidden" name="ads_position" id="ads_position" value="" />
						</div></td>
				</tr>
				</table>
				
			</form>
			{$js_sortlist}
__CONTENT__;
		echo $string;
	} else if ( $task == 'savegroup' ) {
		$adGroupObj	= new AdGroup($group_id);
		$adGroupObj->load();
		//$group_id	= $adGroupObj->ad_group_id;
		
		$new_group_name		= isset($_POST['new_group']) ? $_POST['new_group'] : $adGroupObj->group_name;
		$new_orientation	= isset($_POST['new_orientation']) ? $_POST['new_orientation'] : $adGroupObj->orientation;
		$new_width			= isset($_POST['new_width']) ? $_POST['new_width'] : $adGroupObj->width;
		$new_height			= isset($_POST['new_height']) ? $_POST['new_height'] : $adGroupObj->height;
		
		if ( $new_orientation == 'horizontal' ) {
			$hor_or			= 'checked';
		} else if ( $new_orientation == 'vertical' ) {
			$ver_or			= 'checked';
		}
		
		if ( $new_group_name == '' ) {	
			$hasError	= true;
			$err_group_name	= 'Please specify ad group name.';
			$err_group_name	= display_error($err_group_name);
		} 
		
		if ( $new_orientation == '' ) {
			$hasError	= true;
			$err_orientation	= 'Please specify ad orientation.';
			$err_orientation	= display_error($err_orientation);
		}
		
		if ( !is_numeric($new_width) || $new_width < 0 ) {
			$hasError		= true;
			$err_width		= 'Please specify ad group width with numeric value.';
			$err_width	= display_error($err_width);
		}
		
		if ( !is_numeric($new_height) || $new_height < 0 ) {
			$hasError		= true;
			$err_height		= 'Please specify ad group height with numeric value.';
			$err_height		= display_error($err_height);
		}
		
		$string = <<<__CONTENT__
	<form name="form1" method="post" action="">
				<table width="400"  border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3"><H2 style="color:#EF952C">Edit Ad Group: {$adGroupObj->group_name}</H2></td>
				</tr>
				<tr><td colspan="3"><br>{$err_message}</td></tr>
				<tr>
					<td width="31%">Group Name</td>
					<td width="1%"></td>
					<td width="68%">
						<input name="new_group_name" type="text" id="new_group_name" class="input" value="{$new_group_name}" size="20">{$err_group_name}
					</td>
				</tr>
				<tr>
					<td>Orientation</td>
					<td></td>
					<td><input type="radio" id="new_orientation" name="new_orientation" value="horizontal" {$hor_or} />Horizontal 
					<input type="radio" id="new_orientation" name="new_orientation" value="vertical" {$ver_or} />Vertical {$err_orientation}
					</td>
				</tr>
				<tr>
					<td>Width</td>
					<td></td>
					<td><input name="new_width" type="text" id="new_width" class="input" value="{$new_width}" size="20">{$err_width}</td>
				</tr>
				<tr>
				  <td>Height</td>
				  <td></td>
				  <td>
					<input name="new_height" type="text" id="new_height" class="input" value="{$new_height}" size="20">{$err_height}
				</td>
				</tr>
				
				<tr><td colspan="3"><br></td></tr>
				<tr>
					<td></td>
					<td>
						&nbsp;
					</td>
					<td><div align="left">
			
						<input class="button" type="button" name="btnAdd" value="Add new Ad" onclick="javascript:show_new_ads_block(0, {$group_id});">
						<input class="button" type="submit" name="btnEdit" value="Save">
						<input type="hidden" name="group_id" value="{$group_id}" />
						</div></td>
				</tr>
				</table>
				
			</form>
__CONTENT__;
		echo $string;
		
		
	} else if ( $task == 'saveads' ) {
		$title = 'Edit Ad Detail';
		if ($ads_id == 0) {
			$title = 'Add New Ad';
		} 
		
		
		$adsObj	= new Ads($ads_id);
		$adsObj->load();
		if ( isset($_POST['new_code']) ) {
			$new_code		= base64_decode($_POST['new_code']);
		} else {
			$new_code		= $adsObj->code;
		}
	
		$new_ads_name		= isset($_POST['new_ads_name']) ? trim($_POST['new_ads_name']) : $adsObj->ad_name;
		$new_width			= (isset($_POST['new_width']) && (trim($_POST['new_width']) != '' || $_POST['new_width'] != 0)) ? $_POST['new_width'] : $adsObj->width;
		$new_height			= (isset($_POST['new_height']) && (trim($_POST['new_height']) != '' || $_POST['new_height'] != 0)) ? $_POST['new_height'] : $adsObj->height;
		$trysave			= isset($_POST['trysave']) ? true : false;
		
		if ( $trysave ) {
			if ( $new_ads_name == '' ) {	
				$hasError	= true;
				$err_ads_name	= 'Please specify ad name.';
				$err_ads_name	= display_error($err_ads_name);
			} 
			
			if ( $ads_id == 0 ) {
				if ( Ads::is_exist_ads_name($new_ads_name) ) {
					$hasError	= true;
					$err_ads_name	= 'Please specify with another ad name.';
					$err_ads_name	= display_error($err_ads_name);
				}
			}
			
			if ( $new_code == '' ) {
				$hasError	= true;
				$err_code	= 'Please specify ad code.';
				$err_code	= display_error($err_code);
			}
			
			if ( !is_numeric($new_width) || $new_width < 0 ) {
				$hasError		= true;
				$err_width		= 'Please specify ad group width with numeric value.';
				$err_width	= display_error($err_width);
			}
			
			if ( !is_numeric($new_height) || $new_height < 0 ) {
				$hasError		= true;
				$err_height		= 'Please specify ad group height with numeric value.';
				$err_height		= display_error($err_height);
			}
		}
		
		$blk_delete = '';
		if ( $ads_id != 0 ) {
			$blk_delete = '<a href="javascript:delete_ads('.$ads_id.', '.$group_id.');">Delete</a> | ';
		}
		
		$string = <<<__CONTENT__
	<form name="form1" method="post" action="">
				<table width="400"  border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="3"><H2 style="color:#EF952C">{$title}</H2></td>
				</tr>
				<tr><td colspan="3"><br>{$err_message}</td></tr>
				<tr>
					<td width="31%">Ads Name</td>
					<td width="1%"></td>
					<td width="68%">
						<input name="new_ads_name" type="text" class="input" id="new_ads_name" value="{$new_ads_name}" size="20">{$err_ads_name}
					</td>
				</tr>
				<tr>
					<td>Code</td>
					<td></td>
					<td><textarea name="new_code" id="new_code" class="input" row="30" cols="30">{$new_code}</textarea> {$err_code}
					</td>
				</tr>
				<tr>
					<td>Width</td>
					<td></td>
					<td><input name="new_width" type="text" id="new_width" value="{$new_width}" size="20" class="input">{$err_width}</td>
				</tr>
				<tr>
				  <td>Height</td>
				  <td></td>
				  <td>
					<input name="new_height" type="text" id="new_height" value="{$new_height}" size="20" class="input" >{$err_height}
				</td>
				</tr>
				
				<tr><td colspan="3"><br></td></tr>
				<tr>
					<td></td>
					<td>
						<div align="left">

						<input class="button" type="submit" name="btnSaveAds" value="Save">
						<input type="hidden" name="group_id" value="{$group_id}" />
						<input type="hidden" name="ads_id" value="{$ads_id}" />
						</div>
					</td>
					<td>{$blk_delete}<a href="javascript:close_ads_block();">Cancel</a></td>
				</tr>
				</table>
				
			</form>
__CONTENT__;
		echo $string;
	
	
	
	
	} else if ( $task == 'delete_ads' ) {
		$adsObj	= new Ads($ads_id);
		$adsObj->load();
		$adsObj->delete();
	}
	
?>