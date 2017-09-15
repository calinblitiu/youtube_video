<?php
$is_frontend = false;
include_once "../init.php";

if (empty($_GET['p'])) {
	$_GET['p'] = 1;
}

$data_videos = YT_ListByRelated($_GET['vid'], $_GET['p']);
$total_videos	= YT_Total_Videos($data_videos);
$videos			= YT_Videos($data_videos, '', 8);

if((($_GET['p']) * $config['list_per_page']) < $total_videos) {
	$next_page = $_GET['p'] + 1;
}

if($_GET['p']>1) {
	$prev_page = $_GET['p'] - 1;
}

// ################# Start assign template variables ##########################
$tpl->assign('id',$_GET['vid']);
$tpl->assign('videos', $videos);
$tpl->assign('total', ceil($total_videos / $config['list_per_page']));
$tpl->assign('next_page',$next_page);
$tpl->assign('prev_page',$prev_page);
$tpl->assign('curr_page',$_GET['p']);
$tpl->assign('lang_related_videos', lang('related_videos'));
$tpl->assign('relatedVideosPosition',$config["related_videos_position"]);
$tpl->display('ajax/related.html');
?>