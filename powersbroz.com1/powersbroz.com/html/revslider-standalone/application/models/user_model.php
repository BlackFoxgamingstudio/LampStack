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

class User_model extends CI_Model {

	/*
	 * Table name
	 *
	 */
	public $table = 'user';

	/**
	 *	Check if there is admin user exists
	 */
	public function exists()
	{
		return $this->db->count_all($this->table);
	}

	/**
	 *	Get user
	 *
	 *	@param	int		Id
	 *	@return	array	User
	 */
	public function get($id)
	{
		return $this->db->where('id', $id)->get($this->table)->row_array();
	}

	/**
	 * Login user
	 *
	 * @param  string		Username or email
	 * @param  string		Password
	 * @return array		User
	 */
	public function login($identity, $password)
	{
		$query = $this->db->where('username', $identity)->or_where('email', $identity)->get( $this->table );
		if ($query->num_rows() > 0)
		{
			$user = $query->row_array();
			if ( $password == $this->decrypt($user['password'], $user) )
			{
				return $user;
			}
		}
		return FALSE;
	}

	/**
	 * Check email
	 *
	 * @param  string		Email
	 * @return array		User
	 */
	public function check_email($email)
	{
		$query = $this->db->where('email', $email)->get( $this->table );
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		return FALSE;
	}

	/**
	 * Recover password
	 *
	 * @param  array		User
	 * @return string		Password
	 */
	public function recover_password($user)
	{
		$password = $this->decrypt($user['password'], $user);
		return $password;
	}

	/**
	 *	Update user
	 *
	 *	@param	array	Data
	 *	@return	array	Response
	 */
	public function update($data) {
		$error = '';
		if ( ! $data['user_id'] )
		{
			$error = __('No user id provided');
		}
		elseif ( ! $data['username'] || ! $data['email'])
		{
			$error = __('Username and Email address are required');
		}
		elseif ( strlen($data['username']) < 4)
		{
			$error = __("Username should be at least 4 characters long");
		}
		elseif ( $data['password'] && $data['password'] != $data['confirm_password'])
		{
			$error = __("New password don't match confirmed password");
		}
		elseif ( $data['password'] && strlen($data['password']) < 4)
		{
			$error = __("Password should be at least 4 characters long");
		}

		if (! $error)
		{
			$user = $this->get($data['user_id']);
			if ($data['password'])
			{
				$password = $data['password'];
			}
			else
			{
				$password = $this->decrypt($user['password'], $user);
			}
			$user['username'] = $data['username'];
			$user['email'] = $data['email'];
			$user['salt'] = $this->get_salt();
			$user['password'] = $this->encrypt($password, $user);

			$this->db->set($user)->where('id', $user['id'])->update( $this->table );
			if ( $this->db->affected_rows() != 1 )
			{
				$error = __('Failed to update account. Please try again later.');
			}
		}

		$result = array(
			'success'	=> empty($error),
			'message'	=> empty($error) ? __('Account have been updated') : $error
		);
		return $result;
	}

	/**
	 * Encrypts the password.
	 *
	 * @param  string		The password to encrypt
	 * @param  array		User data array
	 * @return string		Encrypted password
	 */
	public function encrypt($password, $user)
	{
		$ci = &get_instance();
		$ci->load->library('encrypt');

		$hash 	= $ci->encrypt->sha1($user['username'] . $user['salt']);
		$key 	= $ci->encrypt->sha1($ci->config->item('encryption_key') . $hash);

		return $ci->encrypt->encode($password, substr($key, 0, 56));
	}

	/**
	 * Decrypts the password.
	 *
	 * @param  string		The encrypted password
	 * @param  array		User data array
	 * @return string		Decrypted password
	 */
	public function decrypt($password, $user)
	{
		$ci = &get_instance();
		$ci->load->library('encrypt');

		$hash 	= $ci->encrypt->sha1($user['username'] . $user['salt']);
		$key 	= $ci->encrypt->sha1($ci->config->item('encryption_key') . $hash);

		return $ci->encrypt->decode($password, substr($key, 0, 56));
	}

	/**
	 * Generates a random salt value.
	 *
	 * @return String	Hash value
	 *
	 **/
	function get_salt() {
		$ci = &get_instance();
		return substr(md5(uniqid(rand(), true)), 0, $ci->config->item('salt_length'));
	}

}