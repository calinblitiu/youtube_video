<?php

@session_start();
require_once("../../../config/config.php");
require_once("../../../config/config_advanced.php");
if( isset( $config['error_reporting'] ) )
{
	error_reporting($config['error_reporting']);
}
else
{
	error_reporting( 0 );
}

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	header('Location: ../../../admin/');
	die();
	/*echo '<script type="text/javascript">
	self.parent.location='.$config['website_url'].'admin;
	</script>';*/
}

@define("DS", DIRECTORY_SEPARATOR);
$act = isset($_REQUEST["act"]) ? $_REQUEST["act"] : '';
require_once("../../../config/db_config.php");
include ('categories.class.php');
include ('functions.php');
include ('module.php');
require_once("../../../lib/functions.php");

$categories = new categories;

$website_url	= $config['website_url'];
$is_write_category_files	= false;
$is_reload		= false;

$str_enable	= "";
$str_disable= "checked";
$str_attr_pd= "disabled";
$str_pd		= date('Y/m/d');
?><html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../../../admin/css/style.css?420590243905">
	<script type="text/javascript" src="../../../js/jquery.js"></script>
	<script type="text/javascript" src="../../../js/jquery/ui/ui.core.js"></script>
	<script type="text/javascript" src="../../../js/jquery/ui/ui.datepicker.js"></script>
	<link type="text/css" href="../../../js/jquery/themes/base/ui.all.css" rel="stylesheet" />
	<script type="text/javascript">
	<!--
		$(document).ready( function() {
			//ajaxList();
			//ajaxDropdown();
			//ajaxDropdown2();

			$('input[name=rd_enable]').change( function() {

				var choice = $('input[name=rd_enable]:checked').val();
				if ( choice == 1 ) {
					$('#txt_publishdate').removeAttr('disabled');
				} else if ( choice == 0 ) {
					$('#txt_publishdate').attr('disabled' , true);
				}
			});
			$("#txt_publishdate").datepicker({dateFormat: 'yy/mm/dd'});

			$('#rd_enable').click(function(){
				if ($('#rd_enable:checked').val() == 1) {
					$("#blk_date").show();
				} else {
					$("#blk_date").hide();
				}
			});

		});

		function ajaxList() {
			<? if ( isset($_GET['id'])) { ?> var ctg_id = <?=$_GET['id'];?>; <? } else {?>var ctg_id = 0;<? } ?>
			var paramString = 'ctg_id='+ ctg_id+'&task=menu';
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: paramString,
				success: function( return_data) {
				  $('#category-menu-list').html(return_data);
				}
			});
		}

		function ajaxDropdown() {
			var paramString = 'task=dropdown';
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: paramString,
				success: function( return_data) {
				  $('#category_id').html(return_data);
				}
			});
		}


		function ajaxDropdown2() {

			<? if ( isset($_GET['id'])) { ?> var ctg_id = <?=$_GET['id'];?>; <? } else {?>var ctg_id = 0;<? } ?>
			var paramString = 'ctg_id='+ ctg_id+'&task=dropdown2';

			$("#ajax-loader").show();
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: paramString,
				success: function( return_data) {

				  $('#parent').html(return_data);
				  $("#ajax-loader").hide();
				}
			});
		}

		function ajaxPost(playlist_id) {
			$("#block-loading").show();
			var author = $('#users_playlist').val();
			var dataString = 'author='+ author + '&playlist_id=' + playlist_id+'&ajaxcall=1';
			//alert (dataString);return false;
			  $.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				success: function(return_data) {
					$("#block-loading").hide();
					$('#playlist_id').html(return_data);

				}
			  });
			  return false;
		}
	-->
	</script>
