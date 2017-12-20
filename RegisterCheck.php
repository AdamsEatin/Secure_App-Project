<?php
unset($_SESSION["authToken"]);
if(!isset($_SESSION["authToken"])){
	$authError = "Error in receiving Authorization Token";
	$_SESSION["error"] = $authError;
	header("location: Index.php");
}

session_start();

$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "app_db";

$conn = new mysqli($servername, $username, $password, $databasename);

$user = $_POST["username"];
$pword = $_POST["password"];

//generate random salt
$salt = uniqid(mt_rand(), true);
//salt paassword
$saltedPword = crypt($pword, $salt);

//SQL to insert new entry
$sql = "INSERT INTO `user_db`(`username`, `password`, `salt`) VALUES ('$user','$saltedPword','$salt')";
//SQL query to get users with the same username
$checkSQL = "SELECT * FROM user_db WHERE username='$user'"; 

if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
} 

//Query to check for username in db
if(($result=$conn->query($checkSQL)) !== FALSE){
	//get number of rows in response
	$count= mysqli_num_rows($result);
	
	if($count==0){
		if ($conn->query($sql) === TRUE) {
		$regSucc = "New record created successfully";
		$_SESSION["uName"] = $user;
		$_SESSION["error"] = $regSucc;
		header("Location:Index.php");
		} 
	}
	else{
		$regFail = "Username already taken";
		$_SESSION["error"] = $regFail;
		header("Location:Register.php");
	}
}

$conn->close();	
?>