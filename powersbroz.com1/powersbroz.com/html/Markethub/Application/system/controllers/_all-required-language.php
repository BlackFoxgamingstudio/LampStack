<?php
/*************************************************************************/


/*************************************************************************/

	// This is it necessary to display the languages
	if ($C->SHOW_MENU_LANGUAJE == 1) {
		require_once( $C->INCPATH.'helpers/func_languages.php' );
		$D->listlanguages = array();
		foreach(get_available_languages(FALSE) as $k=>$v) {
			$D->listlanguages[$k] = $v->name;
		}
		
		// Evaluate which language is to be used
		$lang = $C->LANGUAGE;
		$_SESSION["DATAGLOBAL"][] = array();
		if ($this->param('lang')) {
			if (array_key_exists($this->param('lang'), $D->listlanguages)) {
				$_SESSION["DATAGLOBAL"][0] = $this->param('lang');
				$C->LANGUAGE = $this->param('lang');
			}
		} elseif ($_SESSION["DATAGLOBAL"][0]) {
			if (array_key_exists($_SESSION["DATAGLOBAL"][0], $D->listlanguages)) {
				$C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];
			}
		}
	}
/*************************************************************************/

?>