<?php

    function replace_tco($text,  $twitter_feed_json) 
    {
         $items = $twitter_feed_json['entities']['urls'];
         
          foreach ($items as $item) {
           $url = $item['url'];
         
            $text = str_replace( $item['url'] , '<a href='.$item['expanded_url'].' target="_blank">'.$item['display_url'].'</a>' , $text  );
           }
         
          return $text;  
    }

	function get_tweeter_feeds($twitter_auto_kword) {
		global $config, $is_gzip_enabled;
		
		if ( isset($config['twitter_enabled']) && $config['twitter_enabled'] == 'true' ) {
			$num_twitter_display	= $config['num_twitter_display'];
			$rd_twitter_type		= $config['rd_twitter_type'];
			$twitter_search_mode	= $config['twitter_search_mode'];
			$twitter_key			= $config['twitter_key'];
			
			if ( $twitter_auto_kword == "" || $twitter_auto_kword == "0" ) {
				if ( $rd_twitter_type == 'author' ) {
					$feed_url	= "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=${twitter_key}&include_rts=1&include_entities=1";
				} else if ( $rd_twitter_type == 'keyword' ) {
					if ( $twitter_search_mode == 'loose' ) {
						$feed_url	= "http://search.twitter.com/search.atom?q=".urlencode($twitter_key);
					} else if ( $twitter_search_mode == 'exact' ) {
						$feed_url	= "http://search.twitter.com/search.atom?q=\"".urlencode($twitter_key)."\"";
					}
				}
			} else {
				$feed_url	= "http://search.twitter.com/search.atom?q=\"".urlencode($twitter_auto_kword)."\"";
			}

			if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
				$filename 		= BASE_PATH.$config['xml_cache_dir'] . md5($feed_url) . ".gz";
			} else {
				$filename 		= BASE_PATH.$config['xml_cache_dir'] . md5($feed_url) . ".txt";
			}
			
			// read cache file if possible
			if ( file_exists($filename) ) {
				$time_exists	= time() - filemtime($filename);
				if ( $time_exists > $config['xml_cache_timeout']) {
					$xml =  REST_Request_Common($feed_url);
					WriteToFile($filename, $xml);
				} else {
					$xml	= file_get_contents($filename);
				}
			} else {
				$xml =  REST_Request_Common($feed_url);
				WriteToFile($filename, $xml);
			}
			
			if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
				$datas		= gzfile($filename);
				$xml		= implode('', $datas);
			}
			
			
			
			$twitter_feeds	= array();
			$idx	= 0;
			$check_error	= json_decode($xml, true);
			if(isset($check_error['error']))
			{
				return false;
			}
			// Based on author
			if ( ( $twitter_auto_kword == "" || $twitter_auto_kword == "0" ) && $rd_twitter_type == 'author' ) {
				$datas	= json_decode($xml, true);
				
				if ( is_array($datas) ) 
				{

					foreach( $datas as $twitter_feed_item ) {
						
						if ( $idx == $num_twitter_display ) {
							break;
						}
						
						$twitter_feeds[$idx]['author'] 		= $twitter_key;
						$twitter_feeds[$idx]['desc'] 		= replace_tco( $twitter_feed_item['text'] , $twitter_feed_item );														
						$twitter_feeds[$idx]['image']		= $twitter_feed_item['user']['profile_image_url'];
						$twitter_feeds[$idx]['link']		= "https://twitter.com/".$twitter_feed_item['user']['name']."/status/".$twitter_feed_item['id_str'];
						$twitter_feeds[$idx]['author_link']	= "https://twitter.com/".$twitter_key;
						
					
						$idx++;
					}
				}
			} 
			else 
			{
			// Based on Keywords

				$twitter_feed = simplexml_load_string( $xml );
				$datas = json_decode(json_encode((array) $twitter_feed), 1);

				if ( is_array($datas) ) {
					foreach($datas['entry'] as $twitter_feed_item) {
						
						if ( $idx == $num_twitter_display ) {
							break;
						}
							
						$author_name_r	= explode(" ", $twitter_feed_item['author']['name']);
						$author_id_str	= $author_name_r[0];

						$twitter_feeds[$idx]['author'] 		= $author_id_str;												
						$twitter_content					= html_entity_decode($twitter_feed_item['content']);						
						if ( !has_anchor_tag($twitter_content) ) {
							$twitter_content				= auto_link_text($twitter_content);
						}
						$twitter_feeds[$idx]['desc'] 		= $twitter_content;
						$twitter_feeds[$idx]['image']		= $twitter_feed_item['link'][1]['@attributes']['href'];
						$twitter_feeds[$idx]['link']		= "http://twitter.com/".$twitter_key."/statuses/".$feed_id;
						$twitter_feeds[$idx]['author_link']	= "http://twitter.com/".$twitter_key;
						
						$idx++;

					}
				}
				
			}	 

			
		}

			// Fallback in case category keywords returns 0 result
			// Very badly done, needs restructuring
			if ( count($twitter_feeds) == 0  ) {
				if ( $rd_twitter_type == 'author' ) {
					$feed_url	= "http://twitter.com/statuses/user_timeline/".$twitter_key.".rss";
				} else if ( $rd_twitter_type == 'keyword' ) {
					if ( $twitter_search_mode == 'loose' ) {
						$feed_url	= "http://search.twitter.com/search.atom?q=".urlencode($twitter_key);
					} else if ( $twitter_search_mode == 'exact' ) {
						$feed_url	= "http://search.twitter.com/search.atom?q=\"".urlencode($twitter_key)."\"";
					}
				}	

				if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
					$filename 		= BASE_PATH.$config['xml_cache_dir'] . md5($feed_url) . ".gz";
				} else {
					$filename 		= BASE_PATH.$config['xml_cache_dir'] . md5($feed_url) . ".txt";
				}
			
				// read cache file if possible
				if ( file_exists($filename) ) {
					$time_exists	= time() - filemtime($filename);
					if ( $time_exists > $config['xml_cache_timeout']) {
						$xml = REST_Request_Common($feed_url);
						WriteToFile($filename, $xml);
					} else {
						$xml	= file_get_contents($filename);
					}
				} else {
					$xml = REST_Request_Common($feed_url);
					WriteToFile($filename, $xml);
				}
				
				if ( $is_gzip_enabled && ($config['cache_storage_format'] == 'auto' || $config['cache_storage_format'] == 'gzip') ) {
					$datas		= gzfile($filename);
					$xml		= implode('', $datas);
				}
				
				if ( $rd_twitter_type == 'author' ) {
					$datas	= json_decode($xml, true);
					
					if ( is_array($datas) ) 
					{

						foreach( $datas as $twitter_feed_item ) {
							
							if ( $idx == $num_twitter_display ) {
								break;
							}
							
							$twitter_feeds[$idx]['author'] 		= $twitter_key;
							$twitter_feeds[$idx]['desc'] 		= replace_tco( $twitter_feed_item['text'] , $twitter_feed_item );														
							$twitter_feeds[$idx]['image']		= $twitter_feed_item['user']['profile_image_url'];
							$twitter_feeds[$idx]['link']		= "https://twitter.com/".$twitter_feed_item['user']['name']."/status/".$twitter_feed_item['id_str'];
							$twitter_feeds[$idx]['author_link']	= "https://twitter.com/".$twitter_key;
							
						
							$idx++;
						}
				}
				} else if ( $rd_twitter_type == 'keyword' ) {

					$twitter_feed = simplexml_load_string( $xml );
					$datas = json_decode(json_encode((array) $twitter_feed), 1);

					if ( is_array($datas) ) {
						foreach($datas['entry'] as $twitter_feed_item) {
							
							if ( $idx == $num_twitter_display ) {
								break;
							}
								
							$author_name_r	= explode(" ", $twitter_feed_item['author']['name']);
							$author_id_str	= $author_name_r[0];

							$twitter_feeds[$idx]['author'] 		= $author_id_str;												
							$twitter_content					= html_entity_decode($twitter_feed_item['content']);						
							if ( !has_anchor_tag($twitter_content) ) {
								$twitter_content				= auto_link_text($twitter_content);
							}
							$twitter_feeds[$idx]['desc'] 		= $twitter_content;
							$twitter_feeds[$idx]['image']		= $twitter_feed_item['link'][1]['@attributes']['href'];
							$twitter_feeds[$idx]['link']		= "http://twitter.com/".$twitter_key."/statuses/".$feed_id;
							$twitter_feeds[$idx]['author_link']	= "http://twitter.com/".$twitter_key;
							
							$idx++;

						}
					}
				
				}
			}
			// End of Fallback
			
			

		return $twitter_feeds;
	}
?>