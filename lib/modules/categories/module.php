<?php
include_once('categories.class.php');
//error_reporting(0);
$action = $_GET["acttion"];
if($action == "browseid")
	categories_browseID($_GET["id"]);

function categories_getDropDown($max_limit = 0) {
	global $config, $randID;

 		if(!isset($_GET["id"])) $_GET["id"] = $randID;
		$ctg_id = $_GET["id"];
		$dropDown = "";
		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";
		$categories_list = $categories->build_list(0, "", 1, $max_limit);
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
 			 		$dropDown .= '<option '.$select.' value="categories/'.SeoKeywordEncode($c["c_keyword"]).'/'.$c["id"].'/page1.html">'.$c["prefix"].' '.prismo_print($c["c_name"]).' </option>';
				}else{
					$c["prefix"] .= "&nbsp;&nbsp;";
			 		$dropDown .= '<option '.$select.' value="categories/'.SeoKeywordEncode($c["c_keyword"]).'/'.$c["id"].'/page1.html">'.$c["prefix"].' &raquo; '.prismo_print($c["c_name"]).' </option>';
				}
			}
            $dropDown .= '</select></form>';
		}else{
			$dropDown = 'No Categories Yet';
		}
	return $dropDown;
}

function categories_getDropDown_max_limit($max_limit = 0) {
	global $config, $randID;

 		if(!isset($_GET["id"])) $_GET["id"] = $randID;
		$ctg_id = $_GET["id"];
		$dropDown = "";
		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";
		$categories_list = $categories->build_list(0, "", 1, $max_limit);
		if(sizeof($categories_list) != 0) {
			$dropDown .= '<form><select class="dropDownCat" name="jumpto" id="jumpto" onChange="if(this.form.jumpto.options[this.form.jumpto.selectedIndex].value != \'\') { window.location.href= this.form.jumpto.options[this.form.jumpto.selectedIndex].value; }">';
			$dropDown .= '<option value=""> ----- Выберите рубрику ----- </option>';
			foreach($categories_list as $c)
			{
				$select = "";
				if($ctg_id == $c["id"])
					$select = "selected";
				if($c["prefix"] == "") {
					$c["prefix"] = '&nbsp;&bull; ';
					$dropDown .= '<option></option>';
 			 		$dropDown .= '<option '.$select.' value="categories/'.SeoKeywordEncode($c["c_keyword"]).'/'.$c["id"].'/page1.html">'.$c["prefix"].' '.prismo_print($c["c_name"]).' </option>';
				}else{
					$c["prefix"] .= "&nbsp;&nbsp;";
			 		$dropDown .= '<option '.$select.' value="categories/'.SeoKeywordEncode($c["c_keyword"]).'/'.$c["id"].'/page1.html">'.$c["prefix"].' &raquo; '.prismo_print($c["c_name"]).' </option>';
				}
			}
            $dropDown .= '</select></form>';
		}else{
			$dropDown = 'No Categories Yet';
		}
	return $dropDown;
}

function categories_getDropDown_admin() {

	global $config;
		if ( !isset($config['default_category_id']) || $config['default_category_id'] == '' ) {
			$ctg_id = 0;
		} else {
			$ctg_id = $config['default_category_id'];
		}

		$dropDown = "";
		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";
		$categories_list = $categories->build_list(0);
		if(sizeof($categories_list) != 0) {
			$dropDown .= '<select class="select" name="default_category_id" id="default_category_id">';
			$dropDown .= '<option value="0"> ----- Нет рубрики по умолчанию ----- </option>';
			foreach($categories_list as $c)
			{
				$select = "";
				if($ctg_id == $c["id"])
					$select = "selected";
				if($c["prefix"] == "") {
					$c["prefix"] = '&nbsp;&bull; ';
					$dropDown .= '<option></option>';
 			 		$dropDown .= '<option '.$select.' value="'.$c["id"].'">'.$c["prefix"].' '.prismo_print($c["c_name"]).' </option>';
				}else{
					$c["prefix"] .= "&nbsp;&nbsp;";
			 		$dropDown .= '<option '.$select.' value="'.$c["id"].'">'.$c["prefix"].' &raquo; '.prismo_print($c["c_name"]).' </option>';
				}
			}
            $dropDown .= '</select>';
		}else{
			$dropDown = 'No Categories Yet';
		}
	return $dropDown;
}

