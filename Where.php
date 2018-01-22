<?php
date_default_timezone_set("UTC");
session_start();

if(!isset($_SESSION["Session_ID"])){
	$errorC = 0;
	$_SESSION["errorCode"] = $errorC;
	header("location: Index.php");
	exit();
}
else{
	$servername = "localhost";
	$username = "root";
	$password = "secret";
	$databasename = "app_db";
	$conn = new mysqli($servername, $username, $password, $databasename);
	
	$sesh_id = $_SESSION["Session_ID"];
	$auth_check = "SELECT * FROM `auth_db` WHERE `session_id` = '$sesh_id'";
	$result = $conn->query($auth_check);
	
	//if Session_ID not in auth_DB kick em back to the login page.
	if($result == FALSE){
		$errorC = 0;
		$_SESSION["errorCode"] = $errorC;
		header("location:Index.php");
		exit();
	}
	$row = $result->fetch_assoc();
	$last_active = strtotime($row['last_activity']);
	$now = time();
	$diff = $now - $last_active;
	
	//checking for timeout after 5m of inactivity
	if($diff > 300){
		$errorC = 9;
		$_SESSION["errorCode"] = $diff;
		header("location:Index.php");
		exit();
	}
	$update_auth = "UPDATE `auth_db` SET `last_activity`= now() WHERE `session_id` = '$sesh_id'";
	$conn->query($update_auth);
}

$conn->close();
?>


<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<title>Secure App : Where? </title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Where does it come from?</h1>
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
			<button formaction="/Why.php">Why?</button>
			<button formaction="/Index.php">Logout</button>
		</form>
	</body>
</html>