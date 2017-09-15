<?php /* Smarty version 2.6.27, created on 2017-09-11 00:48:52
         compiled from footer.html */ ?>

</div>
			<div class="rightContent">
			  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "sidebar.html", 'smarty_include_vars' => array('relatedVideosPosition' => ($this->_tpl_vars['relatedVideosPosition']),'above_video_detail_ads' => ($this->_tpl_vars['above_video_detail_ads']),'twitter_feeds' => ($this->_tpl_vars['twitter_feeds']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </div>

			<?php if ($this->_tpl_vars['bottom_ads'] != ''): ?>
			<div align="center"><?php echo $this->_tpl_vars['bottom_ads']; ?>
</div>
			<?php endif; ?>
          </div>
        </div>
      </div>




  </div>

    <div id="FooterWrapper">
    <!-- Footer Begins -->
    <div class="AutoWrapper"><span class="FooterInner"><?php echo $this->_tpl_vars['lang_all_rights_reserved']; ?>
 <a class="blue_link" href="<?php echo $this->_tpl_vars['website_url']; ?>
"><?php echo $this->_tpl_vars['website_name']; ?>
</a>.
		| <a class="blue_link" href="google_sitemap_xml.php" target="_blank" title="<?php echo $this->_tpl_vars['lang_sitemap']; ?>
"><?php echo $this->_tpl_vars['lang_sitemap']; ?>
</a>
		| <a class="blue_link" href="contact-us.html" title="<?php echo $this->_tpl_vars['lang_contact_us']; ?>
"><?php echo $this->_tpl_vars['lang_contact_us']; ?>
</a>
		<div class="footer_logo">
		<a target="_blank" href="http://www.youtube.com">
			<img border="0" align="top" src="<?php echo $this->_tpl_vars['theme_base']; ?>
graphics/badge1.gif"/>
		</a>
		</div>
	</span>

	</div>
  </div>


</div>
<?php echo $this->_tpl_vars['footer_code']; ?>

<?php echo $this->_tpl_vars['wibiya_code']; ?>

<script type="text/javascript">
<!--
	set_display_favorites();
-->
</script>
</body>
</html>