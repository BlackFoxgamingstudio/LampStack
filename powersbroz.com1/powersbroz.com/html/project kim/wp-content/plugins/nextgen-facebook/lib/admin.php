<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdmin' ) ) {

	class NgfbAdmin {
	
		protected $p;
		protected $menu_id;
		protected $menu_name;
		protected $menu_lib;
		protected $pagehook;

		public static $is_suffix;
		public static $readme_info = array();

		public $form;
		public $lang = array();
		public $submenu = array();

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			if ( SucomUtil::get_const( 'DOING_AJAX' ) ) {
				// nothing to do
			} else {
				load_plugin_textdomain( 'nextgen-facebook', false, 'nextgen-facebook/languages/' );

				$this->set_objects();
				$this->pro_req_notices();
				$this->conflict_warnings();

				add_action( 'admin_init', array( &$this, 'register_setting' ) );
				add_action( 'admin_menu', array( &$this, 'add_admin_menus' ), NGFB_ADD_MENU_PRIORITY );
				// add settings and users submenu items
				add_action( 'admin_menu', array( &$this, 'add_admin_submenus' ), NGFB_ADD_SUBMENU_PRIORITY );

				// nag notices are no longer stored, so this filter hook is unnecessary
				//add_action( 'activated_plugin', array( &$this, 'check_activated_plugin' ), 10, 2 );

				add_action( 'after_switch_theme', array( &$this, 'check_tmpl_head_elements' ) );
				add_action( 'upgrader_process_complete', array( &$this, 'check_tmpl_head_elements' ) );

				add_filter( 'plugin_action_links', array( &$this, 'add_plugin_action_links' ), 10, 2 );
				add_filter( 'wp_redirect', array( &$this, 'wp_profile_updated_redirect' ), -100, 2 );
	
				if ( is_multisite() ) {
					add_action( 'network_admin_menu', array( &$this, 'add_network_admin_menus' ), NGFB_ADD_MENU_PRIORITY );
					add_action( 'network_admin_edit_'.NGFB_SITE_OPTIONS_NAME, array( &$this, 'save_site_options' ) );
					add_filter( 'network_admin_plugin_action_links', array( &$this, 'add_plugin_action_links' ), 10, 2 );
				}

				add_filter( 'get_user_option_wpseo_dismissed_conflicts', 
					array( &$this, 'dismiss_wpseo_notice' ), 10, 3 );
			}

		}

		// load all submenu classes into the $this->submenu array
		// the id of each submenu item must be unique
		private function set_objects() {
			self::$is_suffix = ' '.( $this->p->check->aop( $this->p->cf['lca'], 
				true, $this->p->is_avail['aop'] ) ? 
					_x( 'Pro', 'package type', 'nextgen-facebook' ) :
					_x( 'Free', 'package type', 'nextgen-facebook' ) );

			$menu_libs = array( 
				'submenu', 
				'setting',		// setting must be after submenu to extend submenu/advanced.php
				'profile', 
			);

			if ( is_multisite() )
				$menu_libs[] = 'sitesubmenu';

			foreach ( $menu_libs as $menu_lib ) {
				foreach ( $this->p->cf['plugin'] as $ext => $info ) {
					if ( isset( $info['lib'][$menu_lib] ) ) {
						foreach ( $info['lib'][$menu_lib] as $menu_id => $menu_name ) {
							if ( strpos( $menu_id, 'separator' ) !== false ) 
								continue;
							$classname = apply_filters( $ext.'_load_lib', false, $menu_lib.'/'.$menu_id );
							if ( $classname !== false && class_exists( $classname ) ) {
								if ( ! empty( $info['text_domain'] ) )
									$menu_name = _x( $menu_name, 'lib file description', $info['text_domain'] );
								$this->submenu[$menu_id] = new $classname( $this->p, $menu_id, $menu_name, $menu_lib );
							}
						}
					}
				}
			}
		}

		private function pro_req_notices() {
			$lca = $this->p->cf['lca'];
			if ( $this->p->is_avail['aop'] === true && 
				empty( $this->p->options['plugin_'.$lca.'_tid'] ) && 
					( empty( $this->p->options['plugin_'.$lca.'_tid:is'] ) || 
						$this->p->options['plugin_'.$lca.'_tid:is'] !== 'disabled' ) )
							$this->p->notice->nag( $this->p->msgs->get( 'notice-pro-tid-missing' ) );

			$has_tid = false;
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {
				if ( ! empty( $this->p->options['plugin_'.$ext.'_tid'] ) &&
					isset( $info['base'] ) && SucomUtil::active_plugins( $info['base'] ) ) {
					$has_tid = true;	// found at least one active plugin with an auth id
					if ( ! $this->p->check->aop( $ext, false ) )
						$this->p->notice->err( $this->p->msgs->get( 'notice-pro-not-installed', 
							array( 'lca' => $ext ) ) );
				}
			}

			if ( $has_tid === true && ! $this->p->is_avail['util']['um'] ) {
				if ( ! function_exists( 'get_plugins' ) )
					require_once( ABSPATH.'wp-admin/includes/plugin.php' );
				$installed_plugins = get_plugins();
				if ( ! empty( $this->p->cf['plugin']['ngfbum']['base'] ) &&
					is_array( $installed_plugins[$this->p->cf['plugin']['ngfbum']['base']] ) )
						$this->p->notice->nag( $this->p->msgs->get( 'notice-um-activate-extension' ) );
				else $this->p->notice->nag( $this->p->msgs->get( 'notice-um-extension-required' ) );
			}
		}

		// nag notices are no longer stored, so this filter hook is unnecessary
		/* public function check_activated_plugin( $plugin = false, $sitewide = false ) {
			$lca = $this->p->cf['lca'];
			switch ( $plugin ) {
				case $this->p->cf['plugin'][$lca.'um']['base']:
					$this->p->notice->trunc_all( 'nag' );
					break;
			}
		} */

		protected function set_form_property() {
			$def_opts = $this->p->opt->get_defaults();
			$this->form = new SucomForm( $this->p, NGFB_OPTIONS_NAME, $this->p->options, $def_opts );
		}

		protected function &get_form_reference() {	// returns a reference
			return $this->form;
		}

		public function register_setting() {
			register_setting( $this->p->cf['lca'].'_setting', 
				NGFB_OPTIONS_NAME, array( &$this, 'registered_setting_sanitation' ) );
		} 

		public function set_readme_info( $expire_secs = 86400 ) {
			foreach ( array_keys( $this->p->cf['plugin'] ) as $ext ) {
				if ( empty( self::$readme_info[$ext] ) )
					self::$readme_info[$ext] = $this->p->util->parse_readme( $ext, $expire_secs );
			}
		}

		public function add_admin_submenus() {
			foreach ( array( 'profile', 'setting' ) as $menu_lib ) {

				// match wordpress behavior (users page for admins, profile page for everyone else)
				if ( $menu_lib === 'profile' && current_user_can( 'list_users' ) )
					$parent_slug = $this->p->cf['wp']['admin']['users']['page'];
				else $parent_slug = $this->p->cf['wp']['admin'][$menu_lib]['page'];

				foreach ( $this->p->cf['*']['lib'][$menu_lib] as $menu_id => $menu_name ) {
					if ( isset( $this->submenu[$menu_id] ) )
						$this->submenu[$menu_id]->add_submenu_page( $parent_slug );
					else $this->add_submenu_page( $parent_slug, $menu_id, $menu_name, $menu_lib );
				}
			}
		}

		public function add_network_admin_menus() {
			$this->add_admin_menus( 'sitesubmenu' );
		}

		public function add_admin_menus( $menu_lib = '' ) {
			$menu_lib = empty( $menu_lib ) ?
				'submenu' : $menu_lib;
			$libs = $this->p->cf['*']['lib'][$menu_lib];

			$this->menu_id = key( $libs );
			$this->menu_name = $libs[$this->menu_id];
			$this->menu_lib = $menu_lib;

			if ( isset( $this->submenu[$this->menu_id] ) ) {
				$menu_slug = $this->p->cf['lca'].'-'.$this->menu_id;
				$this->submenu[$this->menu_id]->add_menu_page( $menu_slug );
			}

			foreach ( $libs as $menu_id => $menu_name ) {
				$parent_slug = $this->p->cf['lca'].'-'.$this->menu_id;
				if ( isset( $this->submenu[$menu_id] ) )
					$this->submenu[$menu_id]->add_submenu_page( $parent_slug );
				else $this->add_submenu_page( $parent_slug, $menu_id, $menu_name, $menu_lib );
			}
		}

		protected function add_menu_page( $menu_slug ) {
			global $wp_version;
			$lca = $this->p->cf['lca'];
			$short = $this->p->cf['plugin'][$lca]['short'];

			// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$this->pagehook = add_menu_page( 
				$short.self::$is_suffix.' &mdash; '.$this->menu_name, 
				$this->p->cf['menu'].self::$is_suffix, 
				( isset( $this->p->cf['wp']['admin'][$this->menu_lib]['cap'] ) ?
					$this->p->cf['wp']['admin'][$this->menu_lib]['cap'] :
					'manage_options' ),	// fallback to manage_options capability
				$menu_slug, 
				array( &$this, 'show_setting_page' ), 
				( version_compare( $wp_version, 3.8, '<' ) ? null : 'dashicons-share' ),
				NGFB_MENU_ORDER
			);
			add_action( 'load-'.$this->pagehook, array( &$this, 'load_setting_page' ) );
		}

		protected function add_submenu_page( $parent_slug, $menu_id = '', $menu_name = '', $menu_lib = '' ) {
			$lca = $this->p->cf['lca'];
			$short = $this->p->cf['plugin'][$lca]['short'];
			$menu_id = empty( $menu_id ) ?
				$this->menu_id : $menu_id;
			$menu_name = empty( $menu_name ) ?
				$this->menu_name : $menu_name;
			$menu_lib = empty( $menu_lib ) ?
				$this->menu_lib : $menu_lib;

			if ( strpos( $menu_id, 'separator' ) !== false ) {
				$menu_title = '<div style="z-index:999;
					padding:2px 0;
					margin:0;
					cursor:default;
					border-bottom:1px dotted;
					color:#666;" onclick="return false;">'.
						( $menu_name === $this->p->cf['menu'] ? 
							$menu_name.self::$is_suffix : $menu_name ).'</div>';
				$menu_slug = '';
				$page_title = '';
				$function = '';
			} else {
				// highlight the "extension plugins" part of the menu title
				if ( strpos( $menu_id, 'licenses' ) !== false )
					$menu_title = preg_replace( '/^<span>/',
						'<span style="color:#'.$this->p->cf['color'].';">', $menu_name );
				else $menu_title = $menu_name;

				$menu_slug = $lca.'-'.$menu_id;
				$page_title = $short.self::$is_suffix.' &mdash; '.$menu_title;
				$function = array( &$this, 'show_setting_page' );
			}

			// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
			$this->pagehook = add_submenu_page( $parent_slug, $page_title, $menu_title,
				( isset( $this->p->cf['wp']['admin'][$menu_lib]['cap'] ) ?
					$this->p->cf['wp']['admin'][$menu_lib]['cap'] :
					'manage_options' ), $menu_slug, $function );	// fallback to manage_options capability

			if ( $function )
				add_action( 'load-'.$this->pagehook, array( &$this, 'load_setting_page' ) );
		}

		// add links on the main plugins page
		public function add_plugin_action_links( $links, $file ) {

			if ( ! isset( $this->p->cf['*']['base'][$file] ) )
				return $links;

			$ext = $this->p->cf['*']['base'][$file];
			$info = $this->p->cf['plugin'][$ext];

			foreach ( $links as $num => $val )
				if ( strpos( $val, '>Edit<' ) !== false )
					unset ( $links[$num] );

			if ( ! empty( $info['url']['faq'] ) )
				$links[] = '<a href="'.$info['url']['faq'].'">'.
					_x( 'FAQ', 'plugin action link', 'nextgen-facebook' ).'</a>';

			if ( ! empty( $info['url']['notes'] ) )
				$links[] = '<a href="'.$info['url']['notes'].'">'.
					_x( 'Notes', 'plugin action link', 'nextgen-facebook' ).'</a>';

			if ( ! empty( $info['url']['latest_zip'] ) )
				$links[] = '<a href="'.$info['url']['latest_zip'].'">'.
					_x( 'Download Latest', 'plugin action link', 'nextgen-facebook' ).'</a>';

			if ( ! empty( $info['url']['pro_support'] ) &&
				$this->p->check->aop( $ext, true, $this->p->is_avail['aop'] ) ) {
					$links[] = '<a href="'.$info['url']['pro_support'].'">'.
						_x( 'Pro Support', 'plugin action link', 'nextgen-facebook' ).'</a>';
			} else {
				if ( ! empty( $info['url']['wp_support'] ) )
					$links[] = '<a href="'.$info['url']['wp_support'].'">'.
						_x( 'Support Forum', 'plugin action link', 'nextgen-facebook' ).'</a>';

				if ( ! empty( $info['url']['purchase'] ) ) {
					if ( $this->p->check->aop( $ext, false, $this->p->is_avail['aop'] ) )
						$links[] = '<a href="'.$info['url']['purchase'].'">'.
							_x( 'Purchase Pro License(s)', 'plugin action link', 'nextgen-facebook' ).'</a>';
					else $links[] = '<a href="'.$info['url']['purchase'].'">'.
						_x( 'Purchase Pro Version', 'plugin action link', 'nextgen-facebook' ).'</a>';
				}
			}

			return $links;
		}

		// this method receives only a partial options array, so re-create a full one
		// wordpress handles the actual saving of the options
		public function registered_setting_sanitation( $opts ) {
			$network = false;
			if ( ! is_array( $opts ) ) {
				add_settings_error( NGFB_OPTIONS_NAME, 'notarray', '<b>'.$this->p->cf['uca'].' Error</b> : '.
					__( 'Submitted options are not an array.', 'nextgen-facebook' ), 'error' );
				return $opts;
			}
			// get default values, including css from default stylesheets
			$def_opts = $this->p->opt->get_defaults();
			$opts = SucomUtil::restore_checkboxes( $opts );
			$opts = array_merge( $this->p->options, $opts );
			$this->p->notice->trunc();	// clear all messages before sanitation checks
			$opts = $this->p->opt->sanitize( $opts, $def_opts, $network );
			$opts = apply_filters( $this->p->cf['lca'].'_save_options', $opts, NGFB_OPTIONS_NAME, $network );
			$clear_cache_link = wp_nonce_url( $this->p->util->get_admin_url( '?'.$this->p->cf['lca'].
				'-action=clear_all_cache' ), self::get_nonce(), NGFB_NONCE );
			$this->p->notice->inf( __( 'Plugin settings have been saved.', 'nextgen-facebook' ).' '.
				sprintf( __( 'Wait %1$d seconds for cache objects to expire or <a href="%2$s">%3$s</a> now.',
					'nextgen-facebook' ), $this->p->options['plugin_object_cache_exp'], $clear_cache_link,
						_x( 'Clear All Cache(s)', 'submit button', 'nextgen-facebook' ) ), true );
			$this->check_tmpl_head_elements();
			return $opts;
		}

		public function save_site_options() {
			$network = true;

			$page = empty( $_POST['page'] ) ? 
				key( $this->p->cf['*']['lib']['sitesubmenu'] ) :
				$_POST['page'];

			if ( empty( $_POST[ NGFB_NONCE ] ) ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->log( 'nonce token validation post field missing' );
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			} elseif ( ! wp_verify_nonce( $_POST[ NGFB_NONCE ], self::get_nonce() ) ) {
				$this->p->notice->err( __( 'Nonce token validation failed for network options (update ignored).',
					'nextgen-facebook' ), true );
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			} elseif ( ! current_user_can( 'manage_network_options' ) ) {
				$this->p->notice->err( __( 'Insufficient privileges to modify network options.',
					'nextgen-facebook' ), true );
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			}

			$def_opts = $this->p->opt->get_site_defaults();
			$opts = empty( $_POST[NGFB_SITE_OPTIONS_NAME] ) ? $def_opts : 
				SucomUtil::restore_checkboxes( $_POST[NGFB_SITE_OPTIONS_NAME] );
			$opts = array_merge( $this->p->site_options, $opts );
			$this->p->notice->trunc();	// clear all messages before sanitation checks
			$opts = $this->p->opt->sanitize( $opts, $def_opts, $network );
			$opts = apply_filters( $this->p->cf['lca'].'_save_site_options', $opts, $def_opts, $network );
			update_site_option( NGFB_SITE_OPTIONS_NAME, $opts );
			$this->p->notice->inf( __( 'Plugin settings have been saved.', 'nextgen-facebook' ), true );
			wp_redirect( $this->p->util->get_admin_url( $page ).'&settings-updated=true' );
			exit;	// stop here
		}

		public function load_single_page() {
			wp_enqueue_script( 'postbox' );
			$this->p->admin->submenu[$this->menu_id]->add_meta_boxes();
		}

		public function load_setting_page() {
			$lca = $this->p->cf['lca'];
			$action_query = $lca.'-action';
			wp_enqueue_script( 'postbox' );

			if ( ! empty( $_GET[$action_query] ) ) {
				$action_name = SucomUtil::sanitize_hookname( $_GET[$action_query] );
				if ( empty( $_GET[ NGFB_NONCE ] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'nonce token validation query field missing' );
				} elseif ( ! wp_verify_nonce( $_GET[ NGFB_NONCE ], self::get_nonce() ) ) {
					$this->p->notice->err( __( 'Nonce token validation failed for action \"'.$action_name.'\".', 'nextgen-facebook' ) );
				} else {
					$_SERVER['REQUEST_URI'] = remove_query_arg( array( $action_query, NGFB_NONCE ) );
					switch ( $action_name ) {
						case 'check_for_updates': 
							if ( $this->p->is_avail['util']['um'] ) {
								self::$readme_info = array();
								$ngfbum = NgfbUm::get_instance();
								$ngfbum->update->check_for_updates( null, true, false );
							} else {
								$this->p->notice->err( sprintf( __( 'The <b>%s</b> extension is required to check for Pro version updates.',
									'nextgen-facebook' ), $this->p->cf['plugin'][$lca.'um']['name'] ) );
							}
							break;

						case 'clear_all_cache': 
							$this->p->util->clear_all_cache();
							break;

						case 'clear_metabox_prefs': 
							$user_id = get_current_user_id();
							$user = get_userdata( $user_id );
							//$user_name = trim( $user->first_name.' '.$user->last_name );
							$user_name = $user->display_name;
							NgfbUser::delete_metabox_prefs( $user_id );
							$this->p->notice->inf( sprintf( __( 'Metabox layout preferences for user ID #%d "%s" have been reset.',
								'nextgen-facebook' ), $user_id, $user_name ) );
							break;

						case 'clear_hidden_notices': 
							$user_id = get_current_user_id();
							$user = get_userdata( $user_id );
							//$user_name = trim( $user->first_name.' '.$user->last_name );
							$user_name = $user->display_name;
							delete_user_option( $user_id, NGFB_DISMISS_NAME );
							$this->p->notice->inf( sprintf( __( 'Hidden notices for user ID #%d "%s" have been cleared.',
								'nextgen-facebook' ), $user_id, $user_name ) );
							break;

						case 'change_show_options': 
							if ( isset( $this->p->cf['form']['show_options'][$_GET['show-opts']] ) ) {
								$this->p->notice->inf( sprintf( 'Option preference saved &mdash; viewing "%s" by default.',
									$this->p->cf['form']['show_options'][$_GET['show-opts']] ) );
								NgfbUser::save_pref( array( 'show_opts' => $_GET['show-opts'] ) );
							}
							$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'show-opts' ) );
							break;

						case 'modify_tmpl_head_elements': 
							$this->modify_tmpl_head_elements();
							break;

						default: 
							do_action( $lca.'_load_setting_page_'.$action_name, 
								$this->pagehook, $this->menu_id, $this->menu_name, $this->menu_lib );
							break;
					}
				}
			}

			// the plugin information metabox on all settings pages needs this
			$this->p->admin->set_readme_info( $this->p->cf['feed_cache_exp'] );

			// add child metaboxes first, since they contain the default reset_metabox_prefs()
			$this->p->admin->submenu[ $this->menu_id ]->add_meta_boxes();

			if ( empty( $this->p->options['plugin_'.$lca.'_tid'] ) || 
				! $this->p->check->aop( $lca, true, $this->p->is_avail['aop'] ) ) {

				add_meta_box( $this->pagehook.'_purchase',
					_x( 'Pro / Power-User Version', 'metabox title (side)', 'nextgen-facebook' ), 
						array( &$this, 'show_metabox_purchase' ), $this->pagehook, 'side' );

				$this->p->mods['util']['user']->reset_metabox_prefs( $this->pagehook, 
					array( 'purchase' ), null, 'side', true );
			}

			add_meta_box( $this->pagehook.'_help',
				_x( 'Help and Support', 'metabox title (side)', 'nextgen-facebook' ), 
					array( &$this, 'show_metabox_help' ), $this->pagehook, 'side' );

			if ( $this->menu_lib === 'submenu' ) {
				add_meta_box( $this->pagehook.'_status_gpl',
					_x( 'Standard Features', 'metabox title (side)', 'nextgen-facebook' ), 
						array( &$this, 'show_metabox_status_gpl' ), $this->pagehook, 'side' );
	
				add_meta_box( $this->pagehook.'_status_pro',
					_x( 'Pro Version Features', 'metabox title (side)', 'nextgen-facebook' ), 
						array( &$this, 'show_metabox_status_pro' ), $this->pagehook, 'side' );
			}

			if ( $this->menu_lib === 'submenu' || $this->menu_lib === 'sitesubmenu' ) {
				add_meta_box( $this->pagehook.'_version_info',
					_x( 'Version Information', 'metabox title (side)', 'nextgen-facebook' ), 
						array( &$this, 'show_metabox_version_info' ), $this->pagehook, 'side' );
			}
		}

		protected function add_meta_boxes() {
		}

		public function show_setting_page( $sidebar = true ) {

			if ( ! $this->is_setting() )
				settings_errors( NGFB_OPTIONS_NAME );

			$this->set_form_property();			// set form for side boxes and show_form_content()

			if ( $this->p->debug->enabled ) {
				$this->p->debug->show_html( print_r( $this->p->is_avail, true ), 'available features' );
				$this->p->debug->show_html( print_r( NgfbUtil::active_plugins(), true ), 'active plugins' );
				$this->p->debug->show_html( null, 'debug log' );
			}
			
			echo '<div class="wrap" id="'.$this->pagehook.'">'."\n";
			echo '<h1>'.$this->p->cf['plugin'][$this->p->cf['lca']]['short'].
				self::$is_suffix.' &ndash; '.$this->menu_name.'</h1>'."\n";

			if ( $sidebar === false ) {
				echo '<div id="poststuff" class="metabox-holder">'."\n";
				echo '<div id="post-body">'."\n";
				echo '<div id="post-body-content">'."\n";
			} else {
				echo '<div id="poststuff" class="metabox-holder has-right-sidebar">'."\n";
				echo '<div id="side-info-column" class="inner-sidebar">'."\n";
				do_meta_boxes( $this->pagehook, 'side', null );
				echo '</div><!-- .inner-sidebar -->'."\n";
				echo '<div id="post-body" class="has-sidebar">'."\n";
				echo '<div id="post-body-content" class="has-sidebar-content">'."\n";
			}

			$this->show_form_content();
			?>
						</div><!-- .post-body-content -->
					</div><!-- .post-body -->
				</div><!-- .metabox-holder -->
			</div><!-- .wrap -->
			<script type="text/javascript">
				//<![CDATA[
				jQuery(document).ready( 
					function($) {
						// close postboxes that should be closed
						$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
						// postboxes setup
						postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
					}
				);
				//]]>
			</script>
			<?php
		}

		public function wp_profile_updated_redirect( $location, $status ) {
			if ( strpos( $location, 'updated=' ) !== false && 
				strpos( $location, 'wp_http_referer=' ) ) {

				// match wordpress behavior (users page for admins, profile page for everyone else)
				$menu_lib = current_user_can( 'list_users' ) ? 'users' : 'profile';
				$parent_slug = $this->p->cf['wp']['admin'][$menu_lib]['page'];
				$referer_match = '/'.$parent_slug.'?page='.$this->p->cf['lca'].'-';
				parse_str( parse_url( $location, PHP_URL_QUERY ), $parts );

				if ( strpos( $parts['wp_http_referer'], $referer_match ) ) {
					$this->p->notice->inf( __( 'Profile updated.' ), true );
					return add_query_arg( 'updated', true, $parts['wp_http_referer'] );
				}
			}
			return $location;
		}

		protected function show_form_content() {

			$lca = $this->p->cf['lca'];

			if ( $this->menu_lib === 'profile' ) {

				$user_id = get_current_user_id();
				$profileuser = get_user_to_edit( $user_id );
				$current_color = get_user_option( 'admin_color', $user_id );
				if ( empty( $current_color ) )
					$current_color = 'fresh';

				// match wordpress behavior (users page for admins, profile page for everyone else)
				$admin_url = current_user_can( 'list_users' ) ? 
					$this->p->util->get_admin_url( $this->menu_id, null, 'users' ) :
					$this->p->util->get_admin_url( $this->menu_id, null, $this->menu_lib );

				echo '<form name="'.$lca.'" id="'.$lca.'_setting_form" action="user-edit.php" method="post">'."\n";
				echo '<input type="hidden" name="wp_http_referer" value="'.$admin_url.'" />'."\n";
				echo '<input type="hidden" name="action" value="update" />'."\n";
				echo '<input type="hidden" name="user_id" value="'.$user_id.'" />'."\n";
				echo '<input type="hidden" name="nickname" value="'.$profileuser->nickname.'" />'."\n";
				echo '<input type="hidden" name="email" value="'.$profileuser->user_email.'" />'."\n";
				echo '<input type="hidden" name="admin_color" value="'.$current_color.'" />'."\n";
				echo '<input type="hidden" name="rich_editing" value="'.$profileuser->rich_editing.'" />'."\n";
				echo '<input type="hidden" name="comment_shortcuts" value="'.$profileuser->comment_shortcuts.'" />'."\n";
				echo '<input type="hidden" name="admin_bar_front" value="'._get_admin_bar_pref( 'front', $user_id ).'" />'."\n";

				wp_nonce_field( 'update-user_'.$user_id );

			} elseif ( $this->menu_lib === 'setting' || $this->menu_lib === 'submenu' ) {

				echo '<form name="'.$lca.'" id="'.$lca.'_setting_form" action="options.php" method="post">'."\n";

				settings_fields( $lca.'_setting' ); 

			} elseif ( $this->menu_lib === 'sitesubmenu' ) {

				echo '<form name="'.$lca.'" id="'.$lca.'_setting_form" action="edit.php?action='.
					NGFB_SITE_OPTIONS_NAME.'" method="post">'."\n";
				echo '<input type="hidden" name="page" value="'.$this->menu_id.'" />';

			} else return;

			wp_nonce_field( self::get_nonce(), NGFB_NONCE );
			wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
			wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );

			do_meta_boxes( $this->pagehook, 'normal', null ); 

			do_action( $this->p->cf['lca'].'_form_content_metaboxes_'.
				SucomUtil::sanitize_hookname( $this->menu_id ), $this->pagehook );

			switch ( $this->menu_id ) {
				case 'readme':
				case 'setup':
				case 'sitereadme':
				case 'sitesetup':
					break;
				default:
					if ( $this->menu_lib === 'profile' )
						echo $this->get_submit_buttons( _x( 'Save All Profile Settings',
							'submit button', 'nextgen-facebook' ) );
					else echo $this->get_submit_buttons();
					break;
			}
			echo '</form>', "\n";
		}

		protected function get_submit_buttons( $submit_text = '', $class = 'submit-buttons' ) {
			if ( empty( $submit_text ) ) 
				$submit_text = _x( 'Save All Plugin Settings', 'submit button', 'nextgen-facebook' );

			$show_opts_next = SucomUtil::next_key( NgfbUser::show_opts(), $this->p->cf['form']['show_options'] );
			$show_opts_text = sprintf( _x( 'View %s by Default', 'submit button', 'nextgen-facebook' ),
				_x( $this->p->cf['form']['show_options'][$show_opts_next],
					'option value', 'nextgen-facebook' ) );
			$show_opts_url = $this->p->util->get_admin_url( '?'.$this->p->cf['lca'].
				'-action=change_show_options&show-opts='.$show_opts_next );

			$action_buttons = '<input type="submit" class="button-primary" value="'.$submit_text.'" />'.
				$this->form->get_button( $show_opts_text, 'button-secondary button-highlight', null, 
					wp_nonce_url( $show_opts_url, self::get_nonce(), NGFB_NONCE ) ).'<br/>';

			if ( $this->menu_lib === 'setting' || $this->menu_lib === 'submenu' )
				$action_buttons .= $this->form->get_button( _x( 'Clear All Cache(s)', 'submit button', 'nextgen-facebook' ), 
					'button-secondary', null, wp_nonce_url( $this->p->util->get_admin_url( '?'.$this->p->cf['lca'].
						'-action=clear_all_cache' ), self::get_nonce(), NGFB_NONCE ) );

			if ( $this->menu_lib !== 'profile' )		// don't show on profile pages
				$action_buttons .= $this->form->get_button( _x( 'Check for Pro Update(s)', 'submit button', 'nextgen-facebook' ),
					'button-secondary', null, wp_nonce_url( $this->p->util->get_admin_url( '?'.$this->p->cf['lca'].
						'-action=check_for_updates' ), self::get_nonce(), NGFB_NONCE ), false,
							( $this->p->is_avail['util']['um'] ? false : true ) );	// disable button if um not available

			$action_buttons .= $this->form->get_button( _x( 'Reset Metabox Layout', 'submit button', 'nextgen-facebook' ), 
				'button-secondary', null, wp_nonce_url( $this->p->util->get_admin_url( '?'.$this->p->cf['lca'].
					'-action=clear_metabox_prefs' ), self::get_nonce(), NGFB_NONCE ) );

			$action_buttons .= $this->form->get_button( _x( 'Reset Hidden Notices', 'submit button', 'nextgen-facebook' ), 
				'button-secondary', null, wp_nonce_url( $this->p->util->get_admin_url( '?'.$this->p->cf['lca'].
					'-action=clear_hidden_notices' ), self::get_nonce(), NGFB_NONCE ) );

			return '<div class="'.$class.'">'.$action_buttons.'</div>';
		}

		public function show_metabox_version_info() {
			echo '<table class="sucom-setting '.$this->p->cf['lca'].' side">';
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {
				if ( empty( $info['version'] ) )	// filter out extensions that are not active
					continue;

				$stable_version = __( 'N/A', 'nextgen-facebook' );
				$latest_version = __( 'N/A', 'nextgen-facebook' );
				$installed_version = $info['version'];
				$installed_style = '';
				$latest_notice = '';
				$changelog_url = $info['url']['changelog'];

				// the readme_info array is populated by set_readme_info(), which is called from load_setting_page()
				if ( ! empty( self::$readme_info[$ext]['stable_tag'] ) ) {
					$stable_version = self::$readme_info[$ext]['stable_tag'];
					$upgrade_notice = self::$readme_info[$ext]['upgrade_notice'];
					if ( is_array( $upgrade_notice ) ) {
						reset( $upgrade_notice );
						$latest_version = key( $upgrade_notice );
						$latest_notice = $upgrade_notice[$latest_version];
					}
					$installed_style = version_compare( $installed_version, $stable_version, '<' ) ?
						'style="background-color:#f00;"' : 
						'style="background-color:#0f0;"';
				}

				echo '<tr><td colspan="2"><h4>'.$info['short'].' '.
					( $this->p->check->aop( $ext, true, $this->p->is_avail['aop'] ) ?
						_x( 'Pro', 'package type', 'nextgen-facebook' ) :
						_x( 'Free', 'package type', 'nextgen-facebook' ) ).'</h4></td></tr>';
				echo '<tr><th class="side">'._x( 'Installed',
					'plugin status label', 'nextgen-facebook' ).':</th>
					<td class="side_version" '.$installed_style.'>'.$installed_version.'</td></tr>';
				echo '<tr><th class="side">'._x( 'Stable',
					'plugin status label', 'nextgen-facebook' ).':</th>
					<td class="side_version">'.$stable_version.'</td></tr>';
				echo '<tr><th class="side">'._x( 'Latest',
					'plugin status label', 'nextgen-facebook' ).':</th>
					<td class="side_version">'.$latest_version.'</td></tr>';
				echo '<tr><td colspan="2" id="latest_notice"><p>'.$latest_notice.'</p>'.
					'<p><a href="'.$changelog_url.'" target="_blank">'.
						sprintf( _x( 'View %s changelog...', 'following plugin status version numbers',
							'nextgen-facebook' ), $info['short'] ).'</a></p></td></tr>';
			}
			echo '</table>';
		}

		public function show_metabox_status_gpl() {
			$metabox = 'status';
			$plugin_count = 0;
			foreach ( $this->p->cf['plugin'] as $ext => $info )
				if ( isset( $info['lib']['gpl'] ) )
					$plugin_count++;
			echo '<table class="sucom-setting '.$this->p->cf['lca'].' side"
				style="margin-bottom:10px;">';
			/*
			 * GPL version features
			 */
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {
				if ( ! isset( $info['lib']['gpl'] ) )
					continue;
				if ( $ext === $this->p->cf['lca'] )	// features for this plugin
					$features = array(
						'Author JSON-LD' => array( 
							'status' => $this->p->options['schema_author_json'] ?
								'on' : 'rec',
						),
						'Debug Messages' => array(
							'classname' => 'SucomDebug',
						),
						'Non-Persistant Cache' => array(
							'status' => $this->p->is_avail['cache']['object'] ?
								'on' : 'rec',
						),
						'Open Graph / Rich Pin' => array( 
							'status' => class_exists( $this->p->cf['lca'].'opengraph' ) ?
								'on' : 'rec',
						),
						'Publisher JSON-LD' => array(
							'status' => $this->p->options['schema_publisher_json'] ?
								'on' : 'rec',
						),
						'Transient Cache' => array(
							'status' => $this->p->is_avail['cache']['transient'] ?
								'on' : 'rec',
						),
						'Twitter Cards' => array( 
							'status' => class_exists( $this->p->cf['lca'].'twittercard' ) ?
								'on' : 'rec',
						),
					);
				else $features = array();

				$features = apply_filters( $ext.'_'.$metabox.'_gpl_features', $features, $ext, $info );

				if ( ! empty( $features ) ) {
					if ( $plugin_count > 1 )
						echo '<tr><td><h4>'.$this->p->cf['plugin'][$ext]['short'].'</h4></td></tr>';
					$this->show_plugin_status( $ext, $info, $features );
				}
			}
			echo '</table>';
		}

		public function show_metabox_status_pro() {
			$metabox = 'status';
			$plugin_count = 0;
			foreach ( $this->p->cf['plugin'] as $ext => $info )
				if ( isset( $info['lib']['pro'] ) )
					$plugin_count++;
			echo '<table class="sucom-setting '.$this->p->cf['lca'].' side"
				style="margin-bottom:10px;">';
			/*
			 * Pro version features
			 */
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {
				if ( ! isset( $info['lib']['pro'] ) )
					continue;
				$features = array();
				$short = $this->p->cf['plugin'][$ext]['short'];
				$short_pro = $short.' Pro';
				$aop = $this->p->check->aop( $ext, 
					true, $this->p->is_avail['aop'] );
				foreach ( $info['lib']['pro'] as $sub => $libs ) {
					if ( $sub === 'admin' ) 
						continue;	// skip status for admin menus and tabs
					foreach ( $libs as $id => $name ) {
						$off = $this->p->is_avail[$sub][$id] ?
							'rec' : 'off';
						$features[$name] = array( 
							'status' => class_exists( $ext.'pro'.$sub.$id ) ?
								( $aop ? 'on' : $off ) : $off,
							// default tooltip text
							'tooltip' => sprintf( __( 'If the %1$s plugin is detected, %2$s will load additional '.
								'integration modules to provide enhanced support and features for %3$s.',
									'nextgen-facebook'), $name, $short_pro, $name ),
							'td_class' => $aop ? '' : 'blank',
							'purchase' => empty( $info['url']['purchase'] ) ?
								'' : $info['url']['purchase'],
						);
					}
				}
				$features = apply_filters( $ext.'_'.$metabox.'_pro_features', $features, $ext, $info );

				if ( ! empty( $features ) ) {
					if ( $plugin_count > 1 )
						echo '<tr><td><h4>'.$this->p->cf['plugin'][$ext]['short'].'</h4></td></tr>';
					$this->show_plugin_status( $ext, $info, $features );
				}
			}
			echo '</table>';
		}

		private function show_plugin_status( &$ext = '', &$info = array(), &$features = array() ) {
			$status_info = array( 
				'on' => array(
					'img' => 'green-circle.png',
					'title' => 'Feature is Enabled',
				),
				'off' => array(
					'img' => 'gray-circle.png',
					'title' => 'Feature is Disabled / not Loaded',
				),
				'rec' => array(
					'img' => 'red-circle.png',
					'title' => 'Feature is Recommended but Disabled / not Available',
				),
			);

			uksort( $features, 'strcasecmp' );
			foreach ( $features as $name => $arr ) {

				if ( isset( $arr['classname'] ) )
					$status_key = class_exists( $arr['classname'] ) ?
						'on' : 'off';
				elseif ( isset( $arr['status'] ) )
					$status_key = $arr['status'];
				else $status_key = '';

				if ( ! empty( $status_key ) ) {

					$purchase_url = $status_key === 'rec' && 
						! empty( $arr['purchase'] ) ?
							$arr['purchase'] : '';
	
					$td_class = empty( $arr['td_class'] ) ?
						'' : ' '.$arr['td_class'];

					$tooltip_text = $this->p->msgs->get( 'tooltip-side-'.$name, 
						array( 'text' => ( empty( $arr['tooltip'] ) ? '' : $arr['tooltip'] ),
							'class' => 'sucom_tooltip_side' ) );

					echo '<tr><td class="side'.$td_class.'">'.$tooltip_text.
					( $purchase_url ? '<a href="'.$purchase_url.'" target="_blank">' : '' ).
					( $status_key === 'rec' ? '<strong>'.$name.'</strong>' : $name ).
					( $purchase_url ? '</a>' : '' ).
					'</td><td style="min-width:0;text-align:center;" class="'.$td_class.'">'.
					( $purchase_url ? '<a href="'.$purchase_url.'" target="_blank">' : '' ).
					'<img src="'.NGFB_URLPATH.'images/'.
						$status_info[$status_key]['img'].'" width="12" height="12" title="'.
						$status_info[$status_key]['title'].'"/>'.
					( $purchase_url ? '</a>' : '' ).
					'</td></tr>'."\n";
				}
			}
		}

		public function show_metabox_purchase() {
			$purchase_url = $this->p->cf['plugin'][$this->p->cf['lca']]['url']['purchase'];
			echo '<table class="sucom-setting '.$this->p->cf['lca'].'" side><tr><td>';
			echo $this->p->msgs->get( 'side-purchase' );
			echo '<p class="centered">';
			echo $this->form->get_button( ( $this->p->is_avail['aop'] ? 
				_x( 'Purchase Pro License(s)', 'submit button', 'nextgen-facebook' ) :
				_x( 'Purchase Pro Version', 'submit button', 'nextgen-facebook' ) ), 
					'button-primary', null, $purchase_url, true );
			echo '</p></td></tr></table>';
		}

		public function show_metabox_help() {
			echo '<table class="sucom-setting '.$this->p->cf['lca'].'" side><tr><td>';
			//echo $this->p->msgs->get( 'side-help' );
			$this->show_follow_icons();
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {
				if ( empty( $info['version'] ) )	// filter out extensions that are not installed
					continue;

				$help_links = '';
				$aop = $this->p->check->aop( $ext, true, $this->p->is_avail['aop'] );
				if ( ! empty( $info['url']['faq'] ) ) {
					$help_links .= '<li>'.sprintf( __( 'Review the <a href="%s" target="_blank">Frequently Asked Questions</a>',
						'nextgen-facebook' ), $info['url']['faq'] );
					if ( ! empty( $info['url']['notes'] ) )
						$help_links .= ' '.sprintf( __( 'and <a href="%s" target="_blank">Other Notes</a>',
							'nextgen-facebook' ), $info['url']['notes'] );
					$help_links .= '</li>';
				}
				if ( ! empty( $info['url']['pro_support'] ) && $aop )
					$help_links .= '<li>'.sprintf( __( 'Open a <a href="%s" target="_blank">Support Ticket</a>',
						'nextgen-facebook' ), $info['url']['pro_support'] ).'</li>';
				elseif ( ! empty( $info['url']['wp_support'] ) )
					$help_links .= '<li>'.sprintf( __( 'Post in <a href="%s" target="_blank">Support Forum</a>',
						'nextgen-facebook' ), $info['url']['wp_support'] ).'</li>';

				if ( ! empty( $help_links ) ) {
					echo '<p><strong>'.sprintf( _x( '%s Support', 
						'metabox title (side)', 'nextgen-facebook' ), $info['short'].self::$is_suffix ).'</strong></p>';
					echo '<ul>'.$help_links.'</ul>';
				}
			}
			echo '</td></tr></table>';
		}

		protected function show_follow_icons() {
			echo '<div class="follow_icons">';
			$img_size = $this->p->cf['follow']['size'];
			foreach ( $this->p->cf['follow']['src'] as $img_rel => $url )
				echo '<a href="'.$url.'" target="_blank"><img src="'.NGFB_URLPATH.$img_rel.'" 
					width="'.$img_size.'" height="'.$img_size.'" border="0" /></a> ';
			echo '</div>';
		}

		public static function get_nonce() {
			return ( defined( 'NONCE_KEY' ) ? NONCE_KEY : '' ).plugin_basename( __FILE__ );
		}

		private function is_profile( $menu_id = false ) {
			return $this->is_lib( 'profile', $menu_id );
		}

		private function is_setting( $menu_id = false ) {
			return $this->is_lib( 'setting', $menu_id );
		}

		private function is_submenu( $menu_id = false ) {
			return $this->is_lib( 'submenu', $menu_id );
		}

		private function is_sitesubmenu( $menu_id = false ) {
			return $this->is_lib( 'sitesubmenu', $menu_id );
		}

		private function is_lib( $lib_name, $menu_id = false ) {
			if ( $menu_id === false )
				$menu_id = $this->menu_id;
			return isset( $this->p->cf['*']['lib'][$lib_name][$menu_id] ) ? true : false;
		}

		public function licenses_metabox_content( $network = false ) {
			echo '<table class="sucom-setting '.$this->p->cf['lca'].' licenses-metabox"
				style="padding-bottom:10px">'."\n";
			echo '<tr><td colspan="'.( $network ? 5 : 4 ).'">'.
				$this->p->msgs->get( 'info-plugin-tid'.
					( $network ? '-network' : '' ) ).'</td></tr>'."\n";

			$num = 0;
			$total = count( $this->p->cf['plugin'] );
			foreach ( $this->p->cf['plugin'] as $ext => $info ) {
				$num++;
				$links = '';
				$img_href = '';
				$view_text = _x( 'View Plugin Details', 'plugin action link', 'nextgen-facebook' );

				if ( ! empty( $info['slug'] ) && 
					( empty( $info['url']['latest_zip'] ) ||
						$this->p->is_avail['util']['um'] ) ) {

					$img_href = add_query_arg( array(
						'tab' => 'plugin-information',
						'plugin' => $info['slug'],
						'TB_iframe' => 'true',
						'width' => 600,
						'height' => 550
					), get_admin_url( null, 'plugin-install.php' ) );

					// check to see if plugin is installed or not
					if ( is_dir( WP_PLUGIN_DIR.'/'.$info['slug'] ) ) {
						$update_plugins = get_site_transient('update_plugins');
						if ( isset( $update_plugins->response ) ) {
							foreach ( (array) $update_plugins->response as $file => $plugin ) {
								if ( $plugin->slug === $info['slug'] ) {
									$view_text = '<font color="red">'._x( 'View Plugin Details + Update',
										'plugin action link', 'nextgen-facebook' ).'</font>';
									break;
								}
							}
						}
					} else $view_text = _x( 'View Plugin Details + Install', 'plugin action link', 'nextgen-facebook' );

					$links .= ' | <a href="'.$img_href.'" class="thickbox">'.$view_text.'</a>';

				} elseif ( ! empty( $info['url']['download'] ) ) {
					$img_href = $info['url']['download'];
					$links .= ' | <a href="'.$img_href.'" target="_blank">'._x( 'Plugin Description Page',
						'plugin action link', 'nextgen-facebook' ).'</a>';
				}

				if ( ! empty( $info['url']['latest_zip'] ) )
					$links .= ' | <a href="'.$info['url']['latest_zip'].'">'._x( 'Download Latest Version',
						'plugin action link', 'nextgen-facebook' ).'</a> (ZIP)';

				if ( ! empty( $info['url']['purchase'] ) ) {
					if ( $this->p->cf['lca'] === $ext || 
						$this->p->check->aop( $this->p->cf['lca'], false, $this->p->is_avail['aop'] ) )
							$links .= ' | <a href="'.$info['url']['purchase'].'" target="_blank">'.
								_x( 'Purchase Pro License(s)', 'plugin action link', 'nextgen-facebook' ).'</a>';
					else $links .= ' | <em>'._x( 'Purchase Pro License(s)', 'plugin action link', 'nextgen-facebook' ).'</em>';
				}

				if ( ! empty( $info['img']['icon_small'] ) )
					$img_src = 'src="'.$info['img']['icon_small'].'"';
				else $img_src = 'src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="';

				if ( ! empty( $info['img']['icon_medium'] ) )
					$img_src .= ' srcset="'.$info['img']['icon_medium'].' 256w"';

				// logo image
				echo '<tr><td style="width:148px; padding:10px;" rowspan="3" valign="top" align="left">'."\n";
				if ( ! empty( $img_href ) ) 
					echo '<a href="'.$img_href.'"'.( strpos( $img_href, 'TB_iframe' ) ?
						' class="thickbox"' : ' target="_blank"' ).'>';
				echo '<img '.$img_src.' width="128" height="128">';
				if ( ! empty( $img_href ) ) 
					echo '</a>';
				echo '</td>'."\n";

				// plugin name
				echo '<td colspan="'.( $network ? 4 : 3 ).'" style="padding:10px 0 0 0;">
					<p><strong>'.$info['name'].'</strong></p>';

				if ( ! empty( $info['desc'] ) )
					echo '<p>'._x( $info['desc'], 'plugin description', 'nextgen-facebook' ).'</p>';

				if ( ! empty( $links ) )
					echo '<p>'.trim( $links, ' |' ).'</p>';

				echo '</td></tr>'."\n";

				if ( $network ) {
					if ( ! empty( $info['update_auth'] ) || 
						! empty( $this->p->options['plugin_'.$ext.'_tid'] ) ) {

						if ( $this->p->cf['lca'] === $ext || 
							$this->p->check->aop( $this->p->cf['lca'], 
								true, $this->p->is_avail['aop'] ) ) {

							echo '<tr>'.$this->p->util->get_th( _x( 'Pro Authentication ID',
								'option label', 'nextgen-facebook' ), 'medium nowrap' ).
							'<td class="tid">'.$this->form->get_input( 'plugin_'.$ext.'_tid', 'tid mono' ).'</td>'.
							$this->p->admin->get_site_use( $this->form, true, 'plugin_'.$ext.'_tid' );
						} else {
							echo '<tr>'.$this->p->util->get_th( _x( 'Pro Authentication ID',
								'option label', 'nextgen-facebook' ), 'medium nowrap' ).
							'<td class="blank">'.( empty( $this->p->options['plugin_'.$ext.'_tid'] ) ?
								$this->form->get_no_input( 'plugin_'.$ext.'_tid', 'tid mono' ) :
								$this->form->get_input( 'plugin_'.$ext.'_tid', 'tid mono' ) ).
							'</td><td colspan="2">'.( $this->p->check->aop( $this->p->cf['lca'], 
								true, $this->p->is_avail['aop'] ) ?
									'' : $this->p->msgs->get( 'pro-option-msg' ) ).'</td></tr>'."\n";
						}
					} else echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>'."\n";
				} else {
					if ( ! empty( $info['update_auth'] ) || 
						! empty( $this->p->options['plugin_'.$ext.'_tid'] ) ) {

						if ( $this->p->cf['lca'] === $ext || 
							$this->p->check->aop( $this->p->cf['lca'], 
								true, $this->p->is_avail['aop'] ) ) {

							$qty_used = class_exists( 'SucomUpdate' ) ?
								SucomUpdate::get_option( $ext, 'qty_used' ) : false;

							echo '<tr>'.$this->p->util->get_th( _x( 'Pro Authentication ID',
								'option label', 'nextgen-facebook' ), 'medium nowrap' ).
							'<td class="tid">'.$this->form->get_input( 'plugin_'.$ext.'_tid', 'tid mono' ).
							'</td><td><p>'.( empty( $qty_used ) ? 
								'' : $qty_used.' Licenses Assigned' ).'</p></td></tr>'."\n";
						} else {
							echo '<tr>'.$this->p->util->get_th( _x( 'Pro Authentication ID',
								'option label', 'nextgen-facebook' ), 'medium nowrap' ).
							'<td class="blank">'.( empty( $this->p->options['plugin_'.$ext.'_tid'] ) ?
								$this->form->get_no_input( 'plugin_'.$ext.'_tid', 'tid mono' ) :
								$this->form->get_input( 'plugin_'.$ext.'_tid', 'tid mono' ) ).
							'</td><td>'.( $this->p->check->aop( $this->p->cf['lca'], 
								true, $this->p->is_avail['aop'] ) ? 
									'' : $this->p->msgs->get( 'pro-option-msg' ) ).'</td></tr>'."\n";
						}
					} else echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</tr>'."\n";
				}

				echo '<tr><td'.( $num < $total ? ' style="border-bottom:1px dotted #ddd;"' : '' ).
					' colspan="'.( $network ? 4 : 3 ).'">&nbsp;</td></tr>'."\n";
			}
			echo '</table>'."\n";
		}

		public function conflict_warnings() {
			if ( ! is_admin() ) 	// just in case
				return;

			$lca = $this->p->cf['lca'];
			$base = $this->p->cf['plugin'][$lca]['base'];
			$short = $this->p->cf['plugin'][$lca]['short'];
			$short_pro = $short.' Pro';
			$purchase_url = $this->p->cf['plugin'][$lca]['url']['purchase'];
			$err_pre =  __( 'Plugin conflict detected', 'nextgen-facebook' ) . ' - ';
			$log_pre = 'plugin conflict detected - ';	// don't translate the debug 

			// PHP
			if ( empty( $this->p->is_avail['curl'] ) ) {
				if ( ! empty( $this->p->options['plugin_shortener'] ) && 
					$this->p->options['plugin_shortener'] !== 'none' ) {

					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'url shortening is enabled but curl function is missing' );
					$this->p->notice->err( sprintf( __( 'URL shortening has been enabled, but PHP\'s <a href="%s" target="_blank">Client URL Library</a> (cURL) is missing.', 'nextgen-facebook' ), 'http://ca3.php.net/curl' ).' '.__( 'Please contact your hosting provider to have the missing cURL library files installed.', 'nextgen-facebook' ) );
				} elseif ( ! empty( $this->p->options['plugin_file_cache_exp'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'file caching is enabled but curl function is missing' );
					$this->p->notice->err( sprintf( __( 'The file caching feature has been enabled but PHP\'s <a href="%s" target="_blank">Client URL Library</a> (cURL) is missing.', 'nextgen-facebook' ), 'http://ca3.php.net/curl' ).' '.__( 'Please contact your hosting provider to have the missing library installed.', 'nextgen-facebook' ) );
				}
			}
			if ( empty( $this->p->is_avail['mbstring'] ) ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->log( 'php mbstring extension is missing' );
				$this->p->notice->err( sprintf( __( 'PHP\'s <a href="%s" target="_blank">Multibyte String</a> extension is missing.', 'nextgen-facebook' ), 'http://php.net/manual/en/book.mbstring.php' ).' '.__( 'Please contact your hosting provider to have the missing extension installed.', 'nextgen-facebook' ) );
			}

			// Yoast SEO
			if ( $this->p->is_avail['seo']['wpseo'] ) {
				$opts = get_option( 'wpseo_social' );
				if ( ! empty( $opts['opengraph'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( $log_pre.'wpseo opengraph meta data option is enabled' );
					$this->p->notice->err( $err_pre.sprintf( __( 'Please uncheck the \'<em>Add Open Graph meta data</em>\' option under the <a href="%s">Yoast SEO / Social / Facebook</a> settings tab.', 'nextgen-facebook' ), get_admin_url( null, 'admin.php?page=wpseo_social#top#facebook' ) ) );
				}
				if ( ! empty( $opts['twitter'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( $log_pre.'wpseo twitter meta data option is enabled' );
					$this->p->notice->err( $err_pre.sprintf( __( 'Please uncheck the \'<em>Add Twitter card meta data</em>\' option under the <a href="%s">Yoast SEO / Social / Twitter</a> settings tab.', 'nextgen-facebook' ), get_admin_url( null, 'admin.php?page=wpseo_social#top#twitterbox' ) ) );
				}
				if ( ! empty( $opts['googleplus'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( $log_pre.'wpseo googleplus meta data option is enabled' );
					$this->p->notice->err( $err_pre.sprintf( __( 'Please uncheck the \'<em>Add Google+ specific post meta data</em>\' option under the <a href="%s">Yoast SEO / Social / Google+</a> settings tab.', 'nextgen-facebook' ), get_admin_url( null, 'admin.php?page=wpseo_social#top#google' ) ) );
				}
				if ( ! empty( $opts['plus-publisher'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( $log_pre.'wpseo google plus publisher option is defined' );
					$this->p->notice->err( $err_pre.sprintf( __( 'Please remove the \'<em>Google Publisher Page</em>\' value entered under the <a href="%s">Yoast SEO / Social / Google+</a> settings tab.', 'nextgen-facebook' ), get_admin_url( null, 'admin.php?page=wpseo_social#top#google' ) ) );
				}
			}

			// SEO Ultimate
			if ( $this->p->is_avail['seo']['seou'] === true ) {
				$opts = get_option( 'seo_ultimate' );
				if ( ! empty( $opts['modules'] ) && is_array( $opts['modules'] ) ) {
					if ( array_key_exists( 'opengraph', $opts['modules'] ) && $opts['modules']['opengraph'] !== -10 ) {
						if ( $this->p->debug->enabled )
							$this->p->debug->log( $log_pre.'seo ultimate opengraph module is enabled' );
						$this->p->notice->err( $err_pre.sprintf( __( 'Please disable the \'<em>Open Graph Integrator</em>\' module in the <a href="%s">SEO Ultimate Module Manager</a>.', 'nextgen-facebook' ), get_admin_url( null, 'admin.php?page=seo' ) ) );
					}
				}
			}

			// All in One SEO Pack
			if ( $this->p->is_avail['seo']['aioseop'] === true ) {
				$opts = get_option( 'aioseop_options' );
				if ( ! empty( $opts['modules']['aiosp_feature_manager_options']['aiosp_feature_manager_enable_opengraph'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( $log_pre.'aioseop social meta fetaure is enabled' );
					$this->p->notice->err( $err_pre.sprintf( __( 'Please deactivate the \'<em>Social Meta</em>\' feature in the <a href="%s">All in One SEO Pack Feature Manager</a>.', 'nextgen-facebook' ), get_admin_url( null, 'admin.php?page=all-in-one-seo-pack/aioseop_feature_manager.php' ) ) );
				}
				if ( array_key_exists( 'aiosp_google_disable_profile', $opts ) && empty( $opts['aiosp_google_disable_profile'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( $log_pre.'aioseop google plus profile is enabled' );
					$this->p->notice->err( $err_pre.sprintf( __( 'Please check the \'<em>Disable Google Plus Profile</em>\' option in the <a href="%s">All in One SEO Pack Plugin Options</a>.', 'nextgen-facebook' ), get_admin_url( null, 'admin.php?page=all-in-one-seo-pack/aioseop_class.php' ) ) );
				}
			}
		}

		public function check_tmpl_head_elements() {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			// only check if using the default filter name
			if ( empty( $this->p->options['plugin_head_attr_filter_name'] ) ||
				$this->p->options['plugin_head_attr_filter_name'] !== 'head_attributes' )
					return;

			$headers = glob( get_stylesheet_directory().'/header*.php' );
			foreach ( $headers as $file ) {
				if ( ( $html = SucomUtil::get_stripped_php( $file ) ) === false )
					continue;

				if ( strpos( $html, '<head>' ) !== false ) {
					$this->p->notice->err( $this->p->msgs->get( 'notice-header-tmpl-no-head-attr' ),
						true, true, 'notice-header-tmpl-no-head-attr', false );
					break;
				}
			}
		}

		public function modify_tmpl_head_elements() {

			$have_changes = false;
			$headers = glob( get_stylesheet_directory().'/header*.php' );

			foreach ( $headers as $file ) {
				$base = basename( $file );
				$backup = $file.'~backup-'.date( 'Ymd-His' );

				// double check in case of reloads etc.
				if ( ( $html = SucomUtil::get_stripped_php( $file ) ) === false ||
					strpos( $html, '<head>' ) === false ) {
					$this->p->notice->err( sprintf( __( '&lt;head&gt; element not found in %s.',
						'nextgen-facebook' ), $file ), true );
					continue;
				}

				// make a backup of the original
				if ( ! copy( $file, $backup ) ) {
					$this->p->notice->err( sprintf( __( 'Error copying %1$s to %2$s.',
						'nextgen-facebook' ), 'header.php', $backup ), true );
					continue;
				}

				$php = file_get_contents( $file );
				$php = str_replace( '<head>', '<head <?php do_action( \'add_head_attributes\' ); ?>>', $php );

				if ( ! $fh = @fopen( $file, 'wb' ) ) {
					$this->p->notice->err( sprintf( __( 'Failed to open file %s for writing.',
						'nextgen-facebook' ), $file ), true );
					continue;
				}
				
				if ( fwrite( $fh, $php ) ) {
					fclose( $fh );
					$this->p->notice->inf( sprintf( __( 'The %1$s template has been successfully updated and saved. A backup copy of the original template is available in %2$s.', 'nextgen-facebook' ), $base, $backup ), true );
					$have_changes = true;
				}
			}
			if ( $have_changes === true )
				$this->p->notice->trunc_id( 'notice-header-tmpl-no-head-attr', 'all' );	// just in case
		}

		// dismiss an incorrect yoast seo conflict notification
		public function dismiss_wpseo_notice( $dismissed, $opt_name, $user_obj ) {
			$lca = $this->p->cf['lca'];
			$base = $this->p->cf['plugin'][$lca]['base'];
			if ( ! is_array( $dismissed['open_graph'] ) ||
				! in_array( $base, $dismissed['open_graph'] ) )
					$dismissed['open_graph'][] = $base;
			return $dismissed;
		}

		public function get_site_use( &$form, $network = false, $name ) {
			if ( $network !== true )
				return '';
			return $this->p->util->get_th( _x( 'Site Use',
				'option label (very short)', 'nextgen-facebook' ), 'site_use' ).
			( $this->p->check->aop( $this->p->cf['lca'], true, $this->p->is_avail['aop'] ) ?
				'<td>'.$form->get_select( $name.':use', $this->p->cf['form']['site_option_use'], 'site_use' ).'</td>' :
				'<td class="site_use blank">'.$form->get_select( $name.':use', 
					$this->p->cf['form']['site_option_use'], 'site_use', null, true, true ).'</td>' );
		}
	}
}

?>
