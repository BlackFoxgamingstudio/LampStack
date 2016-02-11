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

	$codecountry='';
	$region=0;

	if (isset($_POST["cod"]) && $_POST["cod"] != '') $codecountry = $this->db1->e($_POST["cod"]);
	if (isset($_POST["r"]) && $_POST["r"] != '') $region = $this->db1->e($_POST["r"]);

	$r = $this->db1->query("SELECT idregion, region FROM country_region WHERE countrycode='".$codecountry."' ORDER BY region ASC");
	$txtregions='<option value="0" select>'.$this->lang('dashboard_mi_lo_form_msgcomboregion').'</option>';
	
	while ($row=$this->db1->fetch_object($r)) {
		if ($row->idregion == $region) $txtregions .= '<option value="'.$row->idregion.'" selected="selected">'.$row->region.'</option>';
		else $txtregions .= '<option value="'.$row->idregion.'">'.$row->region.'</option>';
	}
	
	echo("1: ".$txtregions);

?>