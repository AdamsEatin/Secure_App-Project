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
		<title>Secure App : Where? </title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body>
		<h3>Where does it come from?</h3>
		<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC,<br> 
		making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more<br> 
		obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature,<br> 
		discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum"<br> 
		(The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance.
		The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.<br><br>
		The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from<br> 
		"de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions<br> 
		from the 1914 translation by H. Rackham.</p>
		
		<form>
			<button formaction="/Welcome.php">Welcome</button>
			<button formaction="/Lorem.php">Why?</button>
			<button formaction="/Index.php">Logout</button>
		</form>
	</body>
</html>