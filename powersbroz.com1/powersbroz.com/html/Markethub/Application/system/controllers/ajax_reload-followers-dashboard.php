<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	$this->load_langfile('inside/dashboard.php');
	
	$errored = 0;
	$txterror = '';

	$numitems = $iduser = 0;
	
	if (isset($_POST["ni"]) && !empty($_POST["ni"])) $numitems = $this->db1->e($_POST["ni"]);
	if (isset($_POST["idu"]) && !empty($_POST["idu"])) $iduser = $this->db1->e($_POST["idu"]);
	
	if (!is_numeric($numitems) || $numitems <= 0) { $errored = 1; $txterror .= 'Error... '; }
	if (!is_numeric($iduser) || $iduser <= 0) { $errored = 1; $txterror .= 'Error... '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {


		
		$totalitems = $this->db2->fetch_field('SELECT count(iduser) FROM users, relations WHERE iduser=subscriber AND leader='.$this->user->id);
		
		$r = $this->db2->query('SELECT iduser, code, firstname, lastname, username, avatar, num_items, num_followers, num_following, validated FROM users, relations WHERE iduser=subscriber AND leader='.$this->user->id.' ORDER BY rltdate DESC LIMIT '.$numitems.','.$C->NUM_FOLLOWERS_PAGE);

		$numitemsnow = $this->db2->num_rows();
		


		ob_start();
		while( $obj = $db2->fetch_object($r) ) {
			$D->isThisUserVerified = $obj->validated==1?TRUE:FALSE;
			$D->f_name = (empty($obj->firstname) || empty($obj->lastname))?stripslashes($obj->username):(stripslashes($obj->firstname).' '.stripslashes($obj->lastname));
			$D->f_avatar = $obj->avatar;
			$D->f_numitems = $obj->num_items;
			$D->f_username = $obj->username;
			$this->load_template('__dashboard-one-follower.php');
		}
		$htmlResults = ob_get_contents();
		ob_end_clean();
		
		unset($r, $obj);
		
		if ($totalitems <= $numitemsnow + $numitems) {
			echo("2: ".$htmlResults);
			return;
		} else {
			echo("1: ".$htmlResults);
			return;	
		}
			
	}


?>