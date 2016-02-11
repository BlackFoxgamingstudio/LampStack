<?php

	include 'functions.php';

	$name = $_POST['name'];
	$position = $_POST['position'];
	$desc = $_POST['desc'];
	$table_name = 'triggers_personality';

	$db = new DB;	


	$db->insert($table_name, array(
			'name' => $name,
			'position' => $position,
			'description' => $desc
		));

	echo 'Successfully Inserted!!!';
?>	
