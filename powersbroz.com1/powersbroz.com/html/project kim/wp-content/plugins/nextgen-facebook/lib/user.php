<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbUser' ) ) {

	/*
	 * This class is extended by gpl/util/user.php or pro/util/user.php
	 * and the class object is created as $this->p->mods['util']['user'].
	 */
	class NgfbUser extends NgfbMeta {

		protected static $pref = array();

		protected function add_actions() {

			add_filter( 'user_contactmethods', 
				array( &$this, 'add_contact_methods' ), 20, 2 );

			$this->p->util->add_plugin_filters( $this, 
				array( 'json_http_schema_org_person' => 6 ) );

			if ( is_admin() ) {
				/**
				 * Hook a minimum number of admin actions to maximize performance.
				 * The user_id argument is always present when we're editing a user,
				 * but missing when viewing our own profile page.
				 */

				// common to your profile and user editing pages
				add_action( 'admin_init', array( &$this, 'add_metaboxes' ) );

				// load_meta_page() priorities: 100 post, 200 user, 300 taxonomy
				add_action( 'current_screen', array( &$this, 'load_meta_page' ), 200, 1 );

				// the social settings metabox has moved to its own settings page
				//add_action( 'show_user_profile', array( &$this, 'show_metabox_section' ), 20 );

				if ( ! empty( $this->p->options['plugin_columns_user'] ) ) {

					add_filter( 'manage_users_columns', 
						array( $this, 'add_column_headings' ), 10, 1 );
					add_filter( 'manage_users_custom_column', 
						array( $this, 'get_user_column_content',), 10, 3 );
	
					$this->p->util->add_plugin_filters( $this, array( 
						'og_image_user_column_content' => 4,
						'og_desc_user_column_content' => 4,
					) );
				}

				// exit here if not a user or profile page
				$user_id = SucomUtil::get_req_val( 'user_id' );
				if ( empty( $user_id ) )
					return;

				// hooks for user and profile editing
				add_action( 'edit_user_profile', array( &$this, 'show_metabox_section' ), 20 );

				add_action( 'edit_user_profile_update', array( &$this, 'sanitize_submit_cm' ), 5 );
				add_action( 'edit_user_profile_update', array( &$this, 'save_options' ), NGFB_META_SAVE_PRIORITY );
				add_action( 'edit_user_profile_update', array( &$this, 'clear_cache' ), NGFB_META_CACHE_PRIORITY );

				add_action( 'personal_options_update', array( &$this, 'sanitize_submit_cm' ), 5 ); 
				add_action( 'personal_options_update', array( &$this, 'save_options' ), NGFB_META_SAVE_PRIORITY ); 
				add_action( 'personal_options_update', array( &$this, 'clear_cache' ), NGFB_META_CACHE_PRIORITY ); 
			}
		}

		public function get_user_column_content( $value, $column_name, $id ) {
			return $this->get_mod_column_content( $value, $column_name, $id, 'user' );
		}

		public function filter_og_image_user_column_content( $value, $column_name, $id, $mod ) {
			if ( ! empty( $value ) )
				return $value;

			// use the open graph image dimensions to reject images that are too small
			$size_name = $this->p->cf['lca'].'-opengraph';
			$check_dupes = false;	// using first image we find, so dupe checking is useless
			$force_regen = false;
			$md_pre = 'og';
			$og_image = array();

			if ( empty( $og_image ) )
				$og_image = $this->get_og_video_preview_image( $id, $mod, $check_dupes, $md_pre );

			if ( empty( $og_image ) )
				$og_image = $this->get_og_image( 1, $size_name, $id, $check_dupes, $force_regen, $md_pre );

			if ( empty( $og_image ) )
				$og_image = $this->p->media->get_default_image( 1, $size_name, $check_dupes, $force_regen );

			if ( ! empty( $og_image ) && is_array( $og_image ) ) {
				$image = reset( $og_image );
				if ( ! empty( $image['og:image'] ) )
					$value = $this->get_og_image_column_html( $image );
			}

			return $value;
		}

		public function filter_og_desc_user_column_content( $value, $column_name, $id, $mod ) {
			if ( ! empty( $value ) )
				return $value;

			$author = get_userdata( $id );
			if ( empty( $author->ID ) )
				return $value;

			$value = $this->p->util->get_mod_options( 'user', $author->ID, 'og_desc' );

			if ( empty( $value ) ) {
				if ( ! empty( $author->description ) )
					$value = $author->description;
				elseif ( ! empty( $author->display_name ) )
					$value = sprintf( 'Authored by %s', $author->display_name );
			}

			return $value;
		}

		// hooked into the current_screen action
		public function load_meta_page( $screen = false ) {

			// all meta modules set this property, so use it to optimize code execution
			if ( ! empty( NgfbMeta::$head_meta_tags ) 
				|| ! isset( $screen->id ) )
					return;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
				$this->p->debug->log( 'screen id: '.$screen->id );
			}

			$lca = $this->p->cf['lca'];
			switch ( $screen->id ) {
				case 'profile':
				case 'user-edit':
				case ( strpos( $screen->id, 'profile_page_' ) === 0 ? true : false ):
				case ( strpos( $screen->id, 'users_page_' ) === 0 ? true : false ):
					break;
				default:
					return;
					break;
			}

			$user_id = $this->p->util->get_author_object( 'id' );
			$add_metabox = empty( $this->p->options[ 'plugin_add_to_user' ] ) ? false : true;

			if ( apply_filters( $this->p->cf['lca'].'_add_metabox_user', 
				$add_metabox, $user_id, $screen->id ) === true ) {

				do_action( $this->p->cf['lca'].'_admin_user_header', $user_id, $screen->id );

				// use_post is false since this isn't a post
				// read_cache is false to generate notices etc.
				NgfbMeta::$head_meta_tags = $this->p->head->get_header_array( false, false );
				NgfbMeta::$head_meta_info = $this->p->head->extract_head_info( NgfbMeta::$head_meta_tags );

				if ( empty( NgfbMeta::$head_meta_info['og:image'] ) )
					// check for missing open graph image and issue warning
					$this->p->notice->err( $this->p->msgs->get( 'notice-missing-og-image' ) );
			}

			$action_query = $lca.'-action';
			if ( ! empty( $_GET[$action_query] ) ) {
				$action_name = SucomUtil::sanitize_hookname( $_GET[$action_query] );
				if ( empty( $_GET[ NGFB_NONCE ] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'nonce token validation query field missing' );
				} elseif ( ! wp_verify_nonce( $_GET[ NGFB_NONCE ], NgfbAdmin::get_nonce() ) ) {
					$this->p->notice->err( __( 'Nonce token validation failed for action \"'.$action_name.'\".', 'nextgen-facebook' ) );
				} else {
					$_SERVER['REQUEST_URI'] = remove_query_arg( array( $action_query, NGFB_NONCE ) );
					switch ( $action_name ) {
						default: 
							do_action( $lca.'_load_meta_page_user_'.$action_name, $user_id, $screen->id );
							break;
					}
				}
			}
		}

		public function add_metaboxes() {
			$user_id = $this->p->util->get_author_object( 'id' );
			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->log( 'insufficient privileges to add metabox for user ID '.$user_id );
				return;
			}
			$add_metabox = empty( $this->p->options[ 'plugin_add_to_user' ] ) ? false : true;
			if ( apply_filters( $this->p->cf['lca'].'_add_metabox_user', $add_metabox ) === true )
				add_meta_box( NGFB_META_NAME, _x( 'Social Settings', 'metabox title', 'nextgen-facebook' ),
					array( &$this, 'show_metabox_user' ), 'user', 'normal', 'low' );
		}

		public function show_metabox_section( $user ) {
			if ( ! current_user_can( 'edit_user', $user->ID ) )
				return;

			$lca = $this->p->cf['lca'];
			$is_suffix = ' '.( $this->p->check->aop( $lca, 
				true, $this->p->is_avail['aop'] ) ? 
					_x( 'Pro', 'package type', 'wpsso' ) :
					_x( 'Free', 'package type', 'wpsso' ) );

			echo '<h3 id="'.$lca.'-metaboxes">'.
				$this->p->cf['plugin'][$lca]['name'].
				$is_suffix.'</h3>'."\n";
			echo '<div id="poststuff">';
			do_meta_boxes( 'user', 'normal', $user );
			echo '</div>'."\n";
		}

		public function show_metabox_user( $user ) {
			$opts = $this->get_options( $user->ID );
			$def_opts = $this->get_defaults();
			NgfbMeta::$head_meta_info['post_id'] = false;

			$this->form = new SucomForm( $this->p, NGFB_META_NAME, $opts, $def_opts );
			wp_nonce_field( NgfbAdmin::get_nonce(), NGFB_NONCE );

			$metabox = 'user';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs',
				$this->get_default_tabs(), $user );
			if ( empty( $this->p->is_avail['mt'] ) )
				unset( $tabs['tags'] );

			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key, NgfbMeta::$head_meta_info ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', 
						array(), $this->form, NgfbMeta::$head_meta_info ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		public function get_display_names() {
			$user_ids = array();
			foreach ( get_users() as $user ) 
				$user_ids[$user->ID] = $user->display_name;
			$user_ids[0] = 'none';
			return $user_ids;
		}

		public function get_contact_fields( $fields = array() ) { 
			return array_merge( 
				array( 'none' => '[none]' ), 	// make sure none is first
				$this->add_contact_methods( 
					array( 
						'author' => 'Author Index', 
						'url' => 'Website'
					)
				)
			);
		}

		public function add_contact_methods( $fields = array(), $user = null ) { 
			// loop through each social website option prefix
			if ( ! empty( $this->p->cf['opt']['pre'] ) && 
				is_array( $this->p->cf['opt']['pre'] ) ) {

				foreach ( $this->p->cf['opt']['pre'] as $id => $pre ) {
					$cm_opt = 'plugin_cm_'.$pre.'_';
					// not all social websites have a contact fields, so check
					if ( array_key_exists( $cm_opt.'name', $this->p->options ) ) {
						$enabled = $this->p->options[$cm_opt.'enabled'];
						$name = $this->p->options[$cm_opt.'name'];
						$label = $this->p->options[$cm_opt.'label'];
						if ( ! empty( $enabled ) && 
							! empty( $name ) && 
							! empty( $label ) )
								$fields[$name] = $label;
					}
				}
			}
			if ( $this->p->check->aop() && 
				! empty( $this->p->cf['wp']['cm'] ) && 
				is_array( $this->p->cf['wp']['cm'] ) ) {

				foreach ( $this->p->cf['wp']['cm'] as $id => $name ) {
					$cm_opt = 'wp_cm_'.$id.'_';
					if ( array_key_exists( $cm_opt.'enabled', $this->p->options ) ) {
						$enabled = $this->p->options[$cm_opt.'enabled'];
						$label = $this->p->options[$cm_opt.'label'];
						if ( ! empty( $enabled ) ) {
							if ( ! empty( $label ) )
								$fields[$id] = $label;
						} else unset( $fields[$id] );
					}
				}
			}
			ksort( $fields, SORT_STRING );
			return $fields;
		}

		public function sanitize_submit_cm( $user_id ) {
			if ( ! current_user_can( 'edit_user', $user_id ) )
				return;

			foreach ( $this->p->cf['opt']['pre'] as $id => $pre ) {
				$cm_opt = 'plugin_cm_'.$pre.'_';
				// not all social websites have contact fields, so check
				if ( array_key_exists( $cm_opt.'name', $this->p->options ) ) {

					$enabled = $this->p->options[$cm_opt.'enabled'];
					$name = $this->p->options[$cm_opt.'name'];
					$label = $this->p->options[$cm_opt.'label'];

					if ( isset( $_POST[$name] ) && 
						! empty( $enabled ) && 
						! empty( $name ) && 
						! empty( $label ) ) {

						// sanitize values only for those enabled contact methods
						$val = wp_filter_nohtml_kses( $_POST[$name] );
						if ( ! empty( $val ) ) {
							switch ( $name ) {
								case $this->p->options['plugin_cm_skype_name']:
									// no change
									break;
								case $this->p->options['plugin_cm_twitter_name']:
									$val = substr( preg_replace( '/[^a-zA-Z0-9_]/', '', $val ), 0, 15 );
									if ( ! empty( $val ) ) 
										$val = '@'.$val;
									break;
								default:
									// all other contact methods are assumed to be URLs
									if ( strpos( $val, '://' ) === false )
										$val = '';
									break;
							}
						}
						$_POST[$name] = $val;
					}
				}
			}
			return $user_id;
		}

		// provides backwards compatibility for wp 3.0
		public static function get_user_id_contact_methods( $user_id ) {
			$user = get_user_by( 'id', $user_id );
			if ( function_exists( 'wp_get_user_contact_methods' ) )	// since wp 3.7
				return wp_get_user_contact_methods( $user );
			else {
				$methods = array();
				if ( get_site_option( 'initial_db_version' ) < 23588 ) {
					$methods = array(
						'aim'    => __( 'AIM' ),
						'yim'    => __( 'Yahoo IM' ),
						'jabber' => __( 'Jabber / Google Talk' )
					); 
				}
				return apply_filters( 'user_contactmethods', $methods, $user );
			}
		}

		public function filter_json_http_schema_org_person( $json, $use_post, $obj, $mt_og, $post_id, $author_id ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			if ( empty( $this->p->options['schema_author_json'] ) ||
				empty( $author_id ) )
					return $json;

			// only add the person json if the author has a website url
			$author_website_url = get_the_author_meta( 'url', $author_id );
			if ( strpos( $author_website_url, '://' ) === false )
				return false;

			$size_name = $this->p->cf['lca'].'-schema';
			$og_image = $this->get_og_image( 1, $size_name, $author_id, false );
			$json = '{
	"@context":"http://schema.org",
	"@type":"Person",
	"name":"'.$this->get_author_name( $author_id, 'fullname' )."\",\n".
	( strpos( $author_website_url, '://' ) === false ? 
		'' : "\t\"url\":\"".$author_website_url."\",\n" ).
	$this->p->schema->get_json_image_list( 'image', $og_image, 'og:image' ).
	"\t\"sameAs\":[\n";
			$url_list = '';
			foreach ( self::get_user_id_contact_methods( $author_id ) as $id => $label ) {
				$url = trim( get_the_author_meta( $id, $author_id ) );
				if ( empty( $url ) )
					continue;
				if ( $id === $this->p->options['plugin_cm_twitter_name'] )
					$url = 'https://twitter.com/'.preg_replace( '/^@/', '', $url );
				if ( strpos( $url, '://' ) !== false )
					$url_list .= "\t\t\"".$url."\",\n";
			}
			return $json.rtrim( $url_list, ",\n" )."\n\t]\n}\n";
		}

		// returns the facebook profile url for an author
		// unless the pinterest crawler is detected, in which case it returns the author's name
		public function get_author_profile_url( $author_ids, $url_field = 'og_author_field' ) {
			$ret = array();
			if ( ! empty( $author_ids ) ) {
				if ( ! is_array( $author_ids ) )
					$author_ids = array( $author_ids );
				foreach ( $author_ids as $author_id ) {
					if ( ! empty( $author_id ) ) {
						if ( SucomUtil::crawler_name( 'pinterest' ) === true )
							$ret[] = $this->get_author_name( $author_id, $this->p->options['rp_author_name'] );
						else $ret[] = $this->get_author_website_url( $author_id, $this->p->options[$url_field] );
					}
				}	
			}
			return $ret;
		}

		// called from head and opengraph classes
		public function get_author_name( $author_id, $field_id = 'display_name' ) {
			$name = '';
			switch ( $field_id ) {
				case 'none':
					break;
				case 'fullname':
					$name = trim( get_the_author_meta( 'first_name', $author_id ) ).' '.
						trim( get_the_author_meta( 'last_name', $author_id ) );
					break;
				// sanitation controls, just in case ;-)
				case 'user_login':
				case 'user_nicename':
				case 'display_name':
				case 'nickname':
				case 'first_name':
				case 'last_name':
					$name = get_the_author_meta( $field_id, $author_id );	// since wp 2.8.0 
					break;
			}
			if ( $this->p->debug->enabled )
				$this->p->debug->log( 'author_id '.$author_id.' '.$field_id.' name: '.$name );
			return $name;
		}

		// called from head and opengraph classes
		public function get_author_website_url( $author_id, $field_id = 'url' ) {
			$url = '';
			switch ( $field_id ) {
				case 'none':
					break;
				case 'index':
					$url = get_author_posts_url( $author_id );
					break;
				default:
					$url = get_the_author_meta( $field_id, $author_id );	// since wp 2.8.0 

					// if empty or not a url, then fallback to the author index page,
					// if the requested field is the opengraph or link author field
					if ( empty( $url ) || ! preg_match( '/:\/\//', $url ) ) {
						if ( $this->p->options['og_author_fallback'] && (
							$field_id === $this->p->options['og_author_field'] || 
							$field_id === $this->p->options['seo_author_field'] ) ) {

							if ( $this->p->debug->enabled )
								$this->p->debug->log( 'fetching the author index page url as fallback' );
							$url = get_author_posts_url( $author_id );
						}
					}
					break;
			}

			if ( $this->p->debug->enabled )
				$this->p->debug->log( 'author_id '.$author_id.' '.$field_id.' url: '.$url );

			return $url;
		}

		public function reset_metabox_prefs( $pagehook, $box_ids = array(), $meta_name = '', $section = '', $force = false ) {
			$user_id = get_current_user_id();	// since wp 3.0
			// define a new state to set for the box_ids given
			switch ( $meta_name ) {
				case 'order':	$meta_states = array( 'meta-box-order' ); break ;
				case 'hidden':	$meta_states = array( 'metaboxhidden' ); break ;
				case 'closed':	$meta_states = array( 'closedpostboxes' ); break ;
				default: $meta_states = array( 'meta-box-order', 'metaboxhidden', 'closedpostboxes' ); break;
			}
			foreach ( $meta_states as $state ) {
				// define the meta_key for that option
				$meta_key = $state.'_'.$pagehook; 
				// an empty box_ids array means reset the whole page
				if ( $force && empty( $box_ids ) )
					delete_user_option( $user_id, $meta_key, true );
				$is_changed = false;
				$is_default = false;
				$opts = get_user_option( $meta_key, $user_id );
				if ( ! is_array( $opts ) ) {
					$is_changed = true;
					$is_default = true;
					$opts = array();
				}
				if ( $is_default || $force ) {
					foreach ( $box_ids as $id ) {
						// change the order only if forced (default is controlled by add_meta_box() order)
						if ( $force && $state == 'meta-box-order' && ! empty( $opts[$section] ) ) {
							// don't proceed if the metabox is already first
							if ( strpos( $opts[$section], $pagehook.'_'.$id ) !== 0 ) {
								$boxes = explode( ',', $opts[$section] );
								// remove the box, no matter its position in the array
								if ( $key = array_search( $pagehook.'_'.$id, $boxes ) !== false )
									unset( $boxes[$key] );
								// assume we want to be top-most
								array_unshift( $boxes, $pagehook.'_'.$id );
								$opts[$section] = implode( ',', $boxes );
								$is_changed = true;
							}
						} else {
							// check to see if the metabox is present for that state
							$key = array_search( $pagehook.'_'.$id, $opts );

							// if we're not targetting , then clear it
							if ( empty( $meta_name ) && $key !== false ) {
								unset( $opts[$key] );
								$is_changed = true;
							// otherwise if we want a state, add if it's missing
							} elseif ( ! empty( $meta_name ) && $key === false ) {
								$opts[] = $pagehook.'_'.$id;
								$is_changed = true;
							}
						}
					}
				}
				if ( $is_default || $is_changed )
					update_user_option( $user_id, $meta_key, array_unique( $opts ), true );
			}
		}

		public static function delete_metabox_prefs( $user_id = false ) {
			$user_id = $user_id === false ? 
				get_current_user_id() : $user_id;
			$cf = NgfbConfig::get_config( false, true );

			$parent_slug = 'options-general.php';
			foreach ( array_keys( $cf['*']['lib']['setting'] ) as $id ) {
				$menu_slug = $cf['lca'].'-'.$id;
				self::delete_metabox_pagehook( $user_id, $menu_slug, $parent_slug );
			}

			$parent_slug = $cf['lca'].'-'.key( $cf['*']['lib']['submenu'] );
			foreach ( array_keys( $cf['*']['lib']['submenu'] ) as $id ) {
				$menu_slug = $cf['lca'].'-'.$id;
				self::delete_metabox_pagehook( $user_id, $menu_slug, $parent_slug );
			}
		}

		private static function delete_metabox_pagehook( $user_id, $menu_slug, $parent_slug ) {
			$pagehook = get_plugin_page_hookname( $menu_slug, $parent_slug);
			foreach ( array( 'meta-box-order', 'metaboxhidden', 'closedpostboxes' ) as $state ) {
				$meta_key = $state.'_'.$pagehook;
				if ( $user_id !== false )
					delete_user_option( $user_id, $meta_key, true );
				else foreach ( get_users( array( 'meta_key' => $meta_key ) ) as $user )
					delete_user_option( $user->ID, $meta_key, true );
			}
		}

		public static function save_pref( $prefs, $user_id = false ) {
			$user_id = $user_id === false ? 
				get_current_user_id() : $user_id;
			if ( ! current_user_can( 'edit_user', $user_id ) )
				return false;
			if ( ! is_array( $prefs ) || empty( $prefs ) )
				return false;

			$old_prefs = self::get_pref( false, $user_id );	// get all prefs for user
			$new_prefs = array_merge( $old_prefs, $prefs );

			// don't bother saving unless we have to
			if ( $old_prefs !== $new_prefs ) {
				self::$pref[$user_id] = $new_prefs;	// update the pref cache
				unset( $new_prefs['options_filtered'] );
				update_user_meta( $user_id, NGFB_PREF_NAME, $new_prefs );
				return true;
			} else return false;
		}

		public static function get_pref( $idx = false, $user_id = false ) {
			$user_id = $user_id === false ? 
				get_current_user_id() : $user_id;
			if ( ! isset( self::$pref[$user_id]['options_filtered'] ) || 
				self::$pref[$user_id]['options_filtered'] !== true ) {

				self::$pref[$user_id] = get_user_meta( $user_id, NGFB_PREF_NAME, true );
				if ( ! is_array( self::$pref[$user_id] ) )
					self::$pref[$user_id] = array();

				$ngfb = Ngfb::get_instance();
				if ( ! isset( self::$pref[$user_id]['show_opts'] ) )
					self::$pref[$user_id]['show_opts'] = $ngfb->options['plugin_show_opts'];

				self::$pref[$user_id]['options_filtered'] = true;
			}
			if ( $idx !== false ) {
				if ( isset( self::$pref[$user_id][$idx] ) ) 
					return self::$pref[$user_id][$idx];
				else return false;
			} else return self::$pref[$user_id];
		}

		public static function is_show_all( $user_id = false ) {
			return $this->show_opts( 'all', $user_id );
		}

		public static function get_show_val( $user_id = false ) {
			return $this->show_opts( false, $user_id );
		}

		// returns the value for show_opts, or return true/false if a value to compare is provided
		public static function show_opts( $compare = false, $user_id = false ) {
			$user_id = $user_id === false ? 
				get_current_user_id() : $user_id;
			$value = self::get_pref( 'show_opts' );
			if ( $compare !== false )
				return $compare === $value ? true : false;
			else return $value;
		}

		public function clear_cache( $user_id, $rel_id = false ) {
			$post_id = 0;
			$lca = $this->p->cf['lca'];
			$lang = SucomUtil::get_locale();
			$sharing_url = $this->p->util->get_sharing_url( false );
			$transients = array(
				'NgfbHead::get_header_array' => array( 
					'lang:'.$lang.'_post:'.$post_id.'_url:'.$sharing_url,
					'lang:'.$lang.'_post:'.$post_id.'_url:'.$sharing_url.'_crawler:pinterest',
				),
				'NgfbMeta::get_mod_column_content' => array( 
					'lang:'.$lang.'_id:'.$user_id.'_mod:user_column:'.$lca.'_og_image',
					'lang:'.$lang.'_id:'.$user_id.'_mod:user_column:'.$lca.'_og_desc',
				),
			);
			$transients = apply_filters( $this->p->cf['lca'].'_user_cache_transients', 
				$transients, $user_id, $lang, $sharing_url );

			$deleted = $this->p->util->clear_cache_objects( $transients );

			if ( ! empty( $this->p->options['plugin_cache_info'] ) && $deleted > 0 )
				$this->p->notice->inf( $deleted.' items removed from the WordPress object and transient caches.', true );

			return $user_id;
		}
	}
}

?>
