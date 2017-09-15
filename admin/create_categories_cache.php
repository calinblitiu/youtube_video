<?php
	@session_start();
	
	$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
	if ( !$is_logged ){
		include('inc/auth.php');
		die();
	}
		
	require_once("../config/db_config.php");
	
	$db_link	= mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME) or die( mysql_error());
	
	//DB_PREFIX
	$sqlQuery = "SELECT *
		FROM `".DB_PREFIX."categories`
		WHERE `position` RLIKE '^([0-9]+>){1,1}$' AND `c_group`	= '0'
		AND ( `enable_publishdate` = 0 OR (`enable_publishdate` = 1 AND `publishdate` <= ".time()."))
		ORDER BY `c_name`";
	$sqlResult	= mysql_query($sqlQuery);
	$str_categories_cache	= "<ul>";
	
	while( $row = mysql_fetch_array($sqlResult) ) {
		
		$category_id			= $row['id'];
		$position				= stripslashes($row['position']);
		$category_name			= stripslashes($row['c_name']);
		
		$str_categories_cache	.= <<<__CONTENT__
	<li>{$category_name}</li>
__CONTENT__;
		
		$sqlQuery	= "SELECT *
		FROM `".DB_PREFIX."categories`
		WHERE `position` RLIKE '^".$position."[0-9]+>$'
		ORDER BY `c_name`";
		$sqlResult2	= mysql_query($sqlQuery);
		
		if ( mysql_num_rows($sqlResult2) > 0 ) {
		
		$str_categories_cache	.= "{php} if ( isset(\$_GET['id']) && \$_GET['id'] == {$category_id} ) { {/php}";
		
			while( $row2 = mysql_fetch_array($sqlResult2) ) {
				$category_name			= stripslashes($row2['c_name']);
				$str_categories_cache	.= <<<__CONTENT__
		<li> &raquo; {$category_name}</li>
__CONTENT__;
			}
		
		$str_categories_cache	.= "{php} } {/php}";
		
		}
		
	}
	
	$str_categories_cache	.= "</ul>";
	
	//echo eval($str_categories_cache);
	echo $str_categories_cache;
	
	mysql_close($db_link);
?>