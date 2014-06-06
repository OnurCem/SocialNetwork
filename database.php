<?php

include "baglan.php";

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

}

?>