<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbOptionsUpgrade' ) && class_exists( 'NgfbOptions' ) ) {

	class NgfbOptionsUpgrade extends NgfbOptions {

		private $renamed_site_keys = array(
			'plugin_tid_use' => 'plugin_ngfb_tid:use',
			'plugin_tid:use' => 'plugin_ngfb_tid:use',
			'plugin_tid' => 'plugin_ngfb_tid',
		);

		private $renamed_keys = array(
			'og_def_img' => 'og_def_img_url',
			'og_def_home' => 'og_def_img_on_index',
			'og_def_on_home' => 'og_def_img_on_index',
			'og_def_on_search' => 'og_def_img_on_search',
			'buttons_on_home' => 'buttons_on_index',
			'buttons_lang' => 'gp_lang',
			'ngfb_cache_hours' => 'plugin_file_cache_hrs',
			'fb_enable' => 'fb_on_content', 
			'gp_enable' => 'gp_on_content',
			'twitter_enable' => 'twitter_on_content',
			'linkedin_enable' => 'linkedin_on_content',
			'pin_enable' => 'pin_on_content',
			'stumble_enable' => 'stumble_on_content',
			'tumblr_enable' => 'tumblr_on_content',
			'buttons_location' => 'buttons_pos_content',
			'plugin_pro_tid' => 'plugin_ngfb_tid',
			'og_admins' => 'fb_admins',
			'og_app_id' => 'fb_app_id',
			'link_desc_len' => 'seo_desc_len',
			'ngfb_version' => '',
			'ngfb_pro_tid' => 'plugin_ngfb_tid',
			'ngfb_preserve' => 'plugin_preserve',
			'ngfb_debug' => 'plugin_debug',
			'ngfb_enable_shortcode' => 'plugin_shortcodes',
			'ngfb_skip_small_img' => 'plugin_ignore_small_img',
			'ngfb_filter_content' => 'plugin_filter_content',
			'ngfb_filter_excerpt' => 'plugin_filter_excerpt',
			'ngfb_add_to_post' => 'plugin_add_to_post',
			'ngfb_add_to_page' => 'plugin_add_to_page',
			'ngfb_add_to_attachment' => 'plugin_add_to_attachment',
			'ngfb_verify_certs' => 'plugin_verify_certs',
			'ngfb_file_cache_hrs' => 'plugin_file_cache_hrs',
			'ngfb_object_cache_exp' => 'plugin_object_cache_exp',
			'ngfb_min_shorten' => 'plugin_min_shorten',
			'ngfb_googl_api_key' => 'plugin_google_api_key',
			'ngfb_bitly_login' => 'plugin_bitly_login',
			'ngfb_bitly_api_key' => 'plugin_bitly_api_key',
			'ngfb_cm_fb_name' => 'plugin_cm_fb_name', 
			'ngfb_cm_fb_label' => 'plugin_cm_fb_label', 
			'ngfb_cm_fb_enabled' => 'plugin_cm_fb_enabled',
			'ngfb_cm_gp_name' => 'plugin_cm_gp_name', 
			'ngfb_cm_gp_label' => 'plugin_cm_gp_label', 
			'ngfb_cm_gp_enabled' => 'plugin_cm_gp_enabled',
			'ngfb_cm_linkedin_name' => 'plugin_cm_linkedin_name', 
			'ngfb_cm_linkedin_label' => 'plugin_cm_linkedin_label', 
			'ngfb_cm_linkedin_enabled' => 'plugin_cm_linkedin_enabled',
			'ngfb_cm_pin_name' => 'plugin_cm_pin_name', 
			'ngfb_cm_pin_label' => 'plugin_cm_pin_label', 
			'ngfb_cm_pin_enabled' => 'plugin_cm_pin_enabled',
			'ngfb_cm_tumblr_name' => 'plugin_cm_tumblr_name', 
			'ngfb_cm_tumblr_label' => 'plugin_cm_tumblr_label', 
			'ngfb_cm_tumblr_enabled' => 'plugin_cm_tumblr_enabled',
			'ngfb_cm_twitter_name' => 'plugin_cm_twitter_name', 
			'ngfb_cm_twitter_label' => 'plugin_cm_twitter_label', 
			'ngfb_cm_twitter_enabled' => 'plugin_cm_twitter_enabled',
			'ngfb_cm_yt_name' => 'plugin_cm_yt_name', 
			'ngfb_cm_yt_label' => 'plugin_cm_yt_label', 
			'ngfb_cm_yt_enabled' => 'plugin_cm_yt_enabled',
			'ngfb_cm_skype_name' => 'plugin_cm_skype_name', 
			'ngfb_cm_skype_label' => 'plugin_cm_skype_label', 
			'ngfb_cm_skype_enabled' => 'plugin_cm_skype_enabled',
			'plugin_googl_api_key' => 'plugin_google_api_key',
			'og_img_resize' => 'plugin_auto_img_resize',
			'buttons_css_social' => 'buttons_css_sharing',
			'plugin_shortcode_ngfb' => 'plugin_shortcodes',
			'buttons_link_css' => 'buttons_use_social_css',
			'fb_on_admin_sharing' => 'fb_on_admin_edit',
			'gp_on_admin_sharing' => 'gp_on_admin_edit',
			'twitter_on_admin_sharing' => 'twitter_on_admin_edit',
			'linkedin_on_admin_sharing' => 'linkedin_on_admin_edit',
			'managewp_on_admin_sharing' => 'managewp_on_admin_edit',
			'stumble_on_admin_sharing' => 'stumble_on_admin_edit',
			'pin_on_admin_sharing' => 'pin_on_admin_edit',
			'tumblr_on_admin_sharing' => 'tumblr_on_admin_edit',
			'buttons_location_the_excerpt' => 'buttons_pos_excerpt',
			'buttons_location_the_content' => 'buttons_pos_content',
			'fb_on_the_content' => 'fb_on_content',
			'fb_on_the_excerpt' => 'fb_on_excerpt',
			'gp_on_the_content' => 'gp_on_content',
			'gp_on_the_excerpt' => 'gp_on_excerpt',
			'twitter_on_the_content' => 'twitter_on_content',
			'twitter_on_the_excerpt' => 'twitter_on_excerpt',
			'linkedin_on_the_content' => 'linkedin_on_content',
			'linkedin_on_the_excerpt' => 'linkedin_on_excerpt',
			'managewp_on_the_content' => 'managewp_on_content',
			'managewp_on_the_excerpt' => 'managewp_on_excerpt',
			'stumble_on_the_content' => 'stumble_on_content',
			'stumble_on_the_excerpt' => 'stumble_on_excerpt',
			'pin_on_the_content' => 'pin_on_content',
			'pin_on_the_excerpt' => 'pin_on_excerpt',
			'tumblr_on_the_content' => 'tumblr_on_content',
			'tumblr_on_the_excerpt' => 'tumblr_on_excerpt',
			'link_def_author_id' => 'seo_def_author_id',
			'link_def_author_on_index' => 'seo_def_author_on_index',
			'link_def_author_on_search' => 'seo_def_author_on_search',
			'plugin_tid' => 'plugin_ngfb_tid',
			'og_publisher_url' => 'fb_publisher_url',
			'link_author_field' => 'seo_author_field',
			'link_publisher_url' => 'seo_publisher_url',
			'add_meta_property_og:video' => 'add_meta_property_og:video:url',
			'twitter_shortener' => 'plugin_shortener',
			'stumble_js_loc' => 'stumble_script_loc',
			'pin_js_loc' => 'pin_script_loc',
			'tumblr_js_loc' => 'tumblr_script_loc',
			'gp_js_loc' => 'gp_script_loc',
			'fb_js_loc' => 'fb_script_loc',
			'twitter_js_loc' => 'twitter_script_loc',
			'buffer_js_loc' => 'buffer_script_loc',
			'linkedin_js_loc' => 'linkedin_script_loc',
			'og_desc_strip' => 'plugin_p_strip',
			'og_desc_alt' => 'plugin_use_img_alt',
			'add_meta_name_twitter:data1' => '',
			'add_meta_name_twitter:label1' => '',
			'add_meta_name_twitter:data2' => '',
			'add_meta_name_twitter:label2' => '',
			'add_meta_name_twitter:data3' => '',
			'add_meta_name_twitter:label3' => '',
			'add_meta_name_twitter:data4' => '',
			'add_meta_name_twitter:label4' => '',
			'tc_enable' => '',
			'tc_photo_width' => '',
			'tc_photo_height' => '',
			'tc_photo_crop' => '',
			'tc_photo_crop_x' => '',
			'tc_photo_crop_y' => '',
			'tc_gal_min' => '',
			'tc_gal_width' => '',
			'tc_gal_height' => '',
			'tc_gal_crop' => '',
			'tc_gal_crop_x' => '',
			'tc_gal_crop_y' => '',
			'tc_prod_width' => '',
			'tc_prod_height' => '',
			'tc_prod_crop' => '',
			'tc_prod_crop_x' => '',
			'tc_prod_crop_y' => '',
			'tc_prod_labels' => '',
			'tc_prod_def_label2' => '',
			'tc_prod_def_data2' => '',
			'plugin_version' => '',
		);

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
		}

		// def_opts accepts output from functions, so don't force reference
		public function options( $options_name, &$opts = array(), $def_opts = array(), $network = false ) {

			// retrieve the first numeric string
			$opts_version = empty( $opts['options_version'] ) ? 0 :
				preg_replace( '/^[^0-9]*([0-9]*).*$/', '$1', $opts['options_version'] );

			if ( $options_name === constant( 'NGFB_OPTIONS_NAME' ) ) {

				$opts = SucomUtil::rename_keys( $opts, $this->renamed_keys );

				if ( version_compare( $opts_version, 28, '<=' ) ) {
					// upgrade the old og_img_size name into width / height / crop values
					if ( array_key_exists( 'og_img_size', $opts ) ) {
						if ( ! empty( $opts['og_img_size'] ) && $opts['og_img_size'] !== 'medium' ) {
							$size_info = $this->p->media->get_size_info( $opts['og_img_size'] );
							if ( $size_info['width'] > 0 && $size_info['height'] > 0 ) {
								$opts['og_img_width'] = $size_info['width'];
								$opts['og_img_height'] = $size_info['height'];
								$opts['og_img_crop'] = $size_info['crop'];
							}
						}
						unset( $opts['og_img_size'] );
					}
				}

				if ( version_compare( $opts_version, 260, '<=' ) ) {
					if ( $opts['og_img_width'] == 1200 &&
						$opts['og_img_height'] == 630 &&
						! empty( $opts['og_img_crop'] ) ) {

						$this->p->notice->inf( 'Open Graph Image Dimentions have been updated from '.
							$opts['og_img_width'].'x'.$opts['og_img_height'].', '.
							( $opts['og_img_crop'] ? '' : 'un' ).'cropped to '.
							$def_opts['og_img_width'].'x'.$def_opts['og_img_height'].', '.
							( $def_opts['og_img_crop'] ? '' : 'un' ).'cropped.', true );
	
						$opts['og_img_width'] = $def_opts['og_img_width'];
						$opts['og_img_height'] = $def_opts['og_img_height'];
						$opts['og_img_crop'] = $def_opts['og_img_crop'];
					}
				}

				if ( version_compare( $opts_version, 270, '<=' ) ) {
					foreach ( $opts as $key => $val ) {
						if ( strpos( $key, 'inc_' ) === 0 ) {
							$new_key = '';
							switch ( $key ) {
								case ( preg_match( '/^inc_(description|twitter:)/', $key ) ? true : false ):
									$new_key = preg_replace( '/^inc_/', 'add_meta_name_', $key );
									break;
								default:
									$new_key = preg_replace( '/^inc_/', 'add_meta_property_', $key );
									break;
							}
							if ( ! empty( $new_key ) )
								$opts[$new_key] = $val;
							unset( $opts[$key] );
						}
					}
				}

				if ( version_compare( $opts_version, 296, '<=' ) ) {
					if ( empty( $opts['plugin_min_shorten'] ) || 
						$opts['plugin_min_shorten'] < 22 ) 
							$opts['plugin_min_shorten'] = 22;
				}

				if ( version_compare( $opts_version, 373, '<=' ) ) {
					if ( ! empty( $opts['plugin_head_attr_filter_name'] ) &&
						$opts['plugin_head_attr_filter_name'] === 'language_attributes' ) 
							$opts['plugin_head_attr_filter_name'] = 'head_attributes';
				}

			} elseif ( $options_name === constant( 'NGFB_SITE_OPTIONS_NAME' ) )
				$opts = SucomUtil::rename_keys( $opts, $this->renamed_site_keys );

			if ( version_compare( $opts_version, 342, '<=' ) ) {
				if ( isset( $opts['plugin_file_cache_hrs'] ) ) {
					$opts['plugin_file_cache_exp'] = $opts['plugin_file_cache_hrs'] * 3600;
					unset( $opts['plugin_file_cache_hrs'] );
				}
			}

			return $this->sanitize( $opts, $def_opts, $network );	// cleanup options and sanitize
		}
	}
}

?>
