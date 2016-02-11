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

//include frameword files
require_once(RS_PLUGIN_PATH . 'includes/framework/include-framework.php');

//include bases
require_once($folderIncludes . 'base.class.php');
require_once($folderIncludes . 'elements-base.class.php');
require_once($folderIncludes . 'base-admin.class.php');
require_once($folderIncludes . 'base-front.class.php');

//include product files
require_once(RS_PLUGIN_PATH . 'includes/globals.class.php');
require_once(RS_PLUGIN_PATH . 'includes/operations.class.php');
require_once(RS_PLUGIN_PATH . 'includes/slider.class.php');
require_once(RS_PLUGIN_PATH . 'includes/output.class.php');
require_once(RS_PLUGIN_PATH . 'includes/slide.class.php');
require_once(RS_PLUGIN_PATH . 'includes/navigation.class.php');
require_once(RS_PLUGIN_PATH . 'includes/template.class.php');
require_once(RS_PLUGIN_PATH . 'includes/external-sources.class.php');

require_once(RS_PLUGIN_PATH . 'includes/tinybox.class.php');
require_once(RS_PLUGIN_PATH . 'includes/extension.class.php');

//load front part

require_once(RS_PLUGIN_PATH . 'public/revslider-front.class.php');

// load admin part

require_once(RS_PLUGIN_PATH . 'includes/framework/update.class.php');
require_once(RS_PLUGIN_PATH . 'includes/framework/newsletter.class.php');
require_once(RS_PLUGIN_PATH . 'admin/revslider-admin.class.php');

// slider version
$revSliderVersion = RevSliderGlobals::SLIDER_REVISION;
$wp_version = 'NA';
$revslider_screens = array();

