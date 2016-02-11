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


		//We load the likes
		$D->totallikes = $this->db2->fetch_field('SELECT count(idlike) FROM likes WHERE iduser='.$D->u->iduser);
		
		$r = $this->db2->query('SELECT idlike, items.*, users.username FROM likes, items, users WHERE likes.typeitem=2 AND likes.iduser='.$D->u->iduser.' AND likes.iditem=items.iditem AND items.iduser=users.iduser ORDER BY datewhen DESC LIMIT 0,'.$C->NUM_FAVORITES_PAGE);
	
		$D->numlikes = $this->db2->num_rows();
	
		$D->htmlLikes = ''; 
		ob_start();
		
		while( $obj = $this->db2->fetch_object($r) ) {
			$D->g = $obj;
			$D->g->title = stripslashes($D->g->title);
			switch($D->g->typeitem) {
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
			$this->load_template('__profile-onelike.php');
		}
		
		$D->htmlLikes = ob_get_contents();
		ob_end_clean();
		
		unset($r, $obj);



	}

	/*************************************************************************/
	
	$D->page_title = $D->nameUser.' - '.$this->lang('profile_like_title').' - '.$C->SITE_TITLE;
	
	$D->optionactive = 3;

	$this->load_template('likes.php');
?>