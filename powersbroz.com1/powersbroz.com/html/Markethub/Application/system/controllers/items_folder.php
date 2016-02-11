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
		
		$D->codalbum = '';
		if ($this->param('codalbum')) {
			$D->codalbum = $this->param('codalbum');
			if (!$this->network->verifiedAlbum($D->codalbum,$D->u->iduser)) $this->redirect($C->SITE_URL.$D->u->username.'/items');
		}
		
		$D->usernameProfile = $D->u->username;		

		$objalbum = new album($D->codalbum);
		$D->namealbum = ($objalbum->name);
		$D->description = ($objalbum->description);
		
		$D->arrayPhotos = $objalbum->getItems();
		
		$D->htmlPhotos = '';
		
		ob_start();
		foreach($D->arrayPhotos as $onephoto)
		{
			$D->g = $onephoto;
			$D->g->title = stripslashes($D->g->title);
			switch($onephoto->typeitem) {
				case 1:
					$D->typeitem = $this->lang('global_txt_type1');
					break;
				case 2:
					$D->typeitem =  $this->lang('global_txt_type2');
					break;
				case 3:
					$D->typeitem =  $this->lang('global_txt_type3');
					break;			
			}
			$this->load_template('__profile-oneitem.php');
		}
		$D->htmlPhotos	= ob_get_contents();
		ob_end_clean();
		
		unset($onephoto, $D->g);
		
		unset($objalbum);
		
		$D->page_title = $D->nameUser.' - '.$D->namealbum.' - '.$this->lang('profile_items_title').' - '.$C->SITE_TITLE;
		
		$D->optionactive = 2;
	
		$this->load_template('items_folder.php');

		
	} else {
	
		$D->page_title = $D->nameUser.' - '.$this->lang('profile_items_title').' - '.$C->SITE_TITLE;
		
		$D->optionactive = 2;
	
		$this->load_template('items_folder.php');
	
	}
?>