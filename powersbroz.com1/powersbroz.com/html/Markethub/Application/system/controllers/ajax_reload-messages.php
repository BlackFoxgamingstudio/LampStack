<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('outside/profile.php');
	
	$errored = 0;
	$txterror = '';

	$numitems = $iduser = 0;
	
	if (isset($_POST["ni"]) && !empty($_POST["ni"])) $numitems = $this->db1->e($_POST["ni"]);
	if (isset($_POST["uid"]) && !empty($_POST["uid"])) $iduser = $this->db1->e($_POST["uid"]);
	
	if (!is_numeric($numitems) || $numitems <= 0) { $errored = 1; $txterror .= 'Error... '; }
	if (!is_numeric($iduser) || $iduser <= 0) { $errored = 1; $txterror .= 'Error... '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {

		// find the total number of messages
		$totalitems = $this->db2->fetch_field('SELECT count(chat.id) FROM users, chat WHERE (id_from='.$this->user->id.' AND id_to='.$iduser.' AND id_from=iduser) OR (id_from='.$iduser.' AND id_to='.$this->user->id.' AND id_from=iduser)');
		
		$r = $this->db2->query('SELECT * FROM users, chat WHERE (id_from='.$this->user->id.' AND id_to='.$iduser.' AND id_from=iduser) OR (id_from='.$iduser.' AND id_to='.$this->user->id.' AND id_from=iduser) ORDER BY chat.id DESC LIMIT '.$numitems.','.$C->CHAT_NUM_MSG_START);

		$numitemsnow = $this->db2->num_rows();
		
		while( $obj = $this->db2->fetch_object($r) ) $rowmsgs[] = $obj;

		$rowmsgs = array_reverse($rowmsgs);	
		
		$htmlResults = '';

		ob_start();
		
		foreach( $rowmsgs as $onemsg ) {
			$D->idmsg = $onemsg->id;
			$D->iduser = $onemsg->iduser;
			$D->username = $onemsg->username;
			$D->uname = (empty($onemsg->firstname) || empty($onemsg->lastname))?stripslashes($onemsg->username):(stripslashes($onemsg->firstname).' '.stripslashes($onemsg->lastname));
			$D->avatar = $onemsg->avatar;
			$D->dateago = $onemsg->thedate;
			$D->message = analyzeMessage($onemsg->message);
			$this->load_template('__profile-one-msgchat.php');
		}

		$htmlResults = ob_get_contents();
		ob_end_clean();
		
		unset($r, $rowmsgs);
		
		if ($totalitems <= $numitemsnow + $numitems) {
			echo("2: ".$htmlResults);
			return;
		} else {
			echo("1: ".$htmlResults);
			return;	
		}
			
	}


?>