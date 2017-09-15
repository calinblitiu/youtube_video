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
<script type="text/javascript">
/* <![CDATA[ */
if(typeof ytm == "undefined") var ytm = {};
ytm.startTime = new Date().getTime();
ytm.iref = {};
ytm.nextiref_ = 1;
/* ]]> */
</script>
</head>
<body style="color:#333;font-size:13px;font-family:sans-serif;margin:0;background-color:#fff" >
<table
width="100%" cellspacing="0">
<tr>

<td valign="top" style="padding: 6px 0 2px 5px; font-size: 0">
<img src="../images/<?php echo $config['website_logo'];?>" alt="<?php echo $config['website_name'];?>" style="border:0;margin:0px;" />
</td>
</tr>
</table>
<div style="margin-left:3px;margin-right:3px">



<form id="searchForm" action="search.php" method="post" style="padding:5px 0">
<div>
<input accesskey="*" name="q" type="text" size="15" maxlength="128" style="color:#333;padding:0;font-family:sans-serif;width:65%" value="" />
<input type="submit" name="submit" value="Search" style="padding:0;color:black;margin-top:2px;font-size:100%" />
</div>
</form>

</div>

	<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
<div
style="margin-left:3px;margin-right:3px">
<div style="border-top:none;margin:3px 0;padding-top:8px;text-align:left">
<div  style="font-weight:bold;padding-bottom:1px"><?php echo $video_title?></div>
<table><tr valign="top">
<td align="left" width="160">
	<?php if ( $video_url != '' ) { ?>
	<a href="<?php echo $video_url?>"><img src="http://i.ytimg.com/vi/<?php echo $video_id?>/hqdefault.jpg?w=160" alt="video" width="160" height="120" style="border:0;margin:0px;" /></a>
	<?php } ?>
</td>
<td><div style="font-size:13px">
<div style="font-size:80%;padding-left:2px;text-align:left">
<div><?php echo $duration?>&nbsp;&nbsp;

<img src="http://s.ytimg.com/yt/m/mobile/img/stars_4.5_49x9-vfl84759.gif" alt="4.5 stars" width="49" height="9" style="border:0;margin:0px;" /></div>
<div><?php echo $view_count?> <?php echo $lang_views?></div>
<a href="/profile?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;user=kexpradio"><?php echo $author_name?></a>
<div><?php echo $published_date?></div>
</div>
</div></td>
</tr></table>
</div>
<div>				
	<?php if ( $video_url != '' ) { ?>
	<a href="<?php echo $video_url?>" alt="<?php echo $lang_video?>"><?php echo $lang_watch_video;?></a>
	<?php } ?>
<br/>
<div>

<?php /*
Video quality:
<a href="/setpref?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;pref=serve_hq&amp;value=on&amp;next=%2Fwatch%3Fgl%3DUS%26xl_blz_on%3Dxl_blz_on%26client%3Dmv-google%26hl%3Den%26v%3DFOIU9l4WiuQ">HQ</a>
|
<b>
Normal
</b>
*/ ?>

</div>
</div>
</div>
<br/>
<div style="margin-left:3px;margin-right:3px">
<div><span>
<?php echo $video_description;?>
</span></div>
</div>

<br/>
<a name="more"></a>
<div
style="margin-right:3px;font-size:110%;font-weight:bold;margin-left:3px;padding-bottom:3px"><?php echo $lang_related_videos?></div>
<div
>
<div>
<table width="100%" >
<tr valign="top">
<td style="font-size:0px" width="120">

<a href="rtsp://v4.cache5.c.youtube.com/CjYLENy73wIaLQlsFT1_FyTOORMYESARFEIJbXYtZ29vZ2xlSARSBWluZGV4YPa6qpWXt4mSTAw=/0/0/0/video.3gp"><img src="http://i.ytimg.com/vi/Oc4kF389FWw/default.jpg?w=120&amp;h=90&amp;sigh=GNd2E11p1-msGGBKuyaCSf3H0N0" alt="video" width="120" height="90" style="border:0;margin:0px;" /></a>
</td>
<td style="width:100%;font-size:13px;padding-left:2px">
<div style="font-size:90%;padding-bottom:1px" >
<a accesskey="1" href="/watch?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;rl=yes&amp;v=Oc4kF389FWw">T-Model Ford with GravelRoad - Ask Her For Water</a>
</div>

<div style="color:#333;font-size:80%">5:17&nbsp;&nbsp;<img src="http://s.ytimg.com/yt/m/mobile/img/stars_5.0_49x9-vfl84759.gif" alt="5.0 stars" width="49" height="9" style="border:0;margin:0px;" /></div>
<div style="color:#333;font-size:80%">1 year ago</div>
<div style="color:#333;font-size:80%">11,935 views</div>
</td>
</tr>
</table>
<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
<table width="100%" >
<tr valign="top">
<td style="font-size:0px" width="120">

<a href="rtsp://v5.cache5.c.youtube.com/CjYLENy73wIaLQk1kfuGmnohtRMYESARFEIJbXYtZ29vZ2xlSARSBWluZGV4YPa6qpWXt4mSTAw=/0/0/0/video.3gp"><img src="http://i.ytimg.com/vi/tSF6mob7kTU/default.jpg?w=120&amp;h=90&amp;sigh=RguQLxVAGP6HaudHLzUq94QBJ5s" alt="video" width="120" height="90" style="border:0;margin:0px;" /></a>
</td>
<td style="width:100%;font-size:13px;padding-left:2px">

