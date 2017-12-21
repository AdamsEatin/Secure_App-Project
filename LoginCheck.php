<?php
unset($_SESSION["authToken"]);
if(!isset($_SESSION["authToken"])){
	$authError = "Error in receiving Authorization Token";
	$_SESSION["error"] = $authError;
	header("location: Index.php");
}
date_default_timezone_set("UTC");
session_start();

$servername = "localhost";
$username = "root";
$password = "secret";
$databasename = "app_db";

$conn = new mysqli($servername, $username, $password, $databasename);//new msql connection

$uValue = $_POST["username"];
$user = htmlspecialchars($uValue);
$pword = $_POST["password"];

$salt = uniqid(mt_rand(), true);//generate random salt

$selectSQL = "SELECT * FROM user_db WHERE username='$user'"; //SQL query to get users with the same username

if($conn->connect_error){//if connection error returns a message as such
	die("Connection failed: " . $conn->connect_error);
} 

//Query to check for username in db
if(($result=$conn->query($selectSQL)) !== FALSE){
	//get number of rows in response
	$count= mysqli_num_rows($result);
	
	if($count==1){
		//get values associated with row
		$row = $result->fetch_assoc();
		
		//getting values from row
		$rowCount = $row['failed_login_count'];
		$rowTime = strtotime($row['last_failed_login']);
		
		//calculating length of time between now and last login
		$now = time();
		$diff = $now - $rowTime;
		
		//If Account has less than 3 failed login attempts
		if($rowCount < 3){
			//Encrypt entered password using stored salt, compare to stored password
			if(crypt($pword, $row['salt']) == $row['password']){
				//SQL to update user acc: reset failed login count to 0
				$sql3 = "UPDATE user_db SET failed_login_count=0 WHERE username='$user'";
				
				if($conn->query($sql3) == TRUE){
					$id = uniqid();
					$_SESSION["authToken"] = $id;
					$_SESSION["uName"] = $user;
					header("Location:Welcome.php");
				}
			}
			else{
				//SQL to update user acc: increment failed login and update last failed attempt
				$sql2 = "UPDATE user_db SET failed_login_count=failed_login_count+1,last_failed_login=NOW() WHERE username='$user'";
				
				//Attempt to update failed login count + last failed attemptt
				if($conn->query($sql2) == TRUE){
					$authError = "Failed to authenticate username and password.\nLockout will occur at 3 failed attempts.";
					$_SESSION["error"] = $authError;
					$_SESSION["uName"] = $user;
					header("Location:Index.php");
				}
			}
		}
		else{
			//if time since most recent failed login is greater than 5 minutes
			if($diff > 300){
				//Encrypt entered password using stored salt, compare to stored password
				if(crypt($pword, $row['salt']) == $row['password']){
					//SQL to update user acc: reset failed login count to 0
					$sql3 = "UPDATE user_db SET failed_login_count=0 WHERE username='$user'";
				
					if($conn->query($sql3) == TRUE){
						$id = uniqid();
						$_SESSION["authToken"] = $id;
						$_SESSION["uName"] = $user;
						header("Location:Welcome.php");
					}
				}
				else{
					//Username Present and But Passwords dont match - Failed Login
					//SQL to update user acc: increment failed login and update last failed attempt
					$sql2 = "UPDATE user_db SET failed_login_count=failed_login_count+1,last_failed_login=NOW() WHERE username='$user'";
				
					//Attempt to update failed login count + last failed attemptt
					if($conn->query($sql2) == TRUE){
						//$updateRow = $updateRes->fetch_assoc();
						$authError = "Failed to authenticate username and password.\nLockout will occur at 3 failed attempts.";
						$_SESSION["error"] = $authError;
						$_SESSION["uName"] = $user;
						header("Location:Index.php");
					}					
				}
			}
			
			//Otherwise account is locked
			else{
				//Return locked out
				$lockoutError = "Account is currently locked out.";
				$_SESSION["error"] = $lockoutError;
				$_SESSION["uName"] = $user;
				header("Location:Index.php");
			}
		}
	}else{
		$error = "Username not recognised within the database.";
		$_SESSION["error"] = $error;
		$_SESSION["uName"] = $user;
		header("Location:Index.php");
	}
}else{
	$error = "Shits all sort of fucked yo.";
	$_SESSION["error"] = $error;
	header("Location:Index.php");
}
$conn->close();
?>