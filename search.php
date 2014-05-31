<?php

include "baglan.php";
require("classes.php");

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
				
				$user = new User($row['Id'], $row['FirstName'], $row['LastName'], $row['Email'], "", "", $row['PictureURL']);
								
				$isFriend = mysql_query("SELECT Relationship FROM FRIENDSHIP
										 WHERE (User1Id = $userId AND User2Id = " . $user->getId() . ") 
										 OR (User1Id = " . $user->getId() . " AND User2Id = $userId)");
										 
				if (mysql_num_rows($isFriend) > 0) {
					
					$row = mysql_fetch_array($isFriend);
					$user->displayOnSearchResult($row['Relationship']);
					
				} else {
					
					$isRequestPending = mysql_query("SELECT * FROM FRIENDREQUEST
													 WHERE SenderId = $userId AND ReceiverId = " . $user->getId());
													 
					if (mysql_num_rows($isRequestPending) > 0) {
						$user->displayOnSearchResult("pending");
					} else {
						$user->displayOnSearchResult("notFriend");
					}
					
				}
				
				echo mysql_error();
				
			}
			
		} else {
			echo "<h3>User not found :(";
		}
		
		mysql_query("DELETE FROM SEARCH");

		mysql_close();
	}

}

?>
