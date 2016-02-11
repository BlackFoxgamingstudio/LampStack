<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(RS_PLUGIN_PATH . 'includes/globals.class.php');

include_once APPPATH . "libraries/Gettext/autoloader.php";

use Gettext\Translator;

/**
 * Nwdthemes Standalone Slider Revolution
 *
 * @package     StandaloneRevslider
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2015. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */

class Account extends CI_Controller {

	/**
	 *	Constructor
	 */
	public function __construct() {
		parent::__construct();
		// Set translation
		$translations = Gettext\Translations::fromPoFile(RS_PLUGIN_PATH . 'languages/revslider-' . get_language() . '.po');
		$this->translator = new Translator();
		$this->translator->loadTranslations($translations);
	}

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->login();
	}

	/**
	 * Login page
	 */
	public function login()
	{
		$data = $this->session->userdata('data');
		$data['error'] = $this->session->userdata('error');
		$this->session->unset_userdata('error');
		if ( !isset($data['username']))
		{
			$data = array(
				'username'	=> '',
				'password'	=> '',
				'error'	=> '',
			);
		}
		$this->load->view('account/html', array(
			'version'	=> RevSliderGlobals::SLIDER_REVISION,
			'view_html'	=> $this->load->view('account/login', $data, TRUE)
		));
	}

	/**
	 * Login action
	 */

	public function login_action()
	{
		$data = array(
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password'),
		);
		$this->load->model('user_model', 'user');
		$user = $this->user->login($data['username'], $data['password']);
		if ( $user )
		{
			$this->session->set_userdata('user_id', $user['id']);
			redirect( '' );
			die();
		}
		else
		{
			$this->session->set_userdata('data', $data);
			$this->session->set_userdata('error', __('Incorrect login details. Please try again.') );
			redirect('c=account&m=login');
			die();
		}
	}

	/**
	 *	Logout action
	 */

	public function logout_action() {
		$this->session->unset_userdata('user_id');
		$this->session->set_userdata('error', __('You have been logged out. Bye.') );
		redirect( 'c=account&m=login' );
	}

	/**
	 * Recover password action
	 */

	public function recover_password_action()
	{
		$data = array(
			'email' => $this->input->post('email')
		);
		$this->load->model('user_model', 'user');
		$user = $this->user->check_email($data['email']);
		if ( $user )
		{
			$password = $this->user->recover_password($user);

			// send email with password
			$this->load->library('email');

			$this->email->from('mail@' . $this->input->server('server_name'), __('Slider Revolution') );
			$this->email->to($user['email']);
			$this->email->subject( __('Slider Revolution password recovery service') );
			$this->email->message( __('Your password for Slider Revolution admin is: ') . $password );
			$this->email->send();

			$this->session->set_userdata('error', __('Password have been sent to your email.') );
			redirect( 'c=account&mlogin');
			die();
		}
		else
		{
			$this->session->set_userdata('error', __('No user exists with this email. Please try again.') );
			redirect( 'c=account&m=recover_password');
			die();
		}

	}

	/**
	 * Recover password page
	 */
	public function recover_password()
	{
		$data = array(
			'email'	=> '',
			'error' => $this->session->userdata('error')
		);
		$this->session->unset_userdata('error');
		$this->load->view('account/html', array(
			'version'	=> RevSliderGlobals::SLIDER_REVISION,
			'view_html'	=> $this->load->view('account/recover_password', $data, TRUE)
		));
	}

}