function get_root_category($id) {
	$root_id	= 0;
	$sqlQuery	= "SELECT `position` FROM `".DB_PREFIX."categories` WHERE `id` = {$id}";
	$sqlResult	= dbQuery($sqlQuery);

	if ( mysql_num_rows($sqlResult) > 0 ) {
		list($position)	= mysql_fetch_row($sqlResult);
		$positions		= explode('>', $position);
		$root_id		= isset($positions[0]) ? $positions[0] : 0;
	}

	return $root_id;
}

function set_keyword($listing_source, $name, $kword) {
	$keyword		= "";
	if ( $listing_source == 'keyword' ) {
		$keyword	= SeoKeywordEncode($kword);
	} else {
		$keyword	= SeoKeywordEncode($name);
	}
	return $keyword;
}


function display_categories_data($categories, $parent_id, $ctg_id) {

	global $config;
	$temp_string	= "";
	foreach($categories as $category_data) {
		$prefix		= isset($category_data['prefix']) ? $category_data['prefix'] : '';
		$category_data['c_name'] = prismo_print($category_data['c_name']);
		if ( $category_data['has_children'] ) {
			if ( $prefix == '' ) {
				$kword				= set_keyword($category_data['c_listing_source'], $category_data['c_name'], $category_data['c_keyword']);
				if ( $category_data['id'] == $ctg_id ) {
					$temp_string	.= "<tr><td class=\"treeMenuSelectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuSelectedA\"><strong>".$category_data['c_name']."</strong></a></td></tr>";
				} else {
					$temp_string	.= "<tr><td class=\"treeMenuUnselectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuUnselectedA\">".$category_data['c_name']."</a></td></tr>";
				}
				$temp_string	.= display_categories_data($category_data['subcategories'], $parent_id, $ctg_id);
			} else {
				$position		= isset($category_data['position']) ? $category_data['position'] : '';
				$category_ids	= explode('>', $position);
				if ( $parent_id == $category_ids[0] ) {
					//$temp_string	.= $prefix.$category_data['c_name']."<br />";
					$kword				= set_keyword($category_data['c_listing_source'], $category_data['c_name'], $category_data['c_keyword']);
					if ( $category_data['id'] == $ctg_id ) {
						$temp_string	.= "<tr><td class=\"treeMenuSelectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuSelectedA\"><strong>".$category_data['c_name']."</strong></a></td></tr>";
					} else {
						$temp_string	.= "<tr><td class=\"treeMenuUnselectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuUnselectedA\">".$category_data['c_name']."</a></td></tr>";
					}
					$temp_string	.= display_categories_data($category_data['subcategories'], $parent_id, $ctg_id);
				}
			}
		} else {
			if ( $prefix == '' ) {
				$kword				= set_keyword($category_data['c_listing_source'], $category_data['c_name'], $category_data['c_keyword']);
				if ( $category_data['id'] == $ctg_id ) {
					$temp_string	.= "<tr><td class=\"treeMenuSelectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuSelectedA\"><strong>".$category_data['c_name']."</strong></a></td></tr>";
				} else {
					$temp_string	.= "<tr><td class=\"treeMenuUnselectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuUnselectedA\">".$category_data['c_name']."</a></td></tr>";
				}
			} else {
				$position		= isset($category_data['position']) ? $category_data['position'] : '';
				$category_ids	= explode('>', $position);
				if ( $parent_id == $category_ids[0] ) {
					//$temp_string	.= $prefix.$category_data['c_name']."<br />";
					$kword				= set_keyword($category_data['c_listing_source'], $category_data['c_name'], $category_data['c_keyword']);
					if ( $category_data['id'] == $ctg_id ) {
						$temp_string	.= "<tr><td class=\"treeMenuSelectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuSelectedA\"><strong>".$category_data['c_name']."</strong></a></td></tr>";
					} else {
						$temp_string	.= "<tr><td class=\"treeMenuUnselectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuUnselectedA\">".$category_data['c_name']."</a></td></tr>";
					}
					$temp_string	.= display_categories_data($category_data['subcategories'], $parent_id, $ctg_id);
				}
			}
		}
	}

	return $temp_string;
}

