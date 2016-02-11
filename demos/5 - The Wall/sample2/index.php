<?php
	session_start();
	var_dump($_SESSION);
?>

<html>
<head>
	<title>Login and Registration</title>

	<style type="text/css">
	label{
		width:150px;
		display:block;
		margin-top:7px;
	}
	div.error{
		color:red;
		border:1px solid red;
		background-color: pink;
		padding: 10px;
	}

	</style>
</head>
<body>

<!-- 
Let's outline the steps

1. Create the HTML and CSS
2. Create process.php to validate login info
  - if invalid information, return error messages
3. Create ERD/Schema
  - if valid, redirect to 'profile.php'
4. Have a simple profile.php that says 'welcome user_name';
 -->

<?php
if(isset($_SESSION['error_type']) && $_SESSION['error_type'] == "login")
{
	echo "<div class='error'>";
	foreach($_SESSION['errors'] as $error)
	{
		echo $error ."<br />";
	}
	echo "</div>";

	unset($_SESSION['error_type']);
	unset($_SESSION['errors']);
}
?>
<h2>Login</h2>
<form action="process.php" method="post">
	<label>Email: </label><input type="text" name="email" value="" placeholder="Enter email address" />
	<label>Password: </label><input type="password" name="password" value="" placeholder="Enter password" />
	<input type="submit" value="Login" />
	<input type="hidden" name="action" value="login" />
</form>

<?php
if(isset($_SESSION['success_message_registration']))
{
	echo $_SESSION['success_message_registration'];
}

if(isset($_SESSION['error_type']) && $_SESSION['error_type'] == "registration")
{
	echo "<div class='error'>";
	foreach($_SESSION['errors'] as $error)
	{
		echo $error ."<br />";
	}
	echo "</div>";

	unset($_SESSION['error_type']);
	unset($_SESSION['errors']);
}
?>
<h2>Registration</h2>
<form action="process.php" method="post">
	<label>First name:</label><input type="text" name="first_name" value="" />
	<label>Last name:</label><input type="text" name="last_name" value="" />
	<label>Birthdate:</label><input type="text" name="birth_date" value="" />
	<label>Email:</label><input type="text" name="email" value="" placeholder="Enter email address" />
	<label>Password:</label><input type="password" name="password" value="" placeholder="Enter password" />
	<label>Confirm Password:</label><input type="password" name="confirm_password" value="" placeholder="Enter password" />
	<input type="submit" value="Login" />
	<input type="hidden" name="action" value="registration" />
</form>


</body>
</html>