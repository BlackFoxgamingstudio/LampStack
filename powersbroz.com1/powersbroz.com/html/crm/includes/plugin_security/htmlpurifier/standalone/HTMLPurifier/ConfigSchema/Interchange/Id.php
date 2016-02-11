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

/**
 * Represents a directive ID in the interchange format.
 */
class HTMLPurifier_ConfigSchema_Interchange_Id
{

    public $key;

    public function __construct($key) {
        $this->key = $key;
    }

    /**
     * @warning This is NOT magic, to ensure that people don't abuse SPL and
     *          cause problems for PHP 5.0 support.
     */
    public function toString() {
        return $this->key;
    }

    public function getRootNamespace() {
        return substr($this->key, 0, strpos($this->key, "."));
    }

    public function getDirective() {
        return substr($this->key, strpos($this->key, ".") + 1);
    }

    public static function make($id) {
        return new HTMLPurifier_ConfigSchema_Interchange_Id($id);
    }

}

// vim: et sw=4 sts=4
