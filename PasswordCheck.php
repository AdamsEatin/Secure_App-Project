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
$get_user = "SELECT * FROM `auth_db` WHERE `session_id` = '$sesh_id'";
$user_res = $conn->query($get_user);
$row = $user_res->fetch_assoc();

$user = $row['username'];
$newPword = $_POST["newPass"];

//SQL query to get users with the same username
$checkSQL = "SELECT * FROM user_db WHERE username='$user'"; 


//Query to check for username in db

if($result=$conn->query($checkSQL)){
	$encPword = password_hash($newPword, PASSWORD_DEFAULT);

	//SQL to update entry
	$updateSQL = "UPDATE user_db SET password='$encPword' WHERE username='$user'";
	
	if($update = $conn->query($updateSQL)){
		$errorC = 7;
		$_SESSION["errorCode"] = $errorC;
		header("Location:Index.php");
	}
	else{
		$errorC = 8;
		$_SESSION["errorCode"] = $errorC;
		header("Location:PasswordChange.php");
	}	
}
$conn->close();	
?>