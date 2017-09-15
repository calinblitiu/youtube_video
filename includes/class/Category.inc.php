<?php
	
	require_once(BASE_PATH."/config/db_config.php");
	//require_once("./lib/db.php");
	
	//$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");
	//mysql_select_db(DB_NAME, $conn) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");
	
	
	
	class Category {
		var $id;
		var $position;
		var $c_name;
		var $c_listing_source;
		var $c_desc;
		var $c_keyword;
		var $c_user_videos;
		var $c_group;
		var $c_playlist_id;
		
		function Category($id) {
			$this->id = $id;
		}
		
		function load() {
			$conn = get_db_conn();
			$sqlQuery	= "SELECT * FROM `".DB_PREFIX."categories` WHERE `id` = {$this->id}";
			$sqlResult	= mysql_query($sqlQuery, $conn);
			if ( mysql_num_rows($sqlResult) > 0 ) {
				$rowArray	= mysql_fetch_array($sqlResult);
				$this->position 		= $rowArray['position'];
				$this->c_name			= $rowArray['c_name'];
				$this->c_desc			= $rowArray['c_desc'];
				$this->c_listing_source	= $rowArray['c_listing_source'];
				$this->c_keyword		= $rowArray['c_keyword'];
				$this->c_user_videos	= $rowArray['c_user_videos'];
				$this->c_group			= $rowArray['c_group'];
				$this->c_playlist_id	= $rowArray['c_playlist_id'];
				mysql_close($conn);
				return true;
			} 
			mysql_close($conn);
			return false;
		}
		
	}
	
?>