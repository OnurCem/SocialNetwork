<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254" />
<link rel="stylesheet" href="form.css" type="text/css" />

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
		$error['email'] = "<br>Please enter a valid email address";
	}
	
	if (empty($password)) {
		$error['password'] = "<br>Please enter your password";
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
				echo "<div id='login_error'>Invalid email or password</div>";
			}
			
		}
		
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

<form class="basic-grey" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">  
	<a href="signup.php">Sign Up</a><br><br>
	
	Email:
	<input type="text" name="email" value="<?php echo test_input($_POST['email']); ?>">
	<span class="error">* <?php echo $error['email'];?></span>
	<br>

	Password:
	<input type="password" name="password">
	<span class="error">* <?php echo $error['password'];?></span>
	<br>

	<input class="button" type="submit" name="submit" value="Sign In">
</form>

</body>
</html>