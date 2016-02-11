<?php

	/*************************************************************************/
	// needed before proceeding
	require_once('_all-required-language.php');
	
	/*************************************************************************/

	$this->load_langfile('global/global.php');

	/*************************************************************************/
	
	// needed before proceeding
	require_once('_all-required-page.php');
	
	/*************************************************************************/
	
	$codepage = 'about';

	if ($this->param('p')) $codepage = $this->param('p');
	
	$D->texthtml = '';

	switch($codepage) {
		case 'about':
			$D->opcInfo = 1;
			$D->txtTitle = $this->lang('global_pagesmenu_opc_about');
			$D->texthtml = $this->db2->fetch_field('SELECT texthtml FROM pages WHERE code="'.$codepage.'" LIMIT 1');
			break;
			
		case 'privacy':
			$D->opcInfo = 2;
			$D->txtTitle = $this->lang('global_pagesmenu_opc_privacy');
			$D->texthtml = $this->db2->fetch_field('SELECT texthtml FROM pages WHERE code="'.$codepage.'" LIMIT 1');
			break;
			
		case 'termsofuse':
			$D->opcInfo = 3;
			$D->txtTitle = $this->lang('global_pagesmenu_opc_termsofuse');
			$D->texthtml = $this->db2->fetch_field('SELECT texthtml FROM pages WHERE code="'.$codepage.'" LIMIT 1');
			break;
			
		case 'disclaimer':
			$D->opcInfo = 4;
			$D->txtTitle = $this->lang('global_pagesmenu_opc_disclaimer');
			$D->texthtml = $this->db2->fetch_field('SELECT texthtml FROM pages WHERE code="'.$codepage.'" LIMIT 1');
			break;
			
		case 'contact':
			$D->opcInfo = 5;
			$D->txtTitle = $this->lang('global_pagesmenu_opc_contact');
			$D->texthtml = $this->db2->fetch_field('SELECT texthtml FROM pages WHERE code="'.$codepage.'" LIMIT 1');
			break;
			
		default:
			$D->opcInfo = 1;
			$D->txtTitle = $this->lang('global_pagesmenu_opc_about');
			$D->texthtml = $this->db2->fetch_field('SELECT texthtml FROM pages WHERE code="'.$codepage.'" LIMIT 1');
	}
	
	$D->texthtml = stripslashes($D->texthtml);


	/*************************************************************************/

	$D->page_title = $D->txtTitle.' - '.$C->SITE_TITLE;
		
	$this->load_template('page.php');
?>