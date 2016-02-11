<?php
/*
 * Plugin Name: NextGEN Facebook (NGFB)
 * Plugin Slug: nextgen-facebook
 * Text Domain: nextgen-facebook
 * Domain Path: /languages
 * Plugin URI: http://surniaulula.com/extend/plugins/nextgen-facebook/
 * Author: JS Morisset
 * Author URI: http://surniaulula.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Description: Display your content in the best possible way on Facebook, Google+, Twitter, Pinterest, etc. - no matter how your webpage is shared!
 * Requires At Least: 3.1
 * Tested Up To: 4.4
 * Version: 8.21.5
 * 
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'Ngfb' ) ) {

	class Ngfb {
		/*
		 * Class Object Variables
		 */
		public $p;			// Ngfb
		public $admin;			// NgfbAdmin (admin menus and page loader)
		public $cache;			// SucomCache (object and file caching)
		public $debug;			// SucomDebug or NgfbNoDebug
		public $head;			// NgfbHead
		public $loader;			// NgfbLoader
		public $media;			// NgfbMedia (images, videos, etc.)
		public $msgs;			// NgfbMessages (admin tooltip messages)
		public $notice;			// SucomNotice or NgfbNoNotice
		public $og;			// NgfbOpengraph
		public $tc;			// NgfbTwittercard
		public $opt;			// NgfbOptions
		public $reg;			// NgfbRegister
		public $script;			// SucomScript (admin jquery tooltips)
		public $sharing;		// NgfbSharing (wp_head and wp_footer js and buttons)
		public $style;			// SucomStyle (admin styles)
		public $util;			// NgfbUtil (extends SucomUtil)
		public $webpage;		// SucomWebpage (title, desc, etc., plus shortcodes)

		/*
		 * Reference Variables (config, options, modules, etc.)
		 */
		public $cf = array();		// config array defined in construct method
		public $is_avail = array();	// assoc array for other plugin checks
		public $options = array();	// individual blog/site options
		public $site_options = array();	// multisite options
		public $mods = array();		// pro and gpl modules

		protected static $instance = null;

		public static function &get_instance() {
			if ( self::$instance === null )
				self::$instance = new self;
			return self::$instance;
		}

		/*
		 * Ngfb Constructor
		 */
		public function __construct() {

			require_once( dirname( __FILE__ ).'/lib/config.php' );
			$this->cf = NgfbConfig::get_config();			// unfiltered - $cf['*'] array is not available yet
			NgfbConfig::set_constants( __FILE__ );
			NgfbConfig::require_libs( __FILE__ );			// includes the register.php class library
			$this->reg = new NgfbRegister( $this );			// activate, deactivate, uninstall hooks

			add_action( 'init', array( &$this, 'set_config' ), -1 );
			add_action( 'init', array( &$this, 'init_plugin' ), NGFB_INIT_PRIORITY );
			add_action( 'widgets_init', array( &$this, 'init_widgets' ), 10 );
		}

		// runs at init priority -1
		public function set_config() {
			$this->cf = NgfbConfig::get_config( false, true );	// apply filters - define the $cf['*'] array
		}

		// runs at init priority 1
		public function init_widgets() {
			$opts = get_option( NGFB_OPTIONS_NAME );
			if ( ! empty( $opts['plugin_widgets'] ) ) {
				foreach ( $this->cf['plugin'] as $lca => $info ) {
					if ( isset( $info['lib']['widget'] ) && is_array( $info['lib']['widget'] ) ) {
						foreach ( $info['lib']['widget'] as $id => $name ) {
							$classname = apply_filters( $lca.'_load_lib', false, 'widget/'.$id );
							if ( $classname !== false && class_exists( $classname ) )
								register_widget( $classname );
						}
					}
				}
			}
		}

		// runs at init priority 13 (by default)
		public function init_plugin() {
			if ( ! empty( $_SERVER['NGFB_DISABLE'] ) ) 
				return;

			$this->set_objects();				// define the class object variables

			if ( $this->debug->enabled ) {
				foreach ( array( 'wp_head', 'wp_footer', 'admin_head', 'admin_footer' ) as $action ) {
					foreach ( array( -9999, 9999 ) as $prio ) {
						add_action( $action, create_function( '', 'echo "<!-- ngfb '.
							$action.' action hook priority '.$prio.' mark -->\n";' ), $prio );
						add_action( $action, array( &$this, 'show_debug_html' ), $prio );
					}
				}
			}
			do_action( 'ngfb_init_plugin' );
		}

		public function show_debug_html() { 
			if ( $this->debug->enabled )
				$this->debug->show_html();
		}

		// called by activate_plugin() as well
		public function set_objects( $activate = false ) {
			/*
			 * basic plugin setup (settings, check, debug, notices, utils)
			 */
			$this->set_options();	// filter and define the $this->options and $this->site_options properties
			$this->check = new NgfbCheck( $this );
			$this->is_avail = $this->check->get_avail();		// uses $this->options in checks

			// configure the debug class
			$html_debug = ! empty( $this->options['plugin_debug'] ) || 
				( defined( 'NGFB_HTML_DEBUG' ) && NGFB_HTML_DEBUG ) ? true : false;
			$wp_debug = defined( 'NGFB_WP_DEBUG' ) && NGFB_WP_DEBUG ? true : false;

			// only load the debug class if one or more debug options are enabled
			if ( ( $html_debug || $wp_debug ) && 
				( $classname = NgfbConfig::load_lib( false, 'com/debug', 'SucomDebug' ) ) )
					$this->debug = new $classname( $this, array( 'html' => $html_debug, 'wp' => $wp_debug ) );
			else $this->debug = new NgfbNoDebug();

			if ( $activate === true &&
				$this->debug->enabled )
					$this->debug->log( 'method called for plugin activation' );

			// only load the notification class in the admin interface
			if ( is_admin() &&
				( $classname = NgfbConfig::load_lib( false, 'com/notice', 'SucomNotice' ) ) )
					$this->notice = new $classname( $this );
			else $this->notice = new NgfbNoNotice();

			$this->util = new NgfbUtil( $this );
			$this->opt = new NgfbOptions( $this );
			$this->cache = new SucomCache( $this );			// object and file caching
			$this->style = new SucomStyle( $this );			// admin styles
			$this->script = new SucomScript( $this );		// admin jquery tooltips
			$this->webpage = new SucomWebpage( $this );		// title, desc, etc., plus shortcodes
			$this->media = new NgfbMedia( $this );			// images, videos, etc.
			$this->head = new NgfbHead( $this );
			$this->og = new NgfbOpengraph( $this );
			$this->tc = new NgfbTwittercard( $this );
			$this->schema = new NgfbSchema( $this );

			if ( is_admin() ) {
				$this->msgs = new NgfbMessages( $this );	// admin tooltip messages
				$this->admin = new NgfbAdmin( $this );		// admin menus and page loader
			}

			if ( $this->is_avail['ssb'] )
				$this->sharing = new NgfbSharing( $this );	// wp_head and wp_footer js and buttons

			$this->loader = new NgfbLoader( $this, $activate );	// module loader

			do_action( 'ngfb_init_objects', $activate );

			/*
			 * check and create the default options array
			 * execute after all objects have been defines, so hooks into 'ngfb_get_defaults' are available
			 */
			if ( is_multisite() && ( ! is_array( $this->site_options ) || empty( $this->site_options ) ) )
				$this->site_options = $this->opt->get_site_defaults();

			/*
			 * end here when called for plugin activation (the init_plugin() hook handles the rest)
			 */
			if ( $activate == true || ( 
				! empty( $_GET['action'] ) && $_GET['action'] == 'activate-plugin' &&
				! empty( $_GET['plugin'] ) && $_GET['plugin'] == NGFB_PLUGINBASE ) ) {
				if ( $this->debug->enabled )
					$this->debug->log( 'exiting early: init_plugin hook will follow' );
				return;
			}

			/*
			 * check and upgrade options if necessary
			 */
			$this->options = $this->opt->check_options( NGFB_OPTIONS_NAME, $this->options );

			if ( is_multisite() )
				$this->site_options = $this->opt->check_options( NGFB_SITE_OPTIONS_NAME, 
					$this->site_options, true );

			/*
			 * configure class properties based on plugin settings
			 */
			$this->cache->default_object_expire = $this->options['plugin_object_cache_exp'];

			$this->cache->default_file_expire = ( $this->check->aop() ? 
				( $this->debug->is_enabled( 'wp' ) ? 
					NGFB_DEBUG_FILE_EXP : 
					$this->options['plugin_file_cache_exp'] ) : 0 );

			$this->is_avail['cache']['file'] = $this->cache->default_file_expire > 0 ? true : false;

			// disable the transient cache if html debug mode is on
			if ( $this->debug->is_enabled( 'html' ) === true ) {

				$this->is_avail['cache']['transient'] = defined( 'NGFB_TRANSIENT_CACHE_DISABLE' ) &&
					! NGFB_TRANSIENT_CACHE_DISABLE ? true : false;

				if ( $this->debug->enabled )
					$this->debug->log( 'html debug mode is active: transient cache use '.
						( $this->is_avail['cache']['transient'] ? 'could not be' : 'is' ).' disabled' );

				if ( is_admin() )
					// text_domain is already loaded by the NgfbAdmin class construct
					$this->notice->inf( ( $this->is_avail['cache']['transient'] ?
						__( 'HTML debug mode is active (transient cache could NOT be disabled).', 'nextgen-facebook' ) :
						__( 'HTML debug mode is active (transient cache use is disabled).', 'nextgen-facebook' ) ).' '.
						__( 'Informational debug messages are being added as hidden HTML comments.', 'nextgen-facebook' ) );
			}
		}

		public function set_options() {
			$this->options = get_option( NGFB_OPTIONS_NAME );

			// look for alternate options name
			if ( ! is_array( $this->options ) ) {
				if ( defined( 'NGFB_OPTIONS_NAME_ALT' ) && NGFB_OPTIONS_NAME_ALT ) {
					$this->options = get_option( NGFB_OPTIONS_NAME_ALT );
					if ( is_array( $this->options ) ) {
						// auto-creates options with autoload = yes
						update_option( NGFB_OPTIONS_NAME, $this->options );
						delete_option( NGFB_OPTIONS_NAME_ALT );
					}
				}
			}

			if ( ! is_array( $this->options ) )
				$this->options = array();

			if ( is_multisite() ) {
				$this->site_options = get_site_option( NGFB_SITE_OPTIONS_NAME );

				// look for alternate site options name
				if ( ! is_array( $this->site_options ) ) {
					if ( defined( 'NGFB_SITE_OPTIONS_NAME_ALT' ) && NGFB_SITE_OPTIONS_NAME_ALT ) {
						$this->site_options = get_site_option( NGFB_SITE_OPTIONS_NAME_ALT );
						if ( is_array( $this->site_options ) ) {
							update_site_option( NGFB_SITE_OPTIONS_NAME, $this->site_options );
							delete_site_option( NGFB_SITE_OPTIONS_NAME_ALT );
						}
					}
				}

				// if multisite options are found, check for overwrite of site specific options
				if ( is_array( $this->options ) && is_array( $this->site_options ) ) {
					$current_blog_id = function_exists( 'get_current_blog_id' ) ? 
						get_current_blog_id() : false;
					foreach ( $this->site_options as $key => $val ) {
						if ( isset( $this->site_options[$key.':use'] ) &&
							isset( $this->options[$key] ) ) {

							switch ( $this->site_options[$key.':use'] ) {
								case'force':
									$this->options[$key.':is'] = 'disabled';
									$this->options[$key] = $this->site_options[$key];
									break;
								case 'empty':
									if ( empty( $this->options[$key] ) )
										$this->options[$key] = $this->site_options[$key];
									break;
							}

							// check for constant over-rides
							if ( $current_blog_id !== false ) {
								$constant_name = 'NGFB_OPTIONS_'.$current_blog_id.'_'.strtoupper( $key );
								if ( defined( $constant_name ) )
									$this->options[$key] = constant( $constant_name );
							}
						}
					}
				}
			}

			if ( ! is_array( $this->site_options ) )
				$this->site_options = array();

			// just in case
			unset( $this->options['options_filtered'],
				$this->site_options['options_filtered'] );

			$this->options = apply_filters( 'ngfb_get_options', $this->options );
			$this->site_options = apply_filters( 'ngfb_get_site_options', $this->site_options );
		}
	}

	global $ngfb;
	$ngfb =& Ngfb::get_instance();
}

if ( ! class_exists( 'NgfbNoDebug' ) ) {
	class NgfbNoDebug {
		public $enabled = false;
		public function mark() { return; }
		public function args() { return; }
		public function log() { return; }
		public function show_html() { return; }
		public function get_html() { return; }
		public function is_enabled() { return false; }
	}
}

if ( ! class_exists( 'NgfbNoNotice' ) ) {
	class NgfbNoNotice {
		public function nag() { return; }
		public function inf() { return; }
		public function err() { return; }
		public function log() { return; }
		public function trunc() { return; }
	}
}

?>
