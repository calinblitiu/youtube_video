<?
@session_start();
include('../../config/config.php');

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	die();
}

	//closing the directory
	@closedir($dir_handle);
	// themes list
	//define the path as relative
	$path = "../../templates/".$_GET["tpl"]."/themes/";
	//using the opendir function
	$dir_handle = @opendir($path) or die("Unable to open $path");
	//running the while loop
	$i = 0;
	while ($file = readdir($dir_handle)) 
	{
  		if($file!="." && $file!=".." && $file!="index.php") {
	   		$THEMES[$i] = $file;
			$i++;
		}
		
	}
	sort($THEMES);
	//closing the directory
	closedir($dir_handle);

?>
<select class="select" onchange="setThemeScreenshot('<?=$_GET["tpl"]?>', this.form.active_theme.options[this.form.active_theme.selectedIndex].value);" name="active_theme" id="active_theme">
			<?
	for ($i = 0 ; $i < sizeof($THEMES) ; $i++) {
	?>
		<option <? echo ($config["active_theme"] == $THEMES[$i]) ? "selected" : ""; ?> value="<?=$THEMES[$i]?>"><?=ucfirst($THEMES[$i])?></option>
	<?
	}
	?>
</select>
<br clear="all">
<?

$screenshot = "../templates/".$_GET["tpl"]."/themes/".$config["active_theme"]."/screenshot.jpg";

?>
<div align="center">
	<br clear="all"><br>
	<img id="theme_screenshot" class="theme_screenshot" src="<?=$screenshot?>" border="0" onLoad="setContent('theme_no_screenshot',''); this.style.display='block';" onError="setContent('theme_no_screenshot','No screenshot found for this theme!'); this.style.display='none';">
</div>
