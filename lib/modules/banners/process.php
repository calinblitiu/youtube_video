<?php	
	include_once("../../../init.php");	
	include_once("../../../lib/services.php");	
	$author		= isset($_GET['author']) ? $_GET['author'] : '';	
	$author		= isset($_POST['author']) ? $_POST['author'] : $author;	
	$playlist_id= isset($_GET['playlist_id']) ? $_GET['playlist_id'] : '';	
	$playlist_id= isset($_POST['playlist_id']) ? $_POST['playlist_id'] : $playlist_id;	
	$option_str	= "<option value=\"0\">Please choose your playlist</option>";	
	
	if ( $author != '' ) {		
		$data_videos	= YT_GetUserPlaylists($author);		
		$videos			= YT_Videos($data_videos);				
		foreach($videos as $video) {			
			$playid		= $video['id'];			
			$selected	= "";			
			
			if ($playid == $playlist_id) {				
				$selected	= " selected";			
			}			
			
			$option_str .= "<option value=\"{$playid}\"{$selected}>{$video['title']}</option>";		
		}	
	}	
echo $option_str;
?>