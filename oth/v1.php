<?php
$url = "http://mytinydb.com";
$result=dns_check_record($url, "ANY");
	if ($result) {
		echo $url." === true<br />";
	}else {
		echo $url." === False<br />";
	}


?>