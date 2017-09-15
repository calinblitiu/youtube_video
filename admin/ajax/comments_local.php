<?
@session_start();
include('../../config/db_config.php');
include('../../config/admin_login.php');
include_once('../../lib/db.php');
include('../../config/license_key.php');
include('../../config/config.php');

include('../inc/version.php');
include('../inc/functions.php');
include('../../lib/modules.php');
include('../../lib/services.php');

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	//die();
	echo '<div class="error">It seems like your session has already expired. Please login again</div><ul class="nice_btn_blue" style="padding-left: 420px;">
          <li><a class="current" href="'.$config['website_url'].'admin"><span> OK </span></a></li>
        </ul>';
		exit(0);
}

$vid		= isset($_GET['vid']) ? $_GET['vid'] : 'all';
if(isset($_GET["rmcm"])) {
	$rmCm = $_GET["rmcm"];
	removeComment($rmCm);
	
	echo '<div class="configSaved">Comment for video '.$vid.' has been removed.</div>';
}

if(isset($_GET["rmcms"])) {
	removeAllComments($vid);
	echo '<div class="configSaved">All comments for video '.$vid.' has been removed.</div>';
	exit(0);
}


$comments = getComments($vid, 10);
if ( $comments["total"] > 0 ) {
?>
<table width="100%" class="tablesorter">
<thead>
<tr><th colspan="6" style="text-align:left;">Video ID: <b><?=$vid;?></b></th></tr>
<tr>
	<th width="15%"><b>Comment ID</b></th>
	<th width="20%"><b>Guest</b></th>
	<th width="50%"><b>Comment</b></th>
	<th width="30%"><b>Posted</b></th>
	<th><b>Action</b></th>
</tr>
</thead>
<tbody>

<?
$page = $_GET["page"];
for ($i = 0 ; $i < $comments["total"] ; $i++)  {
	$id = $comments[$i]["id"];
	$vid = $comments[$i]["vid"];
	$user = $comments[$i]["user"];
	$comment= $comments[$i]["comment"];
	$posted= date('Y-m-d / H:s', $comments[$i]["posted"]);
	
	$cls_row = '';
	if ( ($i % 2 ) != 0 ) {
		$cls_row = ' class="odd"';
	}
	
	echo "<tr{$cls_row}><td>$id</td><td>$user</td><td><div style='width:400px; overflow:hidden;'>$comment</div></td><td><small>$posted</small></td><td><a href=\"javascript:ajaxGet('ajax/comments_local.php?vid=$vid&page=$page&rmcm=', 'local_comments', '$id', false);\">Delete</a></td></tr>"; 
}
?>
<tr><td colspan="6"><input type="button" name="btnDelete" onclick="DeleteAllVideoComments('<?=$vid?>');" value="Delete All" />
<div id="block-loading-remove-all-comments"></div>
</td></tr>
</tbody>
</table>
<? } ?>
<br>
&nbsp;<span id="blk-comment-local-paging"></span>
