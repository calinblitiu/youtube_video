<?
function Xml2Array($data) {
  $p = xml_parser_create();
  xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
  xml_parse_into_struct($p, $data, &$vals, &$index);
  xml_parser_free($p);

  $tree = array();
  $i = 0;
  $tree = Xml2ArrayGetNodes($vals, $i);
  return $tree;
}

function Xml2ArrayGetNodes($vals, &$i) {                 
  $nodes = array();
  if ($vals[$i]['value']) {
    array_push($nodes, $vals[$i]['value']);
  }
    
  $prevtag = "";
  $count_vals = count($vals);
  while (++$i < $count_vals) {
    switch ($vals[$i]['type']) {
      case 'cdata':
        array_push($nodes, $vals[$i]['value']);
        break;
      case 'complete':                     
        $nodes[ strtolower($vals[$i]['tag']) ] = $vals[$i]['value'];           
        break;
      case 'open':              
        $j++;
        if ($prevtag <> $vals[$i]['tag']) {
          $j = 0;   
          $prevtag = $vals[$i]['tag'];
        }                             
        $nodes[ strtolower($vals[$i]['tag']) ][$j] = Xml2ArrayGetNodes($vals,$i);
        break;
      case 'close':         
        return $nodes;
    }
  }
}
?>