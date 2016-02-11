<?php 

	$D->codealbum = '';
	
	if ($this->param('a')) $D->codealbum = $this->param('a');
	
	if ($this->network->verifiedAlbum($D->codealbum, $this->user->id)) {
		// Information load photos that are in an album
		
		$D->cadurlalbum = '/a:'.$D->codealbum;
		
		$objalbum = new album($D->codealbum);
		
		$D->a_name = ($objalbum->name);
		$D->a_description = ($objalbum->description);
		
		$D->arrayPhotos = $objalbum->getItems();
		
		$D->htmlPhotos = '';
		
		ob_start();
		foreach($D->arrayPhotos as $onephoto) {
			$onephoto->title = stripslashes($onephoto->title);
			$onephoto->description = stripslashes($onephoto->description);
			$D->f = $onephoto;
			switch($onephoto->typeitem) {
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
			$this->load_template('__dashboard-one-item-folder.php');
		}
		$D->htmlPhotos	= ob_get_contents();
		ob_end_clean();
		
		unset($onephoto, $D->g);
		
		unset($objalbum);
		
		$D->filetoLoad = '_dashboard-myitems-folder-details.php';
		
	} else {

		$D->totalalbums = $this->db2->fetch_field('SELECT count(idalbum) FROM albums WHERE iduser='.$this->user->id);
		
		$r = $this->db2->query('SELECT * FROM albums WHERE iduser='.$this->user->id.' ORDER BY created DESC LIMIT 0,'.$C->NUM_ALBUMS_PAGE);
	
		$D->numAlbums = $this->db2->num_rows();
	
		$D->htmlAlbums = '';
		ob_start();
		
		while( $obj = $this->db2->fetch_object($r) ) {
			
			$D->a_name = stripslashes($obj->name);
			$D->a_description = stripslashes($obj->description);
			$D->a_code = $obj->code;
			$D->a_numitems = $obj->numitems; 
			$D->a_miniphotos = $this->network->getPhotosAlbum($obj->idalbum, 6);
			$this->load_template('__dashboard-one-folder.php');
			
		}
		
		$D->htmlAlbums = ob_get_contents();
		ob_end_clean();
		
		
		$D->filetoLoad = '_dashboard-myitems-folder-list.php';
	}
?>