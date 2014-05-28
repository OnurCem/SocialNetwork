<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254" />
</head>

<body>

<?php

session_start();
unset($_SESSION['userId']);
session_destroy();
header("Location: http://sorubank.ege.edu.tr/~b051164/dersler/lwp/proje/login.php");
die();

?>

</body>
</html>