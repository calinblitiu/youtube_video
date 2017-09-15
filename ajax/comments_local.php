<?php
$is_frontend = false;
include_once "../init.php";

if(isset($_GET["rmcm"])) {

	$rmCm = $_GET["rmcm"];
	removeComment($rmCm);

}

$video_comment = YT_GetComment($_GET['vid']);
$local_video_comment = getComments($_GET['vid'],$config["local_comments_per_page"]);

$totalYoutubeComments = $video_comment[0]['opensearch:totalresults'];
$totalLocalComments =  $local_video_comment["total"];
$totalComments = $totalYoutubeComments + $totalLocalComments;


$tpl->assign('comments',$video_comment[0][entry]);
$tpl->assign('local_comments',$local_video_comment);

$tpl->assign('totalYoutubeComments',$totalYoutubeComments);
$tpl->assign('totalLocalComments',$totalLocalComments);
$tpl->assign('totalComments',$totalComments);

$tpl->display('ajax/comments_local.html');

?>