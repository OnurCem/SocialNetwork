<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254" />

<style>
.error {color: #FF0000;}
</style>

</head>

<body>

<?php

@include_once "koruma.php";

session_start();

if ((isset($_POST)) && (isset($_POST['submit']))) { 
   	$submit = secure_var($_POST["submit"]);
} else { 
	$submit = false; 
}

if ($submit) {

	$error = array();

	$email = test_input($_POST["email"]);
	$password = test_input($_POST["password"]);
	
	if (empty($email)) {
		$error['email'] = "Please enter a valid email address";
	}
	
	if (empty($password)) {
		$error['password'] = "Please enter your password";
	}
	
	if (count($error) == 0) {
	
		@include "baglan.php";
	
		$result = @mysql_query("SELECT * FROM USER WHERE Email = '$email' AND Password = '$password'");
	
		if (mysql_error()) {
			echo "Something went wrong :(";
		} else {
			$count = mysql_num_rows($result);
			
			if ($count > 0) {
				$row = mysql_fetch_array($result);
				$_SESSION['userId'] = $row['Id'];
				mysql_close();
				header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/index.php");
				die();
			} else {
				echo "Invalid email or password";
			}
			
		}
		
		/*if ((strcmp($email, "onurcemsenel@gmail.com") == 0) && (strcmp($password, "123456") == 0)) {
			$_SESSION['userId'] = "onur";
			header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/index.php");
			die();
		} else {
			echo "Invalid email or password";
		}*/
		
	}
	
}

function test_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>

<a href="signup.php">Sign Up</a><br><br>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">  
	Email:
	<input type="text" name="email" value="<?php echo test_input($_POST['email']); ?>">
	<span class="error">* <?php echo $error['email'];?></span>
	<br><br>

	Password:
	<input type="password" name="password">
	<span class="error">* <?php echo $error['password'];?></span>
	<br><br>

	<input type="submit" name="submit" value="Login">
</form>

</body>
</html>