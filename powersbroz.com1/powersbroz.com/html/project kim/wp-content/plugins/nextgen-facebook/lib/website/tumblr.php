<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharingTumblr' ) && class_exists( 'NgfbSubmenuSharing' ) ) {

	class NgfbSubmenuSharingTumblr extends NgfbSubmenuSharing {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->website_id = $id;
			$this->website_name = $name;
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			$this->p->util->add_plugin_filters( $this, array( 
				'image-dimensions_general_rows' => 2,
			) );
		}

		// add an option to the WordPress -> Settings -> Image Dimensions page
		public function filter_image_dimensions_general_rows( $rows, $form ) {

			$rows[] = $this->p->util->get_th( _x( 'Tumblr <em>Sharing Button</em>',
				'option label', 'nextgen-facebook' ), null, 'tumblr_img_dimensions',
			'The image dimensions that the Tumblr button will share (defaults is '.$this->p->opt->get_defaults( 'tumblr_img_width' ).'x'.$this->p->opt->get_defaults( 'tumblr_img_height' ).' '.( $this->p->opt->get_defaults( 'tumblr_img_crop' ) == 0 ? 'un' : '' ).'cropped). Note that original images in the WordPress Media Library and/or NextGEN Gallery must be larger than your chosen image dimensions.' ).
			'<td>'.$form->get_image_dimensions_input( 'tumblr_img' ).'</td>';

			return $rows;
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();
			$buttons_html = '<div class="btn_wizard_row clearfix" id="button_styles">';
			$buttons_style = empty( $this->p->options['tumblr_button_style'] ) ? 
				'share_1' : $this->p->options['tumblr_button_style'];
			foreach ( range( 1, 4 ) as $i ) {
				$buttons_html .= '<div class="btn_wizard_column share_'.$i.'">';
				foreach ( array( '', 'T' ) as $t ) {
					$buttons_html .= '
						<div class="btn_wizard_example clearfix">
						<label for="share_'.$i.$t.'">
						<input type="radio" id="share_'.$i.$t.'" name="'.$this->form->options_name.'[tumblr_button_style]" value="share_'.$i.$t.'" '.  checked( 'share_'.$i.$t, $buttons_style, false ).'/>
						<img src="'.$this->p->util->get_cache_file_url( 'http://platform.tumblr.com/v1/share_'.$i.$t.'.png' ).'" height="20" class="share_button_image"/>
						</label>
						</div>
					';
				}
				$buttons_html .= '</div>';
			}
			$buttons_html .= '</div>';

			$rows[] = $this->p->util->get_th( _x( 'Show Button in',
				'option label (short)', 'nextgen-facebook' ), 'short', null, 'The Tumblr button shares a custom Image ID (in the Social Settings metabox), a featured image, or an attached image, that is equal to or larger than the \'Image Dimensions\' you have chosen (when the <em>Use Attached Image</em> option is checked), embedded video, the content of <em>quote</em> custom Posts, or simply shares the webpage link.' ).'<td>'.
			( $this->show_on_checkboxes( 'tumblr' ) ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Preferred Order',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'tumblr_order', 
				range( 1, count( $this->p->admin->submenu['sharing']->website ) ), 
					'short' ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'JavaScript in',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'tumblr_script_loc', $this->p->cf['form']['script_locations'] ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Button Style',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
				'<td class="btn_wizard">'.$buttons_html.'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Image Dimensions',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_image_dimensions_input( 'tumblr_img', false, true ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Media Caption',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'tumblr_caption', $this->p->cf['form']['caption_types'] ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Caption Length',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_input( 'tumblr_cap_len', 'short' ).' '.
				_x( 'characters or less', 'option comment', 'nextgen-facebook' ).'</td>';
	
			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Link Description',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_input( 'tumblr_desc_len', 'short' ).' '.
				_x( 'characters or less', 'option comment', 'nextgen-facebook' ).'</td>';

			return $rows;
		}
	}
}

