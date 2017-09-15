<?php
$is_frontend = false;
include_once "../init.php";

$ts = time();


if($_POST["guest"] != "" && $_POST["comment"] != "") {

	if($_POST["code"] == ""){

		echo "error2";

	}else if(md5(strtoupper($_POST['code'])) == $_SESSION['__img_code__']) {
		$vid = $_POST["vid"];
		$guest = $_POST["guest"];
		$comment = $_POST["comment"];
		$comment = strip_tags($comment);
		
		echo $vid." ".$guest." ".$comment;

		addComment($vid, $guest,$comment);

	}else{

		echo "error3";
	}

}else{
	echo "error1";
}

?>