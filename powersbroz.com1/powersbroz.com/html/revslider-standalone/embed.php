<?php
/**
 * Nwdthemes Standalone Slider Revolution
 *
 * @package     StandaloneRevslider
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2015. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */

// slider version
$revSliderVersion = "5.1.6";

$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$base_url .= "://".$_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$base_url = str_replace('/install', '', $base_url);

// CI base data
$system_folder = "system";
$application_folder = 'application';

if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
		$system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
}
else
{
	$system_folder = str_replace("\\", "/", $system_folder);
}

// CI constants
define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
define('BASEPATH', $system_folder.'/');
define('ROOTPATH', str_replace("\\", "/", realpath(dirname($system_folder))) . '/');
define('FCPATH', ROOTPATH);
define('BASEURL', $base_url);
define('APPPATH', ROOTPATH . $application_folder.'/');

// CI classes include

require($system_folder . '/database/DB.php');

require($system_folder . '/core/Config.php');
require($system_folder . '/core/Common.php');
require($system_folder . '/core/Loader.php');
require($system_folder . '/core/Utf8.php');
require($system_folder . '/core/Input.php');

require($system_folder . '/libraries/Session.php');

require($application_folder . '/config/constants.php');
require($application_folder . '/config/revslider.php');

require($system_folder . '/helpers/url_helper.php');

require($application_folder . '/helpers/general_helper.php');
require($application_folder . '/helpers/option_helper.php');
require($application_folder . '/helpers/language_helper.php');
require($application_folder . '/helpers/images_helper.php');

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
//require_once(RS_PLUGIN_PATH . 'includes/widget.class.php');
require_once(RS_PLUGIN_PATH . 'includes/navigation.class.php');
require_once(RS_PLUGIN_PATH . 'includes/template.class.php');
require_once(RS_PLUGIN_PATH . 'includes/external-sources.class.php');

require_once(RS_PLUGIN_PATH . 'includes/tinybox.class.php');
require_once(RS_PLUGIN_PATH . 'includes/extension.class.php');
require_once(RS_PLUGIN_PATH . 'public/revslider-front.class.php');

// Rev Slider Embedder Class

$CFG = new CI_Config;
$CFG->set_item('rs_image_sizes', $config['rs_image_sizes']);
$UNI = new CI_Utf8;

class RevSliderEmbedder {

	private static $instance;
	public static $table_prefix;
	public $load;
	public $db;
	public $config;
	public $session;
	public $input;
	public $utf8;

	/**
	 * Constructor
	 *
	 */
	public function __construct() {

		global $CFG;
		global $UNI;

		$CFG->set_item('global_xss_filtering', FALSE);
		$CFG->set_item('csrf_protection', FALSE);

		self::$instance =& $this;
		self::$instance->config = & $CFG;
		self::$instance->load = new CI_Loader;
		self::$instance->utf8 = & $UNI;
		self::$instance->input = new CI_Input;

		include(APPPATH.'config/database'.EXT);
		$this->db = DB('default', true);
		$this->db->db_connect();
		$this->db->db_select();

		self::$table_prefix = $db[$active_group]['dbprefix'];

		RevSliderGlobals::$table_sliders = self::$table_prefix . RevSliderGlobals::TABLE_SLIDERS_NAME;
		RevSliderGlobals::$table_slides = self::$table_prefix . RevSliderGlobals::TABLE_SLIDES_NAME;
		RevSliderGlobals::$table_static_slides = self::$table_prefix . RevSliderGlobals::TABLE_STATIC_SLIDES_NAME;
		RevSliderGlobals::$table_settings = self::$table_prefix . RevSliderGlobals::TABLE_SETTINGS_NAME;
		RevSliderGlobals::$table_css = self::$table_prefix . RevSliderGlobals::TABLE_CSS_NAME;
		RevSliderGlobals::$table_layer_anims = self::$table_prefix . RevSliderGlobals::TABLE_LAYER_ANIMS_NAME;
		RevSliderGlobals::$table_navigation = self::$table_prefix . RevSliderGlobals::TABLE_NAVIGATION_NAME;
	}

