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
	
	//We load the user's following
	$D->totalfollowing = $this->db2->fetch_field('SELECT count(iduser) FROM users, relations WHERE iduser=leader AND subscriber='.$this->user->id);
	
	$r = $this->db2->query('SELECT iduser, code, firstname, lastname, username, avatar, num_items, num_followers, num_following, validated FROM users, relations WHERE iduser=leader AND subscriber='.$this->user->id.' ORDER BY rltdate DESC LIMIT 0,'.$C->NUM_FOLLOWING_PAGE);

	$D->numfollowing = $this->db2->num_rows();

	ob_start();
	
	while( $obj = $this->db2->fetch_object($r) ) {
		$D->isThisUserVerified = $obj->validated==1?TRUE:FALSE;
		$D->f_name = (empty($obj->firstname) || empty($obj->lastname))?stripslashes($obj->username):(stripslashes($obj->firstname).' '.stripslashes($obj->lastname));
		$D->f_avatar = $obj->avatar;
		$D->f_numitems = $obj->num_items;
		$D->f_username = $obj->username;
		$this->load_template('__dashboard-one-following.php');
	}
	
	$D->htmlFollowing = ob_get_contents();
	ob_end_clean();
	
	unset($r, $obj);

	/*************************************************************************/

	$D->showaccessdirect = TRUE;
	$D->optionactive = 7;
	$this->load_template('dashboard-following.php');
?>