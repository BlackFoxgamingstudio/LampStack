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
	
	if (!is_numeric($numitems) || $numitems <= 0) { $errored = 1; $txterror .= 'Error... '; }
	if (!is_numeric($iduser) || $iduser <= 0) { $errored = 1; $txterror .= 'Error... '; }
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {

		
		$totalitems = $this->db2->fetch_field('SELECT count(idalbum) FROM albums WHERE iduser='.$iduser);
		
		$r = $this->db2->query('SELECT idalbum, albums.code, name, numitems, description, username FROM albums, users WHERE albums.iduser=users.iduser AND albums.iduser='.$iduser.' ORDER BY idalbum DESC LIMIT '.$numitems.','.$C->NUM_ALBUMS_PAGE);

		$numitemsnow = $this->db2->num_rows();
		
		$htmlResults = '';
		ob_start();
		
		while( $obj = $this->db2->fetch_object($r) ) {
			$D->namealbum = stripslashes($obj->name);
			$D->description = stripslashes($obj->description);
			$D->codealbum = $obj->code;
			$D->numitems = $obj->numitems;
			$D->miniphotos = $this->network->getPhotosAlbum($obj->idalbum,6);
			$D->usernameProfile = $obj->username;
			$this->load_template('__profile-onealbum.php');
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