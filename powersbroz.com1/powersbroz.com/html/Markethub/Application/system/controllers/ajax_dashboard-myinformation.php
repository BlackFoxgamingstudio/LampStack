<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('inside/dashboard.php');

	// We are here only if you're logged in
	if (!$this->user->is_logged) {
		echo('0: '.$this->lang('dashboard_no_session'));
		die();
	}
	
	$errored = 0;
	$txterror = '';
	
	$action=0;
	
	if (isset($_POST["todo"]) && $_POST["todo"] != '') $action = $this->db1->e($_POST["todo"]);
	if (!is_numeric($action)) {
		$errored = 1;
		$txterror .= 'Error. ';
		echo('0: '.$caderror);
		die();
	}

	// Evaluate personal data
	if ($action == 1)	{

		$lastname = $firstname = $born = '';
		$gender = 0;
		
		if (isset($_POST["ln"]) && !empty($_POST["ln"])) $lastname = $this->db1->e(htmlspecialchars($_POST["ln"]));
		if (isset($_POST["fn"]) && !empty($_POST["fn"])) $firstname = $this->db1->e(htmlspecialchars($_POST["fn"]));
		if (isset($_POST["g"]) && !empty($_POST["g"])) $gender = $this->db1->e($_POST["g"]);
		if (isset($_POST["b"]) && !empty($_POST["b"])) $born = $this->db1->e($_POST["b"]);
		
		if (empty($lastname)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($firstname)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($born)) { $errored = 1; $txterror .= 'Error... '; }
		if (!is_numeric($gender) || $gender == 0) { $errored = 1; $txterror .= 'Error... '; }
		
	}

	if ($action == 2)	{
		$aboutme = '';
		if (isset($_POST["am"]) && !empty($_POST["am"])) $aboutme = $this->db1->e(htmlspecialchars($_POST["am"]));
		if (empty($aboutme)) { $errored = 1; $txterror .= 'Error... '; }
	}

	if ($action == 3)	{
		$loadedimage = '';
		$didchanges = 0;
		if (isset($_POST["dch"]) && !empty($_POST["dch"])) $didchanges = $this->db1->e($_POST["dch"]);
		if (isset($_POST["limg"]) && !empty($_POST["limg"])) $loadedimage = $this->db1->e($_POST["limg"]);
		if ($didchanges == 0) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($loadedimage)) { $errored = 1; $txterror .= 'Error... '; }
	}

	if ($action == 4)	{
		$currentpass = $newpass = '';
		if (isset($_POST["cp"]) && !empty($_POST["cp"])) $currentpass = $this->db1->e($_POST["cp"]);
		if (isset($_POST["np"]) && !empty($_POST["np"])) $newpass = $this->db1->e($_POST["np"]);
		if (empty($currentpass)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($newpass)) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	if ($action == 5)	{
		$country = $city = '';
		$region = 0;
		if (isset($_POST["cc"]) && !empty($_POST["cc"])) $country = $this->db1->e($_POST["cc"]);
		if (isset($_POST["c"]) && !empty($_POST["c"])) $city = $this->db1->e(htmlspecialchars($_POST["c"]));
		if (isset($_POST["r"]) && !empty($_POST["r"])) $region = $this->db1->e($_POST["r"]);
		if (empty($country)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($city)) { $errored = 1; $txterror .= 'Error... '; }
		if ($region == 0) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	if ($action == 6)	{ 
		$privacy = -1;
		if (isset($_POST["p"])) $privacy = $this->db1->e($_POST["p"]);
		if ($privacy>2 && $privacy<0) { $errored = 1; $txterror .= 'Error. '; }
	}
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		
		// Update personal data
		if ($action == 1) {	
			$this->db1->query("UPDATE users SET firstname='".$firstname."', lastname='".$lastname."', gender=".$gender.", born=".strtotime($born)." WHERE iduser=".$this->user->id.' LIMIT 1');
			$txtreturn = $this->lang('dashboard_mi_pi_form_ok');
			echo('1: '.$txtreturn);
			return;
		}

		if ($action == 2) {	
			$this->db1->query('UPDATE users SET aboutme="'.$aboutme.'" WHERE iduser='.$this->user->id.' LIMIT 1');
			$txtreturn = $this->lang('dashboard_mi_am_form_ok');
			echo('1: '.$txtreturn);
			return;
		}

		if ($action == 3) {	 
			if ($didchanges == 1) {
				$ext = explode(".",$loadedimage);
				$finalphoto = $this->user->info->code.'.'.$ext[count($ext)-1];
				
				$this->db1->query("UPDATE users SET avatar='".$finalphoto."' WHERE iduser=".$this->user->id.' LIMIT 1');
				
				$raiz = '../';
				$tmppath = $C->FOLDER_TMP;
				$avatarfolder = $C->FOLDER_AVATAR;
								
				// move photo file
				if (file_exists($raiz.$tmppath.$loadedimage)) {
					$mythumb = new thumb();
		
					$mythumb->loadImage($raiz.$tmppath.$loadedimage);
					$mythumb->resize($C->widthAvatar0,'width');
					$mythumb->save($raiz.$avatarfolder.$finalphoto);
					
					$mythumb->loadImage($raiz.$tmppath.$loadedimage);
					$mythumb->crop($C->widthAvatar1, $C->heightAvatar1);
					$mythumb->save($raiz.$avatarfolder.'min1/'.$finalphoto);
		
					$mythumb->loadImage($raiz.$tmppath.$loadedimage);
					$mythumb->crop($C->widthAvatar2, $C->heightAvatar2);
					$mythumb->save($raiz.$avatarfolder.'min2/'.$finalphoto);

					$mythumb->loadImage($raiz.$tmppath.$loadedimage);
					$mythumb->crop($C->widthAvatar3, $C->heightAvatar3);
					$mythumb->save($raiz.$avatarfolder.'min3/'.$finalphoto);

					unlink($raiz.$tmppath.$loadedimage);
			
					$txtreturn = $this->lang('dashboard_mi_mav_form_ok');
					echo("1: ".$txtreturn);
					return;
				} else {
					$txtreturn=$this->lang('dashboard_mi_mav_form_msg9');
					echo("0: ".$txtreturn);
					return;
				}		
			} else {
				$txtreturn = $this->leng('dashboard_1_mi_form_mi_msg5');
				echo("0: ".$txtreturn);
				return;
			}
		}
		
		if ($action == 4) {			
			$saltdb = $this->user->info->salt;
			$passdb = $this->user->info->password;
			
			$enteredpass = hash('sha512', $saltdb.$currentpass);
			
			if ($passdb == $enteredpass) {
				$salt = md5(uniqid(rand(),true));
				$hash = hash('sha512', $salt.$newpass);
				$this->db1->query("UPDATE users SET password='".$hash."', salt='".$salt."' WHERE iduser=".$this->user->id." LIMIT 1");
				$txtreturn = $this->lang('dashboard_mi_ia_form_ok');
				echo('1: '.$txtreturn);
				return;
			} else {
				$txtreturn = $this->lang('dashboard_mi_ia_form_msg5');
				echo('0: '.$txtreturn);
				return;
			}	
		}
		
		if ($action == 5) {
			$this->db1->query("UPDATE users SET codecountry='".$country."', idregion=".$region.", city='".$city."' WHERE iduser=".$this->user->id.' LIMIT 1');
			$txtreturn = $this->lang('dashboard_mi_lo_form_ok');
			echo('1: '.$txtreturn);
			return;
		}
		
		if ($action == 6) {
			$this->db1->query("UPDATE users SET privacy=".$privacy." WHERE iduser=".$this->user->id.' LIMIT 1');
			$txtreturn = $this->lang('dashboard_mi_pr_form_ok');
			echo('1: '.$txtreturn);
			return;
		}
	
	}
?>