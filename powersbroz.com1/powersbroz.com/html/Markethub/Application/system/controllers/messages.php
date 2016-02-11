<?php

	if( !$this->network->id ) {
		$this->redirect('home');
	}
	
	// We check if the site is open to all
	if ($C->PROTECT_OUTSIDE_PAGES && !$this->user->is_logged) {
		$this->redirect('login');
	}
	
	// Obtain user data profile
	$D->u = $this->network->get_user_by_id(intval($this->params->iduser));
	if( !$D->u ) {
		$this->redirect('dashboard');
	}
	
	/*************************************************************************/
	// needed before proceeding
	require_once('_all-required-language.php');
	
	/*************************************************************************/

	$this->load_langfile('global/global.php');	
	$this->load_langfile('outside/profile.php');

	/*************************************************************************/
	
	// needed before proceeding
	require_once('_all-required-profile.php');
	
	/*************************************************************************/

	// If allowed, it loaded data required for this section
	if ($D->show_profile==1 && !$D->is_my_profile && $D->is_logged==1) {
		
		// find the total number of messages
		$D->totalmsgchat = $this->db2->fetch_field('SELECT count(chat.id) FROM users, chat WHERE (id_from='.$this->user->id.' AND id_to='.$D->u->iduser.' AND id_from=iduser) OR (id_from='.$D->u->iduser.' AND id_to='.$this->user->id.' AND id_from=iduser)');
		
		$r = $this->db2->query('SELECT * FROM users, chat WHERE (id_from='.$this->user->id.' AND id_to='.$D->u->iduser.' AND id_from=iduser) OR (id_from='.$D->u->iduser.' AND id_to='.$this->user->id.' AND id_from=iduser) ORDER BY chat.id DESC LIMIT 0,'.$C->CHAT_NUM_MSG_START);

		$D->nummsgchat = $this->db2->num_rows();
		
		$D->htmlChat = '';
		
		if ($r) {
			$this->db2->query('UPDATE chat SET isread=1 WHERE id_from='.$D->u->iduser.' AND id_to='.$this->user->id.' AND isread=0');
			
			$rowmsgs = array();
			
			while( $obj = $this->db2->fetch_object($r) ) $rowmsgs[] = $obj;

			$rowmsgs = array_reverse($rowmsgs);	

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
			
			$D->htmlChat = ob_get_contents();
			ob_end_clean();
		
			unset($r, $obj);
		}
	}
	

	/*************************************************************************/
	
	$D->page_title = $D->nameUser.' - '.$this->lang('profile_messages_title').' - '.$C->SITE_TITLE;
	
	$D->optionactive = 5;

	$this->load_template('messages.php');
?>