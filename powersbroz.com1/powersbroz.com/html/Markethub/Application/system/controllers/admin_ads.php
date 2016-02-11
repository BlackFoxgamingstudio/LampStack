<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('login');
	}
	
	$db2->query('SELECT 1 FROM users WHERE iduser="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
	if( 0 == $db2->num_rows() ) {
		$this->redirect('dashboard');
	}
	
	
	/*************************************************************************/
	// needed before proceeding
	require_once('_all-required-language.php');
	
	$this->load_langfile('global/global.php');	
	$this->load_langfile('inside/admin.php');

	/*************************************************************************/
	
	
	require_once('_all-required-dashboard-admin.php');
	

	/*************************************************************************/
	
	$r = $this->db2->query('SELECT * FROM ads');
	
	while( $obj = $this->db2->fetch_object($r) ) {
		if ($obj->code == 'adsProf01') $D->adsp1 = $obj->adsource;
		if ($obj->code == 'adsProf02') $D->adsp2 = $obj->adsource;
		if ($obj->code == 'adsDash01') $D->adsd1 = $obj->adsource;
		if ($obj->code == 'adsDash02') $D->adsd2 = $obj->adsource;
	}
	
	/*************************************************************************/
	
	$D->optionactive_admin = 6;
	$this->load_template('admin_ads.php');

?>