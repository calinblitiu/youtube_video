<?php

function getLinks() {

	$query = "SELECT * FROM `".DB_PREFIX."links`";
	$result = dbQuery($query, false);
	$count = 0;
	$links = "";
	while (list ($link_id_r, $link_title_r, $link_url_r) = @mysql_fetch_row($result))  {
	
		$linkID = $link_id_r;
		$linkTitle = stripslashes($link_title_r);
		$linkURL = $link_url_r;


		$links .= "<div style=\"margin:5px;\"><a class=\"blk_link\" href=\"$linkURL\" target=\"_blank\">$linkTitle</a></div>"; 
		$count++;
	}
	if($count == 0)
		$links = "<div style=\"margin:5px;\">".lang('nothing_found')."</div>"; 

	echo $links;

}

function getLinks_data() {
	$query = "SELECT * FROM `".DB_PREFIX."links`";
	$result = dbQuery($query, false);
	$count = 0;
	$links = array();

	while (list ($link_id_r, $link_title_r, $link_url_r) = @mysql_fetch_row($result))  {
		$linkID 	= $link_id_r;
		$linkTitle 	= stripslashes($link_title_r);
		$linkURL 	= $link_url_r;

		$links[$count]['id'] 	= $linkID;
		$links[$count]['title'] = $linkTitle;
		$links[$count]['url']	= $linkURL;
		$count++;
	}

	return $links;
	
}

?>