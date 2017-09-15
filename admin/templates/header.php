<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="<?=$config["website_url"]?>favicon.ico?8458" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?=$config["website_name"]?> - Administration</title>
<script type="text/javascript">
<!--
	var theme_base = '<?=$config['website_url']."admin";?>';
-->
</script>
<link rel="stylesheet" href="css/style.css?495400540" TYPE="text/css" MEDIA="screen">
<link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'>

<script type="text/javascript">
<!--
	/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */
  var website_url = '<?php echo $config['website_url']?>'; // For determining cookie path
	document.write('<style type="text/css">.tabber{display:none;}<\/style>');
	/*==================================================
	  Set the tabber options (must do this before including tabber.js)
	  ==================================================*/
	var tabberOptions = {
	  'cookie':"tabber", /* Name to use for the cookie */
	  'onLoad': function(argsObj)
	  {
		var t = argsObj.tabber;
		var i;
		/* Optional: Add the id of the tabber to the cookie name to allow
		   for multiple tabber interfaces on the site.  If you have
		   multiple tabber interfaces (even on different pages) I suggest
		   setting a unique id on each one, to avoid having the cookie set
		   the wrong tab.
		*/
		if (t.id) {
		  t.cookie = t.id + t.cookie;
		}
		/* If a cookie was previously set, restore the active tab */
		i = parseInt(getCookie(t.cookie));
		if (isNaN(i)) { return; }
		t.tabShow(i);
		//alert('getCookie(' + t.cookie + ') = ' + i);
	  },
	  'onClick':function(argsObj)
	  {
		var c = argsObj.tabber.cookie;
		var i = argsObj.index;
		//alert('setCookie(' + c + ',' + i + ')');
		setCookie(c, i);
	  }
	};
-->
</script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="combine.php?type=javascript&files=common.js,jscolor.js,jquery.pstrength-min.1.2.js,tabber-minimized.js,jquery.filestyle.mini.js,jquery.simplemodal.js,modalbox.js,urlEncode.js"></script>
<script type="text/javascript" src="../js/common.js"></script>


<script type="text/javascript">
<!--
	$(document).ready( function() {
		$('#block_keyword_list').hide();
		
		
		$('.password').pstrength();
		$('.new_menu_link').click( function() {
			$('#blk_new_menu_link').toggle();
		});
		
		$('.new_menu_link2').click( function() {
			$('#blk_new_menu_link2').toggle();
		});
		
		$("#twitter_search_mode").change( function(){  
			$("#twitter_key").fadeOut('slow').fadeIn('slow'); 
			var twitter_mode = $(this).val();
			if ( twitter_mode == 'keyword' ) {
				<? if ($config["rd_twitter_type"] == 'keyword' ) { ?>
				$("#twitter_key").val('<?=$config["twitter_key"]?>');
				<? } else { ?>
				$("#twitter_key").val('');
				<? } ?>
			} else if  ( twitter_mode = 'author' ) {
				<? if ($config["rd_twitter_type"] == 'author' ) { ?>
				$("#twitter_key").val('<?=$config["twitter_key"]?>');
				<? } else { ?>
				$("#twitter_key").val('');
				<? } ?>
			}
			
		});
		
		$("#rd_flickr_type").change( function(){  
			$("#flickr_key").fadeOut('slow').fadeIn('slow'); 
			var flickr_mode = $(this).val();
			if ( flickr_mode == 'keyword' ) {
				<? if ($config["rd_flickr_type"] == 'keyword' ) { ?>
				$("#flickr_key").val('<?=$config["flickr_key"]?>');
				<? } else { ?>
				$("#flickr_key").val('');
				<? } ?>
			} else if  ( flickr_mode = 'user_id' ) {
				<? if ($config["rd_flickr_type"] == 'user_id' ) { ?>
				$("#flickr_key").val('<?=$config["flickr_key"]?>');
				<? } else { ?>
				$("#flickr_key").val('');
				<? } ?>
			}
			
		});
		
		
		
		
		
		
	}); 
	
-->
</script>

</head>
<body class="mainBody">
<form action="index.php" method="post" name="config" id="config" enctype="multipart/form-data"> 
<div class="wrap">
<div class="header">
	<a href="<?=$config["website_url"]?>admin"><img src="images/logo.png" title="<?=$config["website_name"]?>" class="logo" border="0"></a>
	<div class="topRightWrap">
		<div class="topLinks">
			<a href="<?=$config["website_url"]?>admin">Administration</a> - <a href="<?=$config["website_url"]?>" target="_blank">View Site</a> - <a href="<?=$config["website_url"]?>admin/?logout=yes">Logout</a>
		</div>
		<div style="clear:all;"></div>
		<?php if ( !empty( $str_version ) ) { ?>
		<div class="version" style="overflow:none;"><?php echo $str_version;?></div>
		<?php } ?>
		<div style="clear:all;"></div>
	</div>
	<div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>