<?php session_start(); ?>
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
		if(isset($_SESSION['error']))
		{
			echo '<div class="error">';

			foreach($_SESSION['error'] as $name => $message)
			{
				echo '<p>'. $message .'</p>';
			}

			echo '</div>';
		}
		session_destroy();

	?>
		<form id="email-form" action="process.php" method="post">
			<input type="hidden" name="action" value="email-form">
			<div class="fields">
				<div class="form-input">
					<h3>Please enter your email address:</h3>
					<input name="email" type="text" placeholder="email address">
				</div>
				<input name="submit" type="submit" value="Submit">
			</div>
		</form>		
	</div>
</body>
</html>