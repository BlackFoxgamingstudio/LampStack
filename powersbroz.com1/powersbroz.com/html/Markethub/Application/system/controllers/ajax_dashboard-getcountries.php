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
	
	$codselect='';

	if (isset($_POST["cod"]) && $_POST["cod"]!='') $codselect = $this->db1->e($_POST["cod"]);

	$r = $this->db1->query("SELECT code, country FROM country ORDER BY country ASC");
	$txtcountries='<option value="0" select>'.$this->lang('dashboard_mi_lo_form_msgcombocountry').'</option>';
	while ($row=$this->db1->fetch_object($r)) {
		if ($row->code==$codselect) $txtcountries.='<option value="'.$row->code.'" selected="selected">'.$row->country.'</option>';
		else $txtcountries.='<option value="'.$row->code.'">'.$row->country.'</option>';
	}
	
	echo("1: ".$txtcountries);

?>