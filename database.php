<html>
<head>
<link rel="stylesheet" href="database.css" type="text/css" />
</head>
</html>

<?php

session_start();

include "baglan.php";
require("classes.php");

$postContent;

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
		
		echo "<div id='friendship_request'>";
			echo "<img src='" . $sender['PictureURL'] . "'>";
			echo $sender['FirstName'] . " " . $sender['LastName'] . " added you as " . $row['Relationship'] . " friend";
			echo "<button id='" . $sender['Id'] . "confirm_friend' class='submit' onClick='confirmFriend(" . $userId . ", " . $sender['Id'] . ", " . 
			"\"" . $row['Relationship'] . "\");'>Confirm</button>";
		echo "</div>";
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
	
	showPosts($result);	
	
	mysql_close();

} else if($_POST['action'] == "getGroups") {

	$userId = $_POST['id'];
	
	$result = mysql_query("SELECT GroupId FROM USERGROUP
						   WHERE UserId = $userId");
	
	while($row = mysql_fetch_array($result)) {
	
		$query = mysql_query("SELECT GroupId, Name FROM GROUPINFO
							  WHERE GroupId = " . $row['GroupId']);

		$group_info = mysql_fetch_array($query);
				
		echo "
			<a href='main.php?showGroup=" . $group_info['GroupId'] . "'>" . 
			$group_info['Name'] . "</a><br>";		
	}	
	
	mysql_close();

} else if($_POST['action'] == "getGroupContent") {

	$groupId = $_POST['groupId'];
	
	$group_query = mysql_query("SELECT GroupId, Name, PictureURL, AdminId
								FROM GROUPINFO
								WHERE GroupId = $groupId");
								
	$group_info = mysql_fetch_array($group_query);
	
	$group = new GroupInfo($group_info['GroupId'], $group_info['Name'], $group_info['PictureURL'], $group_info['AdminId']);
	
	$group->displayGroupInfo();
	
	$result = mysql_query("SELECT PostId FROM POSTGROUP
						   WHERE GroupId = $groupId
						   ORDER BY PostId DESC" );
	
	while($row = mysql_fetch_array($result)) {
	
		$posts = mysql_query("SELECT PostId, Content, Path, UserId, PostType
							  FROM POST
							  WHERE PostId = " . $row['PostId']);
				
		showPosts($posts);		
	}	
	
	mysql_close();

} else if($_POST['action'] == "joinGroup") {

	$groupId = $_POST['groupId'];
	$userId = $_POST['userId'];
	
	mysql_query("INSERT INTO USERGROUP (UserId, GroupId)
				 VALUES ($userId, $groupId)");
				 
	mysql_close();

} else if($_POST['action'] == "leaveGroup") {

	$groupId = $_POST['groupId'];
	$userId = $_POST['userId'];
	
	mysql_query("DELETE FROM USERGROUP
				 WHERE UserId = $userId
				 AND GroupId = $groupId");
	
	mysql_close();

} else if($_POST['share_post']) {

	global $postContent;
	$userId = $_SESSION['userId'];
	$input = $_POST['share_text'];
	$groupId = $_POST['groupId'];
	
	detectPostType($input);
	
	$result = mysql_query("INSERT INTO POST (Content, UserId, PostType)
						   VALUES ('$postContent', $userId, 'Text')");
						   
	$postId_query = mysql_query("SELECT PostId FROM POST
								 ORDER BY PostId DESC LIMIT 1");
	$postId = mysql_fetch_array($postId_query);
	
	if(isset($groupId))
	{
		mysql_query("INSERT INTO POSTGROUP (PostId,GroupId)
					 VALUES (".$postId['PostId'].", $groupId)");
		
		header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/main.php?showGroup=$groupId");
		die();
    }
	
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

} else if($_POST['action'] == "getPopularPosts") {
	
	$result = mysql_query("SELECT PostId, COUNT(PostId) as PostCount
						   FROM POSTLIKE
						   GROUP BY PostId
						   ORDER BY PostCount DESC");
						   
	while($row = mysql_fetch_array($result)) {
	
		$post_query = mysql_query("SELECT Content, UserId FROM POST
								   WHERE PostId = " . $row['PostId']);
		$post = mysql_fetch_array($post_query);
		
		$user_query = mysql_query("SELECT FirstName, LastName FROM USER
								   WHERE Id = " . $post['UserId']);
		$user = mysql_fetch_array($user_query);
		
		echo $user['FirstName'] . " " . $user['LastName'] . "<br>";
		echo $post['Content'] . "<br><br>";
	}
	
	mysql_close();

}

function detectPostType($str) {

	global $postContent;
	$postContent = $str;
	
	$str = preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)|(?:\S+\.\S+)#', function($arr)
	{
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		global $postContent;
		/*if(strpos($arr[0], 'http://') !== 0)
		{
			$arr[0] = 'http://' . $arr[0];
		}*/
		$url = parse_url($arr[0]);

		// images
		if(preg_match('#\.(png|jpg|gif)$#', $url['path']))
		{
			$postContent = '<img src="'. $arr[0] . '" />';
			return;
		}
		// youtube
		if(in_array($url['host'], array('www.youtube.com', 'youtube.com'))
		  && $url['path'] == '/watch'
		  && isset($url['query']))
		{
			parse_str($url['query'], $query);
			$postContent = sprintf('<iframe class="embedded-video" src="http://www.youtube.com/embed/%s" width="350px" height="250px" allowfullscreen></iframe>', $query['v']);
			return;
		}
		//links
		if(preg_match($reg_exUrl, $arr[0]))
		{
			$postContent = sprintf('<a href="%1$s">%1$s</a>', $arr[0]);
			return;
		}

		return;
	}, $str);

}

function showPosts($posts) {

	$userId = $_SESSION['userId'];

	while($row = mysql_fetch_array($posts)) {
	
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

	}

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