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

class Option_model extends CI_Model {

	/*
	 * Table name
	 *
	 */
	public $table = 'options';

	/**
	 *	Get option by handle
	 *
	 *	@param	string Handle
	 *	@return	string Option
	 */
	public function get_option($handle) {
		$option = $this->db->where('handle', $handle)->get($this->table)->row();
		return $option ? $option->option : FALSE;
	}

	/**
	 *	Update option
	 *
	 *	@param	string	Handle
	 *	@param	string	Value
	 */
	public function update_option($handle, $value) {
		$value = is_string($value) ? $value : serialize($value);
		$option = $this->db->where('handle', $handle)->get($this->table)->row();
		$this->db->set('option', $value);
		if ($option)
		{
			$this->db->where('handle', $handle)->update($this->table);
		}
		else
		{
			$this->db->set('handle', $handle)->insert($this->table);
		}
	}

}