<?php
// рус
$is_frontend = true;
include_once "init.php";

$config['list_per_page'] = $config['list_on_home_page'];
include_once("config/admin_login.php");
include_once("lib/htmLawed.php");

$feed_id 	= isset($_GET['fid']) ? $_GET['fid'] : '';
$main_menu	= main_menu($feed_id);

$name		= "";
$email		= "";
$message	= "";
$code		= "";
$subject	= "";

$hasError		= false;
$err_name		= "";
$err_email		= "";
$err_code		= "";
$err_subject	= "";
$err_message	= "";
$success_msg	= "";
$domain_name	= get_domain_name();

if ( isset($_SESSION['success_msg']) ) {
	$success_msg = $_SESSION['success_msg'];
	unset($_SESSION['success_msg']);
}

if ( isset($_POST['btnSubmit']) ) {
	$name			= isset($_POST['name']) ? trim($_POST['name']) : '';
	$email			= isset($_POST['email']) ? trim($_POST['email']) : '';
	$message		= isset($_POST['message']) ? trim($_POST['message']) : '';
	$code			= isset($_POST['code']) ? trim($_POST['code']) : '';
	$subject		= isset($_POST['subject']) ? trim($_POST['subject']) : '';

	if ( get_magic_quotes_gpc() ) {
		$name		= stripslashes($name);
		$email		= stripslashes($email);
		$message	= stripslashes($message);
		$code		= stripslashes($code);
		$subject	= stripslashes($subject);
	}

	if ( $name == '' ) {
		$hasError	= true;
		$err_name	= lang('err_your_name');
	}
	if ( $message == '' ) {
		$hasError	= true;
		$err_message= lang('err_your_message');
	}
	if ( $subject == '' ) {
		$hasError	= true;
		$err_subject= lang('err_your_subject');
	}

	if ( $email == '' ) {
		$hasError	= true;
		$err_email	= lang('err_your_email');
	} else if ( !is_valid_email($email) ) {
		$hasError	= true;
		$err_email	= lang('err_valid_email');
	}

	if ( $code == '' ) {
		$hasError	= true;
		$err_code	= lang('err_code');
	} else if ( md5($code) != $_SESSION['__img_code__'] ) {
		$hasError	= true;
		$err_code	= lang('err_valid_code');
	}

	if ( !$hasError ) {
		$name 			= htmLawed($name, array('safe'=>1));
		$subject		= htmLawed($subject, array('safe'=>1));
		$message		= htmLawed($message, array('safe'=>1));
		$email 			= htmLawed($email, array('safe'=>1));
		$_SESSION['success_msg'] = lang('your_message_has_been_sent_to_administrator');
		$txt_message	= lang('name').": ".$name."			\r\n".lang('email').": ".$email."			\r\n".lang('message').": ".$message."			\r\nIP Address: ".$_SERVER['REMOTE_ADDR'];

		$headers = 'From: do-not-reply@'.$domain_name . "\r\n" .
    'Reply-To: do-not-reply@'.$domain_name . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
		mail($config['admin_email'], $subject, $txt_message, $headers);

		header("Location: contact-us.html");
		exit(0);
	}
}
$tpl->assign('lang_logout', lang('logout', 'Logout') );
$tpl->assign('lang_admin_cp', lang('admin_cp', 'Admin CP') );
$tpl->assign('lang_add_to_favorites', lang('add_to_favorites', 'Add To Favorites') );
$tpl->assign('lang_online_watchers', lang('online_watchers', 'Online Watchers') );
$tpl->assign('lang_categories', lang('categories', 'Categories') );
$tpl->assign('lang_top_rated', lang('top_rated', 'Top Rated') );
$tpl->assign('lang_top_favorites', lang('top_favorites', 'Top Favorites') );
$tpl->assign('lang_most_viewed', lang('most_viewed', 'Most Viewed') );
$tpl->assign('lang_most_recent', lang('most_recent', 'Most Recent') );
$tpl->assign('lang_most_discussed', lang('most_discussed', 'Most Discussed') );
$tpl->assign('lang_most_linked', lang('most_linked', 'Most Linked') );
$tpl->assign('lang_most_responded', lang('most_responded', 'Most Responded') );
$tpl->assign('lang_recently_featured', lang('recently_featured', 'Recently Featured') );
$tpl->assign('lang_upload', lang('upload', 'Upload') );
$tpl->assign('lang_get_flash_player', lang('get_flash_player', 'Get the latest Flash Player') );
$tpl->assign('lang_to_see_video', lang('to_see_video', 'to see this video.') );
$tpl->assign('lang_all_rights_reserved', lang('all_rights_reserved', 'All Rights Reserved') );
$tpl->assign('lang_display', lang('display', 'Display') );
$tpl->assign('lang_list_view', lang('list_view', 'List View') );
$tpl->assign('lang_grid_view', lang('grid_view', 'Grid View') );
$tpl->assign('lang_view_count', lang('view_count', 'View Count') );
$tpl->assign('lang_relevance', lang('relevance', 'Relevance') );
$tpl->assign('lang_updated', lang('updated', 'Updated') );
$tpl->assign('lang_rating', lang('rating', 'Rating') );
$tpl->assign('lang_order_by', lang('order_by', 'Order By') );
$tpl->assign('lang_time', lang('time', 'Time') );
$tpl->assign('lang_today', lang('today', 'Today') );
$tpl->assign('lang_this_week', lang('this_week', 'This Week') );
$tpl->assign('lang_this_month', lang('this_month', 'This Month') );
$tpl->assign('lang_all_time', lang('all_time', 'All Time') );
$tpl->assign('lang_video_tags', lang('video_tags', 'Video Tags') );
$tpl->assign('lang_links', lang('links', 'Links') );
$tpl->assign('lang_download_video', lang('download_video', 'Download Video') );
$tpl->assign('lang_video_details', lang('video_details', 'Video Details') );
$tpl->assign('main_menu', $main_menu );
$tpl->assign('lang_post_a_comment', lang('post_a_comment', 'Post a comment'));
$tpl->assign('lang_local_comments', lang('local_comments', 'Local Comments'));
$tpl->assign('lang_youtube_comments', lang('youtube_comments', 'Youtube Comments'));
$tpl->assign('lang_says', lang('says', 'Says'));
$tpl->assign('lang_guest_name', lang('guest_name', 'Guest Name'));
$tpl->assign('lang_comment', lang('comment', 'Comment'));
$tpl->assign('lang_submit', lang('submit', 'Submit'));
$tpl->assign('lang_name', lang('name', 'Name'));
$tpl->assign('lang_email', lang('email', 'Email'));
$tpl->assign('lang_subject', lang('subject', 'Subject'));
$tpl->assign('lang_message', lang('message', 'Message'));
$tpl->assign('lang_err_name', $err_name);
$tpl->assign('lang_err_subject', $err_subject);
$tpl->assign('lang_err_code', $err_code);
$tpl->assign('lang_err_message', $err_message);
$tpl->assign('lang_err_email', $err_email);
$tpl->assign('lang_contact_us', lang('contact_us', 'Contact Us') );
$tpl->assign('name', $name);
$tpl->assign('email', $email);
$tpl->assign('subject', $subject);
$tpl->assign('message', $message);
$tpl->assign('success_msg', $success_msg);

$tpl->display('contact_us.html');
include "footer.php";
?>