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

		//We load the likes
		$totalitems = $this->db2->fetch_field('SELECT count(idlike) FROM likes WHERE iduser='.$this->user->id);
		
		$r = $this->db2->query('SELECT idlike, items.*, users.username FROM likes, items, users WHERE likes.typeitem=2 AND likes.iduser='.$this->user->id.' AND likes.iditem=items.iditem AND items.iduser=users.iduser ORDER BY datewhen DESC LIMIT '.$numitems.','.$C->NUM_FAVORITES_PAGE);
	
		$numitemsnow = $this->db2->num_rows();

		$D->htmlResults = ''; 
		ob_start();
		while( $obj = $db2->fetch_object($r) ) {
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
			$this->load_template('__dashboard-onelike.php');
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