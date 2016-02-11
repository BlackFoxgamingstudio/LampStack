<?php 
	session_start();

	if(!isset($_SESSION['user']) OR $_SESSION['user']['logged_in'] != TRUE)
	{
		header("Location: 404.php");
		die();
		$_SESSION = array();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Success Page</title>
</head>
<body>
	<form action="process.php" method="post">
		<input type="hidden" name="action" value="logout" />
		<input type="submit" value="Logout" />
	</form>
	<h2>Welcome, <?php echo $_SESSION['user']['first_name']; ?></h2>
	<h3><?php echo $_SESSION['user']['email']; ?></h3>
</body>
</html>