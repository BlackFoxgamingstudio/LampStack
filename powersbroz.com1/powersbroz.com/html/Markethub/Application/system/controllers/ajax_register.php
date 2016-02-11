<?php
// We check in which language we will work
if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

$this->load_langfile('outside/home.php');
$this->load_langfile('outside/register.php');

$errored = 0;
$txterror = '';

$un = $pw = $em = '';

if (isset($_POST["em"]) && $_POST["em"] != '') $em = $this->db1->e($_POST["em"]);
if (isset($_POST["un"]) && $_POST["un"] != '') $un = $this->db1->e($_POST["un"]);
if (isset($_POST["pw"]) && $_POST["pw"] != '') $pw = $this->db1->e($_POST["pw"]);

if (empty($un)) { $errored = 1; $txterror .= 'Error... '; }
if (empty($em)) { $errored = 1; $txterror .= 'Error... '; }
if (empty($pw)) { $errored = 1; $txterror .= 'Error... ';}

if ($errored == 1) {
	echo("0: ".$txterror);
} else {
	$errored = 0;
	
	// verify that no files with the same name registrant username
	if( file_exists($C->INCPATH.'controllers/'.strtolower($un).'.php') ) {
		$errored = 1;
		$txterror = $this->lang('home_f_signup_error5');
		echo("0: ".$txterror); die();
	}
	if( file_exists($C->INCPATH.'../'.strtolower($un)) ) {
		$errored = 1;
		$txterror = $this->lang('home_f_signup_error5');
		echo("0: ".$txterror); die();
	}
	
	//	check if someone is using this email
	$r = $this->db1->query("SELECT iduser FROM users WHERE email='".$em."'");
	if ($this->db1->num_rows($r) > 0) {
		$errored = 1;
		$txterror = $this->lang('home_f_signup_error7');
		echo("0: ".$txterror); die();
	}
		
	//	check if someone is using the username
	$r = $this->db1->query("SELECT iduser FROM users WHERE username='".$un."'");
	if ($this->db1->num_rows($r) > 0) {
		$errored = 1;
		$txterror = $this->lang('home_f_signup_error6');
		echo("0: ".$txterror); die();
	}

	// If no errors, continued here
	
	$salt = md5(uniqid(rand(), true));
	$hash = hash('sha512', $salt.$pw);

	// We get a unique code to the user
	$code = uniqueCode(11, 1, 'users', 'code');
	
	$ip	= $this->db1->escape( ip2long($_SERVER['REMOTE_ADDR']) );
	
	// Save user information
	$this->db1->query("INSERT INTO users SET code='" . $code . "', email='" . $em . "', username='" . $un . "', password='" . $hash . "', salt='" . $salt . "', registerdate='" . time() . "', ipregister='" . $ip . "'");
	
	$htmlOk = '';
	ob_start();

	$this->load_template('__afterregister.php');

	$htmlOk = ob_get_contents();
	ob_end_clean();

	echo("1: ".$htmlOk);
	return;
	
}
?>