<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbPost' ) ) {

	/*
	 * This class is extended by gpl/util/post.php or pro/util/post.php
	 * and the class object is created as $this->p->mods['util']['post'].
	 */
	class NgfbPost extends NgfbMeta {

		protected function add_actions() {

			if ( is_admin() && SucomUtil::is_post_page() ) {

				add_action( 'add_meta_boxes', array( &$this, 'add_metaboxes' ) );
				// load_meta_page() priorities: 100 post, 200 user, 300 taxonomy
				add_action( 'current_screen', array( &$this, 'load_meta_page' ), 100, 1 );
				add_action( 'save_post', array( &$this, 'save_options' ), NGFB_META_SAVE_PRIORITY );
				add_action( 'save_post', array( &$this, 'clear_cache' ), NGFB_META_CACHE_PRIORITY );
				add_action( 'edit_attachment', array( &$this, 'save_options' ), NGFB_META_SAVE_PRIORITY );
				add_action( 'edit_attachment', array( &$this, 'clear_cache' ), NGFB_META_CACHE_PRIORITY );

				if ( ! empty( $this->p->options['plugin_shortlink'] ) )
					add_action( 'get_shortlink', array( &$this, 'get_shortlink' ), 9000, 4 );
			}

			// add the columns when doing AJAX as well, to allow Quick Edit to add the required columns
			if ( ( is_admin() || SucomUtil::get_const( 'DOING_AJAX' ) ) &&
				! empty( $this->p->options['plugin_columns_post'] ) ) {

				$ptns = $this->p->util->get_post_types( 'names' );
				if ( is_array( $ptns ) ) {
					foreach ( $ptns as $ptn ) {
						if ( apply_filters( $this->p->cf['lca'].'_columns_post_'.$ptn, true ) ) { 
							add_filter( 'manage_'.$ptn.'_posts_columns', 
								array( $this, 'add_column_headings' ), 10, 1 );
							add_action( 'manage_'.$ptn.'_posts_custom_column', 
								array( $this, 'show_post_column_content',), 10, 2 );
						}
					}
				}
				$this->p->util->add_plugin_filters( $this, array( 
					'og_image_post_column_content' => 4,
					'og_desc_post_column_content' => 4,
				) );
			}
		}

		public function get_shortlink( $shortlink, $post_id, $context, $allow_slugs ) {
			if ( isset( $this->p->options['plugin_shortener'] ) &&
				$this->p->options['plugin_shortener'] !== 'none' ) {
					$long_url = $this->p->util->get_sharing_url( $post_id );
					$short_url = apply_filters( $this->p->cf['lca'].'_shorten_url',
						$long_url, $this->p->options['plugin_shortener'] );
					if ( $long_url !== $short_url )
						$shortlink = $short_url;
			}
			return $shortlink;
		}

		public function show_post_column_content( $column_name, $id ) {
			echo $this->get_mod_column_content( '', $column_name, $id, 'post' );
		}

		public function filter_og_image_post_column_content( $value, $column_name, $id, $mod ) {
			if ( ! empty( $value ) )
				return $value;

			// use the open graph image dimensions to reject images that are too small
			$size_name = $this->p->cf['lca'].'-opengraph';
			$check_dupes = false;	// use first image we find, so dupe checking is useless
			$force_regen = false;
			$md_pre = 'og';
			$og_image = array();

			if ( empty( $og_image ) )
				$og_image = $this->get_og_video_preview_image( $id, $mod, $check_dupes, $md_pre );

			if ( empty( $og_image ) ) {
				$og_image = $this->p->og->get_all_images( 1, $size_name, $id, $check_dupes, $md_pre );
				if ( empty( $og_image ) )
					$og_image = $this->p->media->get_default_image( 1, $size_name, $check_dupes, $force_regen );
			}

			if ( ! empty( $og_image ) && is_array( $og_image ) ) {
				$image = reset( $og_image );
				if ( ! empty( $image['og:image'] ) )
					$value = $this->get_og_image_column_html( $image );
			}

			return $value;
		}

		public function filter_og_desc_post_column_content( $value, $column_name, $id, $mod ) {
			if ( ! empty( $value ) )
				return $value;

			return $this->p->webpage->get_description( $this->p->options['og_desc_len'], '...', $id );
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
				$this->p->util->log_is_functions();
			}

			$lca = $this->p->cf['lca'];
			switch ( $screen->id ) {
				case 'upload':
				case ( strpos( $screen->id, 'edit-' ) === 0 ? true : false ):	// posts list table
					return;
					break;
			}

			// make sure we have at least a post type and post status
			if ( ( $obj = $this->p->util->get_post_object() ) === false ||
				empty( $obj->post_type ) || 
					empty( $obj->post_status ) )
						return;

			$post_id = empty( $obj->ID ) ?
				0 : $obj->ID;

			if ( $obj->post_status !== 'auto-draft' ) {

				$post_type = get_post_type_object( $obj->post_type );
				$add_metabox = empty( $this->p->options[ 'plugin_add_to_'.$post_type->name ] ) ? false : true;

				if ( apply_filters( $this->p->cf['lca'].'_add_metabox_post', 
					$add_metabox, $post_id, $post_type->name ) === true ) {

					// hook used by woocommerce module to load front-end libraries and start a session
					do_action( $this->p->cf['lca'].'_admin_post_header', $post_id, $post_type->name );

					// read_cache is false to generate notices etc.
					NgfbMeta::$head_meta_tags = $this->p->head->get_header_array( $post_id, false );
					NgfbMeta::$head_meta_info = $this->p->head->extract_head_info( NgfbMeta::$head_meta_tags );

					if ( $obj->post_status === 'publish' ) {
						// check for missing open graph image and issue warning
						if ( empty( NgfbMeta::$head_meta_info['og:image'] ) )
							$this->p->notice->err( $this->p->msgs->get( 'notice-missing-og-image' ) );

						// check duplicates only when the post is available publicly and we have a valid permalink
						if ( ! empty( $this->p->options['plugin_check_head'] ) )
							$this->check_post_header( $post_id, $obj );
					}
				}
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
							do_action( $lca.'_load_meta_page_post_'.$action_name, $post_id, $obj );
							break;
					}
				}
			}
		}

		public function check_post_header( $post_id = true, &$obj = false ) {

			if ( empty( $this->p->options['plugin_check_head'] ) ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->mark( 'exiting early: plugin_check_head option not enabled');
				return $post_id;
			}

			if ( ! is_object( $obj ) &&
				( $obj = $this->p->util->get_post_object( $post_id ) ) === false ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->mark( 'exiting early: unable to determine the post_id');
				return $post_id;
			}

			// only check publicly available posts
			if ( ! isset( $obj->post_status ) || 
				$obj->post_status !== 'publish' ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->mark( 'exiting early: post_status \''.$obj->post_status.'\' not published');
				return $post_id;
			}

			// only check public post types (to avoid menu items, product variations, etc.)
			$ptns = $this->p->util->get_post_types( 'names' );
			if ( empty( $obj->post_type ) || 
				! in_array( $obj->post_type, $ptns ) ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->mark( 'exiting early: post_type \''.$obj->post_type.'\' not public' );
				return $post_id;
			}

			if ( $this->p->debug->enabled )
				$this->p->debug->mark( 'check head meta' );

			$charset = get_bloginfo( 'charset' );
			$permalink = get_permalink( $post_id );
			$permalink_html = SucomUtil::encode_emoji( htmlentities( urldecode( $permalink ), 
				ENT_QUOTES, $charset, false ) );	// double_encode = false
			$permalink_no_meta = add_query_arg( array( 'NGFB_META_TAGS_DISABLE' => 1 ), $permalink );
			$check_opts = apply_filters( $this->p->cf['lca'].'_check_head_meta_options',
				SucomUtil::preg_grep_keys( '/^add_/', $this->p->options, false, '' ), $post_id );

			if ( current_user_can( 'manage_options' ) )
				$notice_suffix = ' ('.sprintf( __( 'enabled in <a href="%s">WP / Theme Integration</a> settings', 'nextgen-facebook' ),
					$this->p->util->get_admin_url( 'advanced#sucom-tabset_plugin-tab_integration' ) ).')';
			else $notice_suffix = '';

			$this->p->notice->inf( sprintf( __( 'Checking %1$s for duplicate meta tags', 'nextgen-facebook' ), 
				'<a href="'.$permalink.'">'.$permalink_html.'</a>' ).$notice_suffix.'...', true );

			// use the permalink and have get_head_meta() remove our own meta tags
			// to avoid issues with caching plugins that ignore query arguments
			if ( ( $metas = $this->p->util->get_head_meta( $permalink, 
				'/html/head/link|/html/head/meta', true ) ) !== false ) {

				foreach( array(
					'link' => array( 'rel' ),
					'meta' => array( 'name', 'itemprop', 'property' ),
				) as $tag => $types ) {
					if ( isset( $metas[$tag] ) ) {
						foreach( $metas[$tag] as $m ) {
							foreach( $types as $t ) {
								if ( isset( $m[$t] ) && $m[$t] !== 'generator' && 
									! empty( $check_opts[$tag.'_'.$t.'_'.$m[$t]] ) )
										$this->p->notice->err( 'Possible conflict detected &mdash; your theme or another plugin is adding a <code>'.$tag.' '.$t.'="'.$m[$t].'"</code> HTML tag to the head section of this webpage.', true );
							}
						}
					}
				}
			}
			if ( $this->p->debug->enabled )
				$this->p->debug->mark( 'check head meta' );

			return $post_id;
		}

		public function add_metaboxes() {
			if ( ( $obj = $this->p->util->get_post_object() ) === false ||
				empty( $obj->post_type ) )
					return;
			$post_id = empty( $obj->ID ) ? 0 : $obj->ID;
			$post_type = get_post_type_object( $obj->post_type );
			$user_can_edit = false;		// deny by default
			switch ( $post_type->name ) {
				case 'page' :
					if ( current_user_can( 'edit_page', $post_id ) )
						$user_can_edit = true;
					break;
				default :
					if ( current_user_can( 'edit_post', $post_id ) )
						$user_can_edit = true;
					break;
			}
			if ( $user_can_edit === false ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->log( 'insufficient privileges to add metabox for '.$post_type->name.' ID '.$post_id );
				return;
			}
			$add_metabox = empty( $this->p->options[ 'plugin_add_to_'.$post_type->name ] ) ? false : true;
			if ( apply_filters( $this->p->cf['lca'].'_add_metabox_post', $add_metabox, $post_id ) === true )
				add_meta_box( NGFB_META_NAME, _x( 'Social Settings', 'metabox title', 'nextgen-facebook' ),
					array( &$this, 'show_metabox_post' ), $post_type->name, 'normal', 'low' );
		}

		public function show_metabox_post( $post ) {
			$opts = $this->get_options( $post->ID );				// sanitized when saving
			$def_opts = $this->get_defaults();
			$post_type = get_post_type_object( $post->post_type );			// since 3.0

			// save additional info about the post
			NgfbMeta::$head_meta_info['psn'] = get_post_status( $post->ID );	// post status name
			NgfbMeta::$head_meta_info['ptn'] = ucfirst( $post_type->name );		// post type name
			NgfbMeta::$head_meta_info['post_id'] = $post->ID;			// post id

			$this->form = new SucomForm( $this->p, NGFB_META_NAME, $opts, $def_opts );
			wp_nonce_field( NgfbAdmin::get_nonce(), NGFB_NONCE );

			$metabox = 'post';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs',
				$this->get_default_tabs(), $post, $post_type );
			if ( empty( $this->p->is_avail['mt'] ) )
				unset( $tabs['tags'] );

			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key, NgfbMeta::$head_meta_info ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', 
						array(), $this->form, NgfbMeta::$head_meta_info ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		protected function get_rows( $metabox, $key, &$head_info ) {
			$rows = array();
			switch ( $metabox.'-'.$key ) {
				case 'post-preview':
					if ( $head_info['psn'] === 'auto-draft' )
						$rows[] = '<td><blockquote class="status-info"><p class="centered">'.
							sprintf( __( 'Save a draft version or publish the %s to display the open graph social preview.',
								'nextgen-facebook' ), $head_info['ptn'] ).'</p></td>';
					else $rows = $this->get_rows_social_preview( $this->form, $head_info );
					break;

				case 'post-tags':	
					if ( $head_info['psn'] === 'auto-draft' )
						$rows[] = '<td><blockquote class="status-info"><p class="centered">'.
							sprintf( __( 'Save a draft version or publish the %s to display the head tags preview.',
								'nextgen-facebook' ), $head_info['ptn'] ).'</p></blockquote></td>';
					else $rows = $this->get_rows_head_tags( $this->form, $head_info );
					break; 

				case 'post-validate':
					if ( $head_info['psn'] === 'publish' ||
						$head_info['ptn'] === 'Attachment' )
							$rows = $this->get_rows_validate( $this->form, $head_info );
					else $rows[] = '<td><blockquote class="status-info"><p class="centered">'.
						sprintf( __( 'Social validation tools will be available when the %s is published with public visibility.',
							'nextgen-facebook' ), $head_info['ptn'] ).'</p></blockquote></td>';
					break; 
			}
			return $rows;
		}

		public function clear_cache( $post_id, $rel_id = false ) {
			$this->p->util->clear_post_cache( $post_id );
			return $post_id;
		}

	}
}

?>
