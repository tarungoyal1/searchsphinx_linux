<?php
if (isset($_POST['search_submit'])) {
    $query = isset($_POST['search']) ? trim($_POST['search']): null;  
}
require 'simple_html_dom.php';
require 'database.php';
require 'user.php';

if (strpos($query,'http://') !== false || strpos($query,'https://') !== false) {   
    $rawurl=$query;
}else {
    $rawurl="http://".$query;
}



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
<form action="" method="post">
                <input id="search_input" type="text" name="search" value="" placeholder="type and hit enter" required/>
                <button name="search_submit" id="searchImg">submit</button>
                </form>
<?php
if (isset($_POST['search_submit'])) {
    $query = isset($_POST['search']) ? trim($_POST['search']): null;  
$url = detect_redirect($rawurl);

// $redirectedurl = detect_redirect($rawurl);
// $url = rtrim($redirectedurl, '/');
echo $url."<br /><hr />";



try {
    $html = file_get_html($url);
}
catch (Exception $e) {
    echo $e->getMessage();
}


try {
	// Find title 
$title = $html->find('title',0)->innertext;
echo $title.'<br />';
} catch (Exception $e) {
	
}

// try {
// 	//Find meta Description
// $meta_description = $html->find("meta[name='description']", 0)->innertext;
// echo $meta_description;
// } catch (Exception $e) {
	
// }

// try {
// 	//Find meta keywords
// $meta_keywords = $html->find("meta[name='keywords']", 0)->innertext;
// echo $meta_keyworWWWds;
// } catch (Exception $e) {
	
// }

// Find all links
if ($html) {
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

User::add_link_to_real($title, $url, $html->plaintext);
echo "<hr />";
echo $title;
echo "<hr />";
echo $url;
echo "<hr />";
echo $html->plaintext;


// ///paragraph
// $array = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
// foreach ($array as $h) {
//     $tag = $html->find($h);
//     foreach ($tag as $e ) {
//     echo $e->innertext . '<br>';
//     }
// }
//     foreach ( $html->find('p') as $e ) {
//     echo $e->innertext . '<br>';
//     }


 //Dont comment   
    }else {
    	echo "false";
    }
}	
?>


