<?php
include('categories.class.php');

$action = $_GET["acttion"];
if($action == "browseid")
	categories_browseID($_GET["id"]);

function categories_getDropDown() {
	global $config, $randID;

 		if(!isset($_GET["id"])) $_GET["id"] = $randID;
		$ctg_id = $_GET["id"];
		$dropDown = "";
		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";
		$categories_list = $categories->build_list(0);
		if(sizeof($categories_list) != 0) {
			$dropDown .= '<form><select class="dropDownCat" name="jumpto" id="jumpto" onChange="if(this.form.jumpto.options[this.form.jumpto.selectedIndex].value != \'\') { window.location.href= this.form.jumpto.options[this.form.jumpto.selectedIndex].value; }">';
			$dropDown .= '<option value=""> ----- Choose a category ----- </option>';
			foreach($categories_list as $c)
			{
				$select = "";
				if($ctg_id == $c["id"])
					$select = "selected";
				if($c["prefix"] == "") {
					$c["prefix"] = '&nbsp;&bull; ';
					$dropDown .= '<option></option>';
 			 		$dropDown .= '<option '.$select.' value="categories/'.SeoKeywordEncode($c["c_keyword"]).'/'.$c["id"].'/page1.html">'.$c["prefix"].' '.$c["c_name"].' </option>';
				}else{
					$c["prefix"] .= "&nbsp;&nbsp;";
			 		$dropDown .= '<option '.$select.' value="categories/'.SeoKeywordEncode($c["c_keyword"]).'/'.$c["id"].'/page1.html">'.$c["prefix"].' &raquo; '.$c["c_name"].' </option>';
				}
			}
            $dropDown .= '</select></form>';
		}else{
			$dropDown = 'No Categories Yet';
		}
	return $dropDown;
}

function categories_getTreeMenu() {
	global $config, $randID;

		if(!isset($_GET["id"])) $_GET["id"] = $randID;
		$ctg_id = $_GET["id"];
		$categories = new categories;
		$categories_list = $categories->browse_by_id($id);
		if(sizeof($categories_list) != 0) {

			$categories->name_prefix = "&nbsp;&nbsp;&raquo;&nbsp;";
			$categories->HtmlTree = array(
				"header" => "<table width=240px border=0 cellpadding=2 cellspacing=2>",
				"BodyUnselected" => '<tr><td class="treeMenuUnselectedTd">[prefix]<a href="categories/[keyword]/[id]/page1.html" title="[desc]" class="treeMenuUnselectedA">[name]</a></td></tr>',
				"BodySelected" => '<tr><td class="treeMenuSelectedTd">[prefix]<a href="categories/[keyword]/[id]/page1.html" title="[desc]" class="treeMenuSelectedA"><strong>[name]</strong></a></td></tr>',
				"footer" => '</table>',
			);
			$output = $categories->html_output($ctg_id);
		}else{
			$output = "No Categories Found!";
		}
	return $output;
}

function categories_getCSSmenu() {
	global $config, $randID;

 		if(!isset($_GET["id"])) $_GET["id"] = $randID;
		$ctg_id = $_GET["id"];
		$menu = "";
		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";
		$categories_list = $categories->build_list(1);
		if(sizeof($categories_list) != 0) {
			$menu .= '<ul class="dropdown dropdown-vertical dropdown-vertical-rtl">';
			foreach($categories_list as $c)
			{
			 	$menu .= '<li><span class="dir"><a href="categories/'.SeoKeywordEncode($c["c_keyword"]).'/'.$c["c_id"].'/page1.html"  title="'.$c["c_desc"].'">'.$c["prefix"].' &raquo; '.$c["c_name"].' </a></span></li>';
				$subCat = categories_browseID($c["id"]);
				if($subCat != "")
 					$menu .= '<ul>'.$subCat.'</ul>';
			}
            $menu .= '</ul>';
		}else{
			$menu = 'No Categories Yet';
		}
	return $menu;
}

function categories_browseID($id) {
	global $config, $randID;

		$menu = "";
		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";
		$categories_list = $categories->browse_by_id($id);
		if(sizeof($categories_list) != 0) {
			foreach($categories_list as $c)
			{
			 	$menu .= '<li><span class="dir"><a href="categories/'.SeoKeywordEncode($c["c_keyword"]).'/'.$c["c_id"].'/page1.html" title="'.$c["c_desc"].'">'.$c["prefix"].$c["c_name"].' </a></span></li>';
			}
		}
	return $menu;
}

function categories_getRandom() {
		$categories = new categories;
		$categories_list = $categories->build_list(0);
		if(sizeof($categories_list) != 0) {
			$i = 0;
			foreach($categories_list as $c) {
				$keywords[$i] = $c["id"].'||'.$c["c_keyword"];
				$i++;
			}
			return $keywords[rand(0,sizeof($keywords))];
		}else{
			return false;
		}
}

function SeoKeywordEncode($s) {
  $c = array (' ','-','/','\\',',','.','#',':',';','\'','"','[',']','{',
      '}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');

  $s = str_replace($c, '-', $s);

  $s = preg_replace(
        array('/-+/',
              '/-$/',
              '/-ytmsinternsignature/'),
        array('-',
              '',
              'ytmsinternsignature') ,
        $s);
  return $s;
}

?>