<?php
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', 'root');
	define('DB_DATABASE', 'mysql_test');

	$connection = mysql_connect(DB_HOST, DB_USER, DB_PASS) or 
		die('Could not connect to the database host (please double check the settings in connection.php): ' . mysql_error());
	$db_selected = mysql_select_db(DB_DATABASE, $connection) or 
		die ('Could not find a database with the name "'.DB_DATABASE.'" 
		(please double check your settings in connection.php): ' . mysql_error());

	/* Intro to functions: let's create a custom function named 'fetchAll'.
		It accepts 1 parameter which will be the query ($query) we're going to use for retrieving records
		from the database.
	*/
	function fetchAll($query)
	{
		/* First, we create an array to store the records grabbed from the database.
			Initially, it's empty */
		$data = array();

		/* We execute the $query which we got from this function's parameter. */
		$result = mysql_query($query);

		/* Here, we loop through the results by using a while loop.
			The while loop simply says: 'while you still can declare results to the $row variable, keep looping!' */
		while($row = mysql_fetch_assoc($result))
		{
			/* Append each result/row to the $data array */
			$data[] = $row;
		}

		/* After all that looping, now we return the $data variable. */
		return $data;
	}
?>