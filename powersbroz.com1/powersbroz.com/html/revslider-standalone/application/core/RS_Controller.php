<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

class RS_Controller extends CI_Controller {
	
	public $translator;
	
	/**
	 * Constructor
	 */
	public function __construct() {

		parent::__construct();

		// Check the database settings
		if (defined('ENVIRONMENT') && ENVIRONMENT == 'install')
		{
			redirect(base_url().'install/');
		}

		// check if admin user exists
		$this->load->model('user_model', 'User');
		if ( ! $this->User->exists())
		{
			redirect(base_url().'install/');
		}

		// Check for user session
		if ( ! $this->session->userdata('user_id') )
		{
			if ($this->input->is_ajax_request())
			{
				$response = array(
					'success'		=> true,
					'message'		=> __('Your session has expired. Please log in again.'),
					'is_redirect'	=> true,
					'redirect_url'	=> site_url('c=account&m=login')
				);
				header('Content-Type: application/json');
				echo json_encode($response);
			}
			elseif ($this->input->post('client_action') == 'preview_slider')
			{
				$response = '<html><body><script type="text/javascript">parent.location.reload()</script></body></html>';
				header('Content-Type: text/html');
				echo $response;
			}
			else
			{
				redirect('c=account&m=login');
			}
			die();
		}

		// Set language
		if ($this->input->get('lang'))
		{
			set_language($this->input->get('lang'));
		}
		
		// Set translation
		$translations = Gettext\Translations::fromPoFile(RS_PLUGIN_PATH . 'languages/revslider-' . get_language() . '.po');
		$this->translator = new Translator();
		$this->translator->loadTranslations($translations);

		// Reset actions
		$this->session->set_userdata('actions', array());
		$this->session->set_userdata('filters', array());
		$this->session->set_userdata('styles', array());
		$this->session->set_userdata('inline_styles', array());
	}

}