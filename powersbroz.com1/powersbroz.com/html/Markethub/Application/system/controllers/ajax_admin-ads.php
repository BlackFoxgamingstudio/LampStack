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

	// Evaluate personal data
	if ($action == 1)	{

		$adsp1 = $adsp2 = '';
		
		if (isset($_POST["adsp1"]) && !empty($_POST["adsp1"])) $adsp1 = $this->db1->e(trim($_POST["adsp1"]));
		if (isset($_POST["adsp2"]) && !empty($_POST["adsp2"])) $adsp2 = $this->db1->e(trim($_POST["adsp2"]));
		
	}

	if ($action == 2)	{

		$adsd1 = $adsd2 = '';
		
		if (isset($_POST["adsd1"]) && !empty($_POST["adsd1"])) $adsd1 = $this->db1->e(trim($_POST["adsd1"]));
		if (isset($_POST["adsd2"]) && !empty($_POST["adsd2"])) $adsd2 = $this->db1->e(trim($_POST["adsd2"]));

	}

	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {

		if ($action == 1) {	
			$this->db1->query("UPDATE ads SET adsource='".$adsp1."' WHERE code='adsProf01' LIMIT 1");
			$this->db1->query("UPDATE ads SET adsource='".$adsp2."' WHERE code='adsProf02' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 2) {	
			$this->db1->query("UPDATE ads SET adsource='".$adsd1."' WHERE code='adsDash01' LIMIT 1");
			$this->db1->query("UPDATE ads SET adsource='".$adsd2."' WHERE code='adsDash02' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
	
	}
?>