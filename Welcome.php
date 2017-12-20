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
		<meta charset="ISO-8859-1">
		<title>Secure App : Welcome </title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body>
		<?php
			if(isset($_SESSION["uName"])){
				$user = $_SESSION["uName"];
				echo "<h1>Welcome to the Secure App System ".$user."!</h1><br>";
			}
		?> 
		<h3>The standard Lorem Ipsum passage, used since the 1500s</h3>
		<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."<br>
		Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."<br>
		Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."<br>
		Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
		
		<form>
			<button formaction="/Lorem.php">Why?</button>
			<button formaction="/Ipsum.php">Where?</button><br><br>
			<button formaction="/PasswordChange.php">Chage Password</button>
			<button formaction="/Index.php">Logout</button>
		</form>
	</body>
</html>