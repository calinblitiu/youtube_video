<?php /* Smarty version 2.6.27, created on 2017-09-11 00:48:52
         compiled from home.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'seotitle', 'home.html', 26, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('showHomePlayer' => true,'keywords' => "",'title_page' => ($this->_tpl_vars['lang_home']),'title_inner' => ($this->_tpl_vars['category_name']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div class="leftContent">
  <!-- Left content Begins -->
  <div class="blueBar">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "box.list_menu.html", 'smarty_include_vars' => array('showOrder' => 'true')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
  <!-- google_ad_section_start -->
  <div class="gallery" id="display_grid" style="display:none;">
	<?php if ($this->_tpl_vars['category_desc'] != ''): ?>
	<div class="gal_cats_desc"><?php echo $this->_tpl_vars['category_desc']; ?>
</div>
	<?php endif; ?>
	<?php unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['videos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "item.video_brief.html", 'smarty_include_vars' => array('videos' => $this->_tpl_vars['videos'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php if ($this->_sections['i']['index'] == '7'): ?>
			<div class="ad468_home" id="grid_ad" align="center"><?php echo $this->_tpl_vars['list_ads']; ?>
</div>
		<?php endif; ?>
	<?php endfor; else: ?>
	  <p> <p><?php echo $this->_tpl_vars['lang_no_related_videos']; ?>
</p></p>
	<?php endif; ?>
	  <div class="blueBar"><?php if ($this->_tpl_vars['total'] > 0): ?><span class="page"><?php echo $this->_tpl_vars['lang_page']; ?>
: <?php echo $this->_tpl_vars['curr_page']; ?>
 <?php echo $this->_tpl_vars['lang_of']; ?>
 <?php echo $this->_tpl_vars['total']; ?>
</span><?php endif; ?>
		<div class="orderBy">
	
		
			  
		<?php if ($this->_tpl_vars['next_page']): ?>
		  <a href="categories/<?php echo ((is_array($_tmp=$this->_tpl_vars['keyword'])) ? $this->_run_mod_handler('seotitle', true, $_tmp) : smarty_modifier_seotitle($_tmp)); ?>
/<?php echo $this->_tpl_vars['randID']; ?>
/page<?php echo $this->_tpl_vars['next_page']; ?>
.html" class="next"><?php echo $this->_tpl_vars['lang_next_page']; ?>
 &gt;&gt;</a>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['prev_page']): ?>
		  <a href="categories/<?php echo ((is_array($_tmp=$this->_tpl_vars['keyword'])) ? $this->_run_mod_handler('seotitle', true, $_tmp) : smarty_modifier_seotitle($_tmp)); ?>
/<?php echo $this->_tpl_vars['randID']; ?>
/page<?php echo $this->_tpl_vars['prev_page']; ?>
.html" class="next">&lt;&lt; <?php echo $this->_tpl_vars['lang_previous_page']; ?>
</a>
		<?php endif; ?>
		</div>
	  </div>
	</div>

	<div class="gallery" id="display_list" style="display:none;"> 
	<?php if ($this->_tpl_vars['category_desc'] != ''): ?>
	<div class="gal_cats_desc"><?php echo $this->_tpl_vars['category_desc']; ?>
</div>
	<?php endif; ?>
	<?php unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['videos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "item.video_detailed.html", 'smarty_include_vars' => array('videos' => $this->_tpl_vars['videos'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php if ($this->_sections['i']['index'] == '2'): ?>
			<div class="ad468_home" id="grid_ad" align="center"><?php echo $this->_tpl_vars['list_ads']; ?>
</div>
		<?php endif; ?>
	<?php endfor; else: ?>
	  <p><?php echo $this->_tpl_vars['lang_no_video_available']; ?>
</p>
	<?php endif; ?>
	<div class="blueBar"><?php if ($this->_tpl_vars['total'] > 0): ?><span class="page"><?php echo $this->_tpl_vars['lang_page']; ?>
: <?php echo $this->_tpl_vars['curr_page']; ?>
 <?php echo $this->_tpl_vars['lang_of']; ?>
 <?php echo $this->_tpl_vars['total']; ?>
</span><?php endif; ?>
		<div class="orderBy">
	
		
			  
		<?php if ($this->_tpl_vars['next_page']): ?>
		  <a href="categories/<?php echo ((is_array($_tmp=$this->_tpl_vars['keyword'])) ? $this->_run_mod_handler('seotitle', true, $_tmp) : smarty_modifier_seotitle($_tmp)); ?>
/<?php echo $this->_tpl_vars['randID']; ?>
/page<?php echo $this->_tpl_vars['next_page']; ?>
.html" class="next"><?php echo $this->_tpl_vars['lang_next_page']; ?>
 &gt;&gt;</a>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['prev_page']): ?>
		  <a href="categories/<?php echo ((is_array($_tmp=$this->_tpl_vars['keyword'])) ? $this->_run_mod_handler('seotitle', true, $_tmp) : smarty_modifier_seotitle($_tmp)); ?>
/<?php echo $this->_tpl_vars['randID']; ?>
/page<?php echo $this->_tpl_vars['prev_page']; ?>
.html" class="next">&lt;&lt; <?php echo $this->_tpl_vars['lang_previous_page']; ?>
</a>
		<?php endif; ?>
		</div>
	  </div>
	</div>
	<!-- google_ad_section_end -->
	
	<script>
	setTimeout('setDisplayOnLoad();',1);
	</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>