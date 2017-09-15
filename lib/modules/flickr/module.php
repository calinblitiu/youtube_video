<?
	require_once("phpFlickr.php");
	
	function get_flickr_photos($flickr_auto_kword) {
		global $config;
		$flickr_photos = array();
		if ( isset($config['flickr_enabled']) && $config['flickr_enabled'] == 'true' ) {
			$flickr = new phpFlickr($config['flickr_api_key'], $config['flickr_secret']);
			$flickr->cache			= 'fs';
			$flickr->cache_dir 		= BASE_PATH.$config['xml_cache_dir'];
			$flickr->cache_expire	= $config['xml_cache_timeout'];
			$flickr->cache_key		= 'flickr_';
			
			$num_flickr_display	= $config['num_flickr_display'];
			$rd_flickr_type		= $config['rd_flickr_type'];
			$flickr_search_mode	= $config['flickr_search_mode'];
			$flickr_key			= $config['flickr_key'];


			if ( $flickr_auto_kword == "" ) {
				if ( $rd_flickr_type == 'user_id' ) {
					$recent = $flickr->photos_search (array("user_id"=>$flickr_key, "per_page" => $num_flickr_display));
				} else if ( $rd_flickr_type == 'keyword' ) {
					if ( $flickr_search_mode == 'loose' ) {
						$recent = $flickr->photos_search (array("text"=>urlencode($flickr_auto_kword), "per_page" => $num_flickr_display, "safe_search" => 1));
					} else if ( $flickr_search_mode == 'exact' ) {
						$flickr_auto_kword	= '"'.$flickr_auto_kword.'"';
						$recent = $flickr->photos_search (array("text"=>urlencode($flickr_auto_kword), "per_page" => $num_flickr_display, "safe_search" => 1));
					}
				}
			} else {
				$recent = $flickr->photos_search (array("text"=> urlencode($flickr_auto_kword), "per_page" => $num_flickr_display, "safe_search" => 1));
			}
			
			$idx = 0;
			if ( isset($recent['photo']) && count($recent['photo']) > 0 ) {
				foreach ($recent['photo'] as $data_photo ) { 
					$flickr_photos[$idx]['img']	= '<a target="_blank" href="http://www.flickr.com/photos/'.$data_photo['owner'].'/'.$data_photo['id'].'"><img src="http://farm'.$data_photo['farm'].'.static.flickr.com/'.$data_photo['server'].'/'.$data_photo['id'].'_'.$data_photo['secret'].'_s.jpg" border="0" /></a>';	
					$idx++;
				}
			}
			
		}
		
		return $flickr_photos;
	}
?>