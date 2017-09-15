<?php
include('../inc/version.php');

function echoVersionInfo() {


	$latestVersion = implode('', file('http://www.prismotube.com/version/get.php')); 

	if(PT_VERSION < $latestVersion)
		echo '<div class="error">PrismoTube version: '.$latestVersion.' has been launched<br>Visit <a href="http://prismotube.com" target="_blank">www.prismotube.com</a> for more info.</div>';

	else if (PT_VERSION == $latestVersion)
		echo '<div class="success">You are using PrismoTube Version: '.PT_VERSION.' It is the latest release.</div>';

	else if(PT_VERSION > $latestVersion)
		echo '<div class="error">'.PT_VERSION.' > '.$latestVersion.' Update Check Failed</div>';

}

echoVersionInfo();

?>