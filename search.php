<?php

include "baglan.php";
session_start();

if (isset($_SESSION['userId'])) {

	if($_POST['action'] == "search") {

		$userId = $_SESSION['userId'];
		$q = $_POST['q'];

		$words = explode(" ", $q);

		for ($i = 0; $i < count($words); $i++) {
			mysql_query("INSERT INTO SEARCH (Id, FirstName, LastName, Email, PictureURL)
						SELECT Id, FirstName, LastName, Email, PictureURL FROM USER WHERE
						(FirstName LIKE '%$words[$i]%' OR
						LastName LIKE '%$words[$i]%') AND
						Id <> $userId");
		}
		
		$result = mysql_query("SELECT DISTINCT Id, FirstName, LastName, Email, PictureURL FROM SEARCH");
		
		if (mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result)) {
				echo "<p><img src='" . $row['PictureURL'] . "' width='60px'>";
				echo $row['FirstName'] . " " . $row['LastName'] . "<br>";
				echo $row['Email'] . "<br>";
			}
		} else {
			echo "<h3>User not found :(";
		}
		
		mysql_query("DELETE FROM SEARCH");

		mysql_close();
	}

}

?>