<?php
if ($_SERVER['REQUEST_METHOD']=="GET") {
	exit("no");
}
$search_q = $_POST['qu'];
if (!isset($search_q) || empty($search_q)) {
	exit("sorry");
	die();
}
require ( "sphinxapi.php" );
require ( "config.php" );
require ( "database.php" );
class User {
	public $id;
	public $title;	
	public $url;	
	public $content;	
	public $click;	
	public static function find_by_sql($sql="") {
		global $db;
		$result = $db->perform_query($sql);
		$object_array =array();
		 while ($row = $db->fetch_array($result)) {
		 	$object_array[] = self::instantiate($row);
		 }
		return $object_array;
	}
	private static function instantiate($record) {
		$object = new self;
		foreach($record as $attribute=>$value) {
			if ($object->has_attribute($attribute)) {
				$object->$attribute = $value;				
			}
		}
		return $object;
	}
 	private function has_attribute($attribute) {
 		$object_vars = get_object_vars($this);
 		return array_key_exists($attribute, $object_vars);
 	}
}

$host = "127.0.0.1";
$port = "9312";
$cl = new SphinxClient();
$cl->SetServer ($host, $port);
$cl->SetArrayResult (true);
$cl->SetIndexWeights(array('title' => 20, 'url' => 10, 'content'=>5));
$cl->SetMatchMode(SPH_MATCH_EXTENDED2);
$cl->SetSortMode(SPH_SORT_EXTENDED, '@relevance DESC');
if (isset($search_q)) {
	$result = $cl->Query ("{$search_q}", "test4");
	echo $cl->GetLastError() ;
	if (isset($result['matches'])) {
			$array = $result['matches'];
			if (!empty($array)) {
				foreach ($array as $value) { // foreach 1
					$query = "SELECT * FROM indexed WHERE id={$value['id']}";
					$content = User::find_by_sql($query);
					foreach ($content as $r) { // foreach 2
						$doc = array("$r->content", "$r->url", "$r->title");
						$options = array(
			        'before_match'          => '<b>',
			        'after_match'           => '</b>',
			        'chunk_separator'       => ' ... ',
			        'limit'                 => 150,
			        'around'                => 10,
			);
			$res = $cl->BuildExcerpts($doc, 'test4', "{$search_q}", $options);
						
						$out='<div id="search_item"><div class="title"><a href="'.$r->url.'" target="_blank">'.$res[2].'</a></div>';
							$out.='<div class="url">'.$res[1].'</div>';
							$out.='<div class="cont">'.$res[0].'</div></div>';
						echo $out;		
					} // foreach 2
				}  // foreach 1

				}//end of if (!empty($array))
				else {
					echo "<br /><b>No results for your query</b><br />";
				}
		}//end of if isset($result['matches'])
		else {
					echo "<br /><b>No results for your query</b><br />";
				}
}//end of if isset($search_q)
?>
