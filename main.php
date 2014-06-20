<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="main.css" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript">

function share(userId) {
	var input = document.getElementById("share_text").value;
	
	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'share', 'id': userId, 'q': input},
      success: function(data, status) {
		  getContent(userId);
      }
    });
}

function openFileDialog() {
	var file = document.getElementById("picture");
	file.click();
	file.addEventListener("change", function(event) {

		var i = 0,
			files = file.files,
			len = files.length;

		for (; i < len; i++) {
			console.log("Filename: " + files[i].name);
			console.log("Type: " + files[i].type);
			console.log("Size: " + files[i].size + " bytes");
		}

	}, false);
}

function getContent(userId) {
	
	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'getContent', 'id': userId},
      success: function(data, status) {
		  $('#content').html(data); 
      }
    });
}

function getGroups(userId) {
	
	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'getGroups', 'id': userId},
      success: function(data, status) {
		  $('#groups').html(data);
      }
    });
}

function likePost(postId, userId) {
	
	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'likePost', 'postId': postId, 'userId': userId},
      success: function(data, status) {
		  document.getElementById(postId + "like_post").disabled = true;
		  getContent(userId);
      }
    });
}

function unlikePost(postId, userId) {
	
	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'unlikePost', 'postId': postId, 'userId': userId},
      success: function(data, status) {
		  document.getElementById(postId + "unlike_post").disabled = true;
		  getContent(userId);
      }
    });
}

function shareComment(userId, postId) {

	var comment = document.getElementById("comment_text").value;
	
	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'shareComment', 'postId': postId, 'userId': userId, 'q': comment},
      success: function(data, status) {
		  getContent(userId);
      }
    });
}

function getNotificationCount(userId) {
	
	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'getNotificationCount', 'id': userId},
      success: function(data, status) {
		  document.getElementById("notification_count").getElementsByTagName('a')[0].innerHTML = data;
      }
    });	
}

function showNotification(userId) {
	
	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'showNotification', 'id': userId},
      success: function(data, status) {
		  $('#content').html(data); 
      }
    });	
}

function searchUser() {

	var input = document.getElementById("search_text").value;

    $.ajax({
      url: 'search.php',
      type: 'post',
      data: {'action': 'search', 'q': input},
      success: function(data, status) {
          $('#content').html(data);     
      }
    });
}

function showProfile(userId) {

    $.ajax({
      url: 'profile.php',
      type: 'post',
      data: {'action': 'showProfile', 'q': userId},
      success: function(data, status) {
          $('#content').html(data);     
      }
    });
}

function createGroup(userId) {

    $.ajax({
      url: 'group.php',
      type: 'post',
      data: {'action': 'createGroup', 'userId': userId},
      success: function(data, status) {
          $('#content').html(data);  
      }
    });
}

function updateGroup(groupId) {

    $.ajax({
      url: 'group.php',
      type: 'post',
      data: {'action': 'updateGroup', 'groupId': groupId},
      success: function(data, status) {
          $('#content').html(data);  
      }
    });
}

function joinGroup(groupId, userId) {

    $.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'joinGroup', 'groupId': groupId, 'userId': userId},
      success: function(data, status) {
		  document.getElementById(groupId + "join_group").disabled = true;
          document.getElementById(groupId + "join_group").innerHTML = "Joined"; 
		  getGroups(userId);
      }
    });
}

function leaveGroup(groupId, userId) {

    $.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'leaveGroup', 'groupId': groupId, 'userId': userId},
      success: function(data, status) {
		  document.getElementById(groupId + "leave_group").disabled = true;
          document.getElementById(groupId + "leave_group").innerHTML = "Left"; 
		  getGroups(userId);
      }
    });
}

function getGroupContent(groupId) {

    $.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'getGroupContent', 'groupId': groupId},
      success: function(data, status) {
          $('#content').html(data);  
      }
    });
}

function addFriend(recId, senId) {
	
	var e = document.getElementById("relation_type");
	var relation = e.options[e.selectedIndex].value;

    $.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'addFriend', 'receiver': recId, 'sender': senId, 'rel': relation},
      success: function(data, status) {
		  document.getElementById(recId + "_add_friend").disabled = true;
          document.getElementById(recId + "_add_friend").innerHTML = "Request sent";   
      }
    });
}

