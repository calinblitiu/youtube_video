<?php
	
	require_once("./config/db_config.php");
	//require_once("./lib/db.php");
	
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");
	mysql_select_db(DB_NAME, $conn) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");
	
	class VideosUploadLog {
		var $id;				var $youtube_id		= '';
		var $title 			= '';
		var $category 		= '';
		var $keywords 		= '';
		var $description 	= '';
		var $ip_addr 		= '';
		var $date_upload 	= '';
		var $country_code	= '';
		
		function VideosUploadLog($id) {
			$this->id = $id;
		}
		
		function save() {
			$conn = get_db_conn();
			if ( $this->id == 0 ) {
				$sqlQuery = "INSERT INTO `".DB_PREFIX."videos_upload_log` SET 
					`title`			= '".db_escape_string($this->title)."',										`youtube_id`			= '".db_escape_string($this->youtube_id)."',
					`description`	= '".db_escape_string($this->description)."',
					`category`		= '{$this->category}',
					`keywords`		= '".db_escape_string($this->keywords)."',
					`date_upload`	= {$this->date_upload},
					`ip_addr`		= '{$this->ip_addr}',
					`country_code`	= '{$this->country_code}'
				";
				
				mysql_query($sqlQuery, $conn) or die( mysql_error() );
				$this->id	= mysql_insert_id();
			} 
			mysql_close($conn);
		}
		
		function load() {
			$conn = get_db_conn();
			$sqlQuery	= "SELECT * FROM `".DB_PREFIX."videos` WHERE `id` = {$this->id}";
			$sqlResult	= mysql_query($sqlQuery, $conn);
			if ( mysql_num_rows($sqlResult) > 0 ) {
				$rowArray	= mysql_fetch_array($sqlResult);
				$this->video_id 		= $rowArray['video_id'];
				$this->tube_type		= $rowArray['tube_type'];
				$this->author			= $rowArray['author'];
				$this->title			= $rowArray['title'];
				$this->duration			= $rowArray['duration'];
				$this->description		= $rowArray['description'];
				$this->category			= $rowArray['category'];
				$this->keywords			= $rowArray['keywords'];
				$this->view_count		= $rowArray['view_count'];
				$this->comment_count	= $rowArray['comment_count'];
				$this->favorite_count	= $rowArray['favorite_count'];
				$this->rating_count		= $rowArray['rating_count'];
				$this->rating			= $rowArray['rating'];
				$this->response_count	= $rowArray['response_count'];
				$this->publish_time		= $rowArray['publish_time'];
				$this->last_viewed		= $rowArray['last_viewed'];
				mysql_close($conn);
				return true;
			} 
			mysql_close($conn);
			return false;
		}
		
		
	}
	
?>