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
		$viewPhoto = 0;
		$D->codphoto = '';
		if ($this->param('codphoto')) {
			$D->codphoto = $this->param('codphoto');
			if (!$this->network->verifiedPhoto($D->codphoto,$D->u->iduser)) $this->redirect($C->SITE_URL.$D->u->username.'/items');
			$viewPhoto = 1;
		}		
		
		if ($viewPhoto == 1) {
			$D->selectedPhoto = new item($D->codphoto);
			if ($D->selectedPhoto->error) $this->redirect($D->u->username.'/items');
			
			if ($D->selectedPhoto->numcensors >= $C->NUM_CENSOR_NOT_SHOW_ITEM) {
				$D->optionactive = 2;
				$this->load_template('items-censor.php');
			} else {
				$D->codeItem = $D->selectedPhoto->code;
				$D->idItem = $D->selectedPhoto->id;
				$D->idUser = $D->selectedPhoto->iduser;
				$D->p_numviews = $D->selectedPhoto->numviews;
				$D->p_numcomments = $D->selectedPhoto->numcomments;
				$D->p_numlikes = $D->selectedPhoto->numlikes;
				
				$D->prevnext = $this->network->getNextAndPrev($D->selectedPhoto->idalbum, $D->idItem);
				
				$D->usernameProfile = $D->u->username;
				$D->codeAlbum = $this->network->getCodeAlbum($D->selectedPhoto->idalbum);
				
				// see if the favorite is for the observer
				$D->liketoUser=0;
				if ($D->is_logged==1) {
					if ($D->selectedPhoto->likeOfUser($this->user->id) > 0) $D->liketoUser = 1;
				}
				
				// check if the user censured item
				$D->censoredbyUser = 0;
				if ($D->is_logged == 1) {
					if ($D->selectedPhoto->censoredbyUser($this->user->id) > 0) $D->censoredbyUser = 1;
				}
				
				// see the comments on this item
				$D->htmlComments = '';
				$allComments = $D->selectedPhoto->getComments();
				ob_start();
				foreach($allComments as $onecomment) {
					$D->onecomments = $onecomment;
					$D->o_comment = stripslashes($D->onecomments->comment);
					$D->o_username = stripslashes($D->onecomments->username);
					$D->o_firstname = stripslashes($D->onecomments->firstname);
					$D->o_lastname = stripslashes($D->onecomments->lastname);
					$D->o_nameUser = (empty($D->o_firstname) || empty($D->o_lastname))?stripslashes($D->o_username):(stripslashes($D->o_firstname).' '.stripslashes($D->o_lastname));
					$D->o_whendate = $D->onecomments->whendate;
					$D->o_avatar =  empty($D->onecomments->avatar)?$C->AVATAR_DEFAULT:$D->onecomments->avatar;
					$D->o_idcomment = $D->onecomments->idcomment;
					$D->o_owner = 0;
					if ($D->is_logged==1) {
						if ($this->user->id == $D->onecomments->iduser) $D->o_owner = 1;
					}
					$this->load_template('__profile-onecomment-item.php');			
				}
				$D->htmlComments	= ob_get_contents();
				ob_end_clean();
				unset($onecomment, $D->comments);
				
				$D->page_title = $D->nameUser.' - '.stripslashes($D->selectedPhoto->title).' - '.$this->lang('profile_items_title').' - '.$C->SITE_TITLE;
				$D->optionactive = 2;
				$D->selectedPhoto->increaseVisits();
				$this->load_template('items-details.php');
			}

		} else {
			
			$D->totalalbums = $this->db2->fetch_field('SELECT count(idalbum) FROM albums WHERE iduser='.$D->u->iduser);
			
			$r = $this->db2->query('SELECT idalbum, code, name, numitems, description FROM albums WHERE iduser='.$D->u->iduser.' ORDER BY idalbum DESC LIMIT 0,'.$C->NUM_ALBUMS_PAGE);
	
			$D->numalbums = $this->db2->num_rows();
	
			ob_start();
			$D->htmlAlbums = '';
			
			while( $obj = $this->db2->fetch_object($r) ) {
				$D->namealbum = stripslashes($obj->name);
				$D->description = stripslashes($obj->description);
				$D->codealbum = $obj->code;
				$D->numitems = $obj->numitems;
				$D->miniphotos = $this->network->getPhotosAlbum($obj->idalbum,6);
				$D->usernameProfile = $D->u->username;
				$this->load_template('__profile-onealbum.php');
			}
			
			$D->htmlAlbums = ob_get_contents();
			ob_end_clean();
			
			unset($r, $obj);

			$D->page_title = $D->nameUser.' - '.$this->lang('profile_items_title').' - '.$C->SITE_TITLE;
			$D->optionactive = 2;
			$this->load_template('items.php');
			
		}
	} else {
		$D->page_title = $D->nameUser.' - '.$this->lang('profile_items_title').' - '.$C->SITE_TITLE;
		$D->optionactive = 2;
		$this->load_template('items.php');
	}
?>