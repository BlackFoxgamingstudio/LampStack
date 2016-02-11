<?php
/** 
 * 
 * NOTE: Designed for use with PHP version 4, 5 and up
 * @author Santos Montano B. <ssmontano@hotmail.com, litosantosm@gmail.com>
 * @country Perú
 * @copyright 2013
 * @version: 1.0 
 * 
 */

/** 
 * Main file that is responsible for loading the classes needed
 * to successfully perform the tasks of the application.
 */

	chdir(dirname(__FILE__));
	
	require_once('./helpers/func_main.php');
	require_once('./config.php');
	
	session_start();
	
	$db1 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);
	$db2 = &$db1;
	
	$network = new network();
	$network->load();
	
	$user = new user();
	$user->load();
	
	$page = new page();
	$page->load();

?>