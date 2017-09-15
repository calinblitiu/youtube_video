<?php
/***************************************************************
Categories Class
Author: Shadi Ali
Em@il: write2shadi@gmail.com
---------------------------------

SQL TABLE:
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `position` varchar(255) NOT NULL DEFAULT '',
  `c_name` varchar(255) NOT NULL DEFAULT '',
  `c_listing_source` varchar(50) NOT NULL DEFAULT 'keyword',
  `c_desc` tinytext NOT NULL,
  `c_keyword` varchar(255) NOT NULL DEFAULT '',
  `c_user_videos` varchar(50) NOT NULL,
  `c_group` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);


class summary:
------------------

->add_new($parent , $name , $desc , $keyword )  // add new category
->delete($id,$deleteItems) // delete existing category and all sub-categories And also possible to delete all items associated with it ($deleteItems=1)
->update($id , $parent , $name  , $desc  , $keyword  ) // update existing category
->build_list($id=0,$collapsed="") // return array with the categories ordered by name , it could be collapsed by setting $collapsed="collapsed".
->browse_by_id($id) // return array with sub categories under a specific category only.
->fetch ($id) // return existing category info.
->count_categories($id) // get sub categories count below a TOP-LEVEL category $id.
->count_items($id) // get the count of items associated to a category and its sub-categories. [needs the 2 variables to be set...  $itemsTable,$CID_FieldName]
********************************************************************/

class categories
{

	var $HtmlTree;
	var $name_prefix  = "&nbsp;&nbsp;";	// this is the prefix which will be added to the category name depending on its position usually use space.
	var $table_name   = "categories";
	var $itemsTable   = "items";		// this is the name of the table which contain the items associated to the categories
	var $CID_FieldName= "category_id";	 // this is the field name in the items table which refere to the ID of the item's category.

	// use the following keys into the $HtmlTree varialbe.
	var $fields = array(
		// field		=> field name in database ( sql structure )
		"id"  		=> "id",
		"position" 	=> "position",
		"name"		=> "c_name",
		"desc"		=> "c_desc",
		"keyword"		=> "c_keyword",
		"user_videos" => "c_user_videos",
		"listing_source" => "c_listing_source",
		"author_username" => "author_username",
		"playlist_id" => "c_playlist_id",
		"group"		=> "c_group",
		"enable_publishdate" => "enable_publishdate",
		"publishdate" => "publishdate"
	);

/**************************************************
	--- NO CHANGES TO BE DONE BELOW ---
**************************************************/

	var $c_list  = array();  // DON'T CHANGE THIS
	var $Group  = 0;		 // DON'T CHANGE THIS

	function categories()
	{
		$this->HtmlTree = array(
		"header" 		 => '<table width=300px border=0 cellpadding=2 cellspacing=2>',
		"BodyUnselected" => '<tr><td>[prefix]&raquo;<a href="?id=[id]">[name]</a></td></tr>',
		"BodySelected"	 => '<tr><td>[prefix]&raquo;<a href="?id=[id]"><strong>[name]</strong></a></td></tr>',
		"footer"		 => '</table>',
		);
	}

// ********************************************************
//		Add New Category
// ********************************************************


	function add_new($parent = 0 , $name , $desc , $keyword, $listing_source, $author, $author_username, $playlist_id, $enable_publishdate, $publishdate )  // add new category
	{

		$arr_char_codes	= array('’');
		$arr_char_codes_to	= array("'");

		//$name			= str_replace($arr_char_codes, $arr_char_codes_to, $name);
		//$keyword		= str_replace($arr_char_codes, $arr_char_codes_to, $keyword);

		// lets get the position from the $parent value
		$position  = $this->get_position($parent);

		if ( get_magic_quotes_gpc() ) {
			$author		= stripslashes($author);
			$author_username	= stripslashes($author_username);
		}

		if ( $publishdate == '' ) {
			$publishdate = 0;
		}

		// lets insert add the new category into the database.
		$sql = "INSERT into ".DB_PREFIX.$this->table_name."(position,c_name,c_desc,c_keyword,c_group, c_listing_source, c_user_videos,
					author_username, c_playlist_id, enable_publishdate, publishdate, date_added)
				VALUES('','".db_escape_string($name)."','".db_escape_string($desc)."','".db_escape_string($keyword)."','".$this->Group."', '{$listing_source}', '{$author}',
					'{$author_username}', '{$playlist_id}', {$enable_publishdate}, {$publishdate}, '".date('Y-m-d')."')";

		dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

		$position .= lastCatId().">";

		$sql = "UPDATE ".DB_PREFIX.$this->table_name."
				SET position = '".$position."'
				WHERE id = '".lastCatId()."'";

		dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

		$this->clear_categories_cache();

		return lastCatId();
	}

// ********************************************************
//		Delete Category
// ********************************************************

	function delete($id, $items=NULL) // delete this category and all categories under it [set $items=1 if you need to delete associated items too, needs the 2 variables $itemsTable,$CID_FieldName]
	{
		$position = $this->get_position($id);

		if($items==1) // delete associated items
		{
			if($this->itemsTable == "" OR $this->CID_FieldName=="")
			{
				die("<br><storng><u>Class Error:</u></strong><br>Either items Table name Or CID field name is blank!<br><br>");
			}

			$sql = "SELECT id
					FROM ".DB_PREFIX.$this->table_name."
					WHERE position LIKE '".$position."%'";
			$res = dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

			while($category = mysql_fetch_array($res)){

				$sql2 = "DELETE	FROM ".DB_PREFIX.$this->itemsTable."
					WHERE ".$this->CID_FieldName." = '".$category["id"]."'";
				$res2 = dbQuery($sql2) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
			}
		}

		if ( trim($position) != '' ) {
			$sql = "DELETE FROM ".DB_PREFIX.$this->table_name."
					WHERE position
					LIKE '".$position."%'";

			dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
		}

		$this->clear_categories_cache();
	}


// ********************************************************
//		Update Category
// ********************************************************

	function update($id , $parent = 0 , $name = 0 , $desc = 0 , $keyword = 0 ,$group = 0, $listing_source = 0, $user_videos = 0, $author_username = 0, $playlist_id = 0, $enable_publishdate = 0 , $publishdate =0)
	{
		// lets see if there is a change on the group
		if($group == 0){
			$this_category = $this->fetch($id);
			$group = $this_category['c_group'];
		}

		// lets get the current position
		$position     = $this->get_position($id);
		$new_position = $this->get_position($parent).$id.">";

		if($position != $new_position){
			// then we update all the sub_categories position to be still under the current category
			$sql1 = "SELECT id, position
					FROM ".DB_PREFIX.$this->table_name."
					WHERE position	LIKE  '".$position."%'";
			$res = dbQuery($sql1) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql1."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

			while($sub = mysql_fetch_array($res)){
				$new_sub_position = str_replace($position,$new_position,$sub['position']);
				$sql2 = "UPDATE ".DB_PREFIX.$this->table_name."
						SET position = '".$new_sub_position."'
						WHERE id =  '".$sub['id']."'";
				dbQuery($sql2) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql2."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
			}
		}

		// finally update the category position.
		$sql3 = "UPDATE `".DB_PREFIX.$this->table_name."`
				SET position = '".$new_position."'
				WHERE position	=  '".$position."'";
		dbQuery($sql3) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql3."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

		$sql = "UPDATE `".DB_PREFIX.$this->table_name."` SET ";

		// lets see what changes should be done and add it to the sql query.
		foreach($this->fields as $field => $field_name){
			if ($field 	== 'id') continue;		// no change will be done on the id
			if ($field 	== 'position' ) continue; // position change have been done in the section above
			$new_field	= $$field;

			$sql .= "`".$field_name."` = '".db_escape_string($new_field)."',";
		}

		$sql = substr_replace($sql,"",-1);
		$sql .= ' WHERE `id`="'.$id.'"';

		dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

		$this->clear_categories_cache();

	}

