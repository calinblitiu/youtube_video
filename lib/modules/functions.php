<?php
	function has_children($position) // return TRUE if that position has sub-categories otherwise returns FALSE
	{
		$check_sql = "SELECT id FROM `".DB_PREFIX."categories` WHERE position RLIKE  '^".$position."[0-9]+>$'";
		$check_res = dbQuery($check_sql) or die(trigger_error("<br><storng><u>Ошибка MySQL:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$check_sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
		$check = mysql_fetch_array($check_res);
		if($check['id'] != "")return TRUE;
		else return FALSE;
	}

	function get_children($position , $id = 0){
		$subcategories = array();

		$sql = "SELECT `id`, `position`, `c_name`, `c_listing_source`, `c_keyword`
			FROM `".DB_PREFIX."categories`
			WHERE position	RLIKE '^".$position."[0-9]+>$'
			ORDER BY c_name";

		$res = dbQuery($sql) or die(trigger_error("<br><storng><u>MySQL Error:</u></strong><br>".mysql_error()."<br><br><storng><u>Query Used:</u></strong><br>".$sql."<br><br><storng><u>Info:</u></strong><br>",E_USER_ERROR));
		$idx2 = 0;
		while($child = mysql_fetch_array($res)){

			$subcategories[$idx2]['id']					= $child['id'];
			$subcategories[$idx2]['position']			= stripslashes($child['position']);
			$subcategories[$idx2]['c_name']				= stripslashes($child['c_name']);
			$subcategories[$idx2]['c_listing_source']	= stripslashes($child['c_listing_source']);
			$subcategories[$idx2]['c_keyword']			= stripslashes($child['c_keyword']);
			$subcategories[$idx2]['prefix']				= get_prefix($child['position']);
			$subcategories[$idx2]['has_children']		= 0;
			$subcategories[$idx2]['subcategories']		= array();


			if($id != 0)
			{
				//$this->c_list_by_id[$child['id']] = $child;
				$has_children = has_children($child['position']);
				$subcategories[$idx2]['has_children'] = ( ($has_children) ? 1 : 0 );
				if($has_children == TRUE){
					get_children($child['position']);
				}
				continue;

				}else{

				// lets check if there is sub-categories
				//c_list[$child['id']] = $child;
				$has_children = has_children($child['position']);
				if($has_children == TRUE) { $subcategories[$idx2]['subcategories'] = get_children($child['position']); }
			}
			$idx2++;
		}
		return $subcategories;
	}

	function get_prefix($position)
	{
		$prefix = "";
		$position_slices = explode(">",$position);
		$count = count($position_slices) - 1;
		for($i=1 ; $i < $count ; $i++){
		$prefix .= "--";
		}
		return $prefix;
	}
?>