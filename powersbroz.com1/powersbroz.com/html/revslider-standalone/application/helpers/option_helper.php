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

/**
 *	Get option
 *
 *	@param	string	Handle
 *	@param	string	Default value
 *	@return	string	Option value
 */

if( ! function_exists('get_option'))
{
	function get_option($handle, $default = false) {
		$ci = &get_instance();
		$ci->load->model('option_model', 'Option');

		if ($value = $ci->Option->get_option($handle))
		{
			if ($unserializedValue = @unserialize($value))
			{
				$value = $unserializedValue;
			}
			return $value;
		}
		else
		{
			return $default;
		}
	}
}

/**
 * Update option
 *
 * @param string $handle
 * @param string value
 */

if( ! function_exists('update_option'))
{
	function update_option($handle, $option = '') {
		$ci = &get_instance();
		$ci->load->model('option_model', 'Option');
		$ci->Option->update_option($handle, $option);
	}
}