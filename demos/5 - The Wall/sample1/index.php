<?php 
	session_start(); 

	if(isset($_SESSION['user']) AND $_SESSION['user']['logged_in'] == TRUE)
		header('Location: wall.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Intermediate II - Login and Registration</title>
</head>
<body>
<?php
	if(isset($_SESSION['errors']))
	{
		foreach($_SESSION['errors'] as $error)
		{
			echo $error . "<br>";
		}
	}
?>
	<h1>Register</h1>
	<form action="process.php" method="post">
		<label for="first_name">First Name:</label>
		<input type="text" name="first_name" id="first_name" /> <br />

		<label for="last_name">Last Name:</label>
		<input type="text" name="last_name" id="last_name" /> <br />

		<label for="email">Email:</label>
		<input type="email" name="email" id="email" /> <br />

		<label for="password">Password:</label>
		<input type="password" name="password" id="password" /> <br />

		<label for="confirm_password">Confirm Password:</label>
		<input type="password" name="confirm_password" id="confirm_password" /> <br />

		<input type="hidden" name="action" value="register" />
		<input type="submit" value="Register" />
	</form>

	<h1>Login</h1>
	<form action="process.php" method="post">
		<label for="email">Email</label>
		<input type="email" name="email" id="email" /> <br />

		<label for="password">Password</label>
		<input type="password" name="password" id="password" /> <br />

		<input type="hidden" name="action" value="login" />
		<input type="submit" value="Login" />
	</form>
<?php $_SESSION['errors'] = array(); ?>
</body>
</html>