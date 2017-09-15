<?php
#################################################################################
//Simple File Manager PHP Script v1.5.													
//Created By Dave Earley.															
//Dave-Earley.com																	
//3rd April 10																	
//Do whatever you wish with the script (but please leave this notice here)		
#################################################################################

@session_start();
include_once("../../config/config.php");
include_once('../inc/functions.php');

$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;
if ( !$is_logged ){
	header("Location: ".$config['website_url']."admin");
	exit(0);
} else if ( isDemo() ) {
	echo 'disable in Demo';
	exit(0);
}

define("DS", DIRECTORY_SEPARATOR);

#Function save file (Only if file is writeable)
  function save_file() {  
	if($_POST['save_file'] && $_GET['file']){
		if($handle = fopen($_GET['where'].$_GET['file'] , 'w')){
			if (fwrite($handle, stripslashes($_POST['content'])) === FALSE) {
				$warning_message .= "Sorry, cannot write to file";
			}else{
				$success_message .= "File successfully saved!";
			}
		}else{
			$warning_message .= "Invalid file!!!";
		}
	}
  if($warning_message){
	echo "
	
	<div title='Click to close' class='message_warning close'>$warning_message</div>

	";
} else if ($success_message) {	echo "
	
	<div title='Click to close' class='message_success close'>$success_message</div>

	";}
  }
  
  
function download_file($where, $filename)
{

$filename = $where.$filename;

// fix for IE catching or PHP bug issue
header("Pragma: public");
header("Expires: 0"); // set expiration time
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

// force download dialog
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");

// use the Content-Disposition header to supply a recommended filename and
// force the browser to display the save dialog.
header("Content-Disposition: attachment; filename=".basename($filename).";");


header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($filename));

readfile($filename);
}
  
