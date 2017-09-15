<?php /* Smarty version 2.6.27, created on 2017-09-11 00:48:52
         compiled from box.list_menu.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'seotitle', 'box.list_menu.html', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['total'] > 0): ?><span class="page"><?php echo $this->_tpl_vars['lang_page']; ?>
: <?php echo $this->_tpl_vars['curr_page']; ?>
 <?php echo $this->_tpl_vars['lang_of']; ?>
 <?php echo $this->_tpl_vars['total']; ?>
</span><?php endif; ?>
<?php if ($this->_tpl_vars['showOrder']): ?>
	<div class="orderBy">
		<label for="my-dropdown2" class="order"><?php echo $this->_tpl_vars['lang_order_by']; ?>
: &nbsp;</label>
		<div id="win-xp" class="selCont">
			<select id="order-list" name="my-dropdown2">
				<?php $_from = $this->_tpl_vars['options_sort_by']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idx_key'] => $this->_tpl_vars['idx_option']):
?>
					<option <?php if ($this->_tpl_vars['orderby'] == $this->_tpl_vars['idx_option']['key']): ?> selected <?php endif; ?> value="<?php if ($this->_tpl_vars['cid']): ?>categories/<?php else: ?>tag/<?php endif; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['keyword'])) ? $this->_run_mod_handler('seotitle', true, $_tmp) : smarty_modifier_seotitle($_tmp)); ?>
/<?php if ($this->_tpl_vars['cid']): ?><?php echo $this->_tpl_vars['cid']; ?>
/<?php endif; ?>orderby-<?php echo $this->_tpl_vars['idx_option']['key']; ?>
/page1.html"><?php echo $this->_tpl_vars['idx_option']['desc']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
	</div>
	<?php elseif ($this->_tpl_vars['showTime']): ?>
	<div id="list_time">
		<?php echo $this->_tpl_vars['lang_time']; ?>
: &nbsp;
		<?php if ($this->_tpl_vars['time']): ?>
		<a <?php if ($this->_tpl_vars['time'] == 'today'): ?>class="selected"<?php endif; ?> href="feed/time-today/<?php echo $this->_tpl_vars['feed_id']; ?>
.html"><?php echo $this->_tpl_vars['lang_today']; ?>
</a> |
		<a <?php if ($this->_tpl_vars['time'] == 'this_week'): ?>class="selected"<?php endif; ?> href="feed/time-this_week/<?php echo $this->_tpl_vars['feed_id']; ?>
.html"><?php echo $this->_tpl_vars['lang_this_week']; ?>
</a> |
		<a <?php if ($this->_tpl_vars['time'] == 'this_month'): ?>class="selected"<?php endif; ?> href="feed/time-this_month/<?php echo $this->_tpl_vars['feed_id']; ?>
.html"><?php echo $this->_tpl_vars['lang_this_month']; ?>
</a> |
		<a <?php if ($this->_tpl_vars['time'] == 'all_time'): ?>class="selected"<?php endif; ?> href="feed/time-all_time/<?php echo $this->_tpl_vars['feed_id']; ?>
.html"><?php echo $this->_tpl_vars['lang_all_time']; ?>
</a>
		<?php else: ?>
			<?php echo $this->_tpl_vars['feed_title']; ?>

		<?php endif; ?>
	</div>
<?php endif; ?>

<div id="list_mode">
	<?php echo $this->_tpl_vars['lang_display']; ?>
: &nbsp;
	<a href="#" onclick="setDisplay('list'); return false;" id="button_list" title="<?php echo $this->_tpl_vars['lang_list_view']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_base']; ?>
images/list_view.gif"/></a>
	<a href="#" onclick="setDisplay('grid'); return false;" id="button_grid"title="<?php echo $this->_tpl_vars['lang_grid_view']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_base']; ?>
images/grid_view.gif"/></a>
</div>