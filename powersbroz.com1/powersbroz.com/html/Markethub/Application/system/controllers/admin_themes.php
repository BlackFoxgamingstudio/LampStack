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
	
	require_once( $C->INCPATH.'helpers/func-themes.php' );
	$D->listThemes	= array();
	foreach(get_available_themes() as $k=>$v) {
		$D->listThemes[$k]	= $v->name;
	}
	
	$D->themeDefault = $this->db2->fetch_field('SELECT value FROM settings WHERE word="THEME"');
	
	/*************************************************************************/

	$D->optionactive_admin = 4;
	$this->load_template('admin_themes.php');

?>