#Function create a new folder
function create_folder () {
		if($_POST['create_folder']){
		if(@mkdir($_GET['where'].$_POST['folder_name'])){
			$success_message = "Folder successfully created!";
		}else{
			$warning_message = "Sorry, Something went wrong!";
		}
	}
	if($warning_message){
	echo "
	
	<div title='Click to close' class='message_warning close'>$warning_message</div>

	";
} else if ($success_message) {	echo "
	
	<div title='Click to close' class='message_success close'>$success_message</div>

	";}
	
}

#Function to convert filesize from bytes.
function file_size($size){
	$i=0;
	$iec = array("B", "Kb", "Mb", "Gb", "Tb");
	while (($size/1024)>1) {
	$size=$size/1024;
	$i++;}
	return(round($size,1)." ".$iec[$i]);
}

#Function to upload file and look for errors.
function upload_file() {
	if($_POST['upload_file'] == 'upload_file'){
		if($_FILES['file']['error'] == 8){
			$warning_message .= "File upload stopped by extension!!!";
		}
		if($_FILES['file']['error'] == 7){
			$warning_message .= "Failed to write file to disk!!!";
		}
		if($_FILES['file']['error'] == 6){
			$warning_message .= "Missing a temporary folder!!!";
		}
		if($_FILES['file']['error'] == 4){
			$warning_message .= "No file was uploaded!!!";
		}
		if($_FILES['file']['error'] == 3){
			$warning_message .= "The uploaded file was only partially uploaded!!!";
		}
		if($_FILES['file']['error'] == 2){
			$warning_message .= "The uploaded file exceeds the MAX_FILE_SIZE!!!";
		}
		if($_FILES['file']['error'] == 1){
			$warning_message .= "The uploaded file exceeds the upload_max_filesize!!!";
		}


		if(!$warning_message){
			if(file_exists($_GET['where'].$_FILES['file']['name']) and !$_POST['replace_file']){
				$warning_message .= "File exists already - Select overwrite to overwrite the file";
			}else{
				if(!@move_uploaded_file($_FILES["file"]["tmp_name"], $_GET['where'].$_FILES['file']['name'])){
					$warning_message .= "Failed to upload file!!!";
				}else{
					$success_message .= "File successfully uploaded!";
				}
			}
		}
	}
	if($warning_message){
	echo "
	
	<div title='Click to close' class='message_warning close'>$warning_message</div>

	";
} else if ($success_message) {	echo "
	
	<div title='Click to close' class='message_success close'>$success_message</div>

	";}
}


#Function to create a breadcrumb link back to start folder.
function breadcrumb($where)
{
	echo '<a id="breadcrumb" href="?do=view_files&where=../">Home</a>';
	     $bc=explode("/",$where);
while(list($key,$val)=each($bc)){
 $dir='';
 if($key > 1){
  $n=1;
  while($n < $key){
   $dir.='/'.$bc[$n];
   $val=$bc[$n];
   $n++;
  }
  if($key < count($bc)-1) echo ' < <a id="breadcrumb"  href="?do=view_files&where=..'.$dir.'/">'.$val.'</a>';
 }
}
}

/*Function to delete file  / folder and all files within.*/
    function delete_file($dirname){
        if(is_file($dirname)){
		   		echo "
	
	<div title='Click to close' class='message_success close'>Deleted file : $dirname</div>
";
       return @unlink($dirname);
			
        }
        elseif(is_dir($dirname)){
            $scan = glob(rtrim($dirname,'/').'/*');
            foreach($scan as $index=>$path){

                delete_file($path);
            }						   	
     return @rmdir($dirname);
        }

		
    }

#Main function to list files
 function list_files($where = '../') {
	
	   ?>

<link href="style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="custom.js"></script>
<script type="text/javascript" src="jquery.tablesorter.min.js"></script>

<div class="wrap">
<h2>Manage Files</h2>
<?=breadcrumb($_GET['where']);?>

<p>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="tablesorter" id="manage_table">
  <thead>
    <tr>
      <th width="710" scope="col">Name</th>
      <th width="70" scope="col">Edit</th>
      <th width="70" scope="col">Delete</th>
      <th width="80" scope="col">Download</th>
      <th width="70" scope="col">Size</th>
    </tr>
  </thead>
  <tbody>
    <?php
	     global $row;
	 	$dir = opendir($where);
 		while(false !== ($file = readdir($dir)))
		{
 		 if(is_file($where.$file))
				{
			    $size = filesize("$where/$file");
				}else 
				{
					$size = '-';
				}
#Edit here to hide certain files.
				if($file != "." && $file != ".." && $file != "Thumbs.db" && $file != ".DS_Store")
			{ 
			

				$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
				
			    if (empty($ext))
			    {
				    $ext = 'folder';
			    }	
				if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp' || $ext == 'gif')
				{
					$ext = 'image';
				}
				if ($ext == 'htaccess')
				{
					$ext = 'txt';
				}
				if (!file_exists("images/doc_img/icon_$ext.gif"))
								{
									$ext = 'generic';
									
								}

                $icon = '<img src="images/doc_img/icon_'. $ext . '.gif" /> ';
				
##Edit below to state if a file type can be edited or not  
            	if ($ext == 'html' || $ext == 'htm' || $ext == 'php' || $ext == 'js' || $ext == 'css'|| $ext == 'xml'|| $ext == 'txt')
				{
					$allow_edit = '1';
				} else {$allow_edit = '0';}
 		
 	?>
    <tr>
      <td><?=$icon?>
        <?php if($ext=='folder'){?>
        <div style=" display:inline; "title="Browse This Folder."><a href="?do=view_files&where=<?=$where.$file.'/'?>">
          <?=$file?>
          </a></div>
        <?php } else {echo $file;}?></td>
      <td><?php if($allow_edit == '1') { ?>
        <a href="?do=edit&file=<?=$file?>&where=<?=$where?>&ext=<?=$ext?>"><img src="images/edit.png" alt="edit" width="32" height="32" border="0" /></a>
        <?php } else {echo '-';} ?></td>
      <td><a href="?do=delete_file&file=<?=$file?>&where=<?=$where?>" onclick="return confirm('Are you sure?')"><img src="images/delete.png" alt="edit" width="32" height="32" border="0" /></a></td>
      <td> <?php if($ext=='folder'){?>-<?php } else{ ?><a href="?do=download_file&file=<?=$file?>&where=<?=$where?>"><img src="images/download.png" alt="edit" border="0" /></a><?php } ?></td>
      <td><?=file_size($size)?></td>
    </tr>
    <?php }  }closedir($dir); ?>
  </tbody>
</table>
<div id="upload_box">
  <form action="?where=<?=$where?>&do=upload_file" method="post" enctype="multipart/form-data" name="form_upload" id="form_upload" class="form_upload">
    Upload a file in current folder:
    <input type="file" name="file" id="file" />
    &nbsp;  &nbsp;
    <input name="replace_file" type="checkbox" value="1" />
    Overwrite existing file &nbsp;  &nbsp;
    <input type="submit" name="upload" id="upload" value="Upload" />
    <input name="upload_file" type="hidden" id="upload_file" value="upload_file" />
    <a id='close_upload' href="">[X]</a>
  </form>
</div>
<div id="folder_box">
  <form action="?where=<?=$where?>&do=create_folder" method="post" enctype="multipart/form-data" name="form_upload" id="form_upload" class="form_upload">
    Create a new folder. Folder name:
    <input name="folder_name" type="text" style="width:290px" />
    <input type="submit" name="create_folder" id="create_folder" value="Create folder" />
    <a id='close_folder' href="">[X]</a>
  </form>
</div>
<input  id='upload_button' name="" type="button" value="Upload Files" />
<input  id='folder_button' name="" type="button" value="Create Folder" />
<?php }

#Function display editable file.
 function edit_file($file, $where)
 {
$file = "$where/$file";
$fh = fopen($file, 'r');
$data = fread($fh, filesize($file));
fclose($fh);


?>

<link href="style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="custom.js"></script>
<script type="text/javascript" src="jquery.tablesorter.min.js"></script>

<div class="wrap">
<h2>Edit File</h2>

<?=breadcrumb($_GET['where']);?>

<script language="javascript" type="text/javascript" src="editarea/edit_area_full.js"></script>
<script language="javascript" type="text/javascript">
editAreaLoader.init({
	id : "page_content"		// textarea id
	,syntax: "<?= $_GET['ext']?>"			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
});


</script>
<form action="?do=save_file&where=<?=$where?>&file=<?=$_GET['file']?>" method="post" enctype="multipart/form-data">
  <textarea name='content' id='page_content' cols='95' rows='20'><?=$data?>
</textarea>
  <label>
    <input type="submit" name="save_file" id="button" value="Save File" />
  </label>
</form>
<?php

 
 }
  

?>
<?php 

				  switch ($_GET['do']) {
					  
					  case 'view_files':
                      list_files($_GET['where']);
					  break;
					  
					  case 'edit':
					  edit_file($_GET['file'], $_GET['where']);
                      break;
					  
					  case 'save_file':
					  save_file();
					  edit_file($_GET['file'], $_GET['where']);
                      break;
					  					  
					  case 'delete_file':
					  delete_file($_GET['where'].$_GET['file']);
					  list_files($_GET['where']);
                      break;
					  
					  case 'download_file':
					  download_file($_GET['where'], $_GET['file']);
					  list_files($_GET['where']);
                      break;
					  
					  case 'upload_file':
					  upload_file();
					  list_files($_GET['where']);
                      break;
					  
					  case 'create_folder':
					  create_folder();
					  list_files($_GET['where']);
                      break;
					  
					  default:
					  
					  $root_path = str_replace("admin".DS."filemanager", "", dirname(__FILE__));
                      list_files($root_path);
					  break;
					  
				                  }

  
  
  ?>
<br />
<br />
<span style="size:14px; border-bottom:dotted #333; 1px;">PHP File Browser by <a href="http://dave-earley.com">Dave-Earley.com</a></span></div>