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

	
	// We see how many notifications are for this user
	$totalitems = $this->db2->fetch_field('SELECT count(idnotification) FROM notifications WHERE to_user_id='.$this->user->id);
	
	// We extract the notifications	
	$r = $this->db2->query('SELECT notif_type, notif_object_id, username, firstname, lastname, avatar, date FROM notifications, users WHERE users.iduser=from_user_id AND to_user_id='.$this->user->id.' ORDER BY date DESC LIMIT '.$numitems.', '.$C->NUM_NOTIFICATIONS_PAGE);
	
	$numitemsnow = $this->db2->num_rows();

	$htmlResults = '';
	ob_start();
	while( $obj = $this->db2->fetch_object($r) ) {
		$D->n_nameUser = (empty($obj->firstname) || empty($obj->lastname))?$obj->username:($obj->firstname.' '.$obj->lastname);
		$D->n_username = $obj->username;
		$D->n_avatar = $obj->avatar;
		$D->n_fdate = $obj->{'date'};
		$D->n_idphoto = $obj->notif_object_id;
		$D->n_typenotifications = $obj->notif_type;
		$D->n_photocode = $this->network->getCodePhoto($D->n_idphoto);
		$this->load_template('__dashboard-one-notification.php');
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