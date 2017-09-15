<?php
include_once "../init.php";

if(!isset($_GET["size"])) {
	echo "adsize param is missing";
	exit();
}

$adsize = $_GET["size"];
$banner = $config["ads_$adsize"];
?>

<html>
<body topmargin="0" bottommargin="0" rightmargin="0" leftmargin="0">
<?=$banner?>
</body>
</html>
