<?php
	require_once('connection.php');

	session_start();
	// session_destroy();
	// var_dump($_SESSION);
	// var_dump($_POST);
	// die();

	//if $_POST['action'] is not sent to process.php, assume that the user wants to log off
	if(!isset($_POST['action']))
	{
		session_destroy();
		header("Location: /");
	}

	//do this if the user wants to post a message
	if(isset($_POST['action']) && $_POST['action'] == 'post_message')
	{
		$query = "INSERT INTO messages (user_id, message, created_at) VALUES (".$_SESSION['user']['id'].",'".$_POST['message']."', NOW())";
		run_query($query);
		header("Location: /profile.php");
	}
	//do this if the user wants to post a message
	if(isset($_POST['action']) && $_POST['action'] == 'post_comment')
	{
		$query = "INSERT INTO comments (user_id, comment, message_id, created_at) VALUES (".$_SESSION['user']['id'].",'".$_POST['comment']."', ".$_POST['message_id'].", NOW())";
		// echo $query;
		// die();
		run_query($query);
		header("Location: /profile.php");
	}


	if(isset($_POST['action']) && $_POST['action'] == 'registration')
	{
		//First Name Validation
		if(strlen($_POST['first_name']) < 1)
		{
			$_SESSION['errors']['first_name'] = "First Name is Empty";
		}
		else if(!ctype_alnum($_POST['first_name']))
		{
			$_SESSION['errors']['first_name'] = "First Name is not Alphanumeric";
		}

		//Last Name Validation
		if(strlen($_POST['last_name']) < 1)
		{
			$_SESSION['errors']['last_name'] = "Last Name is Empty";
		}
		else if(!ctype_alnum($_POST['last_name']))
		{
			$_SESSION['errors']['last_name'] = "Last Name is not Alphanumeric";
		}

		//BirthDate
		if(strlen($_POST['birth_date']) < 10)
		{
			$_SESSION['errors']['birth_date'] = "Invalid Date";
		}
		else
		{
			$dates = explode("/", $_POST['birth_date']);
			if(count($dates) < 3)
			{
				$_SESSION['errors']['birth_date'] = 'Invalid Date';
			}
			else
			{
				if(!checkdate($dates[0], $dates[1], $dates[2]))
				{
					$_SESSION['errors']['birth_date'] = "Invalid Date";
				}
			}
		}

		//Email Validation
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['errors']['email'] = "Invalid Email";
		}

		//Password
		if(strlen($_POST['password']) < 6)
		{
			$_SESSION['errors']['password'] = "Password is too short";
		}
		else if($_POST['password'] != $_POST['confirm_password'])
		{
			$_SESSION['errors']['password'] = "Password does not match";
		}
	}
	else if(isset($_POST['action']) && $_POST['action'] == 'login')
	{
		//Email Validation
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['errors']['email'] = "Invalid Email";
		}

		//Password
		if(strlen($_POST['password']) < 6)
		{
			$_SESSION['errors']['password'] = "Password is too short";
		}
	}
	

	if(isset($_SESSION['errors']) && count($_SESSION['errors']) > 0)
	{
		if($_POST['action'] == 'registration')
			$_SESSION['error_type'] = "registration";
		else if($_POST['action'] == 'login')
			$_SESSION['error_type'] = "login";

		header("Location: /");
	}
	else
	{
		if($_POST['action'] == 'registration')
		{
			$query = "INSERT INTO users (first_name, last_name, email, password, birth_date, created_at) VALUES ('".$_POST['first_name']."','".$_POST['last_name']."','".$_POST['email']."','".md5($_POST['password'])."','".date('Y-m-d', strtotime($_POST['birth_date']))."',NOW())";

			mysqli_query($connection, $query);
			$_SESSION['success_message_registration'] = "Success!  Registration complete!";
			header("Location: /");
		}
		else if($_POST['action'] == 'login')
		{
			$query = "SELECT * FROM users WHERE email = '".$_POST['email']."' and password = '".md5($_POST['password'])."'";
			// echo $query;

			$result = mysqli_query($connection, $query);
			// var_dump($result);

			if($result->num_rows < 1)
			{
				$_SESSION['errors']['login'] = 'Invalid Login Credentials';
				$_SESSION['error_type'] = "login";
				header("Location: /");
			}
			else
			{
				$row = mysqli_fetch_assoc($result);
				$_SESSION['user']['first_name'] = $row['first_name'];
				$_SESSION['user']['last_name'] = $row['last_name'];
				$_SESSION['user']['email'] = $row['email'];
				$_SESSION['user']['id'] = $row['id'];
				$_SESSION['logged_in'] = true;
				// var_dump($_SESSION);
				header("Location: /profile.php");
			}
			// $count = mysqli_stmt_num_rows($connection, $result);
			// echo $count;
		}
	}

	