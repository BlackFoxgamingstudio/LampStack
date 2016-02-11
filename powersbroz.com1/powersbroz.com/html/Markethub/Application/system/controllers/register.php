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
	$this->load_langfile('outside/register.php');
	
	/*************************************************************************/


	$D->page_title	= $this->lang('register_title_page').' - '.$C->SITE_TITLE;
	
	$this->load_template('register.php');
?>