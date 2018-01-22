<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "app_db";
$conn = new mysqli($servername, $username, $password, $databasename);

$uValue = $_POST["username"];
$user = htmlspecialchars($uValue);
$pword = $_POST["password"];

//Assure that there are variables for username, password.
if(!isset($uValue) || !isset($pword)){
	$errorC = 3;
	$_SESSION["errorCode"] = $errorC;
	header("Location:Register.php");
	exit();
}

//if not set, kicked back to login
if(!isset($_SESSION["Session_ID"])){
	$errorC = 10;
	$_SESSION["errorCode"] = $errorC;
	header("Location:Index.php");
	exit();
}
else{
	//SQL query to get users with the same username
	$checkSQL = "SELECT * FROM user_db WHERE username='$user'";
	
	//check if the username is currently in the DB
	if(($result=$conn->query($checkSQL)) !== FALSE){
		$count = mysqli_num_rows($result);
		
		//if no rows returned. ie; username not present
		if($count == 0){
			$encPword = password_hash($pword, PASSWORD_DEFAULT);
		
			$insert_pword = "INSERT INTO `user_db`(`username`, `password`) VALUES ('$user','$encPword')";
			$conn->query($insert_pword);

			$errorC = 5;
			$_SESSION["errorCode"] = $errorC;
			header("Location:Index.php");
			exit();
			
		}else{
			$errorC = 2;
			$_SESSION["errorCode"] = $errorC;
			header("Location:Register.php");
			exit();
		}
	}
}
$conn->close();	
?>