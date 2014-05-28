<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="main.css" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript">

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
      data: {'action': 'profile', 'q': userId},
      success: function(data, status) {
          $('#content').html(data);     
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

} else {
	header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/login.php");
	die();
}

?>

<div id="container">
    
	<div id="navigation">
	
		<div id="searchbar">
			<div style="float:left;">
				<form>
					<input type="text" id="search_text">
				</form>
			</div>
			<div style="float:right;">
				<button onclick="searchUser();">Ara</button>
			</div>
		</div>
		
		<div id="toprightmenu">
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

</body>
</html>