function deleteFriend(user1Id, user2Id) {

    $.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'deleteFriend', 'id1': user1Id, 'id2': user2Id},
      success: function(data, status) {
		  document.getElementById(user1Id + "_delete_friend").disabled = true;
          document.getElementById(user1Id + "_delete_friend").innerHTML = "Deleted";   
      }
    });
}

function confirmFriend(user1Id, user2Id, relation) {

	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'confirmFriend', 'id1': user1Id, 'id2': user2Id, 'rel': relation},
      success: function(data, status) {
		  document.getElementById(user2Id + "confirm_friend").disabled = true;
          document.getElementById(user2Id + "confirm_friend").innerHTML = "Added"; 
		  getNotificationCount(user1Id);
      }
    });
}

function getPopularPosts() {

	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'getPopularPosts'},
      success: function(data, status) {
		  $('#posts').html(data); 
      }
    });
}
	
</script>

</head>
<body>

<?php

session_start();

if (isset($_SESSION['userId'])) {

	$userId = $_SESSION['userId'];
	
	include "baglan.php";

	$result = mysql_query("SELECT FirstName, LastName, PictureURL FROM USER WHERE Id = $userId");
	$user = mysql_fetch_array($result);
	
	$_SESSION['FirstName'] = $user['FirstName'];
	$_SESSION['LastName'] = $user['LastName'];
	
	if(isset($_GET['showGroup']))
	{
		$groupId = $_GET['showGroup'];
		echo "<script> getGroupContent($groupId); </script>";
	}
	else if(isset($_GET['showProfile']) && $_GET['showProfile'] == $userId)
	{
		$userId = $_GET['showProfile'];
		echo "<script> showProfile($userId); </script>";
	}
	else
	{
		echo "<script> getContent($userId); </script>";
	}

} else {
	header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/login.php");
	die();
}

?>

<div id="container">
    
	<div id="navigation">
	
		<div id="searchbar">
            <form class="form" onsubmit="searchUser(); return false;">
                <input type="text" id="search_text" placeholder="Search people or groups">
                <input class="submit" type="submit" value="Search">
            </form>
		</div>
		
		<div id="toprightmenu">
			<div class="homepage_button">
				<a class="homepage" href="main.php">Home</a>
			</div>
        	<div id="notification_count">
            	<a href="#" onclick="showNotification(<?php echo $userId; ?>); return false;"></a>
            </div>
			<img src="<?php echo $user['PictureURL']; ?>">
			<a href="#" onclick="showProfile(<?php echo $userId; ?>); return false;"><?php echo $user['FirstName'] . " " . $user['LastName']; ?></a>
			<a href="logout.php">Sign out</a>
		</div>
		
	</div>
	
	<div id="inner_container">
	
		<div id="leftmenu">
			<p><a href="#" onclick="createGroup(<?php echo $userId; ?>); return false;">Create new group</a></p>

			<div id="groups">
			</div>
			
		</div>
		
		<div id="rightmenu">
			Popular posts
			
			<div id="posts">
			</div>
			
		</div>
		
		<div id="sharebar">
			<form class="form" method="post" action="database.php" id="share_form" enctype="multipart/form-data">
				<textarea name="share_text" rows="1" cols="40" placeholder="Write your things or a link"></textarea>
				<input class="submit" type="submit" name="share_post" value="Share">
				<input type="hidden" name="groupId" value="<?php echo $groupId; ?>">
				<input type="file" id="picture" name="file" style="display:none">
				<a href="#" style="font-size: 14px" onclick="openFileDialog(); return false;">Upload Picture</a>
			</form>
			
			
		</div>

		<div id="content">
			
		</div>
	
	</div>

	
     
</div>

<script>
	window.onload = function() {
		//getContent(<?php echo $userId; ?>);
		getNotificationCount(<?php echo $userId; ?>);
		getGroups(<?php echo $userId; ?>);
		getPopularPosts();
	};
</script>

</body>
</html>