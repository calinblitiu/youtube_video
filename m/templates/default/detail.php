<?php include('header.php');?>

<div style="padding:4px 6px;border-top:1px solid #999;font-weight:bold;background:#EEE">
<b><?php echo $video_title?></b></a>
</div>
	
<div
style="margin-left:3px;margin-right:3px">
<div style="border-top:none;margin:3px 0;padding-top:8px;text-align:left">

<table><tr valign="top">
<td align="left" width="160">
	<?php if ( $video_url != '' ) { ?>
		<?php if ( !$is_iphone ) { ?>
			<a href="<?php echo $video_url?>"><img src="http://i.ytimg.com/vi/<?php echo $video_id?>/hqdefault.jpg?w=160" alt="video" width="160" height="120" style="border:0;margin:0px;" /></a> 
		<?php } else { ?>
			<embed id="yt" type="application/x-shockwave-flash" src="http://www.youtube.com/watch?v=<?=$video_id?>" width="160" height="120"></embed>
		<?php } ?>
	<?php } ?>
</td>
<td><div style="font-size:13px">
<div style="font-size:80%;padding-left:2px;text-align:left">
<div><?php echo $duration?>&nbsp;&nbsp;

<div class="rating_bar"><div style="width:<?php echo $video_average?>%"></div></div></div>
<div><?php echo $view_count?> <?php echo $lang_views?></div>
<a href="author.php?author=<?php echo $author_name?>"><?php echo $author_name?></a>
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

<?php foreach( $related_videos as $key => $related_video ) { ?>
	<table width="100%" >
	<tr valign="top">
	<td style="font-size:0px" width="120">

	<a href="<?php echo $config['website_url']?>m/detail.php?v=<?php echo $related_video['video_id'];?>"><img src="http://i.ytimg.com/vi/<?php echo $related_video['video_id']?>/default.jpg?w=120&amp;h=90" alt="<?php echo $related_video['title']?>" width="120" height="90" style="border:0;margin:0px;" /></a>
	</td>
	<td style="width:100%;font-size:13px;padding-left:2px">
	<div style="font-size:90%;padding-bottom:1px" >
	<a accesskey="<?php echo $related_video['accesskey']?>" href="<?php echo $config['website_url']?>m/detail.php?v=<?php echo $related_video['video_id'];?>"><?php echo $related_video['title']?></a>
	</div>

	<div style="color:#333;font-size:80%"><?php echo $related_video['duration']?>&nbsp;&nbsp;<div class="rating_bar"><div style="width:<?php echo $related_video['average']?>%"></div></div></div>
	<div style="color:#333;font-size:80%"><?php echo $related_video['view']?> <?php echo $lang_views?></div>
	</td>
	</tr>
	</table>
	<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
<?php } ?>

</div>

<div align='center'>
<a href="related.php?v=<?php echo $video_id?>"><?php echo $lang_view_all_related_videos?> &raquo;</a>
</div>
<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />

<br/>
<div
style="margin-left:3px;margin-right:3px">
<div style="padding-bottom:0">

<?php echo $lang_comments?> (<b><?php echo $total_comments?></b>)
</div>
<?php /* <div style="padding-bottom:0">
<a href="view_comment?gl=US&amp;xl_blz_on=xl_blz_on&amp;client=mv-google&amp;hl=en&amp;v=FOIU9l4WiuQ"> <?php echo $lang_view;?></a>
</div> */ ?>
<br/>


</div>
<br/>



<?php include('footer.php');?>