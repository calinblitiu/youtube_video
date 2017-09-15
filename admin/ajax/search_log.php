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
		$sqlQuery	= "DELETE FROM `".DB_PREFIX."search_log`";
		dbQuery($sqlQuery);
		
		echo '<div class="configSaved">Search Log has been deleted.</div>';
		exit(0);
	} else {
		echo '<div class="error">Search Log is not removed. (Demo Mode)</div>';
		exit(0);
	}
}


function GetSearchLog($perpage = 4) {
   	$query = "SELECT * FROM `".DB_PREFIX."search_log` ORDER BY `time` DESC";
    
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
		$prev .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/search_log.php?page=\', \'block_search_log\', \''.$page.'\', \'block-search-log-page\');"> [Prev]</a> ';
		$first = ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/search_log.php?page=\', \'block_search_log\', \'1\', \'block-search-log-page\');"> [First]</a> | ';
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
		$next .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/search_log.php?page=\', \'block_search_log\', \''.$page.'\', \'block-search-log-page\');"> [Next]</a> ';
		$last .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/search_log.php?page=\', \'block_search_log\', \''.$maxPage.'\', \'block-search-log-page\');"> [Last]</a> ';
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
	while (list ($log_id, $search_term, $ip_address, $time) = @mysql_fetch_row($result))  {


		$comments[$i]["id"] 			= $log_id;
		$comments[$i]["search_term"] 	= stripslashes($search_term);
		$comments[$i]["ip_address"] 	= $ip_address;
		$comments[$i]["time"] 			= $time;

		$i++;

	}


	$comments["total"] = $i;
	$comments["pagination"] = $pagination;

	return $comments;

}


$comments = GetSearchLog(10);
if ( $comments["total"] > 0 ) {
?>
<table width="100%" class="tablesorter">
<thead>

<tr>
	<th width="20%"><b>Search Term</b></th>
	
	<th width="30%"><b>Time</b></th>
</tr>
</thead>
<tbody>

<?
$page = $_GET["page"];
for ($i = 0 ; $i < $comments["total"] ; $i++)  {
	$id = $comments[$i]["id"];
	$search_term = $comments[$i]["search_term"];
	
	$posted= date('Y-m-d / H:s', $comments[$i]["time"]);
	
	$cls_row = ' class="even"';
	if ( ($i % 2 ) != 0 ) {
		$cls_row = ' class="odd"';
	}
	
	echo "<tr{$cls_row}><td><div style='width:400px; overflow:hidden;'>$search_term</div></td><td align=\"center\"><small>$posted</small></td></tr>"; 
}
?>

<tr>
<td colspan="2">
<input type="button" name="btnDeleteSearchLog" id="btnDeleteSearchLog" value="Delete All" onclick="javascript:ajaxGet('ajax/search_log.php?delete=', 'block_search_log', '1', 'blk-delete-log');"  />
&nbsp;<span id="blk-delete-log"></span>
</td>
</tr>

</tbody>
</table>

<? } ?>
<br>
<?=$comments["pagination"]?>
<span id="block-search-log-page"></span>
