<?php

//define constants for db_host, db_user, db_pass, and db_database
//adjust the values below to match your database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root'); //set DB_PASS as 'root' if you're using MAMP
define('DB_DATABASE', 'class_users');

//connect to database
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
// check connection
if (mysqli_connect_errno()) {
    echo "Connect failed: ";
    echo mysqli_connect_error();
    exit();
}

function fetch_all($query)
{
	global $connection;

	$result = mysqli_query($connection, $query);
	$rows = array();
	while($row = mysqli_fetch_assoc($result))
	{
		$rows[] = $row;
	}
	return $rows;
}

function run_query($query)
{
	global $connection;
	$result = mysqli_query($connection, $query);
	return $result;
}

