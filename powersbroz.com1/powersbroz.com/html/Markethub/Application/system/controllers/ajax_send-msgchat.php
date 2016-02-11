<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('inside/dashboard.php');
	
	
	$errored = 0;
	$txterror = '';

	$uid = 0;
	$txtmsg = '';
	
	if (isset($_POST["uid"]) && !empty($_POST["uid"])) $uid = $this->db1->e($_POST["uid"]);
	if (isset($_POST["msg"]) && !empty($_POST["msg"])) $txtmsg = $this->db1->e(htmlspecialchars($_POST["msg"]));
	
	if (!is_numeric($uid) || $uid <= 0) { $errored = 1; $txterror .= 'Error. '; }
	if (empty($txtmsg)) { $errored = 1; $txterror .= 'Error. '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		
		$this->db2->query('INSERT INTO chat SET id_from='.$this->user->id.', id_to='.$uid.', message="'.$txtmsg.'", thedate="'.time().'"');

		if ($this->db2->affected_rows()) {
			
			ob_start();
			$txtReturn = '';
			$r = $this->db2->query('SELECT * FROM chat, users WHERE (id_from='.$this->user->id.' AND id_to='.$uid.' AND id_from=iduser) ORDER BY chat.id DESC LIMIT 1');
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

		}
		
	}		
?>