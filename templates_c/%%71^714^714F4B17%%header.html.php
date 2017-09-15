<?php /* Smarty version 2.6.27, created on 2017-09-11 00:48:52
         compiled from header.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'header.html', 12, false),array('modifier', 'seotitle', 'header.html', 157, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php if ($this->_tpl_vars['facebook_comments'] == true): ?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<?php else: ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php endif; ?>
<head>
<base href="<?php echo $this->_tpl_vars['url_base']; ?>
" />
<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['url_base']; ?>
favicon.ico?845834354554" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo $this->_tpl_vars['meta_keywords']; ?>
"/>
<meta name="description" content="<?php if ($this->_tpl_vars['description_raw']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['description_raw'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 150) : smarty_modifier_truncate($_tmp, 150)); ?>
<?php else: ?><?php echo $this->_tpl_vars['meta_description']; ?>
<?php endif; ?>" />
<?php if ($this->_tpl_vars['facebook_app_id'] != ''): ?>
<meta property="fb:app_id" content="<?php echo $this->_tpl_vars['facebook_app_id']; ?>
">
<?php endif; ?>
<title><?php echo $this->_tpl_vars['website_name']; ?>
 - <?php echo $this->_tpl_vars['title_page']; ?>
</title>
<!--[if lte IE 6]>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['theme_base']; ?>
js/supersleight-min.js"></script>
<![endif]-->
<!--[if gte IE 5.5000]><![if lt IE 7.0000]>
  <script type="text/javascript" src="js/pngfix.js"></script>
  <![endif]><![endif]-->
<?php if ($this->_tpl_vars['player_image'] != ''): ?>
<link rel="image_src" type="image/jpeg" href="<?php echo $this->_tpl_vars['player_image']; ?>
" />
<?php endif; ?>
<link href="<?php echo $this->_tpl_vars['theme_base']; ?>
css/style.css?839455" rel="stylesheet" type="text/css" />
<?php if ($this->_tpl_vars['def_language'] == 'true'): ?>
<link href="<?php echo $this->_tpl_vars['theme_base']; ?>
css/order.css" rel="stylesheet" type="text/css" />
<?php else: ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['google_web_fonts_enabled'] == 'true'): ?>
<link href="http://fonts.googleapis.com/css?family=Philosopher&subset=cyrillic,latin" rel="stylesheet" type="text/css" />
<?php endif; ?>
<?php echo $this->_tpl_vars['js_vars']; ?>

<?php echo '
<script type="text/javascript">
'; ?>

	var lang_filter_on = '<?php echo $this->_tpl_vars['lang_filter_on']; ?>
';
	var lang_filter_off = '<?php echo $this->_tpl_vars['lang_filter_off']; ?>
';
	var lang_please_enter_guest_and_comment = '<?php echo $this->_tpl_vars['lang_please_enter_guest_and_comment']; ?>
';
	var lang_please_enter_the_image_code = '<?php echo $this->_tpl_vars['lang_please_enter_the_image_code']; ?>
';
	var lang_wrong_image_code = '<?php echo $this->_tpl_vars['lang_wrong_image_code']; ?>
';
	var lang_please_specify_video_title = '<?php echo $this->_tpl_vars['lang_please_specify_video_title']; ?>
';
	var lang_please_specify_video_desc = '<?php echo $this->_tpl_vars['lang_please_specify_video_desc']; ?>
';
	var lang_please_specify_video_keyword = '<?php echo $this->_tpl_vars['lang_please_specify_video_keyword']; ?>
';
	var lang_please_specify_video_file = '<?php echo $this->_tpl_vars['lang_please_specify_video_file']; ?>
';
	var lang_action_not_allowed	= '<?php echo $this->_tpl_vars['lang_action_not_allowed']; ?>
';
	var opt_safe_search = '<?php echo $this->_tpl_vars['opt_safe_search']; ?>
';
<?php echo '
</script>
'; ?>

<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/swfobject.js" type="text/javascript"></script>
<script src="/js/ajax.js" type="text/javascript"></script>
<script src="/js/common.min.js" type="text/javascript"></script>
<script src="/js/scrollTo.min.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['theme_base']; ?>
js/jquery.stylish-select.min.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['theme_base']; ?>
js/common.js?343454667" type="text/javascript"></script>
<?php if ($this->_tpl_vars['virtual_keyboard_enabled'] == 'true'): ?>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<?php echo '
<script type="text/javascript">
<!--
google.load("elements", "1", {
  packages: "keyboard"
});

var kbd;  // A Keyboard object.

function onVKLoad() {
// Create an instance on Keyboard.
	kbd = new google.elements.keyboard.Keyboard([google.elements.keyboard.LayoutCode.'; ?>
<?php echo $this->_tpl_vars['virtual_keyboard_layout_code']; ?>
<?php echo '], [\'search\']);
}

google.setOnLoadCallback(onVKLoad);
// -->
</script>
'; ?>

<?php endif; ?>
 <!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['theme_base']; ?>
css/png_fix.css" />
<![endif]-->
<?php echo $this->_tpl_vars['header_code']; ?>


<?php echo $this->_tpl_vars['skysa_bar_code']; ?>


</head>
<body>

<!-- OuterBg -->
<div id="outerWrapper">


  <div class="AutoWrapper">
    <!-- AutoWrapper -->
      <div class="header">
        <!-- Header Begins -->
        <div class="leftHeader">
          <!-- Left Header Begins -->

		  <?php if ($this->_tpl_vars['website_logo'] != ''): ?>
          <h1 class="Logo"><a href="<?php echo $this->_tpl_vars['website_url']; ?>
" title="<?php echo $this->_tpl_vars['website_title']; ?>
"><img src="<?php echo $this->_tpl_vars['website_logo']; ?>
" /></a></h1>
		  <?php endif; ?>
        </div>
        <div class="rightHeader">
          <!-- Right header Begins -->
          <div class="categoryBox">
            <div class="FloatLeft">
              <div class="width58px">
                <label for="my-dropdown"><?php echo $this->_tpl_vars['lang_categories']; ?>
</label>

              </div>
              <div class="FloatLeft selCont2" id="win-xp2"><?php  echo get_root_categories();  ?></div>
            </div>
          </div>

		  <ul class="topNav" align="center">
            <!-- Top navigation Begins -->
            <li id="blk_favorites" style="display:none;"><a href="#" onclick="custom_bookmark('<?php echo $this->_tpl_vars['website_name']; ?>
','<?php echo $this->_tpl_vars['website_url']; ?>
'); return false;" title="<?php echo $this->_tpl_vars['lang_add_to_favorites']; ?>
"><?php echo $this->_tpl_vars['lang_add_to_favorites']; ?>
</a> |</li>
            <li><?php echo $this->_tpl_vars['lang_online_watchers']; ?>
: <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['html_cache_dir'])."/guests.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></li>
          </ul>
        </div>

      </div>

	   <ul class="navigation">
        <!-- main navigation Begins -->
        <li><a <?php if ($this->_tpl_vars['feed_id'] == "" && $this->_tpl_vars['breadcrumb'] == ''): ?> class="active nav" <?php else: ?> class="nav" <?php endif; ?> href="<?php echo $this->_tpl_vars['website_url']; ?>
"><span><?php echo $this->_tpl_vars['lang_home']; ?>
</span></a></li>
		<?php unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['main_menu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<li><a <?php if ($this->_tpl_vars['main_menu'][$this->_sections['i']['index']]['on'] == 1): ?> class="active nav" <?php else: ?> class="nav" <?php endif; ?> <?php if ($this->_tpl_vars['main_menu'][$this->_sections['i']['index']]['new_window'] == 1): ?> target="_blank" <?php else: ?>  <?php endif; ?> href="<?php echo $this->_tpl_vars['main_menu'][$this->_sections['i']['index']]['url']; ?>
"><span><?php echo $this->_tpl_vars['main_menu'][$this->_sections['i']['index']]['title']; ?>
</span></a></li>
		<?php endfor; endif; ?>
      </ul>

	  <div class="contentBgBtm">
        <!-- main content Begins -->
        <div class="contentBg">
          <div class="contentBgTop">
			<div class="leaderboard" align="center">
				<?php echo $this->_tpl_vars['leaderboard_ads']; ?>

			</div>

			<?php if ($this->_tpl_vars['video_search_enabled'] != 'false'): ?>
            <div class="searchBox"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "box.search.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['breadcrumb'] != ''): ?>
			<div class="breadcrumb">
			<?php echo $this->_tpl_vars['breadcrumb']; ?>

			</div>
			<?php endif; ?>

			<!-- google_ad_section_start -->
			<div id="nav_title">

			<?php if ($this->_tpl_vars['feed_id']): ?> <a href="rss/feed/<?php echo $this->_tpl_vars['feed_id']; ?>
/" target="_blank" title='"<?php echo $this->_tpl_vars['title_inner']; ?>
" RSS Feed'><img class="rssIcon" src="<?php echo $this->_tpl_vars['theme_base']; ?>
images/rss.gif" border="0"></a>
			<?php elseif ($this->_tpl_vars['catid'] > 0): ?> <a href="rss/<?php echo $this->_tpl_vars['catid']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['title_inner'])) ? $this->_run_mod_handler('seotitle', true, $_tmp) : smarty_modifier_seotitle($_tmp)); ?>
/page1" target="_blank" title='"<?php echo $this->_tpl_vars['title_inner']; ?>
" RSS Feed'><img class="rssIcon" src="<?php echo $this->_tpl_vars['theme_base']; ?>
images/rss.gif" border="0"></a>
			<?php elseif ($this->_tpl_vars['keyword']): ?> <a href="rss/0/<?php echo $this->_tpl_vars['title_inner']; ?>
/page1" target="_blank" title='"<?php echo $this->_tpl_vars['title_inner']; ?>
" RSS Feed'><img class="rssIcon" src="<?php echo $this->_tpl_vars['theme_base']; ?>
images/rss.gif" border="0"></a>
			<?php elseif ($this->_tpl_vars['username']): ?><img class="usernameIcon" src="<?php echo $this->_tpl_vars['theme_base']; ?>
images/profile.gif" title="<?php echo $this->_tpl_vars['username']; ?>
 Channel" border="0">
			<?php elseif ($this->_tpl_vars['no_icon'] == 'yes'): ?>
			<?php else: ?><img class="playIcon" src="<?php echo $this->_tpl_vars['theme_base']; ?>
images/play.gif" title="<?php echo $this->_tpl_vars['title_inner']; ?>
 Now Playing..." border="0">
			<?php endif; ?>
			<h1>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['title_inner'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 60) : smarty_modifier_truncate($_tmp, 60)); ?>
&nbsp;
			</h1>
			</div>
			<!-- google_ad_section_end -->
			<?php if ($this->_tpl_vars['youtube_player']): ?>
				<?php if ($this->_tpl_vars['showHomePlayer'] && $this->_tpl_vars['enable_home_player']): ?>
				<div class="flashHeader">
					<div class="video_player" id="mediaspace"><a href="http://www.macromedia.com/go/getflashplayer"><?php echo $this->_tpl_vars['lang_get_flash_player']; ?>
</a> <?php echo $this->_tpl_vars['lang_to_see_video']; ?>
</div>
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "html/player.home.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					<div id="blk-home-player" style="<?php echo $this->_tpl_vars['str_player_frontcolor']; ?>
<?php echo $this->_tpl_vars['str_player_backcolor']; ?>
">
						<ul>
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
							<li>
							<a href="video/<?php echo $this->_tpl_vars['videos'][$this->_sections['i']['index']]['id']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('seotitle', true, $_tmp) : smarty_modifier_seotitle($_tmp)); ?>
.html">
								<img src="<?php echo $this->_tpl_vars['videos'][$this->_sections['i']['index']]['thumbnail']['2']; ?>
" onmouseout="mouseOutImage(this, '2', '666666', <?php echo $this->_tpl_vars['videos'][$this->_sections['i']['index']]['is_filtered']; ?>
)" onmouseover="mousOverImage(this,'<?php echo $this->_tpl_vars['videos'][$this->_sections['i']['index']]['id']; ?>
',1, '2','DD424E', <?php echo $this->_tpl_vars['videos'][$this->_sections['i']['index']]['is_filtered']; ?>
)" />
							</a>
							<a class="home-player-title" title="<?php echo $this->_tpl_vars['videos'][$this->_sections['i']['index']]['title']; ?>
" href="video/<?php echo $this->_tpl_vars['videos'][$this->_sections['i']['index']]['id']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('seotitle', true, $_tmp) : smarty_modifier_seotitle($_tmp)); ?>
.html"><?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20) : smarty_modifier_truncate($_tmp, 20)); ?>
</a>
							<span class="home-player-time"><?php echo $this->_tpl_vars['videos'][$this->_sections['i']['index']]['minute_format']; ?>
</span>
							<div class="home-player-desc"><?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['i']['index']]['description'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 80) : smarty_modifier_truncate($_tmp, 80)); ?>
</div>
							<div class="home-player-space"></div>

							</li>
							<?php endfor; endif; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
			<?php else: ?>

				<?php if ($this->_tpl_vars['showHomePlayer'] && $this->_tpl_vars['enable_home_player']): ?>
				<div class="flashHeader">
					<div class="video_player" id="mediaspace"><a href="http://www.macromedia.com/go/getflashplayer"><?php echo $this->_tpl_vars['lang_get_flash_player']; ?>
</a> <?php echo $this->_tpl_vars['lang_to_see_video']; ?>
</div>
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "html/player.home.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</div>
				<?php endif; ?>
			<?php endif; ?>