</head>
<body>
<?

  include ('../../../admin/inc/aconfig.php');
  include_once ('../../../lib/db.php');
  include ('../../../config/config.php');
  include ('../../../config/admin_login.php');
  include ('../../../config/db_config.php');


  if(!dbTableExist(DB_PREFIX."categories")){

	echo "<font color=red>Ошибка:</font> Таблицы базы данных отсутствуют, пожалуйста, убедитесь, что модуль рубрик установлен правильно!";
	exit();
  }


	//	$categories_list = $categories->build_list(0);
		$hasError = false;
		// lets do some actions
		if(!isset($_REQUEST['act'])) $_REQUEST['act'] = "";

		$act = $_REQUEST["act"];

		if($act == "add") {

			if( $_POST['listing_source'] == 'keyword' ) {
				if ($_POST['keyword'] == '' ) {
					echo '<script>alert("Keyword cannot be empty!");</script>';
					$hasError = true;
				}
			} else if ( $_POST['listing_source'] == 'author' ) {
				if ( $_POST['author'] == '' ) {
					echo '<script>alert("Author cannot be empty!");</script>';
					$hasError = true;
				}
			} else if ( $_POST['listing_source'] == 'playlist_id' ) {
				if ( empty($_POST['playlist_id']) ) {
					echo '<script>alert("Playlist ID cannot be empty!");</script>';
					$hasError = true;
				}
			}

			if ( isset($_POST['rd_enable']) && $_POST['rd_enable'] == 1 ) {
				$rd_enable		= 1;
				$str_enable	= "checked";
				$str_disable	= "";
				$str_attr_pd	= "";
			} else {
				$rd_enable		= 0;
				$str_disable	= "checked";
				$str_attr_pd	= "disabled";
			}

			if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
				if($_POST["name"] == "") {
					//if($_POST["name"] == "")
						echo '<script>alert("Category name cannot be empty!");</script>';
					//else
						//echo '<script>alert("Keyword cannot be empty!");</script>';
				}else{
					if ( isset($_POST['txt_publishdate']) && trim($_POST['txt_publishdate']) == '' ) {
						$int_publish = 0;
					} else if ( isset($_POST['txt_publishdate']) && trim($_POST['txt_publishdate']) != '' ) {
						//echo $_POST['txt_publishdate'] ."<br />";
						//echo strtotime($_POST['txt_publishdate']) ."<br />";
						$int_publish = strtotime($_POST['txt_publishdate']);
						//echo $int_publish;
						//exit(0);

					}

					if ( !$aConfig["ADMIN_DEMO"] && !$hasError) {
						$lastCatId = $categories->add_new($_POST['parent'] , $_POST["name"] , $_POST["desc"] , $_POST["keyword"], $_POST['listing_source'], $_POST['author'], $_POST['users_playlist'], $_POST['playlist_id'], $rd_enable, $int_publish);
						$msg = "Category was inserted successfully into database. <a href='../../../categories/".SeoTitleEncode( $_POST["name"] )."/${lastCatId}/page1.html' target='_blank'>Click here to view</a>";
						$is_write_category_files	= true;
					}

				}
			}
			$is_reload = true;

		}else if($act == "delete") {
			if ( !$aConfig["ADMIN_DEMO"] ) {
				$categories->delete($_GET["id"]);
				$msg="Category and Sub-Categories were successfully deleted";
				$is_write_category_files	= true;
			}
		}else if($act == "update") {
			$hasError = false;
			if ( trim($_POST['name']) == '' ) {
				$hasError = true;
					echo '<script>alert("Category name cannot be empty!");</script>';
			}

			if( $_POST['listing_source'] == 'keyword' ) {
				if ($_POST['keyword'] == '' ) {
					$hasError = true;
					echo '<script>alert("Keyword cannot be empty!");</script>';
				}
			} else if ( $_POST['listing_source'] == 'author' ) {
				if ( $_POST['author'] == '' ) {
					$hasError = true;
					echo '<script>alert("Author cannot be empty!");</script>';
				}
			} else if ( $_POST['listing_source'] == 'playlist_id' ) {
				if ( empty($_POST['playlist_id']) ) {
					$hasError = true;
					echo '<script>alert("Playlist ID cannot be empty!");</script>';
				}
			}

			if ( isset($_POST['rd_enable']) && $_POST['rd_enable'] == 1 ) {
				$rd_enable		= 1;
				$str_enable	= "checked";
				$str_disable	= "";
				$str_attr_pd	= "";
			} else {
				$rd_enable		= 0;
				$str_disable	= "checked";
				$str_attr_pd	= "disabled";
			}

			$str_pd		= $_POST['txt_publishdate'];

			if ( !$hasError ) {
				if ( isset($_POST['txt_publishdate']) && trim($_POST['txt_publishdate']) == '' ) {
					$int_publish = 0;
				} else if ( isset($_POST['txt_publishdate']) && trim($_POST['txt_publishdate']) != '' ) {
					$int_publish = strtotime($_POST['txt_publishdate']);
				}

				if ( !$aConfig["ADMIN_DEMO"] ) {
					$categories->update($_POST["id"] , $_POST["parent"] , $_POST["name"] , $_POST["desc"] , $_POST["keyword"], 0, $_POST["listing_source"], $_POST["author"], $_POST["users_playlist"], $_POST['playlist_id'], $rd_enable, $int_publish  );

					$msg="Category was updated successfully!";

					$is_write_category_files	= true;
				}
			}

			$is_reload = true;

		}

  if(!isset($_GET["id"])) $_GET["id"] = 0;

		$ctg_id = $_GET["id"];
		$ctg_id	= isset($_POST['id']) ? $_POST['id'] : $ctg_id;

		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";


		$categories_list = $categories->build_list(0, "", 0);

		if ( $aConfig["ADMIN_DEMO"] && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			echo '<div class="error">Categories Not Saved (Demo Mode)</div>';
		} else if(isset($msg)) {

			echo'<div style="margin:5px 0 5px 10px; font-size:15px; color:green;">'.$msg.'</div>';

		}
		?>

		<div class="gradientBg" style="width:210px; float:left; margin:10px;">
		<?

		if(sizeof($categories_list) != 0) {
		?>
			<table width="190" border="0" cellpadding="2" cellspacing="0">
 		 	<tr>
    		<td width="72" colspan="2"><select class="input" name="category_id" id="category_id" style="width:206px;">
			<option value="">Loading...</option>
            </select></td>
			</tr><tr>
    		<td width="0"><input class="button" type="button" name="Button" value="Edit" onClick="location='?act=updateForm&id='+document.getElementById('category_id').value;"></td>
    		<td width="0"><input class="button" type="button" name="Button" value="Delete" onClick="location='?act=delete&id='+document.getElementById('category_id').value;"></td>
  			</tr>
			</table>
		<?
		}
		?>
		<br>
		<div id="category-menu-list">
		Loading ...
		</div>
		<div style="text-align:center;margin-top:10px;"> <input type="button" onclick="javascript:parent.document.getElementById('frame_categories').src ='../lib/modules/categories/admin.php?act=add';" value="Add New Category" name="Button" class="button" style="width:150px;" /></div>
		<?

		/*$categories->HtmlTree = array(
		"header" => "<div style=\"padding:5px;height:280px; overflow-x:hidden; overflow-y:auto;\"><table width=180px border=0 cellpadding=2 cellspacing=2>",
		"BodyUnselected" => '<tr><td> [prefix] &raquo; <a href="?act=updateForm&id=[id]"><font color=#313131>[name]</font></a></td></tr>',
		"BodySelected" => '<tr><td bgcolor="#CD2532"> [prefix] &bull; <a href="?act=updateForm&id=[id]"><strong><font color="#FFFFFF">[name]</font></strong></a></td></tr>',
		"footer" => '</table></div>',
		);

		$catMenu = $categories->html_output($ctg_id);

		echo $catMenu;*/
