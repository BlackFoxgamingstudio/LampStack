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
	
	$D->query = '';
	if ($this->param('q')) $D->query = trim($this->param('q'));
	
	if (!empty($D->query)) $D->qsql = " AND (username like '%".$D->query."%' OR firstname like '%".$D->query."%' OR lastname LIKE '%".$D->query."%') ";
	else $D->qsql = "";

	$D->USER_PER_PAGE = $C->NUM_USERS_DIRECTORY_PAGE;

	$D->pageCurrent = 1;
	
	if ($this->param('p')) $D->pageCurrent = $this->param('p');

	$D->totalusers = $this->db2->fetch_field('SELECT count(iduser) FROM users WHERE active=1 '.$D->qsql);
	
	$D->start = ($D->pageCurrent-1) * $D->USER_PER_PAGE;
	
	/**** Pagination ****/
	
	$D->totalPag = ceil($D->totalusers/$D->USER_PER_PAGE);
	
	if ($D->totalPag<$D->pageCurrent) $this->redirect($C->SITE_URL.'directory/people');
	
	$D->pagVisibles = 2;
	
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
	
	$r = $this->db2->query('SELECT iduser, code, firstname, lastname, username, avatar, num_items, validated FROM users WHERE active=1 '.$D->qsql.' ORDER BY username ASC LIMIT '.$D->start.','.$D->USER_PER_PAGE);

	$D->numusers = $this->db2->num_rows();

	$D->htmlUsers = '';
	ob_start();

	while( $obj = $this->db2->fetch_object($r) ) {
		$D->isThisUserVerified = $obj->validated==1?TRUE:FALSE;
		$D->f_name = (empty($obj->firstname) || empty($obj->lastname))?stripslashes($obj->username):(stripslashes($obj->firstname).' '.stripslashes($obj->lastname));
		$D->f_avatar = $obj->avatar;
		$D->f_numitems = $obj->num_items;
		$D->f_username = $obj->username;
		$this->load_template('__directory-one-user.php');
	}
	
	$D->htmlUsers = ob_get_contents();
	ob_end_clean();
	
	unset($r, $obj);

	/*************************************************************************/

	$D->page_title = $this->lang('global_txt_directory').' - '.$C->SITE_TITLE;
		
	$this->load_template('directory_people.php');
?>