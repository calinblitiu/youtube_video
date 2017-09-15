<?php
header("Content-Type: text/plain"); 
include_once "init.php";
if ( $config['allow_spiders'] == 1 || !isset($config['allow_spiders']) || $config['allow_spiders'] == '' ) {
?>User-agent: *
Disallow:
<?php } else if ( $config['allow_spiders'] == 2 ) { ?>
User-agent: *
Disallow: /
User-agent: Googlebot
Allow: /
User-agent: Mediapartners-Google
Allow: /
User-agent: Googlebot-Image
Allow: /
User-agent: Slurp
Allow: /
User-agent: Msnbot
Disallow: 
User-agent: bingbot
Allow: /
<?php } ?>