?>
		</div>
<?



		if($act == "updateForm" || $act == "update") {

			$cat = $categories->fetch($ctg_id);

			if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
				if ( $cat['enable_publishdate'] == 1 ) {
					$str_enable = 'checked';
					$rd_enable	= 1;
					$str_disable= '';
					$str_attr_pd = '';
					$str_pd		= date('Y/m/d', $cat['publishdate']);
				} else {
					$rd_enable	= 0;
					$str_disable= 'checked';
					$str_attr_pd = 'disabled';
				}
			}

		?>
			<div class="gradientBg" style="margin:10px 10px 0 10px; float:right;width:600px;">
			<form name="form1" method="post" action="">

          	<table width="570" align="center" border="0" cellpadding="2" cellspacing="0">
    		<tr>
      			<td colspan="3"><h1 style="color:#EF952C">Edit Category:</h1></td>
    		</tr>
			<tr><td colspan="3"><br></td></tr>
    		<tr>
      			<td width="90">Child Of</td>
      			<td width="10"></td>
      			<td width="300">
	  				<select name="parent" id="parent" class="input">
        			<option value="">Loading...</option>
      				</select>
					<img src="../../../admin/images/ajax-loader.gif" id="ajax-loader" style="display:none;" />
	  			</td>
    		</tr>
    		<tr>
      			<td>Name</td>
      			<td></td>
      			<td><input class="input" name="name" type="text" id="name" value="<?=$cat["c_name"]?>" size="20"></td>
    		</tr>
    		<tr>
      			<td>Description</td>
      			<td></td>
      			<td><textarea class="input" name="desc" cols="32" rows="3" id="desc"><?=$cat["c_desc"];?></textarea></td>
    		</tr>
			<tr>
			  <td>Listing Source</td>
			  <td></td>
			  <td>
				<select class="input" name="listing_source" id="listing_source" onChange="javascript:display_field();">
					<option value="keyword" <? if ($cat['c_listing_source'] == 'keyword') echo 'selected'; ?>>Keyword</option>
					<option value="author"<? if ($cat['c_listing_source'] == 'author') echo 'selected'; ?>>Author</option>
					<!-- option value="users_playlist"<? if ($cat['c_listing_source'] == 'users_playlist') echo 'selected'; ?>>User's Playlist</option -->
					<option value="playlist_id"<? if ($cat['c_listing_source'] == 'playlist_id') echo 'selected'; ?>>Playlist ID</option>
				</select>
			</td>
			</tr>
			<tr>
			<td colspan="2">&nbsp;</td>
		<td>
			<div id="row_keyword">
				<div style="float:left;width:0px;"><!-- Keyword<em style="color:red;">*</em -->&nbsp;</div>
				<div><input class="input" name="keyword" type="text" id="keyword" value="<?=htmlspecialchars($cat["c_keyword"], ENT_QUOTES, 'UTF-8')?>" size="30"></div>
			</div>
			<div id="row_author">
				<div style="float:left;width:0px;"><!-- Author<em style="color:red;">*</em -->&nbsp;</div>
				<div><input class="input" name="author" type="text" id="author" value="<?=$cat["c_user_videos"]?>" size="30"></div>
			</div>
			<div id="row_users_playlist" style="margin-bottom:5px;">
				<div style="float:left;width:110px;">Author<em style="color:red;">*</em></div>
				<div><input class="input" name="users_playlist" type="text" id="users_playlist" value="<?=$cat["author_username"]?>" style="width:290px;" onblur="javascript:ajaxPost(this.value);"></div>
			</div>
			<div id="row_playlist_id" style="margin-bottom:5px;">
				<div style="float:left;width:110px;">Playlist ID<em style="color:red;">*</em></div>
				<div><select class="input" name="playlist_id" id="playlist_id" style="width:290px;">
	  <option value="0">Please choose your playlist</option>
	  </select><span id="block-loading" style="display:none;"><img  style="margin-left:10px;" src="<?=$website_url;?>/admin/images/mozilla_blu.gif" /></span>
	  <br /><a href="#" onclick="javascript:ajaxPost(document.form1.users_playlist.value);">Reload Playlist</a></div>
			</div>
		</td>
	</tr>

    		<tr style="color:#000000;" valign="top">
			  <td>Publish Date</td>
			  <td>&nbsp;</td>
			  <td>
				<? /* <div><input type="radio" name="rd_enable" id="rd_enable" value="1" <?=$str_enable?> /> Enable</div>
				<div>Date <input class="input" type="text" name="txt_publishdate" id="txt_publishdate" value="<?=$str_pd?>" <?=$str_attr_pd?> /></div>
				<div><input type="radio" name="rd_enable" id="rd_enable" value="0" <?=$str_disable?> /> Disable</div> */ ?>
				<div><input type="checkbox" name="rd_enable" id="rd_enable" value="1" <?=$str_enable?> /> Enable</div>
				<div id="blk_date" <? if ( !isset($rd_enable) || $rd_enable == 0 ) { echo 'style="display:none;"'; }?>>Date <input class="input" class="input" type="text" name="txt_publishdate" id="txt_publishdate" value="<?=$str_pd?>" <?=$str_attr_pd?> /></div>

			</td>
			</tr>

			<tr><td colspan="3"><br></td></tr>
    		<tr>

      			<td colspan="3">
					<div align="center">
        			<input name="act" type="hidden" value="update">
					<input name="id" type="hidden" value="<?=$ctg_id?>">
					<input class="button" type="submit" name="Submit" value="Save">
					&nbsp;&nbsp;<a href="?act=delete&id=<?=$ctg_id?>">Delete</a>&nbsp; | &nbsp;<a href="?act=cancel">Cancel</a>

      				</div>
					</td>

    		</tr>
  			</table>

			</form>
			</div>
		<?
		}else{
		?>

<div class="gradientBg" style="margin:10px 10px 0 10px; width:600px;float:right;">

<form name="form1" method="post" action="">

  <table width="600"  border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <td colspan="3"><H2 style="color:#EF952C"> Add New Category </H2></td>
    </tr>
	<tr><td colspan="3"><br></td></tr>
    <tr>
      <td width="80">Child Of</td>
      <td width="10"></td>
      <td width="300"><select class="input" name="parent" id="parent">
		<option value="">Loading...</option>
      </select>
	  <img src="../../../admin/images/ajax-loader.gif" id="ajax-loader" style="display:none;" />
	  </td>
    </tr>
    <tr>
      <td>Name<em style="color:red;">*</em></td>
      <td></td>
      <td><input class="input" name="name" type="text" id="name" size="20"></td>
    </tr>
    <tr>
      <td>Description</td>
      <td></td>
      <td><textarea class="input" name="desc" cols="32" rows="3" id="desc"></textarea></td>
    </tr>
	<tr>
      <td>Listing Source</td>
      <td></td>
      <td>
		<select class="input" name="listing_source" id="listing_source" onChange="javascript:display_field();">
			<option value="keyword">Keyword</option>
			<option value="author">Author</option>
			<!-- option value="users_playlist">User's Playlist</option -->
			<option value="playlist_id">Playlist ID</option>
		</select>
	</td>
    </tr>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td>
			<div id="row_keyword">
				<div style="float:left;width:0px;">&nbsp;</div>
				<div><input class="input" name="keyword" type="text" id="keyword" value="" size="30"></div>
			</div>
			<div id="row_author">
				<div style="float:left;width:0px;">&nbsp;</div>
				<div><input class="input" name="author" type="text" id="author" value="" size="30"></div>
			</div>

			<div id="row_users_playlist" style="margin-bottom:5px;">
				<div style="float:left;width:110px;">Author<em style="color:red;">*</em></div>
				<div><input class="input" name="users_playlist" type="text" id="users_playlist" value="" style="width:290px;" onblur="javascript:ajaxPost(this.value);"></div>
			</div>

			<div id="row_playlist_id" style="margin-bottom:5px;">
				<div style="float:left;width:110px;">Playlist ID<em style="color:red;">*</em></div>
				<div><select class="input" name="playlist_id" id="playlist_id" style="width:290px;">
	  <option value="0">Please choose your playlist</option>
	  </select><span id="block-loading" style="display:none;"><img  style="margin-left:10px;" src="<?=$website_url;?>/admin/images/mozilla_blu.gif" /></span>
	  <br /><a href="#" onclick="javascript:ajaxPost(document.form1.users_playlist.value);">Reload Playlist</a></div>
			</div>
		</td>
	</tr>
	<tr style="color:#000000;" valign="top">
			  <td>Publish Date</td>
			  <td>&nbsp;</td>
			  <td>
				<div><input type="checkbox" name="rd_enable" id="rd_enable" value="1" <?=$str_enable?> /> Enable</div>
				<div id="blk_date" <? if ( !isset($rd_enable) || $rd_enable == 0 ) { echo 'style="display:none;"'; }?>>Date <input class="input" class="input" type="text" name="txt_publishdate" id="txt_publishdate" value="<?=$str_pd?>" <?=$str_attr_pd?> /></div>
				<? /*<div><input type="radio" name="rd_enable" id="rd_enable" value="0" <?=$str_disable?> /> Disable</div> */?>
			</td>
			</tr>
    <tr>
      <td colspan="3"><div align="center">
        <input name="act" type="hidden" value="add">
        <input class="button" type="submit" name="Submit" value="Add">
      </div></td>
    </tr>
  </table>

</form>

</div>


<?
}

