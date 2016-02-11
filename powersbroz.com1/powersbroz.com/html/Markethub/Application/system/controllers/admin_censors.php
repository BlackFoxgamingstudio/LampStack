<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('home');
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
	

	$D->totalphotos = $this->db2->fetch_field('SELECT count(iditem) FROM items WHERE numcensors>0');
		
	$D->npage = 1;
		
	if ($this->param('p')) $D->npage = $this->param('p');
	
	$D->qperpage = 20;
	
	$D->start = ($D->npage - 1) * $D->qperpage;
	
	$D->totalpages = ceil($D->totalphotos / $D->qperpage);
	
	$r = $this->db2->fetch_all('SELECT * FROM items WHERE numcensors>0 LIMIT '.$D->start.','.$D->qperpage);
	
	$D->htmlCensors = '';
	
	ob_start();
	
	foreach($r as $onePhoto) {
		$D->g = $onePhoto;
		$this->load_template('__admin-items-one-censor.php');
	}

	$D->htmlCensors = ob_get_contents();
	ob_end_clean();
	
	unset($r, $onePhoto);

	
	$D->optionactive_admin = 7;
	$this->load_template('admin_censors.php');

	
	/*************************************************************************/

?>