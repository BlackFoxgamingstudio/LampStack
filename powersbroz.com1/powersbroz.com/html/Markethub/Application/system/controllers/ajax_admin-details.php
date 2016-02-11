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
		$status = -1;
		$uid = 0;
		if (isset($_POST["st"]) && $_POST["st"]!='') $status = $this->db1->e($_POST["st"]);
		if (isset($_POST["uid"]) && $_POST["uid"]!='') $uid = $this->db1->e($_POST["uid"]);
		
		if ($status == -1) { $errored = 1; $txterror .= 'Error... '; }
		if ($uid == 0) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	if ($action == 2)	{
		$level = -1;
		$uid = 0;
		if (isset($_POST["lv"]) && $_POST["lv"]!='') $level = $this->db1->e($_POST["lv"]);
		if (isset($_POST["uid"]) && $_POST["uid"]!='') $uid = $this->db1->e($_POST["uid"]);
		
		if ($level == -1) { $errored = 1; $txterror .= 'Error... '; }
		if ($uid == 0) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	if ($action == 3)	{
		$uid = 0;

		if (isset($_POST["uid"]) && $_POST["uid"]!='') $uid = $this->db1->e($_POST["uid"]);
		
		if ($uid == 0) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	if ($action == 4)	{
		$mvalidated = -1;
		$uid = 0;
		if (isset($_POST["mv"]) && $_POST["mv"]!='') $mvalidated = $this->db1->e($_POST["mv"]);
		if (isset($_POST["uid"]) && $_POST["uid"]!='') $uid = $this->db1->e($_POST["uid"]);
		
		if ($mvalidated == -1) { $errored = 1; $txterror .= 'Error... '; }
		if ($uid == 0) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		
		if ($action == 1) {		

			$this->db1->query("UPDATE users SET active=".$status." WHERE iduser=".$uid." LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		
		}
		
		if ($action == 2) {		

			$this->db1->query("UPDATE users SET is_network_admin=".$level." WHERE iduser=".$uid." LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		
		}
		
		if ($action == 3) {
			
			// look for the ids of albums
			$allalbums = $this->network->getAlbumsUser($uid);
			
			foreach ($allalbums as $onealbum) {
				$thealbum = new album($onealbum->code);
				$thealbum->deleteAlbum();
				unset($thealbum);
			}
			
			$this->db1->query("DELETE FROM activities WHERE iduser=".$uid);
			$this->db1->query("DELETE FROM chat WHERE id_from=".$uid." OR id_to=".$uid);
			$this->db1->query("DELETE FROM comments WHERE iduser=".$uid);
			$this->db1->query("DELETE FROM likes WHERE iduser=".$uid);
			$this->db1->query("DELETE FROM notifications WHERE from_user_id=".$uid." OR to_user_id=".$uid);
			$this->db1->query("DELETE FROM relations WHERE leader=".$uid." OR subscriber=".$uid);
			$this->db1->query("DELETE FROM users_pageviews WHERE iduser=".$uid);
			$this->db1->query("DELETE FROM users WHERE iduser=".$uid);			

			echo('1: Ok');
			return;
		
		}
		
		if ($action == 4) {		

			$this->db1->query("UPDATE users SET validated=".$mvalidated." WHERE iduser=".$uid." LIMIT 1");
			$txtreturn = $this->lang('admin_txt_msgok');
			echo('1: '.$txtreturn);
			return;
		
		}
		
		
	}

?>