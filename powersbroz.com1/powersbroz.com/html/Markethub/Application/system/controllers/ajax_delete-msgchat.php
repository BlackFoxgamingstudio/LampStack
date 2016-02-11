<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('outside/profile.php');
	
	$errored = 0;
	$txterror = '';

	$idmsg = -1;
	
	if (isset($_POST["idmsg"]) && !empty($_POST["idmsg"])) $idmsg = $this->db1->e($_POST["idmsg"]);
	
	if (!is_numeric($idmsg) || $idmsg <= 0) { $errored = 1; $txterror .= 'Error... '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		$totalitems = $this->db2->query('DELETE FROM chat WHERE id='.$idmsg.' AND id_from='.$this->user->id);
		echo('1: Ok');
		return;	
	}


?>