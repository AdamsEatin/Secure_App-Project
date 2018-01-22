<?php
date_default_timezone_set("UTC");
session_start();

if(!isset($_SESSION["Session_ID"])){
	$errorC = 0;
	$_SESSION["errorCode"] = $errorC;
	header("location: Index.php");
	
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
		<title>Secure App : Why? </title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Why do we use it?</h1>
		<p>"It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.<br>
		The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here,<br> 
		content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum<br> 
		as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.<br> 
		Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
		
		<form>
			<button value="Welcome" formaction="/Welcome.php">Welcome</button>
			<button value="Where" formaction="/Where.php">Where?</button>
			<button value="Logout" formaction="/Index.php">Logout</button>
		</form>
	</body>
</html>