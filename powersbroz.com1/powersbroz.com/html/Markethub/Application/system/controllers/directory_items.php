<?php

	/*************************************************************************/
	// needed before proceeding
	require_once('_all-required-language.php');
	
	/*************************************************************************/

	$this->load_langfile('global/global.php');

	/*************************************************************************/
	
	// needed before proceeding
	require_once('_all-required-directory-items.php');
	
	/*************************************************************************/
	
	$D->typeitems = 0;
	if ($this->param('t')) $D->typeitems = $this->param('t');
	switch($D->typeitems) {
		case 0:
			$sqloptional1 = '';
			$sqloptional2 = '';
			break;	
		case 1:
			$sqloptional1 = ' WHERE typeitem=1 ';
			$sqloptional2 = ' AND typeitem=1 ';
			break;	
		case 2:
			$sqloptional1 = ' WHERE typeitem=2 ';
			$sqloptional2 = ' AND typeitem=2 ';
			break;	
		case 3:
			$sqloptional1 = ' WHERE typeitem=3 ';
			$sqloptional2 = ' AND typeitem=3 ';
			break;
		default:
			$sqloptional1 = '';
			break;
	}
	
	$D->ascendent = 0;
	if ($this->param('a')) $D->ascendent = $this->param('a');
	switch($D->ascendent) {
		case 0:
			$sqloptional3 = ' DESC ';
			break;	
		case 1:
			$sqloptional3 = ' ASC ';
			break;	
		default:
			$sqloptional3 = ' DESC';
			break;
	}
	
	$D->orderby = 0;
	if ($this->param('o')) $D->orderby = $this->param('o');
	switch($D->orderby) {
		case 0:
			$sqloptional4 = ' datecreation ';
			break;
		case 1:
			$sqloptional4 = ' numlikes ';
			break;
		case 2:
			$sqloptional4 = ' numviews ';
			break;
		default:
			$sqloptional1 = ' datecreation ';
			break;
	}



	$D->ITEMS_PER_PAGE = $C->NUM_PHOTOS_DIRECTORY_PAGE;

	$D->pageCurrent = 1;
	
	if ($this->param('p')) $D->pageCurrent = $this->param('p');

	$D->totalresult = $this->db2->fetch_field("SELECT count(iditem) FROM items".$sqloptional1);
	
	$D->start = ($D->pageCurrent-1) * $D->ITEMS_PER_PAGE;
	
	/**** Pagination ****/
	
	$D->totalPag = ceil($D->totalresult/$D->ITEMS_PER_PAGE);
	
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
	
	$r = $this->db2->query("SELECT items.code, title, imageitem, username, numcomments, numlikes, numviews, typeitem FROM items, users WHERE items.iduser=users.iduser".$sqloptional2." ORDER BY ".$sqloptional4.$sqloptional3." LIMIT ".$D->start.",".$D->ITEMS_PER_PAGE);

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
		$this->load_template('__directory-one-item.php');
	}
	
	$D->htmlPhotos = ob_get_contents();
	ob_end_clean();
	
	unset($r, $obj);


	/*************************************************************************/

	$D->page_title = $this->lang('global_directory_items_title').' - '.$C->SITE_TITLE;
		
	$this->load_template('directory_items.php');
?>