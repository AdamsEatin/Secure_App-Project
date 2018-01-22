<?php
session_start();
//If the Session ID variable is set on page load;
if(isset($_SESSION["Session_ID"])){
	$sesh_id = $_SESSION["Session_ID"];
	
	//Removing sessionID from auth DB 
	//to prevent going back to restricted pages
	$servername = "localhost";
	$username = "root";
	$password = "secret";
	$databasename = "app_db";
	$conn = new mysqli($servername, $username, $password, $databasename);
	
	$sesh_id = $_SESSION["Session_ID"];
	$auth_check = "DELETE FROM `auth_db` WHERE `session_id` = '$sesh_id'";
	$conn->query($auth_check);
	
}else{//If the Session ID variable is not set on page load
	
	//Get User' IP address and User Agent, use them along with a salt to generate a unique Session ID
	$ip = $_SERVER['REMOTE_ADDR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$str = $ip . " " . $user_agent;
	
	$salt = uniqid(mt_rand(), true);
	$s_id = crypt($str, $salt);
	$_SESSION["Session_ID"] = $s_id;
	$sesh_id = $_SESSION["Session_ID"];
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Secure App : Login </title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System</h1>
		<h2>Please enter your details below to Login</h2>
		<?php
			if(isset($_SESSION["errorCode"])){
				$errC = $_SESSION["errorCode"];
				switch($errC){
					case 0:
						echo "<h3>Error receiving Authorization</h3>";
						break;
					case 1:
						$user = $_SESSION["uName"];
						echo "<h3>Account: $user<br>Failed to authenticate username and password at this time.<br><br>Lockout will occur after 3 failed attempts within 5 minutes.</h3>";
						break;
					case 2:
						echo "<h3>This session is currently locked out.</h3>";
						break;
					case 3:
						echo "<h3>Username not recognized.</h3>";
						break;
					case 4:
						echo "<h3>No Session in DB, try again.</h3>";
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
					case 9:
						echo "<h3>Account has timed out due to inactivity.</h3>";
						break;
					case 10:
						echo "<h3>Session not valid.</h3>";
						break;
				}
			}
		?>  
		
		<form action="LoginCheck.php" method="POST">
			<label><b>Username</b></label>
			<input type="text" name="username" placeholder="Enter Username" required><br><br>
	
			<label><b>Password</b></label>
			<input type="password" name="password" placeholder="Enter Password" required><br><br>
	
			<input type="submit" name="submit" value="Submit">
			<a href = "/Register.php">
			<input type="button" value="Register">
			</a>
		</form>
	</body>
</html>

<?php
	unset($_SESSION["errorCode"]);
?>