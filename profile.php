
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php
session_start();

if (isset($_SESSION['userId'])) {
	$userId = $_SESSION['userId'];
} else {
	header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/login.php");
	die();
}

include "baglan.php";

if($_POST['action'] == "showProfile") {

	$q = $_POST['q'];
	
	$result = mysql_query("SELECT * FROM USER
						   WHERE Id = $q");
	
	
	while($row = mysql_fetch_array($result)) {
		$userId = $row['Id'];
		$firstname = $row['FirstName'];
		$lastname = $row['LastName'];
		$email = $row['Email'];
		$password = $row['Password'];
		$birthday = $row['Birthday'];
		$gender = $row['Gender'];
		$picture = $row['PictureURL'];
	
		echo "<p><img src='" . $row['PictureURL'] . "' width='60px'>";
		echo $row['FirstName'] . " " . $row['LastName'] . "<br>";
		echo $row['Email'] . "<br>";
	}

	mysql_close();

} else {

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
		$password = test_input($_POST["password"]);
		$birthday = test_input($_POST["birthday"]);
		$gender = test_input($_POST["gender"]);
		$pictureURL = ($_POST["pictureURL"]);

		if (empty($firstName)) {
			$error['firstName'] = "Please enter your name";
		} else {
			if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
				$error['firstName'] = "Only letters and white space allowed";
			}
		}
		
		if (empty($lastName)) {
			$error['lastName'] = "Please enter your last name";
		} else {
			if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
				$error['lastName'] = "Only letters and white space allowed";
			}
		}
		
		if (empty($password)) {
			$error['password'] = "You must choose a password";
		} else {
			if (!preg_match("/^\S*(?=\S{6,})\S*$/D", $password)) {
				$error['password'] = "Your password length must be 6 at least";
			}
		}
		
		if (empty($birthday)) {
			$error['birthday'] = "Birthday is required";
		}
		
		if (empty($gender)) {
			$error['gender'] = "Gender is required";
		}
		
		if ($_FILES["file"]["name"] != "") {
			$allowedExts = array("jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["file"]["name"]);
			$extension = end($temp);
			if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/jpg")
			|| ($_FILES["file"]["type"] == "image/pjpeg")
			|| ($_FILES["file"]["type"] == "image/x-png")
			|| ($_FILES["file"]["type"] == "image/png"))
			&& ($_FILES["file"]["size"] < 1000000)
			&& in_array($extension, $allowedExts))
			{
				if ($_FILES["file"]["error"] > 0)
				{
					$error['pictureURL'] = "Invalid file";
				}
				else
				{
					if (file_exists("img/" . $_FILES["file"]["name"]))
					{
					  echo $_FILES["file"]["name"] . " already exists. ";
					}
					else
					{
					  $new_filename = $userId . "_" . $firstName . "_" . $lastName ;
					  
					  $full_local_path = 'img/'.$new_filename.".".$extension ;
					  $pictureURL = "http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/".$full_local_path;
					  move_uploaded_file($_FILES["file"]["tmp_name"], $full_local_path);
					}
				}
			}
			else
			{
			  $error['pictureURL'] = "Invalid file";
			}
		}

		if (count($error) == 0) {	
			mysql_query("UPDATE USER SET FirstName = '$firstName', LastName = '$lastName', Password = '$password',
						Birthday = '$birthday', Gender = '$gender', PictureURL = '$pictureURL'
						WHERE Id = $userId ");
									
			echo "<br>Your account information has been updated successfully<br><br>";
			
			mysql_close();	
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

<form id="profile_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">  
	First Name:
	<input type="text" name="firstName" value="<?php echo $firstname; ?>">
	<span class="error">* <?php echo $error['firstName'];?> </span>
	<br><br>

	Last Name:
	<input type="text" name="lastName" value="<?php echo $lastname; ?>">
	<span class="error">* <?php echo $error['lastName'];?> </span>
	<br><br>

	Email:
	<input type="text" name="email" value="<?php echo $email; ?>">
	<span class="error">* <?php echo $error['email'];?> </span>
	<br><br>

	Password:
	<input type="password" name="password" value="<?php echo $password; ?>">
	<span class="error">* <?php echo $error['password'];?> </span>
	<br><br>
	
	Birthday:
	<input type="date" name="birthday" value="<?php echo $birthday; ?>">
	<span class="error">* <?php echo $error['birthday'];?> </span>
	<br><br>

	Gender:
	<input type="radio" name="gender" value="Female"
		<?php if (strcmp($gender, "Female") == 0) echo " checked"; ?> >Female
	<input type="radio" name="gender" value="Male"
		<?php if (strcmp($gender, "Male") == 0) echo " checked"; ?> >Male
	<span class="error">* <?php echo $error['gender'];?> </span>
	<br>
	
	<span class="error"><?php echo $error['pictureURL'];?> </span><br>
	<label for="file">Set profile picture:</label>
	<input type="file" name="file" id="file"><br>
	
	<input type="hidden" name="pictureURL" value="<?php echo $picture; ?>">

	<input type="submit" name="submit" value="Save Changes">
</form>

</body>
</html>