<?php
/*************************************************************************/


/*************************************************************************/

	// If there is a user logged in, get ready a variable for use.
	$D->is_my_profile = 0;
	$D->is_logged = 0;
	$D->he_follows_me = 0;
	$D->i_follow_him = 0;
	if ($this->user->is_logged) {
		$D->me = $this->user->info;
		$D->is_logged = 1;
		
		$D->is_my_profile	=  ($D->u->iduser == $this->user->id);
		
		$D->i_follow_him = $this->user->if_follow_user($D->u->iduser);
		$D->he_follows_me = $this->user->if_user_follows_me($D->u->iduser);
	}
/*************************************************************************/

	$D->adsProfile1 = trim($this->network->get_ads('adsProf01'));
	$D->adsProfile2 = trim($this->network->get_ads('adsProf02'));
	
/*************************************************************************/
	
	$D->isUserVerified = 0;
	$D->isUserVerified = $D->u->validated==1?1:0;
	
	$D->nameUser = (empty($D->u->firstname) || empty($D->u->lastname))?$D->u->username:($D->u->firstname.' '.$D->u->lastname);

/*************************************************************************/

	$D->i_am_network_admin = ( $this->user->is_logged && $this->user->info->is_network_admin );
	
	// We check if it is display the profile information
	$D->privacy = $D->u->privacy;
	$D->profile_public = $D->profile_privado = $D->profile_only_followers = 0;
	$D->show_profile = 0;
	switch($D->privacy) {
		case 0:
			$D->show_profile = 1;
			$D->profile_public = 1;
			break;
		case 1:
			if ($this->user->is_logged || $D->i_am_network_admin) {
				$D->show_profile = 1;
				$D->profile_privado = 1;
			}
			break;
		case 2:
			if (($this->user->is_logged || $D->i_am_network_admin) && ($D->i_follow_him || $D->he_follows_me || $D->is_my_profile)) {
				$D->show_profile = 1;
				$D->profile_only_followers = 1;
			}
			break;
	}
	
/*************************************************************************/

	$D->useraccesories = '';
	if ($this->user->is_logged) $userAleat = $this->network->getUserAleat(5,$D->u->iduser);
	else $userAleat = $this->network->getUserAleat(5,$D->u->iduser,0);
	
	ob_start();
	
	foreach($userAleat as $oneUser) {
		$D->acc_name = (empty($oneUser->firstname) || empty($oneUser->lastname))?stripslashes($oneUser->username):(stripslashes($oneUser->firstname).' '.stripslashes($oneUser->lastname));
		$D->acc_avatar = $oneUser->avatar;
		$D->acc_numitems = $oneUser->num_items;
		$D->acc_username = $oneUser->username;
		$this->load_template('__accessories-one-user.php');
	}

	$D->useraccesories = ob_get_contents();
	ob_end_clean();
	
	unset($userAleat, $oneUser);
	
	
/*************************************************************************/

	$D->site_keywords = $C->SITE_TITLE.', '.$D->nameUser.', '.$D->u->username.', '.$C->SITE_KEYWORDS;
	$D->site_description = $C->SITE_TITLE.', '.$D->nameUser.', '.$D->u->username.', '.$C->SITE_DESCRIPTION;

/*************************************************************************/

?>