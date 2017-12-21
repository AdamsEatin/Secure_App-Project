<?php
session_start();
unset($_SESSION["authToken"]);
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