<?php
if(!isset($_SESSION["authToken"])){
	$errorC = 0;
	$_SESSION["errorCode"] = $errorC;
	header("location: Index.php");
}

session_start();

$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "app_db";

$conn = new mysqli($servername, $username, $password, $databasename);

$user = $_SESSION["uName"];
$newPword = $_POST["newPass"];

//SQL query to get users with the same username
$checkSQL = "SELECT * FROM user_db WHERE username='$user'"; 

if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
} 

//Query to check for username in db
if(($result=$conn->query($checkSQL)) !== FALSE){
	$row = $result->fetch_assoc();
	$newSaltedPword = crypt($newPword, $row['salt']);
	//SQL to update entry
	$updateSQL = "UPDATE user_db SET password='$newSaltedPword' WHERE username='$user'";
	
	if($conn->query($updateSQL) == TRUE){
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