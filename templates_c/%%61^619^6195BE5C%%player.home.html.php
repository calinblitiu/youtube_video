<?php /* Smarty version 2.6.27, created on 2017-09-11 00:59:46
         compiled from html/player.home.html */ ?>
<?php if ($this->_tpl_vars['youtube_player']): ?>
<script type="text/javascript">

// https://developers.google.com/youtube/player_parameters *}
	var so = new SWFObject("http://www.youtube.com/v/<?php echo $this->_tpl_vars['first_video_id']; ?>
?fs=1&amp;rel=0", "youtube", "600", "420", "2", "#000000");	so.addVariable("autohide","1");
	so.addVariable("autoplay","1");
	so.addVariable("controls","2");
	so.addVariable("fs","1"); 	so.addVariable("modestbranding","1");
	so.addVariable("rel","0");	so.addVariable("showinfo","0");
	so.addVariable("theme","light");
	so.addVariable("autoplay","1");
	so.addParam("allowfullscreen","true");
	so.write("mediaspace");
</script>
<?php else: ?>
<script type="text/javascript">
	var so = new SWFObject("player/player.swf?kdfkdkfd","mpl","935","420","8");
	so.addParam("allowscriptaccess","always");
	so.addParam("allowfullscreen","true");
	so.addParam("wmode","transparent");
	so.addParam("flashvars","file=<?php echo $this->_tpl_vars['player_file']; ?>
<?php echo $this->_tpl_vars['player_skin']; ?>
<?php if ($this->_tpl_vars['enable_player_colors']): ?><?php echo $this->_tpl_vars['player_colors']; ?>
<?php endif; ?><?php echo $this->_tpl_vars['player_longtail_param']; ?>
<?php if ($this->_tpl_vars['player_custom_plugins_home']): ?><?php echo $this->_tpl_vars['player_custom_plugins_home']; ?>
<?php endif; ?>");
	so.write("mediaspace");
</script>
<?php endif; ?>