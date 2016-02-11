<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('inside/dashboard.php');

	// We are here only if you're logged in
	if (!$this->user->is_logged) {
		echo('0: '.$this->lang('dashboard_no_session'));
		die();
	}
	
	$errored = 0;
	$txterror = '';
	
	$action=0;
	
	if (isset($_POST["todo"]) && $_POST["todo"] != '') $action = $this->db1->e($_POST["todo"]);
	if (!is_numeric($action)) {
		$errored = 1;
		$txterror .= 'Error. ';
		echo('0: '.$caderror);
		die();
	}

	if ($action == 1 || $action==2)	{
		$userid = 0;
		if (isset($_POST["uid"])) $userid = $this->db1->e($_POST["uid"]);
		if (!is_numeric($userid) || $userid <= 0) { $errored = 1; $txterror .= 'Error. '; }
	}
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		
		// Follow
		if ($action == 1) {
			if( $this->user->follow($userid, TRUE) ) $txtreturn = '1: Ok';
			else $txtreturn = '0: '.$this->lang('dashboard_no_session');
			echo $txtreturn;
			return;
		}

		// unFollow
		if ($action == 2) {
			if( $this->user->follow($userid, FALSE) ) $txtreturn = '1: Ok';
			else $txtreturn = '0: '.$this->lang('dashboard_no_session');
			echo $txtreturn;
			return;
		}
		
		
	}
	
?>