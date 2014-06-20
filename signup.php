<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254" />
<link rel="stylesheet" href="form.css" type="text/css" />

</head>

<body>

<?php

@include_once "koruma.php";

if ((isset($_POST)) && (isset($_POST['submit']))) { 
   	$submit = secure_var($_POST["submit"]);
} else { 
	$submit = false; 
}

if ($submit) {

	$error = array();
	
	$firstName = test_input($_POST["firstName"]);
	$lastName = test_input($_POST["lastName"]);
	$email = test_input($_POST["email"]);
	$confirmEmail = test_input($_POST["confirmEmail"]);
	$password = test_input($_POST["password"]);
	$birthday = test_input($_POST["birthday"]);
	$gender = test_input($_POST["gender"]);
	$pictureURL = "http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/img/user.png";
	
	if (empty($firstName)) {
		$error['firstName'] = "<br>Please enter your name";
	} else {
		if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
			$error['firstName'] = "<br>Only letters and white space allowed";
		}
	}
	
	if (empty($lastName)) {
		$error['lastName'] = "<br>Please enter your last name";
	} else {
		if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
			$error['lastName'] = "<br>Only letters and white space allowed";
		}
	}
	
	if (empty($email)) {
		$error['email'] = "<br>Please enter a valid email address";
	} else {
		if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
			$error['email'] = "<br>Your email address doesn't seem to be valid";
		} else {
			if (empty($confirmEmail)) {
				$error['confirmEmail'] = "<br>Please re-enter your email";
			} else {
				if (strcmp($email, $confirmEmail) != 0) {
					$error['confirmEmail'] = "<br>Your emails do not match";
				}
			}
		}
	}
	
	if (empty($password)) {
		$error['password'] = "<br>You must choose a password";
	} else {
		if (!preg_match("/^\S*(?=\S{6,})\S*$/D", $password)) {
			$error['password'] = "<br>Your password length must be 6 at least";
		}
	}
	
	if (empty($birthday)) {
		$error['birthday'] = "<br>Birthday is required";
	}
	
	if (empty($gender)) {
		$error['gender'] = "<br>Gender is required";
	}
	
	if (count($error) == 0) {
	
		@include "baglan.php";
	
		$result = @mysql_query("SELECT * FROM USER WHERE Email = '$email'");
	
		if (mysql_error()) {
			echo "Something went wrong :(";
		} else {
			$count = mysql_num_rows($result);

			if ($count == 0) {

				mysql_query("INSERT INTO USER (FirstName, LastName, Email, Password, Birthday, Gender, PictureURL)
							 VALUES('$firstName',
									'$lastName',
									'$email',
									'$password',
									'$birthday',
									'$gender',
									'$pictureURL')");
			
				echo "Your account created successfully<br><br>";
				
				mysql_close();
				
				header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/login.php");
				die();
				
			} else {
				echo "This email address is already in use<br><br>";
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
	First Name:
	<input type="text" name="firstName" value="<?php echo test_input($_POST['firstName']); ?>">
	<span class="error">* <?php echo $error['firstName'];?></span>
	<br>

	Last Name:
	<input type="text" name="lastName" value="<?php echo test_input($_POST['lastName']); ?>">
	<span class="error">* <?php echo $error['lastName'];?></span>
	<br>

	Email:
	<input type="text" name="email" value="<?php echo test_input($_POST['email']); ?>">
	<span class="error">* <?php echo $error['email'];?></span>
	<br>

	Re-enter Email:
	<input type="text" name="confirmEmail" value="<?php echo test_input($_POST['confirmEmail']); ?>">
	<span class="error">* <?php echo $error['confirmEmail'];?></span>
	<br>

	Password:
	<input type="password" name="password">
	<span class="error">* <?php echo $error['password'];?></span>
	<br>
	
	Birthday:
	<input type="date" name="birthday" value="<?php echo test_input($_POST['birthday']); ?>">
	<span class="error">* <?php echo $error['birthday'];?></span>
	<br>

	Gender:
	<input type="radio" name="gender" value="Female"
		<?php if (strcmp($_POST["gender"], "Female") == 0) echo " checked"; ?> >Female
	<input type="radio" name="gender" value="Male"
		<?php if (strcmp($_POST["gender"], "Male") == 0) echo " checked"; ?> >Male
	<span class="error">* <?php echo $error['gender'];?></span>
	<br>

	<input class="button" type="submit" name="submit" value="Sign Up">
</form>

</body>
</html>