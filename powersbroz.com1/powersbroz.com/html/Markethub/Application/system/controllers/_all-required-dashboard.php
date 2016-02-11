<?php
/*************************************************************************/

	// If there is a user logged in, get ready a variable for use.
	$D->is_logged = 0;
	if ($this->user->is_logged) {
		$D->me = $this->user->info;
		$D->is_logged = 1;
	}
	
/*************************************************************************/

	$D->adsDashboard1 = trim($this->network->get_ads('adsDash01'));
	$D->adsDashboard2 = trim($this->network->get_ads('adsDash02'));

/*************************************************************************/

	$D->i_am_network_admin = ( $this->user->is_logged && $this->user->info->is_network_admin );
	
/*************************************************************************/

	$D->useraccesories = '';
	$userAleat = $this->network->getUserAleat(5,$this->user->id);
	
	ob_start();
	
	foreach($userAleat as $oneUser) {
		$D->accd_name = (empty($oneUser->firstname) || empty($oneUser->lastname))?stripslashes($oneUser->username):(stripslashes($oneUser->firstname).' '.stripslashes($oneUser->lastname));
		$D->accd_avatar = $oneUser->avatar;
		$D->accd_numphotos = $oneUser->num_items;
		$D->accd_username = $oneUser->username;
		$this->load_template('__accessories-dashboard-one-user.php');
	}

	$D->useraccesories = ob_get_contents();
	ob_end_clean();
	
	unset($userAleat, $oneUser);
	
	
/*************************************************************************/

?>