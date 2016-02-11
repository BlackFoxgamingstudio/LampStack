<?php
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('global/global.php');
	
	$errored = 0;
	$txterror = '';
	
	$squery = '';

	if (isset($_POST["q"]) && !empty($_POST["q"])) $squery = $this->db1->e($_POST["q"]);

	if (empty($squery) || strlen($squery)<=2) { $errored = 1; $txterror .= '<div class="centered mrg20B txtsize01">'.$this->lang('global_search_qshort').'</div>'; }

	if ($errored == 1) {
		$D->divclose = '<div class="areaclose"><a href="#" onclick="hideSearchTop(); return false;" title="'.$this->lang('global_txt_close_notification').'"><img src="'.$C->SITE_URL.'themes/default/imgs/icocloseinv.png"></a></div>';
		$cadreturn = '<div id="contentSearch"><div id="resultsSearch">' . $D->divclose . $txterror . '</div></div>';
		echo($cadreturn);
	} else {

		$totalresult = $this->db2->fetch_field("SELECT count(iditem) FROM items, users WHERE items.iduser=users.iduser AND title like '%".$squery."%'");

		$r = $this->db2->query("SELECT items.code, title, imageitem, username, numcomments, numlikes, numviews, typeitem FROM items, users WHERE items.iduser=users.iduser AND title like '%".$squery."%' ORDER BY iditem DESC LIMIT 0,".$C->NUM_RESULT_SEARCH_TOP);
		
		$itemsnow = $this->db2->num_rows();
	
		$D->htmlPhotos = '';
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
			$this->load_template('__search-top-one-item.php');
		}
		
		$D->htmlPhotos = ob_get_contents();
		ob_end_clean();
		
		unset($r, $obj);
		
		if (empty($D->htmlPhotos)) {
			$D->htmlPhotos = '<div class="centered pdn20B txtsize01">'.$this->lang('global_search_noresult').'</div>';
			$D->more = '';
		} else {
			$D->more = '<div class="centered pdn10B"><span class="link"><a href="'.$C->SITE_URL.'search/q:'.$squery.'">'.$this->lang('global_search_moreresults').'</a></div>';		
		}
		$D->divclose = '<div class="areaclose"><a href="#" onclick="hideSearchTop(); return false;" title="'.$this->lang('global_search_msgclose').'"><img src="'.$C->SITE_URL.'themes/default/imgs/icocloseinv.png"></a></div>';
		
		$cadreturn = '<div id="contentSearch">';
		$cadreturn .= '<div id="resultsSearch">';
		$cadreturn .= $D->divclose;
		$cadreturn .= $D->htmlPhotos;
		if ($totalresult>$itemsnow) $cadreturn .= $D->more;
		$cadreturn .= '</div>';
		$cadreturn .= '</div>';
		
		echo $cadreturn;
	}
?>