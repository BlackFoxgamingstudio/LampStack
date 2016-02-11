<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('inside/admin.php');

	// We are here only if you're logged in
	if (!$this->user->is_logged || !$this->user->info->is_network_admin) {
		echo('0: '.$this->lang('admin_no_session'));
		die();
	}
	
	$errored = 0;
	$txterror = '';
	

	$cph = '';
	
	if (isset($_POST["cph"]) && !empty($_POST["cph"])) $cph = $this->db1->e($_POST["cph"]);
	
	if (empty($cph)) { $errored = 1; $txterror .= 'Error... '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		$theitem = new item($cph, TRUE);
		$theitem->deleteItem();
		unset($theitem);

		$txtreturn = 'Ok';
		echo('1: '.$txtreturn);
		return;
	
	}
?>