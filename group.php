
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

if($_POST['action'] == "createGroup") {

	$isUpdate = false;
	
} else if($_POST['action'] == "updateGroup") {

	$isUpdate = true;
	$groupId = $_POST['groupId'];
	
	$group_query = mysql_query("SELECT GroupId, Name, PictureURL, AdminId
								FROM GROUPINFO
								WHERE GroupId = $groupId");
								
	$group_info = mysql_fetch_array($group_query);
	
	$groupName = $group_info['Name'];
	
} else {

	@include_once "koruma.php";

	if ((isset($_POST)) && (isset($_POST['submit']))) { 
		$submit = secure_var($_POST["submit"]);
	} else { 
		$submit = false; 
	}

	if ($submit) {
		
		$error = array();
		
		$groupName = test_input($_POST["groupName"]);
		$isUpdate = $_POST["isUpdate"];
		$groupId = $_POST['groupId'];

		if (empty($groupName)) {
			$error['groupName'] = "Please enter your name";
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
					if (file_exists("img/group/" . $_FILES["file"]["name"]))
					{
					  echo $_FILES["file"]["name"] . " already exists. ";
					}
					else
					{
					  $new_filename = $userId . "_" . date("Ymd") . "_" . date("hisa");
					  
					  $full_local_path = 'img/group/' . $new_filename . "." . $extension ;
					  $pictureURL = "http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/" . $full_local_path;
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

			if ($isUpdate) {
				mysql_query("UPDATE GROUPINFO SET Name = '$groupName', PictureURL = '$pictureURL'
							 WHERE GroupId = $groupId ");
							 
							 echo mysql_error();
			} else {
				mysql_query("INSERT INTO GROUPINFO (Name, AdminId, PictureURL)
							 VALUES ('$groupName', $userId, '$pictureURL')");
							 
				$groupId_query = mysql_query("SELECT GroupId FROM GROUPINFO
							                  ORDER BY GroupId DESC LIMIT 1");
				$groupId = mysql_fetch_array($groupId_query);
							 
				mysql_query("INSERT INTO USERGROUP (UserId, GroupId)
							 VALUES ($userId, " . $groupId['GroupId'] . ")");
			}
												
			mysql_close();
			
			header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/main.php");
			die();
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

<form id="group_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">  
	Group Name:
	<input type="text" name="groupName" value="<?php echo $groupName; ?>">
	<span class="error">* <?php echo $error['groupName'];?> </span>
	<br><br>
	
	<span class="error"><?php echo $error['pictureURL'];?> </span><br>
	<label for="file">Set group picture:</label>
	<input type="file" name="file" id="file"><br>
	
	<input type="hidden" name="pictureURL" value="<?php echo $picture; ?>">
	<input type="hidden" name="isUpdate" value="<?php echo $isUpdate; ?>">
	<input type="hidden" name="groupId" value="<?php echo $groupId; ?>">
	<input type="submit" name="submit" value="Save Changes">
</form>

</body>
</html>