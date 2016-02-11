<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Nwdthemes Standalone Slider Revolution
 *
 * @package     StandaloneRevslider
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2015. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */


if( ! function_exists('check_for_jquery_addon'))
{
	/**
	 *	Checks if jQuery editor addon installed and have correct version
	 *
	 *	return	boolean
	 */

	function check_for_jquery_addon() {
		$ci = &get_instance();
		$ci->load->library('updater');
		$result = $ci->updater->check_jquery_addon();
		return $result['success'];

	}
}

if( ! function_exists('get_jquery_addon_message'))
{
	/**
	 *	Get message about jQuery addon
	 *
	 *	return	string
	 */

	function get_jquery_addon_message() {
		$ci = &get_instance();
		$ci->load->library('updater');
		$result = $ci->updater->check_jquery_addon();
		return isset($result['message']) ? $result['message'] : '';
	}
}

if( ! function_exists('is_jquery_addon_activated'))
{
	/**
	 *	Checks if jQuery editor addon activated
	 *
	 *	return	boolean
	 */

	function is_jquery_addon_activated() {
		return get_option('jquery-plugin-code', '') && get_option('jquery-plugin-code-activated', 'false');
	}
}