?>
<script type="text/javascript">
			<!--
				function display_field() {
					var selected_val = document.getElementById('listing_source').value;
					//alert( selected_val );
					if ( selected_val == 'author' ) {

						document.getElementById('row_users_playlist').style.display = "none";
						document.getElementById('row_keyword').style.display = "none";
						document.getElementById('row_author').style.display = "block";
						document.getElementById('row_playlist_id').style.display = "none";
					} else if ( selected_val == 'keyword' ) {
						document.getElementById('row_users_playlist').style.display = "none";
						document.getElementById('row_keyword').style.display = "block";
						document.getElementById('row_author').style.display = "none";
						document.getElementById('row_playlist_id').style.display = "none";
					} else if ( selected_val == 'users_playlist') {
						document.getElementById('row_users_playlist').style.display = "block";
						document.getElementById('row_keyword').style.display = "none";
						document.getElementById('row_author').style.display = "none";
						document.getElementById('row_playlist_id').style.display = "none";
					} else if ( selected_val == 'playlist_id') {
						document.getElementById('row_playlist_id').style.display = "block";
						document.getElementById('row_keyword').style.display = "none";
						document.getElementById('row_author').style.display = "none";
						document.getElementById('row_users_playlist').style.display = "block";
					}
				}

				display_field();
			-->
			</script>
