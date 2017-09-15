<?php
//Статистика кэширования
$resultguest2 = dbQuery("SELECT count(*) as count FROM ".DB_PREFIX."guest_log");
list ($count) = mysql_fetch_row($resultguest2);

$handle = fopen(BASE_PATH.$config['html_cache_dir']."guests.html", "w");
fwrite($handle,$count);
fclose($handle);


?>