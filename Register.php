<?php
session_start();

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
	
}else{
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
		<script>
		function validatePassword(){
			var regexCheck = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
			var pass = document.forms["regForm"]["password"].value;
			if(regexCheck.test(pass)){
				return true;
			}else{
				alert("Passwords must; \n-Contain 1 uppercase letter \n-Contain 1 lower case letter \n-Contain 1 numeric character \n-Be at least 8 characters long")
				return false;
			}
		}
		</script>
		
		<meta charset="UTF-8">
		<title>Secure App : Register</title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Secure App System</h1>
		<h2>Please enter your details below to Register</h2>
		<?php		
			if(isset($_SESSION["errorCode"])){
				$errC = $_SESSION["errorCode"];
				switch($errC){
					case 1:
						echo "<h3>Account successfully registered!<br>Please login to continue.</h3>";
						break;
					case 2:
						echo "<h3>Username already present.</h3>";
						break;
					case 3:
						echo "<h3>Surprise! It's an error.</h3>";
						break;
				}
			}
		?>  
		<form name= "regForm" action="RegisterCheck.php" method="POST">
			<label><b>Username</b></label>
			<input type="text" name="username" placeholder="Enter Username" required><br><br>
	
			<label><b>Password</b></label>
			<input type="password" name="password" placeholder="Enter Password" required><br><br>
	
			<input type="submit" name="submit" value="Submit" onclick="return validatePassword()">
			<a href = "/Index.php">
			<input type="button" value="Login">
			</a>
		</form>
	</body>
</html>

<?php
	unset($_SESSION["uName"]);
    unset($_SESSION["errorCode"]);
?>