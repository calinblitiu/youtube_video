<?php include('header.php');?>

<div style="padding:4px 6px;border-top:1px solid #999;font-weight:bold;background:#EEE">
<b><?php echo $q;?></b></a>
</div>

	<?php foreach($videos as $video) { ?>
	<hr size="1" noshade='noshade' color="#999" style="width:100%;height:1px;margin:2px 0;padding:0;color:#999;background:#999;border:none;" />
	<div>
	<table width="100%" >
	<tr valign="top">
	<td style="font-size:0px" width="120">

	<a href="<?php echo $config['website_url']?>m/detail.php?v=<?php echo $video['id'];?>"><img src="http://i.ytimg.com/vi/<?php echo $video['id'];?>/default.jpg?w=120&amp;h=90" alt="video" width="120" height="90" style="border:0;margin:0px;" /></a>
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
			<a href="search.php?p=<?php echo $prev_page?>&amp;q=<?php echo urlencode($q);?>">&laquo; <?php echo $lang_prev?></a>
			</span>
			-
			<?php } ?>
			<?php if ( $next_page != 0 ) { ?>
			<span style="padding:0px 3px">
			<a href="search.php?p=<?php echo $next_page?>&amp;q=<?php echo urlencode($q);?>"><?php echo $lang_next?> &raquo;</a>
			</span>
			<?php }?>

		
		</div>
	</div>
	<br/>
	<?php } ?>

<?php include('footer.php');?>