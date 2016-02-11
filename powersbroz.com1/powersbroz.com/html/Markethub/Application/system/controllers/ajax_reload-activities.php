<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('outside/profile.php');
	
	$errored = 0;
	$txterror = '';

	$numitems = $iduser = 0;
	
	if (isset($_POST["ni"]) && !empty($_POST["ni"])) $numitems = $this->db1->e($_POST["ni"]);
	if (isset($_POST["idu"]) && !empty($_POST["idu"])) $iduser = $this->db1->e($_POST["idu"]);
	
	if (!is_numeric($numitems) || $numitems <= 0) { $errored = 1; $txterror .= 'Error. '; }
	if (!is_numeric($iduser) || $iduser <= 0) { $errored = 1; $txterror .= 'Error. '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		
		$itemsperpage = $C->NUM_ACTIVITIES_PAGE;
		
		$D->u = $this->network->get_user_by_id(intval($iduser));
		
		$totalitems = $this->db2->fetch_field('SELECT count(id) FROM activities WHERE iduser='.$iduser);

		$theactivities = $this->db2->fetch_all('SELECT activities.iduser, iduser2, action, idresult, iditem, date FROM activities, users WHERE (users.iduser=activities.iduser) AND activities.iduser='.$iduser.' ORDER BY date DESC LIMIT '.$numitems.','.$itemsperpage);

		$numitemsnow = count($theactivities);
		
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
		
		$htmlResults = '';
		
		ob_start();
		foreach($theactivities as $oneactivity) {
			
			switch ($oneactivity->action) {
				case 1:
					$D->txtaction = $this->lang('profile_activities_follow');
					foreach($following as $onefg) {
						if ($onefg->iduser == $oneactivity->iduser2) {
							$D->isThisUserVerified = $onefg->validated==1?TRUE:FALSE;
							$D->f_username = $onefg->username;
							$D->f_date = $oneactivity->{'date'};
							$D->f_name = (empty($onefg->firstname) || empty($onefg->lastname))?stripslashes($onefg->username):(stripslashes($onefg->firstname).' '.stripslashes($onefg->lastname));
							$D->f_avatar = $onefg->avatar;
							$D->f_numitems = $onefg->num_items;
							$this->load_template('__profile-activity-one-following.php');
						}
					}
					
					break;
				
				case 2:
					$D->txtaction = $this->lang('profile_activities_createdalbum');
					$D->a_date = $oneactivity->{'date'};
					
					$D->codealbum = $this->network->getCodeAlbum($oneactivity->iditem);
					$oneAlbum = new album($D->codealbum);
					$D->name = stripslashes($oneAlbum->name);
					$D->descripcion = stripslashes($oneAlbum->descripcion);
					$D->numitems = $oneAlbum->numitems;
	
					$this->load_template('__profile-activity-one-album.php');
					break;
				
				case 3:
				case 6:
				case 7:
					$D->txtaction = $this->lang('profile_activities_createdphoto');
					$D->a_date = $oneactivity->{'date'};
					
					$D->codephoto = $this->network->getCodePhoto($oneactivity->iditem);
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
					$D->a_date = $oneactivity->{'date'};
					
					$D->codephoto = $this->network->getCodePhoto($oneactivity->iditem);
					$oneItem = new item($D->codephoto);
					$D->imageitem = $oneItem->imageitem;
					$D->title = stripslashes($oneItem->title);
					$D->nameAlbum = $this->network->getNameAlbum($oneItem->idalbum);
					$D->codeAlbum = $this->network->getCodeAlbum($oneItem->idalbum);
					$D->txtcomment = stripslashes($this->network->getComment($oneactivity->idresult));
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
					
					$D->codephoto = $this->network->getCodePhoto($oneactivity->iditem);
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
		if ($totalitems <= $numitemsnow + $numitems) {
			$D->isUserVerified = $this->network->isUserVerified($iduser);
			$this->load_template('__profile-info-register.php');
		}
		///////////////////////////////////////
		
		$htmlResults = ob_get_contents();
		ob_end_clean();

		
		if ($totalitems <= ($numitemsnow + $numitems) ) {
			echo("2: ".$htmlResults);
			return;
		} else {
			echo("1: ".$htmlResults);
			return;	
		}
			
	}


?>