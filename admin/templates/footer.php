<div class="footer">
	<div class="copyright">
		Copyright &copy; 2008 - <?=date("Y")?> <a href="<?=$config["website_url"]?>"><?=$config["website_name"]?></a> All Rights Reserved
	</div>
	<div class="poweredby">
	</div>
</div>
<script type="text/javascript">
<!--
function get_default_category_ajax() {
	$('#default_category_id').html('<option value="0">Loading...</option>');
	$("#ajax-loader").show();
	$.ajax({
		type: "POST",
		url: "<?=$config['website_url']?>lib/modules/categories/ajax.php",
		data: "task=dropdown&default_category_id=<?=$config['default_category_id']?>&default=1",
		success: function( return_data) {
		  $('#default_category_id').html(return_data);
		  $("#ajax-loader").hide();
		 // alert(return_data);
		 //setTimeout("ajaxDropdown2()",5000);
		//setTimeout("ajaxList()",5000);
		 //setTimeout("ajaxDropdown()",5000);

		}
	});
}

<?php /*
function ajaxDropdown2() {

	<? if ( isset($_GET['id'])) { ?> var ctg_id = <?=$_GET['id'];?>; <? } else {?>var ctg_id = 0;<? } ?>
	var paramString = 'ctg_id='+ ctg_id+'&task=dropdown2';

	if ( window.frames['frame_categories'].document.getElementById("ajax-loader") != null ) {
	window.frames['frame_categories'].document.getElementById("ajax-loader").style.display = 'block';
	$.ajax({
		type: "POST",
		url: "<?=$config['website_url']?>lib/modules/categories/ajax.php",
		data: paramString,
		success: function( return_data) {
			window.frames['frame_categories'].document.getElementById("parent").innerHTML = return_data;
			window.frames['frame_categories'].document.getElementById("ajax-loader").style.display = 'none';
		}
	});
	}
}

function ajaxList() {
	<? if ( isset($_GET['id'])) { ?> var ctg_id = <?=$_GET['id'];?>; <? } else {?>var ctg_id = 0;<? } ?>
	if ( window.frames['frame_categories'].document.getElementById("category-menu-list") != null ) {
	var paramString = 'ctg_id='+ ctg_id+'&task=menu';
	$.ajax({
		type: "POST",
		url: "<?=$config['website_url']?>lib/modules/categories/ajax.php",
		data: paramString,
		success: function( return_data) {
		  window.frames['frame_categories'].document.getElementById('category-menu-list').innerHTML = return_data;
		}
	});
	}
}

function ajaxDropdown() {
	var paramString = 'task=dropdown';
	if ( window.frames['frame_categories'].document.getElementById("category_id") != null ) {
	$.ajax({
		type: "POST",
		url: "<?=$config['website_url']?>lib/modules/categories/ajax.php",
		data: paramString,
		success: function( return_data) {
		  window.frames['frame_categories'].document.getElementById('category_id').innerHTML = return_data;
		}
	});
	}
}
*/ ?>

var alert_keyword_match = 0;
var show_browse_tags = 0;

alert_keyword_match = setTimeout ( "showKeywordMatch()", 10000 );
show_browse_tags = setTimeout ( "showBrowseTags()", 10000 );
show_upload_video_log = setTimeout ( "uploadVideoLog()", 10000 );
function showKeywordMatch() {

	if ( document.getElementById('block_keyword_matches') != 'undefined' ) {
		ajaxGet('ajax/tools.php?task=keyword_matches', 'block_keyword_matches', false, 'block-loading-keyword-matches');
		clearTimeout ( alert_keyword_match );
	}
}

function showBrowseTags() {

	if ( document.getElementById('block_browse_tags') != 'undefined' ) {
		ajaxGet('ajax/tools.php?task=browse_tags', 'block_browse_tags', false, 'block-loading-browse-tags');
		clearTimeout ( show_browse_tags );
	}
}

function uploadVideoLog() {

	if ( document.getElementById('block_video_upload_log') != 'undefined' ) {
		ajaxGet('ajax/tools.php?task=upload_video_log', 'block_video_upload_log', false, 'block-loading-video-upload-log');
		clearTimeout ( show_upload_video_log );
	}
}

$(document).ready(function() {

  setTimeout("logo_resize()",1000);
  setTimeout("get_default_category_ajax()",15000);

<?if( isset( $_GET['login'] ) ){?>

  setTimeout("$('#loading_msg').slideUp(600);",7000);

<?}?>
});
-->
</script>