<?

if ( !function_exists('get_default_languages') ) {
	function get_default_languages() {
		global $config;

		@define("DS", DIRECTORY_SEPARATOR);
		$path				= BASE_PATH;

		$files				= array();
		$default_language	= $config['web_default_language'];
		
		if ( file_exists($path."language".DS.$default_language.DS."frontend.php") ) {
			$files['frontend']	= $path."language".DS.$default_language.DS."frontend.php";
			$files['default']	= $path."language".DS."en".DS."frontend.php";
		} else {
			$files['frontend']	= $path."language".DS."en".DS."frontend.php";
		}
		return $files;
	}
}

if ( !function_exists('lang') ) {
	function lang($key, $default_value = '') {
		global $lang;
		if ( isset($lang[$key]) ) {
			return $lang[$key];
		} else {
			return $default_value;
		}
	}
}

function addComment($vid, $user, $comment) {
	if ( get_magic_quotes_gpc() ) {
		$comment	= stripslashes($comment);
	}

	$query = "INSERT INTO ".DB_PREFIX."video_comments (vid, user, comment, posted) VALUES ('".$vid."', '".$user."', '".db_escape_string($comment)."', ".time().")";

	dbQuery($query, false);

		
}

function removeComment($cm_id){
	if ( !is_array($cm_id) ) {
	$query = "DELETE FROM `".DB_PREFIX."video_comments` WHERE `id` = {$cm_id}";
	} else {	
		$query = "DELETE FROM `".DB_PREFIX."video_comments` WHERE `id` IN (".implode(", ", $cm_id).")";
	}
	dbQuery($query, false);
}

function removeAllComments($vid){
	$query = "DELETE FROM `".DB_PREFIX."video_comments` WHERE `vid` = '{$vid}'";
	dbQuery($query, false);
}


function getComments($vid, $perpage = 4) {
	$avatars	= get_avatar_files();
	
   	

	// how many rows to show per page
	$rowsPerPage = $perpage;

	// by default we show first page
	$pageNum = 1;

	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}

	// counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;

	if($vid == "all") {

		$query = "SELECT COUNT(`vid`) AS `total` FROM `".DB_PREFIX."video_comments`
			ORDER BY posted DESC";
    }else{

		$query = "SELECT COUNT(`vid`) AS `total` FROM `".DB_PREFIX."video_comments` 
			WHERE vid = '$vid' ORDER BY posted DESC";
    }
	// how many rows we have in database
	$result = dbQuery($query, false);
	$numrows = 0;
	if ( mysql_num_rows($result) > 0 ) {
		list($numrows) = mysql_fetch_row($result);
	}
	
	if($vid == "all") {

		$query = "SELECT * FROM `".DB_PREFIX."video_comments`
			ORDER BY posted DESC";
    }else{

		$query = "SELECT * FROM `".DB_PREFIX."video_comments`
			WHERE vid = '$vid' ORDER BY posted DESC";
    }

	$query  = $query." LIMIT $offset, $rowsPerPage";

	$result = dbQuery($query, false);




	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);



	// creating 'previous' and 'next' link
	// plus 'first page' and 'last page' link

	// print 'previous' link only if we're not
	// on page one
	if ($pageNum > 1)
	{

		$page = $pageNum - 1;
		$prev .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/comments_local.php?vid='.$vid.'&page=\', \'local_comments\', \''.$page.'\', \'blk-comment-local-paging\');"> ['.lang('prev').']</a> ';
		$first = ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/comments_local.php?vid='.$vid.'&page=\', \'local_comments\', \'1\', \'blk-comment-local-paging\');"> ['.lang('first').']</a> | ';
	} 
	else
	{
		$prev  = '';       // we're on page one, don't enable 'previous' link
		$first = ''; // nor 'first page' link
	}
	
	// print 'next' link only if we're not
	// on the last page
	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/comments_local.php?vid='.$vid.'&page=\', \'local_comments\', \''.$page.'\', \'blk-comment-local-paging\');"> ['.lang('next').']</a> ';
		$last .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/comments_local.php?vid='.$vid.'&page=\', \'local_comments\', \''.$maxPage.'\', \'blk-comment-local-paging\');"> ['.lang('last').']</a> ';
	} 
	else
	{
		$next = '';      // we're on the last page, don't enable 'next' link
		$last = ''; // nor 'last page' link
	}

	if ($maxPage!= 0) 

		$pagination = $first . $prev . " ".lang('showing_page')." <strong>$pageNum</strong> ".lang('of')." <strong>$maxPage</strong> ".lang('pages')." " . $next . $last;
	else
		$pagination = lang('nothing_found');


	$comments = array();
	$temp_avatars					= $avatars;

	$i = 0;
	while (list ($comment_id, $comment_vid, $comment_user, $comment_body, $comment_posted) = @mysql_fetch_row($result))  {


		$comments[$i]["id"] = $comment_id;
		$comments[$i]["vid"] = $comment_vid;
		$comments[$i]["user"] = $comment_user;
		$comments[$i]["comment"] = $comment_body;
		$comments[$i]["posted"] = $comment_posted;
		$comments[$i]["avatar"]	= "";
		
		if ( count($temp_avatars) > 0 ) {
			list($temp_avatars, $comments[$i]["avatar"]) = randomize_avatars($temp_avatars);
		} else {
			$temp_avatars					= $avatars;
			list($temp_avatars, $comments[$i]["avatar"]) = randomize_avatars($temp_avatars);
		}
	
		$vid_title	= stripslashes($vid_title);
		if ( is_null($vid_title) ){
			$vid_title	= 'watch';
		}
		//$comments[$i]['title']	= $vid_title;

		$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
		if ( $is_logged ){
			$comments[$i]["remove"] = '<a href="javascript:ajaxGet(\'ajax/comments_local.php?vid='.$vid.'&page='.$pageNum.'&rmcm=\', \'local_comments\', \''.$comment_id.'\', false);">Delete As Admin</a>';
		} else {
			$comments[$i]["remove"] = '';
		}
		$i++;

	}


	$comments["total"] = $i;
	$comments["pagination"] = $pagination;

	return $comments;

}