// ********************************************************
//		Build Categories Array
// ********************************************************

	function build_list($id=0,$collapsed="", $is_check_publish = 1, $max_limit = 0) //return an array with the categories ordered by position
	{
		$RootPos = "";
		$this->c_list = array();

		if($id != 0){
			$this_category  = $this->fetch($id);
			$positions      = explode(">",$this_category['position']);
			$RootPos        = $positions[0];
		}



		if ( $is_check_publish ) {
			// lets fetch the root categories
			$sql = "SELECT *
					FROM ".DB_PREFIX.$this->table_name."
					WHERE position RLIKE '^([0-9]+>){1,1}$' AND  c_group	=      '".$this->Group."'
					AND ( enable_publishdate = 0 OR (enable_publishdate = 1 AND publishdate <= ".time()."))
					ORDER BY c_name";
		} else {
			// lets fetch the root categories
			$sql = "SELECT *
					FROM ".DB_PREFIX.$this->table_name."
					WHERE position RLIKE '^([0-9]+>){1,1}$' AND  c_group	=      '".$this->Group."'
					ORDER BY c_name";
		}


		$res = dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

		while($root = mysql_fetch_array($res)){
			$root["prefix"] = $this->get_prefix($root['position']);
			$this->c_list[$root['id']] = $root;

			if($RootPos == $root['id'] AND $id != 0 AND $collapsed != "") {
				$this->list_by_id($id, $max_limit);
				continue;

			} else {

				// lets check if there is sub-categories
				if($collapsed == "" AND $id==0){
					$has_children = $this->has_children($root['position']);
					if($has_children == TRUE) $this->get_children($root['position'],0,$max_limit);
				}
			}
		}
		return $this->c_list;
	}


