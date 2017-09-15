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

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	die();
}

if(isset($_GET["rmcm"])) {
	$rmCm = $_GET["rmcm"];
	removeComment($rmCm);
}

if(isset($_GET["rmcms"])) {
	$rmCms = $_GET["rmcms"];
	foreach ($rmCms as $rmCm) {
		if ( is_numeric($rmCm) ) {
			removeComment($rmCm);
		}
	}
}

$page		= isset($_GET['page']) ? $_GET['page'] : '1';
$comments = getCommentsVideoID(10);
?>
<table width="100%" class="tablesorter">
<thead>
<tr><th><b>Thumbnail</b></th><th><b>Video ID</b></th><th><b>Manage</b></th></tr>
</thead>
<tbody>


<?
for ($i = 0 ; $i < $comments["total"] ; $i++)  {
	//$id = $comments[$i]["id"];
	$vid = $comments[$i]["vid"];
	///$user = $comments[$i]["user"];
	//$comment= $comments[$i]["comment"];
	//$posted= date('Y-m-d / H:s', $comments[$i]["posted"]);
	$cls_row = '';
	if ( ($i % 2 ) != 0 ) {
		$cls_row = ' class="odd"';
	}
	
	//echo "<tr><td><a href=\"".$config["website_url"]."video/$vid/watch.html\" target=\"_blank\"><img src=\"http://img.youtube.com/vi/$vid/1.jpg\" width=\"60\" border=\"0\"></a></td><td><a href=\"javascript:ajaxGet('ajax/comments_local.php?vid=$vid&page=1&rmcm=', 'local_comments', '$id', false);\">Delete</a></td></tr>"; 
	echo "<tr{$cls_row}><td><a href=\"".$config["website_url"]."video/{$vid}/".SeoKeywordEncode($comments[$i]["title"]).".html\" target=\"_blank\"><img src=\"http://img.youtube.com/vi/{$vid}/1.jpg\" width=\"60\" border=\"0\"></a></td><td>{$vid}</td><td><a href=\"javascript:ajaxGet('ajax/comments_local.php?vid=$vid&page=1', 'local_comments', false, false);\">Manage Comments for this video</a></td></tr>"; 
}
?>
</tbody>
</table>
<br>
<?=$comments["pagination"]?>
&nbsp;<span id="blk_video_paging"></span>
