<?php 
// We check in which language we will work
if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];
$this->load_langfile('outside/login.php');

$errored = 0;
$txterror = '';

$un = $pw = '';

if (isset($_POST["un"]) && $_POST["un"] != '') $un = $this->db1->e($_POST["un"]);
if (isset($_POST["pw"]) && $_POST["pw"] != '') $pw = $this->db1->e($_POST["pw"]);

if (empty($un)) { $errored = 1; $txterror .= 'Error... '; }
if (empty($pw)) { $errored = 1; $txterror .= 'Error... ';}

if ($errored == 1) {
	echo("0: ".$txterror);
} else { 
	if ( !$this->user->login($un,$pw) ) {
		echo('0: '.$this->lang('login_error5'));
	} else {
		$urlr = $this->get_lasturl();
		if(!empty($urlr)) { $this->set_lasturl('/'); echo('2: '.$urlr); }
		else echo('1: Ok');
	}
}
?>