<?php

 if ( $is_write_category_files && $config["categories_cache_enable"]) {
	$path_pm		= str_replace(DS."lib".DS."modules".DS."categories", "", dirname(__FILE__));
	$cache_file		= $path_pm.DS.$config['html_cache_dir']."categories_cache.php";

	$cache_ids		= $path_pm.DS."cache".DS."data".DS."category_ids.php";
	if ( file_exists($cache_ids) ) {
		unlink($cache_ids);
	}
	if ( file_exists($cache_file) ) {
		unlink($cache_file);

		$str_categories_cache = create_categories_cache(1, false);


		$handle = fopen($cache_file, 'w+');
		fwrite($handle, $str_categories_cache);
		fclose($handle);
	}

 }

function lastCatId()
{
	$query = "SELECT id FROM `".DB_PREFIX."categories` ORDER BY `id`  DESC LIMIT 1";
	$res = dbQuery($query, $silence = true);;
	$ID = mysql_fetch_row($res);

	return $ID[0];
}

if($act == "updateForm") {
	if ( isset($cat['author_username']) && $cat['author_username'] != '' ) {
		$playlist = isset($cat['c_playlist_id']) ? $cat['c_playlist_id'] : '';
	?>
	<script type="text/javascript">
	ajaxPost('<?=$playlist?>');
	</script>
	<?
	}
	?>
	<script type="text/javascript">
	ajaxList();
	ajaxDropdown();
	ajaxDropdown2();
	</script>
	<?
} else if ( $act == "cancel" || $act == "delete" ) { ?>
	<script type="text/javascript">
	ajaxList();
	ajaxDropdown();
	ajaxDropdown2();
	</script>
<?
}

if ( $is_reload ) {
?>
	<script type="text/javascript">
	ajaxList();
	ajaxDropdown();
	ajaxDropdown2();
	</script>
<?
}

?>


</body>
</html>