function categories_getTreeMenu() {
	global $config, $randID;

	$output		= "";
	if(!isset($_GET["id"])) $_GET["id"] = $randID;
	$ctg_id = $_GET["id"];

	@define("DS", DIRECTORY_SEPARATOR);

	if ( isset($config['categories_cache_enable']) && $config['categories_cache_enable'] ) {
		$path_pm		= str_replace(DS."lib".DS."modules".DS."categories", "", dirname(__FILE__));
		$cache_file		= $path_pm.DS.$config['html_cache_dir']."categories_cache.php";

		if ( file_exists($cache_file) ) {
			$filetime		= time() - filemtime($cache_file);
			if ( $filetime > $config['categories_cache_timeout']) {
				@unlink($cache_file);
				$output = create_categories_cache($ctg_id);

				$handle = fopen($cache_file, 'w+');
				fwrite($handle, $output);
				fclose($handle);
			} else {
				$output	= file_get_contents($cache_file);
			}
		} else {
			//@unlink($cache_file);
			$output = create_categories_cache($ctg_id);
			$handle = fopen($cache_file, 'w+');
			fwrite($handle, $output);
			fclose($handle);
		}



	} else {
		$categories = new categories;
		$categories_list = $categories->browse_by_id($id);
		if(sizeof($categories_list) != 0) {

			$categories->name_prefix = "&nbsp;&nbsp;&raquo;&nbsp;";

			$categories->HtmlTree = array(
				"header" => "<ul>",
				"BodyUnselected" => '<li>[prefix]<a href="'.$config['website_url'].'categories/[keyword]/[id]/page1.html" title="[desc]">[name]</a></li>',
				"BodySelected" => '<li>[prefix]<a href="'.$config['website_url'].'categories/[keyword]/[id]/page1.html" title="[desc]" class="treeMenuSelectedA"><strong>[name]</strong></a></li>',
				"footer" => '</ul>',
			);
			$output = $categories->html_output($ctg_id);
		}else{
			$output = lang('nothing_found');
		}
	}



	return $output;
}

