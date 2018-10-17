<?php
require 'simple_html_dom.php';
require 'database.php';
require 'user.php';


function url($url) {
  $result = parse_url($url);
  return $result['scheme']."://".$result['host'];
}

function parse_url_missing_scheme($url) {     
     $str2 = substr($url, 2);
    return $str2;
}

function detect_redirect($url) {
	$urls = array($url);
	$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

foreach($urls as $url) {
    curl_setopt($ch, CURLOPT_URL, $url);
    $out = curl_exec($ch);

    // line endings is the wonkiest piece of this whole thing
    $out = str_replace("\r", "", $out);

    // only look at the headers
    $headers_end = strpos($out, "\n\n");
    if( $headers_end !== false ) { 
        $out = substr($out, 0, $headers_end);
    }   

    $headers = explode("\n", $out);
    foreach($headers as $header) {
        if( substr($header, 0, 10) == "Location: " ) { 
            $target = substr($header, 10);

            return $target;
            continue 2;
        }  
    }   

    return $url;
}
}

?>
<?php

$sql = "SELECT * FROM to_index LIMIT 100";
$links = User::find_by_sql($sql);
foreach ($links as $link) {
    $u = $link->url;
    if (strpos($u,'http://') !== false || strpos($u,'https://') !== false) {   
    $rawurl=$u;
    }else {
    $rawurl="http://".$u;
    }
    // $result=checkdnsrr($rawurl, "ANY");
    if (true) {
        //valid link process it
            $url = detect_redirect($rawurl);
            $ver =User::verify_link_exist($url, "indexed");
            if (!$ver) {
                try {
                $html = file_get_html($url);
                }
                catch (Exception $e) {
                echo $e->getMessage();
                 }
                if ($html) {
                     try {
                     // Find title and insert link to the 'indexed' table 
                    $title = $html->find('title',0)->innertext;
                    echo $title;
                     User::add_link_to_real($title, $url, $html->plaintext);
                    } catch (Exception $e) {
                        
                    }
                   
                $links_array = $html->find('a');
                    if ($links_array!=null && !empty($links_array)) {
                        foreach($links_array as $element) {
                            if (strpos($element->href, "#")===false && substr($element->href, 0, 10)!=="javascript" && strlen($element->href)!==0 && substr($element->href, 0, 6)!=="mailto" && !empty($element->href)) {
                                if (substr($element->href, 0, 2)=="//") {
                                    $link =parse_url_missing_scheme($element->href);
                                    echo $link. '<br>';
                                    User::add_link($link);
                                }else if (substr($element->href, 0, 1)=="/" || substr($element->href, 0, 1)=="?" && substr($element->href, 0, 2)!=="//") {
                                    $link =url($url).$element->href;
                                    echo $link . '<br>';
                                    User::add_link($link);
                                }  
                                // }elseif (preg_match('/^((?!-)[A-Za-z0-9-]{1,63}(?<!-)\\.)+[A-Za-z]{2,6}$/', $element->href)) {
                                //  echo $element->href . '<br>';
                                // }
                                else {
                                 $link =$element->href;
                                    echo $link . '<br>';
                                    User::add_link($link);
                                }
                            }
                            
                        }
                    }
                }
            }
            
    }else {
        echo "link not procesed<br />";
    }
    
}
	
?>


