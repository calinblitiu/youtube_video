<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>
<title><?php echo $config["website_name"];?></title>
<style type="text/css">
/* <![CDATA[ */
a:link,a:visited,a:hover {color:#0033CC;text-decoration:none}
.rating_bar { width:55px;}
.rating_bar div{
background:transparent url(../templates/sunny-day/images/star_x_orange.gif) repeat-x scroll 0 0;
height:12px;

}
/* ]]> */
</style>
</head>
<body style="color:#333;font-size:13px;font-family:sans-serif;margin:0;background-color:#fff" >
<table
width="100%" cellspacing="0">
<tr>

<td valign="top" style="padding: 6px 0 2px 5px; font-size: 0">
<a href="<?php echo $config['website_url']?>m/"><img src="../images/<?php echo $config['website_logo'];?>" alt="<?php echo $config['website_name'];?>" style="border:0;margin:0px;width:70%;" /></a>
</td>
</tr>
</table>
<div style="margin-left:3px;margin-right:3px">



<form id="searchForm" action="search.php" method="post" style="padding:5px 0">
<div>
<input accesskey="*" name="q" type="text" size="15" maxlength="128" style="color:#333;padding:0;font-family:sans-serif;width:65%" value="<?php echo $q;?>" />
<input type="submit" name="submit" value="Search" style="padding:0;color:black;margin-top:2px;font-size:100%" />
</div>
</form>

</div>
<?php 
$is_cats_page	= strpos($_SERVER['SCRIPT_NAME'], 'categories.php');
if ( $is_cats_page === false ) {
?>
<div style="margin-left:3px;margin-right:3px;margin-bottom:5px;">
<a href="categories.php"><?php echo $lang_browse_categories?></a><br />
</div>
<?php } ?>