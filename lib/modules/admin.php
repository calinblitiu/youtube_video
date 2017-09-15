<?php

@session_start();
$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	header('Location: ../../../admin/');
	die();
}
$act = isset($_REQUEST["act"]) ? $_REQUEST["act"] : '';
include ('categories.class.php');
include ('functions.php');

$categories = new categories;
require_once("../../../config/config.php");
$website_url	= $config['website_url'];
$is_write_category_files	= false;
?>
<html>
<head>
	<link rel="stylesheet" href="../../../admin/css/style.css">
	<script type="text/javascript" src="../../../js/jquery.js"></script>
	<script type="text/javascript">
	<!--
		$(document).ready( function() {
			ajaxList();
			ajaxDropdown();
			ajaxDropdown2();
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
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: paramString,
				success: function( return_data) {
				  $('#parent').html(return_data);
				}
			});
		}

		function ajaxPost(playlist_id) {
			$("#block-loading").show();
			var author = $('#users_playlist').val();
			var dataString = 'author='+ author + '&playlist_id=' + playlist_id;
			//alert (dataString);return false;
			  $.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				success: function( return_data) {
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

  if($aConfig["ADMIN_DEMO"] && ($_REQUEST["act"] == "add" || $_REQUEST["act"] == "delete" || $_REQUEST["act"] == "update")) {

		echo '<script>parent.location.href = \''.$config["website_url"].'admin/index.php?admindemo=true\';</script>';
		die();
  }


  if(!dbTableExist("categories")){

	echo "<font color=red>Error:</font> Database Tables Missing, please make sure that the Categories module is installed properly!";
	exit();
  }


	//	$categories_list = $categories->build_list(0);

		// lets do some actions
		if(!isset($_REQUEST['act'])) $_REQUEST['act'] = "";

		$act = $_REQUEST["act"];

		if($act == "add") {

			if( $_POST['listing_source'] == 'keyword' ) {
				if ($_POST['keyword'] == '' ) echo '<script>alert("Keyword cannot be empty!");</script>';
			} else if ( $_POST['listing_source'] == 'author' ) {
				if ( $_POST['author'] == '' ) echo '<script>alert("Author cannot be empty!");</script>';
			} else if ( $_POST['listing_source'] == 'playlist_id' ) {
				if ( empty($_POST['playlist_id']) ) echo '<script>alert("Playlist ID cannot be empty!");</script>';
			}

			if($_POST["name"] == "") {
				//if($_POST["name"] == "")
					echo '<script>alert("Category name cannot be empty!");</script>';
				//else
					//echo '<script>alert("Keyword cannot be empty!");</script>';
			}else{

				$categories->add_new($_POST['parent'] , $_POST["name"] , $_POST["desc"] , $_POST["keyword"], $_POST['listing_source'], $_POST['author'], $_POST['users_playlist'], $_POST['playlist_id'] );
				$msg="Category was inserted successfully into database";
				$is_write_category_files	= true;
			}

		}else if($act == "delete") {

			$categories->delete($_GET["id"]);
			$msg="Category and Sub-Categories where successfully deleted";
			$is_write_category_files	= true;

		}else if($act == "update") {
			$hasError = false;
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

			if ( !$hasError ) {

				$categories->update($_POST["id"] , $_POST["parent"] , $_POST["name"] , $_POST["desc"] , $_POST["keyword"], 0, $_POST["listing_source"], $_POST["author"], $_POST['playlist_id'] );

				$msg="Category was updated successfully!";

				$is_write_category_files	= true;
			}

		}

  if(!isset($_GET["id"])) $_GET["id"] = 0;

		$ctg_id = $_GET["id"];
		$ctg_id	= isset($_POST['id']) ? $_POST['id'] : $ctg_id;

		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";


		$categories_list = $categories->build_list(0);

		if(isset($msg)) {

			echo'<div style="margin:5px 0 5px 10px; font-size:15px; color:green;">'.$msg.'</div>';

		}
		?>

		<div class="gradientBg" style="width:210px; float:left; margin:10px;">
		<?

		if(sizeof($categories_list) != 0) {
		?>
			<table width="190" border="0" cellpadding="2" cellspacing="0">
 		 	<tr>
    		<td width="72" colspan="2"><select name="category_id" id="category_id" style="width:206px;">
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

		?>
			<div class="gradientBg" style="margin:10px 10px 0 10px; float:right;">
			<form name="form1" method="post" action="">

          	<table width="400"  border="0" cellpadding="2" cellspacing="0">
    		<tr>
      			<td colspan="3" bgcolor="#CD2532"><strong><font color="#ffffff">Edit Category:</font></strong></td>
    		</tr>
			<tr><td colspan="3"><br></td></tr>
    		<tr>
      			<td width="13%">Child Of</td>
      			<td width="1%"></td>
      			<td width="86%">
	  				<select name="parent" id="parent">

      				</select>
	  			</td>
    		</tr>
    		<tr>
      			<td>Name</td>
      			<td></td>
      			<td><input name="name" type="text" id="name" value="<?=$cat["c_name"]?>" size="20"></td>
    		</tr>
    		<tr>
      			<td>Description</td>
      			<td></td>
      			<td><textarea name="desc" cols="32" rows="3" id="desc"><?=$cat["c_desc"]?></textarea></td>
    		</tr>
			<tr>
			  <td>Listing Source</td>
			  <td></td>
			  <td>
				<select name="listing_source" id="listing_source" onChange="javascript:display_field();">
					<option value="keyword" <? if ($cat['c_listing_source'] == 'keyword') echo 'selected'; ?>>Keyword</option>
					<option value="author"<? if ($cat['c_listing_source'] == 'author') echo 'selected'; ?>>Author</option>
					<!-- option value="users_playlist"<? if ($cat['c_listing_source'] == 'users_playlist') echo 'selected'; ?>>User's Playlist</option -->
					<option value="playlist_id"<? if ($cat['c_listing_source'] == 'playlist_id') echo 'selected'; ?>>Playlist ID</option>
				</select>
			</td>
			</tr>
			<tr>
		<td colspan="3">
			<div id="row_keyword">
				<div style="float:left;width:110px;">Keyword<em style="color:red;">*</em></div>
				<div><input name="keyword" type="text" id="keyword" value="<?=$cat["c_keyword"]?>" size="30"></div>
			</div>
			<div id="row_author">
				<div style="float:left;width:110px;">Author<em style="color:red;">*</em></div>
				<div><input name="author" type="text" id="author" value="<?=$cat["c_user_videos"]?>" size="30"></div>
			</div>
			<div id="row_users_playlist">
				<div style="float:left;width:110px;">Author<em style="color:red;">*</em></div>
				<div><input name="users_playlist" type="text" id="users_playlist" value="<?=$cat["c_users_playlist"]?>" size="30" onblur="javascript:ajaxPost(this.value);"></div>
			</div>
			<div id="row_playlist_id">
				<div style="float:left;width:110px;">Playlist ID<em style="color:red;">*</em></div>
				<div><select name="playlist_id" id="playlist_id" style="width:250px;">
	  <option value="0">Please choose your playlist</option>
	  </select><span id="block-loading" style="display:none;"><img  style="margin-left:10px;" src="<?=$website_url;?>/templates/default/images/mozilla_blu.gif" /></span>
	  <br /><a href="#" onclick="javascript:ajaxPost(document.form1.users_playlist.value);">Reload Playlist</a></div>
			</div>
		</td>
	</tr>


			<tr><td colspan="3"><br></td></tr>
    		<tr>
				<td></td>
      			<td>
					<div align="right">
        			<input name="act" type="hidden" value="update">
					<input name="id" type="hidden" value="<?=$ctg_id?>">
					<input class="button" type="submit" name="Submit" value="Save">
      				</div>
				</td>
				<td>&nbsp;<a href="?act=delete&id=<?=$ctg_id?>">Delete</a>&nbsp; | &nbsp;<a href="?">Cancel</a></td>
    		</tr>
  			</table>

			</form>
			</div>
		<?
		}else{
		?>

<div class="gradientBg" style="margin:10px 10px 0 10px; width:450px;float:right;">

<form name="form1" method="post" action="">

  <table width="450"  border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <td colspan="3" bgcolor="#CD2532"><strong><font color="#ffffff">Add New Category </font></strong></td>
    </tr>
	<tr><td colspan="3"><br></td></tr>
    <tr>
      <td width="50">Child Of</td>
      <td width="10"></td>
      <td width="300"><select name="parent" id="parent">

      </select></td>
    </tr>
    <tr>
      <td>Name<em style="color:red;">*</em></td>
      <td></td>
      <td><input name="name" type="text" id="name" size="20"></td>
    </tr>
    <tr>
      <td>Description</td>
      <td></td>
      <td><textarea name="desc" cols="32" rows="3" id="desc"></textarea></td>
    </tr>
	<tr>
      <td>Listing Source</td>
      <td></td>
      <td>
		<select name="listing_source" id="listing_source" onChange="javascript:display_field();">
			<option value="keyword">Keyword</option>
			<option value="author">Author</option>
			<!-- option value="users_playlist">User's Playlist</option -->
			<option value="playlist_id">Playlist ID</option>
		</select>
	</td>
    </tr>
	<tr>
		<td colspan="3">
			<div id="row_keyword">
				<div style="float:left;width:110px;">Keyword<em style="color:red;">*</em></div>
				<div><input name="keyword" type="text" id="keyword" value="" size="30"></div>
			</div>
			<div id="row_author">
				<div style="float:left;width:110px;">Author<em style="color:red;">*</em></div>
				<div><input name="author" type="text" id="author" value="" size="30"></div>
			</div>
			<div id="row_users_playlist">
				<div style="float:left;width:110px;">Author<em style="color:red;">*</em></div>
				<div><input name="users_playlist" type="text" id="users_playlist" value="" size="30" onblur="javascript:ajaxPost(this.value);"></div>
			</div>
			<div id="row_playlist_id">
				<div style="float:left;width:110px;">Playlist ID<em style="color:red;">*</em></div>
				<div><select name="playlist_id" id="playlist_id" style="width:250px;">
	  <option value="0">Please choose your playlist</option>
	  </select><span id="block-loading" style="display:none;"><img  style="margin-left:10px;" src="<?=$website_url;?>/templates/default/images/mozilla_blu.gif" /></span>
	  <br /><a href="#" onclick="javascript:ajaxPost(document.form1.users_playlist.value);">Reload Playlist</a></div>
			</div>
		</td>
	</tr>
    <tr>
      <td colspan="3"><div align="right">
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

 if ( $is_write_category_files ) {
	$temp_categories	= array();
	$idx		= 0;
	$id2		= 0;

	$sqlQuery	= "SELECT `id`, `position`, `c_name`, `c_listing_source`, `c_keyword`
			FROM `categories`
			WHERE `position` RLIKE '^([0-9]+>){1,1}$' AND  c_group = 0
			ORDER BY `c_name`";
	$sqlResult	= dbQuery($sqlQuery);

	while($row = mysql_fetch_array($sqlResult)) {


		$temp_categories[$idx]['id']			= $row['id'];
		$temp_categories[$idx]['position']	= stripslashes($row['position']);
		$temp_categories[$idx]['c_name']		= stripslashes($row['c_name']);
		$temp_categories[$idx]['c_listing_source']	= stripslashes($row['c_listing_source']);
		$temp_categories[$idx]['c_keyword']			= stripslashes($row['c_keyword']);
		$temp_categories[$idx]['has_children']		= 0;
		$temp_categories[$idx]['subcategories']		= array();

			// lets check if there is sub-categories
		if( $collapsed == "" && $id2== 0 ) {
			$has_children = has_children($row['position']);
			$temp_categories[$idx]['has_children'] = ( ($has_children) ? 1 : 0 );

			if ( $has_children ) {
				$temp_categories[$idx]['subcategories'] = get_children($temp_categories[$idx]['position']);
			}

		}

		$idx++;
	}


	$serialize_cat	= serialize($temp_categories);
	@define("DS", DIRECTORY_SEPARATOR);
	$path_pm		= str_replace(DS."lib".DS."modules".DS."categories", "", dirname(__FILE__));

	$path_file		= $path_pm.DS."categories_cached_data_temp.txt";

	$fp = fopen($path_file, "w");
	fwrite($fp, $serialize_cat);
	fclose($fp);

	if ( file_exists($path_pm.DS."categories_cached_data_temp.txt") || file_exists($path_pm.DS."categories_cached_data.txt") ) {
		@unlink($path_pm.DS."categories_cached_data.txt");
		copy($path_pm.DS."categories_cached_data_temp.txt", $path_pm.DS."categories_cached_data.txt");
		@unlink($path_pm.DS."categories_cached_data_temp.txt");
	}

 }

function lastCatId()
{
	$query = "SELECT id FROM `categories` ORDER BY `id`  DESC LIMIT 1";
	$res = dbQuery($query, $silence = true);;
	$ID = mysql_fetch_row($res);

	return $ID[0];
}

if($act == "updateForm") {
	if ( isset($cat['c_users_playlist']) && $cat['c_users_playlist'] != '' ) {
		$playlist = isset($cat['c_playlist_id']) ? $cat['c_playlist_id'] : '';
	?>
	<script type="text/javascript">
	ajaxPost('<?=$playlist?>');
	</script>
	<?
	}
}

?>

</body>
</html><? //xdebug_stop_trace();?>