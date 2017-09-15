<?php include('header.php');?>

<div style="padding:4px 6px;border-top:1px solid #999;font-weight:bold;background:#EEE">
<b><?php echo $author;?>'s <?php echo $lang_channel?></b></a>
</div>
<table width="100%"><tr valign="top">
<td width="548"><div style="font-size:80%;padding-left:2px">
<div><?php if ( $published != 0 ) { echo date("M j,Y",$published); }?> <?php echo $lang_joined?></div>
<div><?php echo $subscriptions_count?> <?php echo $lang_subscribers;?></div>
<div><?php echo $view_count?> <?php echo $lang_channel_views?></div>

</div></td>
<td align="right">
<div><img src="<?php echo $thumbnail?>" alt="profile icon" width="88" height="88" style="border:2;margin:0px;" />
</div></td></tr>

</table>
<div
style="margin-left:3px;margin-right:3px">
<br/>
<br/>
</div>
<div
style="margin-right:3px;font-size:110%;font-weight:bold;margin-left:3px;padding-bottom:3px">Videos (15)</div>

<div
>

	<?php foreach($videos as $video) { ?>
	<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
	<div>
	<table width="100%" >
	<tr valign="top">
	<td style="font-size:0px" width="120">

	<a href="<?php echo $video['rtsp_url'];?>"><img src="http://i.ytimg.com/vi/<?php echo $video['id'];?>/default.jpg?w=120&amp;h=90" alt="video" width="120" height="90" style="border:0;margin:0px;" /></a>
	</td>
	<td style="width:100%;font-size:13px;padding-left:2px">

	<div style="font-size:90%;padding-bottom:1px" >
	<a accesskey="<?php echo $video['accesskey'];?>" href="<?php echo $config['website_url']?>m/detail.php?v=<?php echo $video['id'];?>"><?php echo $video['title'];?></a>
	</div>
	<div style="color:#333;font-size:80%"><?php echo $video['duration'];?>&nbsp;&nbsp;<div class="rating_bar"><div style="width:<?php echo $video['average']?>%"></div></div></div>

	<div style="color:#333;font-size:80%"><?php echo $video['view'];?> <?php echo $lang_views;?></div>

	</td>
	</tr>

	</table>
	<?php } ?>

	</div>

	<?php if ( $total_videos > $config['list_per_page'] ) { ?>
	<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
	<div id="botPagination">			
		<div style="font-size:90%;margin-top:8px;text-align:center">
			<?php if ( $prev_page != 0 ) { ?>
			<span style="padding:0px 3px">
			<a href="author.php?p=<?php echo $prev_page?>&amp;author=<?php echo urlencode($author);?>">&laquo; <?php echo $lang_prev?></a>
			</span>
			-
			<?php } ?>
			<?php if ( $next_page != 0 ) { ?>
			<span style="padding:0px 3px">
			<a href="author.php?p=<?php echo $next_page?>&amp;author=<?php echo urlencode($author);?>"><?php echo $lang_next?> &raquo;</a>
			</span>
			<?php }?>

		
		</div>
	</div>
	<br/>
	<?php } ?>

<?php include('footer.php');?>