<?php
	include_once ('../../../config/db_config.php');

	$db_link	= mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME, $db_link);
	
	
	class Ads {
		var $ad_id;
		var $ad_name;
		var $ad_group_id;
		var $width;
		var $height;
		var $code;
		var $position;
		
		function Ads($ad_id) {
			$this->ad_id	= $ad_id;
		}
		
		function load() {
			$sqlQuery	= "SELECT ad_name, ad_group_id, code, position, width, height FROM `".DB_PREFIX."ads` WHERE ad_id = {$this->ad_id}";
			$sqlResult	= mysql_query($sqlQuery);
			$rowList	= mysql_fetch_row($sqlResult);
			$this->ad_name			= stripslashes($rowList[0]);
			$this->ad_group_id		= stripslashes($rowList[1]);
			$this->code				= stripslashes($rowList[2]);
			$this->position			= stripslashes($rowList[3]);
			$this->width			= stripslashes($rowList[4]);
			$this->height			= stripslashes($rowList[5]);
		}
		
		function save() {
			if ( $this->ad_id == 0 ) {
				$sqlQuery	= "INSERT INTO `".DB_PREFIX."ads` SET ad_name = '".db_escape_string($this->ad_name)."',
					ad_group_id  = '".$this->ad_group_id."',
					position  = ".$this->position.",
					code  = '".db_escape_string(base64_decode($this->code))."',
					width	= '".$this->width."',
					height	= '".$this->height."'";
				
				mysql_query($sqlQuery);
				$this->ad_id	= mysql_insert_id();
			} else {
				$sqlQuery	= "UPDATE `".DB_PREFIX."ads` SET ad_name = '".db_escape_string($this->ad_name)."',
					ad_group_id  = '".$this->ad_group_id."',
					position  = ".$this->position.",
					code  = '".db_escape_string(base64_decode($this->code))."',
					width	= '".$this->width."',
					height	= '".$this->height."'
					WHERE ad_id = {$this->ad_id}";
				mysql_query($sqlQuery);
			}
			
		}
		
		function findByGroupID($group_id, $is_active = 0) {
			$str_filter	= "";
			if ( $is_active ) {
				$str_filter	= "AND b.`active` = 1";
			}
			$ads	= array();
			$sqlQuery	= "SELECT ad_id, ad_name, 
					a.ad_group_id, 
					code, 
					position, 
					a.width, 
					a.height 
				FROM `".DB_PREFIX."ads` a INNER JOIN `".DB_PREFIX."ad_group` b ON
				a.ad_group_id = b.ad_group_id
				WHERE a.ad_group_id = {$group_id} {$str_filter}
				ORDER BY `position`";
			
			$sqlResult	= mysql_query($sqlQuery) or die( mysql_error() );
	
			while( $ad_group_arr	= mysql_fetch_array($sqlResult) ) {
				$adgroup				= new Ads($ad_group_arr['ad_id']);
				$adgroup->ad_name		= stripslashes($ad_group_arr['ad_name']);
				$adgroup->ad_group_id	= $ad_group_arr['ad_group_id'];
				$adgroup->position		= $ad_group_arr['position'];
				$adgroup->width			= stripslashes($ad_group_arr['width']);
				$adgroup->code			= stripslashes($ad_group_arr['code']);
				$adgroup->height		= stripslashes($ad_group_arr['height']);
				$ads[]					= $adgroup;
			}
			
			return $ads;
		}
		
		function is_exist_ads_name($ads_name) {
			$sqlQuery	= "SELECT * FROM `".DB_PREFIX."ads` WHERE LCASE(ad_name) = '".strtolower($ads_name)."'";
			$sqlResult	= mysql_query($sqlQuery);
			
			if ( mysql_num_rows($sqlResult) > 0 ) {
				return true;
			} 
			return false;
		}
		
		function delete() {
			$sqlQuery	= "DELETE FROM `".DB_PREFIX."ads` WHERE ad_id = {$this->ad_id}";
			mysql_query($sqlQuery);
		}
		
	}
	
	class AdGroup {
		var $ad_group_id;
		var $group_name;
		var $orientation;
		var $width;
		var $height;
		var $active;
		
		function AdGroup($ad_group_id) {
			$this->ad_group_id	= $ad_group_id;
		}
		
		
		function load() {
			$sqlQuery	= "SELECT group_name, orientation, width, height, `active` FROM `".DB_PREFIX."ad_group` WHERE ad_group_id = {$this->ad_group_id}";
			$sqlResult	= mysql_query($sqlQuery);
			$rowList	= mysql_fetch_row($sqlResult);
			$this->group_name		= stripslashes($rowList[0]);
			$this->orientation		= stripslashes($rowList[1]);
			$this->width			= stripslashes($rowList[2]);
			$this->height			= stripslashes($rowList[3]);
			$this->active			= stripslashes($rowList[4]);
		}
		
		function save() {
			if ( $this->ad_group_id == 0 ) {
				$sqlQuery	= "INSERT INTO `".DB_PREFIX."ad_group` SET group_name = '".db_escape_string($this->group_name)."',
					orientation  = '".$this->orientation."',
					width	= '".$this->width."',
					height	= '".$this->height."',
					`active` = 1";
					
					mysql_query($sqlQuery);
					$this->ad_group_id	= mysql_insert_id();
			} else {
				$sqlQuery	= "UPDATE `".DB_PREFIX."ad_group` SET group_name = '".db_escape_string($this->group_name)."',
					orientation  = '".$this->orientation."',
					width	= '".$this->width."',
					height	= '".$this->height."',
					`active` = {$this->active}
					WHERE ad_group_id = {$this->ad_group_id}";
					mysql_query($sqlQuery);
			}
			return $this->ad_group_id;
		}
		
		function display_all() {
			$adgroups	= array();
			$sqlQuery	= "SELECT ad_group_id,
				group_name,
				orientation, 
				width,
				height
				FROM `".DB_PREFIX."ad_group` ORDER BY `ad_group_id`";
			$sqlResult	= mysql_query($sqlQuery) or die( mysql_error() . $sqlQuery);
	
			while( $ad_group_arr	= mysql_fetch_array($sqlResult) ) {
				$adgroup	= new AdGroup($ad_group_arr['ad_group_id']);
				$adgroup->group_name	= stripslashes($ad_group_arr['group_name']);
				$adgroup->orientation	= stripslashes($ad_group_arr['orientation']);
				$adgroup->width			= stripslashes($ad_group_arr['width']);
				$adgroup->height		= stripslashes($ad_group_arr['height']);
				$adgroups[]				= $adgroup;
			}
			
			return $adgroups;
		}
		
		function is_exist_group_name($group_name) {
			$sqlQuery	= "SELECT * FROM `".DB_PREFIX."ad_group` WHERE LCASE(group_name) = '".strtolower($group_name)."'";
			$sqlResult	= mysql_query($sqlQuery);
			
			if ( mysql_num_rows($sqlResult) > 0 ) {
				return true;
			} 
			return false;
		}
	}
	
	function display_error($string) {
		if ( trim($string) == '' ) {
			return '';
		} else {
			return '<div style="color:#FF0000;">'.$string.'</div>';
		}
	}
	
	
?>