	/**
	 * Returns current instance of Embedder
	 *
	 */
	public static function &get_instance() {
		return self::$instance;
	}

	/**
	 *	Return header includes
	 *
	 *	@param	boolean	Include jQuery
	 *	@return string
	 */

	public static function headIncludes($jQueryInclude = true) {

		$output = '';
		$output .= '<link rel="stylesheet" href="' . RS_PLUGIN_URL . 'public/assets/css/settings.css' . '" type="text/css" media="all" />' . "\n";

		$custom_css = RevSliderOperations::getStaticCss();
		$custom_css = RevSliderCssParser::compress_css($custom_css);

		$output .= '<style type="text/css">' . $custom_css . '</style>' . "\n";

		if ($jQueryInclude)
		{
			$output .= '<script type="text/javascript" src="' . base_url() . 'assets/js/includes/jquery/jquery.js' . '"></script>' . "\n";
		}

		$output .= '<script type="text/javascript" src="' . RS_PLUGIN_URL .'public/assets/js/jquery.themepunch.tools.min.js' . '"></script>' . "\n";
		$output .= '<script type="text/javascript" src="' . RS_PLUGIN_URL .'public/assets/js/jquery.themepunch.revolution.min.js' . '"></script>' . "\n";

		echo $output;
	}

	/**
	 *	Put Slider
	 */
	public static function putRevSlider($data,$putIn = "") {

		// Do not output Slider if we are on mobile
		ob_start();
		$slider = RevSliderOutput::putSlider($data,$putIn);
		$content = ob_get_contents();
		ob_clean();
		ob_end_clean();

		$content = self::load_icon_fonts() . $content;

        if(!empty($slider)){
            // Do not output Slider if we are on mobile
            $disable_on_mobile = $slider->getParam("disable_on_mobile","off");
            if($disable_on_mobile == 'on'){
                $mobile = (strstr($_SERVER['HTTP_USER_AGENT'],'Android') || strstr($_SERVER['HTTP_USER_AGENT'],'webOS') || strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') ||strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'Windows Phone') || wp_is_mobile()) ? true : false;
                if($mobile) return false;
            }

            $show_alternate = $slider->getParam("show_alternative_type","off");

            if($show_alternate == 'mobile' || $show_alternate == 'mobile-ie8'){
                if(strstr($_SERVER['HTTP_USER_AGENT'],'Android') || strstr($_SERVER['HTTP_USER_AGENT'],'webOS') || strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') ||strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'Windows Phone') || wp_is_mobile()){
                    $show_alternate_image = $slider->getParam("show_alternate_image","");
                    $content = '<img class="tp-slider-alternative-image" src="'.$show_alternate_image.'" data-no-retina>';
                }
            }
        }

		echo $content;
	}

	/**
	 *	Add icon fonts
	 */

	public static function load_icon_fonts(){
		global $fa_icon_var,$pe_7s_var;
		$content = '';
		if($fa_icon_var) $content .= "<link rel='stylesheet' property='stylesheet' id='rs-icon-set-fa-icon-css'  href='" . RS_PLUGIN_URL . "public/assets/fonts/font-awesome/css/font-awesome.css' type='text/css' media='all' />";
		if($pe_7s_var) $content .= "<link rel='stylesheet' property='stylesheet' id='rs-icon-set-pe-7s-css'  href='" . RS_PLUGIN_URL . "public/assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css' type='text/css' media='all' />";
		return $content;
	}

}

// Create new instance

$revSliderEmbedder = new RevSliderEmbedder;
$revSliderEmbedder->session = new CI_Session;

$revSliderEmbedder->load->model('wpdb_model', 'WPDB');
$wpdb = $revSliderEmbedder->WPDB;

// Get intance globally

function &get_instance() {
	global $revSliderEmbedder;
	return $revSliderEmbedder::get_instance();
}

// define plugin url

define('RS_PLUGIN_URL', base_url() . 'revslider/');


