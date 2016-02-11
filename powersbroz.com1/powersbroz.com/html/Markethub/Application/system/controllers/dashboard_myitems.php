<?php
	if( !$this->user->is_logged ) {
		$this->redirect('login');
	}
	
	
	/*************************************************************************/
	// needed before proceeding
	require_once('_all-required-language.php');
	
	$this->load_langfile('global/global.php');	
	$this->load_langfile('inside/dashboard.php');

	/*************************************************************************/
	
	
	require_once('_all-required-dashboard.php');
	

	/*************************************************************************/

	$D->tabactive = 1;
	
	if ($this->param('tab')) $D->tabactive = intval($this->param('tab'));
	
	// Only parameters allowed 1 to 3
	if ($D->tabactive>4 || $D->tabactive<1) $D->tabactive = 1;
	
	switch ($D->tabactive) {
		case 1:
			require_once('_all-required-dashboard-myitems-tab1.php');
			break;
		case 2:
			require_once('_all-required-dashboard-myitems-tab2.php');
			break;
		case 3:
			require_once('_all-required-dashboard-myitems-tab3.php');
			break;
		case 4:
			require_once('_all-required-dashboard-myitems-tab4.php');
			break;
		default:
			require_once('_all-required-dashboard-myitems-tab1.php');					
	}
	
	/*************************************************************************/

	$D->showaccessdirect = FALSE;
	$D->optionactive = 3;
	$this->load_template('dashboard-myitems.php');
?>