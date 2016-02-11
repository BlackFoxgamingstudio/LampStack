<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('inside/dashboard.php');
	
	$numnotifications = $this->db2->fetch_field('SELECT num_notifications FROM users WHERE iduser='.$this->user->id);
		
	if ($numnotifications > 0) {
		echo('1: '.$numnotifications);
		return;
	} else {
		echo('0: '.$numnotifications);
		return;		
	}
		
?>