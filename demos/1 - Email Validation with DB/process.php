<?php
session_start();
require_once('connection.php');

/* Intro to functions: let's create a custom function named 'validateEmail'.
It should accept a single parameter/argument and that will be the email address to be validated. */
function validateEmail($email)
{
	/* If the email is valid, let's return TRUE. If not, return FALSE.
	This is good for comparison later... you'll see :D */
	return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
}

/* If the form was submitted with an action field and if the action field is equal to 'email-form' */
if (isset($_POST['action']) && $_POST['action'] == 'email-form')
{
	/* Check if the user has submitted a blank input field: */
	if(empty($_POST['email'])) 
		/* Set error message if the field submitted was blank. */
		$_SESSION['error']['email'] = 'Sorry, the email address field cannot be blank';

	/* If the email address is not empty: */
	else
	{
		/* Let's validate the email submitted by the user */
		$_SESSION['email_success'] = validateEmail($_POST['email']); // Remember that validateEmail will return TRUE if the email is valid and FALSE if it is not.

		/* If the email is not valid, generate the error message */
		if(!$_SESSION['email_success'])
			$_SESSION['error']['email'] = 'The email address you entered (' . $_POST['email'] . ') is NOT a valid email address!';
		else
		{
			$insert_email_query = "INSERT INTO users (email, created_at) VALUES('". $_POST['email'] ."', NOW())";

			$insert_email_result = mysql_query($insert_email_query);

			if($insert_email_result === true)
			{
				$_SESSION['email'] = $_POST['email'];
				header('Location: success.php');
				exit();
			}
			else
				$_SESSION['error']['email'] = "Something went wrong. Please check database connection.";
		}

	}

	header('Location: index.php');
	/* Add an exit after using the header() function above */
	exit();
}

?>