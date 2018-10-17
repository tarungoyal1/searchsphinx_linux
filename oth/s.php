<?php
$keywords = explode("\n", file_get_contents('word1.txt'));
foreach ($keywords as $key) {
	echo strtolower($key)."<br />";
}
?>