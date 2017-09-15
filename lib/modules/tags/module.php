<?php
function stripTags($string) {
	global $config, $is_mbstring_enabled;
  
  // Does user-agent match $config['browsers_user_agent'] in config_advanced.php ?
	if ( is_browsers_user_agent() ) 
	{
		$tags = explode(" ", $string);

		for($i = 0 ; $i < sizeof($tags) ; $i++) 
		{
			$is_insert_tags		= true;
			
			// How long is it?
			if ( $is_mbstring_enabled ) 
			{
				$tags_len	= mb_strlen($tags[$i]);
			} 
			else 
			{
				$tags_len	= strlen($tags[$i]);
			}
			
			// Is it >3 & <11  ??
			if ( $tags_len < 3 && $tags_len > 11 ) 
			{
				$is_insert_tags = false;
			} 
			else 
			{
				$is_insert_tags = is_stop_words( trim($tags[$i]) ) ;
			}
			
      // Does it pass the keywords filter?
			if($is_insert_tags) 
			{
				if ( $config["keyword_filter_enabled"] == "true" ) 
				{
					if ( $config["keyword_filter_match"] == "exact" ) 
					{
						$filter_kwords		= explode("|", $config["keyword_filter_list"]);
						$regex_filter_kwords= array();
						foreach($filter_kwords as $filter_kword) 
						{
							$regex_filter_kwords[] = "\b".$filter_kword."\b";
						}
						$str_filter			= implode("|", $regex_filter_kwords);
						$banned_keywords	= "/(".$str_filter.")/i";
					} 
					else 
					{
						$banned_keywords	= "/(".$config["keyword_filter_list"].")/i";
					}
					preg_match( $banned_keywords, $tags[$i], $matches );
					
					if ( !isset($matches[1]) ) 
					{
						addTag($tags[$i]);
					}
				} 
				else 
				{
					addTag($tags[$i]);
				}
			}
		}
	}
}

function addTag($tag) {
	global $config;
	$tag	= trim($tag);
	
	if ( strlen($tag) > 150 ) { 
		return;
	}
	
	if ( $config['tag_cloud_enabled'] && $tag != '' ) 
	{ 
		$query	="INSERT INTO `".DB_PREFIX."video_tags` SET `tag` = '".db_escape_string($tag)."', `quantity` = '1' ON DUPLICATE KEY 
			UPDATE `tag` = '".db_escape_string($tag)."', `quantity` = (`quantity` + 1);";
		dbQuery($query);
		return;
	}
}

function cacheTags() {
  	global $tags_list, $tpl, $config;

	$mini_tags_count = ceil(($config['mini_tag_percent'] * $config['tags_max_display']) / 100);

	// change these font sizes if you will
	$tag_max_size = $config['tag_max_size']; // max font size in %
	$tag_min_size = $config['tag_min_size']; // min font size in %

	$count = 0;
	
	if ( is_array($tags_list) && count($tags_list) > 0 ) {
		// get the largest and smallest array values
		$max_qty = max(array_values($tags_list));
		$min_qty = min(array_values($tags_list));

		// find the range of values
		$spread = $max_qty - $min_qty;
		if (0 == $spread) { // we don't want to divide by zero
			$spread = 1;
		}

		// determine the font-size increment
		// this is the increase per tag quantity (times used)
		$step = ($tag_max_size - $tag_min_size)/($spread);

		$mini_tags = "";
		$tags = "";
		
		// loop through our tag array
		foreach ($tags_list as $tagName => $value) {
			// calculate CSS font-size
			// find the $value in excess of $min_qty
			// multiply by the font-size increment ($size)
			// and add the $tag_min_size set above
			$size = $tag_min_size + (($value - $min_qty) * $step);
			// uncomment if you want sizes in whole %:
			// $size = ceil($size);

			$tags .= '<div class="tagLink"><a href="'.prismo_print($tagName).'/" style="font-size: '.$size.'%"';
			$tags .= ' title="'.prismo_print($tagName).' has been searched '.$value.' times"';
			$tags .= '>'.prismo_print($tagName).'</a></div> ';
			// notice the space at the end of the link

			$count++;
		}
	}
	
	if($count == 0) {
	    $tags .= '<div class="tagLink">';
	    $tags .= 'No Tags Found!';
	    $tags .= '</div>';
	}

	$handle = fopen(BASE_PATH.$config['html_cache_dir']."tags.html", "w");
	fwrite($handle,$tags); 
	fclose($handle);
}


function shuffle_assoc( $array )
{
   $keys = array_keys( $array );
   shuffle( $keys );
   return array_merge( array_flip( $keys ) , $array );
} 




if ( defined('BASE_PATH') ) 
{
	
	if ( $config['tag_cloud_enabled'] ) 
	{
		$is_recreate_tags	= false;
		if ( file_exists(BASE_PATH.$config['html_cache_dir']."tags.html") )
		{
			$time_exists	= time() - filemtime(BASE_PATH.$config['html_cache_dir']."tags.html");

			if ( $time_exists > $config['tags_cache_timeout'] ) 
			{
				$is_recreate_tags	= true;
			}
		} 
		else 
		{	
			$is_recreate_tags	= true;
		}
		
		if ( $is_recreate_tags ) 
		{
		
		/* 
		Update the tags.html timestamp to prevent second instance from 
		being triggered before the first has been completed 
		*/
		touch( BASE_PATH.$config['html_cache_dir']."tags.html" );
		
			if ( isset($config['tags_max_display']) ) 
			{
        		$query = "SELECT * FROM `".DB_PREFIX."video_tags` ORDER BY rand() LIMIT ".$config['tags_max_display'];
			} 
			else 
			{
        		$query = "SELECT * FROM `".DB_PREFIX."video_tags` ORDER BY rand()";
			}
		
			// tags view if tags selection and tags selection limit exist
			if(isset($config['tags_selection']) && $config['tags_selection'] == 'top')
			{
				if(isset($config['tags_max_display']))
					$query = "SELECT * FROM `".DB_PREFIX."video_tags` ORDER BY `quantity` DESC LIMIT ".$config['tags_max_display'];
				else
					$query = "SELECT * FROM `".DB_PREFIX."video_tags` ORDER BY `quantity` DESC";
			}

			$result = dbQuery($query, false);

			while ($row = mysql_fetch_array($result)) 
			{
				$tag_name	= stripslashes($row['tag']);
				$tags_list[$tag_name] = $row['quantity'];
			}
			
			$tags_list = shuffle_assoc(  $tags_list );

			if ( $config['tag_cloud_enabled'] ) 
			{
				cacheTags();
			}
		}
	}
}

?>