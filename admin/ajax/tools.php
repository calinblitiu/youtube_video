<?php
@define("DS", DIRECTORY_SEPARATOR);
@session_start();
@set_time_limit(0);// error suppressed bcoz this is disabled on some hosts, filling up error logs at times

include('../../config/db_config.php');
include('../../config/admin_login.php');
include_once('../../lib/db.php');
include('../../config/license_key.php');
include('../../config/config.php');
include('../../config/config_filter.php');

include('../inc/aconfig.php');
include('../inc/version.php');
include('../inc/functions.php');
include('../../lib/modules.php');

$task			= isset($_GET['task']) ? $_GET['task'] : '';
$task			= isset($_POST['task']) ? $_POST['task'] : $task;

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	if ( $task == 'remove_html_cache' || $task == 'remove_tags' || $task == 'delete_keyword' || $task == 'keyword_matches' ) {
		echo '<div class="error">It seems like your session has already expired. Please login again</div><ul class="nice_btn_blue" style="padding-left: 420px;">
          <li><a class="current" href="'.$config['website_url'].'admin"><span> OK </span></a></li>
        </ul>';
		exit(0);
	} else if ( $task == 'new_keyword' || $task == 'add_new_keyword' ) {
		echo '0|<div class="error">It seems like your session has already expired. Please login again</div><ul class="nice_btn_blue" style="padding-left: 200px;">
          <li><a class="current" href="'.$config['website_url'].'admin"><span> OK </span></a></li>
        </ul>';
		exit(0);
	} else {
		//echo "0|Your login is expired.";
		echo '0|<ul class="nice_btn_blue">
          <li><a class="current" href="'.$config['website_url'].'admin"><span> OK </span></a></li>
        </ul>';
		exit(0);
	}
}

$num			= isset($_GET['num']) ? $_GET['num'] : '';
$new_keywords	= isset($_GET['new_keywords']) ? $_GET['new_keywords'] : '';
$new_keywords	= isset($_POST['new_keywords']) ? $_POST['new_keywords'] : $new_keywords;

$chk_contribute	= isset($_GET['chk_contribute']) ? $_GET['chk_contribute'] : 0;
$chk_contribute	= isset($_POST['chk_contribute']) ? $_POST['chk_contribute'] : $chk_contribute;

$id		= isset($_GET['id']) ? $_GET['id'] : 0;
$id		= isset($_POST['id']) ? $_POST['id'] : $id;

$page			= isset($_GET['page']) ? $_GET['page'] : 1;

