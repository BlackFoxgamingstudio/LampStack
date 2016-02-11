<?php 
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('outside/profile.php');
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

	// 
	if ($action == 1)	{

		$comment = '';
		$iditem = $idowner = 0;

		if (isset($_POST["c"]) && !empty($_POST["c"])) $comment = $this->db1->e(htmlspecialchars($_POST["c"]));
		if (isset($_POST["ip"]) && !empty($_POST["ip"])) $iditem = $this->db1->e($_POST["ip"]);
		if (isset($_POST["iu"]) && !empty($_POST["iu"])) $idowner = $this->db1->e($_POST["iu"]);

		if (empty($comment)) { $errored = 1; $txterror .= 'Error. '; }
		if ($iditem == 0) { $errored = 1; $txterror .= 'Error. '; }
		if ($idowner == 0) { $errored = 1; $txterror .= 'Error. '; }
		
	}

	if ($action == 2)	{
		$idcomment = $iditem = $idowner = 0;
		
		if (isset($_POST["idc"]) && !empty($_POST["idc"])) $idcomment = $this->db1->e($_POST["idc"]);
		if (isset($_POST["idp"]) && !empty($_POST["idp"])) $iditem = $this->db1->e($_POST["idp"]);
		if (isset($_POST["idu"]) && !empty($_POST["idu"])) $idowner = $this->db1->e($_POST["idu"]);
		
		if (empty($idcomment)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($iditem)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($idowner)) { $errored = 1; $txterror .= 'Error... '; }
		
	}
	
	if ($action == 3)	{
		$whatdo = -1;
		$iditem = $idowner = 0;
		
		if (isset($_POST["w"]) && $_POST["w"]!='') $whatdo = $this->db1->e($_POST["w"]);
		if (isset($_POST["idp"]) && !empty($_POST["idp"])) $iditem = $this->db1->e($_POST["idp"]);
		if (isset($_POST["iu"]) && !empty($_POST["iu"])) $idowner = $this->db1->e($_POST["iu"]);
		
		if ($whatdo == -1) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($iditem)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($idowner)) { $errored = 1; $txterror .= 'Error... '; }
		
	}
	
	if ($action == 4)	{
		$whatdo = -1;
		$iditem = $idowner = 0;
		
		if (isset($_POST["w"]) && $_POST["w"]!='') $whatdo = $this->db1->e($_POST["w"]);
		if (isset($_POST["idp"]) && !empty($_POST["idp"])) $iditem = $this->db1->e($_POST["idp"]);
		if (isset($_POST["iu"]) && !empty($_POST["iu"])) $idowner = $this->db1->e($_POST["iu"]);
		
		if ($whatdo == -1) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($iditem)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($idowner)) { $errored = 1; $txterror .= 'Error... '; }
		
	}	


	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		
		// We save the comments
		if ($action == 1) {	
			$r = $this->db1->query("INSERT INTO comments SET iditem=".$iditem.", iduser=".$this->user->id.", comment='".$comment."', whendate='".time()."'");
			$idcomment = $this->db1->insert_id();
			$this->db1->query("UPDATE users SET num_comments=num_comments+1 WHERE iduser=".$this->user->id.' LIMIT 1');
			$this->db1->query("UPDATE items SET numcomments=numcomments+1 WHERE iditem=".$iditem.' LIMIT 1');
			$this->db1->query('INSERT INTO activities SET iduser='.$this->user->id.', action=4, idresult='.$idcomment.', iditem='.$iditem.', typeitem=1, date="'.time().'"');
			if ($this->user->id != $idowner) {
				$this->db1->query("INSERT INTO notifications SET notif_type=3, idresult=".$idcomment.", to_user_id=".$idowner.", from_user_id=".$this->user->id.", notif_object_type=1, notif_object_id=".$iditem.",date='".time()."'");
				$this->db1->query("UPDATE users SET num_notifications=num_notifications+1 WHERE iduser=".$idowner.' LIMIT 1');
			}
			
			$htmlReturn = '';
			ob_start();
			$D->o_comment = stripslashes($comment);
			$D->o_username = stripslashes($this->user->info->username);
			$D->o_firstname = stripslashes($this->user->info->firstname);
			$D->o_lastname = stripslashes($this->user->info->lastname);
			$D->o_nameUser = (empty($D->o_firstname) || empty($D->o_lastname))?stripslashes($D->o_username):(stripslashes($D->o_firstname).' '.stripslashes($D->o_lastname));
			$D->o_whendate = time();
			$D->o_avatar =  empty($this->user->info->avatar)?$C->AVATAR_DEFAULT:$this->user->info->avatar;
			$D->o_idcomment = $idcomment;
			$D->o_owner = 1;
			$D->idItem = $iditem;
			$D->idUser = $idowner;
			$this->load_template('__profile-onecomment-item.php');	
			$htmlReturn = ob_get_contents();
			ob_end_clean();

			echo("1: ".$htmlReturn);
			return;
		}

		if ($action == 2) {	
			$this->db1->query("DELETE FROM comments WHERE idcomment=".$idcomment);
			if ($this->db1->affected_rows()>0) {
				$this->db1->query("UPDATE users SET num_comments=num_comments-1 WHERE iduser=".$this->user->id.' LIMIT 1');
				$this->db1->query("UPDATE items SET numcomments=numcomments-1 WHERE iditem=".$iditem.' LIMIT 1');
				$this->db1->query('DELETE FROM activities WHERE iduser='.$this->user->id.' AND action=4 AND idresult='.$idcomment);

				$this->db1->query("DELETE FROM notifications WHERE notif_type=3 AND idresult=".$idcomment." AND from_user_id=".$this->user->id);
				
				$nnotifications = $this->network->getNumNotifications($idowner);
				if ($nnotifications <= 0) $nnotifications = 0;
				else $nnotifications = $nnotifications - 1;
				
				$this->db1->query("UPDATE users SET num_notifications=".$nnotifications." WHERE iduser=".$idowner." LIMIT 1");

			}
			echo('1: Ok');
			return;
		}
		
		if ($action == 3) {
			
			if ($whatdo == 0) {
			
				$r = $this->db1->query("DELETE FROM likes WHERE typeitem=2 AND iditem=".$iditem." AND iduser=".$this->user->id);
				$idlike = $this->db1->insert_id();
				$this->db1->query("UPDATE users SET num_likes=num_likes-1 WHERE iduser=".$this->user->id.' LIMIT 1');
				$this->db1->query("UPDATE items SET numlikes=numlikes-1 WHERE iditem=".$iditem.' LIMIT 1');
				$this->db1->query('DELETE FROM activities WHERE iduser='.$this->user->id.' AND action=5 AND iditem='.$iditem.' AND typeitem=1');

				$this->db1->query("DELETE FROM notifications WHERE notif_type=2 AND to_user_id=".$idowner." AND from_user_id=".$this->user->id." AND notif_object_type=1 AND notif_object_id=".$iditem);
				$nnotifications = $this->network->getNumNotifications($idowner);
				if ($nnotifications <= 0) $nnotifications = 0;
				else $nnotifications = $nnotifications - 1;
				$this->db1->query("UPDATE users SET num_notifications=".$nnotifications." WHERE iduser=".$idowner.' LIMIT 1');
			} else {
			
				if ($whatdo == 1) {
				
					$r = $this->db1->query("INSERT INTO likes SET typeitem=2, iditem=".$iditem.", iduser=".$this->user->id.", datewhen='".time()."'");
					$idlike = $this->db1->insert_id();
					$this->db1->query("UPDATE users SET num_likes=num_likes+1 WHERE iduser=".$this->user->id.' LIMIT 1');
					$this->db1->query("UPDATE items SET numlikes=numlikes+1 WHERE iditem=".$iditem.' LIMIT 1');
					$this->db1->query('INSERT INTO activities SET iduser='.$this->user->id.', action=5, idresult='.$idlike.', iditem='.$iditem.', typeitem=1, date="'.time().'"');
					if ($this->user->id != $idowner) {
						$this->db1->query("INSERT INTO notifications SET notif_type=2, idresult=".$idlike.", to_user_id=".$idowner.", from_user_id=".$this->user->id.", notif_object_type=1, notif_object_id=".$iditem.",date='".time()."'");
						$this->db1->query("UPDATE users SET num_notifications=num_notifications+1 WHERE iduser=".$idowner.' LIMIT 1');
					}
				}				
			}
				
			echo('1: Ok');
			return;
		}
		
		
		if ($action == 4) {
			
			if ($whatdo == 0) {
			
				$r = $this->db1->query("DELETE FROM censored WHERE typeitem=2 AND iditem=".$iditem." AND iduser=".$this->user->id);
				$this->db1->query("UPDATE items SET numcensors=numcensors-1 WHERE iditem=".$iditem.' LIMIT 1');
				
			} else {
			
				if ($whatdo == 1) {
					
					$r = $this->db1->query("INSERT INTO censored SET typeitem=2, iditem=".$iditem.", iduser=".$this->user->id.", whendate='".time()."'");
					$idcensored = $this->db1->insert_id();
					$this->db1->query("UPDATE items SET numcensors=numcensors+1 WHERE iditem=".$iditem.' LIMIT 1');
					
				}				
			}
				
			echo('1: Ok');
			return;
		}

	
	}
?>