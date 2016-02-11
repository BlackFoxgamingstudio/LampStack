<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */



class module_setup extends module_base{
    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	public function init(){
		$this->module_name = "setup";
		$this->module_position = 20;

        $this->version=2.321;
		// 2.3 - 2014-07-07 - initial setup improvements
		// 2.31 - 2014-07-25 - initial setup improvements
		// 2.32 - 2014-09-18 - sql mode fix
		// 2.321 - 2015-03-18 - db prefix fix

	}





}