<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('inside/dashboard.php');

	
	$r = $this->db2->query('SELECT * FROM (SELECT * FROM chat, users WHERE id_to='.$this->user->id.' AND id_from = iduser ORDER BY id DESC) as itemchat GROUP BY id_from ORDER BY id DESC LIMIT '.$C->NUM_NOTIFICATIONSMSG_ALERT);

	$this->db2->query('UPDATE chat SET isread=1 WHERE id_to='.$this->user->id.' AND isread=0');

	$txtResult = '';
	ob_start();
	while( $obj = $this->db2->fetch_object($r) ) {
		$D->username = $obj->username;
		$D->uname = (empty($obj->firstname) || empty($obj->lastname))?stripslashes($obj->username):(stripslashes($obj->firstname).' '.stripslashes($obj->lastname));
		$D->avatar = $obj->avatar;
		$D->dateago = $obj->thedate;
		$D->message = analyzeMessage(str_cut($obj->message,50));
		$this->load_template('__dashboard-one-alert-messages.php');
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