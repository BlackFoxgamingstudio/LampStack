<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbConfig' ) ) {

	class NgfbConfig {

		public static $cf = array(
			'lca' => 'ngfb',		// lowercase acronym
			'uca' => 'NGFB',		// uppercase acronym
			'menu' => 'NGFB',		// menu item label
			'color' => 'f60',		// menu item color - dark orange
			'feed_cache_exp' => 86400,	// 24 hours
			'plugin' => array(
				'ngfb' => array(
					'version' => '8.21.5',		// plugin version
					'short' => 'NGFB',		// short plugin name
					'name' => 'NextGEN Facebook (NGFB)',
					'desc' => 'Display your content in the best possible way on Facebook, Google+, Twitter, Pinterest, etc. - no matter how your webpage is shared!',
					'slug' => 'nextgen-facebook',
					'base' => 'nextgen-facebook/nextgen-facebook.php',
					'update_auth' => 'tid',
					'text_domain' => 'nextgen-facebook',
					'domain_path' => '/languages',
					'img' => array(
						'icon_small' => 'images/icon-128x128.png',
						'icon_medium' => 'images/icon-256x256.png',
						'background' => 'images/background.jpg',
					),
					'url' => array(
						// wordpress
						'download' => 'https://wordpress.org/plugins/nextgen-facebook/',
						'review' => 'https://wordpress.org/support/view/plugin-reviews/nextgen-facebook?filter=5&rate=5#postform',
						'readme' => 'https://plugins.svn.wordpress.org/nextgen-facebook/trunk/readme.txt',
						'setup' => 'https://plugins.svn.wordpress.org/nextgen-facebook/trunk/setup.html',
						'wp_support' => 'https://wordpress.org/support/plugin/nextgen-facebook',
						// surniaulula
						'update' => 'http://surniaulula.com/extend/plugins/nextgen-facebook/update/',
						'purchase' => 'http://surniaulula.com/extend/plugins/nextgen-facebook/',
						'changelog' => 'http://surniaulula.com/extend/plugins/nextgen-facebook/changelog/',
						'codex' => 'http://surniaulula.com/codex/plugins/nextgen-facebook/',
						'faq' => 'http://surniaulula.com/codex/plugins/nextgen-facebook/faq/',
						'notes' => 'http://surniaulula.com/codex/plugins/nextgen-facebook/notes/',
						'feed' => 'http://surniaulula.com/category/application/wordpress/wp-plugins/ngfb/feed/',
						'pro_support' => 'http://nextgen-facebook.support.surniaulula.com/',
					),
					'lib' => array(			// libraries
						'profile' => array (	// lib file descriptions will be translated
							'social-settings' => 'Your Social Settings',
						),
						'setting' => array (	// lib file descriptions will be translated
							'image-dimensions' => 'Social Image Dimensions',
							'social-accounts' => 'Website / Business Social Accounts',
							'contact-fields' => 'User Profile Contact Methods',
						),
						'submenu' => array (	// lib file descriptions will be translated
							'essential' => 'Essential Settings',
							'general' => 'General Settings',
							'advanced' => 'Advanced Settings',
							'sharing' => 'Sharing Buttons',
							'style' => 'Sharing Styles',
							'readme' => 'Plugin Read Me',
							'setup' => 'Setup Guide',
							// the first 2 words will be highlighted in menu
							'licenses' => '<span>Extension Plugins</span> and Pro Licenses',
						),
						'sitesubmenu' => array(	// lib file descriptions will be translated
							'siteadvanced' => 'Advanced Settings',
							'sitereadme' => 'Plugin Read Me',
							'sitesetup' => 'Setup Guide',
							// the first 2 words will be highlighted in menu
							'sitelicenses' => '<span>Extension Plugins</span> and Pro Licenses',
						),
						'website' => array(
							'twitter' => 'Twitter',
							'facebook' => 'Facebook', 
							'gplus' => 'GooglePlus',
							'pinterest' => 'Pinterest',
							'linkedin' => 'LinkedIn',
							'buffer' => 'Buffer',
							'reddit' => 'Reddit',
							'managewp' => 'ManageWP',
							'stumbleupon' => 'StumbleUpon',
							'tumblr' => 'Tumblr',
							'youtube' => 'YouTube',
							'skype' => 'Skype',
						),
						'shortcode' => array(
							'sharing' => 'Sharing Shortcode',
						),
						'widget' => array(
							'sharing' => 'Sharing Widget',
						),
						'gpl' => array(
							'admin' => array(
								'general' => 'General Settings',
								'advanced' => 'Advanced Settings',
								'sharing' => 'Button Settings',
								'style' => 'Style Settings',
								'post' => 'Post Settings',
								'taxonomy' => 'Taxonomy Settings',
								'user' => 'User Settings',
							),
							'ecom' => array(
								'woocommerce' => 'WooCommerce',
							),
							'forum' => array(
								'bbpress' => 'bbPress',
							),
							'social' => array(
								'buddypress' => 'BuddyPress',
							),
							'util' => array(
								'post' => 'Post Settings',
								'taxonomy' => 'Taxonomy Settings',
								'user' => 'User Settings',
							),
						),
						'pro' => array(
							'admin' => array(
								'general' => 'General Settings',
								'advanced' => 'Advanced Settings',
								'sharing' => 'Button Settings',
								'style' => 'Style Settings',
								'post' => 'Post Settings',
								'taxonomy' => 'Taxonomy Settings',
								'user' => 'User Settings',
							),
							'ecom' => array(
								'edd' => 'Easy Digital Downloads',
								'marketpress' => 'MarketPress',
								'woocommerce' => 'WooCommerce',
								'wpecommerce' => 'WP e-Commerce',
								'yotpowc' => 'Yotpo for WC',	// yotpo-social-reviews-for-woocommerce
							),
							'forum' => array(
								'bbpress' => 'bbPress',
							),
							'lang' => array(
								'polylang' => 'Polylang',
							),
							'media' => array(
								'gravatar' => 'Author Gravatar',
								'ngg' => 'NextGEN Gallery',
								'slideshare' => 'Slideshare API',
								'upscale' => 'WP Image Upscaler',
								'vimeo' => 'Vimeo Video API',
								'wistia' => 'Wistia Video API',
								'youtube' => 'YouTube Video / Playlist API',
							),
							'seo' => array(
								'aioseop' => 'All in One SEO Pack',
								'headspace2' => 'HeadSpace2 SEO',
								'wpseo' => 'Yoast SEO',
							),
							'social' => array(
								'buddypress' => 'BuddyPress',
							),
							'util' => array(
								'language' => 'Publisher Language',
								'shorten' => 'URL Shortening',
								'post' => 'Post Settings',
								'restapi' => 'WP REST API v2',
								'taxonomy' => 'Taxonomy Settings',
								'user' => 'User Settings',
							),
						),
					),
				),
				'ngfbum' => array(
					'short' => 'NGFB UM',
					'name' => 'NextGEN Facebook (NGFB) Pro Update Manager',
					'desc' => 'Update Manager for the NextGEN Facebook (NGFB) Pro plugin and its Pro extensions.',
					'slug' => 'nextgen-facebook-um',
					'base' => 'nextgen-facebook-um/nextgen-facebook-um.php',
					'update_auth' => '',
					'img' => array(
						'icon_small' => 'https://surniaulula.github.io/nextgen-facebook-um/assets/icon-128x128.png',
						'icon_medium' => 'https://surniaulula.github.io/nextgen-facebook-um/assets/icon-256x256.png',
					),
					'url' => array(
						// surniaulula
						'download' => 'http://surniaulula.com/extend/plugins/nextgen-facebook-um/',
						'latest_zip' => 'http://surniaulula.com/extend/plugins/nextgen-facebook-um/latest/',
						'review' => '',
						'readme' => 'https://raw.githubusercontent.com/SurniaUlula/nextgen-facebook-um/master/readme.txt',
						'wp_support' => '',
						'update' => 'http://surniaulula.com/extend/plugins/nextgen-facebook-um/update/',
						'purchase' => '',
						'changelog' => 'http://surniaulula.com/extend/plugins/nextgen-facebook-um/changelog/',
						'codex' => '',
						'faq' => '',
						'notes' => '',
						'feed' => '',
						'pro_support' => '',
					),
				),
			),
			'opt' => array(						// options
				'version' => 'ngfb404',				// increment when changing default options
				'defaults' => array(
					'options_filtered' => false,
					'schema_logo_url' => '',
					'schema_desc_len' => 250,		// meta itemprop="description" maximum text length
					'schema_type_for_post' => 'webpage',
					'schema_type_for_page' => 'webpage',
					'schema_type_for_attachment' => 'webpage',
					'schema_type_for_article' => 'article',
					'schema_type_for_book' => 'book',
					'schema_type_for_blog' => 'blog',
					'schema_type_for_event' => 'event',
					'schema_type_for_organization' => 'organization',
					'schema_type_for_person' => 'person',
					'schema_type_for_place' => 'place',
					'schema_type_for_product' => 'product',
					'schema_type_for_recipe' => 'recipe',
					'schema_type_for_review' => 'review',
					'schema_type_for_other' => 'other',
					'schema_type_for_local.business' => 'local.business',
					'schema_type_for_webpage' => 'webpage',
					'schema_type_for_website' => 'website',
					'schema_author_name' => 'display_name',
					'schema_author_json' => 1,
					'schema_publisher_json' => 1,
					'schema_website_json' => 1,
					'schema_add_noscript' => 1,
					'seo_desc_len' => 156,			// meta name="description" maximum text length
					'seo_author_name' => 'none',		// meta name="author" format
					'seo_def_author_id' => 0,
					'seo_def_author_on_index' => 0,
					'seo_def_author_on_search' => 0,
					'seo_author_field' => '',		// default value set by NgfbOptions::get_defaults()
					'seo_publisher_url' => '',
					'fb_publisher_url' => '',
					'fb_admins' => '',
					'fb_app_id' => '',
					'fb_lang' => 'en_US',
					'instgram_publisher_url' => '',
					'linkedin_publisher_url' => '',
					'myspace_publisher_url' => '',
					'og_site_name' => '',
					'og_site_description' => '',
					'og_art_section' => 'none',
					'og_img_width' => 600,
					'og_img_height' => 315,
					'og_img_crop' => 1,
					'og_img_crop_x' => 'center',
					'og_img_crop_y' => 'center',
					'og_img_max' => 1,
					'og_vid_max' => 1,
					'og_vid_https' => 1,
					'og_vid_autoplay' => 1,
					'og_vid_prev_img' => 0,
					'og_vid_html_type' => 1,
					'og_def_img_id_pre' => 'wp',
					'og_def_img_id' => '',
					'og_def_img_url' => '',
					'og_def_img_on_index' => 1,
					'og_def_img_on_search' => 0,
					'og_def_vid_url' => '',
					'og_def_vid_on_index' => 1,
					'og_def_vid_on_search' => 0,
					'og_def_author_id' => 0,
					'og_def_author_on_index' => 0,
					'og_def_author_on_search' => 0,
					'og_ngg_tags' => 0,
					'og_page_parent_tags' => 0,
					'og_page_title_tag' => 0,
					'og_author_field' => '',		// default value set by NgfbOptions::get_defaults()
					'og_author_fallback' => 0,
					'og_title_sep' => '-',
					'og_title_len' => 70,
					'og_desc_len' => 300,
					'og_desc_hashtags' => 3,
					'rp_publisher_url' => '',
					'rp_author_name' => 'display_name',	// rich-pin specific article:author
					'rp_img_width' => 600,
					'rp_img_height' => 600,
					'rp_img_crop' => 0,
					'rp_img_crop_x' => 'center',
					'rp_img_crop_y' => 'center',
					'rp_dom_verify' => '',
					'tc_site' => '',
					'tc_desc_len' => 200,
					// summary card
					'tc_sum_width' => 300,
					'tc_sum_height' => 300,
					'tc_sum_crop' => 1,
					'tc_sum_crop_x' => 'center',
					'tc_sum_crop_y' => 'center',
					// large image summary card
					'tc_lrgimg_width' => 600,
					'tc_lrgimg_height' => 600,
					'tc_lrgimg_crop' => 0,
					'tc_lrgimg_crop_x' => 'center',
					'tc_lrgimg_crop_y' => 'center',
					// enable/disable header html tags
					'add_link_rel_author' => 1,
					'add_link_rel_publisher' => 1,
					// facebook
					'add_meta_property_fb:admins' => 1,
					'add_meta_property_fb:app_id' => 1,
					// open graph
					'add_meta_property_og:locale' => 1,
					'add_meta_property_og:site_name' => 1,
					'add_meta_property_og:description' => 1,
					'add_meta_property_og:title' => 1,
					'add_meta_property_og:type' => 1,
					'add_meta_property_og:url' => 1,
					'add_meta_property_og:image' => 1,
					'add_meta_property_og:image:secure_url' => 1,
					'add_meta_property_og:image:width' => 1,
					'add_meta_property_og:image:height' => 1,
					'add_meta_property_og:video:url' => 1,
					'add_meta_property_og:video:secure_url' => 1,
					'add_meta_property_og:video:width' => 1,
					'add_meta_property_og:video:height' => 1,
					'add_meta_property_og:video:type' => 1,
					'add_meta_property_og:altitude' => 1,
					'add_meta_property_og:latitude' => 1,
					'add_meta_property_og:longitude' => 1,
					// article
					'add_meta_property_article:author' => 1,
					'add_meta_property_article:publisher' => 1,
					'add_meta_property_article:published_time' => 1,
					'add_meta_property_article:modified_time' => 1,
					'add_meta_property_article:section' => 1,
					'add_meta_property_article:tag' => 1,
					// book
					'add_meta_property_book:author' => 1,
					'add_meta_property_book:isbn' => 1,
					'add_meta_property_book:release_date' => 1,
					'add_meta_property_book:tag' => 1,
					// music
					'add_meta_property_music:album' => 1,
					'add_meta_property_music:album:disc' => 1,
					'add_meta_property_music:album:track' => 1,
					'add_meta_property_music:creator' => 1,
					'add_meta_property_music:duration' => 1,
					'add_meta_property_music:musician' => 1,
					'add_meta_property_music:release_date' => 1,
					'add_meta_property_music:song' => 1,
					'add_meta_property_music:song:disc' => 1,
					'add_meta_property_music:song:track' => 1,
					// place
					'add_meta_property_place:location:altitude' => 1,
					'add_meta_property_place:location:latitude' => 1,
					'add_meta_property_place:location:longitude' => 1,
					'add_meta_property_place:street_address' => 1,
					'add_meta_property_place:locality' => 1,
					'add_meta_property_place:region' => 1,
					'add_meta_property_place:postal_code' => 1,
					'add_meta_property_place:country_name' => 1,
					// product
					'add_meta_property_product:price:amount' => 1,
					'add_meta_property_product:price:currency' => 1,
					'add_meta_property_product:availability' => 1,
					'add_meta_property_product:rating:average' => 0,
					'add_meta_property_product:rating:count' => 0,
					'add_meta_property_product:rating:worst' => 0,
					'add_meta_property_product:rating:best' => 0,
					// profile
					'add_meta_property_profile:first_name' => 1,
					'add_meta_property_profile:last_name' => 1,
					'add_meta_property_profile:username' => 1,
					'add_meta_property_profile:gender' => 1,
					// video
					'add_meta_property_video:actor' => 1,
					'add_meta_property_video:actor:role' => 1,
					'add_meta_property_video:director' => 1,
					'add_meta_property_video:writer' => 1,
					'add_meta_property_video:duration' => 1,
					'add_meta_property_video:release_date' => 1,
					'add_meta_property_video:tag' => 1,
					'add_meta_property_video:series' => 1,
					// seo
					'add_meta_name_author' => 1,
					'add_meta_name_canonical' => 0,
					'add_meta_name_description' => 1,
					'add_meta_name_generator' => 1,
					// pinterest
					'add_meta_name_p:domain_verify' => 1,
					// twitter cards
					'add_meta_name_twitter:card' => 1,
					'add_meta_name_twitter:creator' => 1,
					'add_meta_name_twitter:domain' => 1,
					'add_meta_name_twitter:site' => 1,
					'add_meta_name_twitter:title' => 1,
					'add_meta_name_twitter:description' => 1,
					'add_meta_name_twitter:image' => 1,
					'add_meta_name_twitter:image:width' => 1,
					'add_meta_name_twitter:image:height' => 1,
					'add_meta_name_twitter:player' => 1,
					'add_meta_name_twitter:player:width' => 1,
					'add_meta_name_twitter:player:height' => 1,
					// schema
					'add_meta_itemprop_name' => 1,
					'add_meta_itemprop_datepublished' => 1,
					'add_meta_itemprop_datemodified' => 1,
					'add_meta_itemprop_description' => 1,
					'add_meta_itemprop_url' => 1,
					'add_meta_itemprop_image' => 1,
					'add_meta_itemprop_image.url' => 1,
					'add_meta_itemprop_image.width' => 1,
					'add_meta_itemprop_image.height' => 1,
					'add_meta_itemprop_publisher.name' => 1,
					'add_meta_itemprop_author.name' => 1,
					'add_meta_itemprop_address' => 1,
					'add_meta_itemprop_ratingvalue' => 1,
					'add_meta_itemprop_ratingcount' => 1,
					'add_meta_itemprop_worstrating' => 1,
					'add_meta_itemprop_bestrating' => 1,
					'add_meta_itemprop_reviewcount' => 1,
					/*
					 * Advanced Settings
					 */
					// Plugin Settings Tab
					'plugin_debug' => 0,				// Add Hidden Debug Messages
					'plugin_preserve' => 0,				// Preserve Settings on Uninstall
					'plugin_show_opts' => 'basic',			// Options to Show by Default
					// Content and Filters Tab
					'plugin_filter_title' => 1,
					'plugin_filter_content' => 0,
					'plugin_filter_excerpt' => 0,
					'plugin_p_strip' => 0,
					'plugin_use_img_alt' => 1,
					'plugin_img_alt_prefix' => 'Image:',
					'plugin_p_cap_prefix' => 'Caption:',
					'plugin_gravatar_api' => 1,
					'plugin_slideshare_api' => 1,
					'plugin_vimeo_api' => 1,
					'plugin_wistia_api' => 1,
					'plugin_youtube_api' => 1,
					// Social Settings Tab
					'plugin_columns_post' => 1,
					'plugin_columns_taxonomy' => 1,
					'plugin_columns_user' => 1,
					'plugin_add_to_post' => 1,
					'plugin_add_to_page' => 1,
					'plugin_add_to_attachment' => 1,
					'plugin_add_to_taxonomy' => 1,
					'plugin_add_to_user' => 1,
					'plugin_add_tab_preview' => 1,
					'plugin_add_tab_tags' => 1,
					'plugin_add_tab_validate' => 1,
					'plugin_cf_img_url' => '_format_image_url',
					'plugin_cf_vid_url' => '_format_video_url',
					'plugin_cf_vid_embed' => '_format_video_embed',
					// WP / Theme Integration Tab
					'plugin_html_attr_filter_name' => 'language_attributes',
					'plugin_html_attr_filter_prio' => 100,
					'plugin_head_attr_filter_name' => 'head_attributes',
					'plugin_head_attr_filter_prio' => 100,
					'plugin_check_head' => 1,			// Check for Duplicate Meta Tags
					'plugin_filter_lang' => 1,			// Use WP Locale for Language
					'plugin_auto_img_resize' => 1,			// Create Missing WP Media Images
					'plugin_ignore_small_img' => 1,			// Enforce Image Dimensions Check
					'plugin_upscale_images' => 0,			// Allow Upscaling of Smaller Images
					'plugin_upscale_img_max' => 50,			// Maximum Image Upscale Percentage
					'plugin_shortcodes' => 1,			// Enable Plugin Shortcode(s)
					'plugin_widgets' => 1,				// Enable Plugin Widget(s)
					'plugin_page_excerpt' => 0,			// Enable WP Excerpt for Pages
					'plugin_page_tags' => 0,			// Enable WP Tags for Pages
					// File and Object Cache Tab
					'plugin_object_cache_exp' => 86400,		// Object Cache Expiry
					'plugin_verify_certs' => 0,			// Verify SSL Certificates
					'plugin_cache_info' => 0,			// Report Cache Purge Count
					'plugin_file_cache_exp' => 0,			// File Cache Expiry
					// Service API Keys Tab
					'plugin_shortener' => 'none',
					'plugin_shortlink' => 1,
					'plugin_min_shorten' => 22,
					'plugin_bitly_login' => '',
					'plugin_bitly_api_key' => '',
					'plugin_owly_api_key' => '',
					'plugin_google_api_key' => '',
					'plugin_google_shorten' => 0,
					// Contact Field Names and Labels
					'plugin_cm_fb_name' => 'facebook', 
					'plugin_cm_fb_label' => 'Facebook URL', 
					'plugin_cm_fb_enabled' => 1,
					'plugin_cm_gp_name' => 'gplus', 
					'plugin_cm_gp_label' => 'Google+ URL', 
					'plugin_cm_gp_enabled' => 1,
					'plugin_cm_instgram_name' => 'instagram', 
					'plugin_cm_instgram_label' => 'Instagram URL', 
					'plugin_cm_instgram_enabled' => 1,
					'plugin_cm_linkedin_name' => 'linkedin', 
					'plugin_cm_linkedin_label' => 'LinkedIn URL', 
					'plugin_cm_linkedin_enabled' => 1,
					'plugin_cm_myspace_name' => 'myspace', 
					'plugin_cm_myspace_label' => 'MySpace URL', 
					'plugin_cm_myspace_enabled' => 1,
					'plugin_cm_pin_name' => 'pinterest', 
					'plugin_cm_pin_label' => 'Pinterest URL', 
					'plugin_cm_pin_enabled' => 1,
					'plugin_cm_tumblr_name' => 'tumblr', 
					'plugin_cm_tumblr_label' => 'Tumblr URL', 
					'plugin_cm_tumblr_enabled' => 1,
					'plugin_cm_twitter_name' => 'twitter', 
					'plugin_cm_twitter_label' => 'Twitter @username', 
					'plugin_cm_twitter_enabled' => 1,
					'plugin_cm_yt_name' => 'youtube', 
					'plugin_cm_yt_label' => 'YouTube Channel URL', 
					'plugin_cm_yt_enabled' => 1,
					'plugin_cm_skype_name' => 'skype', 
					'plugin_cm_skype_label' => 'Skype Username', 
					'plugin_cm_skype_enabled' => 1,
					'wp_cm_aim_name' => 'aim', 
					'wp_cm_aim_label' => 'AIM', 
					'wp_cm_aim_enabled' => 1,
					'wp_cm_jabber_name' => 'jabber', 
					'wp_cm_jabber_label' => 'Google Talk', 
					'wp_cm_jabber_enabled' => 1,
					'wp_cm_yim_name' => 'yim',
					'wp_cm_yim_label' => 'Yahoo IM', 
					'wp_cm_yim_enabled' => 1,
					// Extension Plugins and Pro Licenses
					'plugin_ngfb_tid' => '',
				),
				'site_defaults' => array(
					'options_filtered' => false,
					/*
					 * Advanced Settings
					 */
					// Plugin Settings Tab
					'plugin_debug' => 0,				// Add Hidden Debug Messages
					'plugin_debug:use' => 'default',
					'plugin_preserve' => 0,				// Preserve Settings on Uninstall
					'plugin_preserve:use' => 'default',
					'plugin_show_opts' => 'basic',			// Options to Show by Default
					'plugin_show_opts:use' => 'default',
					'plugin_cache_info' => 0,			// Report Cache Purge Count
					'plugin_cache_info:use' => 'default',
					'plugin_filter_lang' => 1,			// Use WP Locale for Language
					'plugin_filter_lang:use' => 'default',
					'plugin_auto_img_resize' => 1,			// Auto-Resize Media Images
					'plugin_auto_img_resize:use' => 'default',
					'plugin_ignore_small_img' => 1,			// Check Image Dimensions
					'plugin_ignore_small_img:use' => 'default',
					'plugin_shortcodes' => 1,			// Enable Plugin Shortcode(s)
					'plugin_shortcodes:use' => 'default',
					'plugin_widgets' => 1,				// Enable Plugin Widget(s)
					'plugin_widgets:use' => 'default',
					'plugin_page_excerpt' => 0,			// Enable WP Excerpt for Pages
					'plugin_page_excerpt:use' => 'default',
					'plugin_page_tags' => 0,			// Enable WP Tags for Pages
					'plugin_page_tags:use' => 'default',
					// File and Object Cache Tab
					'plugin_object_cache_exp' => 86400,		// Object Cache Expiry
					'plugin_object_cache_exp:use' => 'default',
					'plugin_file_cache_exp' => 0,			// File Cache Expiry
					'plugin_file_cache_exp:use' => 'default',
					'plugin_verify_certs' => 0,			// Verify SSL Certificates
					'plugin_verify_certs:use' => 'default',
					// Extension Plugins and Pro Licenses
					'plugin_ngfb_tid' => '',
					'plugin_ngfb_tid:use' => 'default',
				),
				'preset' => array(
					'small_share_count' => array(
						'fb_button' => 'share',
						'fb_send' => 0,
						'fb_show_faces' => 0,
						'fb_action' => 'like',
						'fb_type' => 'button_count',
						'gp_action' => 'share',
						'gp_size' => 'medium',
						'gp_annotation' => 'bubble',
						'gp_expandto' => '',
						'twitter_size' => 'medium',
						'twitter_count' => 'horizontal',
						'linkedin_counter' => 'right',
						'linkedin_showzero' => 1,
						'pin_button_shape' => 'rect',
						'pin_button_height' => 'small',
						'pin_count_layout' => 'beside',
						'buffer_count' => 'horizontal',
						'reddit_type' => 'static-wide',
						'managewp_type' => 'small',
						'tumblr_button_style' => 'share_1',
						'stumble_badge' => 1,
					),
					'large_share_vertical' => array(
						'fb_button' => 'share',
						'fb_send' => 0,
						'fb_show_faces' => 0,
						'fb_action' => 'like',
						'fb_type' => 'box_count',
						'fb_layout' => 'box_count',
						'gp_action' => 'share',
						'gp_size' => 'tall',
						'gp_annotation' => 'vertical-bubble',
						'gp_expandto' => '',
						'twitter_size' => 'medium',
						'twitter_count' => 'vertical',
						'linkedin_counter' => 'top',
						'linkedin_showzero' => '1',
						'pin_button_shape' => 'rect',
						'pin_button_height' => 'large',
						'pin_count_layout' => 'above',
						'buffer_count' => 'vertical',
						'reddit_type' => 'static-tall-text',
						'managewp_type' => 'big',
						'tumblr_button_style' => 'share_2',
						'stumble_badge' => 5,
					),
				),
				'pre' => array(
					'email' => 'email', 
					'facebook' => 'fb', 
					'gplus' => 'gp',
					'twitter' => 'twitter',
					'instagram' => 'instgram',
					'linkedin' => 'linkedin',
					'myspace' => 'myspace',
					'pinterest' => 'pin',
					'pocket' => 'pocket',
					'buffer' => 'buffer',
					'reddit' => 'reddit',
					'managewp' => 'managewp',
					'stumbleupon' => 'stumble',
					'tumblr' => 'tumblr',
					'youtube' => 'yt',
					'skype' => 'skype',
					'vk' => 'vk',
				),
			),
			'wp' => array(				// wordpress
				'min_version' => '3.0',		// minimum wordpress version
				'cm' => array(
					'aim' => 'AIM',
					'jabber' => 'Google Talk',
					'yim' => 'Yahoo IM',
				),
				'admin' => array(
					'users' => array(
						'page' => 'users.php',
						'cap' => 'list_users',
					),
					'profile' => array(
						'page' => 'profile.php',
						'cap' => 'edit_posts',
					),
					'setting' => array(
						'page' => 'options-general.php',
						'cap' => 'manage_options',
					),
					'submenu' => array(
						'page' => 'admin.php',
						'cap' => 'manage_options',
					),
					'sitesubmenu' => array(
						'page' => 'admin.php',
						'cap' => 'manage_options',
					),
				),
			),
			'php' => array(				// php
				'min_version' => '4.1.0',	// minimum php version
			),
			'form' => array(
				'og_image_col_width' => '70px',
				'og_image_col_height' => '37px',
				'tooltip_class' => 'sucom_tooltip',
				'max_hashtags' => 10,
				'max_media_items' => 20,
				'yes_no' => array(
					'1' => 'Yes',
					'0' => 'No',
				),
				'time_by_name' => array(
					'hour' => 3600,
					'day' => 86400,
					'week' => 604800,
					'month' => 18144000,
				),
				'file_cache_hrs' => array(
					0 => 0,
					3600 => 1,
					7200 => 3,
					21600 => 6,
					32400 => 9,
					43200 => 12,
					86400 => 24,
					129600 => 36,
					172800 => 48,
					259200 => 72,
					604800 => 168,
				),
				'qualifiers' => array(
					'default' => '(default)',
					'no_images' => '(no images)',
					'no_videos' => '(no videos)',
					'settings' => '(settings value)',
					'disabled' => '(option disabled)',
				),
				'script_locations' => array(
					'none' => '[none]',
					'header' => 'Header',
					'footer' => 'Footer',
				),
				'caption_types' => array(
					'none' => '[none]',
					'title' => 'Title Only',
					'excerpt' => 'Excerpt Only',
					'both' => 'Title and Excerpt',
				),
				'user_name_fields' => array(
					'none' => '[none]',
					'fullname' => 'First and Last Names',
					'display_name' => 'Display Name',
					'nickname' => 'Nickname',
				),
				'show_options' => array(
					'basic' => 'Basic Options',
					'all' => 'All Options',
				),
				'site_option_use' => array(
					'default' => 'Default value',
					'empty' => 'If value is empty',
					'force' => 'Force this value',
				),
				'position_crop_x' => array(
					'left' => 'Left',
					'center' => 'Center',
					'right' => 'Right',
				),
				'position_crop_y' => array(
					'top' => 'Top',
					'center' => 'Center',
					'bottom' => 'Bottom',
				),
				'shorteners' => array(
					'none' => '[none]',
					'bitly' => 'Bit.ly (suggested)',
					'googl' => 'Goo.gl',
					'owly' => 'Ow.ly',
					'tinyurl' => 'TinyURL (slow)',
				),
			),
			'head' => array(
				'max_img_ratio' => 3,
				'min_img_dim' => 200,
				'min_desc_len' => 156,
				'og_type_ns' => array(		// from http://ogp.me/#types
					'article' => 'http://ogp.me/ns/article#',
					'book' => 'http://ogp.me/ns/book#',
					'music.album' => 'http://ogp.me/ns/music#',
					'music.playlist' => 'http://ogp.me/ns/music#',
					'music.radio_station' => 'http://ogp.me/ns/music#',
					'music.song' => 'http://ogp.me/ns/music#',
					'place' => 'http://ogp.me/ns/place#',		// for Facebook and Pinterest
					'product' => 'http://ogp.me/ns/product#',	// for Facebook and Pinterest
					'profile' => 'http://ogp.me/ns/profile#',
					'video.episode' => 'http://ogp.me/ns/video#',
					'video.movie' => 'http://ogp.me/ns/video#',
					'video.other' => 'http://ogp.me/ns/video#',
					'video.tv_show' => 'http://ogp.me/ns/video#',
					'website' => 'http://ogp.me/ns/website#',
				),
				'og_type_mt' => array(
					'article' => array(
						'article:author',
						'article:section',
						'article:tag',
						'article:published_time',
						'article:modified_time',
						'article:expiration_time',
					),
					'book' => array(
						'book:author',
						'book:isbn',
						'book:release_date',
						'book:tag',
					),
					'music.album' => array(
						'music:song',
						'music:song:disc',
						'music:song:track',
						'music:musician',
						'music:release_date',
					),
					'music.playlist' => array(
						'music:creator',
						'music:song',
						'music:song:disc',
						'music:song:track',
					),
					'music.radio_station' => array(
						'music:creator',
					),
					'music.song' => array(
						'music:album',
						'music:album:disc',
						'music:album:track',
						'music:duration',
						'music:musician',
					),
					'place' => array(
						'og:altitude',
						'og:latitude',
						'og:longitude',
						'place:location:latitude',
						'place:location:longitude',
						'place:location:altitude',
						'place:street_address',
						'place:locality',
						'place:region',
						'place:postal_code',
						'place:country_name',
					),
					'product' => array(
						'product:price:amount',
						'product:price:currency',
						'product:availability',
						'product:rating:average',
						'product:rating:count',
						'product:rating:worst',
						'product:rating:best',
					),
					'profile' => array(
						'profile:first_name',
						'profile:last_name',
						'profile:username',
						'profile:gender',
					),
					'video.episode' => array(
						'video:actor',
						'video:actor:role',
						'video:director',
						'video:writer',
						'video:duration',
						'video:release_date',
						'video:tag',
						'video:series',
					),
					'video.movie' => array(
						'video:actor',
						'video:actor:role',
						'video:director',
						'video:writer',
						'video:duration',
						'video:release_date',
						'video:tag',
					),
					'video.other' => array(
						'video:actor',
						'video:actor:role',
						'video:director',
						'video:writer',
						'video:duration',
						'video:release_date',
						'video:tag',
					),
					'video.tv_show' => array(
						'video:actor',
						'video:actor:role',
						'video:director',
						'video:writer',
						'video:duration',
						'video:release_date',
						'video:tag',
					),
				),
				'schema_type' => array(
					'article' => 'http://schema.org/Article',
					'book' => 'http://schema.org/Book',
					'blog' => 'http://schema.org/Blog',
					'event' => 'http://schema.org/Event',
					'local.business' => 'http://schema.org/LocalBusiness',
					'organization' => 'http://schema.org/Organization',
					'other' => 'http://schema.org/Other',
					'person' => 'http://schema.org/Person',
					'place' => 'http://schema.org/Place',
					'product' => 'http://schema.org/Product',
					'recipe' => 'http://schema.org/Recipe',
					'review' => 'http://schema.org/Review',
					'webpage' => 'http://schema.org/WebPage',
					'website' => 'http://schema.org/WebSite',
				),
			),
			'cache' => array(
				'file' => false,
				'object' => true,
				'transient' => true,
			),
			'follow' => array(
				'size' => 24,
				'src' => array(
					'images/follow/Wordpress.png' => 'https://profiles.wordpress.org/jsmoriss/',
					'images/follow/Github.png' => 'https://github.com/SurniaUlula',
					'images/follow/Facebook.png' => 'https://www.facebook.com/SurniaUlulaCom',
					'images/follow/GooglePlus.png' => 'https://plus.google.com/+SurniaUlula/',
					//'images/follow/Linkedin.png' => 'https://www.linkedin.com/company/surnia-ulula-ltd',
					'images/follow/Twitter.png' => 'https://twitter.com/surniaululacom',
					//'images/follow/Youtube.png' => 'https://www.youtube.com/user/SurniaUlulaCom',
					'images/follow/Rss.png' => 'http://surniaulula.com/category/application/wordpress/wp-plugins/ngfb/feed/',
				),
			),
			'sharing' => array(
				'show_on' => array( 
					'content' => 'Content', 
					'excerpt' => 'Excerpt', 
					'sidebar' => 'CSS Sidebar', 
					'admin_edit' => 'Admin Edit',
				),
				'style' => array(
					'sharing' => 'All Buttons',
					'content' => 'Content',
					'excerpt' => 'Excerpt',
					'sidebar' => 'CSS Sidebar',
					'admin_edit' => 'Admin Edit',
					'shortcode' => 'Shortcode',
					'widget' => 'Widget',
				),
				'position' => array(
					'top' => 'Top',
					'bottom' => 'Bottom',
					'both' => 'Both Top and Bottom',
				),
			),
		);

		// get_config is called very early, so don't apply filters unless instructed
		public static function get_config( $idx = false, $filter = false ) { 

			if ( ! isset( self::$cf['config_filtered'] ) || 
				self::$cf['config_filtered'] !== true ) {

				// remove the sharing libs if social sharing features are disabled
				// don't use SucomUtil::get_const() since the SucomUtil class may not be loaded yet
				if ( defined( 'NGFB_SOCIAL_SHARING_DISABLE' ) && NGFB_SOCIAL_SHARING_DISABLE ) {
					foreach ( array_keys( self::$cf['plugin'] ) as $lca ) {
						unset (
							self::$cf['plugin'][$lca]['lib']['website'],
							self::$cf['plugin'][$lca]['lib']['submenu']['sharing'],
							self::$cf['plugin'][$lca]['lib']['submenu']['style'],
							self::$cf['plugin'][$lca]['lib']['shortcode']['sharing'],
							self::$cf['plugin'][$lca]['lib']['widget']['sharing'],
							self::$cf['plugin'][$lca]['lib']['gpl']['admin']['sharing'],
							self::$cf['plugin'][$lca]['lib']['gpl']['admin']['style'],
							self::$cf['plugin'][$lca]['lib']['pro']['admin']['sharing'],
							self::$cf['plugin'][$lca]['lib']['pro']['admin']['style']
						);
					}
				}

				if ( $filter === true ) {
					self::$cf['opt']['version'] .= is_dir( trailingslashit( dirname( __FILE__ ) ).'pro/' ) ? 'pro' : 'gpl';
					self::$cf = apply_filters( self::$cf['lca'].'_get_config', self::$cf );
					self::$cf['config_filtered'] = true;
					self::$cf['*'] = array( 
						'base' => array(),
						'lib' => array(),
						'version' => '',
					);
					foreach ( self::$cf['plugin'] as $lca => $info ) {
						if ( isset( $info['base'] ) )
							self::$cf['*']['base'][$info['base']] = $lca;
						if ( isset( $info['lib'] ) && is_array( $info['lib'] ) )
							self::$cf['*']['lib'] = SucomUtil::array_merge_recursive_distinct( 
								self::$cf['*']['lib'], 
								$info['lib']
							);
						if ( isset( $info['version'] ) )
							self::$cf['*']['version'] .= '-'.$lca.$info['version'];
					}
					self::$cf['*']['version'] = trim( self::$cf['*']['version'], '-' );
				}

				// complete relative paths in the image array
				foreach ( self::$cf['plugin'] as $lca => $info ) {
					foreach ( $info['img'] as $id => $url )
						if ( ! empty( $url ) && strpos( $url, '//' ) === false )
							self::$cf['plugin'][$lca]['img'][$id] = trailingslashit( plugins_url( '', $info['base'] ) ).$url;
				}
			}

			if ( $idx !== false ) {
				if ( isset( self::$cf[$idx] ) )
					return self::$cf[$idx];
				else return null;
			} else return self::$cf;
		}

		public static function set_constants( $plugin_filepath ) { 
			define( 'NGFB_FILEPATH', $plugin_filepath );						
			define( 'NGFB_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'NGFB_PLUGINSLUG', self::$cf['plugin']['ngfb']['slug'] );		// nextgen-facebook
			define( 'NGFB_PLUGINBASE', self::$cf['plugin']['ngfb']['base'] );		// nextgen-facebook/nextgen-facebook.php
			define( 'NGFB_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
			define( 'NGFB_NONCE', md5( NGFB_PLUGINDIR.'-'.self::$cf['plugin']['ngfb']['version'].
				( defined( 'NONCE_SALT' ) ? NONCE_SALT : '' ) ) );
			self::set_variable_constants();
		}

		public static function set_variable_constants() { 
			foreach ( self::get_variable_constants() as $name => $value )
				if ( ! defined( $name ) )
					define( $name, $value );
		}

		public static function get_variable_constants() { 
			$var_const = array();

			if ( defined( 'NGFB_PLUGINDIR' ) ) {
				$var_const['NGFB_CACHEDIR'] = NGFB_PLUGINDIR.'cache/';
				$var_const['NGFB_TOPICS_LIST'] = NGFB_PLUGINDIR.'share/topics.txt';
			}

			if ( defined( 'NGFB_URLPATH' ) )
				$var_const['NGFB_CACHEURL'] = NGFB_URLPATH.'cache/';

			$var_const['NGFB_DEBUG_FILE_EXP'] = 300;
			$var_const['NGFB_MENU_ORDER'] = '99.11';
			$var_const['NGFB_MENU_ICON_HIGHLIGHT'] = true;
			$var_const['NGFB_SHARING_SHORTCODE'] = 'ngfb';		// used by social sharing features

			/*
			 * NGFB option and meta array names
			 */
			$var_const['NGFB_TS_NAME'] = 'ngfb_timestamps';
			$var_const['NGFB_OPTIONS_NAME'] = 'ngfb_options';
			$var_const['NGFB_SITE_OPTIONS_NAME'] = 'ngfb_site_options';
			$var_const['NGFB_NOTICE_NAME'] = 'ngfb_notices';	// stored notices
			$var_const['NGFB_DISMISS_NAME'] = 'ngfb_dismissed';	// dismissed notices
			$var_const['NGFB_META_NAME'] = '_ngfb_meta';		// post meta
			$var_const['NGFB_PREF_NAME'] = '_ngfb_pref';		// user meta

			/*
			 * NGFB option and meta array alternate names
			 */
			$var_const['NGFB_OPTIONS_NAME_ALT'] = 'wpsso_options';
			$var_const['NGFB_SITE_OPTIONS_NAME_ALT'] = 'wpsso_site_options';
			$var_const['NGFB_META_NAME_ALT'] = '_wpsso_meta';
			$var_const['NGFB_PREF_NAME_ALT'] = '_wpsso_pref';

			/*
			 * NGFB hook priorities
			 */
			$var_const['NGFB_ADD_MENU_PRIORITY'] = -20;
			$var_const['NGFB_ADD_SUBMENU_PRIORITY'] = -10;
			$var_const['NGFB_META_SAVE_PRIORITY'] = 6;
			$var_const['NGFB_META_CACHE_PRIORITY'] = 9;
			$var_const['NGFB_INIT_PRIORITY'] = 14;
			$var_const['NGFB_HEAD_PRIORITY'] = 10;
			$var_const['NGFB_SOCIAL_PRIORITY'] = 100;		// used by social sharing features
			$var_const['NGFB_FOOTER_PRIORITY'] = 100;		// used by social sharing features
			$var_const['NGFB_SEO_FILTERS_PRIORITY'] = 100;

			/*
			 * NGFB curl settings
			 */
			if ( defined( 'NGFB_PLUGINDIR' ) )
				$var_const['NGFB_CURL_CAINFO'] = NGFB_PLUGINDIR.'share/curl/ca-bundle.crt';
			$var_const['NGFB_CURL_USERAGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:40.0) Gecko/20100101 Firefox/40.0';

			// disable 3rd-party caching for duplicate meta tag checks
			if ( ! empty( $_GET['NGFB_META_TAGS_DISABLE'] ) ) {
				$var_const['DONOTCACHEPAGE'] = true;		// wp super cache
				$var_const['QUICK_CACHE_ALLOWED'] = false;	// quick cache
				$var_const['ZENCACHE_ALLOWED'] = false;		// zencache
			}

			foreach ( $var_const as $name => $value )
				if ( defined( $name ) )
					$var_const[$name] = constant( $name );	// inherit existing values

			return $var_const;
		}

		public static function require_libs( $plugin_filepath ) {
			
			require_once( NGFB_PLUGINDIR.'lib/com/util.php' );
			require_once( NGFB_PLUGINDIR.'lib/com/cache.php' );
			require_once( NGFB_PLUGINDIR.'lib/com/script.php' );
			require_once( NGFB_PLUGINDIR.'lib/com/style.php' );
			require_once( NGFB_PLUGINDIR.'lib/com/webpage.php' );

			require_once( NGFB_PLUGINDIR.'lib/register.php' );
			require_once( NGFB_PLUGINDIR.'lib/check.php' );
			require_once( NGFB_PLUGINDIR.'lib/util.php' );
			require_once( NGFB_PLUGINDIR.'lib/options.php' );
			require_once( NGFB_PLUGINDIR.'lib/meta.php' );
			require_once( NGFB_PLUGINDIR.'lib/post.php' );		// extends meta.php
			require_once( NGFB_PLUGINDIR.'lib/taxonomy.php' );	// extends meta.php
			require_once( NGFB_PLUGINDIR.'lib/user.php' );		// extends meta.php
			require_once( NGFB_PLUGINDIR.'lib/media.php' );
			require_once( NGFB_PLUGINDIR.'lib/head.php' );
			require_once( NGFB_PLUGINDIR.'lib/opengraph.php' );
			require_once( NGFB_PLUGINDIR.'lib/twittercard.php' );
			require_once( NGFB_PLUGINDIR.'lib/schema.php' );
			require_once( NGFB_PLUGINDIR.'lib/functions.php' );

			if ( is_admin() ) {
				require_once( NGFB_PLUGINDIR.'lib/messages.php' );
				require_once( NGFB_PLUGINDIR.'lib/admin.php' );
				require_once( NGFB_PLUGINDIR.'lib/com/form.php' );
				require_once( NGFB_PLUGINDIR.'lib/ext/parse-readme.php' );
			}

			if ( ! SucomUtil::get_const( 'NGFB_SOCIAL_SHARING_DISABLE' ) &&
				empty( $_SERVER['NGFB_SOCIAL_SHARING_DISABLE'] ) &&
					file_exists( NGFB_PLUGINDIR.'lib/sharing.php' ) )
						require_once( NGFB_PLUGINDIR.'lib/sharing.php' );

			if ( file_exists( NGFB_PLUGINDIR.'lib/loader.php' ) )
				require_once( NGFB_PLUGINDIR.'lib/loader.php' );

			add_filter( 'ngfb_load_lib', array( 'NgfbConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( $ret === false && ! empty( $filespec ) ) {
				$filepath = NGFB_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once( $filepath );
					if ( empty( $classname ) )
						return SucomUtil::sanitize_classname( 'ngfb'.$filespec );
					else return $classname;
				}
			}
			return $ret;
		}
	}
}

?>
