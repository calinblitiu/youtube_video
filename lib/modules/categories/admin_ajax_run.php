<?php
session_start();
$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	header('Location: ../../../admin/');
	die();
}
$act = isset($_REQUEST["act"]) ? $_REQUEST["act"] : '';
include ('categories.class.php');

$categories = new categories;
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

			var author = $('#users_playlist').val();
			var dataString = 'author='+ author + '&playlist_id=' + playlist_id;
			//alert (dataString);return false;
			  $.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				success: function( return_data) {
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

	echo "<font color=red>Error:</font> Таблицы базы данных отсутствуют, пожалуйста, убедитесь, что модуль рубрик установлен правильно!";
	exit();
  }


	//	$categories_list = $categories->build_list(0);

		// lets do some actions
		if(!isset($_REQUEST['act'])) $_REQUEST['act'] = "";

		$act = $_REQUEST["act"];

		if($act == "add") {

			if( $_POST['listing_source'] == 'keyword' ) {
				if ($_POST['keyword'] == '' ) echo '<script>alert("Ключевое слово не может быть пустым!");</script>';
			} else if ( $_POST['listing_source'] == 'author' ) {
				if ( $_POST['author'] == '' ) echo '<script>alert("Автор не может быть пустым!");</script>';
			} else if ( $_POST['listing_source'] == 'playlist_id' ) {
				if ( empty($_POST['playlist_id']) ) echo '<script>alert("Плейлист ID не может быть пустым!");</script>';
			}

			if($_POST["name"] == "") {
				//if($_POST["name"] == "")
					echo '<script>alert("Название рубрики не может быть пустым!");</script>';
				//else
					//echo '<script>alert("Keyword cannot be empty!");</script>';
			}else{

				$categories->add_new($_POST['parent'] , $_POST["name"] , $_POST["desc"] , $_POST["keyword"], $_POST['listing_source'], $_POST['author'], $_POST['users_playlist'], $_POST['playlist_id'] );
				$msg="Category was inserted successfully into database";
			}

		}else if($act == "delete") {

			$categories->delete($_GET["id"]);
			$msg="Category and Sub-Categories where successfully deleted";


		}else if($act == "update") {

			$categories->update($_POST["id"] , $_POST["parent"] , $_POST["name"] , $_POST["desc"] , $_POST["keyword"], 0, $_POST["listing_source"], $_POST["author"], $_POST['users_playlist'], $_POST['playlist_id'] );

			$msg="Category was updated successfully!";


		}

  if(!isset($_GET["id"])) $_GET["id"] = 0;

		$ctg_id = $_GET["id"];

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



		if($act == "updateForm") {

			$cat = $categories->fetch($_GET["id"]);

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
    		<tr id="row_keyword">
      			<td>Keyword</td>
      			<td></td>
      			<td><input name="keyword" type="text" id="keyword" value="<?=$cat["c_keyword"]?>" size="30"></td>
    		</tr>
			<tr id="row_author">
			  <td>Author</td>
			  <td></td>
			  <td><input name="author" type="text" id="author" value="<?=$cat["c_user_videos"]?>" size="30"></td>
			</tr>
			<tr id="row_users_playlist">
			  <td>Author<em style="color:red;">*</em></td>
			  <td></td>
			  <td><input name="users_playlist" type="text" id="users_playlist" value="<?=$cat["author_username"]?>" size="30"></td>
			</tr>
			<tr id="row_playlist_id">
			  <td>Playlist ID<em style="color:red;">*</em></td>
			  <td></td>
			  <td><select name="playlist_id" id="playlist_id" style="width:250px;">
	  <option value="0">Please choose your playlist</option>
	  </select></td>
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

<div class="gradientBg" style="margin:10px 10px 0 10px; float:right;">

<form name="form1" method="post" action="">

  <table width="450"  border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <td colspan="3" bgcolor="#CD2532"><strong><font color="#ffffff">Add New Category </font></strong></td>
    </tr>
	<tr><td colspan="3"><br></td></tr>
    <tr>
      <td width="30%">Child Of</td>
      <td width="1%"></td>
      <td width="69%"><select name="parent" id="parent">

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
    <tr id="row_keyword">
      <td>Keyword<em style="color:red;">*</em></td>
      <td></td>
      <td><input name="keyword" type="text" id="keyword" value="" size="30"></td>
    </tr>
	<tr id="row_author">
      <td>Author<em style="color:red;">*</em></td>
      <td></td>
      <td><input name="author" type="text" id="author" value="" size="30"></td>
    </tr>
	<tr id="row_users_playlist">
      <td>Author<em style="color:red;">*</em></td>
      <td></td>
      <td><input name="users_playlist" type="text" id="users_playlist" value="" size="30" onblur="javascript:ajaxPost(0);"></td>
    </tr>
	<tr id="row_playlist_id">
      <td>Playlist ID<em style="color:red;">*</em></td>
      <td></td>
      <td><select name="playlist_id" id="playlist_id" style="width:250px;">
	  <option value="0">Please choose your playlist</option>
	  </select></td>
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

						document.getElementById('row_users_playlist').style.visibility = "hidden";
						document.getElementById('row_keyword').style.visibility = "hidden";
						document.getElementById('row_author').style.visibility = "visible";
						document.getElementById('row_playlist_id').style.visibility = "hidden";
					} else if ( selected_val == 'keyword' ) {
						document.getElementById('row_users_playlist').style.visibility = "hidden";
						document.getElementById('row_keyword').style.visibility = "visible";
						document.getElementById('row_author').style.visibility = "hidden";
						document.getElementById('row_playlist_id').style.visibility = "hidden";
					} else if ( selected_val == 'users_playlist') {
						document.getElementById('row_users_playlist').style.visibility = "visible";
						document.getElementById('row_keyword').style.visibility = "hidden";
						document.getElementById('row_author').style.visibility = "hidden";
						document.getElementById('row_playlist_id').style.visibility = "hidden";
					} else if ( selected_val == 'playlist_id') {
						document.getElementById('row_playlist_id').style.visibility = "visible";
						document.getElementById('row_keyword').style.visibility = "hidden";
						document.getElementById('row_author').style.visibility = "hidden";
						document.getElementById('row_users_playlist').style.visibility = "visible";
					}
				}

				display_field();
			-->
			</script>
<?php


function lastCatId()
{
	$query = "SELECT id FROM `categories` ORDER BY `id`  DESC LIMIT 1";
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
}

?>

</body>
</html>