function create_categories_cache($ctg_id, $is_print = true) {
	global $config;
	$str_categories_cache	= "";
	$_GET['abcid']	= $ctg_id;

	$sqlQuery = "SELECT *
		FROM `".DB_PREFIX."categories`
		WHERE `position` RLIKE '^([0-9]+>){1,1}$' AND `c_group`	= '0'
		AND ( `enable_publishdate` = 0 OR (`enable_publishdate` = 1 AND `publishdate` <= ".time()."))
		ORDER BY `c_name`";
	$sqlResult	= dbQuery($sqlQuery);
	$str_categories_cache	= "<?php global \$ctg_id, \$randID; if ( isset(\$randID) ) { \$ctg_id = \$randID; }?>
<ul>";

	while( $row = mysql_fetch_array($sqlResult) ) {

		$category_id			= $row['id'];
		$position				= stripslashes($row['position']);
		$category_name			= stripslashes($row['c_name']);

		$seo_name				= prismo_print(SeoKeywordEncode($row['c_name']));
		$enable_publishdate		= $row['enable_publishdate'];
		$publishdate			= $row['publishdate'];

		if ( $enable_publishdate ) {
		$str_categories_cache	.= " <?php if (  time() >= {$publishdate} ) { ?>";
		}

		$str_categories_cache	.= '<li><a href="categories/'.$seo_name.'/'.$category_id.'/page1.html" title="'.$category_name.'" <?php if($ctg_id=='.$category_id.') echo \'class="treeMenuSelectedA"\'?>>'.$category_name.'</a></li>
';
		if ( $enable_publishdate ) {
		$str_categories_cache	.= " <?php } ?>";
		}

		$sqlQuery	= "SELECT *
		FROM `".DB_PREFIX."categories`
		WHERE `position` RLIKE '^".$position."[0-9]+>$'
		AND ( `enable_publishdate` = 0 OR (`enable_publishdate` = 1 AND `publishdate` <= ".time()."))
		ORDER BY `c_name`";
		$sqlResult2	= mysql_query($sqlQuery);
		$sub_categories[$category_id]	= array();
		if ( mysql_num_rows($sqlResult2) > 0 ) {
			$idx = 0;
			while( $row2 = mysql_fetch_array($sqlResult2) ) {
				$sub_categories[$category_id][$idx] = $row2['id'];
				$idx++;
			}
		}

		$sqlQuery	= "SELECT *
		FROM `".DB_PREFIX."categories`
		WHERE `position` RLIKE '^".$position."[0-9]+>$'
		AND ( `enable_publishdate` = 0 OR (`enable_publishdate` = 1 AND `publishdate` <= ".time()."))
		ORDER BY `c_name`";
		$sqlResult2	= mysql_query($sqlQuery);

		if ( mysql_num_rows($sqlResult2) > 0 ) {

		$str_sub = "";
		if ( count($sub_categories[$category_id]) > 0 ) {

			foreach( $sub_categories[$category_id] as $subcategory_id ) {
				$str_sub .= " || \$ctg_id == {$subcategory_id} ";
			}
		}

		if ( $enable_publishdate ) {
			$str_categories_cache	.= " <?php if ( ( time() >= {$publishdate} ) && ( \$ctg_id == {$category_id} {$str_sub} ) ) { ?>";
		} else {
			$str_categories_cache	.= " <?php if ( \$ctg_id == {$category_id} {$str_sub} ) { ?>";
		}

			while( $row2 = mysql_fetch_array($sqlResult2) ) {
				$category_name2			= stripslashes($row2['c_name']);

				$seo_name2				= prismo_print(SeoKeywordEncode($row2['c_name']));
				$category_id2			= $row2['id'];
				$enable_publishdate2	= $row2['enable_publishdate'];
				$publishdate2			= $row2['publishdate'];

				if ( $enable_publishdate2 ) {
					$str_categories_cache	.= " <?php if ( time() >= {$publishdate2} ) { ?>";
				}

				$str_categories_cache	.= '<li>&raquo;<a href="categories/'.$seo_name2.'/'.$category_id2.'/page1.html" title="'.$category_name2.'" <?php if($ctg_id=='.$category_id2.') echo \'class="treeMenuSelectedA"\'?>>'.$category_name2.'</a></li>
';

				if ( $enable_publishdate2 ) {
					$str_categories_cache	.= " <?php } ?>";
				}

			}

		$str_categories_cache	.= "<?php } ?>";

		}

	}

	$str_categories_cache	.= "</ul>";

	if ( $is_print ) {
	//echo eval($str_categories_cache);
	//echo $str_categories_cache;
	}

	return $str_categories_cache;
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
			 	$menu .= '<li><span class="dir"><a href="categories/'.prismo_print(SeoKeywordEncode($c["c_name"])).'/'.$c["id"].'/page1.html"  title="'.$c["c_desc"].'">'.$c["prefix"].' &raquo; '.$c["c_name"].' </a></span></li>';
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
			 	$menu .= '<li><span class="dir"><a href="categories/'.prismo_print(SeoKeywordEncode($c["c_name"])).'/'.$c["id"].'/page1.html" title="'.$c["c_desc"].'">'.$c["prefix"].$c["c_name"].' </a></span></li>';
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

function categories_random() {
	$sqlQuery	= "SELECT `id`, `c_name` FROM `".DB_PREFIX."categories` WHERE c_group = '0'
		AND ( enable_publishdate = 0 OR (enable_publishdate = 1 AND publishdate <= ".time().")) ORDER BY RAND() LIMIT 1";
	$sqlResult	= dbQuery($sqlQuery);
	list($category_id, $c_name) = mysql_fetch_row($sqlResult);
	$c_name		= stripslashes($c_name);

	return $category_id."||".$c_name;
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

function categories_getGoogleSitemap() {
	global $config;

	$output			= "";
	$categories 	= new categories;
	$category_lists	= $categories->build_list();

	foreach($category_lists as $category_data) {
		if ( $category_data['c_listing_source'] == 'keyword' ) {
			$output .= $config['website_url'].'categories/'.$categories->SeoKeywordEncode($category_data['c_keyword']).'/'.$category_data['id']."/page1.html\n";
		} else {
			$output .= $config['website_url'].'categories/'.$categories->SeoKeywordEncode($category_data['c_name']).'/'.$category_data['id']."/page1.html\n";
		}
	}

	return $output;
}

function categories_getXMLGoogleSitemap() {
	global $config;

	$categories 	= new categories;
	$category_lists	= $categories->build_list();

	$output			= '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="sitemap.xsl"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">	';


	foreach($category_lists as $category_data) {
		$output		.= "<url>
		<loc>";
		if ( $category_data['c_listing_source'] == 'keyword' ) {
			$output .= $config['website_url'].'categories/'.$categories->SeoKeywordEncode($category_data['c_keyword']).'/'.$category_data['id']."/page1.html";
		} else {
			$output .= $config['website_url'].'categories/'.$categories->SeoKeywordEncode($category_data['c_name']).'/'.$category_data['id']."/page1.html";
		}
		$output		.= "</loc>
		";
		$output		.= "<lastmod>".$category_data['date_added']."</lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
		</url>
		";

	}

	$output .= '</urlset>';
	return $output;
}

function get_root_categories() {
	global $config, $randID;
	$max_limit	= $config['dropdown_limit'];

 		if(!isset($_GET["id"])) $_GET["id"] = $randID;
		$ctg_id = $_GET["id"];
		$dropDown = "";
		$categories = new categories;
		$categories->name_prefix = "&nbsp;&nbsp;";
		$categories_list = $categories->get_root_categories($max_limit);
		if(sizeof($categories_list) != 0) {
			$dropDown .= '<form><select class="dropDownCat" name="jumpto" id="jumpto" onChange="if(this.form.jumpto.options[this.form.jumpto.selectedIndex].value != \'\') { window.location.href= this.form.jumpto.options[this.form.jumpto.selectedIndex].value; }">';
			//$dropDown .= '<option value=""> ----- Choose a category ----- </option>';
			foreach($categories_list as $c)
			{
				$select = "";
				if($ctg_id == $c["id"])
					$select = "selected";

					$c["prefix"] = '&nbsp;&bull; ';
 			 		$dropDown .= '<option '.$select.' value="'.$config['website_url'].'categories/'.prismo_print($categories->SeoKeywordEncode($c["c_name_raw"])).'/'.$c["id"].'/page1.html">'.prismo_print($c["c_name_raw"]).' </option>';

			}
			$dropDown .= '<option value="'.$config['website_url'].'categories/all.html">&raquo; '.lang('view_all').'</option>';
            $dropDown .= '</select></form>';
		}else{
			$dropDown = 'No Categories Yet';
		}
	return $dropDown;
}







function display_subcategories_data($categories, $parent_id, $ctg_id) {

	global $config;
	$temp_string	= "";
	foreach($categories as $category_data) {
		$prefix		= isset($category_data['prefix']) ? $category_data['prefix'] : '';
		$category_data['c_name'] = prismo_print($category_data['c_name']);
		if ( $category_data['has_children'] ) {
			if ( $prefix == '' ) {
				$kword				= set_keyword($category_data['c_listing_source'], $category_data['c_name'], $category_data['c_keyword']);
				if ( $category_data['id'] == $ctg_id ) {
					$temp_string	.= "<tr><td class=\"treeMenuSelectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuSelectedA\"><strong>".$category_data['c_name']."</strong></a></td></tr>";
				} else {
					$temp_string	.= "<tr><td class=\"treeMenuUnselectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuUnselectedA\">".$category_data['c_name']."</a></td></tr>";
				}
				$temp_string	.= display_subcategories_data($category_data['subcategories'], $parent_id, $ctg_id);
			} else {
				$position		= isset($category_data['position']) ? $category_data['position'] : '';
				$category_ids	= explode('>', $position);
				if ( $parent_id == $category_ids[0] ) {
					//$temp_string	.= $prefix.$category_data['c_name']."<br />";
					$kword				= set_keyword($category_data['c_listing_source'], $category_data['c_name'], $category_data['c_keyword']);
					if ( $category_data['id'] == $ctg_id ) {
						$temp_string	.= "<tr><td class=\"treeMenuSelectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuSelectedA\"><strong>".$category_data['c_name']."</strong></a></td></tr>";
					} else {
						$temp_string	.= "<tr><td class=\"treeMenuUnselectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuUnselectedA\">".$category_data['c_name']."</a></td></tr>";
					}
					$temp_string	.= display_subcategories_data($category_data['subcategories'], $parent_id, $ctg_id);
				}
			}
		} else {
			if ( $prefix == '' ) {
				$kword				= set_keyword($category_data['c_listing_source'], $category_data['c_name'], $category_data['c_keyword']);
				if ( $category_data['id'] == $ctg_id ) {
					$temp_string	.= "<tr><td class=\"treeMenuSelectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuSelectedA\"><strong>".$category_data['c_name']."</strong></a></td></tr>";
				} else {
					$temp_string	.= "<tr><td class=\"treeMenuUnselectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuUnselectedA\">".$category_data['c_name']."</a></td></tr>";
				}
			} else {
				$position		= isset($category_data['position']) ? $category_data['position'] : '';
				$category_ids	= explode('>', $position);
				if ( $parent_id == $category_ids[0] ) {
					//$temp_string	.= $prefix.$category_data['c_name']."<br />";
					$kword				= set_keyword($category_data['c_listing_source'], $category_data['c_name'], $category_data['c_keyword']);
					if ( $category_data['id'] == $ctg_id ) {
						$temp_string	.= "<tr><td class=\"treeMenuSelectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuSelectedA\"><strong>".$category_data['c_name']."</strong></a></td></tr>";
					} else {
						$temp_string	.= "<tr><td class=\"treeMenuUnselectedTd\">{$prefix}<a href=\"categories/{$kword}/".$category_data['id']."/page1.html\" class=\"treeMenuUnselectedA\">".$category_data['c_name']."</a></td></tr>";
					}
					$temp_string	.= display_subcategories_data($category_data['subcategories'], $parent_id, $ctg_id);
				}
			}
		}
	}

	return $temp_string;
}

?>