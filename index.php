<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254" />
</head>

<body>

<?php

session_start();

if (isset($_SESSION['userId'])) { 
   	header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/main.php");
	die();
} else { 
	header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/login.php");
	die();
}

?>

</body>
</html>