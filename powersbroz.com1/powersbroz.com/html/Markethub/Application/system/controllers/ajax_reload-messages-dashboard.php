<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('inside/dashboard.php');
	
	$errored = 0;
	$txterror = '';

	$numitems = $iduser = 0;
	
	if (isset($_POST["ni"]) && !empty($_POST["ni"])) $numitems = $this->db1->e($_POST["ni"]);
	if (isset($_POST["idu"]) && !empty($_POST["idu"])) $iduser = $this->db1->e($_POST["idu"]);
	
	if (!is_numeric($numitems) || $numitems <= 0) { $errored = 1; $txterror .= 'Error... '; }
	if (!is_numeric($iduser) || $iduser <= 0) { $errored = 1; $txterror .= 'Error... '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {


		$totalitems = $this->db2->fetch_field('SELECT COUNT(DISTINCT id_from) FROM chat WHERE id_to='.$this->user->id);
		
		$r = $this->db2->query('SELECT * FROM (SELECT * FROM chat, users WHERE id_to='.$this->user->id.' AND id_from = iduser ORDER BY thedate DESC) as itemchat GROUP BY id_from ORDER BY thedate DESC LIMIT '.$numitems.','.$C->NUM_USERCHAT_PAGE);

		$numitemsnow = $this->db2->num_rows();
		
		$htmlResults = '';
		ob_start();
		
		while( $obj = $this->db2->fetch_object($r) ) {
			$D->isThisUserVerified = $obj->validated==1?TRUE:FALSE;
			$D->f_name = (empty($obj->firstname) || empty($obj->lastname))?stripslashes($obj->username):(stripslashes($obj->firstname).' '.stripslashes($obj->lastname));
			$D->f_avatar = $obj->avatar;
			$D->f_lastmessage = analyzeMessage($obj->message);
			$D->f_date = $obj->{'date'};
			$D->f_username = $obj->username;
			$this->load_template('__dashboard-one-userchat.php');
		}
		
		$htmlResults = ob_get_contents();
		ob_end_clean();
		
		unset($r, $obj);
		
		if ($totalitems <= $numitemsnow + $numitems) {
			echo("2: ".$htmlResults);
			return;
		} else {
			echo("1: ".$htmlResults);
			return;	
		}
			
	}


?>