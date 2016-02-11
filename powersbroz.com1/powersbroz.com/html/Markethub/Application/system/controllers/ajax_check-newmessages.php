<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('inside/dashboard.php');

	$r = $this->db2->query('SELECT id FROM chat WHERE id_to='.$this->user->id.' AND isread=0');
	
	$nummessages = $this->db2->num_rows($r);
		
	if ($nummessages > 0) {
		echo('1: '.$nummessages);
		return;
	} else {
		echo('0: '.$nummessages);
		return;		
	}
		
?>