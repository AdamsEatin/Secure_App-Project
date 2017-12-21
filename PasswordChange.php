<?php
session_start();
if(!isset($_SESSION["authToken"])){
	$authError = "Error in receiving Authorization Token";
	$_SESSION["error"] = $authError;
	header("location: Index.php");
}
?>

<!DOCTYPE html>
<html>
	<head>
		<script>
		function validatePassword(){
			var regexCheck = new pwExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
			var pass = document.forms["pwForm"]["newPass"].value;
			if(regexCheck.test(pass)){
				return true;
			}else{
				alert("Passwords must; \n-Contain 1 uppercase letter \n-Contain 1 lower case letter \n-Contain 1 numeric character \n-Be at least 8 characters long")
				return false;
			}
		}
		</script>
		
		<meta charset="ISO-8859-1">
		<title>Secure App : Password Change</title>
		<link rel="stylesheet" type="text/css" href="main_page.css">
	</head>
	
	<body>
		<h1>Password Change</h1>
		<h2>Please enter password details below to change</h2>
		<?php
			if(isset($_SESSION["error"])){
				$error = $_SESSION["error"];
				$saltedOldP = $_SESSION["old"];
				echo "<h3>$error</h3><br>$saltedOldP<br>";
			}
			if(isset($_SESSION["uName"])){
				$user = $_SESSION["uName"];
				echo "<span>$user</span>";
			}
		?>  
		<form name= "pwForm" action="PasswordCheck.php" method="POST">
			<label><b>New Password</b></label>
			<input type="password" name="newPass" placeholder="Enter New Password" required><br><br>
	
			<input type="submit" value="Submit" onclick="return validatePassword()">
			<button formaction="/Welcome.php">Back</button>
		</form>
	</body>
</html>

<?php
    unset($_SESSION["error"]);
?>