// ********************************************************
//		Check if Category has childrens
// ********************************************************

	function has_children($position) // return TRUE if that position has sub-categories otherwise returns FALSE
	{
		$check_sql = "SELECT id FROM ".DB_PREFIX.$this->table_name." WHERE position RLIKE  '^".$position."[0-9]+>$'";
		$check_res = dbQuery($check_sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$check_sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
		$check = mysql_fetch_array($check_res);

		if($check['id'] != "")return TRUE;
		else return FALSE;
	}


// ********************************************************
//		Get Childrens
// ********************************************************

	function get_children($position , $id = 0, $max_limit =0){
		$str_limit		= '';
		if ( $max_limit > 0 ) {
			$str_limit	= ' LIMIT '.$max_limit;
		}

		$sql = "SELECT *
				FROM ".DB_PREFIX.$this->table_name."
				WHERE position	RLIKE '^".$position."[0-9]+>$'
				ORDER BY c_name {$str_limit}";

		$res = dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

		while($child = mysql_fetch_array($res)){
			$child["prefix"] = $this->get_prefix($child['position']);

			if($id != 0)
			{
				$this->c_list_by_id[$child['id']] = $child;
				$has_children = $this->has_children($child['position']);

				if($has_children == TRUE) {
					$this->get_children($child['position']);
				}
				continue;

			} else {

				// lets check if there is sub-categories
				$this->c_list[$child['id']] = $child;
				$has_children = $this->has_children($child['position']);
				if($has_children == TRUE)$this->get_children($child['position']);
			}
		}
	}


// ********************************************************
//		Get childs of Specific Category only.
// ********************************************************

	function list_by_id($id, $max_limit = 0) //return an array with the categories under the given ID and ordered by name
	{
		$this_category  = $this->fetch($id);

		$positions = explode(">",$this_category['position']);
		$pCount = count($positions);
		$i = 0;

		// lets fetch from top to center
		while($i < $pCount){
			$pos_id	   = $positions["$i"];
			if($pos_id == "") {
				$i++; continue;
			}

			$list = $this->browse_by_id($pos_id, $max_limit);

			foreach($list as $key=>$value){
				$this->c_list["$key"] = $value;
				$ni = $i + 1;
				$nxt_id = $positions[$ni];
				if($key == $nxt_id ) break;
			}
			$i++;
		}

		//center to end
		$i = $pCount-1;

		while($i >= 0){
			$pos_id	 = $positions["$i"];
			if($pos_id == ""){$i--; continue;}
			$list = $this->browse_by_id($pos_id, $max_limit);

			foreach($list as $key=>$value){
				$ni = $i - 1;
				if($ni < 0) $ni =0;
				$nxt_id = $positions[$ni];
				if($key == $nxt_id ) break;
				$this->c_list["$key"] = $value;
			}
			$i--;
		}

	}


/***************************************
    Get array of categories under specific category.
 ****************************************/

function browse_by_id($id, $max_limit = 0) // return array of categories under specific category.
{
	$children 		= array();
	$this_category  = $this->fetch($id);
	$position       = $this_category['position'];

	$str_limit		= '';
	if ( $max_limit > 0 ) {
		$str_limit	= ' LIMIT '.$max_limit;
	}

	$sql = "SELECT *
			FROM ".DB_PREFIX.$this->table_name."
			WHERE position	RLIKE '^".$position."(([0-9])+\>){1}$'
			ORDER BY c_name {$str_limit}";
	$res = dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

	while($child = mysql_fetch_array($res)) {
		$child["prefix"] = $this->get_prefix($child['position']);
		$children[$child['id']] = $child;
	}
	return $children;
}


// ********************************************************
//		Get Position
// ********************************************************

function get_position($id)
{
	if($id == 0)return "";
	$sql = "SELECT position
			FROM ".DB_PREFIX.$this->table_name."
			WHERE id = '".$id."'";
	$res = dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
	$record =  mysql_fetch_array($res);
	return $record['position'];
}


// ********************************************************
//		Get Prefix
// ********************************************************

function get_prefix($position)
{
	$prefix = "";
	$position_slices = explode(">",$position);
	$count = count($position_slices) - 1;
	for($i=1 ; $i < $count ; $i++){
		$prefix .= $this->name_prefix;
	}
	return $prefix;
}

// ********************************************************
//		Fetch Category Record
// ********************************************************

function fetch ($id)
{
	$sql = "SELECT *
			FROM ".DB_PREFIX.$this->table_name."
			WHERE id = '".$id."'";
	$res = dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

	$record = mysql_fetch_array($res);
	$record["prefix"] = $this->get_prefix($record['position']);
	$position_slices  = explode(">",$record['position']);
	$key              = count($position_slices)-3;
	if($key < 0) $key = 0;
	$record["parent"] = $position_slices["$key"];
	return $record;
}

// ********************************************************
//		Build HTML output
// ********************************************************

	function html_output($id=0, $is_check_publish = 1)
	{
		$tree  = $this->build_list($id,"collapsed", $is_check_publish); // we have selected to view category

		$output = "";
		$output .= $this->HtmlTree['header'];

		if(is_array($tree))
		{
			foreach($tree as $c)
			{

				if($c['id'] == $id)
					$body = $this->HtmlTree['BodySelected'];
				else
					$body = $this->HtmlTree['BodyUnselected'];



				foreach($this->fields as $name => $field_name)
				{
					if ( $c['listing_source'] == 'keyword' && $name == 'keyword' ) {
						$body = str_replace("[$name]" , prismo_print($this->SeoKeywordEncode($c["keyword"])), $body);
					} else if ( $name == 'keyword' ) {
						$body = str_replace("[$name]" , prismo_print($this->SeoKeywordEncode($c["c_name"])), $body);
					} else {
						$body = str_replace("[$name]" , prismo_print($c["$field_name"]),$body);
					}

					/*
					if($name == "keyword")
						$body = str_replace("[$name]" ,$this->SeoKeywordEncode($c["$field_name"]),$body);
					else
						$body = str_replace("[$name]" ,$c["$field_name"],$body);
					*/

				}
				$body = str_replace("[prefix]",$c['prefix'],$body);

				$output .= $body;
			}
		}

		$output .= $this->HtmlTree['footer'];
		return $output;
	}


// ********************************************************
//                      get sub-categories count at TOP-Level Category.  ( needs top-level category ID as a param)
// ********************************************************

	function count_categories($cat_id)
	{
		$thisPosition = $this->get_position($cat_id);
		$sql = "SELECT *
				FROM ".DB_PREFIX.$this->table_name."
				WHERE position LIKE '".$thisPosition."%'";
		$res   = dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
		$count = mysql_num_rows($res);
		$count-= 1; // remove the category itself from the count
		return $count;
	}

// ********************************************************
//                      get items count under TOP-Level Category/sub-categories.  ( needs top-level category ID as a param)
// ********************************************************

	function count_items($cat_id)
	{
		if($this->itemsTable == "" OR $this->CID_FieldName=="") die("<br><storng><u>Class Error:</u></strong><br>Either items Table name Or CID field name is blank!<br><br>");

		$count = 0;
		$thisPosition = $this->get_position($cat_id);

		$sql = "SELECT *
				FROM ".DB_PREFIX.$this->table_name."
				WHERE position LIKE '".$thisPosition."%'";
		$res = dbQuery($sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

		while($category = mysql_fetch_array($res)){

			$sql2 = "SELECT *
					FROM ".DB_PREFIX.$this->itemsTable."
					WHERE ".$this->CID_FieldName."	= '".$category["id"]."'";
			$res2 = dbQuery($sql2) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
			$count+= mysql_num_rows($res2);
		}

		return $count;
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

	function get_root_categories($max_limit) {
		$categories	= array();

		$all_categories	= $this->get_categories();
		$idx	= 0;
		foreach($all_categories as $all_category) {
			if ( !$all_category['enable_publishdate'] || ( $all_category['enable_publishdate'] && $all_category['publishdate'] <= time() ) ) {
				if ( $max_limit != 0 && ( $idx == $max_limit ) ) {
					break;
				}
				$categories[$idx]['id'] 		= $all_category['category_id'];
				$categories[$idx]['c_name']		= stripslashes($all_category['category_name']);
				$categories[$idx]['c_keyword']	= stripslashes($all_category['keyword']);
				$categories[$idx]['c_name_raw']	= stripslashes($all_category['c_name_raw']);
				$idx++;
			}
		}
		return $categories;
	}

	function get_categories_from_db($position = '', $str_cache_categories = '') {
		$categories		= array();
		$root_level		= false;

		if ( $position == '' ) {
			$position	= '([0-9]+>){1,1}';
			$level		= 1;
			$root_level	= true;
		}

		if ( $str_cache_categories == '' ) {
			$str_cache_categories	= "<?php \$data_categories = array(";
		}

		$sqlQuery	= "SELECT `id`, `c_name`, `position`, `c_keyword`, `enable_publishdate`, `publishdate`
						FROM `".DB_PREFIX."categories`
						WHERE `position` RLIKE '^{$position}$' AND `c_group` = '0' ORDER BY `c_name`";
		$sqlResult	= dbQuery($sqlQuery) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

		$idx = 0;
		while($row	= mysql_fetch_array($sqlResult) ) {
			$subcategories	= array();

			if ( !isset($level) ) {
				$position_slices = explode(">",$position);
				$level = count($position_slices) - 1;
			}

			$str_cache_categories	.= " {$idx} => array('category_id' => {$row['id']},'category_name' => '".$this->cleanCode(prismo_print($row['c_name']))."',
				'c_name_raw' => '".$this->cleanCode($row['c_name'])."','keyword' => '".$this->cleanCode($row['c_keyword'])."','enable_publishdate' => ".$row['enable_publishdate'].",'publishdate' => ".$row['publishdate'].",'level' => {$level},'subcategories' => array(";

			$sqlQuery	= "SELECT `id` FROM `".DB_PREFIX."categories` WHERE `position` RLIKE  '^".$row['position']."[0-9]+>$'";
			$sqlResult2	= dbQuery($sqlQuery) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));

			if ( mysql_num_rows($sqlResult2) > 0 ) {
				list($str_cache_categories, $subcategories)	= $this->get_categories_from_db($row['position'].'[0-9]+>', $str_cache_categories);
			}

			$str_cache_categories .= " ), ), ";

			$categories[$idx]['category_id']	= $row['id'];
			$categories[$idx]['category_name']	= addslashes(prismo_print($row['c_name']));
			$categories[$idx]['c_name_raw']		= addslashes($row['c_name']);
			$categories[$idx]['keyword']		= addslashes($row['c_keyword']);
			$categories[$idx]['enable_publishdate']		= $row['enable_publishdate'];
			$categories[$idx]['publishdate']	= $row['publishdate'];
			$categories[$idx]['level']			= $level;
			$categories[$idx]['subcategories']	= $subcategories;
			$idx++;
		}

		if ( $root_level ) {
			$str_cache_categories .= " ); ?>";
		}

		return array($str_cache_categories, $categories);
	}

	function get_categories() {
		global $config;

		$cache_files	= $this->categories_cache_files();

		$categories	= array();

		$all_categories_cache_file	= $cache_files['all_categories'];

		if ( file_exists($all_categories_cache_file) ) {
			include_once($all_categories_cache_file);
			$categories	= $data_categories;

		} else {
			list($str_cache_categories, $categories)	= $this->get_categories_from_db();

			$fp = fopen($all_categories_cache_file, "w");
			fwrite($fp, $str_cache_categories);
			fclose($fp);
		}

		return $categories;
	}

	function clear_categories_cache() {
		$cache_files	= $this->categories_cache_files();

		foreach($cache_files as $cache_file) {

			if ( file_exists($cache_file) ) {
				unlink($cache_file);
			}
		}
	}

	function categories_cache_files() {
		if ( !defined('BASE_PATH') ) {
			$file_path	= str_replace("lib".DS."modules".DS."categories","", dirname(__FILE__));
			define('BASE_PATH', $file_path);
		}

		$cache_files	= array(
			'all_categories' => BASE_PATH."cache".DS."data".DS."all_categories.php",
			'category_ids'	=> BASE_PATH."cache".DS."data".DS."category_ids.php",
			'categories_cache'	=> BASE_PATH."cache".DS."html".DS."categories_cache.php",
		);

		return $cache_files;
	}

	function cleanCode($code) {

		$cleanCode = "";
		for($i = 0 ; $i < mb_strlen($code, 'UTF-8') ; $i++) {
			if($code[$i] == '\\') {
				$cleanCode .= '';
			}else{
				$cleanCode .= $code[$i];
			}
		}

		$cleanCode2 = "";
		for($i = 0 ; $i < mb_strlen($cleanCode, 'UTF-8') ; $i++) {
			if($cleanCode[$i] == '"') {
				$cleanCode2 .= '\"';
			} else if ($cleanCode[$i] == "'") {
				$cleanCode2 .= '\\\'';
			}else{
				$cleanCode2 .= $cleanCode[$i];
			}
		}
		return $cleanCode2;
	}

	//recursive
	//max limit 0 => unlimited
	function show_categories($max_limit = 0, $is_check_publish = false) {
		$all_categories	= $this->get_categories();

	}

	function display_categories($data_categories, $default_category_id = 0, $ctg_id = 0) {
		$str_list	= "";
		foreach($data_categories as $data_category) {

			$seo_title_encode	= $this->SeoKeywordEncode($data_category['category_name']);

			if ( $ctg_id == $data_category['category_id'] ) continue;

			if ( $data_category['category_id'] == $default_category_id ) {
				$str_list	.= '<option selected value="'.$data_category['category_id'].'">';
			} else {
				$str_list	.= '<option value="'.$data_category['category_id'].'">';
			}

			$level		= $data_category['level'];
			while ( $level > 1 ) {
				$str_list	.= $this->name_prefix;
				$level		= $level - 1;
			}

			$str_list	.= "&raquo;".$this->name_prefix.$data_category['category_name']."</option>";

			if ( count($data_category['subcategories']) > 0 ) {
				$str_list	.= $this->display_categories($data_category['subcategories'], $default_category_id, $ctg_id);
			}

		}
		return $str_list;
	}


} // Class END
?>