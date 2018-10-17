<?php
$keywords = explode("\n", file_get_contents('word1.txt'));

//simple two combi

// foreach ($keywords as $key=>$value) {
// 	$v = strtolower(preg_replace('/\s+/', '', $value));
// 	if (end($keywords)==$value) {
// 		echo $v."<br />";
// 	}else {
// 		echo $v.strtolower($keywords[$key+1])."<br />";
// 		echo strtolower(preg_replace('/\s+/', '', $keywords[$key+1])).$v."<br />";
// 	}
	
// }

$keyarray=array();
foreach ($keywords as $key=>$value) {
		// echo $keywords[0].$keywords[$key+1]."<br />";
		// echo $value."<br />";
		array_push($keyarray, $value);

}
// shuffle($keywords);
// shuffle($keyarray);
foreach($keywords as $index => $code ) {
	foreach ($keyarray as $key => $value) {
		echo $code.$value . "<br />";
	}   
}

?>