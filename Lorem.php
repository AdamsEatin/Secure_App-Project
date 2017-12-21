<?php
session_start();
if(!isset($_SESSION["authToken"])){
	$authError = "Error in receiving Authorization Token";
	$_SESSION["error"] = $authError;
	header("location: Index.php");
}
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="ISO-8859-1">
		<title>Secure App : Why? </title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h3>Why do we use it?</h3>
		<p>"It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.<br>
		The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here,<br> 
		content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum<br> 
		as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.<br> 
		Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
		
		<form>
			<button value="Lorem" formaction="/Welcome.php">Welcome</button>
			<button value="Ipsum" formaction="/Ipsum.php">Where?</button>
			<button value="Logout" formaction="/Index.php">Logout</button>
		</form>
	</body>
</html>