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
		
		if (empty($D->u->aboutme)) $D->aboutme = $this->lang('profile_userinfo_withoutinfo');
		else $D->aboutme = $D->u->aboutme;
		
		if (empty($D->u->codecountry) || $D->u->idregion==0 || empty($D->u->city)) {
			$D->location = $this->lang('profile_userinfo_withoutinfo');
		} else {
			$D->country = $this->network->get_country($D->u->codecountry);
			$D->region = $this->network->get_region($D->u->idregion);
			$D->city = $D->u->city;
			$D->location = $D->country.', '.$D->region.', '.$D->city;			
		}
		
		if ($D->u->gender == 0) $D->gender = $this->lang('profile_userinfo_withoutinfo');
		else {
			if ($D->u->gender == 1) $D->gender = $this->lang('profile_userinfo_gender1');
			else $D->gender = $this->lang('profile_userinfo_gender2');
		}
		
		if ($D->u->born == 0) $D->birth = $this->lang('profile_userinfo_withoutinfo');
		else {
			$bday = date('d',$D->u->born);
			$bmonth = date('F',$D->u->born);
			$byear = date('Y',$D->u->born);
			$D->birth = $bmonth.' '.$bday.', '.$byear;
		}

		
	}

	/*************************************************************************/
	
	$D->page_title = $D->nameUser.' - '.$this->lang('profile_userinfo_title').' - '.$C->SITE_TITLE;
	
	$D->optionactive = 4;

	$this->load_template('infouser.php');
?>