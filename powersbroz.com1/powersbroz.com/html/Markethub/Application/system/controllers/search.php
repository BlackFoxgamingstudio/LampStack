<?php

	/*************************************************************************/
	// needed before proceeding
	require_once('_all-required-language.php');
	
	/*************************************************************************/

	$this->load_langfile('global/global.php');

	/*************************************************************************/
	
	// needed before proceeding
	require_once('_all-required-directory.php');
	
	/*************************************************************************/

	$D->errorsearch = 0;

	$D->query = '';
	if ($this->param('q')) $D->query = trim($this->param('q'));

	if (strlen($D->query)>2) {

		$D->ITEMS_PER_PAGE = $C->NUM_SEARCH_PAGE;
	
		$D->pageCurrent = 1;
		
		if ($this->param('p')) $D->pageCurrent = $this->param('p');

		$D->totalresult = $this->db2->fetch_field("SELECT count(iditem) FROM items WHERE title like '%".$this->db2->e($D->query)."%'");
		
		$D->start = ($D->pageCurrent-1) * $D->ITEMS_PER_PAGE;
		
		/**** Pagination ****/
		
		$D->totalPag = ceil($D->totalresult/$D->ITEMS_PER_PAGE);
		
		//if ($D->totalPag<$D->pageCurrent) $this->redirect($C->SITE_URL.'search/q:'.$D->query);
		
		$D->pagVisibles = 4;
		
		if ($D->totalPag > (2 * $D->pagVisibles) + 1) {
		
			$D->firstPage = $D->pageCurrent - $D->pagVisibles;
			if ($D->firstPage < 1) $D->firstPage = 1;
			
			$D->lastPage = $D->firstPage + (2 * $D->pagVisibles);
			if ($D->lastPage > $D->totalPag) $D->lastPage = $D->totalPag;
			
			if ($D->lastPage - $D->firstPage < (2 * $D->pagVisibles) + 1) $D->firstPage = $D->lastPage - (2 * $D->pagVisibles);
			
		} else {
			
			$D->firstPage = 1;
			$D->lastPage = $D->totalPag;
		}
		
		/********************/
		
		$r = $this->db2->query("SELECT items.code, title, imageitem, username, numcomments, numlikes, numviews, typeitem FROM items, users WHERE items.iduser=users.iduser AND title like '%".$this->db2->e($D->query)."%' ORDER BY title ASC LIMIT ".$D->start.",".$D->ITEMS_PER_PAGE);
	
		$D->numphotos = $this->db2->num_rows();
	
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
			$this->load_template('__search-one-item.php');
		}
		
		$D->htmlPhotos = ob_get_contents();
		ob_end_clean();
		
		unset($r, $obj);
		
	} else {
		$D->errorsearch = 1;
		$D->msgerror = $this->lang('global_search_qshort');
	}

	/*************************************************************************/

	$D->page_title = $this->lang('global_search_title').' - '.$C->SITE_TITLE;
		
	$this->load_template('search.php');
?>