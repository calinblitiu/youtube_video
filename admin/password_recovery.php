<?php 
@session_start();

include_once ('../config/license_key.php');
include_once ('../config/config.php');
include_once ('../config/admin_login.php');
include_once ('inc/functions.php');

$error_msg	= '';
$is_sent	= 0;
$domain_name	= get_domain_name();
$time_stamp = time();



if ( isset($_SESSION['sent']) ) 
{
	$c_email	= $config['admin_email'];
	$mail_dats	= explode("@", $c_email);
	$mail_dats[0]	= substr_replace($mail_dats[0], str_repeat('*', strlen($mail_dats[0])-1), 1, strlen($mail_dats[0]));
	$c_email	= implode("@", $mail_dats);
	
	$error_msg	= '<div align="center" class="success">Your login info has been sent to your email ( '. $c_email.' ) <br />
	<br /><a href="'.$config['website_url'].'admin">Click here to go back</a></div>';
	$is_sent	= 1;
	unset($_SESSION['sent']);
}


// Send the Login info

    if ( isset($_POST['btnSubmit']) ) 
    {
        $code	= isset($_POST['code']) ? trim($_POST['code']) : '';
        
        if ( $code == '' ) 
        {
          $error_msg	= '<div class="error">You have entered the random code wrongly. <br>Please try again</div>';
        } 
        else if (md5(strtoupper($_POST['code'])) == $_SESSION['__img_code__']) 
        {
        
          $subject = 'Password Recovery - '.$config['website_name'];
          $message = "Your Prismotube Admin Login on ".$config['website_url']."admin/ ".":\r\nUsername: ".$config['admin_username']."\r\nPassword: " . $config['admin_pass']."\r\n\r\nThanks,\r\nPrismotube";
          $headers = 'From: do-not-reply@'.$domain_name . "\r\n" .
                    'Reply-To: do-not-reply@'.$domain_name . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();


          @mail($config["admin_email"], $subject, $message, $headers);
          
          $_SESSION['sent'] = 1;
          
          header("Location: password_recovery.php");
          exit(0);
        } 
        else 
        {
          $error_msg	= '<div class="error">You have entered the random code wrongly. <br>Please try again</div>';
        }
    }

include('templates/login/header.php');


?>

      <div class="box-top">&nbsp;</div>
      <div class="box">
      
        <div style="text-align:center;margin: auto;width:300px;">
          <script type="text/javascript" src="<?=$config['website_url']?>js/jquery.js?040308"></script>
          <script type="text/javascript" src="<?=$config['website_url']?>js/common.js?040308"></script>

          <form method="post" action="">

                <div style="font-size:2em;font-family: 'Courgette';"><b>Password Recovery<b></div>

                <br>
                
                <?=$error_msg?>

                <?if ( $is_sent == 0 ) {?>
                <div class="label" style="width:150px;">Random Code</div>

                <input style="color:#000000" type="text" name="code" id="code" value="" /><br><br>


                <div class="label" style="width:150px;">&nbsp;</div>

                <img id="__code__" src="<?=$config['website_url']?>lib/validator/code.php?id=<?=$time_stamp?>" style="border:1px solid #000000" /><br />
                <a style="color:#FFFFFF;" href="#" onclick="getNewImgCode('<?=$config['website_url']?>','__code__'); return false;">Click for new code</a>

                <br />
                <br />
                <input  style="width:130px;color:#000000;" type=submit name="btnSubmit" value="Retrieve Password" />
                <?}?>



          </form>
        </div>
        
      </div>
      <div class="box-bottom">&nbsp;</div>     
        
        
<?
include('templates/login/footer.php');
?>