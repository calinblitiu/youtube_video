<?php /* Smarty version 2.6.27, created on 2017-09-11 00:48:52
         compiled from box.search.html */ ?>
<form method="get" action="list.php" name="frmSearch">
<div class="searchLeft">
	<div class="searchLeftInner">
		<div class="width84px">
		<label class="searchTxt" for="search"><?php echo $this->_tpl_vars['lang_search']; ?>
</label>
		</div>
		<input type="text" class="inputBg" id="search" title="<?php echo $this->_tpl_vars['lang_when_you_need_to_type_english']; ?>
" name="q" onfocus="if(this.value=='<?php echo $this->_tpl_vars['lang_search_for_videos']; ?>
') this.value='';" onblur="if(this.value=='') this.value='<?php echo $this->_tpl_vars['lang_search_for_videos']; ?>
';" value="<?php if ($this->_tpl_vars['keyword']): ?><?php echo $this->_tpl_vars['keyword']; ?>
<?php else: ?><?php echo $this->_tpl_vars['lang_search_for_videos']; ?>
<?php endif; ?>"  alt="<?php echo $this->_tpl_vars['lang_search_for_videos']; ?>
" />
	</div>

	<div class="buttonwrapper" id="filter">
		<?php if ($this->_tpl_vars['default_filter_value'] == 'on'): ?>
		<a id="search-filter-status-button" class="ovalbutton" href="javascript:setSafeSearchPref();"><span><?php echo $this->_tpl_vars['lang_filter_on']; ?>
</span></a> 
		<?php else: ?>
		<a id="search-filter-status-button" class="ovalbutton_off" href="javascript:setSafeSearchPref();"><span><?php echo $this->_tpl_vars['lang_filter_off']; ?>
</span></a> 
		<?php endif; ?>
	</div>
    <div class="buttonwrapper2"> <a class="ovalbutton2" id="searchbutton" href="javascript:submit_search();"><span><?php echo $this->_tpl_vars['lang_go']; ?>
</span></a> </div>
	<input id="search-filter-hiddenInput" type="hidden" value="<?php echo $this->_tpl_vars['filterValue']; ?>
" name="filter">
</div>
</form>