if ( ! class_exists( 'NgfbSharingTumblr' ) ) {

	class NgfbSharingTumblr {

		private static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'tumblr_on_content' => 0,
					'tumblr_on_excerpt' => 0,
					'tumblr_on_sidebar' => 0,
					'tumblr_on_admin_edit' => 1,
					'tumblr_order' => 10,
					'tumblr_script_loc' => 'footer',
					'tumblr_button_style' => 'share_1',
					'tumblr_desc_len' => 300,
					'tumblr_img_width' => 600,
					'tumblr_img_height' => 600,
					'tumblr_img_crop' => 0,
					'tumblr_img_crop_x' => 'center',
					'tumblr_img_crop_y' => 'center',
					'tumblr_caption' => 'excerpt',
					'tumblr_cap_len' => 400,
				),
			),
		);

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 
				'plugin_image_sizes' => 1,
				'get_defaults' => 1,
			) );
		}

		public function filter_plugin_image_sizes( $sizes ) {
			$sizes['tumblr_img'] = array(
				'name' => 'tumblr-button',
				'label' => _x( 'Tumblr Sharing Button',
					'image size label', 'nextgen-facebook' ),
			);
			return $sizes;
		}

		public function filter_get_defaults( $opts_def ) {
			return array_merge( $opts_def, self::$cf['opt']['defaults'] );
		}

		public function get_html( $atts = array(), &$opts = array() ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			$lca = $this->p->cf['lca'];
			$prot = empty( $_SERVER['HTTPS'] ) ?
				'http:' : 'https:';
			$use_post = isset( $atts['use_post'] ) ?
				$atts['use_post'] : true;
			$src_id = $this->p->util->get_source_id( 'tumblr', $atts );
			$atts['add_page'] = isset( $atts['add_page'] ) ?
				$atts['add_page'] : true;	// get_sharing_url argument
			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $src_id ) : 
				apply_filters( $lca.'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $src_id );

			$post_id = 0;
			if ( is_singular() || $use_post !== false ) {
				if ( ( $obj = $this->p->util->get_post_object( $use_post ) ) === false ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'exiting early: invalid object type' );
					return false;
				}
				$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
			}

			if ( empty( $atts['size'] ) ) 
				$atts['size'] = $lca.'-tumblr-button';

			if ( ! empty( $atts['pid'] ) )
				list(
					$atts['photo'],
					$atts['width'],
					$atts['height'],
					$atts['cropped']
				) = $this->p->media->get_attachment_image_src( $atts['pid'], $atts['size'], false );

			if ( empty( $atts['photo'] ) && empty( $atts['embed'] ) ) {
				list( $img_url, $vid_url ) = $this->p->og->get_the_media_urls( $atts['size'], $post_id, 'og' );
				if ( empty( $atts['photo'] ) )
					$atts['photo'] = $img_url;
				if ( empty( $atts['embed'] ) )
					$atts['embed'] = $vid_url;
			}

			// if no image or video, then check for a 'quote'
			if ( empty( $atts['photo'] ) && empty( $atts['embed'] ) && empty( $atts['quote'] ) && $post_id > 0 ) {
				if ( get_post_format( $post_id ) === 'quote' ) 
					$atts['quote'] = $this->p->webpage->get_quote();
			}

			// we only need the caption, title, or description for some types of shares
			if ( ! empty( $atts['photo'] ) || ! empty( $atts['embed'] ) ) {
				// html encode param is false to use url encoding instead
				if ( empty( $atts['caption'] ) ) 
					$atts['caption'] = $this->p->webpage->get_caption(
						$opts['tumblr_caption'],	// title, excerpt, both
						$opts['tumblr_cap_len'],	// max caption length
						$use_post,			//
						true,				// use_cache
						true,				// add_hashtags
						false,				// encode is false for later url encoding)
						( ! empty( $atts['photo'] ) ? 
							'tumblr_img_desc' : 'tumblr_vid_desc' ),
						$src_id
					);

			} else {
				if ( empty( $atts['title'] ) ) 
					$atts['title'] = $this->p->webpage->get_title(
						null,				// max length
						null,				// trailing
						$use_post,			//
						true,				// use_cache
						false,				// add_hashtags
						false,				// encode (false for later url encoding)
						null,				// metadata key
						$src_id
					);
				if ( empty( $atts['description'] ) ) 
					$atts['description'] = $this->p->webpage->get_description(
						$opts['tumblr_desc_len'],	// max length
						'...',				// trailing
						$use_post,			//
						true,				// use_cache
						true,				// add_hashtags
						false,				// encode (false for later url encoding)
						null,				// metadata key
						$src_id
					);
			}

			// define the button, based on what we have
			$query = '';
			if ( ! empty( $atts['photo'] ) ) {
				$query .= 'photo?source='. urlencode( $atts['photo'] );
				$query .= '&amp;clickthru='.urlencode( $atts['url'] );
				$query .= '&amp;caption='.urlencode( $atts['caption'] );
			} elseif ( ! empty( $atts['embed'] ) ) {
				$query .= 'video?embed='.urlencode( $atts['embed'] );
				$query .= '&amp;caption='.urlencode( $atts['caption'] );
			} elseif ( ! empty( $atts['quote'] ) ) {
				$query .= 'quote?quote='.urlencode( $atts['quote'] );
				$query .= '&amp;source='.urlencode( $atts['title'] );
			} elseif ( ! empty( $atts['url'] ) ) {
				$query .= 'link?url='.urlencode( $atts['url'] );
				$query .= '&amp;name='.urlencode( $atts['title'] );
				$query .= '&amp;description='.urlencode( $atts['description'] );
			}
			if ( empty( $query ) ) return;

			$html = '<!-- Tumblr Button --><div '.$this->p->sharing->get_css( 'tumblr', $atts ).'>';
			$html .= '<a href="http://www.tumblr.com/share/'. $query.'" title="Share on Tumblr">';
			$html .= '<img border="0" alt="Share on Tumblr" src="'.
				$this->p->util->get_cache_file_url( $prot.'//platform.tumblr.com/v1/'.$opts['tumblr_button_style'].'.png' ).'" /></a></div>';

			if ( $this->p->debug->enabled )
				$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html."\n";
		}

		// the tumblr host does not have a valid SSL cert, and it's javascript does not work in async mode
		public function get_script( $pos = 'id' ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_file_url( apply_filters( $this->p->cf['lca'].'_js_url_tumblr',
				$prot.'//platform.tumblr.com/v1/share.js', $pos ) );

			return '<script type="text/javascript" id="tumblr-script-'.$pos.'" src="'.$js_url.'"></script>'."\n";
		}
	}
}

?>