<div style="font-size:90%;padding-bottom:1px" >
<a accesskey="2" href="/watch?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;rl=yes&amp;v=tSF6mob7kTU">T-Model Ford - Jack Daniel Time</a>
</div>
<div style="color:#333;font-size:80%">6:09&nbsp;&nbsp;<img src="http://s.ytimg.com/yt/m/mobile/img/stars_4.5_49x9-vfl84759.gif" alt="4.5 stars" width="49" height="9" style="border:0;margin:0px;" /></div>
<div style="color:#333;font-size:80%">1 year ago</div>
<div style="color:#333;font-size:80%">7,766 views</div>
</td>
</tr>
</table>
<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
<table width="100%" >
<tr valign="top">
<td style="font-size:0px" width="120">

<a href="rtsp://v8.cache2.c.youtube.com/CjYLENy73wIaLQlHRrRjiaLKHRMYESARFEIJbXYtZ29vZ2xlSARSBWluZGV4YPa6qpWXt4mSTAw=/0/0/0/video.3gp"><img src="http://i.ytimg.com/vi/HcqiiWO0Rkc/default.jpg?w=120&amp;h=90&amp;sigh=uYMetoTrReWb-fmIUo4RzyVst2A" alt="video" width="120" height="90" style="border:0;margin:0px;" /></a>
</td>
<td style="width:100%;font-size:13px;padding-left:2px">
<div style="font-size:90%;padding-bottom:1px" >
<a accesskey="3" href="/watch?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;rl=yes&amp;v=HcqiiWO0Rkc">R.L. Burnside</a>
</div>
<div style="color:#333;font-size:80%">2:54&nbsp;&nbsp;<img src="http://s.ytimg.com/yt/m/mobile/img/stars_5.0_49x9-vfl84759.gif" alt="5.0 stars" width="49" height="9" style="border:0;margin:0px;" /></div>
<div style="color:#333;font-size:80%">4 years ago</div>
<div style="color:#333;font-size:80%">94,042 views</div>
</td>
</tr>
</table>

<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
<table width="100%" >
<tr valign="top">
<td style="font-size:0px" width="120">

<a href="rtsp://v5.cache8.c.youtube.com/CjYLENy73wIaLQnmQV3EoT6lpBMYESARFEIJbXYtZ29vZ2xlSARSBWluZGV4YPa6qpWXt4mSTAw=/0/0/0/video.3gp"><img src="http://i.ytimg.com/vi/pKU-ocRdQeY/default.jpg?w=120&amp;h=90&amp;sigh=2HiFLHCFDw7C2EAXpv7XlolFNik" alt="video" width="120" height="90" style="border:0;margin:0px;" /></a>
</td>
<td style="width:100%;font-size:13px;padding-left:2px">
<div style="font-size:90%;padding-bottom:1px" >
<a accesskey="4" href="/watch?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;rl=yes&amp;v=pKU-ocRdQeY">Dead Confederate &quot;The Rat&quot;</a>
</div>
<div style="color:#333;font-size:80%">5:29&nbsp;&nbsp;<img src="http://s.ytimg.com/yt/m/mobile/img/stars_5.0_49x9-vfl84759.gif" alt="5.0 stars" width="49" height="9" style="border:0;margin:0px;" /></div>
<div style="color:#333;font-size:80%">2 years ago</div>

<div style="color:#333;font-size:80%">46,013 views</div>
</td>
</tr>
</table>
<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
<table width="100%" >
<tr valign="top">
<td style="font-size:0px" width="120">

<a href="rtsp://v6.cache8.c.youtube.com/CjYLENy73wIaLQkOUlxpGXZ1KxMYESARFEIJbXYtZ29vZ2xlSARSBWluZGV4YPa6qpWXt4mSTAw=/0/0/0/video.3gp"><img src="http://i.ytimg.com/vi/K3V2GWlcUg4/default.jpg?w=120&amp;h=90&amp;sigh=h6ELeYOJo3J9R1Y9_8E_aQXQIiM" alt="video" width="120" height="90" style="border:0;margin:0px;" /></a>
</td>
<td style="width:100%;font-size:13px;padding-left:2px">
<div style="font-size:90%;padding-bottom:1px" >
<a accesskey="5" href="/watch?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;rl=yes&amp;v=K3V2GWlcUg4">T-Model Ford - I'm Insane</a>
</div>

<div style="color:#333;font-size:80%">4:21&nbsp;&nbsp;<img src="http://s.ytimg.com/yt/m/mobile/img/stars_5.0_49x9-vfl84759.gif" alt="5.0 stars" width="49" height="9" style="border:0;margin:0px;" /></div>
<div style="color:#333;font-size:80%">1 year ago</div>
<div style="color:#333;font-size:80%">5,434 views</div>
</td>
</tr>
</table>
</div>
</div>
<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
<div align='center'>
<a href="results?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;search=by_related&amp;q=&amp;v=FOIU9l4WiuQ">View all related videos &raquo;</a>
</div>
<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />

<br/>
<div
style="margin-left:3px;margin-right:3px">
<div style="padding-bottom:0">

<?php echo $lang_comments?> (<b><?php echo $total_comments?></b>)
</div>
<div style="padding-bottom:0">
<a href="view_comment?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;v=FOIU9l4WiuQ"> View</a>
</div>
<br/>


</div>
<br/>



<div
style="border-top:1px solid #999;font-size:80%;background:#EEE;text-align:center">
<br/>

<div>&copy; <?php echo date("Y");?> <?php echo $config['website_name'];?>, LLC</div>

<br/>
</div>
</body>
</html>