<?php
	
	require_once("./config/db_config.php");
	//require_once("./lib/db.php");
	
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");
	mysql_select_db(DB_NAME, $conn) or die("Could not connect to database. Please check your DB Settings from your admin area or in \"config/db_config.php\"");
	
	class Videos {
		var $id;
		var $video_id;
		var $duration = 0;
		var $title = '';
		var $author = '';
		var $keywords = '';
		var $description = '';
		var $category = '';
		var $tube_type;
		var $view_count = 0;
		var $comment_count = 0;
		var $favorite_count = 0;
		var $rating_count = 0;
		var $rating = 0;
		var $response_count = 0;
		var $publish_time;
		var $last_viewed;
		
		function Videos($id) {
			$this->id = $id;
		}
		
		function FindVideoList($by, $list_per_page, $time) {
			$conn 		= get_db_conn();
			$VideoObjects	= array();
			//echo $by;exit(0);
			if ( $by == 'most_recent' ) {
				$sqlQuery 	= "SELECT `id` FROM `".DB_PREFIX."videos` ORDER BY publish_time DESC LIMIT {$list_per_page}";
			} else if ( $by == 'most_viewed' ) {
			
				if ( $time == 'all_time'  ) {
					$sqlQuery 	= "SELECT `id` FROM `".DB_PREFIX."videos` ORDER BY view_count DESC LIMIT {$list_per_page}";
				} else if ( $time == 'today' || $time == '' ) {
					$sqlQuery 	= "SELECT `id` FROM `".DB_PREFIX."videos` WHERE FROM_UNIXTIME(last_viewed, '%Y-%m-%d') = '".date("Y-m-d")."' ORDER BY view_count DESC LIMIT {$list_per_page}";
				} else if ( $time == 'this_week' ) {
					$sqlResult2		= mysql_query("SELECT WEEK('".date("Y-m-d")."', 7);");
					list($week_num)	= mysql_fetch_row($sqlResult2);
					$dates			= Videos::Week_Date($week_num, date("Y"), 1, "Y-m-d");
					$sqlQuery 		= "SELECT `id` FROM `".DB_PREFIX."videos` WHERE FROM_UNIXTIME(last_viewed, '%Y-%m-%d') BETWEEN '".$dates[0]."' AND '".$dates[1]."' ORDER BY view_count DESC LIMIT {$list_per_page}";
				} else if ( $time == 'this_month' ) {
					$sqlQuery 		= "SELECT `id` FROM `".DB_PREFIX."videos` WHERE FROM_UNIXTIME(last_viewed, '%m') = '".date('m')."' ORDER BY view_count DESC LIMIT {$list_per_page}";
				}
				
			} else if ( $by == "top_favorites" ) {
				$sqlQuery 	= "SELECT `id` FROM `".DB_PREFIX."videos` ORDER BY favorite_count DESC LIMIT {$list_per_page}";
			} else if ( $by == "top_rated" ) {
				$sqlQuery 	= "SELECT `id` FROM `".DB_PREFIX."videos` ORDER BY rating_count DESC LIMIT {$list_per_page}";
			} else if ( $by == "most_discussed" ) {
				//$sqlQuery 	= "SELECT `id` FROM `videos` ORDER BY comment_count DESC LIMIT {$list_per_page}";
				if ( $time == 'all_time'  ) {
					$sqlQuery 	= "SELECT v.id, COUNT(`vid`) AS total_comments 
					FROM `".DB_PREFIX."video_comments` vc INNER JOIN `".DB_PREFIX."videos` v ON vc.vid = v.video_id
					GROUP BY vc.vid
					ORDER BY total_comments DESC LIMIT {$list_per_page}";
					
				} else if ( $time == 'today' || $time == '' ) {
					$sqlQuery 	= "SELECT v.id, COUNT(`vid`) AS total_comments 
					FROM `".DB_PREFIX."video_comments` vc INNER JOIN `".DB_PREFIX."videos` v ON vc.vid = v.video_id
					WHERE FROM_UNIXTIME(posted, '%Y-%m-%d') = '".date("Y-m-d")."'
					GROUP BY vc.vid
					ORDER BY total_comments DESC LIMIT {$list_per_page}";
				} else if ( $time == 'this_week' ) {
					$sqlResult2		= mysql_query("SELECT WEEK('".date("Y-m-d")."', 7);");
					list($week_num)	= mysql_fetch_row($sqlResult2);
					$dates			= Videos::Week_Date($week_num, date("Y"), 1, "Y-m-d");
					
					$sqlQuery 	= "SELECT v.id, COUNT(`vid`) AS total_comments 
					FROM `".DB_PREFIX."video_comments` vc INNER JOIN `".DB_PREFIX."videos` v ON vc.vid = v.video_id
					WHERE FROM_UNIXTIME(last_viewed, '%Y-%m-%d') BETWEEN '".$dates[0]."' AND '".$dates[1]."'
					GROUP BY vc.vid
					ORDER BY total_comments DESC LIMIT {$list_per_page}";
				} else if ( $time == 'this_month' ) {
					$sqlQuery 	= "SELECT v.id, COUNT(`vid`) AS total_comments 
					FROM `".DB_PREFIX."video_comments` vc INNER JOIN `".DB_PREFIX."videos` v ON vc.vid = v.video_id
					WHERE FROM_UNIXTIME(last_viewed, '%m') = '".date('m')."'
					GROUP BY vc.vid
					ORDER BY total_comments DESC LIMIT {$list_per_page}";
				}
			} else if ( $by == "most_responded" ) {
				$sqlQuery 	= "SELECT `id` FROM `".DB_PREFIX."videos` ORDER BY response_count DESC LIMIT {$list_per_page}";
			}
			
			$sqlResult	= mysql_query($sqlQuery, $conn);
			while( list($id) = mysql_fetch_row($sqlResult) ) {
				$VideoObject = new Videos($id);
				$VideoObject->load();
				$VideoObjects[] = $VideoObject;
			}
			return $VideoObjects;
		}
		
		function find_by_video_id($video_id, $published) {
			$conn = get_db_conn();
			$sqlQuery	= "SELECT `id` FROM `".DB_PREFIX."videos` WHERE `video_id` = '{$video_id}'";
			$sqlResult	= mysql_query($sqlQuery, $conn) or die( mysql_error() );
			if ( mysql_num_rows($sqlResult) > 0 ) {
				list($vid)	= mysql_fetch_row($sqlResult);
				$VideoObject = new Videos($vid);
				$VideoObject->load();
			} else {
			
				$VideoObject = new Videos(0);
				$VideoObject->video_id = $video_id;
				$VideoObject->tube_type = 'yt';
				$VideoObject->publish_time = $published;
				$VideoObject->last_viewed = time();
				$VideoObject->save();
			}
			mysql_close($conn);
			return $VideoObject;
		}
		
		function save() {
			$conn = get_db_conn();
			if ( $this->id == 0 ) {
				if ( isset($this->publish_time) && $this->publish_time != '' ) {
				$sqlQuery = "INSERT INTO `".DB_PREFIX."videos` SET 
					`video_id` 		= '{$this->video_id}',
					`author`		= '{$this->author}',
					`duration`		= {$this->duration},
					`title`			= '".db_escape_string($this->title)."',
					`description`	= '".db_escape_string($this->description)."',
					`category`		= '{$this->category}',
					`keywords`		= '".db_escape_string($this->keywords)."',
					`tube_type`		= '{$this->tube_type}',
					`view_count`	= {$this->view_count},
					`comment_count`	= {$this->comment_count},
					`favorite_count`= {$this->favorite_count},
					`rating_count`	= {$this->rating_count},
					`rating`		= {$this->rating},
					`response_count`= {$this->response_count},
					`publish_time`	= {$this->publish_time},
					`last_viewed`	= {$this->last_viewed}
				";
				mysql_query($sqlQuery, $conn) or die( mysql_error() );
				$this->id	= mysql_insert_id();
				}
			} else {
				$sqlQuery = "UPDATE `".DB_PREFIX."videos` SET 
					`video_id` 		= '{$this->video_id}',
					`author`		= '{$this->author}',
					`duration`		= {$this->duration},
					`title`			= '".db_escape_string($this->title)."',
					`description`	= '".db_escape_string($this->description)."',
					`category`		= '{$this->category}',
					`keywords`		= '".db_escape_string($this->keywords)."',
					`tube_type`		= '{$this->tube_type}',
					`view_count`	= {$this->view_count},
					`comment_count`	= {$this->comment_count},
					`favorite_count`= {$this->favorite_count},
					`rating_count`	= {$this->rating_count},
					`rating`		= {$this->rating},
					`response_count`= {$this->response_count},
					`publish_time`	= {$this->publish_time},
					`last_viewed`	= {$this->last_viewed}
					WHERE `id` = {$this->id}
				";
				
				mysql_query($sqlQuery, $conn);
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
		
		function count_increment($field) {
			$conn = get_db_conn();
			$sqlQuery	= "UPDATE `".DB_PREFIX."videos` SET
				`{$field}` = `{$field}` + 1
			WHERE `id` = {$this->id}";
			
			$sqlResult	= mysql_query($sqlQuery, $conn) or die( mysql_error());
			mysql_close($conn);
		}
		
		function Week_Date($wk_num, $yr, $first = 1, $format = 'F d, Y'){
			$wk_ts  	= strtotime('+' . $wk_num . ' weeks', strtotime($yr . '0101')); 
			$mon_ts 	= strtotime('-' . date('w', $wk_ts) + $first . ' days', $wk_ts); 
			$start_date = date($format, $mon_ts); 
			$end_date	= date($format, strtotime('+6 days', strtotime($start_date))); 
			return array($start_date, $end_date);
		}
	}
	

?>