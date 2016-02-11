<?php
	include 'functions.php';

	$db = new DB;

	$table_name = 'triggers_personality';

	$result = $db->get_all($table_name);

	while ($row = mysql_fetch_array($result))
	{
?>
	
	<h1><?php echo $row['name']; ?></h1>
	<h3><?php echo $row['position']; ?></h3>
	<h5><?php echo $row['desc']; ?></h5>

	<hr>
	
<?php

	}



?>