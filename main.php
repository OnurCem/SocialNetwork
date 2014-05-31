<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="main.css" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript">

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

function addFriend(recId, senId) {
	
	var e = document.getElementById("relation_type");
	var relation = e.options[e.selectedIndex].value;

    $.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'addFriend', 'receiver': recId, 'sender': senId, 'rel': relation},
      success: function(data, status) {
		  document.getElementById("add_friend").disabled = true;
          document.getElementById("add_friend").innerHTML = "Request sent";   
      }
    });
}

function confirmFriend(user1Id, user2Id, relation) {

	$.ajax({
      url: 'database.php',
      type: 'post',
      data: {'action': 'confirmFriend', 'id1': user1Id, 'id2': user2Id, 'rel': relation},
      success: function(data, status) {
		  document.getElementById("confirm_friend").disabled = true;
          document.getElementById("confirm_friend").innerHTML = "Added";   
      }
    });
	
	getNotificationCount(user1Id);
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

} else {
	header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/login.php");
	die();
}

?>

<div id="container">
    
	<div id="navigation">
	
		<div id="searchbar">
            <form>
                <input type="text" id="search_text">
                <input type="button" onClick="searchUser();" value="Ara">
            </form>
		</div>
		
		<div id="toprightmenu">
        	<div id="notification_count">
            	<a href="#" onclick="showNotification(<?php echo $userId; ?>); return false;"></a>
            </div>
			<img src="<?php echo $user['PictureURL']; ?>">
			<a href="#" onclick="showProfile(<?php echo $userId; ?>); return false;"><?php echo $user['FirstName'] . " " . $user['LastName']; ?></a>
			<a href="logout.php">Sign out</a>
		</div>
		
	</div>
	
	
	<div id="leftmenu">
	
		<h3>Deneme</h3>
		<h3>Deneme</h3>
		<h3>Deneme</h3>
		<h3>Deneme</h3>
		<h3>Deneme</h3>
		<h3>Deneme</h3>
		<h3>Deneme</h3>
		
	</div> 
	
	
	<div id="content">

		
	</div>
        
</div>

<script>
	window.onload = function() {
	  getNotificationCount(<?php echo $userId; ?>);
	};
</script>

</body>
</html>