<?php
date_default_timezone_set("UTC");
session_start();
$sesh_id = $_SESSION["Session_ID"];

$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "app_db";

$conn = new mysqli($servername, $username, $password, $databasename);//new msql connection

$uValue = $_POST["username"];
$user = htmlspecialchars($uValue);
$pword = $_POST["password"];

// First checking that the username and password
$selectSQL = "SELECT * FROM user_db WHERE username='$user'"; //SQL query to get users with the same username

if($conn->connect_error){//if connection error returns a message as such
	die("Connection failed: " . $conn->connect_error);
} 

//Assure that there are variables for username, password.
if(!isset($uValue) || !isset($pword)){
	$errorC = 0;
	$_SESSION["errorCode"] = $errorC;
	header("Location:Index.php");
	exit();
}

//Query to check if Session_ID is already in the db
$session_check = "SELECT * FROM `login_db` WHERE `session_id` = '$sesh_id'";
$result =$conn->query($session_check);
$count = mysqli_num_rows($result);

//if not present, kicked back to login
if($count == 0){
	$add_sesh_id = "INSERT INTO `login_db`(`session_id`, `failed_login_count`, `last_failed_login`) VALUES ('$sesh_id', 0, DEFAULT)";
	$conn->query($add_sesh_id);
}

if($count == 1){
	//get values associated with the row
	$row = $result->fetch_assoc();
	
	//getting specific values to check for lockout	
	$row_count = $row['failed_login_count'];
	$row_time = strtotime($row['last_failed_login']);
		
	//Calculate time between now and the last login
	$now = time();
	$diff = $now - $row_time;		
		
	//If difference failed count is equal to or greater than 3
	//and last attempt was less than 5m ago.
	if($row_count >= 3 && $diff < 300){
		//Return locked out
		$errorC = 2;
		$_SESSION["errorCode"] = $errorC;
		header("Location:Index.php");
		exit();
	}
	else{ //if not locked out continue in execution.
	
		//Query to check for the Username in the DB.
		$usern_query = "SELECT * FROM user_db WHERE username='$user'";
		$result=$conn->query($usern_query);
		
		if($result !== FALSE){
			//getting associated values from row
			$row = $result->fetch_assoc();
			
			//comparing values
			if(password_verify($pword, $row['password'])){
				
				//reset failed login count in login_db
				$reset_logins = "UPDATE `login_db` SET `failed_login_count`=0 WHERE `session_id`= '$sesh_id'";
				$conn->query($reset_logins);
					
				//check for duplicates in auth_db
				$check_auth = "SELECT * FROM `auth_db` WHERE `session_id` = '$sesh_id'";
				$check = $conn->query($check_auth);
				$count = mysqli_num_rows($check);
					
				//if there are duplicates present, clear them and continue
				if($count != 0){
					$clear_duplicate = "DELETE FROM `auth_db` WHERE `session_id` = '$sesh_id'";
					$conn->query($clear_duplicate);
				}
				
				//Update authorised table to contain session_ID
				$update_auth = "INSERT INTO `auth_db`(`session_id`, `username`, `last_activity`) VALUES ('$sesh_id', '$user', now())";
				$conn->query($update_auth);
				header("Location:Welcome.php");	
				exit();
			}
			else{//Incorrect login details 
				//Update failed logins, return to Login page
				$login_fail = "UPDATE `login_db` SET `failed_login_count`=`failed_login_count`+1 ,`last_failed_login`=now() WHERE `session_id` = '$sesh_id'";
				$conn->query($login_fail);
					
				$errorC = 1;
				$_SESSION["errorCode"] = $errorC;
				$_SESSION["uName"] = $user;
				header("Location:Index.php");
				exit();
			}
		}else{//if username not present in DB
			//Update failed logins, return to login page.
			$login_fail = "UPDATE `login_db` SET `failed_login_count`=`failed_login_count`+1 ,`last_failed_login`=now() WHERE `session_id` = '$sesh_id'";
			$conn->query($login_fail);
					
			$errorC = 1;
			$_SESSION["errorCode"] = $errorC;
			$_SESSION["uName"] = "test"; //$user;
			header("Location:Index.php");
			exit();
		}
	}
}else{
	$errorC = 4;
	$_SESSION["errorCode"] = $errorC;
	$_SESSION["uName"] = $user;
	header("Location:Index.php");
	exit();
}
$conn->close();
?>