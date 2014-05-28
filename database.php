<?php

include "baglan.php";

if($_POST['action'] == "addFriend") {

	$receiver = $_POST['receiver'];
	$sender = $_POST['sender'];
	$rel = $_POST['rel'];
	
	mysql_query("INSERT INTO FRIENDREQUEST (ReceiverId, SenderId, Relationship)
				 VALUES ($receiver, $sender, '$rel')");
	
	mysql_close();

}

?>