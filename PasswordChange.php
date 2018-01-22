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
		<script>
		function validatePassword(){
			var regexCheck = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
			var pass = document.forms["pwForm"]["newPass"].value;
			if(regexCheck.test(pass)){
				return true;
			}else{
				alert("Passwords must; \n-Contain 1 uppercase letter \n-Contain 1 lower case letter \n-Contain 1 numeric character \n-Be at least 8 characters long")
				return false;
			}
		}
		</script>
		
		<meta charset="UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<title>Secure App : Password Change</title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Password Change</h1>
		<h2>Please enter the your new password below.</h2>
		<?php
			if(isset($_SESSION["errorCode"])){
				$errC = $_SESSION["errorCode"];
				switch($errC){
					case 0:
						echo "<h3>Error receiving Authorized Token</h3>";
						break;
					case 1:
						$user = $_SESSION["uName"];
						echo "<h3>Account: $user<br>Failed to authenticate username and password at this time.<br>Lockout will occur after 3 failed attempts.</h3>";
						break;
					case 2:
						echo "<h3>This account is currently locked out.</h3>";
						break;
					case 3:
						echo "<h3>Username not recognized.</h3>";
						break;
					case 4:
						echo "<h3>This should not have happened...</h3>";
						break;
					case 5:
						echo "<h3>Account successfully registered!<br>Please login to continue.</h3>";
						break;
					case 6:
						echo "<h3>Username already present.</h3>";
						break;
					case 7:
						echo "<h3>Password changed successfully.<br>Please login with your new password to re-authenticate.</h3>";
						break;
					case 8:
						echo "<h3>Updating password has failed.</h3>";
						break;
				}
			}
		?>  
		<form name= "pwForm" action="PasswordCheck.php" method="POST">
			<label><b>New Password</b></label>
			<input type="password" name="newPass" placeholder="New Password" required><br><br>
	
			<input type="submit" value="Submit" onclick="return validatePassword()">
			<a href = "/Welcome.php">
			<input type="button" value="Back">
		</form>
	</body>
</html>

<?php
    unset($_SESSION["error"]);
?>