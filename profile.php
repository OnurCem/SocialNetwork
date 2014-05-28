<?php

include "baglan.php";

if($_POST['action'] == "profile") {

	$q = $_POST['q'];
	
	$result = mysql_query("SELECT Id, FirstName, LastName, Email, PictureURL FROM USER
						   WHERE Id = $q");
	
	while($row = mysql_fetch_array($result)) {
		echo "<p><img src='" . $row['PictureURL'] . "' width='60px'>";
		echo $row['FirstName'] . " " . $row['LastName'] . "<br>";
		echo $row['Email'] . "<br>";
	}

	mysql_close();

}

?>