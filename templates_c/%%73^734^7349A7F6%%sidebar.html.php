<?php /* Smarty version 2.6.27, created on 2017-09-11 00:48:52
         compiled from sidebar.html */ ?>
<?php if ($this->_tpl_vars['showDescription']): ?>
	<?php if ($this->_tpl_vars['enable_download']): ?>
	<div class="category">
	<h2><?php echo $this->_tpl_vars['lang_download_video']; ?>
</h2>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "box.download.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	<?php endif; ?>
	
	<div class="sidebarAd" style="margin-bottom:10px;">
	<?php echo $this->_tpl_vars['above_video_detail_ads']; ?>

	</div>

	<div class="category">
	<h2><?php echo $this->_tpl_vars['lang_video_details']; ?>
</h2>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "box.video_description.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['video_upload_enabled'] == 'true'): ?>
<div class="sidebarContainer_upload">
<a href="upload.php" class="png">
<img border="0" src="<?php echo $this->_tpl_vars['theme_base']; ?>
images/upload_video_btn.png" style="width:300px;" />
</a>
</div>
<?php endif; ?>




<?php if ($this->_tpl_vars['id'] && $this->_tpl_vars['relatedVideosPosition'] == 'sidebar'): ?>
<?php if ($this->_tpl_vars['above_related_ads'] != ""): ?>
<div class="sidebarAd" id="blk-display">
<?php echo $this->_tpl_vars['above_related_ads']; ?>

</div>
<?php endif; ?>
<div class="category">
		<div class="sidebarContainer" id="height-related">
			<div id="relatedVideos"></div>
		</div>
		<script type="text/javascript">
			setTimeout('ajaxGet(\'ajax/related.php?vid=<?php echo $this->_tpl_vars['id']; ?>
&p=\', \'relatedVideos\', \'1\', \'relatedVideos\');',1500);
		</script>
</div>
<div class="clearboth"></div>
<?php endif; ?>

<?php if ($this->_tpl_vars['sitewide_above_category'] != ""): ?>
 <?php if ($this->_tpl_vars['relatedVideosPosition'] == 'sidebar'): ?>
 <div class="sidebarAd" id="blk-display">
 <?php else: ?>
 <div class="sidebarAd" id="blk-display">
 <?php endif; ?>

<?php echo $this->_tpl_vars['sitewide_above_category']; ?>

</div>
<?php endif; ?>


<div class="category">
	<h2><?php echo $this->_tpl_vars['lang_categories']; ?>
</h2>
	<div class="categoriesTxt">
		<?php if ($this->_tpl_vars['is_categories_cache']): ?>
			<?php  categories_getTreeMenu();  ?>
			<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => ($this->_tpl_vars['html_cache_dir'])."/categories_cache.php", 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

		<?php else: ?>
			<?php  echo categories_getTreeMenu();  ?>
		<?php endif; ?>
	</div>
</div>
<?php if ($this->_tpl_vars['showTagCloud']): ?>
<div class="sidebarAd" id="blk-display">
<?php echo $this->_tpl_vars['sitewide_above_video_tags']; ?>

</div>
  <div class="category">
	<!-- Video tags Begins -->
	<h2><?php echo $this->_tpl_vars['lang_video_tags']; ?>
</h2>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['html_cache_dir'])."/tags.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
  <?php endif; ?>
  
  <?php if ($this->_tpl_vars['facebook_enabled'] == 'true'): ?>
  <div class="category">
	<h2><?php echo $this->_tpl_vars['lang_facebook']; ?>
</h2>
	<iframe src="https://www.facebook.com/plugins/likebox.php?href=<?php echo $this->_tpl_vars['facebook_page_url']; ?>
&amp;width=302&amp;height=<?php echo $this->_tpl_vars['facebook_height']; ?>
&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=<?php echo $this->_tpl_vars['facebook_stream']; ?>
&amp;header=false&amp;appId=170920906315017" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:302px; height:<?php echo $this->_tpl_vars['facebook_iframe_height']; ?>
px;" allowTransparency="true"></iframe>
	
	

	
	
  </div>
 
  <?php endif; ?>
  
	<?php if ($this->_tpl_vars['shoutbox_enabled'] == 'true'): ?>
	<div class="category">
		<h2>Shoutbox</h2>
		<div class="categoriesTxt" style="height:auto;">
		<?php echo $this->_tpl_vars['shoutbox_code']; ?>

		</div>
	</div>
	<?php endif; ?>
	
  <?php if ($this->_tpl_vars['is_show_twitter_feeds']): ?>
  <!-- google_ad_section_start -->
   <div class="category">
	<h2><?php echo $this->_tpl_vars['lang_twitter_feeds']; ?>
</h2>
	<div class="categoriesTxt">
	<?php unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['twitter_feeds']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
	<div id="tweet_feeds">
		<div><a href="<?php echo $this->_tpl_vars['twitter_feeds'][$this->_sections['i']['index']]['author_link']; ?>
"><img src="<?php echo $this->_tpl_vars['twitter_feeds'][$this->_sections['i']['index']]['image']; ?>
" border="0" /></a></div>
		<a href="<?php echo $this->_tpl_vars['twitter_feeds'][$this->_sections['i']['index']]['author_link']; ?>
"><?php echo $this->_tpl_vars['twitter_feeds'][$this->_sections['i']['index']]['author']; ?>
</a>:&nbsp;&nbsp;<?php echo $this->_tpl_vars['twitter_feeds'][$this->_sections['i']['index']]['desc']; ?>
 <a href="<?php echo $this->_tpl_vars['twitter_feeds'][$this->_sections['i']['index']]['link']; ?>
 "><?php echo $this->_tpl_vars['lang_view_tweet']; ?>
</a>
	</div>
	<?php endfor; endif; ?>
	</div>
  </div>
  <!-- google_ad_section_end -->
  <?php endif; ?>
  
    <?php if ($this->_tpl_vars['is_show_flickr_feeds']): ?>

   <div class="category">
	<h2><?php echo $this->_tpl_vars['lang_flickr']; ?>
</h2>
	<div class="sidebar_widget">
		<div id="flickr_feeds">
		<?php unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['flickr_feeds']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
		<div>
			<?php echo $this->_tpl_vars['flickr_feeds'][$this->_sections['i']['index']]['img']; ?>

		</div>
		<?php endfor; endif; ?>
		</div>
	</div>
  </div>

  <?php endif; ?>
  
  <?php if ($this->_tpl_vars['showLinks']): ?>
  <div class="category">
	<!-- Video tags Begins -->
	<h2><?php echo $this->_tpl_vars['lang_links']; ?>
</h2>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "box.links.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
  <?php endif; ?>
  