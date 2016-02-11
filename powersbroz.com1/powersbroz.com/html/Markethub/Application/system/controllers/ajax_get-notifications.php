<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('inside/dashboard.php');

	// We put the number of notices to 0
	$this->db2->query('UPDATE users SET num_notifications=0 WHERE iduser='.$this->user->id.' LIMIT 1');

	// We extract the notifications	
	$r = $this->db2->query('SELECT notif_type, notif_object_id, username, firstname, lastname, avatar, date FROM notifications, users WHERE users.iduser=from_user_id AND to_user_id='.$this->user->id.' ORDER BY date DESC LIMIT '.$C->NUM_NOTIFIC_TOP);


	$txtResult = '';
	ob_start();
	while( $obj = $this->db2->fetch_object($r) ) {
		$D->n_nameUser = (empty($obj->firstname) || empty($obj->lastname))?$obj->username:($obj->firstname.' '.$obj->lastname);
		$D->n_username = $obj->username;
		$D->n_avatar = $obj->avatar;
		$D->n_fdate = $obj->{'date'};
		$D->n_idphoto = $obj->notif_object_id;
		$D->n_typenotifications = $obj->notif_type;
		$D->n_photocode = $this->network->getCodePhoto($D->n_idphoto);
		$this->load_template('__dashboard-one-alert-notifications.php');
	}
	$txtResult = ob_get_contents();
	ob_end_clean();
	
	unset($r, $obj);

	if (!empty($txtResult)) {
		echo('1: '.$txtResult);
		return;
	} else {
		echo('1: <div style="text-align:center; padding:0 5px 5px">'.$this->lang('dashboard_mynotifications_nonotifications').'</div>');
		return;
	}
	
		
?>