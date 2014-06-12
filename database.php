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
	
		$post = new Post($row['PostId'], $row['Content'], $row['Path'], $sharer['Id'], $row['Type'], $like_count);
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

} else if($_POST['action'] == "share") {

	$userId = $_POST['id'];
	$input = $_POST['q'];
	
	$result = mysql_query("INSERT INTO POST (Content, UserId, PostType)
						   VALUES ('$input', $userId, 'Text')");
	
	mysql_close();

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

?>