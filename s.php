<?php
function redirect_to($location){
  header("Location: ".$location);
  exit();
}
  $search_q = isset($_GET['q']) ? trim($_GET['q']): null;
    if (isset($_POST['search_submit'])) {
  $query = isset($_POST['search']) ? trim($_POST['search']): null;
  $url = "s.php?q=".urlencode($query);
  redirect_to ($url);
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<style type="text/css">
		#search_item {
			border: 1px solid grey;
			padding: 15px 20px;
			margin: 10px 0;
		}
		.title{
			color: blue;
			font-size:1.1em;
		}

		.url{
			color: green;
			font-size:0.9em;
		}
	</style>
	<script type="text/javascript">
  window.onload = function(){
    ajaxFunction();
  }
function ajaxFunction(){
 var ajaxRequest;  // The variable that makes Ajax possible!
	
 try{
   // Opera 8.0+, Firefox, Safari
   ajaxRequest = new XMLHttpRequest();
 }catch (e){
   // Internet Explorer Browsers
   try{
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
   }catch (e) {
      try{
         ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      }catch (e){
         // Something went wrong
         alert("Your browser broke!");
         return false;
      }
   }
 }
 // Create a function that will receive data 
 // sent from the server and will update
 // div section in the same page.
 ajaxRequest.onreadystatechange = function(){
   if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('results');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
   }
 }
 // Now get the value from user and pass it to
 // server script.
 var searchquery = document.getElementById('search_input').value;
 var queryString = "qu=" + searchquery ;
 ajaxRequest.open("POST", "ajax.php", true);
 ajaxRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
 ajaxRequest.send(queryString);
}
</script>
	</head>
	<body>
		<form id='myForm' action="" method="POST">
                <input id="search_input" name="search" type="text" value="<?php echo htmlentities($search_q); ?>" placeholder="type and hit enter" required/>
          		<input type="submit" name="search_submit" value="Search" />
                
                </form>
			<div id="results">
		</div>
</body>
</html>
