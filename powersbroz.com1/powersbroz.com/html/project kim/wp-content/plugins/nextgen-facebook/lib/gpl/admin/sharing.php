<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbGplAdminSharing' ) ) {

	class NgfbGplAdminSharing {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 
				'plugin_cache_rows' => 3,	// advanced 'File and Object Cache' options
				'sharing_include_rows' => 2,	// social sharing 'Include Buttons' options
				'sharing_preset_rows' => 2,	// social sharing 'Button Presets' options
				'post_tabs' => 1,		// post 'Sharing Buttons' tab
				'post_sharing_rows' => 3,	// post 'Sharing Buttons' options
			), 30 );
		}

		public function filter_plugin_cache_rows( $rows, $form, $network = false ) {

			$rows['plugin_file_cache_exp'] = $this->p->util->get_th( _x( 'Social File Cache Expiry',
				'option label', 'nextgen-facebook' ), null, 'plugin_file_cache_exp' ).
			'<td nowrap class="blank">'.$this->p->cf['form']['file_cache_hrs'][$form->options['plugin_file_cache_exp']].' '.
				_x( 'hours', 'option comment', 'nextgen-facebook' ).'</td>'.
			$this->p->admin->get_site_use( $form, $network, 'plugin_file_cache_exp' );

			return $rows;
		}

		public function filter_sharing_include_rows( $rows, $form ) {
			$checkboxes = '';

			foreach ( $this->p->util->get_post_types() as $post_type )
				$checkboxes .= '<p>'.$form->get_no_checkbox( 'buttons_add_to_'.$post_type->name ).' '.
					$post_type->label.' '.( empty( $post_type->description ) ? '' :
						'('.$post_type->description.')' ).'</p>';

			$rows[] = '<td colspan="2" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg', array( 'lca' => 'ngfb' ) ).'</td>';

			$rows['buttons_add_to'] = $this->p->util->get_th( _x( 'Include on Post Types',
				'option label', 'nextgen-facebook' ), null, 'buttons_add_to' ).
				'<td class="blank">'.$checkboxes.'</td>';

			return $rows;
		}

		public function filter_sharing_preset_rows( $rows, $form ) {

			$presets = array( 'shortcode' => 'Shortcode', 'widget' => 'Widget' );
			$show_on = apply_filters( $this->p->cf['lca'].'_sharing_show_on', 
				$this->p->cf['sharing']['show_on'], '' );
			foreach ( $show_on as $type => $label )
				$presets[$type] = $label;
			asort( $presets );

			$rows[] = '<td colspan="2" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg', array( 'lca' => 'ngfb' ) ).'</td>';

			foreach( $presets as $filter_id => $filter_name )
				$rows[] = $this->p->util->get_th( sprintf( _x( '%s Preset',
					'option label', 'nextgen-facebook' ), $filter_name ), null, 'sharing_preset' ).
				'<td class="blank">'.$this->p->options['buttons_preset_'.$filter_id].'</td>';

			return $rows;
		}

		public function filter_post_tabs( $tabs ) {
			$new_tabs = array();
			foreach ( $tabs as $key => $val ) {
				$new_tabs[$key] = $val;
				if ( $key === 'media' )	// insert the social sharing tab after the media tab
					$new_tabs['sharing'] = _x( 'Sharing Buttons', 'metabox tab', 'nextgen-facebook' );
			}
			return $new_tabs;
		}

		public function filter_post_sharing_rows( $rows, $form, $head_info ) {

			$lca = $this->p->cf['lca'];
			$post_status = get_post_status( $head_info['post_id'] );
			$size_info = $this->p->media->get_size_info( 'thumbnail' );

			$rows[] = '<td colspan="3" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg', array( 'lca' => 'ngfb' ) ).'</td>';

			/*
			 * Pinterest
			 */
			list( $pid, $img_url ) = $this->p->og->get_the_media_urls( $lca.'-pinterest-button',
				$head_info['post_id'], 'rp', array( 'pid', 'image' ) );

			$th = $this->p->util->get_th( _x( 'Pinterest Caption Text',
				'option label', 'nextgen-facebook' ), 'medium', 'post-pin_desc' );
			if ( ! empty( $pid ) ) {
				list(
					$img_url,
					$img_width,
					$img_height,
					$img_cropped
				) = $this->p->media->get_attachment_image_src( $pid, 'thumbnail', false ); 
			}
			$rows['pin_caption'] = $th.'<td class="blank">'.
			$this->p->webpage->get_caption( $this->p->options['pin_caption'], $this->p->options['pin_cap_len'] ).'</td>'.
			( empty( $img_url ) ? '' : '<td style="width:'.$size_info['width'].'px;"><img src="'.$img_url.'"
				style="max-width:'.$size_info['width'].'px;"></td>' );

			/*
			 * Tumblr
			 */
			list( $pid, $img_url, $vid_url, $prev_url ) = $this->p->og->get_the_media_urls( $lca.'-tumblr-button', 
				$head_info['post_id'], 'og', array( 'pid', 'image', 'video', 'preview' ) );

			$th = $this->p->util->get_th( _x( 'Tumblr Image Caption',
				'option label', 'nextgen-facebook' ), 'medium', 'post-tumblr_img_desc' );

			if ( ! empty( $pid ) ) {
				list(
					$img_url,
					$img_width,
					$img_height,
					$img_cropped
				) = $this->p->media->get_attachment_image_src( $pid, 'thumbnail', false ); 
			}

			if ( ! empty( $img_url ) ) {
				$rows['tumblr_img_desc'] = $th.'<td class="blank">'.
				$this->p->webpage->get_caption( $this->p->options['tumblr_caption'], $this->p->options['tumblr_cap_len'] ).'</td>'.
				'<td style="width:'.$size_info['width'].'px;"><img src="'.$img_url.'"
					style="max-width:'.$size_info['width'].'px;"></td>';
			} else $rows['tumblr_img_desc'] = $th.'<td class="blank"><em>'.
				sprintf( __( 'Caption disabled - no suitable image found for the %s button', 'nextgen-facebook' ), 'Tumblr' ).'</em></td>';

			$th = $this->p->util->get_th( _x( 'Tumblr Video Caption',
				'option label', 'nextgen-facebook' ), 'medium', 'post-tumblr_vid_desc' );
			if ( ! empty( $vid_url ) ) {
				$rows['tumblr_vid_desc'] = $th.'<td class="blank">'.
				$this->p->webpage->get_caption( $this->p->options['tumblr_caption'], $this->p->options['tumblr_cap_len'] ).'</td>'.
				'<td style="width:'.$size_info['width'].'px;"><img src="'.$prev_url.'" 
					style="max-width:'.$size_info['width'].'px;"></td>';
			} else $rows['tumblr_vid_desc'] = $th.'<td class="blank"><em>'.
				sprintf( __( 'Caption disabled - no suitable video found for the %s button', 'nextgen-facebook' ), 'Tumblr' ).'</em></td>';

			/*
			 * Twitter
			 */
			if ( $post_status == 'auto-draft' ) {
				$rows['twitter_desc'] = $this->p->util->get_th( _x( 'Tweet Text',
					'option label', 'nextgen-facebook' ), 'medium', 'post-twitter_desc' ). 
				'<td class="blank"><em>'.__( 'Save a draft version or publish to enable this option.',
					'nextgen-facebook' ).'</em></td>';
			} else {
				$twitter_cap_len = $this->p->util->get_tweet_max_len( get_permalink( $head_info['post_id'] ) );
				$rows['twitter_desc'] = $this->p->util->get_th( _x( 'Tweet Text',
					'option label', 'nextgen-facebook' ), 'medium', 'post-twitter_desc' ). 
				'<td class="blank">'.$this->p->webpage->get_caption( $this->p->options['twitter_caption'], $twitter_cap_len,
					true, true, true ).'</td>';	// $use_post = true, $use_cache = true, $add_hashtags = true
			}

			$rows['buttons_disabled'] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Disable Sharing Buttons',
				'option label', 'nextgen-facebook' ), 'medium', 'post-buttons_disabled', $head_info ).
			'<td class="blank">&nbsp;</td>';

			return $rows;
		}
	}
}

?>
