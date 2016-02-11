<?php
/*************************************************************************/

	// If there is a user logged in, get ready a variable for use.
	$D->is_logged = 0;
	if ($this->user->is_logged) {
		$D->me = $this->user->info;
		$D->is_logged = 1;
	}
/*************************************************************************/

	$D->site_keywords = $C->SITE_TITLE.', '.$C->SITE_KEYWORDS;
	$D->site_description = $C->SITE_TITLE.', '.$C->SITE_DESCRIPTION;

/*************************************************************************/

?>