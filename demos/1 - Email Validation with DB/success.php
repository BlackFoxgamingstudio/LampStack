<?php
	session_start();
	require_once('connection.php');
	$get_emails_query = "SELECT * FROM users";
	$emails = fetchAll($get_emails_query);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Advanced - Basic Assignment #2</title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<div class="container">
<?php 
		if (isset($_SESSION['email_success']) && $_SESSION['email_success'] == true)
		{ ?>
		<div class="success">
			<div class="message">
				<p><?= 'The email address you entered (' . $_SESSION['email'] . ') is a VALID email address!'; ?></p>
			</div>
		</div>
		<h4>Email Addresses Entered:</h4>
<?php 	}	?>
		<ul>
<?php 	foreach($emails as $email)
		{ ?>
			<li><p><?php echo $email['email']; ?> - <small><?php echo $email['created_at']; ?></small></p></li>
<?php	} ?>
		</ul>
	</div>
</body>
</html>