if ( $task == 'add_tag_to_filter' ) {
	$tag		= isset($_GET['tag']) ? urldecode($_GET['tag']) : '';
	
	$msg_tag	= '<div class="configSaved">Tag '.$tag.' has been added to keyword filter.</div>';
	if ( !isDemo() ) {
		add_new_keyword_filter($tag);
	} else {
		$msg_tag	= '<div class="error">Tag '.$tag.' is not added to keyword filter. (Demo Mode)</div>';
	}
	
	$sqlQuery	= "SELECT COUNT(*) AS `total` FROM `".DB_PREFIX."video_tags`";
	$sqlResult	= dbQuery($sqlQuery);
	
	$data_match	= mysql_fetch_array($sqlResult);
	$total		= 0;
	if ( isset($data_match['total']) ) {
		$total	= $data_match['total'];
	}
	
	if ( $total > 0 ) {
		$pagination		= "";
	
		$rows_per_page	= 10;
		$offset 		= ($page - 1) * $rows_per_page;
		$max_page		= ceil($total/$rows_per_page);
		
		if ( $page > $max_page ) {
			$page	= $max_page;
			$offset 		= ($page - 1) * $rows_per_page;
		}
		
		$current_page	= $page;
		
		$str_filter		= <<<__CONTENT__
		{$msg_tag}
			<table width="100%" class="tablesorter">
		<thead>
		<tr>
			<th><b>Tag</b></th>
			<th><b>Qty</b></th>
			<th><b>Action</b></th>
		</tr>
		</thead>
		<tbody>
__CONTENT__;

		$sqlQuery		= "SELECT `tag`, `quantity` FROM `".DB_PREFIX."video_tags` ORDER BY `quantity` DESC LIMIT {$offset}, {$rows_per_page}";
		$sqlResult2		= dbQuery($sqlQuery, false);
		$cls_row		= 'even';
		while($row = mysql_fetch_array($sqlResult2) ) {
			$str_filter .= "<tr class=\"".$cls_row."\"><td>".stripslashes($row['tag'])."</td>
			<td align=\"right\">".stripslashes($row['quantity'])."</td>
			<td align=\"center\">
			<a href=\"javascript:ajaxGet('ajax/tools.php?task=delete_tag&tag=".urlencode(stripslashes($row['tag']))."&page=','block_browse_tags','".$page."','block-loading-browse-tags');\">Delete</a>
			&nbsp;&nbsp;
			<a href=\"javascript:ajaxGet('ajax/tools.php?task=add_tag_to_filter&tag=".urlencode(stripslashes($row['tag']))."&page=','block_browse_tags','".$page."','block-loading-browse-tags');\">Add to keyword filter</a>
			</td></tr>";
			if ( $cls_row == 'even' ) {
				$cls_row		= 'odd';
			} else {
				$cls_row		= 'even';
			}
		}
		
		$str_filter .= "</tbody></table>";
		
		if ($page > 1)
		{
			//	$page = $page - 1;
			$prev_page = $current_page - 1;
			$prev .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \''.$prev_page.'\', \'blk-tags-paging\');"> [Prev]</a> ';
			$first = ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \'1\', \'blk-tags-paging\');"> [First]</a> | ';
		} 
		else
		{
			$prev  = '';       // we're on page one, don't enable 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($page < $max_page)
		{
			//$page = $page + 1;
			$next_page = $current_page +1;
			$next .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \''.$next_page.'\', \'blk-tags-paging\');"> [Next]</a> ';
			$last .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \''.$max_page.'\', \'blk-tags-paging\');"> [Last]</a> ';
		} 
		else
		{
			$next = '';      // we're on the last page, don't enable 'next' link
			$last = ''; // nor 'last page' link
		}

		if ($max_page!= 0) 
			$pagination = $first . $prev . " Showing Page <strong>{$current_page}</strong> of <strong>{$max_page}</strong> pages " . $next . $last;
		else
			$pagination = 'Nothing Found';
		
		$str_filter .= "<div id=\"blk-tags-paging\">{$pagination}</div>";
		echo $str_filter;
	} else {
		echo 'Nothing Found';
	}
	
} else if ( $task == 'delete_tag' ) {
	$tag		= isset($_GET['tag']) ? urldecode($_GET['tag']) : '';
	
	$msg_tag	= '<div class="configSaved">Tag '.$tag.' has been removed.</div>';
	if ( !isDemo() ) {
		if ( $tag != '' ) {
			$sqlQuery	= "DELETE FROM `".DB_PREFIX."video_tags` WHERE LCASE(`tag`) = '".strtolower($tag)."'";
			dbQuery($sqlQuery);
			
			$tags_file	= '../../'.$config["html_cache_dir"]."tags.html";
			
			if ( file_exists($tags_file) ) {
				unlink($tags_file);
			} 
		}
	} else {
		$msg_tag	= '<div class="error">Tag '.$tag.' is not removed. (Demo Mode)</div>';
	}
	
	$sqlQuery	= "SELECT COUNT(*) AS `total` FROM `".DB_PREFIX."video_tags`";
	$sqlResult	= dbQuery($sqlQuery);
	
	$data_match	= mysql_fetch_array($sqlResult);
	$total		= 0;
	if ( isset($data_match['total']) ) {
		$total	= $data_match['total'];
	}
	
	if ( $total > 0 ) {
	
		$pagination		= "";
	
		$rows_per_page	= 10;
		$offset 		= ($page - 1) * $rows_per_page;
		$max_page		= ceil($total/$rows_per_page);
		
		if ( $page > $max_page ) {
			$page	= $max_page;
			$offset 		= ($page - 1) * $rows_per_page;
		}
		
		$current_page	= $page;
		
		$str_filter		= <<<__CONTENT__
		{$msg_tag}
			<table width="100%" class="tablesorter">
		<thead>
		<tr>
			<th><b>Tag</b></th>
			<th><b>Qty</b></th>
			<th><b>Action</b></th>
		</tr>
		</thead>
		<tbody>
__CONTENT__;

		$sqlQuery		= "SELECT `tag`, `quantity` FROM `".DB_PREFIX."video_tags` ORDER BY `quantity` DESC LIMIT {$offset}, {$rows_per_page}";
		$sqlResult2		= dbQuery($sqlQuery, false);
		$cls_row		= 'even';
		while($row = mysql_fetch_array($sqlResult2) ) {
			$str_filter .= "<tr class=\"".$cls_row."\"><td>".stripslashes($row['tag'])."</td>
			<td align=\"right\">".stripslashes($row['quantity'])."</td>
			<td align=\"center\">
			<a href=\"javascript:ajaxGet('ajax/tools.php?task=delete_tag&tag=".urlencode(stripslashes($row['tag']))."&page=','block_browse_tags','".$page."','block-loading-browse-tags');\">Delete</a>
			&nbsp;&nbsp;
			<a href=\"javascript:ajaxGet('ajax/tools.php?task=add_tag_to_filter&tag=".urlencode(stripslashes($row['tag']))."&page=','block_browse_tags','".$page."','block-loading-browse-tags');\">Add to keyword filter</a>
			</td></tr>";
			if ( $cls_row == 'even' ) {
				$cls_row		= 'odd';
			} else {
				$cls_row		= 'even';
			}
		}
		
		$str_filter .= "</tbody></table>";
		
		if ($page > 1)
		{
			//	$page = $page - 1;
			$prev_page = $current_page - 1;
			$prev .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \''.$prev_page.'\', \'blk-tags-paging\');"> [Prev]</a> ';
			$first = ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\',\'block_browse_tags\', \'1\', \'blk-tags-paging\');"> [First]</a> | ';
		} 
		else
		{
			$prev  = '';       // we're on page one, don't enable 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($page < $max_page)
		{
			//$page = $page + 1;
			$next_page = $current_page +1;
			$next .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\',\'block_browse_tags\',\''.$next_page.'\', \'blk-tags-paging\');"> [Next]</a> ';
			$last .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\',\'block_browse_tags\',\''.$max_page.'\', \'blk-tags-paging\');"> [Last]</a> ';
		} 
		else
		{
			$next = '';      // we're on the last page, don't enable 'next' link
			$last = ''; // nor 'last page' link
		}

		if ($max_page!= 0) 
			$pagination = $first . $prev . " Showing Page <strong>{$current_page}</strong> of <strong>{$max_page}</strong> pages " . $next . $last;
		else
			$pagination = 'Nothing Found';
		
		$str_filter .= "<div id=\"blk-tags-paging\">{$pagination}</div>";
		echo $str_filter;
	} else {
		echo 'Nothing Found';
	}
	
} else if ( $task == 'browse_tags' ) {
	
	$sqlQuery	= "SELECT COUNT(*) AS `total` FROM `".DB_PREFIX."video_tags`";
	$sqlResult	= dbQuery($sqlQuery);
	
	$data_match	= mysql_fetch_array($sqlResult);
	$total		= 0;
	if ( isset($data_match['total']) ) {
		$total	= $data_match['total'];
	}
	
	if ( $total > 0 ) {
	
		$pagination		= "";
		
		$rows_per_page	= 10;
		$offset 		= ($page - 1) * $rows_per_page;
		$max_page		= ceil($total/$rows_per_page);
		$current_page	= $page;
		
		$str_filter		= <<<__CONTENT__
		<table width="100%" class="tablesorter">
		<thead>
		<tr>
			<th><b>Tag</b></th>
			<th><b>Qty</b></th>
			<th><b>Action</b></th>
		</tr>
		</thead>
		<tbody>
__CONTENT__;

		$sqlQuery		= "SELECT `tag`, `quantity` FROM `".DB_PREFIX."video_tags` ORDER BY `quantity` DESC LIMIT {$offset}, {$rows_per_page}";
		$sqlResult2		= dbQuery($sqlQuery, false);
		$cls_row		= 'even';
		while($row = mysql_fetch_array($sqlResult2) ) {
			$str_filter .= "<tr class=\"".$cls_row."\"><td>".stripslashes($row['tag'])."</td>
			<td align=\"right\">".stripslashes($row['quantity'])."</td>
			<td align=\"center\"><a href=\"javascript:ajaxGet('ajax/tools.php?task=delete_tag&tag=".urlencode(stripslashes($row['tag']))."&page=','block_browse_tags','".$page."','block-loading-browse-tags');\">Delete</a>
			&nbsp;&nbsp;
			<a href=\"javascript:ajaxGet('ajax/tools.php?task=add_tag_to_filter&tag=".urlencode(stripslashes($row['tag']))."&page=','block_browse_tags', '".$page."','block-loading-browse-tags');\">Add to keyword filter</a>
			
			</td></tr>";
			if ( $cls_row == 'even' ) {
				$cls_row		= 'odd';
			} else {
				$cls_row		= 'even';
			}
		}
		
		$str_filter .= "</tbody></table>";
		
		if ($page > 1)
		{
			//	$page = $page - 1;
			$prev_page = $current_page - 1;
			$prev .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \''.$prev_page.'\', \'blk-tags-paging\');"> [Prev]</a> ';
			$first = ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \'1\', \'blk-tags-paging\');"> [First]</a> | ';
		} 
		else
		{
			$prev  = '';       // we're on page one, don't enable 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($page < $max_page)
		{
			//$page = $page + 1;
			$next_page = $current_page +1;
			$next .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \''.$next_page.'\', \'blk-tags-paging\');"> [Next]</a> ';
			$last .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=browse_tags&page=\', \'block_browse_tags\', \''.$max_page.'\', \'blk-tags-paging\');"> [Last]</a> ';
		} 
		else
		{
			$next = '';      // we're on the last page, don't enable 'next' link
			$last = ''; // nor 'last page' link
		}

		if ($max_page!= 0) 
			$pagination = $first . $prev . " Showing Page <strong>{$current_page}</strong> of <strong>{$max_page}</strong> pages " . $next . $last;
		else
			$pagination = 'Nothing Found';
		
		$str_filter .= "<div id=\"blk-tags-paging\">{$pagination}</div>";
		echo $str_filter;
	} else {
		echo 'Nothing Found';
	}
	
} else if ( $task == 'keyword_matches' ) {
	$sqlQuery	= "SELECT COUNT(*) AS `total` FROM `".DB_PREFIX."filter_match`";
	$sqlResult	= dbQuery($sqlQuery);
	
	$data_match	= mysql_fetch_array($sqlResult);
	$total		= 0;
	if ( isset($data_match['total']) ) {
		$total	= $data_match['total'];
	}
	
	if ( $total > 0 ) {
	
		$pagination		= "";
	
		$rows_per_page	= 10;
		$offset 		= ($page - 1) * $rows_per_page;
		$max_page		= ceil($total/$rows_per_page);
		$current_page	= $page;
		
		$str_filter		= <<<__CONTENT__
			<table width="100%" class="tablesorter">
		<thead>
		
		<tr>
			<th><b>Keyword</b></th>
			<th><b>URL</b></th>
		</tr>
		</thead>
		<tbody>
__CONTENT__;

		$sqlQuery		= "SELECT `term`, `url` FROM `".DB_PREFIX."filter_match` ORDER BY `id` DESC LIMIT {$offset}, {$rows_per_page}";
		$sqlResult2		= dbQuery($sqlQuery, false);
		$cls_row		= 'even';
		while($row = mysql_fetch_array($sqlResult2) ) {
			$str_filter .= "<tr class=\"".$cls_row."\"><td>".stripslashes($row['term'])."</td><td>".stripslashes($row['url'])."</td></tr>";
			if ( $cls_row == 'even' ) {
				$cls_row		= 'odd';
			} else {
				$cls_row		= 'even';
			}
		}
		
		$str_filter .= "</tbody></table>";
		
		if ($page > 1)
		{
			//	$page = $page - 1;
			$prev_page = $current_page - 1;
			$prev .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=keyword_matches&page=\', \'block_keyword_matches\', \''.$prev_page.'\', \'blk-keyword-matches-paging\');"> [Prev]</a> ';
			$first = ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=keyword_matches&page=\', \'block_keyword_matches\', \'1\', \'blk-keyword-matches-paging\');"> [First]</a> | ';
		} 
		else
		{
			$prev  = '';       // we're on page one, don't enable 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($page < $max_page)
		{
			//$page = $page + 1;
			$next_page = $current_page +1;
			$next .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=keyword_matches&page=\', \'block_keyword_matches\', \''.$next_page.'\', \'blk-keyword-matches-paging\');"> [Next]</a> ';
			$last .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=keyword_matches&page=\', \'block_keyword_matches\', \''.$max_page.'\', \'blk-keyword-matches-paging\');"> [Last]</a> ';
		} 
		else
		{
			$next = '';      // we're on the last page, don't enable 'next' link
			$last = ''; // nor 'last page' link
		}

		if ($max_page!= 0) 
			$pagination = $first . $prev . " Showing Page <strong>{$current_page}</strong> of <strong>{$max_page}</strong> pages " . $next . $last;
		else
			$pagination = 'Nothing Found';
		
		$str_filter .= "<div id=\"blk-keyword-matches-paging\">{$pagination}</div>";
		echo $str_filter;
	} else {
		echo 'Nothing Found';
	}
	
} else if ( $task == 'save_link' ) {
	if ( $id != 0 ) {
		$link_title	= isset($_POST['link_title']) ? $_POST['link_title'] : '';
		$link_url	= isset($_POST['link_url']) ? $_POST['link_url'] : '';
		if ( !isDemo() ) {
			$sqlQuery	= "UPDATE `".DB_PREFIX."links` SET `title` = '".db_escape_string($link_title)."', `url` = '".db_escape_string($link_url)."' WHERE `id` = ".$id;
			dbQuery($sqlQuery);
		}
		echo 'success';
		
	}
} else if ( $task == 'edit_link' ) {
	
	$sqlQuery	= "SELECT `title`, `url` FROM `".DB_PREFIX."links` WHERE `id` = {$id}";
	$sqlResult	= dbQuery($sqlQuery, false) or die(mysql_error());
	
	$mainmenu_data	= mysql_fetch_array($sqlResult);
	$link_title	= stripslashes($mainmenu_data['title']);
	$link_url	= stripslashes($mainmenu_data['url']);
	
	$form = <<<__CONTENT__
	<div style='display:none'>
	<div class='modalbox-top'></div>
	<div class='modalbox-content' style="height:200px;">
		<h1 class='modalbox-title'>Edit Sidebar Link</h1>
		<div class='modalbox-loading' style='display:none'></div>
		<div class='modalbox-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<label for='modalbox-name' style="width:150px;">Link Title</label>
			<input type='text' class='modalbox-input' name='link_title' id='link_title' value="{$link_title}" />

			<label for='modalbox-name' style="width:150px;">Link URL</label>
			<input type='text' class='modalbox-input' name='link_url' id='link_url' value="{$link_url}" />
			<br/>

			<label style="width:150px;">&nbsp;</label>
			<button type='submit' class='modalbox-send modalbox-button' tabindex='1006'>Submit</button>
			<button type='submit' class='modalbox-cancel modalbox-button simplemodal-close' tabindex='1007'>Cancel</button>
			<br/>

			<input type="hidden" name="id" id="id" value="{$id}" />
			<input type="hidden" name="admin_url" id="admin_url" value="{$config['website_url']}admin" />
			<input type="hidden" name="modal_type" id="modal_type" value="save_link" />
		</form>
	</div>
	<div class='modalbox-bottom'></div>
</div>
__CONTENT__;
	echo "1|".$form;
	
} else if ( $task == 'save_mainmenu_link' ) {
	if ( $id != 0 ) {
		$link_title	= isset($_POST['link_title']) ? $_POST['link_title'] : '';
		$link_url	= isset($_POST['link_url']) ? $_POST['link_url'] : '';
		$link_order	= isset($_POST['link_order']) ? $_POST['link_order'] : '';
		$link_css	= isset($_POST['link_css']) ? $_POST['link_css'] : '';
		$link_new_window	= isset($_POST['link_new_window']) ? $_POST['link_new_window'] : '';
		
		if ( !isDemo() ) {
		$sqlQuery	= "UPDATE `".DB_PREFIX."main_menu` SET `title` = '".db_escape_string($link_title)."', `url` = '".db_escape_string($link_url)."',
			`order` = {$link_order}, `class` = '".db_escape_string($link_css)."', `new_window` = {$link_new_window} WHERE `id` = ".$id;
		dbQuery($sqlQuery);
		}
		echo 'success';
		
	}
} else if ( $task == 'edit_mainmenu_link' ) {
	
	$sqlQuery	= "SELECT `title`, `url`, `class`, `new_window`, `order` FROM `".DB_PREFIX."main_menu` WHERE `id` = {$id}";
	$sqlResult	= dbQuery($sqlQuery, false) or die(mysql_error());
	
	$mainmenu_data	= mysql_fetch_array($sqlResult);
	$link_title	= stripslashes($mainmenu_data['title']);
	$link_url	= stripslashes($mainmenu_data['url']);
	$link_class	= stripslashes($mainmenu_data['class']);
	$link_order	= stripslashes($mainmenu_data['order']);
	$new_window	= $mainmenu_data['new_window'];
	$link_yes	= "";
	$link_no	= "";
	if( $new_window ) {
		$link_yes	= "selected";
	} else {
		$link_no	= "selected";
	}
	
	$form = <<<__CONTENT__
	<div style='display:none'>
	<div class='modalbox-top'></div>
	<div class='modalbox-content' style="height:200px;">
		<h1 class='modalbox-title'>Edit Main Menu Link</h1>
		<div class='modalbox-loading' style='display:none'></div>
		<div class='modalbox-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<label for='modalbox-name' style="width:150px;">Link Title</label>
			<input type='text' class='modalbox-input' name='link_title' id='link_title' value="{$link_title}" />
			
			<label for='modalbox-name' style="width:150px;">Link URL</label>
			<input type='text' class='modalbox-input' name='link_url' id='link_url' value="{$link_url}" />
			
			<label for='modalbox-name' style="width:150px;">Link Order</label>
			<input type='text' class='modalbox-input' name='link_order' id='link_order' value="{$link_order}" />
			
			<label for='modalbox-name' style="width:150px;">Link Class</label>
			<input type='text' class='modalbox-input' name='link_class' id='link_class' value="{$link_class}" />
			
			<label for='modalbox-name' style="width:150px;">Link New Window</label>
			<select name="mm_link_new_window" id="link_new_window" class="modalbox-input">
				<option value="0" {$link_no}>No</option>
				<option value="1" {$link_yes}>Yes</option>
			</select>
			
			<br/>
			<label style="width:150px;">&nbsp;</label>
			<button type='submit' class='modalbox-send modalbox-button' tabindex='1006'>Submit</button>
			<button type='submit' class='modalbox-cancel modalbox-button simplemodal-close' tabindex='1007'>Cancel</button>
			<br/>
			<input type="hidden" name="id" id="id" value="{$id}" />
			<input type="hidden" name="admin_url" id="admin_url" value="{$config['website_url']}admin" />
			<input type="hidden" name="modal_type" id="modal_type" value="save_mainmenu_link" />
		</form>
	</div>
	<div class='modalbox-bottom'></div>
</div>
__CONTENT__;
	echo "1|".$form;
	exit(0);
} 
else if ( $task == 'remove_tags' ) 
{
	if( !isDemo() ) 
	{
		$sqlQuery	= "SELECT COUNT(1) AS `total` FROM `".DB_PREFIX."video_tags`";
		$sqlResult	= dbQuery($sqlQuery, false) or die(mysql_error());
		
		$data		= mysql_fetch_array($sqlResult);
		$total		= $data['total'];
		
		$limit		= $total-$num;

		if ( $total > $num ) 
		{
			if ( $limit > 0 ) 
			{
				$sqlQuery	= "DELETE FROM `".DB_PREFIX."video_tags` ORDER BY `quantity` ASC LIMIT {$limit}";
				dbQuery($sqlQuery);

				$tags_file	= '../../'.$config["html_cache_dir"]."tags.html";
				if ( file_exists($tags_file) ) 
				{
					unlink($tags_file);
				}
				
				echo '<div class="configSaved">Video tags cloud has been deleted, except top '.$num.' of video tags.</div>';
				exit(0);
			} 
		} 
		else 
		{
			echo '<div class="error">You have less than (or exactly) '.$num.' tags.</div>';
			exit(0);
		}
	} 
	else 
	{
		echo '<div class="error">Video tags is not removed. (Demo Mode)</div>';
		exit(0);
	}
	
	

} else if ( $task == 'remove_html_cache' ) {
	if ( !isDemo() ) {
		deleteFiles("../../templates_c/");
		echo '<div class="configSaved">HTML templates cache files has been removed.</div>';
	} else {
		echo '<div class="error">HTML templates cache files is not removed. (Demo Mode)</div>';
	}
	exit(0);
	
} else if ( $task == 'delete_keyword' ) {
	$id		= isset($_GET['id']) ? $_GET['id'] : 0;
	
	if ( $id != 0 ) {
		if ( !isDemo() ) {
			delete_keyword_filter($id);
		}
		$str_keyword	= "";
		$sqlQuery	= "SELECT `id`, `keyword` FROM `".DB_PREFIX."filter` ORDER BY `keyword`";
		$sqlResult	= dbQuery($sqlQuery);
		$keywords	= array();
		while($row	= mysql_fetch_array($sqlResult)) {
			//$str_keyword	.= '<span>'.stripslashes($row['keyword']).' <a href="ajaxGet(\'ajax/tools.php?task=delete_keyword&id='.$row['id'].'\', \'block_keywords_list\', false, \'block-removing-keyword-filter\');"><img src="images/ico-delete.gif" border="0" /></a>&nbsp;</span>';
			//$str_keyword .= "<li>".stripslashes($row['keyword'])." <a href=\"ajax/tools.php?task=delete_keyword&id=".$row['id']."\"><img src=\"images/ico-delete.gif\" border=\"0\" title=\"ajax/tools.php?task=delete_keyword&id=".$row['id']."\" /></a>&nbsp;</li>";
			$str_keyword .= "<li>".stripslashes($row['keyword'])." <a href=\"#\" title=\"".$row['id']."\"><img src=\"images/ico-delete.gif\" border=\"0\" title=\"".$row['id']."\" /></a>&nbsp;</li>";
		}
		
		
		if ( $str_keyword != '' ) {
			echo $str_keyword;
		} else {
		}
	}
} else if ( $task == 'add_new_keyword' ) {
	$form = <<<__CONTENT__
	<div style='display:none'>
	<div class='modalbox-top'></div>
	<div class='modalbox-content'>
		<h1 class='modalbox-title'>Add New Keyword(s)</h1>
		<div class='modalbox-loading' style='display:none'></div>
		<div class='modalbox-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<label for='modalbox-name'>Add keyword(s) <br /><span>separated by commas</span></label>
			<input type='text' class='modalbox-input' name='new_keyword_filter' id='new_keyword_filter' tabindex='1001' />
			
			<label for='modalbox-email'>Contribute to Database:</label>
			
			<input type="checkbox" value="1" id="chk_contribute" class='modalbox-input' name="chk_contribute" class="input" checked style="width:25px;" /> 
			
			<label for='modalbox-subject'>&nbsp;</label>
			<label id="lblDescription">By contributing to the central keywords database, you will help the community. You will also benefit from the keywords contributed by other Prismotube users.</label>
			
			<br/>
			<label>&nbsp;</label>
			<button type='submit' class='modalbox-send modalbox-button' tabindex='1006'>Submit</button>
			<button type='submit' class='modalbox-cancel modalbox-button simplemodal-close' tabindex='1007'>Cancel</button>
			<br/>
			<input type="hidden" name="admin_url" id="admin_url" value="{$config['website_url']}admin" />
			<input type="hidden" name="modal_type" id="modal_type" value="keyword_filter" />
		</form>
	</div>
	<div class='modalbox-bottom'></div>
</div>
__CONTENT__;
	echo "1|".$form;
} else if ( $task == 'new_keyword' ) {
	
	if ( !isDemo() ) {
		$new_kword_filter	= array();
		$new_kword_filter2	= array();
		$new_kword_filter_pt= array();
		if ( isset($config['keyword_filter_list']) && trim($config['keyword_filter_list']) != '' ) {
			$data_kwords	= explode("|", $config['keyword_filter_list']);
			foreach($data_kwords as $kword) {
				if ( $mb_string_enabled ) {
					$kword	= mb_strtolower($kword);
				}
				$kword	= trim($kword);
				if ( $kword != '' && !in_array($kword, $new_kword_filter) ) {
					$new_kword_filter[]	= $kword;
				}
			}
		}

		$data_kwords		= explode(",", $new_keywords);
		
		foreach($data_kwords as $kword) {
			if ( $is_mbstring_enabled ) {
				$kword	= mb_strtolower($kword, 'UTF-8');
			}
			$kword	= trim($kword);
			if ( $kword != '' && !in_array($kword, $new_kword_filter) ) {
				$new_kword_filter2[]	= $kword;
			}
			
			if ( $kword != '' && !in_array($kword, $new_kword_filter) ) {
				$new_kword_filter_pt[]	= $kword;
			}
		}
		
		add_keyword_filter($new_kword_filter2);
		
		if ( $chk_contribute ) {
			if ( count($new_kword_filter_pt) > 0 ) {
				$kword_params	= implode(", ", $new_kword_filter_pt);
				post_keyword_filter($kword_params);
			}
		}
	}
	
	$str_keyword	= "";
	$keywords2		= array();
	
	$sqlQuery	= "SELECT `id`, `keyword` FROM `".DB_PREFIX."filter` ORDER BY `keyword`";
	$sqlResult	= dbQuery($sqlQuery);
	$keywords	= array();
	while($row	= mysql_fetch_array($sqlResult)) {
		if ( $row['keyword'] != '' ) {
			$str_keyword .= "<li>".stripslashes($row['keyword'])." <a href=\"ajax/tools.php?task=delete_keyword&id=".$row['id']."\"><img src=\"images/ico-delete.gif\" border=\"0\" title=\"ajax/tools.php?task=delete_keyword&id=".$row['id']."\" /></a>&nbsp;</li>";
			$keywords2[] = escape_string_for_regex(stripslashes($row['keyword']));
		}
	}
	
	if ( $str_keyword != '' ) {
		echo $str_keyword;
	} 

	if ( !isDemo() ) {
	$str_keywords = '<?php  
$config["keyword_filter_list"] = "'.implode("|", $keywords2). '";
?>'; 



	$config_path	= "../../config/config_filter.php";
	
	$fp = fopen($config_path, "w"); 
	fwrite($fp, $str_keywords); 
	fclose($fp);
	}
} else if ( $task == 'add_new_link' ) {
	$form = <<<__CONTENT__
	<div style='display:none'>
	<div class='modalbox-top'></div>
	<div class='modalbox-content' style="height:200px;">
		<h1 class='modalbox-title'>Add New Link</h1>
		<div class='modalbox-loading' style='display:none'></div>
		<div class='modalbox-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<label for='modalbox-name' style="width:150px;">Link Title</label>
			<input type='text' class='modalbox-input' name='link_title' id='link_title' />
			
			<label for='modalbox-name' style="width:150px;">Link URL</label>
			<input type='text' class='modalbox-input' name='link_url' id='link_url' value='http://' />
			
			
			<br/>
			<label style="width:150px;">&nbsp;</label>
			<button type='submit' class='modalbox-send modalbox-button' tabindex='1006'>Submit</button>
			<button type='submit' class='modalbox-cancel modalbox-button simplemodal-close' tabindex='1007'>Cancel</button>
			<br/>
			<input type="hidden" name="admin_url" id="admin_url" value="{$config['website_url']}admin" />
			<input type="hidden" name="modal_type" id="modal_type" value="new_link" />
		</form>
	</div>
	<div class='modalbox-bottom'></div>
</div>
__CONTENT__;
	echo "1|".$form;
} else if ( $task == 'new_link' ) {
	
	$link_title	= isset($_POST['link_title']) ? $_POST['link_title'] : '';
	$link_url	= isset($_POST['link_url']) ? $_POST['link_url'] : '';
	
	if ( !isDemo() ) {
		$sqlQuery	= "INSERT INTO `".DB_PREFIX."links` SET `title` = '".db_escape_string($link_title)."', `url` = '".db_escape_string($link_url)."'";
		dbQuery($sqlQuery);
	}
	echo 'success';
} else if ( $task == 'add_mainmenu_link' ) {
	$form = <<<__CONTENT__
	<div style='display:none'>
	<div class='modalbox-top'></div>
	<div class='modalbox-content' style="height:200px;">
		<h1 class='modalbox-title'>Add Main Menu Link</h1>
		<div class='modalbox-loading' style='display:none'></div>
		<div class='modalbox-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<label for='modalbox-name' style="width:150px;">Link Title</label>
			<input type='text' class='modalbox-input' name='link_title' id='link_title' />
			
			<label for='modalbox-name' style="width:150px;">Link URL</label>
			<input type='text' class='modalbox-input' name='link_url' id='link_url' />
			
			<label for='modalbox-name' style="width:150px;">Link Order</label>
			<input type='text' class='modalbox-input' name='link_order' id='link_order' />
			
			<label for='modalbox-name' style="width:150px;">Link Class</label>
			<input type='text' class='modalbox-input' name='link_class' id='link_class' />
			
			<label for='modalbox-name' style="width:150px;">Link New Window</label>
			<select name="mm_link_new_window" id="link_new_window" class="modalbox-input">
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>
			
			
			<br/>
			<label style="width:150px;">&nbsp;</label>
			<button type='submit' class='modalbox-send modalbox-button' tabindex='1006'>Submit</button>
			<button type='submit' class='modalbox-cancel modalbox-button simplemodal-close' tabindex='1007'>Cancel</button>
			<br/>
			<input type="hidden" name="admin_url" id="admin_url" value="{$config['website_url']}admin" />
			<input type="hidden" name="modal_type" id="modal_type" value="new_mainmenu_link" />
		</form>
	</div>
	<div class='modalbox-bottom'></div>
</div>
__CONTENT__;
	echo "1|".$form;
} else if ( $task == 'new_mainmenu_link' ) {
	$link_title	= isset($_POST['link_title']) ? $_POST['link_title'] : '';
	$link_url	= isset($_POST['link_url']) ? $_POST['link_url'] : '';
	$link_order	= isset($_POST['link_order']) ? $_POST['link_order'] : '';
	$link_css	= isset($_POST['link_css']) ? $_POST['link_css'] : '';
	$link_new_window	= isset($_POST['link_new_window']) ? $_POST['link_new_window'] : '';
	
	$new_window	= 0;
	if ( $link_new_window == 'yes' ) {
		$new_window = 1;
	}
	
	if ( !isDemo() ) {
		$sqlQuery	= "INSERT INTO `".DB_PREFIX."main_menu` SET `title` = '".db_escape_string($link_title)."', `url` = '".db_escape_string($link_url)."',
			`order` = {$link_order}, `class` = '".db_escape_string($link_css)."', `new_window` = {$new_window}, `time` = ".time();
		dbQuery($sqlQuery);
	}
	echo 'success';
} else if ( $task == 'upload_video_log' ) {
	
	$sqlQuery	= "SELECT COUNT(*) AS `total` FROM `".DB_PREFIX."videos_upload_log`";
	$sqlResult	= dbQuery($sqlQuery);
	
	$data_match	= mysql_fetch_array($sqlResult);
	$total		= 0;
	if ( isset($data_match['total']) ) {
		$total	= $data_match['total'];
	}
	
	if ( $total > 0 ) {
	
		$pagination		= "";
		
		
	
		$rows_per_page	= 10;
		$offset 		= ($page - 1) * $rows_per_page;
		$max_page		= ceil($total/$rows_per_page);
		$current_page	= $page;
		
		$str_filter		= <<<__CONTENT__
			<table width="100%" class="tablesorter">
		<thead>
		
		<tr>
			<th><b>Title</b></th>
			<th><b>Description</b></th>
			<th><b>Country Code</b></th>
			<th><b>IP Address</b></th>
			<th><b>Date Upload</b></th>
			<th><b>Action</b></th>
		</tr>
		</thead>
		<tbody>
__CONTENT__;

		$sqlQuery		= "SELECT `title`, `youtube_id`, `description`, `country_code`, `ip_addr`, `date_upload` FROM `".DB_PREFIX."videos_upload_log` ORDER BY `date_upload` DESC LIMIT {$offset}, {$rows_per_page}";
		$sqlResult2		= dbQuery($sqlQuery, false);
		$cls_row		= 'even';
		while($row = mysql_fetch_array($sqlResult2) ) {
			$str_filter .= "<tr class=\"".$cls_row."\"><td>".stripslashes($row['title'])."</td>
			<td align=\"right\">".stripslashes($row['description'])."</td>
			<td align=\"right\">".stripslashes($row['country_code'])."</td>
			<td align=\"right\">".stripslashes($row['ip_addr'])."</td>
			<td align=\"right\">".date("Y-m-d",$row['date_upload'])."</td>
			<td align=\"center\">
			<a href=\"http://www.youtube.com/watch?v=".$row['youtube_id']."\" title=\"".stripslashes($row['title'])."\" target=\"_blank\">View Video</a>
			</td></tr>";
			if ( $cls_row == 'even' ) {
				$cls_row		= 'odd';
			} else {
				$cls_row		= 'even';
			}
		}
		
		$str_filter .= "</tbody></table>";
		
		if ($page > 1)
		{

		//	$page = $page - 1;
			$prev_page = $current_page - 1;
			$prev .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=upload_video_log&page=\', \'block_video_upload_log\', \''.$prev_page.'\', \'blk-video-upload-paging\');"> [Prev]</a> ';
			$first = ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=upload_video_log&page=\', \'block_video_upload_log\', \'1\', \'blk-video-upload-paging\');"> [First]</a> | ';
		} 
		else
		{
			$prev  = '';       // we're on page one, don't enable 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($page < $max_page)
		{
			//$page = $page + 1;
			$next_page = $current_page +1;
			$next .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=upload_video_log&page=\', \'block_video_upload_log\', \''.$next_page.'\', \'blk-video-upload-paging\');"> [Next]</a> ';
			$last .= ' <a class="pagination_link" href="javascript:ajaxGet(\'ajax/tools.php?task=upload_video_log&page=\', \'block_video_upload_log\', \''.$max_page.'\', \'blk-video-upload-paging\');"> [Last]</a> ';
		} 
		else
		{
			$next = '';      // we're on the last page, don't enable 'next' link
			$last = ''; // nor 'last page' link
		}

		if ($max_page!= 0) 
			$pagination = $first . $prev . " Showing Page <strong>{$current_page}</strong> of <strong>{$max_page}</strong> pages " . $next . $last;
		else
			$pagination = 'Nothing Found';
		
		$str_filter .= "<div id=\"blk-video-upload-paging\">{$pagination}</div>";
		echo $str_filter;
	} else {
		echo 'Nothing Found';
	}
	
	
} else if ( $task == 'optimize_db' ) {

	$sqlQuery	= "SHOW TABLES";
	$sqlResult	= dbQuery($sqlQuery);
	
	while(list($table) = mysql_fetch_array($sqlResult)){
		$sqlQuery	= "OPTIMIZE TABLE `{$table}`;";
		dbQuery($sqlQuery);
	}

	echo '<div class="configSaved">Database tables has been optimized.</div>';
	exit(0);
}
	
?>