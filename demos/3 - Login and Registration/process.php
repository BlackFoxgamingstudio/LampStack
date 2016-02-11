<?php
	session_start();
	require_once('connection.php');

	if(isset($_POST['action']) && $_POST['action'] == "register")
		register_action();
	if(isset($_POST['action']) && $_POST['action'] == "login")
		login_action();
	if(isset($_POST['action']) && $_POST['action'] == "logout")
		logout();

	function register_action()
	{
		if(empty($_POST['first_name']))
			$_SESSION['errors'][] = "First Name field is required";
		if(empty($_POST['last_name']))
			$_SESSION['errors'][] = "Last Name field is required";
		if(empty($_POST['email']))
			$_SESSION['errors'][] = "Email field is required";
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			$_SESSION['errors'][] = "Invalid Email";
		if(empty($_POST['password']))
			$_SESSION['errors'][] = "Password Field is requried";
		if(empty($_POST['confirm_password']))
			$_SESSION['errors'][] = "Confirm Password Field is required";
		if($_POST['password'] != $_POST['confirm_password'])
			$_SESSION['errors'][] = "Passwords do not match!";

		if(empty($_SESSION['errors']))
		{
			$check_email_query = "SELECT * FROM users WHERE email = '". $_POST['email'] ."' ";
			$check_email = fetch_record($check_email_query);

			if(!$check_email)
			{
				$password = md5($_POST['password']);
				$insert_user_query = "INSERT INTO users (first_name, last_name, email, password, created_at) VALUES ('". $_POST['first_name'] ."', '". $_POST['last_name'] ."', '". $_POST['email'] ."', '". $password ."', NOW()) ";
				$insert_user_result = mysql_query($insert_user_query);

				if($insert_user_result)
				{
					$user = array(
						"first_name" => $_POST['first_name'],
						"last_name" => $_POST['last_name'],
						"email" => $_POST['email'],
						"logged_in" => TRUE
					);

					$_SESSION['user'] = $user;
					header('Location: success.php');
					exit();
				}
				else
					$_SESSION['errors'][] = "Someting went wrong. Please check database connection.";			
			}
			else
				$_SESSION['errors'][] = "Email address already exist!";		
		}

		header('Location: index.php');
	}

	function login_action()
	{
		if(empty($_POST['email']))
			$_SESSION['errors'][] = "Email field is required";
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			$_SESSION['errors'][] = "Invalid Email";
		if(empty($_POST['password']))
			$_SESSION['errors'][] = "Password Field is requried";

		if(empty($_SESSION['errors']))
		{
			$check_user = "SELECT * FROM users WHERE email = '". $_POST['email'] ."' AND password = '". md5($_POST['password']) . "' ";
			$user = fetch_record($check_user);

			if(!$user)
				$_SESSION['errors'][] = "Invalid email and password combination.";
			else
			{
				$user += array(
					"logged_in" => TRUE
				);

				$_SESSION['user'] = $user;
				header('Location: success.php');
				exit();	
			}
		}

		header('Location: index.php');
	}

	function logout()
	{
		session_destroy();
		header('Location: index.php');
	}

	/* Add an exit after using the header() function above */
	exit();
?>