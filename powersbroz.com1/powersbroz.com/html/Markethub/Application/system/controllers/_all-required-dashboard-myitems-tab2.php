<?php 

	
	$D->numalbums = $this->user->info->num_albums;
	$D->codealbum = '';

	if ($this->param('a'))$D->codealbum = $this->param('a');

	if ($D->numalbums > 0) $D->allAlbums = $this->network->getAlbumsUser($this->user->id, TRUE);

	$D->filetoLoad = '_dashboard-myitems-addphoto.php';

?>