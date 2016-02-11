<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('inside/dashboard.php');
	
	
	$errored = 0;
	$txterror = '';

	$uid = 0;
	
	if (isset($_POST["uid"]) && !empty($_POST["uid"])) $uid = $this->db1->e($_POST["uid"]);
	
	if (!is_numeric($uid) || $uid <= 0) { $errored = 1; $txterror .= 'Error. '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		


		$r = $this->db2->query('SELECT * FROM chat WHERE id_from='.$uid.' AND id_to='.$this->user->id.' AND isread=0');	
		
		if ($this->db2->num_rows($r)) {

			$r = $this->db2->query('SELECT * FROM chat, users WHERE id_from='.$uid.' AND id_to='.$this->user->id.' AND isread=0 AND id_from=iduser ORDER BY chat.id DESC');
			
			if ($r) {
				
				$this->db2->query('UPDATE chat SET isread=1 WHERE id_from='.$uid.' AND id_to='.$this->user->id.' AND isread=0');
	
				ob_start();
				$txtReturn = '';			
				while( $obj = $this->db2->fetch_object($r) ) {
					$D->idmsg = $obj->id;
					$D->iduser = $obj->iduser;
					$D->username = $obj->username;
					$D->uname = (empty($obj->firstname) || empty($obj->lastname))?stripslashes($obj->username):(stripslashes($obj->firstname).' '.stripslashes($obj->lastname));
					$D->avatar = $obj->avatar;
					$D->dateago = $obj->thedate;
					$D->message = analyzeMessage($obj->message);
					$this->load_template('__profile-one-msgchat.php');
				}	
				$txtReturn = ob_get_contents();
				ob_end_clean();
	
				echo('1: '.$txtReturn);
				
			} else {
				echo('0: Nothing'); return;
			}
			
		} else echo('0: Nothing'); return;
		
		
	}		
?>