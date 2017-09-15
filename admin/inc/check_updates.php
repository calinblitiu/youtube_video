<?php

include('version.php');

$latestVersion = implode('', file('http://www.prismotube.com/version/get.php')); 

if(PT_VERSION < $latestVersion)
	echo '<div class="error">PrismoTube version: '.$latestVersion.' has been launched<br>Visit <a href="http://prismotube.com">www.prismotube.com</a> for more info.</div>';

else if (PT_VERSION == $latestVersion)
	echo '<div class="success">You are using PrismoTube Version: '.PT_VERSION.' It is the latest release.</div>';

else
	echo '<div class="error">Update Check Failed</div>'. 

?>