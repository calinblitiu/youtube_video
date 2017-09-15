<?php

function smarty_modifier_seotitle($s)
{
  $c = array (' ','-','/','\\',',','.','#',':',';','\'','"','[',']','{',
      '}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');

  $s = str_replace($c, '-', $s);

  $s = preg_replace(
        array('/-+/',
              '/-$/',
              '/-ytmsinternsignature/'),
        array('-',
              '',
              'ytmsinternsignature') ,
        $s);
  return $s;
}

?>