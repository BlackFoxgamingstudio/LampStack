<?php
	if( !$this->user->is_logged ) {
		$this->redirect('login');
	}
	
	
	/*************************************************************************/
	// needed before proceeding
	require_once('_all-required-language.php');
	
	$this->load_langfile('global/global.php');	
	$this->load_langfile('inside/dashboard.php');

	/*************************************************************************/
	
	
	require_once('_all-required-dashboard.php');
	

	/*************************************************************************/
	
	//$r = $this->db2->query('SELECT * FROM (SELECT * FROM chat, users WHERE id_to='.$this->user->id.' AND isread=0 AND id_from = iduser ORDER BY id DESC) as x GROUP BY id_from');
	
	//We load the user's in chat
	$D->totalchats = $this->db2->fetch_field('SELECT COUNT(DISTINCT id_from) FROM chat WHERE id_to='.$this->user->id);
	$r = $this->db2->query('SELECT * FROM (SELECT * FROM chat, users WHERE id_to='.$this->user->id.' AND id_from = iduser ORDER BY thedate DESC) as itemchat GROUP BY id_from ORDER BY thedate DESC LIMIT 0,'.$C->NUM_USERCHAT_PAGE);

	$D->numuserschats = $this->db2->num_rows();

	
	$D->htmlUserChats = '';
	ob_start();
	
	while( $obj = $this->db2->fetch_object($r) ) {
		$D->isThisUserVerified = $obj->validated==1?TRUE:FALSE;
		$D->f_name = (empty($obj->firstname) || empty($obj->lastname))?stripslashes($obj->username):(stripslashes($obj->firstname).' '.stripslashes($obj->lastname));
		$D->f_avatar = $obj->avatar;
		$D->f_lastmessage = analyzeMessage($obj->message);
		$D->f_username = $obj->username;
		$D->f_date = $obj->thedate;
		$this->load_template('__dashboard-one-userchat.php');
	}
	
	$D->htmlUserChats = ob_get_contents();
	ob_end_clean();
	
	unset($r, $obj);

	/*************************************************************************/
	
	$D->showaccessdirect = TRUE;
	$D->optionactive = 8;
	$this->load_template('dashboard-mymessages.php');
?>