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
 * Replace str_split()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.str_split
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.1 $
 * @since       PHP 5
 * @require     PHP 4.0.0 (user_error)
 */
function php_compat_str_split($string, $split_length = 1)
{
    if (!is_scalar($split_length)) {
        user_error('str_split() expects parameter 2 to be long, ' .
            gettype($split_length) . ' given', E_USER_WARNING);
        return false;
    }

    $split_length = (int) $split_length;
    if ($split_length < 1) {
        user_error('str_split() The length of each segment must be greater than zero', E_USER_WARNING);
        return false;
    }
    
    // Select split method
    if ($split_length < 65536) {
        // Faster, but only works for less than 2^16
        preg_match_all('/.{1,' . $split_length . '}/s', $string, $matches);
        return $matches[0];
    } else {
        // Required due to preg limitations
        $arr = array();
        $idx = 0;
        $pos = 0;
        $len = strlen($string);

        while ($len > 0) {
            $blk = ($len < $split_length) ? $len : $split_length;
            $arr[$idx++] = substr($string, $pos, $blk);
            $pos += $blk;
            $len -= $blk;
        }

        return $arr;
    }
}


// Define
if (!function_exists('str_split')) {
    function str_split($string, $split_length = 1)
    {
        return php_compat_str_split($string, $split_length);
    }
}
