<?php
	if( $this->user->is_logged ) {
		$this->redirect('dashboard');
	}
	
	$D->is_logged = $this->user->is_logged;
	
	/*************************************************************************/
	// needed before proceeding
	require_once('_all-required-language.php');
	
	/*************************************************************************/
	
	$this->load_langfile('global/global.php');
	$this->load_langfile('outside/home.php');
	
	/*************************************************************************/

	$D->userAleat = $this->network->getUsersAleat(8);
	$D->numUserAleat = count($D->userAleat);
	
	$D->photosRecents = $this->network->getItemsRecents($C->ITEMS_RECENTS_HOME, 1);
	$D->numphotosrecent = count($D->photosRecents);

	$D->gifRecents = $this->network->getItemsRecents($C->ITEMS_RECENTS_HOME, 2);
	$D->numgifrecent = count($D->gifRecents);
	
	$D->videosRecents = $this->network->getItemsRecents($C->ITEMS_RECENTS_HOME, 3);
	$D->numvideosrecent = count($D->videosRecents);
	
	/*************************************************************************/

	$D->page_title	= $this->lang('home_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));

	$this->load_template('home.php');
?>