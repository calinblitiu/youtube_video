<?php

include_once ('../config/license_key.php');
include_once ('../config/config.php');
include_once ('../config/admin_login.php');

$show_login_form  = 0 ;
$show_login_fail  = 0 ;
$current_year     = date('Y') ;
$PT_VERSION       = PT_VERSION ;
$num_of_tweets    = 3 ;

// Get twitter feed
	$feed_url	= 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name=prismotube&include_rts=1&include_entities=1&count='.$num_of_tweets;
	$twitter_feed_data = url_get_contents( $feed_url , '../'.$config["html_cache_dir"].'AdminLoginTwitter.html' , 86400);// cache for a day
	$twitter_feed = json_decode( $twitter_feed_data , true);
	$num = 0;




    if( is_array( $twitter_feed ) )// Degrade nicely if somehow there's something wrong with the feed
    {
      $twitter_feed_html .= '<div class="twitter_feed_top">&nbsp;</div>';
        foreach( $twitter_feed as $twitter_feed)
        {
          if( $num < $num_of_tweets )
          {
            $twitter_feed['text'] = replace_tco( $twitter_feed['text'] , $twitter_feed) ;
            $twitter_feed['created_at'] = date('dS M Y', strtotime($twitter_feed['created_at']) );
            $twitter_feed_html .= '<div class="twitter_feed">';
            $twitter_feed_html .= " ${twitter_feed['text']} <br>";
            $twitter_feed_html .= '<font>' . $twitter_feed['created_at'] . '</font>';
            $twitter_feed_html .= '</div>';
          }
          $num++;
        }
      $twitter_feed_html .= '<div class="twitter_feed_bottom"><a href="http://twitter.com/prismotube" target="_blank"><b>View more tweets @prismotube</b></a></div>';
    }


function login()
{
	global $config , $show_login_form, $show_login_fail , $post_username , $post_password ;

	$is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;

	if ( !$is_logged )
	{
      //if nothing posted, show login form
	    if (empty($_POST))
	    {
        $post_username	= isset($_POST['username']) ? $_POST['username'] : 'Username';
        $post_password	= isset($_POST['password']) ? $_POST['password'] : '';
        $show_login_form = 1;

	    }
	    else
      {
        //If something posted
        $post_username	= $_POST['username'];
        $post_password	= $_POST['password'];

        if ($_POST["username"]==$config["admin_username"] && $_POST["password"]==$config["admin_pass"] )
        {
            $_SESSION['logged']=TRUE;

            // Login success
            echo '<div align="center" class="success"> Welcome '.$config["admin_username"].', you will be redirected in 5 sec... <a href="'.$config['website_url'].'admin/?login=true">Click Here</a></b><br><meta http-equiv="refresh" content="3;url='.$config['website_url'].'admin/?login=true"/></div>';
            $show_login_form = 0;
        }
        else
        {
          $is_logged	= isset($_SESSION['logged']) ? $_SESSION['logged'] : false;

            if ( !$is_logged )
            {
              // Wrong username or password
              $show_login_form = 1;
              $show_login_fail = 1;
            }
        }
        }
	}
	else
	{
		// Already logged in
		return 2;
	}

}



// Start rendering Form !!



include('templates/login/header.php');
login();

if( $show_login_form ){?>


    <form method="post" action="">
          <div class="box-top">&nbsp;</div>
          <div class="box">

            <?if( $show_login_fail  ){?>

                <div class="error">
                  Login Failed: Wrong Username / Password<br />
                  <a href="password_recovery.php">
                    Click here to recover your password
                  </a>
                </div>

            <?}?>

            <div class="username">
               <input type="text" class="input_text" name="username" id="username" value="<?=${post_username}?>" onfocus="if(this.value=='Username')this.value=''" onblur="if(this.value=='')this.value='Username'" />
            </div>

            <div class="password">
              <input type="text" class="input_text" id="hide" value="Password" onclick="javascript:switchto(1)" onkeydown="switchto(1)">
              <input type="password" class="input_text" name="password" id="password" value="<?=${post_password}?>" onblur="if(this.value=='')switchto(0)" style="display:none">
            </div>

            <div class="login">
              <input class="button" type="submit" name="submit" value="" />
                </div>

                <h5>
                  Copyright &copy; 2008 - <?=${current_year}?> PrismoTube <?=$PT_VERSION?></span><br>
                  All Rights Reserved
                </h5>
            </div>
            <div class="box-bottom">&nbsp;</div>

     </form>

    <script type="text/javascript">
    function switchto(q)
    {
        if (q)
        {
            document.getElementById("hide").style.display = "none";
            document.getElementById("password").style.display = "inline";
            document.getElementById("password").focus();
        }
        else
        {
            document.getElementById("password").style.display = "none";
            document.getElementById("hide").style.display = "inline";
        }
    }
    </script>


    <?=$twitter_feed_html;?>

<?}


include('templates/login/footer.php');
?>