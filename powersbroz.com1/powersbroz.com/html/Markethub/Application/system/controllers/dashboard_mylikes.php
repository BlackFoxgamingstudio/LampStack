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
	
	
	//We load the likes
	$D->totallikes = $this->db2->fetch_field('SELECT count(idlike) FROM likes WHERE iduser='.$this->user->id);
	
	$r = $this->db2->query('SELECT idlike, items.*, users.username FROM likes, items, users WHERE likes.typeitem=2 AND likes.iduser='.$this->user->id.' AND likes.iditem=items.iditem AND items.iduser=users.iduser ORDER BY datewhen DESC LIMIT 0,'.$C->NUM_FAVORITES_PAGE);

	$D->numlikes = $this->db2->num_rows();

	$D->htmlLikes = ''; 
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
		$this->load_template('__dashboard-onelike.php');
	}
	
	$D->htmlLikes = ob_get_contents();
	ob_end_clean();
	
	unset($r, $obj);	

	/*************************************************************************/
	
	$D->showaccessdirect = TRUE;
	$D->optionactive = 4;
	$this->load_template('dashboard-mylikes.php');
?>