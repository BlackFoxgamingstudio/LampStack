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


	if ($action == 1)	{

		$titlesite = $descsite = $keywsite = '';
		$protect = -1;
		$showlanguage = $spages = -1;
		
		if (isset($_POST["ts"]) && !empty($_POST["ts"])) $titlesite = $this->db1->e(trim($_POST["ts"]));
		if (isset($_POST["ds"]) && !empty($_POST["ds"])) $descsite = $this->db1->e(trim($_POST["ds"]));
		if (isset($_POST["ks"]) && !empty($_POST["ks"])) $keywsite = $this->db1->e(trim($_POST["ks"]));
		if (isset($_POST["prt"]) && $_POST["prt"]!='') $protect = $this->db1->e(trim($_POST["prt"]));
		if (isset($_POST["lng"]) && $_POST["lng"]!='') $showlanguage = $this->db1->e(trim($_POST["lng"]));
		if (isset($_POST["spg"]) && $_POST["spg"]!='') $spages = $this->db1->e(trim($_POST["spg"]));
		
		if (empty($titlesite)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($descsite)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($keywsite)) { $errored = 1; $txterror .= 'Error... '; }
		if ($protect == -1) { $errored = 1; $txterror .= 'Error... '; }
		if ($showlanguage == -1) { $errored = 1; $txterror .= 'Error... '; }
		if ($spages == -1) { $errored = 1; $txterror .= 'Error... '; }
		
	}

	if ($action == 2)	{

		$ne = $ine = $nm = $inm = -1;
		
		if (isset($_POST["ne"]) && !empty($_POST["ne"])) $ne = $this->db1->e(trim($_POST["ne"]));
		if (isset($_POST["ine"]) && !empty($_POST["ine"])) $ine = $this->db1->e(trim($_POST["ine"]));
		if (isset($_POST["nm"]) && !empty($_POST["nm"])) $nm = $this->db1->e(trim($_POST["nm"]));
		if (isset($_POST["inm"]) && !empty($_POST["inm"])) $inm = $this->db1->e(trim($_POST["inm"]));
		
		if ($ne == -1) { $errored = 1; $txterror .= 'Error... '; }
		if ($ine == -1) { $errored = 1; $txterror .= 'Error... '; }
		if ($nm == -1) { $errored = 1; $txterror .= 'Error... '; }
		if ($inm == -1) { $errored = 1; $txterror .= 'Error... '; }

	}
	
	if ($action == 3)	{

		$ncs = $imc = 0;
		$ce = -1;
		
		if (isset($_POST["ncs"]) && $_POST["ncs"]!='') $ncs = $this->db1->e(trim($_POST["ncs"]));
		if (isset($_POST["imc"]) && $_POST["imc"]!='') $imc = $this->db1->e(trim($_POST["imc"]));
		if (isset($_POST["ce"]) && $_POST["ce"]!='') $ce = $this->db1->e(trim($_POST["ce"]));
		
		if ($ncs == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($imc == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($ce == -1) { $errored = 1; $txterror .= 'Error... '; }

	}
	
	if ($action == 4)	{

		$na = $nfs = $nfg = $nn = $nim = $nf = $ns = $nmd = $nis = $ndp = $nrh = 0;
		
		if (isset($_POST["na"]) && !empty($_POST["na"])) $na = $this->db1->e(trim($_POST["na"]));
		if (isset($_POST["nfs"]) && !empty($_POST["nfs"])) $nfs = $this->db1->e(trim($_POST["nfs"]));
		if (isset($_POST["nfg"]) && !empty($_POST["nfg"])) $nfg = $this->db1->e(trim($_POST["nfg"]));
		if (isset($_POST["nn"]) && !empty($_POST["nn"])) $nn = $this->db1->e(trim($_POST["nn"]));
		if (isset($_POST["nim"]) && !empty($_POST["nim"])) $nim = $this->db1->e(trim($_POST["nim"]));
		if (isset($_POST["nf"]) && !empty($_POST["nf"])) $nf = $this->db1->e(trim($_POST["nf"]));
		if (isset($_POST["ns"]) && !empty($_POST["ns"])) $ns = $this->db1->e(trim($_POST["ns"]));
		if (isset($_POST["nmd"]) && !empty($_POST["nmd"])) $nmd = $this->db1->e(trim($_POST["nmd"]));
		if (isset($_POST["nis"]) && !empty($_POST["nis"])) $nis = $this->db1->e(trim($_POST["nis"]));
		if (isset($_POST["ndp"]) && !empty($_POST["ndp"])) $ndp = $this->db1->e(trim($_POST["ndp"]));
		if (isset($_POST["nrh"]) && !empty($_POST["nrh"])) $nrh = $this->db1->e(trim($_POST["nrh"]));

		
		if ($na == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($nfs == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($nfg == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($nn == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($nim == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($nf == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($ns == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($nmd == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($nis == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($ndp == 0) { $errored = 1; $txterror .= 'Error... '; }
		if ($nrh == 0) { $errored = 1; $txterror .= 'Error... '; }


	}

	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {

		if ($action == 1) {	
			$this->db1->query("UPDATE settings SET value='".$titlesite."' WHERE word='SITE_TITLE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$descsite."' WHERE word='SITE_DESCRIPTION' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$keywsite."' WHERE word='SITE_KEYWORDS' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$protect."' WHERE word='PROTECT_OUTSIDE_PAGES' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$showlanguage."' WHERE word='SHOW_MENU_LANGUAJE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$spages."' WHERE word='SHOW_MENU_PAGES' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 2) {	
			$this->db1->query("UPDATE settings SET value='".$ne."' WHERE word='NUM_NOTIFICATIONS_ALERT' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$ine."' WHERE word='INTERVAL_NOTIFICATIONS_EVENTS' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nm."' WHERE word='NUM_NOTIFICATIONSMSG_ALERT' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$inm."' WHERE word='INTERVAL_NOTIFICATIONS_MSGS' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 3) {	
			$this->db1->query("UPDATE settings SET value='".$ncs."' WHERE word='CHAT_NUM_MSG_START' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$imc."' WHERE word='CHAT_INTERVAL_REFRESH' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$ce."' WHERE word='CHAT_WITH_EMOTICONS' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 4) {	
			$this->db1->query("UPDATE settings SET value='".$na."' WHERE word='NUM_ACTIVITIES_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nfs."' WHERE word='NUM_FOLLOWERS_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nfg."' WHERE word='NUM_FOLLOWING_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nn."' WHERE word='NUM_NOTIFICATIONS_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nim."' WHERE word='NUM_USERCHAT_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nf."' WHERE word='NUM_FAVORITES_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$ns."' WHERE word='NUM_SEARCH_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nmd."' WHERE word='NUM_PHOTOS_DIRECTORY_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nis."' WHERE word='NUM_RESULT_SEARCH_TOP' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$ndp."' WHERE word='NUM_USERS_DIRECTORY_PAGE' LIMIT 1");
			$this->db1->query("UPDATE settings SET value='".$nrh."' WHERE word='ITEMS_RECENTS_HOME' LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		}
	
	}
?>