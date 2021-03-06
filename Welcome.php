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
	//set username value for printing to the screen.
	$user_name = $row['username'];
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
		<title>Secure App : Welcome </title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<?php echo "<h1>Welcome to the Secure App System : $user_name !</h1>"; ?> 
		
		<h2>The standard Lorem Ipsum passage, used since the 1500s</h2>
		<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."<br>
		Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."<br>
		Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."<br>
		Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
		
		<form>
			<button formaction="/Why.php">Why?</button>
			<button formaction="/Where.php">Where?</button><br><br>
			<button formaction="/PasswordChange.php">Chage Password</button>
			<button formaction="/Index.php">Logout</button>
		</form>
	</body>
</html>