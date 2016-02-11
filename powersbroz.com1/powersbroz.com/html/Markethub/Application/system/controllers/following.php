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
	if ($D->show_profile==1) {

		//We load the user's following
		$D->totalfollowing = $this->db2->fetch_field('SELECT count(iduser) FROM users, relations WHERE iduser=leader AND subscriber='.$D->u->iduser);
		
		$r = $this->db2->query('SELECT iduser, code, firstname, lastname, username, avatar, num_items, num_followers, num_following, validated FROM users, relations WHERE iduser=leader AND subscriber='.$D->u->iduser.' ORDER BY rltdate DESC LIMIT 0,'.$C->NUM_FOLLOWING_PAGE);

		$D->numfollowing = $this->db2->num_rows();

		ob_start();
		$D->htmlFollowing = '';
		
		while( $obj = $this->db2->fetch_object($r) ) {
			$D->isThisUserVerified = $obj->validated==1?TRUE:FALSE;
			$D->f_name = (empty($obj->firstname) || empty($obj->lastname))?stripslashes($obj->username):(stripslashes($obj->firstname).' '.stripslashes($obj->lastname));
			$D->f_avatar = $obj->avatar;
			$D->f_numitems = $obj->num_items;
			$D->f_username = $obj->username;
			$this->load_template('__profile-one-following.php');
		}
		
		$D->htmlFollowing = ob_get_contents();
		ob_end_clean();
		
		unset($r, $obj);
	}

	/*************************************************************************/
	
	$D->page_title = $D->nameUser.' - '.$this->lang('profile_following_title').' - '.$C->SITE_TITLE;
	
	$D->optionactive = 0;

	$this->load_template('following.php');
?>