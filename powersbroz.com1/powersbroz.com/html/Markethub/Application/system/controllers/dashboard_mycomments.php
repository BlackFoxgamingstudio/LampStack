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
	

	$D->totalcomments = $this->db2->fetch_field('SELECT count(idcomment) FROM comments WHERE iduser='.$this->user->id);
	
	$r = $this->db2->query('SELECT items.imageitem, users.username, items.code as pcode, items.title, comments.comment, comments.whendate FROM comments, items, users WHERE comments.iditem=items.iditem AND users.iduser=items.iduser AND comments.iduser='.$this->user->id.' ORDER BY comments.whendate DESC LIMIT 0,'.$C->NUM_COMMENTS_DASH_PAGE);

	$D->numcomments = $this->db2->num_rows();

	$D->htmlComments = '';
	ob_start();
	
	while( $obj = $this->db2->fetch_object($r) ) {
		$D->g = $obj;
		$D->g->title = stripslashes($D->g->title);
		$D->g->comment = stripslashes($D->g->comment);
		$this->load_template('__dashboard-one-comment.php');
	}
	
	$D->htmlComments = ob_get_contents();
	ob_end_clean();
	
	unset($r, $obj, $D->g);
	
	/*************************************************************************/

	$D->showaccessdirect = TRUE;
	$D->optionactive = 5;
	$this->load_template('dashboard-mycomments.php');
?>