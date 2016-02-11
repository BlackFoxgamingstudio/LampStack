<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbMeta' ) ) {

	/*
	 * This class is extended by NgfbPost, NgfbUser, and NgfbTaxonomy.
	 */
	class NgfbMeta {

		protected $p;
		protected $form;
		protected $opts = array();	// cache for options
		protected $defs = array();	// cache for default values

		protected static $head_meta_tags = array();
		protected static $head_meta_info = array();

		protected function get_default_tabs() {
			$tabs = array();
			foreach( array(
				'preview' => _x( 'Social Preview', 'metabox tab', 'nextgen-facebook' ),
				'header' => _x( 'Descriptions', 'metabox tab', 'nextgen-facebook' ),
				'media' => _x( 'Priority Media', 'metabox tab', 'nextgen-facebook' ),
				'tags' => _x( 'Head Tags', 'metabox tab', 'nextgen-facebook' ),
				'validate' => _x( 'Validate', 'metabox tab', 'nextgen-facebook' ),
			) as $key => $name ) {
				if ( isset( $this->p->options['plugin_add_tab_'.$key] ) ) {
					if ( ! empty( $this->p->options['plugin_add_tab_'.$key] ) )
						$tabs[$key] = $name;
				} else $tabs[$key] = $name;
			}
			return $tabs;
		}

		protected function get_rows( $metabox, $key, &$head_info ) {
			$rows = array();
			switch ( $key ) {
				case 'preview':
					$rows = $this->get_rows_social_preview( $this->form, $head_info );
					break;

				case 'tags':	
					$rows = $this->get_rows_head_tags( $this->form, $head_info );
					break; 

				case 'validate':
					$rows = $this->get_rows_validate( $this->form, $head_info );
					break; 

			}
			return $rows;
		}

		public function get_rows_social_preview( &$form, &$head_info ) {
			$rows = array();
			$prev_width = 600;
			$prev_height = 315;
			$div_style = 'width:'.$prev_width.'px; height:'.$prev_height.'px;';
			$have_sizes = ( ! empty( $head_info['og:image:width'] ) &&
				$head_info['og:image:width'] > 0 && 
					! empty( $head_info['og:image:height'] ) &&
						$head_info['og:image:height'] > 0 ) ? true : false;
			$is_sufficient = ( $have_sizes === true && 
				$head_info['og:image:width'] >= $prev_width && 
				$head_info['og:image:height'] >= $prev_height ) ? true : false;

			foreach ( array( 'og:image:secure_url', 'og:image' ) as $img_url ) {
				if ( ! empty( $head_info[$img_url] ) ) {
					if ( $have_sizes === true ) {
						$image_preview_html = '<div class="preview_img" style="'.$div_style.' 
						background-size:'.( $is_sufficient === true ? 
							'cover' : $head_info['og:image:width'].' '.$head_info['og:image:height'] ).'; 
						background-image:url('.$head_info[$img_url].');" />'.( $is_sufficient === true ? 
							'' : '<p>'.sprintf( _x( 'Image Dimensions Smaller<br/>than Suggested Minimum<br/>of %s',
								'preview image error', 'nextgen-facebook' ),
									$prev_width.'x'.$prev_height.'px' ).'</p>' ).'</div>';
					} else {
						$image_preview_html = '<div class="preview_img" style="'.$div_style.' 
						background-image:url('.$head_info[$img_url].');" /><p>'.
						_x( 'Image Dimensions Unknown<br/>or Not Available',
							'preview image error', 'nextgen-facebook' ).'</p></div>';
					}
					break;	// stop after first image
				}
			}

			if ( empty( $image_preview_html ) )
				$image_preview_html = '<div class="preview_img" style="'.$div_style.'"><p>'.
				_x( 'No Open Graph Image Found', 'preview image error', 'nextgen-facebook' ).'</p></div>';

			$long_url = $this->p->util->get_sharing_url( $head_info['post_id'] );
			$short_url = apply_filters( $this->p->cf['lca'].'_shorten_url',
				$long_url, $this->p->options['plugin_shortener'] );

			if ( $long_url === $short_url &&
				SucomUtil::is_post_page() )
					$short_url = wp_get_shortlink();

			$rows[] = $this->p->util->get_th( _x( 'Short URL',
				'option label', 'nextgen-facebook' ), 'medium' ).
			'<td>'.$form->get_copy_input( $short_url ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Sharing URL',
				'option label', 'nextgen-facebook' ), 'medium' ).
			'<td>'.$form->get_copy_input( $long_url ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Open Graph Example',
				'option label', 'nextgen-facebook' ), 'medium' ).
			'<td rowspan="2" style="background-color:#e9eaed;border:1px dotted #e0e0e0;">
			<div class="preview_box" style="width:'.( $prev_width + 40 ).'px;">
				<div class="preview_box" style="width:'.$prev_width.'px;">
					'.$image_preview_html.'
					<div class="preview_txt">
						<div class="preview_title">'.( empty( $head_info['og:title'] ) ?
							'No Title' : $head_info['og:title'] ).'</div>
						<div class="preview_desc">'.( empty( $head_info['og:description'] ) ?
							'No Description' : $head_info['og:description'] ).'</div>
						<div class="preview_by">'.( $_SERVER['SERVER_NAME'].( empty( $head_info['author'] )
							? '' : ' | By '.$head_info['author'] ) ).'</div>
					</div>
				</div>
			</div></td>';

			$rows[] = '<th class="medium textinfo">'.$this->p->msgs->get( 'info-meta-social-preview' ).'</th>';

			return $rows;
		}

		public function get_rows_head_tags( &$form, &$head_info ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			$rows = array();
			$xtra_class = '';
			foreach ( NgfbMeta::$head_meta_tags as $parts ) {
				if ( count( $parts ) === 1 ) {
					if ( strpos( $parts[0], '<script ' ) === 0 )
						$xtra_class = 'script';
					elseif ( strpos( $parts[0], '<noscript ' ) === 0 )
						$xtra_class = 'noscript';
					$rows[] = '<td colspan="5" class="html '.$xtra_class.'"><pre>'.
						esc_html( $parts[0] ).'</pre></td>';
					if ( $xtra_class === 'script' ||
						strpos( $parts[0], '</noscript>' ) === 0 )
							$xtra_class = '';
				} elseif ( isset( $parts[5] ) && $parts[5] != -1 ) {
					$rows[] = '<tr class="'.$xtra_class.' '.
						( empty( $parts[0] ) ? 'is_disabled' : 'is_enabled' ).'">'.
					'<th class="xshort">'.$parts[1].'</th>'.
					'<th class="xshort">'.$parts[2].'</th>'.
					'<td class="short">'.( empty( $parts[6] ) ? 
						'' : '<!-- '.$parts[6].' -->' ).$parts[3].'</td>'.
					'<th class="xshort">'.$parts[4].'</th>'.
					'<td class="wide">'.( strpos( $parts[5], 'http' ) === 0 ? 
						'<a href="'.$parts[5].'">'.$parts[5].'</a>' : $parts[5] ).'</td>';
				}
			}
			return $rows;
		}

		public function get_rows_validate( &$form, &$head_info ) {
			$rows = array();

			$sharing_url = $this->p->util->get_sharing_url( $head_info['post_id'] );
			$sharing_urlenc = urlencode( $sharing_url );

			$rows[] = $this->p->util->get_th( _x( 'Facebook Debugger', 'option label', 'nextgen-facebook' ) ).'<td class="validate"><p>'.__( 'Facebook, Pinterest, LinkedIn, Google+, and most social websites use Open Graph meta tags.', 'nextgen-facebook' ).' '.__( 'The Facebook debugger allows you to refresh Facebook\'s cache, while also validating the Open Graph and Pinterest Rich Pin meta tags.', 'nextgen-facebook' ).' '.__( 'The Facebook debugger remains the most stable and reliable method to verify Open Graph meta tags. <strong>You may have to click the "Fetch new scrape information" button several times to refresh Facebook\'s cache</strong>.', 'nextgen-facebook' ).'</p></td><td class="validate">'.$form->get_button( _x( 'Validate Open Graph', 'submit button', 'nextgen-facebook' ), 'button-secondary', null, 'https://developers.facebook.com/tools/debug/og/object?q='.$sharing_urlenc, true ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Google Structured Data Testing Tool', 'option label', 'nextgen-facebook' ) ).'<td class="validate"><p>'.__( 'Verify that Google can correctly parse your structured data markup (meta tags, Schema, Microdata, and social JSON-LD markup) for Google Search and Google+.', 'nextgen-facebook' ).'</p></td><td class="validate">'.$form->get_button( _x( 'Validate Data Markup', 'submit button', 'nextgen-facebook' ), 'button-secondary', null, 'https://developers.google.com/structured-data/testing-tool/?url='.$sharing_urlenc, true ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Pinterest Rich Pin Validator', 'option label', 'nextgen-facebook' ) ).'<td class="validate"><p>'.__( 'Validate the Open Graph / Rich Pin meta tags and apply to have them shown on Pinterest zoomed pins.', 'nextgen-facebook' ).'</p></td><td class="validate">'.$form->get_button( _x( 'Validate Rich Pins', 'submit button', 'nextgen-facebook' ), 'button-secondary', null, 'https://developers.pinterest.com/tools/url-debugger/?link='.$sharing_urlenc, true ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Twitter Card Validator', 'option label', 'nextgen-facebook' ) ).'<td class="validate"><p>'.__( 'The Twitter Card Validator does not accept query arguments &ndash; copy-paste the following sharing URL into the validation input field.', 'nextgen-facebook' ).'</p><p>'.$form->get_copy_input( $sharing_url ).'</p></td><td class="validate">'.$form->get_button( _x( 'Validate Twitter Card', 'submit button', 'nextgen-facebook' ), 'button-secondary', null, 'https://dev.twitter.com/docs/cards/validation/validator', true ).'</td>';

			return $rows;
		}

		public function reset_options_filter( $id ) {
			if ( isset( $this->opts[$id]['options_filtered'] ) )
				$this->opts[$id]['options_filtered'] = false;
		}

		public function get_options( $id, $idx = false, $attr = array() ) {
			return $this->not_implemented( __METHOD__,
				( $idx === false ? array() : false ) );
		}

		public function get_defaults( $idx = false, $mod = false ) {
			if ( ! isset( $this->defs['options_filtered'] ) || 
				$this->defs['options_filtered'] !== true ) {
				$this->defs = array(
					'options_filtered' => '',
					'options_version' => '',
					'og_art_section' => -1,
					'og_title' => '',
					'og_desc' => '',
					'schema_desc' => '',
					'seo_desc' => '',
					'tc_desc' => '',
					'pin_desc' => '',
					'sharing_url' => '',
					'og_img_width' => '',
					'og_img_height' => '',
					'og_img_crop' => ( empty( $this->p->options['og_img_crop'] ) ? 0 : 1 ),
					'og_img_crop_x' => ( empty( $this->p->options['og_img_crop_x'] ) ?
						'center' : $this->p->options['og_img_crop_x'] ),
					'og_img_crop_y' => ( empty( $this->p->options['og_img_crop_y'] ) ?
						'center' : $this->p->options['og_img_crop_y'] ),
					'og_img_id' => '',
					'og_img_id_pre' => ( empty( $this->p->options['og_def_img_id_pre'] ) ?
						'' : $this->p->options['og_def_img_id_pre'] ),
					'og_img_url' => '',
					'og_vid_url' => '',
					'og_vid_embed' => '',
					'og_img_max' => -1,
					'og_vid_max' => -1,
					'og_vid_prev_img' => ( empty( $this->p->options['og_vid_prev_img'] ) ? 0 : 1 ),
					'rp_img_width' => '',
					'rp_img_height' => '',
					'rp_img_crop' => ( empty( $this->p->options['rp_img_crop'] ) ? 0 : 1 ),
					'rp_img_crop_x' => ( empty( $this->p->options['rp_img_crop_x'] ) ?
						'center' : $this->p->options['rp_img_crop_x'] ),
					'rp_img_crop_y' => ( empty( $this->p->options['rp_img_crop_y'] ) ?
						'center' : $this->p->options['rp_img_crop_y'] ),
					'rp_img_id' => '',
					'rp_img_id_pre' => ( empty( $this->p->options['og_def_img_id_pre'] ) ?
						'' : $this->p->options['og_def_img_id_pre'] ),
					'rp_img_url' => '',
				);
				if ( $mod !== false )
					$this->defs = apply_filters( $this->p->cf['lca'].'_get_meta_defaults', $this->defs, $mod );
				$this->defs['options_filtered'] = true;
			}
			if ( $idx !== false ) {
				if ( isset( $this->defs[$idx] ) )
					return $this->defs[$idx];
				else return null;
			} else return $this->defs;
		}

		public function save_options( $id, $rel_id = false ) {
			return $this->not_implemented( __METHOD__, $id );
		}

		public function clear_cache( $id, $rel_id = false ) {
			// nothing to do
			return $id;
		}

		public function delete_options( $id, $rel_id = false ) {
			return $this->not_implemented( __METHOD__, $id );
		}

		protected function not_implemented( $method, $ret = true ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->log( $method.' not implemented in free version',
					get_class( $this ) );	// log the extended class name
			return $ret;
		}
	
		protected function verify_submit_nonce() {
			if ( empty( $_POST[ NGFB_NONCE ] ) ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->log( 'nonce token missing from submitted POST' );
				return false;
			} elseif ( ! wp_verify_nonce( $_POST[ NGFB_NONCE ], NgfbAdmin::get_nonce() ) ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->log( 'nonce token validation failed' );
				if ( is_admin() )
					$this->p->notice->err( __( 'Nonce token validation failed for the submitted form (update ignored).',
						'nextgen-facebook' ), true );
				return false;
			} else return true;
		}

		protected function get_submit_opts( $id, $mod = false ) {
			$defs = $this->get_defaults( false, $mod );
			unset ( $defs['options_filtered'] );
			unset ( $defs['options_version'] );

			$prev = $this->get_options( $id );
			unset ( $prev['options_filtered'] );
			unset ( $prev['options_version'] );

			$opts = empty( $_POST[ NGFB_META_NAME ] ) ? 
				array() : $_POST[ NGFB_META_NAME ];
			$opts = SucomUtil::restore_checkboxes( $opts );
			$opts = array_merge( $prev, $opts );
			$opts = $this->p->opt->sanitize( $opts, $defs, false, $mod );	// network is false

			if ( $mod !== false )
				$opts = apply_filters( $this->p->cf['lca'].'_save_meta_options', $opts, $mod, $id );

			foreach ( $defs as $key => $def_val )
				if ( isset( $opts[$key] ) )
					if ( $opts[$key] === '' || $opts[$key] == -1 )
						unset ( $opts[$key] );

			if ( $opts['og_vid_prev_img'] === $this->p->options['og_vid_prev_img'] )
				unset ( $opts['og_vid_prev_img'] );

			if ( empty( $opts['buttons_disabled'] ) )
				unset ( $opts['buttons_disabled'] );

			foreach ( array( 'rp', 'og' ) as $md_pre ) {
				if ( empty( $opts[$md_pre.'_img_id'] ) )
					unset ( $opts[$md_pre.'_img_id_pre'] );

				$force_regen = false;
				foreach ( array( 'width', 'height', 'crop', 'crop_x', 'crop_y' ) as $key ) {
					// if option is the same as the default, then unset it
					if ( isset( $opts[$md_pre.'_img_'.$key] ) &&
						isset( $defs[$md_pre.'_img_'.$key] ) &&
							$opts[$md_pre.'_img_'.$key] === $defs[$md_pre.'_img_'.$key] )
								unset( $opts[$md_pre.'_img_'.$key] );

					if ( $mod !== false ) {
						if ( ! empty( $this->p->options['plugin_auto_img_resize'] ) ) {
							$check_current = isset( $opts[$md_pre.'_img_'.$key] ) ?
								$opts[$md_pre.'_img_'.$key] : '';
							$check_previous = isset( $prev[$md_pre.'_img_'.$key] ) ?
								$prev[$md_pre.'_img_'.$key] : '';
							if ( $check_current !== $check_previous )
								$force_regen = true;
						}
					}
				}
				if ( $force_regen === true )
					set_transient( $this->p->cf['lca'].'_'.$mod.'_'.$id.'_regen_'.$md_pre, true );
			}
			return $opts;
		}

		public function add_column_headings( $columns ) { 
			return array_merge( $columns, array(
				$this->p->cf['lca'].'_og_image' => _x( 'Social Img', 'column title', 'nextgen-facebook' ),
				$this->p->cf['lca'].'_og_desc' => _x( 'Social Desc', 'column title', 'nextgen-facebook' )
			) );
		}

		protected function get_mod_column_content( $value, $column_name, $id, $mod = '' ) {

			// optimize performance and return immediately if this is not our column
			if ( strpos( $column_name, $this->p->cf['lca'].'_' ) !== 0 )
				return $value;

			// when adding a new category, $screen_id may be false
			$screen_id = SucomUtil::get_screen_id();
			if ( ! empty( $screen_id ) ) {
				$hidden = get_user_option( 'manage'.$screen_id.'columnshidden' );
				if ( is_array( $hidden ) && 
					in_array( $column_name, $hidden ) )
						return __( 'Reload to View', 'nextgen-facebook' );
			}

			switch ( $column_name ) {
				case $this->p->cf['lca'].'_og_image':
				case $this->p->cf['lca'].'_og_desc':
					$use_cache = true;
					break;
				default:
					$use_cache = false;
					break;
			}

			if ( $use_cache === true && $this->p->is_avail['cache']['transient'] ) {
				$lang = SucomUtil::get_locale();
				$cache_salt = __METHOD__.'(lang:'.$lang.'_id:'.$id.'_mod:'.$mod.'_column:'.$column_name.')';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				$value = get_transient( $cache_id );
				if ( $value !== false )
					return $value;
			}

			switch ( $column_name ) {
				case $this->p->cf['lca'].'_og_image':
					// set custom image dimensions for this post/term/user id
					$this->p->util->add_plugin_image_sizes( $id, array(), true, $mod );
					break;
			}

			$value = apply_filters( $column_name.'_'.$mod.'_column_content', $value, $column_name, $id, $mod  );

			if ( $use_cache === true && $this->p->is_avail['cache']['transient'] )
				set_transient( $cache_id, $value, $this->p->options['plugin_object_cache_exp'] );

			return $value;
		}

		public function get_og_image_column_html( $og_image ) {
			$value = '';
			// try and get a smaller thumbnail version if we can
			if ( isset( $og_image['og:image:id'] ) && 
				$og_image['og:image:id'] > 0 )
					list(
						$og_image['og:image'],
						$og_image['og:image:width'],
						$og_image['og:image:height'],
						$og_image['og:image:cropped'],
						$og_image['og:image:id']
					) = $this->p->media->get_attachment_image_src( $og_image['og:image:id'], 'thumbnail', false, false );

			if ( ! empty( $og_image['og:image'] ) )
				$value .= '<div class="preview_img" style="background-image:url('.$og_image['og:image'].');"></div>';
			return $value;
		}

		public function get_og_image( $num = 0, $size_name = 'thumbnail', $id,
			$check_dupes = true, $force_regen = false, $md_pre = 'og' ) {

			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			return $this->get_meta_image( $num, $size_name, $id,
				$check_dupes, $force_regen, $md_pre, 'og' );
		}

		public function get_meta_image( $num = 0, $size_name = 'thumbnail', $id,
			$check_dupes = true, $force_regen = false, $md_pre = 'og', $mt_pre = 'og' ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->args( array( 
					'num' => $num,
					'size_name' => $size_name,
					'id' => $id,
					'check_dupes' => $check_dupes,
					'force_regen' => $force_regen,
					'md_pre' => $md_pre,
					'mt_pre' => $mt_pre,
				), get_class( $this ) );
			}
			$meta_ret = array();
			$meta_image = SucomUtil::meta_image_tags( $mt_pre );

			if ( empty( $id ) )
				return $meta_ret;

			foreach( array_unique( array( $md_pre, 'og' ) ) as $prefix ) {

				$pid = $this->get_options( $id, $prefix.'_img_id' );
				$pre = $this->get_options( $id, $prefix.'_img_id_pre' );
				$url = $this->get_options( $id, $prefix.'_img_url' );

				if ( $pid > 0 ) {
					$pid = $pre === 'ngg' ? 'ngg-'.$pid : $pid;
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'using custom '.$prefix.' image id = "'.$pid.'"',
							get_class( $this ) );	// log extended class name
					list( 
						$meta_image[$mt_pre.':image'],
						$meta_image[$mt_pre.':image:width'],
						$meta_image[$mt_pre.':image:height'],
						$meta_image[$mt_pre.':image:cropped'],
						$meta_image[$mt_pre.':image:id']
					) = $this->p->media->get_attachment_image_src( $pid, $size_name, $check_dupes, $force_regen );
				}

				if ( empty( $meta_image[$mt_pre.':image'] ) && ! empty( $url ) ) {

					$width = $this->get_options( $id, $prefix.'_img_url:width' );
					$height = $this->get_options( $id, $prefix.'_img_url:height' );

					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'using custom '.$prefix.' image url = "'.$url.'"',
							get_class( $this ) );	// log extended class name

					list(
						$meta_image[$mt_pre.':image'],
						$meta_image[$mt_pre.':image:width'],
						$meta_image[$mt_pre.':image:height'],
						$meta_image[$mt_pre.':image:cropped'],
						$meta_image[$mt_pre.':image:id']
					) = array(
						$url,
						( $width > 0 ? $width : -1 ), 
						( $height > 0 ? $height : -1 ), 
						-1,
						-1
					);
				}

				if ( ! empty( $meta_image[$mt_pre.':image'] ) &&
					$this->p->util->push_max( $meta_ret, $meta_image, $num ) )
						return $meta_ret;
			}
			return $meta_ret;
		}

		public function get_og_video( $num = 0, $id, $check_dupes = false, $md_pre = 'og', $mt_pre = 'og' ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->args( array( 
					'num' => $num,
					'id' => $id,
					'check_dupes' => $check_dupes,
					'md_pre' => $md_pre,
					'mt_pre' => $mt_pre,
				), get_class( $this ) );
			}

			$og_ret = array();
			$og_video = array();

			if ( empty( $id ) )
				return $og_ret;

			foreach( array_unique( array( $md_pre, 'og' ) ) as $prefix ) {

				$html = $this->get_options( $id, $prefix.'_vid_embed' );
				$url = $this->get_options( $id, $prefix.'_vid_url' );

				if ( ! empty( $html ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'fetching video(s) from custom '.$prefix.' embed code',
							get_class( $this ) );	// log extended class name
					$og_video = $this->p->media->get_content_videos( $num, 0, $check_dupes, $html );
					if ( ! empty( $og_video ) )
						return array_merge( $og_ret, $og_video );
				}

				if ( ! empty( $url ) && ( $check_dupes == false || $this->p->util->is_uniq_url( $url ) ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'fetching video from custom '.$prefix.' url '.$url,
							get_class( $this ) );	// log extended class name
					$og_video = $this->p->media->get_video_info( $url, 0, 0, $check_dupes );
					if ( empty( $og_video ) )	// fallback to the original custom video URL
						$og_video['og:video:url'] = $url;
					if ( $this->p->util->push_max( $og_ret, $og_video, $num ) ) 
						return $og_ret;
				}
			}
			return $og_ret;
		}

		public function get_og_video_preview_image( $id, $mod, $check_dupes = false, $md_pre = 'og' ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->args( array( 
					'id' => $id,
					'mod' => $mod,
					'check_dupes' => $check_dupes,
					'md_pre' => $md_pre,
				), get_class( $this ) );
			}

			$og_image = array();

			// fallback to value from general plugin settings
			if ( ( $use_prev_img = $this->get_options( $id, 'og_vid_prev_img' ) ) === null )
				$use_prev_img = $this->p->options['og_vid_prev_img'];

			// get video preview images if allowed
			if ( ! empty( $use_prev_img ) ) {
				// assumes the first video will have a preview image
				$og_video = $this->p->og->get_all_videos( 1, $id, $mod, $check_dupes, $md_pre );
				if ( ! empty( $og_video ) && is_array( $og_video ) ) {
					foreach ( $og_video as $video ) {
						if ( ! empty( $video['og:image'] ) ) {
							$og_image[] = $video;
							break;
						}
					}
				}
			} elseif ( $this->p->debug->enabled )
				$this->p->debug->log( 'use_prev_img is 0 - skipping retrieval of video preview image' );

			return $og_image;
		}
	}
}

?>
