<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharingPinterest' ) && class_exists( 'NgfbSubmenuSharing' ) ) {

	class NgfbSubmenuSharingPinterest extends NgfbSubmenuSharing {

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

			$rows[] = $this->p->util->get_th( _x( 'Pinterest <em>Sharing Button</em>',
				'option label', 'nextgen-facebook' ), null, 'pin_img_dimensions',
			'The image dimensions that the Pinterest Pin It button will share (defaults is '.$this->p->opt->get_defaults( 'pin_img_width' ).'x'.$this->p->opt->get_defaults( 'pin_img_height' ).' '.( $this->p->opt->get_defaults( 'pin_img_crop' ) == 0 ? 'un' : '' ).'cropped). Images in the Facebook / Open Graph meta tags are usually cropped, where-as images on Pinterest often look better in their original aspect ratio (uncropped) and/or cropped using portrait photo dimensions.' ).
			'<td>'.$form->get_image_dimensions_input( 'pin_img' ).'</td>';

			return $rows;
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();

			$rows[] = $this->p->util->get_th( _x( 'Show Button in',
				'option label (short)', 'nextgen-facebook' ), 'short', null ).
			'<td>'.$this->show_on_checkboxes( 'pin' ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Preferred Order',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_select( 'pin_order', range( 1, 
				count( $this->p->admin->submenu['sharing']->website ) ), 'short' ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'JavaScript in',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_select( 'pin_script_loc', $this->p->cf['form']['script_locations'] ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Button Height',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_select( 'pin_button_height', 
				array( 'small' => 'Small', 'large' => 'Large' ) );

			$rows[] = $this->p->util->get_th( _x( 'Button Shape',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_select( 'pin_button_shape', 
				array( 'rect' => 'Rectangular', 'round' => 'Circular' ) );

			$rows[] = $this->p->util->get_th( _x( 'Button Color',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_select( 'pin_button_color', 
				array( 'gray' => 'Gray', 'red' => 'Red', 'white' => 'White' ) );

			$rows[] = $this->p->util->get_th( _x( 'Button Language',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_select( 'pin_button_lang', 
				array( 'en' => 'English', 'ja' => 'Japanese' ) );

			$rows[] = $this->p->util->get_th( _x( 'Show Pin Count',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_select( 'pin_count_layout', 
				array( 
					'none' => 'Not Shown',
					'beside' => 'Beside the Button',
					'above' => 'Above the Button',
				)
			).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Share Single Image',
				'option label (short)', 'nextgen-facebook' ), 'short', null,
			'Check this option to have the Pinterest Pin It button appear only on Posts and Pages with a custom Image ID (in the Social Settings metabox), a featured image, or an attached image, that is equal to or larger than the \'Image Dimensions\' you have chosen. <strong>By leaving this option unchecked, the Pin It button will submit the current webpage URL without a specific image</strong>, allowing Pinterest to present any number of available images for pinning.' ).
			'<td>'.$this->form->get_checkbox( 'pin_use_img' ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Image Dimensions',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_image_dimensions_input( 'pin_img', false, true ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Caption Text',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_select( 'pin_caption', $this->p->cf['form']['caption_types'] ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Caption Length',
				'option label (short)', 'nextgen-facebook' ), 'short' ).
			'<td>'.$this->form->get_input( 'pin_cap_len', 'short' ).' '.
				_x( 'characters or less', 'option comment', 'nextgen-facebook' ).'</td>';

			return $rows;
		}
	}
}

if ( ! class_exists( 'NgfbSharingPinterest' ) ) {

	class NgfbSharingPinterest {

		private static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'pin_on_content' => 0,
					'pin_on_excerpt' => 0,
					'pin_on_sidebar' => 0,
					'pin_on_admin_edit' => 1,
					'pin_order' => 4,
					'pin_script_loc' => 'footer',
					'pin_button_lang' => 'en',
					'pin_button_shape' => 'rect',
					'pin_button_color' => 'gray',
					'pin_button_height' => 'small',
					'pin_count_layout' => 'beside',
					'pin_use_img' => 1,
					'pin_img_width' => 600,
					'pin_img_height' => 600,
					'pin_img_crop' => 0,
					'pin_img_crop_x' => 'center',
					'pin_img_crop_y' => 'center',
					'pin_caption' => 'excerpt',
					'pin_cap_len' => 400,
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
			$sizes['pin_img'] = array(
				'name' => 'pinterest-button',
				'label' => _x( 'Pinterest Sharing Button',
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
			$prot = empty( $_SERVER['HTTPS'] ) ?
				'http:' : 'https:';
			$use_post = isset( $atts['use_post'] ) ?
				$atts['use_post'] : true;
			$src_id = $this->p->util->get_source_id( 'pinterest', $atts );
			$atts['add_page'] = isset( $atts['add_page'] ) ?
				$atts['add_page'] : true;	// get_sharing_url argument
			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $src_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $src_id );
			$href_query = '?url='.urlencode( $atts['url'] );

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
				$atts['size'] = $this->p->cf['lca'].'-pinterest-button';

			if ( ! empty( $atts['pid'] ) )
				list(
					$atts['photo'],
					$atts['width'],
					$atts['height'],
					$atts['cropped']
				) = $this->p->media->get_attachment_image_src( $atts['pid'], $atts['size'], false );

			if ( empty( $atts['photo'] ) && empty( $atts['embed'] ) ) {
				list( $img_url, $vid_url ) = $this->p->og->get_the_media_urls( $atts['size'], $post_id, 'rp' );
				if ( empty( $atts['photo'] ) )
					$atts['photo'] = $img_url;
				if ( empty( $atts['embed'] ) )
					$atts['embed'] = $vid_url;
			}

			// let the pinterest crawler choose an image
			if ( empty( $this->p->options['pin_use_img'] ) )
				$href_query .= '&amp;media=';
			elseif ( empty( $atts['photo'] ) ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->log( 'exiting early: no photo defined for post_id '.$post_id );
				return false;
			} else $href_query .= '&amp;media='.rawurlencode( $atts['photo'] );

			if ( empty( $atts['caption'] ) ) {
				$atts['caption'] = $this->p->webpage->get_caption(
					$opts['pin_caption'],		// title, excerpt, both
					$opts['pin_cap_len'],		// max caption length
					$use_post,			//
					true,				// use_cache
					true,				// add_hashtags
					false,				// encode (false for later url encoding)
					'pin_desc',			// metadata key
					$src_id
				);
			}
			// use rawurlencode() for mobile devices (encodes a space as '%20' instead of '+')
			$href_query .= '&amp;description='.rawurlencode( $atts['caption'] );

			switch ( $opts['pin_button_shape'] ) {
				case 'rect':
					$pin_img_width = $opts['pin_button_height'] == 'small' ? 40 : 56;
					$pin_img_height = $opts['pin_button_height'] == 'small' ? 20 : 28;
					$pin_img_url = $prot.'//assets.pinterest.com/images/pidgets/pinit_fg_'.
						$opts['pin_button_lang'].'_'.$opts['pin_button_shape'].'_'.
						$opts['pin_button_color'].'_'.$pin_img_height.'.png';
					break;
				case 'round':
					$pin_img_width = $pin_img_height = $opts['pin_button_height'] == 'small' ? 16 : 32;
					$pin_img_url = $prot.'//assets.pinterest.com/images/pidgets/pinit_fg_'.
						'en_'.$opts['pin_button_shape'].'_'.
						'red_'.$pin_img_height.'.png';
					break;
				default:
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'exiting early: unknown pinterest button shape' );
					return $html;
					break;
			}
			$pin_img_url = $this->p->util->get_cache_file_url( $pin_img_url );

			$html = '<!-- Pinterest Button --><div '.$this->p->sharing->get_css( 'pinterest', $atts ).'>'.
			'<a href="'.$prot.'//pinterest.com/pin/create/button/'.$href_query.'" '.
			'data-pin-do="buttonPin" '.
			'data-pin-lang="'.$opts['pin_button_lang'].'" '.
			'data-pin-shape="'.$opts['pin_button_shape'].'" '.
			'data-pin-color="'.$opts['pin_button_color'].'" '.
			'data-pin-height="'.$pin_img_height.'" '.
			'data-pin-config="'.$opts['pin_count_layout'].'">'.
			'<img border="0" alt="Pin It" src="'.$pin_img_url.'" width="'.$pin_img_width.'" height="'.$pin_img_height.'" /></a></div>';

			if ( $this->p->debug->enabled )
				$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html."\n";
		}

		public function get_script( $pos = 'id' ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_file_url( apply_filters( $this->p->cf['lca'].'_js_url_pinterest', 
				$prot.'//assets.pinterest.com/js/pinit.js', $pos ) );

			return '<script type="text/javascript" id="pinterest-script-'.$pos.'">'.
				$this->p->cf['lca'].'_insert_js( "pinterest-script-'.$pos.'", "'.$js_url.'" );</script>'."\n";
		}
	}
}

?>
