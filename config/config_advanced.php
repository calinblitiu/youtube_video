<?php
//started with ampersand e.g $config['youtube_extra_params'] = '&restriction=DE';
$config['youtube_extra_params']	= '';
	
/*
The keyword filter redirects visitors away from the page when a keyword triggers the filter.
We set the maximum redirect frequency below to prevent an infinite loop.
*/
$config['max_kwfilter_redir'] = 5;
$config['browsers_user_agent'] = "Firefox|MSIE|Safari|Opera|Chrome";// Left out 'Mozilla' coz Googlebot useragent contains it
$config['youtube_reset_quota_timeout'] = 600;
//$config['error_reporting'] = -1;
$config['stop_words']	= "\bvs\b|\bjs\b|\ba\b|\babout\b|\bafter\b|\bagainst\b|\ball\b|\balso\b|\balthough\b|\bamong\b|\ban\b|\band\b|\bare\b|\bas\b|\bat\b|\bbe\b|\bbecame\b|\bbecause\b|\bbeen\b|\bbetween\b|\bbut\b|\bby\b|\bcan\b|\bcome\b|\bdo\b|\bduring\b|\beach\b|\bearly\b|\bfor\b|\bform\b|\bfound\b|\bfrom\b|\bhad\b|\bhas\b|\bhave\b|\bhe\b|\bher\b|\bhis\b|\bhowever\b|\bin\b|\binclude\b|\bincluding\b|\binto\b|\bis\b|\bit\b|\bits\b|\blate\b|\blater\b|\bme\b|\bmade\b|\bmany\b|\bmay\b|\bmore\b|\bmost\b|\bnear\b|\bno\b|\bnon\b|\bnot\b|\bof\b|\bon\b|\bonly\b|\bor\b|\bother\b|\bover\b|\bseveral\b|\bshe\b|\bsome\b|\bsuch\b|\bthan\b|\bthat\b|\bthe\b|\btheir\b|\bthen\b|\bthere\b|\bthese\b|\bthey\b|\bthis\b|\bthrough\b|\bto\b|\bunder\b|\buntil\b|\buse\b|\bwas\b|\bwe\b|\bwere\b|\bwhen\b|\bwhere\b|\bwhich\b|\bwho\b|\bwith\b|\byou\b";
?>