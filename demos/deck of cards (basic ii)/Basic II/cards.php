<?php session_start();
?>
<body>
<form action = "process.php" method = 'post'>
	<input type = 'hidden' name = 'action' value = 'deal'>
	<input type = 'submit' value = 'deal'>
</form>
<form action = "process.php" method = 'post'>
	<input type = 'hidden' name = 'action' value = 'reset'>
	<input type = 'submit' value = 'reset'>
</form>
<form action = "process.php" method = 'post'>
	<input type = 'hidden' name = 'action' value = 'shuffle'>
	<input type = 'submit' value = 'shuffle'>
</form>
<?php if(isset($_SESSION['pic'])){ ?>
	<img src="<?php echo $_SESSION['pic']; ?>">
<?php } ?>
</body>

