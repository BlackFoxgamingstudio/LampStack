<?php
	//echo('0: You can not make changes to the demo version.'); die();
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
	
	$action=0;
	
	if (isset($_POST["todo"]) && $_POST["todo"] != '') $action = $this->db1->e($_POST["todo"]);
	if (!is_numeric($action)) {
		$errored = 1;
		$txterror .= 'Error. ';
		echo('0: '.$caderror);
		die();
	}
	
	$txtpage = '';
	
	if (isset($_POST["txtpage"]) && !empty($_POST["txtpage"])) $txtpage = $this->db1->e(trim($_POST["txtpage"]));
	
	if (empty($txtpage)) { $errored = 1; $txterror .= 'Error... '; }

	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {

		if ($action == 1) {	
			$this->db1->query("UPDATE pages SET texthtml='".$txtpage."' WHERE code='about' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 2) {	
			$this->db1->query("UPDATE pages SET texthtml='".$txtpage."' WHERE code='privacy' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 3) {	
			$this->db1->query("UPDATE pages SET texthtml='".$txtpage."' WHERE code='termsofuse' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 4) {	
			$this->db1->query("UPDATE pages SET texthtml='".$txtpage."' WHERE code='disclaimer' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 5) {	
			$this->db1->query("UPDATE pages SET texthtml='".$txtpage."' WHERE code='contact' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
	
	}
?>