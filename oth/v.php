<?php
$file = "word1.txt";
$keywords = explode("\n", file_get_contents($file));
foreach ($keywords as $key) {
	$url = preg_replace('/\s+/', '', $key);	
	$url = strtolower($url).".com";
	$result=checkdnsrr($url, "ANY");
	if ($result) {
		echo $url." === true<br />";
	}else {
		echo $url." === False<br />";
	}
}

?>