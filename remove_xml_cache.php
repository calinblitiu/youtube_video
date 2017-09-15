<?php
/*
 Delete cache files that are over 'xml_cache_timeout' in seconds
 parameters: display_limit , report , xml_cache_timeout
 Note: Clearing 100,000 files took 18 secs on our server
*/
@set_time_limit(0);
include_once "init.php";

  $num_files_deleted	= 0;
  $xml_cache_dir      = BASE_PATH.$config['xml_cache_dir'];
  $report             = 0 ;
  $cache_info		  = Array();
  $total_cache_files  = 0;
	$last_cache_clearing_file		= BASE_PATH."cache/.last_cache_clearing.txt";
  $display_limit  = isset( $_GET['display_limit'] ) ? $_GET['display_limit'] : 1000;

  $report 	          = isset( $_GET['report'] ) ? true : false;
  $xml_cache_timeout 	= isset( $_GET['xml_cache_timeout'] ) ? $_GET['xml_cache_timeout'] : $config['xml_cache_timeout'];
  $time_since_last_cleared = 0;
  $start_time = $finish_time = $total_exec_time = '';

  if( is_file( $last_cache_clearing_file ) )
  {
    $time_since_last_cleared = time() - (int) file_get_contents( $last_cache_clearing_file ) ;
  }

// If '&all', means cache_timeout is zero.
// We now use '&xml_cache_timeout=0' for greater control
  if ( isset($_GET['all']) )
  {
    $xml_cache_timeout = 0;
  }

// ################# Start reading cache folder and delete them ###############

    // Start Benchmark
    $time = explode(' ', microtime() );
    $start_time = $time[1] + $time[0];

if ( $handle = opendir( $xml_cache_dir ) )
{
    while ( false !== ($entry = readdir($handle)) )
    {
        if ($entry != "." && $entry != ".." && is_file( $xml_cache_dir.$entry ))
        {
          // If file is .gz or .txt (cache files)
          if( ( substr($entry,-3,3) == '.gz' || substr($entry,-4,4) == '.txt' ) )
          {
            $total_cache_files++;
            $cache_age	= time() - filemtime(  $xml_cache_dir.$entry  );

            if( !isset( $cache_info['newest']['age'] ) )
            {
              $cache_info['newest']['age'] = $cache_age;
            }

            if( $cache_age > $cache_info['oldest']['age'] )
            {
              $cache_info['oldest']['filename']	= $entry;
              $cache_info['oldest']['age']		= $cache_age;
            }
            if( $cache_age < $cache_info['newest']['age'] )
            {
              $cache_info['newest']['filename']	= $entry;
              $cache_info['newest']['age']		= $cache_age;
            }

            // If cache file is old enough to be deleted
            if( $cache_age > $xml_cache_timeout)
            {
              $num_files_deleted++;
              @unlink( $xml_cache_dir.$entry );
              if( $total_cache_files <= $display_limit )
              {
                $logmsg_deleted .= "<b>${entry}</b> ( $cache_age secs old) <br>";
              }

            }
            else
            {
              if( $total_cache_files <= $display_limit )
              {
                $logmsg_intact .= "<b>${entry}</b> ( $cache_age secs old) <br>";
              }
            }

          }

        }
    }
    closedir($handle);

    //Set the last cache clearing time
    file_put_contents( $last_cache_clearing_file , time() );
}

    // Stop Benchmark
    $time = explode(' ', microtime() );
    $finish_time = $time[1] + $time[0];
    $total_exec_time = number_format( (float) ($finish_time - $start_time) , 2, '.' , '');

// Report
if( $report ){?>
    <p><b>Очистить кэш данных</b> <br>
    <p>This process runs every hour (3600 secs) by default <br>
    Last time this process was run :<?=$time_since_last_cleared?> secs ago <br>
    Cache files cleared: <?=$num_files_deleted?> out of <?=$total_cache_files?> (only <?=$display_limit?> are displayed here)<br>
    Newest cache file : <?=$cache_info['newest']['filename']?> ( <?=$cache_info['newest']['age']?> secs old , about ~<?echo  @get_time_ago( $cache_info['newest']['age'] );?>)<br>
    Oldest cache file : <?=$cache_info['oldest']['filename']?> ( <?=$cache_info['oldest']['age']?> secs old , about ~<?echo  @get_time_ago( $cache_info['oldest']['age'] );?>) <br>
    Time taken : <?=$total_exec_time?> secs
	<hr>

    Deleted:<br> <?=$logmsg_deleted?> <hr>
    Not Deleted:<br> <?=$logmsg_intact?> <hr><br>

<?}?>

<?if($config['xml_cache_timeout'] < $cache_info['oldest']['age']){?>
There might to be a problem with your cache clearing. </br>
Try running this again ...
<?}?>