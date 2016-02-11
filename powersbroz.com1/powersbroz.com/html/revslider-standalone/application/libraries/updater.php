<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Updater {

	const ADDON_PRODUCT = 'visual-editor-extension';
	const ADDON_CFG_PATH = 'info.cfg';
	const ADDON_PATH = 'public/assets/';
	const ACTIVATE_URL = 'http://updates.themepunch.tools/activate.php';
	const DEACTIVATE_URL = 'http://updates.themepunch.tools/deactivate.php';
	const UPDATE_URL = 'http://updates.themepunch.tools/revslider-js-addon/jquery-addon.php';
	const UPDATE_FILE = 'visual-editor-extension.zip';

	private $_ci;

	public function __construct() {
		$this->_ci = &get_instance();
		$this->_ci->load->library('filesystem');
	}

	/**
	 *	Check if have valid jQuery addon
	 *
	 *	return	boolean
	 */

	public function check_jquery_addon() {
		$cfg_path = RS_PLUGIN_PATH . self::ADDON_PATH . self::ADDON_CFG_PATH;
		if ($this->_ci->filesystem->exists($cfg_path))
		{
			$cfg = json_decode( $this->_ci->filesystem->get_contents($cfg_path) );
			if (isset($cfg->version))
			{
				update_option('revslider-js-version', $cfg->version);
				
				if (version_compare(RevSliderGlobals::SLIDER_REVISION, $cfg->version, '<='))
				{
					return array('success' => true);
				}
				else
				{
					return array(
						'success' => false,
						'message' => __('Installed jQuery Plugin version: ', 'revslider') . $cfg->version .
									 __(' Required version: ', 'revslider') . RevSliderGlobals::SLIDER_REVISION .
									 __(' Please install updates', 'revslider')
					);
				}
			}
		}
		return array(
			'success' => false,
			'message' => __('Slider Revolution jQuery Plugin NOT installed', 'revslider')
		);
	}

	/**
	 *	Upload and install addon
	 */

	public function upload_addon() {

		try{

			switch ($_FILES['addon_file']['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					RevSliderFunctions::throwError(__('No file sent.', 'revslider'));
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					RevSliderFunctions::throwError(__('Exceeded filesize limit.', 'revslider'));
				default:
				break;
			}
			$filepath = $_FILES["addon_file"]["tmp_name"];

			$this->_install_addon($filepath);

		}catch(Exception $e){
			$errorMessage = $e->getMessage();
			return(array("success"=>false,"error"=>$errorMessage));
		}

		return(array("success"=>true));

	}

	/**
	 *	Download and install addon
	 */

	public function download_addon() {
		try {

			$data = $this->_ci->input->post('data');
			$code = isset($data['code']) ? $data['code'] : '';
			if ( ! $code)
			{
				RevSliderFunctions::throwError(__('Please enter purchase code', 'revslider'));
			}

			$response = $this->_register_code($code);

			if ( ! $response['success'])
			{
				if (isset($response['message']))
				{
					RevSliderFunctions::throwError($response['message']);
				}
				else
				{
					RevSliderFunctions::throwError(__('Unable to register your purchase code', 'revslider'));
				}
			}

			$request = wp_remote_post($response['download_link'], array(
				'user-agent' => 'StandAlone/None; '.get_bloginfo('url'),
				'timeout' => 45,
				'method' => 'GET'
			));

			if(is_wp_error($request) || $request['body'] == 'invalid')
			{
				RevSliderFunctions::throwError(__('Unable to download addon. Please try again later', 'revslider'));
			}

			$upload_dir = wp_upload_dir();
			$upload_path = DIRECTORY_SEPARATOR . 'update';
			$addon_file = $upload_dir['basedir'] . $upload_path . DIRECTORY_SEPARATOR . self::UPDATE_FILE;
			@mkdir(dirname($addon_file));
			$ret = @file_put_contents($addon_file, $request['body']);
			if($ret === false)
			{
				RevSliderFunctions::throwError(__('Unable to save downloaded addon. Please make sure destination path is writable', 'revslider'));
			}

			$response = $this->_install_addon($addon_file);
			@unlink($addon_file);
			if ($response === false)
			{
				RevSliderFunctions::throwError(__('Failed to install addon', 'revslider'));
			}

		}
		catch(Exception $e)
		{
			return(array("success"=>false,"message"=>$e->getMessage()));
		}

		return array(
			'success' => true,
			'is_redirect' => true,
			'redirect_url' => RevSliderAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDERS),
			'message' => __('Addon has been installed!', 'revslider')
		);
	}

	/**
	 *	Register purchase code
	 *
	 *	@param	string	Code
	 *	@return	array
	 */

	private function _register_code($code) {

		if ( ! is_jquery_addon_activated())
		{
			$response = wp_remote_post(self::ACTIVATE_URL, array(
				'user-agent' => 'StandAlone/None; '.get_bloginfo('url'),
				'body' => array(
					'code' => urlencode($code),
					'product' => urlencode(self::ADDON_PRODUCT)
				)
			));

			$response_code = wp_remote_retrieve_response_code( $response );
			$version_info = wp_remote_retrieve_body( $response );
		
			if ( $response_code != 200 || is_wp_error( $version_info ) )
			{
				return array('success' => false);
			}

			if ($version_info == 'valid')
			{
				update_option('jquery-plugin-code-activated', 'true');
				update_option('jquery-plugin-code', $code);
			}
			elseif ($version_info == 'exist')
			{
				return array('success' => false, 'message' => __('Purchase Code already registered!', 'revslider'));
			}
			else
			{
				return array('success' => false);
			}
		}

		$request = wp_remote_post(self::UPDATE_URL, array(
			'user-agent' => 'StandAlone/None; '.get_bloginfo('url'),
			'body' => array(
				'code' => urlencode($code),
				'product' => urlencode(self::ADDON_PRODUCT)
			),
		));
		
		if(!is_wp_error($request))
		{
			if($response = maybe_unserialize($request['body']))
			{
				if (is_object($response) && isset($response->download_link))
				{
					$data = array(
						'success' => true,
						'download_link' => $response->download_link
					);
					return $data;
				}
			}
		}

		return array('success' => false);
	}

	/**
	 *	Deactivate Addon Purchase Code
	 */

	public function deactivate_addon() {

		$code = get_option('jquery-plugin-code', '');

		$response = wp_remote_post(self::DEACTIVATE_URL, array(
			'user-agent' => 'StandAlone/None; '.get_bloginfo('url'),
			'body' => array(
				'code' => urlencode($code),
				'product' => urlencode(self::ADDON_PRODUCT)
			)
		));

		$response_code = wp_remote_retrieve_response_code( $response );
		$version_info = wp_remote_retrieve_body( $response );

		if ($response_code != 200 || is_wp_error( $version_info ) || $version_info != 'valid')
		{
			return array("success" => false, "message" => __('Failed to deregister addon purchase code'));
		}
		else
		{
			update_option('jquery-plugin-code-activated', 'false');
			update_option('jquery-plugin-code', '');
			return array(
				'success' => true,
				'is_redirect' => true,
				'redirect_url' => RevSliderAdmin::getViewUrl(RevSliderAdmin::VIEW_SLIDERS),
				'message' => __('Addon have been deregistered!', 'revslider')
			);
		}
	}

	/**
	 *	Install addon
	 *
	 *	@param	string	Addon file path
	 *	@return	boolean
	 */

	private function _install_addon($filepath) {

		if(file_exists($filepath) == false)
		{
			RevSliderFunctions::throwError(__('Addon file not found!!!', 'revslider'));
		}

		$importZip = false;

		WP_Filesystem();

		global $wp_filesystem;

		$upload_dir = wp_upload_dir();
		$d_path = $upload_dir['basedir'].DIRECTORY_SEPARATOR.'rstemp'.DIRECTORY_SEPARATOR;
		$unzipfile = unzip_file( $filepath, $d_path);

		if ($unzipfile)
		{
			$content = ( $wp_filesystem->exists( $d_path.self::ADDON_CFG_PATH ) ) ? $wp_filesystem->get_contents( $d_path.self::ADDON_CFG_PATH ) : '';
			$cfg = json_decode( $content );

			if ( ! isset($cfg->version))
			{
				RevSliderFunctions::throwError(__('Invalid or corrupted addon file!', 'revslider'));
			}

			if (version_compare($cfg->version, RevSliderGlobals::SLIDER_REVISION, '<'))
			{
				RevSliderFunctions::throwError(__('Incorrect addon version! Please download required version ' . RevSliderGlobals::SLIDER_REVISION, 'revslider'));
			}

			// lets install it
			recurse_move($d_path, RS_PLUGIN_PATH.self::ADDON_PATH);
		}
		else
		{
			RevSliderFunctions::throwError(__('Unzipping failed', 'revslider'));
		}

		return true;
	}

}