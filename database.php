<?php

session_start();

include "baglan.php";
require("classes.php");

if($_POST['action'] == "addFriend") {

	$receiver = $_POST['receiver'];
	$sender = $_POST['sender'];
	$rel = $_POST['rel'];
	
	mysql_query("INSERT INTO FRIENDREQUEST (ReceiverId, SenderId, Relationship)
				 VALUES ($receiver, $sender, '$rel')");
	
	mysql_close();

} else if($_POST['action'] == "getNotificationCount") {
	
	$userId = $_POST['id'];
	
	$result = mysql_query("SELECT COUNT(*) FROM FRIENDREQUEST WHERE ReceiverId = $userId");
	$count = mysql_fetch_array($result);
	echo $count[0];
	
	mysql_close();
	
} else if($_POST['action'] == "showNotification") {
	
	$userId = $_POST['id'];
	
	$result = mysql_query("SELECT SenderId, Relationship FROM FRIENDREQUEST WHERE ReceiverId = $userId");
	
	while($row = mysql_fetch_array($result)) {
		
		$query = mysql_query("SELECT Id, FirstName, LastName, PictureURL FROM USER WHERE Id = " . $row['SenderId']);
		$sender = mysql_fetch_array($query);
		
		echo "<p><img src='" . $sender['PictureURL'] . "' width='60px'>";
		echo $sender['FirstName'] . " " . $sender['LastName'] . " added you as " . $row['Relationship'] . " friend";
		echo "<button id='confirm_friend' onClick='confirmFriend(" . $userId . ", " . $sender['Id'] . ", " . 
		"\"" . $row['Relationship'] . "\");'>Confirm</button>";
	}
	
	mysql_close();
	
} else if($_POST['action'] == "confirmFriend") {
	
	$user1Id = $_POST['id1'];
	$user2Id = $_POST['id2'];
	$relation = $_POST['rel'];
	
	mysql_query("INSERT INTO FRIENDSHIP (User1Id, User2Id, Relationship)
				 VALUES ($user1Id, $user2Id, '$relation')");
	
	mysql_query("DELETE FROM FRIENDREQUEST WHERE ReceiverId = $user1Id AND SenderId = $user2Id");
	
	mysql_close();
	
} else if($_POST['action'] == "deleteFriend") {

	$id1 = $_POST['id1'];
	$id2 = $_POST['id2'];
	
	mysql_query("DELETE FROM FRIENDSHIP WHERE (User1Id = $id1 AND User2Id = $id2)
											  OR (User1Id = $id2 AND User2Id = $id1)");
	
	mysql_close();

} else if($_POST['action'] == "getContent") {

	$userId = $_POST['id'];
	
	$result = mysql_query("SELECT DISTINCT POST.PostId, POST.UserId, Path, Content, PostType FROM POST,
						   ((SELECT User1Id AS UserId FROM FRIENDSHIP WHERE User2Id = $userId)
						   UNION
						   (SELECT User2Id FROM FRIENDSHIP WHERE User1Id = $userId))USR
						   WHERE (USR.UserId = POST.UserId)
						   OR (POST.UserId = $userId)
						   ORDER BY POST.PostId DESC;");
	
	while($row = mysql_fetch_array($result)) {
	
		$query = mysql_query("SELECT Id, FirstName, LastName, PictureURL FROM USER
							  WHERE Id = " . $row['UserId']);
		$sharer = mysql_fetch_array($query);
		
		$like_query = mysql_query("SELECT UserId FROM POSTLIKE WHERE PostId = " . $row['PostId']);
		$like_count = mysql_num_rows($like_query);
		$isLiked = false;
		while($likedUser = mysql_fetch_array($like_query)) {
			if($likedUser['UserId'] == $userId) {
				$isLiked = true;
				break;
			}
		}
		
		$sharerUser = new User();
		$sharerUser->setFirstName($sharer['FirstName']);
		$sharerUser->setLastName($sharer['LastName']);
		$sharerUser->setPictureURL($sharer['PictureURL']);
		$sharerUser->displayPhotoName();
	
		$post = new Post($row['PostId'], $row['Content'], $row['Path'], $sharer['Id'], $row['PostType'], $like_count);
		$post->displayPost($isLiked);
		
		$comment = new Comment($userId, $row['PostId']);
		$comment_query = mysql_query("SELECT UserId, Username, Content FROM COMMENT WHERE PostId = " . $row['PostId']);
		while($comment_row = mysql_fetch_array($comment_query)) {
			$comment->setContent($comment_row['Content']);
			$comment->display($comment_row['Username']);
		}
		$comment->displayMakeComment();
		
		echo mysql_error();

	}	
	
	mysql_close();

} else if($_POST['share_post']) {

	$userId = $_SESSION['userId'];
	$input = $_POST['share_text'];
	
	$result = mysql_query("INSERT INTO POST (Content, UserId, PostType)
						   VALUES ('$input', $userId, 'Text')");
						   
	$postId_query = mysql_query("SELECT PostId FROM POST
								 ORDER BY PostId DESC LIMIT 1");
	$postId = mysql_fetch_array($postId_query);
						   
	uploadPicture($userId, $postId['PostId']);
	
	mysql_close();
	
	header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/main.php");
	die();

} else if($_POST['action'] == "likePost") {

	$userId = $_POST['userId'];
	$postId = $_POST['postId'];
	
	$result = mysql_query("INSERT INTO POSTLIKE (PostId, UserId)
						   VALUES ($postId, $userId)");
	
	mysql_close();

} else if($_POST['action'] == "unlikePost") {

	$userId = $_POST['userId'];
	$postId = $_POST['postId'];
	
	$result = mysql_query("DELETE FROM POSTLIKE
						   WHERE (PostId = $postId
						   AND UserId = $userId)");
	
	mysql_close();

} else if($_POST['action'] == "shareComment") {

	$userId = $_POST['userId'];
	$postId = $_POST['postId'];
	$content = $_POST['q'];
	$name = $_SESSION['FirstName'] . " " . $_SESSION['LastName'];
	
	$result = mysql_query("INSERT INTO COMMENT (UserId, Username, PostId, Content)
						   VALUES ($userId, '$name', $postId, '$content')");
	
	mysql_close();

}

function uploadPicture($userId, $postId) {
	
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
				echo "<script language='javascript'>;
						alert('Invalid picture');
					  </script>;";
				die();
			}
			else
			{
				if (file_exists("img/" . $_FILES["file"]["name"]))
				{
				  echo $_FILES["file"]["name"] . " already exists. ";
				}
				else
				{
				  $new_filename = $userId . "_" . $postId;
				  
				  $full_local_path = 'img/post/' . $new_filename . "." . $extension ;
				  $pictureURL = "http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/" . $full_local_path;
				  move_uploaded_file($_FILES["file"]["tmp_name"], $full_local_path);
				}
			}
		}
		else
		{
			echo "<script language='javascript'>;
					alert('Invalid picture');
				  </script>;";
			die();
		}
		
		mysql_query("UPDATE POST SET PostType = 'Picture', Path = '$pictureURL'
					 WHERE PostId = $postId");
	}
	
}

?>