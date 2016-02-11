<?php

class item
{
	public $id;
	public $error = FALSE;
	
	public function __construct($code, $mini = FALSE)
	{
		$this->db1 = & $GLOBALS['db1'];
		$this->db2 = & $GLOBALS['db2'];
		
		$code = $this->db2->e($code);
		
		if (strlen($code)!=11) {
			$this->error = TRUE;
			return;				
		}
		
		if ($mini == FALSE) {
		
			$r	= $this->db2->query('SELECT items.*, albums.name as nalbum, albums.code as calbum FROM items, albums WHERE items.idalbum=albums.idalbum AND items.code="'.$code.'" LIMIT 1', FALSE);
			
			if( ! $o = $this->db2->fetch_object($r) ) {
				$this->error = TRUE;
				return;
			}
			
			$this->id = $o->iditem;
			$this->idalbum = $o->idalbum;
			$this->code = $o->code;
			$this->iduser = $o->iduser;
			$this->title = stripslashes($o->title);
			$this->description = stripslashes($o->description);
			$this->imageitem = $o->imageitem;
			$this->datecreation = $o->datecreation;
			$this->numviews = $o->numviews;
			$this->numcomments = $o->numcomments;
			$this->numlikes = $o->numlikes;
			$this->numcensors = $o->numcensors;
			$this->namealbum = stripslashes($o->nalbum);
			$this->codealbum = $o->calbum;
			$this->typeitem = $o->typeitem;
			$this->codvideo = $o->codvideo;
			
		} else {
			
			$r	= $this->db2->query('SELECT iditem, imageitem, iduser, typeitem, idalbum FROM items WHERE code="'.$code.'" LIMIT 1', FALSE);
			if( ! $o = $this->db2->fetch_object($r) ) {
				$this->error = TRUE;
				return;
			}
			
			$this->id = $o->iditem;
			$this->idalbum = $o->idalbum;
			$this->iduser = $o->iduser;
			$this->imageitem = $o->imageitem;	
			$this->typeitem = $o->typeitem;		
		}
		return TRUE;
	}
	
	private function _deleteComments()
	{
		// Here we remove the comments on this photo
		$allcomments = $this->db2->fetch_all('SELECT iduser FROM comments WHERE iditem='.$this->id);
		
		foreach ($allcomments as $onecomments) {
			$this->db2->query('UPDATE users SET num_comments=num_comments-1 WHERE iduser='.$onecomments->iduser.' LIMIT 1');
		}
		$this->db2->query('DELETE FROM comments WHERE iditem='.$this->id);
		/************************************************/		
	}
	
	private function _deleteLikes()
	{
		// Here we remove the favorites this photo
		$alllikes = $this->db2->fetch_all('SELECT iduser FROM likes WHERE iditem='.$this->id.' AND typeitem=2');
		foreach($alllikes as $onelike) {
			$this->db2->query('UPDATE users SET num_likes=num_likes-1 WHERE iduser='.$onelike->iduser.' LIMIT 1');
		}
		$this->db2->query('DELETE FROM likes WHERE iditem='.$this->id.' AND typeitem=2');
		/************************************************/	
	}
	
	private function _deleteCensors()
	{
		$this->db2->query('DELETE FROM censored WHERE iditem='.$this->id.' AND typeitem=2');
		/************************************************/	
	}
	
	private function _deleteActivities()
	{
		// Here we remove the "activities" made in this photo
		$this->db2->query('DELETE FROM activities WHERE iditem='.$this->id.' AND typeitem=1');
		/************************************************/
	}
	
	private function _deleteNotifications()
	{
		// Here we remove the "notifications" made in this photo
		$this->db2->query('DELETE FROM notifications WHERE notif_object_id='.$this->id.' AND notif_object_type=1');
		/************************************************/
	}
	
	private function _deletePhotoinFolder()
	{
		global $C;
		// Now remove the file
		$raiz = '../';
		$folderphoto = $C->FOLDER_PHOTOS;
		if (file_exists($raiz.$folderphoto.$this->imageitem)) unlink($raiz.$folderphoto.$this->imageitem);
		$folderphoto = $C->FOLDER_PHOTOS.'/min1/';
		if (file_exists($raiz.$folderphoto.$this->imageitem)) unlink($raiz.$folderphoto.$this->imageitem);
		$folderphoto = $C->FOLDER_PHOTOS.'/min2/';
		if (file_exists($raiz.$folderphoto.$this->imageitem)) unlink($raiz.$folderphoto.$this->imageitem);
		$folderphoto = $C->FOLDER_PHOTOS.'/min3/';
		if (file_exists($raiz.$folderphoto.$this->imageitem)) unlink($raiz.$folderphoto.$this->imageitem);
	}

	public function deleteItem()
	{
		$this->db2->query('DELETE FROM items WHERE iditem='.$this->id.' AND iduser='.$this->iduser.' LIMIT 1');
		$this->db2->query('UPDATE albums SET numitems=numitems-1 WHERE idalbum='.$this->idalbum.' AND iduser='.$this->iduser.' LIMIT 1');
		$this->db2->query('UPDATE users SET num_items=num_items-1 WHERE iduser='.$this->iduser.' LIMIT 1');
		
		$this->_deleteComments();
		$this->_deleteLikes();
		$this->_deleteCensors();
		$this->_deleteActivities();
		$this->_deleteNotifications();
		$this->_deletePhotoinFolder();
	}
	
	public function deleteAccesoriesItem()
	{
		$this->_deleteComments();
		$this->_deleteLikes();
		$this->_deleteCensors();
		$this->_deleteActivities();
		$this->_deleteNotifications();
		$this->_deletePhotoinFolder();
	}

	public function increaseVisits($quantity=1)
	{
		$cookiePhoto = (isset($_COOKIE['item_'.$this->id])?$_COOKIE['item_'.$this->id]:FALSE);
		if (!$cookiePhoto) {
			$this->db2->query('UPDATE items SET numviews=numviews+'.$quantity.' WHERE iditem='.$this->id.' LIMIT 1');
			setcookie('photo_'.$this->id,1, time() + 3600);
		}
	}
	
	public function getNumCensor()
	{
		return $this->numcensors;
	}
	
	public function restoreItemFromCensured() {
		$this->db2->query('UPDATE items SET numcensors=0 WHERE iditem='.$this->id.' LIMIT 1');
		$this->db2->query("DELETE FROM censored WHERE typeitem=2 AND iditem=".$this->id);
	}
	
	public function likeOfUser($iduser)
	{
		$r	= $this->db2->fetch_field('SELECT idlike FROM likes WHERE iduser='.$iduser.' AND iditem='.$this->id.' AND typeitem=2 LIMIT 1');
		return $r;
	}
	
	public function censoredbyUser($iduser)
	{
		$r	= $this->db2->fetch_field('SELECT idcensor FROM censored WHERE iduser='.$iduser.' AND iditem='.$this->id.' AND typeitem=2 LIMIT 1');
		return $r;
	}
	
	public function getComments()
	{
		$r = $this->db2->fetch_all('SELECT idcomment, comments.whendate, comment, comments.iduser, username, firstname, lastname, avatar FROM comments, users WHERE users.iduser=comments.iduser AND iditem='.$this->id.' ORDER BY comments.whendate ASC');
		return $r;
	}
	
}
?>