class Admin extends RS_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $wpdb;

		parent::__construct();

		$this->load->model('wpdb_model', 'WPDB');
		$wpdb = $this->WPDB;
	}

	/**
	 * Admin pages
	 */
	public function index()
	{
		$revSliderAdmin = new RevSliderAdmin();
		$revSliderAdmin->onAddScripts();

		add_filter('revslider_mod_icon_sets', array('RevSliderBase', 'set_icon_sets'));

		ob_start();
		$revSliderAdmin::adminPages();
		$adminPage = ob_get_contents();
		ob_end_clean();

		call_actions('admin_enqueue_scripts');

		$headerData = array(
			'cssIncludes' => array(
				base_url() . 'assets/css/includes/dashicons.min.css',
				base_url() . 'assets/css/includes/admin-bar.min.css',
				base_url() . 'assets/css/admin/wp-admin.min.css',
				base_url() . 'assets/css/includes/buttons.min.css',
				base_url() . 'assets/css/includes/wp-auth-check.min.css',
				base_url() . 'assets/css/includes/media-views.min.css',
				base_url() . 'assets/css/admin/color-picker.min.css',
				base_url() . 'assets/css/admin/farbtastic.css',
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/jquery-ui.css',
				base_url() . 'assets/js/includes/mediaelement/mediaelementplayer.min.css',
				base_url() . 'assets/js/includes/mediaelement/wp-mediaelement.css',
				base_url() . 'assets/js/includes/imgareaselect/imgareaselect.css',
				base_url() . 'assets/css/rs-separate.css',
				base_url() . 'assets/image_crud/css/fineuploader.css',
				base_url() . 'assets/image_crud/css/photogallery.css',
				base_url() . 'assets/image_crud/css/colorbox.css',

				RS_PLUGIN_URL .'admin/assets/css/admin.css',
				RS_PLUGIN_URL .'admin/assets/css/tipsy.css',
				RS_PLUGIN_URL .'admin/assets/js/codemirror/codemirror.css',
			),
			'jsIncludes' => array(
				base_url() . 'assets/js/includes/jquery/jquery.js',
				base_url() . 'assets/js/includes/jquery/jquery-migrate.min.js',
				base_url() . 'assets/js/includes/utils.min.js',
				base_url() . 'assets/js/includes/plupload/plupload.full.min.js',
				base_url() . 'assets/js/includes/json2.min.js',
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js',
				base_url() . 'assets/js/farbtastic/my-farbtastic.js',
				base_url() . 'assets/js/dropdownchecklist/ui.dropdownchecklist-1.4-min.js',

				base_url() . 'assets/js/gallery.js',
				base_url() . 'assets/js/language.js',
				base_url() . 'assets/js/edit_account.js',
				base_url() . 'assets/js/navigation.js',
				base_url() . 'assets/js/updater.js',

				base_url() . 'assets/image_crud/js/fineuploader-3.2.min.js',
				base_url() . 'assets/image_crud/js/jquery.colorbox-min.js',

				base_url() . 'assets/js/includes/underscore.min.js',
				base_url() . 'assets/js/includes/wp-util.min.js',
				base_url() . 'assets/js/includes/iris.min.js',
				base_url() . 'assets/js/includes/color-picker.min.js',

				RS_PLUGIN_URL . 'admin/assets/js/settings.js',
				RS_PLUGIN_URL . 'admin/assets/js/admin.js',
				RS_PLUGIN_URL . 'admin/assets/js/jquery.tipsy.js',
				RS_PLUGIN_URL . 'admin/assets/js/codemirror/codemirror.js',
				RS_PLUGIN_URL . 'admin/assets/js/codemirror/util/match-highlighter.js',
				RS_PLUGIN_URL . 'admin/assets/js/codemirror/util/searchcursor.js',
				RS_PLUGIN_URL . 'admin/assets/js/codemirror/css.js',
				RS_PLUGIN_URL . 'admin/assets/js/codemirror/xml.js',
				RS_PLUGIN_URL . 'admin/assets/js/edit_layers_timeline.js',
				RS_PLUGIN_URL . 'admin/assets/js/edit_layers.js',
				RS_PLUGIN_URL . 'admin/assets/js/css_editor.js',
				RS_PLUGIN_URL . 'admin/assets/js/rev_admin.js'
			),
			'revLang' => $revSliderAdmin->get_javascript_multilanguage_json(),
			'adminHead' => call_actions('admin_head'),
			'inlineStyles' => $this->session->userdata('inline_styles')
		);

		if (check_for_jquery_addon())
		{
			$headerData['jsIncludes'][] = RS_PLUGIN_URL . 'public/assets/js/jquery.themepunch.tools.min.js';
			$styles = $this->session->userdata('styles');
		}
		else
		{
			$headerData['jsIncludes'][] = base_url() . 'assets/js/jquery.themepunch.tools.min.js';
			$styles = array(
				base_url() . 'assets/fonts/font-awesome/css/font-awesome.min.css',
				base_url() . 'assets/css/settings.css'
			);
		}
		foreach ($styles as $style) {
			$headerData['cssIncludes'][] = $style;
		}

		$this->load->model('user_model', 'User');
		$navigationData = array(
			'user' => $this->User->get( $this->session->userdata('user_id') ),
			'admin_notices' => call_actions('admin_notices'),
			'export_button' => false
		);

		if ($this->input->get('view') == 'slider' || $this->input->get('view') == 'slide')
		{
			$headerData['jsIncludes'][] = base_url() . 'assets/js/navigation.js';
			$navigationData['export_button'] = true;
		}

		echo $this->load->view('admin/header', $headerData, TRUE);
		echo $this->load->view('admin/navigation', $navigationData, TRUE);
		echo $adminPage;
		echo $this->load->view('admin/footer', array(), TRUE);
	}

	/**
	 * Ajax calls
	 */
	public function ajax(){
		
		$action = $this->input->post('client_action');
		
		if(RS_DEMO){
			switch($action){
				case 'update_account':
				case 'download_addon':
				case 'deactivate_addon':
					$action = 'demomode';
				break;
				
			}
		}
		
		switch ($action) {
			case 'update_account' :
				$this->_update_account_action();
			break;
			case 'download_addon' :
				$this->_download_addon();
			break;
			case 'deactivate_addon' :
				$this->_deactivate_addon();
			break;
			default:
				$revSliderAdmin = new RevSliderAdmin();
				$revSliderAdmin::onAjaxAction();
			break;
		}
	}

	/**
	 * Edit account dialog
	 */

	public function edit_account() {
		$this->load->model('user_model', 'User');
		$data = array('user' => $this->User->get( $this->session->userdata('user_id') ));
		$this->load->view('admin/edit_account_dialog', $data);
	}

	/**
	 *	Update account action
	 */

	private function _update_account_action() {
		$data = $this->input->post('data');
		$this->load->model('user_model', 'User');
		$response = $this->User->update($data);
		echo json_encode($response);
		die();
	}

	/**
	 *	Update application
	 */

	public function update() {

		$revSliderAdmin = new RevSliderAdmin();

		global $wp_version;

		dmp(__('Checking for new version...'));

		$upgrade = new RevSliderUpdate( GlobalsRevSlider::SLIDER_REVISION );
		$update = $upgrade->_retrieve_update_info();

		if (isset($update->full->version) && version_compare(RevSliderGlobals::SLIDER_REVISION, $update->full->version, '<'))
		{
			dmp(__('New version found!'));

			$upload_dir = wp_upload_dir();
			$upload_path = DIRECTORY_SEPARATOR . 'update';

			if(wp_mkdir_p( $upload_dir['basedir'] . $upload_path ) )
			{
				dmp(__('Downloading updates...'));

				$request = wp_remote_post($update->full->download_link, array(
					'user-agent' => 'StandAlone/'.$wp_version.'; '.get_bloginfo('url'),
					'timeout' => 45,
					'method' => 'GET'
				));

				if( ! is_wp_error($request))
				{
					if($response = $request['body']) {
						if($response !== 'invalid'){
							
							dmp(__('Updates downloaded!'));
							
							//add stream as a zip file
							$file = $upload_dir['basedir'] . $upload_path . DIRECTORY_SEPARATOR . $update->full->file_name;
							@mkdir(dirname($file));
							$ret = @file_put_contents( $file, $response );
							
							if($ret !== false)
							{
								dmp(__('Installing updates...'));

								// Lets extract

								if (unzip_file($file, $upload_dir['basedir'] . $upload_path) === true)
								{
									unlink($file);
									$file_info = pathinfo($update->full->file_name);
									$update_path = basename($update->full->file_name,'.'.$file_info['extension']);
									recurse_move($upload_dir['basedir'] . $upload_path . DIRECTORY_SEPARATOR . $update_path, FCPATH);
									rmdir($upload_dir['basedir'] . $upload_path);
									$message = __('Updates are installed! Redirecting...');
								}
								else
								{
									$error = __('Invalid ZIP archive or no library installed');
								}

							}
							else
							{
								$error = __('Not possible to save updates to updates folder. Please check permissions.');
							}
						}
						else
						{
							$error = __('Invalid updates response.');
						}
					}
					else
					{
						$error = __('Problem with getting update response.');
					}
				}
				else
				{
					$error = __('Failed to download updates.');
				}
			}
			else
			{
				$error = __('Not possible to write to updates folder. Please check permissions.');
			}
		}
		else
		{
			$error = __('No updates found to download');
		}

		$viewBack = RevSliderAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDERS);
		if (isset($error))
		{
			dmp("<b>Error: ".$error."</b>");
			echo RevSliderFunctions::getHtmlLink($viewBack, __("Go Back",'revslider'));
		}
		else
		{
			dmp(__($message, 'revslider'));
			echo "<script>location.href='$viewBack'</script>";
		}
	}

	/**
	 *	Upload and install addon
	 */

	public function upload_addon() {

		$this->load->library('updater');
		$response = $this->updater->upload_addon();

		//handle error
		$viewBack = RevSliderAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDERS);
		if($response["success"] == false){
			$message = $response["error"];
			dmp("<b>Error: ".$message."</b>");
			echo RevSliderFunctions::getHtmlLink($viewBack, __("Go Back",'revslider'));
		}
		else{	//handle success, js redirect.
			dmp(__("Addon Install Success, redirecting...",'revslider'));
			echo "<script>location.href='$viewBack'</script>";
		}
		exit();

	}

	/**
	 *	Download and install addon
	 */

	private function _download_addon() {
		$this->load->library('updater');
		$response = $this->updater->download_addon();
		echo json_encode($response);
		die();
	}

	/**
	 *	Deactivate addon
	 */

	private function _deactivate_addon() {
		$this->load->library('updater');
		$response = $this->updater->deactivate_addon();
		echo json_encode($response);
		die();
	}
}
