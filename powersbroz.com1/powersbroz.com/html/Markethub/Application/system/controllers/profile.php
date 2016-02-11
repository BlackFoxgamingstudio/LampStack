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

		$D->totalactivities = $this->db2->fetch_field('SELECT count(id) FROM activities WHERE iduser='.$D->u->iduser);

		$theactivities = $this->db2->fetch_all('SELECT activities.iduser, iduser2, action, idresult, iditem, date, username, firstname, lastname, avatar FROM activities, users WHERE (users.iduser=activities.iduser) AND activities.iduser='.$D->u->iduser.' ORDER BY date DESC LIMIT 0,'.$C->NUM_ACTIVITIES_PAGE);

		$D->numactivities = count($theactivities);
		
		$D->nameUser = (empty($D->u->firstname) || empty($D->u->lastname))?$D->u->username:($D->u->firstname.' '.$D->u->lastname);
		 
		// see if there are "follows" and we group the user ids seconds
		$secondsids = array();
		foreach($theactivities as $oneactivity) {
			if ($oneactivity->action == 1 && $oneactivity->iduser2 != 0) $secondsids[] = $oneactivity->iduser2;
		}
		
		if (count($secondsids) > 0) {
			$following = $this->db2->fetch_all('SELECT iduser, username, firstname, lastname, avatar, num_items, validated FROM users WHERE iduser in ('.implode($secondsids,',').')');
		}
		/////////
		
		$D->htmlResult = '';
		
		ob_start();
		foreach($theactivities as $theactivity) {
			
			switch ($theactivity->action) {
				case 1:
					$D->txtaction = $this->lang('profile_activities_follow');
					foreach($following as $onefg) {
						if ($onefg->iduser == $theactivity->iduser2) {
							$D->isThisUserVerified = $onefg->validated==1?TRUE:FALSE;
							$D->f_username = $onefg->username;
							$D->f_date = $theactivity->{'date'};
							$D->f_name = (empty($onefg->firstname) || empty($onefg->lastname))?stripslashes($onefg->username):(stripslashes($onefg->firstname).' '.stripslashes($onefg->lastname));
							$D->f_avatar = $onefg->avatar;
							$D->f_numitems = $onefg->num_items;
							$this->load_template('__profile-activity-one-following.php');
						}
					}
					
					break;
				
				case 2:
				
					$D->txtaction = $this->lang('profile_activities_createdalbum');
					$D->a_date = $theactivity->{'date'};
					
					$D->codealbum = $this->network->getCodeAlbum($theactivity->iditem);
					$oneAlbum = new album($D->codealbum);
					$D->name = stripslashes($oneAlbum->name);
					$D->description = stripslashes($oneAlbum->description);
					$D->numitems = $oneAlbum->numitems;
	
					$this->load_template('__profile-activity-one-album.php');
					break;
				
				case 3:
				case 6:
				case 7:
					$D->txtaction = $this->lang('profile_activities_createdphoto');
					$D->a_date = $theactivity->{'date'};
					
					$D->codephoto = $this->network->getCodePhoto($theactivity->iditem);
					$oneItem = new item($D->codephoto);
					$D->imageitem = $oneItem->imageitem;
					$D->numviews = $oneItem->numviews;
					$D->numlikes = $oneItem->numlikes;
					$D->numcomments = $oneItem->numcomments;
					$D->title = stripslashes($oneItem->title);
					$D->nameAlbum = $this->network->getNameAlbum($oneItem->idalbum);
					$D->codeAlbum = $this->network->getCodeAlbum($oneItem->idalbum);
					switch($oneItem->typeitem) {
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
					$this->load_template('__profile-activity-one-item.php');
					unset($oneItem);
					break;
				
				case 4:
					$D->txtaction = $this->lang('profile_activities_commentphoto');
					$D->a_date = $theactivity->{'date'};
					
					$D->codephoto = $this->network->getCodePhoto($theactivity->iditem);
					$oneItem = new item($D->codephoto);
					$D->imageitem = $oneItem->imageitem;
					$D->title = stripslashes($oneItem->title);
					$D->nameAlbum = $this->network->getNameAlbum($oneItem->idalbum);
					$D->codeAlbum = $this->network->getCodeAlbum($oneItem->idalbum);
					$D->txtcomment = stripslashes($this->network->getComment($theactivity->idresult));
					$D->f_username = stripslashes($this->network->getUsername($oneItem->iduser));
					switch($oneItem->typeitem) {
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
					$this->load_template('__profile-activity-one-comment.php');
					unset($oneItem);
					break;
					
				case 5:
					$D->txtaction = $this->lang('profile_activities_favoritephoto');
					$D->a_date = $theactivity->{'date'};
					
					$D->codephoto = $this->network->getCodePhoto($theactivity->iditem);
					$oneItem = new item($D->codephoto);
					$D->imageitem = $oneItem->imageitem;
					$D->numviews = $oneItem->numviews;
					$D->numlikes = $oneItem->numlikes;
					$D->numcomments = $oneItem->numcomments;
					$D->title = stripslashes($oneItem->title);
					$D->l_username = stripslashes($this->network->getUsername($oneItem->iduser));
					switch($oneItem->typeitem) {
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
					$this->load_template('__profile-activity-one-like.php');
					unset($oneItem);

					break;
				
			}
		}
		
		//ponemos el dia de registro del perfil
		if ($D->totalactivities<=$C->NUM_ACTIVITIES_PAGE) $this->load_template('__profile-info-register.php');
		///////////////////////////////////////
		
		$D->htmlResult = ob_get_contents();
		ob_end_clean();

	}

	/*************************************************************************/

	$D->page_title = $D->nameUser.' - '.$this->lang('profile_activities_title').' - '.$C->SITE_TITLE;
	
	$D->optionactive = 1;	
	$this->load_template('profile.php');
?>