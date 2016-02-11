<?php

class album
{
	public $id;
	public $error = FALSE;
	
	public function __construct($code)
	{
		$this->db1 = & $GLOBALS['db1'];
		$this->db2 = & $GLOBALS['db2'];
		
		$r	= $this->db2->query('SELECT * FROM albums WHERE code="'.$code.'" LIMIT 1', FALSE);
		if( ! $o = $this->db2->fetch_object($r) ) {
			$this->error = TRUE;
			return;
		}
		
		$this->id = $o->idalbum;
		$this->code = $o->code;
		$this->iduser = $o->iduser;
		$this->name = stripslashes($o->name);
		$this->description = stripslashes($o->description);
		$this->numitems = $o->numitems;
		$this->created = $o->created;
		$this->modified = $o->modified;
		return TRUE;
	}
	
	public function getItems()
	{
		$r = $this->db2->fetch_all('SELECT * FROM items WHERE idalbum='.$this->id.' ORDER BY iditem DESC');
		return $r;
	}
	
	public function deleteAlbum()
	{
		$itemsinfolder = $this->db2->fetch_all('SELECT code FROM items WHERE idalbum='.$this->id);
		foreach($itemsinfolder as $oneitem) {
			$itemin = new item($oneitem->code,TRUE);
			$itemin->deleteAccesoriesItem();
			unset($itemin);
		}
		
		$this->_deleteActivities();
		
		$this->db2->query('DELETE FROM items WHERE idalbum='.$this->id.' AND iduser='.$this->iduser);
		$this->db2->query('DELETE FROM albums WHERE idalbum='.$this->id.' AND iduser='.$this->iduser.' LIMIT 1');
		$this->db2->query('UPDATE users SET num_albums=num_albums-1, num_items=num_items-'.$this->numitems.' WHERE iduser='.$this->iduser.' LIMIT 1');
		unset($itemsinfolder, $oneitem);
	}
	
	private function _deleteActivities()
	{
		// Here we remove the "activities" made in this album
		$this->db2->query('DELETE FROM activities WHERE iditem='.$this->id.' AND typeitem=2');
		/************************************************/
	}
	
}

?>