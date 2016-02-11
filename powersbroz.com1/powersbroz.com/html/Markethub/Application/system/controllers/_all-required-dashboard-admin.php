<?php
/*************************************************************************/

	// If there is a user logged in, get ready a variable for use.
	$D->is_logged = 0;
	if ($this->user->is_logged) {
		$D->me = $this->user->info;
		$D->is_logged = 1;
	}

/*************************************************************************/

	$D->i_am_network_admin = ( $this->user->is_logged && $this->user->info->is_network_admin );
	
/*************************************************************************/

	$D->page_title = 'Admin - '.$C->SITE_TITLE;

?>