function getCommentsVideoID($perpage = 4) {


   
	$query = "SELECT DISTINCT(vid) FROM `".DB_PREFIX."video_comments`
		ORDER BY posted DESC";
   

	// how many rows to show per page
	$rowsPerPage = $perpage;

	// by default we show first page
	$pageNum = 1;

	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}

	// counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;


	// how many rows we have in database
	$result = dbQuery($query, false);
	$numrows = 0;
	while ($row = @mysql_fetch_row($result)) {
		$numrows++;
	}

	$query  = $query." LIMIT $offset, $rowsPerPage";

	$result = dbQuery($query, false);





	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);



	// creating 'previous' and 'next' link
	// plus 'first page' and 'last page' link

	// print 'previous' link only if we're not
	// on page one
	if ($pageNum > 1)
	{

		$page = $pageNum - 1;
		$prev .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/comments_video.php?page=\', \'local_comments2\', \''.$page.'\', \'blk_video_paging\');"> [Prev]</a> ';
		$first = ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/comments_video.php?page=\', \'local_comments2\', \'1\', \'blk_video_paging\');"> [First]</a> | ';
	} 
	else
	{
		$prev  = '';       // we're on page one, don't enable 'previous' link
		$first = ''; // nor 'first page' link
	}
	
	// print 'next' link only if we're not
	// on the last page
	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/comments_video.php?page=\', \'local_comments2\', \''.$page.'\', \'blk_video_paging\');"> [Next]</a> ';
		$last .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/comments_video.php?page=\', \'local_comments2\', \''.$maxPage.'\', \'blk_video_paging\');"> [Last]</a> ';
	} 
	else
	{
		$next = '';      // we're on the last page, don't enable 'next' link
		$last = ''; // nor 'last page' link
	}

	if ($maxPage!= 0) 

		$pagination = $first . $prev . " Showing page <strong>$pageNum</strong> of <strong>$maxPage</strong> pages " . $next . $last;
	else
		$pagination = "Nothing Found!";


	$comments = array();


	$i = 0;
	while (list ($comment_vid) = @mysql_fetch_row($result))  {



		$comments[$i]["vid"] = $comment_vid;
		//$vid_title	= stripslashes($vid_title);
		if ( is_null($vid_title) ){
			$vid_title	= 'watch';
		}
		//$comments[$i]['title']	= $vid_title;
	
		
		$i++;

	}


	$comments["total"] = $i;
	$comments["pagination"] = $pagination;

	return $comments;

}

?>