<?php
function redirect_to($location){
  header("Location: ".$location);
  exit();
}
if (isset($_POST['search_submit'])) {
    $search_q = isset($_POST['search']) ? trim($_POST['search']): null;
  $url = "/s.php?q=".urlencode($search_q);
  redirect_to ($url);  
}
?>
<!DOCTYPE html>
<html>
<head>
	</head>
	<body>
		<form id='myForm' action="" method="POST">
                <input id="search_input" name="search" type="text" value="<?php if (isset($search_q)) {echo htmlentities($search_q);}  ?>" placeholder="type and hit enter" required/>
          		<input type="submit" name="search_submit" value="Search" />
                
                </form>
			<div id="results">
		</div>
</body>
</html>
