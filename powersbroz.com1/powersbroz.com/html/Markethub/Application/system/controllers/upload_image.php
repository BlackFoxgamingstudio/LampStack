<?php
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('inside/dashboard.php');

	// We are here only if you're logged in
	if (!$this->user->is_logged) {
		echo('0: '.$this->lang('dashboard_no_session'));
		die();
	}
	
	$errored = 0;
	$txterror = '';

	// captured the folder where the image will be uploaded
	if ($this->param('fold')) $fold = str_replace('-','/',$this->db1->escape($this->param('fold')));

	// we establish the folder to upload - in the temporal
	$uploaddir = '../'.$fold;
	
	$prefj='';
	if ($this->param('prefj')) $prefj = $this->db1->escape($this->param('prefj'));
	
	if ($prefj == '') { $errored = 1; $txterror .= 'error. '; }

if ($errored == 1) {
	echo("0: ".$txterror);
	return;
} else {
	$mfilename = $this->user->id.$prefj;
	$uploadfile = $uploaddir . basename($mfilename);
	$sizeimage = $_FILES['uploaded_image']['size'];
	$loadedtype = $_FILES['uploaded_image']['type'];
	
	if ($loadedtype=="image/jpeg" || $loadedtype=="image/gif" || $loadedtype=="image/png") {
		switch ($loadedtype) {
			case "image/jpeg":
				$uploadfile.='.jpg';
				$mfilename.='.jpg';
				break;
			case "image/gif":
				$uploadfile.='.gif';
				$mfilename.='.gif';							
				break;
			case "image/png":
				$uploadfile.='.png';
				$mfilename.='.png';							
				break;	
		}
		
		if ($sizeimage<$C->SIZE_IMAGEN_AVATAR) {
			 // move the file to the specified folder
			 if (move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $uploadfile)) {
				echo('1: '.$mfilename);
				return;
			 } else {
				echo('0: '.$this->lang('dashboard_mi_mav_form_msg7'));
				return;
			 }
		} else {
			echo('0: '.$this->lang('dashboard_mi_mav_form_msg6'));
			return;
		} 
	} else { 
		echo('0: '.$this->lang('dashboard_mi_mav_form_msg8'));
		return;
	}	
	echo("1: Ok");
	return;
}
?>