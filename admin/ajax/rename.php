<?php
function RandomAlphanumeric( $underscores = 0, $length = 10) 
    {    
        
        $p =""; 
        for ($i=0;$i<$length;$i++) 
        {    
            $c = mt_rand(1,4); 
            switch ($c) 
            { 
                case ($c<=2): 
                    // Add a number 
                    $p .= mt_rand(0,9);    
                break; 
                case ($c<=4): 
                    // Add an uppercase letter 
                    $p .= chr(mt_rand(65,90));    
                break;        
            } 
        } 
        return $p; 
    } 
	
$is_renamed	= @rename("../../install", "../../install.".RandomAlphanumeric( 0 , 10 ));

if ( $is_renamed ) {
	echo 'Folder install has been renamed.';
} else {
	echo '<span style="color:red">We could not rename your directory. Please do it manually.</span>';
}


?>