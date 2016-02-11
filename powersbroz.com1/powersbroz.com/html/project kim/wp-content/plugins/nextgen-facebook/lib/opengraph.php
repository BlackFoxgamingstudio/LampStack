<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbOpengraph' ) ) {

	class NgfbOpengraph {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			$this->p->util->add_plugin_filters( $this, array( 
				'plugin_image_sizes' => 1,
			) );

			if ( ! empty( $this->p->options['plugin_html_attr_filter_name'] ) &&
				$this->p->options['plugin_html_attr_filter_name'] !== 'none' ) {

					$prio = empty( $this->p->options['plugin_html_attr_filter_prio'] ) ? 
						100 : $this->p->options['plugin_html_attr_filter_prio'];

					// add open graph namespace attributes to the <html> tag
					add_filter( $this->p->options['plugin_html_attr_filter_name'], 
						array( &$this, 'add_html_attributes' ), $prio, 1 );

			} elseif ( $this->p->debug->enabled )
				$this->p->debug->log( 'add_html_attributes skipped: plugin_html_attr_filter_name option is empty' );
		}

		public function filter_plugin_image_sizes( $sizes ) {
			if ( ! SucomUtil::get_const( 'NGFB_RICH_PIN_DISABLE' ) ) {
				$sizes['rp_img'] = array(
					'name' => 'richpin',
					'label' => _x( 'Pinterest Rich Pin',
						'image size label', 'nextgen-facebook' ),
				);
			}
			$sizes['og_img'] = array( 
				'name' => 'opengraph', 
				'label' => _x( 'Facebook / Open Graph',
					'image size label', 'nextgen-facebook' ),
			);
			return $sizes;
		}

		public function add_html_attributes( $html_attr ) {

			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			
			$prefix_ns = apply_filters( $this->p->cf['lca'].'_og_prefix_ns', array(
				'og' => 'http://ogp.me/ns#',
				'fb' => 'http://ogp.me/ns/fb#',
			) );
	
			// find and extract an existing prefix attribute value
			if ( strpos( $html_attr, ' prefix=' ) &&
				preg_match( '/^(.*) prefix=["\']([^"\']*)["\'](.*)$/', $html_attr, $match ) ) {
					$html_attr = $match[1].$match[3];
					$prefix_value = ' '.$match[2];
			} else $prefix_value = '';

			foreach ( $prefix_ns as $ns => $url )
				if ( strpos( $prefix_value, ' '.$ns.': '.$url ) === false )
					$prefix_value .= ' '.$ns.': '.$url;

			$html_attr .= ' prefix="'.trim( $prefix_value ).'"';

			return trim( $html_attr );
		}

		public function get_array( $use_post = false, $obj = false, &$og = array() ) {

			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			if ( ! is_object( $obj ) )
				$obj = $this->p->util->get_post_object( $use_post );
			$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ||
				! SucomUtil::is_post_page( $use_post ) ? 0 : $obj->ID;

			// counter for video previews found
			$video_previews = 0;

			// a post_id of 0 returns the default plugin settings 
			$max = $this->p->util->get_max_nums( $post_id, 'post' );
			$og = apply_filters( $this->p->cf['lca'].'_og_seed', $og, $use_post, $obj );

			if ( ! empty( $og ) && 
				$this->p->debug->enabled ) {
					$this->p->debug->log( $this->p->cf['lca'].'_og_seed filter returned:' );
					$this->p->debug->log( $og );
			}

			if ( ! isset( $og['fb:admins'] ) && ! empty( $this->p->options['fb_admins'] ) )
				foreach ( explode( ',', $this->p->options['fb_admins'] ) as $fb_admin )
					$og['fb:admins'][] = trim( $fb_admin );

			if ( ! isset( $og['fb:app_id'] ) )
				$og['fb:app_id'] = $this->p->options['fb_app_id'];

			if ( ! isset( $og['og:url'] ) )
				$og['og:url'] = $this->p->util->get_sharing_url( $use_post, true, 
					$this->p->util->get_source_id( 'opengraph' ) );

			// define the type after the url
			if ( ! isset( $og['og:type'] ) ) {

				// singular posts / pages are articles by default
				// check the post_type for a match with a known open graph type
				if ( SucomUtil::is_post_page( $use_post ) ) {
					if ( ! empty( $obj->post_type ) && 
						isset( $this->p->cf['head']['og_type_ns'][$obj->post_type] ) )
							$og['og:type'] = $obj->post_type;
					else $og['og:type'] = 'article';

				// check for default author info on indexes and searches
				} elseif ( $this->p->util->force_default_author( $use_post, 'og' ) &&
					! empty( $this->p->options['og_def_author_id'] ) ) {

					$og['og:type'] = 'article';

					// meta tag not defined or value is null
					if ( ! isset( $og['article:author'] ) )
						$og['article:author'] = $this->p->mods['util']['user']->get_author_profile_url( $this->p->options['og_def_author_id'] );

				// default for everything else is 'website'
				} else $og['og:type'] = 'website';

				$og['og:type'] = apply_filters( $this->p->cf['lca'].'_og_type', $og['og:type'], $use_post );

				// pre-define basic open graph meta tags for this type
				if ( isset( $this->p->cf['head']['og_type_mt'][$og['og:type']] ) ) {
					foreach( $this->p->cf['head']['og_type_mt'][$og['og:type']] as $mt_name ) {
						if ( ! isset( $og[$mt_name] ) ) {
							$og[$mt_name] = null;
							if ( $this->p->debug->enabled )
								$this->p->debug->log( $og['og:type'].' pre-defined mt: '.$mt_name );
						}
					}
				}
			}

			if ( ! isset( $og['og:locale'] ) ) {
				// get the current or configured language for og:locale
				$lang = empty( $this->p->options['fb_lang'] ) ? 
					SucomUtil::get_locale( $post_id ) : $this->p->options['fb_lang'];

				$lang = apply_filters( $this->p->cf['lca'].'_lang', 
					$lang, SucomUtil::get_pub_lang( 'facebook' ), $post_id );

				$og['og:locale'] = $lang;
			}

			if ( ! isset( $og['og:site_name'] ) )
				$og['og:site_name'] = $this->get_site_name( $post_id );

			if ( ! isset( $og['og:title'] ) )
				$og['og:title'] = $this->p->webpage->get_title( $this->p->options['og_title_len'], '...', $use_post );

			if ( ! isset( $og['og:description'] ) )
				$og['og:description'] = $this->p->webpage->get_description( $this->p->options['og_desc_len'], '...', $use_post );

			// if the page is an article, then define the other article meta tags
			if ( isset( $og['og:type'] ) && 
				$og['og:type'] == 'article' ) {

				// meta tag not defined or value is null
				if ( ! isset( $og['article:author'] ) ) {
					if ( SucomUtil::is_post_page( $use_post ) ) {
						if ( ! empty( $obj->post_author ) )
							$og['article:author'] = $this->p->mods['util']['user']->get_author_profile_url( $obj->post_author );
						elseif ( ! empty( $this->p->options['og_def_author_id'] ) )
							$og['article:author'] = $this->p->mods['util']['user']->get_author_profile_url( $this->p->options['og_def_author_id'] );
					}
				}

				// meta tag not defined or value is null
				if ( ! isset( $og['article:publisher'] ) )
					$og['article:publisher'] = $this->p->options['fb_publisher_url'];

				// meta tag not defined or value is null
				if ( ! isset( $og['article:tag'] ) )
					$og['article:tag'] = $this->p->webpage->get_tags( $post_id );

				// meta tag not defined or value is null
				if ( ! isset( $og['article:section'] ) )
					$og['article:section'] = $this->p->webpage->get_section( $post_id );

				// meta tag not defined or value is null
				if ( ! isset( $og['article:published_time'] ) )
					$og['article:published_time'] = trim( get_post_time( 'c', null, $post_id ) );

				// meta tag not defined or value is null
				if ( ! isset( $og['article:modified_time'] ) )
					$og['article:modified_time'] = trim( get_post_modified_time( 'c', null, $post_id ) );
			}

			// get all videos
			// call before getting all images to find / use preview images
			if ( ! isset( $og['og:video'] ) ) {
				if ( empty( $max['og_vid_max'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'videos disabled: maximum videos = 0' );
				} else {
					$og['og:video'] = $this->get_all_videos( $max['og_vid_max'], $post_id, 'post', false, 'og' );
					if ( ! empty( $og['og:video'] ) && is_array( $og['og:video'] ) ) {
						foreach ( $og['og:video'] as $val )
							if ( ! empty( $val['og:image'] ) )
								$video_previews++;
						if ( $video_previews > 0 ) {
							$max['og_img_max'] -= $video_previews;
							if ( $this->p->debug->enabled )
								$this->p->debug->log( $video_previews.
									' video preview images found (og_img_max adjusted to '.$max['og_img_max'].')' );
						}
					}
				} 
			}

			// get all images
			if ( ! isset( $og['og:image'] ) ) {
				if ( empty( $max['og_img_max'] ) ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'images disabled: maximum images = 0' );
				} else {
					$crawler_name = SucomUtil::crawler_name();

					if ( ! SucomUtil::get_const( 'NGFB_RICH_PIN_DISABLE' ) ) {
						if ( is_admin() )
							$img_sizes = array(
								'rp' => $this->p->cf['lca'].'-richpin',
								'og' => $this->p->cf['lca'].'-opengraph',
							);
						elseif ( $crawler_name === 'pinterest' )
							$img_sizes = array( 'og' => $this->p->cf['lca'].'-richpin' );	// use the pinterest image size
						else $img_sizes = array( 'og' => $this->p->cf['lca'].'-opengraph' );
					} else $img_sizes = array( 'og' => $this->p->cf['lca'].'-opengraph' );

					$size_count = count( $img_sizes );
					$size_num = 0;
					foreach ( $img_sizes as $md_pre => $size_name ) {
						$check_dupes = ++$size_num < $size_count ?
							false : true;

						if ( $this->p->debug->enabled )
							$this->p->debug->log( 'getting all images for '.$md_pre.' ('.$size_name.')' );

						$og[$md_pre.':image'] = $this->get_all_images( $max['og_img_max'], 
							$size_name, $post_id, $check_dupes, $md_pre );

						switch ( $md_pre ) {
							case 'rp':
								foreach ( $og[$md_pre.':image'] as $num => $arr )
									$og[$md_pre.':image'][$num] = SucomUtil::preg_grep_keys( '/^og:/',
										$arr, false, 'pinterest:' );
								break;
							case 'og':
								// if there's no image, and no video preview image, 
								// then add the default image for singular webpages
								if ( empty( $og[$md_pre.':image'] ) && $video_previews === 0 && 
									SucomUtil::is_post_page( $use_post ) ) {
									$og[$md_pre.':image'] = $this->p->media->get_default_image( $max['og_img_max'],
										$size_name, $check_dupes );
								}
								break;
						}
					}
				} 
			}

			return apply_filters( $this->p->cf['lca'].'_og', $og, $use_post, $obj );
		}

		public function get_all_videos( $num = 0, $id, $mod = 'post', $check_dupes = true, $md_pre = 'og', $force_prev_img = false ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->args( array( 
					'num' => $num,
					'id' => $id,
					'mod' => $mod,
					'check_dupes' => $check_dupes,
					'md_pre' => $md_pre,
					'force_prev_img' => $force_prev_img,
				) );
			}

			$og_ret = array();
			$use_prev_img = $this->p->options['og_vid_prev_img'];	// default value
			$num_remains = $this->p->media->num_remains( $og_ret, $num );

			// video modules are not available in the free version
			if ( $this->p->check->aop() ) {
				list( $id, $mod_obj ) = $this->p->util->get_mod_obj( $id, $mod );
				if ( ! empty( $mod_obj ) ) {
					if ( ( $mod_prev_img = $mod_obj->get_options( $id, 'og_vid_prev_img' ) ) !== null ) {
						$use_prev_img = $mod_prev_img;
						if ( $this->p->debug->enabled )
							$this->p->debug->log( 'setting use_prev_img to '.
								$use_prev_img.' from meta data' );
					}
					$og_ret = array_merge( $og_ret, 
						$mod_obj->get_og_video( $num_remains, 
							$id, $check_dupes, $md_pre ) );
				}
			}

			if ( count( $og_ret ) < 1 && $this->p->util->force_default_video() )
				return array_merge( $og_ret, $this->p->media->get_default_video( $num_remains, $check_dupes ) );

			$num_remains = $this->p->media->num_remains( $og_ret, $num );

			// if we haven't reached the limit of videos yet, keep going
			if ( $mod === 'post' && 
				! $this->p->util->is_maxed( $og_ret, $num ) )
					$og_ret = array_merge( $og_ret, 
						$this->p->media->get_content_videos( $num_remains, 
							$id, $check_dupes ) );

			$this->p->util->slice_max( $og_ret, $num );

			if ( empty( $use_prev_img ) && $force_prev_img === false ) {
				if ( $this->p->debug->enabled )
					$this->p->debug->log( 'use_prev_img is 0 and force_prev_img is false - removing video preview images' );
				foreach ( $og_ret as $num => $og_video ) {
					unset ( 
						$og_ret[$num]['og:image'],
						$og_ret[$num]['og:image:secure_url'],
						$og_ret[$num]['og:image:width'],
						$og_ret[$num]['og:image:height']
					);
				}
			}

			if ( ! empty( $this->p->options['og_vid_html_type'] ) ) {
				$og_extend = array();
				foreach ( $og_ret as $num => $og_video ) {
					$og_extend[] = $og_video;
					if ( ! empty( $og_video['og:video:embed_url'] ) ) {
						$og_embed['og:video:url'] = $og_video['og:video:embed_url'];
						$og_embed['og:video:type'] = 'text/html';		// define the type after the url
						foreach ( array( 'og:video:width', 'og:video:height' ) as $key ) 
							if ( isset( $og_video[$key] ) )
								$og_embed[$key] = $og_video[$key];
						$og_extend[] = $og_embed;
					}
				}
				return $og_extend;
			} else return $og_ret;
		}

		public function get_all_images( $num = 0, $size_name = 'thumbnail', $post_id, $check_dupes = true, $md_pre = 'og' ) {

			if ( $this->p->debug->enabled )
				$this->p->debug->args( array(
					'num' => $num,
					'size_name' => $size_name,
					'post_id' => $post_id,
					'check_dupes' => $check_dupes,
					'md_pre' => $md_pre,
				) );

			$og_ret = array();
			$force_regen = false;
			$num_remains = $this->p->media->num_remains( $og_ret, $num );

			// if requesting images for a specific post_id
			if ( SucomUtil::is_post_page( $post_id ) ) {

				// is_attachment() only works on the front-end, so check the post_type as well
				if ( ( is_attachment( $post_id ) || get_post_type( $post_id ) === 'attachment' ) && 
					wp_attachment_is_image( $post_id ) ) {

					$og_image = $this->p->media->get_attachment_image( $num_remains, 
						$size_name, $post_id, $check_dupes );

					// exiting early
					if ( empty( $og_image ) )
						return array_merge( $og_ret, $this->p->media->get_default_image( $num_remains, 
							$size_name, $check_dupes, $force_regen ) );
					else return array_merge( $og_ret, $og_image );
				}

				// check for custom meta, featured, or attached image(s)
				// allow for empty post_id in order to execute featured/attached image filters for modules
				if ( ! $this->p->util->is_maxed( $og_ret, $num ) )
					$og_ret = array_merge( $og_ret, $this->p->media->get_post_images( $num_remains, 
						$size_name, $post_id, $check_dupes, $md_pre ) );

				// check for ngg shortcodes and query vars
				if ( ! $this->p->util->is_maxed( $og_ret, $num ) &&
					$this->p->is_avail['media']['ngg'] && ! empty( $this->p->mods['media']['ngg'] ) ) {
	
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'checking for ngg shortcodes and query vars' );
	
					// ngg pre-v2 used query arguments
					$ngg_query_og_ret = array();
					$num_remains = $this->p->media->num_remains( $og_ret, $num );
					if ( version_compare( $this->p->mods['media']['ngg']->ngg_version, '2.0.0', '<' ) )
						$ngg_query_og_ret = $this->p->mods['media']['ngg']->get_query_images( $num_remains, 
							$size_name, $post_id, $check_dupes );
	
					// if we found images in the query, skip content shortcodes
					if ( count( $ngg_query_og_ret ) > 0 ) {
						if ( $this->p->debug->enabled )
							$this->p->debug->log( count( $ngg_query_og_ret ).
								' image(s) returned - skipping additional shortcode images' );
						$og_ret = array_merge( $og_ret, $ngg_query_og_ret );
	
					// if no query images were found, continue with ngg shortcodes in content
					} elseif ( ! $this->p->util->is_maxed( $og_ret, $num ) ) {
						$num_remains = $this->p->media->num_remains( $og_ret, $num );
						$og_ret = array_merge( $og_ret, 
							$this->p->mods['media']['ngg']->get_shortcode_images( $num_remains, 
								$size_name, $post_id, $check_dupes ) );
					}
				} // end of check for ngg shortcodes and query vars
	
				// if we haven't reached the limit of images yet, keep going and check the content text
				if ( ! $this->p->util->is_maxed( $og_ret, $num ) ) {
					$num_remains = $this->p->media->num_remains( $og_ret, $num );
					$og_ret = array_merge( $og_ret, $this->p->media->get_content_images( $num_remains, 
						$size_name, $post_id, $check_dupes ) );
				}

			} else {
				// check for priority media before using the default image
				if ( SucomUtil::is_term_page() ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'is_term_page() = true' );
					$term_id = $this->p->util->get_term_object( 'id' );
					$og_ret = array_merge( $og_ret, $this->p->mods['util']['taxonomy']->get_og_image( $num_remains, 
						$size_name, $term_id, $check_dupes, $force_regen, $md_pre ) );
	
					if ( ! $this->p->util->is_maxed( $og_ret, $num ) )
						$og_ret = array_merge( $og_ret, $this->p->mods['util']['taxonomy']->get_term_images( $num_remains, 
							$size_name, $term_id, $check_dupes, $force_regen, $md_pre ) );

				} elseif ( SucomUtil::is_author_page() ) {
					if ( $this->p->debug->enabled )
						$this->p->debug->log( 'is_author_page() = true' );
					$author_id = $this->p->util->get_author_object( 'id' );
					$og_ret = array_merge( $og_ret, $this->p->mods['util']['user']->get_og_image( $num_remains, 
						$size_name, $author_id, $check_dupes, $force_regen, $md_pre ) );
				}
	
				if ( count( $og_ret ) < 1 && $this->p->util->force_default_image() ) {
					return array_merge( $og_ret, $this->p->media->get_default_image( $num_remains, 
						$size_name, $check_dupes, $force_regen ) );
				}
	
				$num_remains = $this->p->media->num_remains( $og_ret, $num );
			}

			$this->p->util->slice_max( $og_ret, $num );

			return $og_ret;
		}

		public function get_site_name( $get = 'current' ) {
			// provide options array to allow fallback if locale option does not exist
			$key = SucomUtil::get_locale_key( 'og_site_name', $this->p->options, $get );
			if ( ! empty( $this->p->options[$key] ) )
				return $this->p->options[$key];
			else return get_bloginfo( 'name', 'display' );
		}

		// the returned array can include a varying number of elements, depending on the $items value
		public function get_the_media_urls( $size_name = 'thumbnail', 
			$post_id, $md_pre = 'og', $items = array( 'image', 'video' ) ) {

			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			
			$ret = array();
			foreach ( $items as $item ) {
				switch ( $item ) {
					case 'pid':
					case 'image':
						if ( ! isset( $og_image ) )
							$og_image = $this->get_all_images( 1, $size_name, $post_id, false, $md_pre );
						break;
					case 'video':
					case 'preview':
						if ( ! isset( $og_video ) )
							$og_video = $this->get_all_videos( 1, $post_id, 'post', false, $md_pre, true );
						break;
				}
				switch ( $item ) {
					case 'pid':
						$ret[] = $this->get_og_media_url( 'pid', $og_image );
						break;
					case 'image':
						$ret[] = $this->get_og_media_url( 'image', $og_image );
						break;
					case 'video':
						$ret[] = $this->get_og_media_url( 'video', $og_video );
						break;
					case 'preview':
						$ret[] = $this->get_og_media_url( 'image', $og_video );
						break;
					default:
						$ret[] = '';
						break;
				}
			}

			if ( $this->p->debug->enabled )
				$this->p->debug->log( $ret );

			return $ret;
		}

		public function get_og_media_url( $name, $og, $mt_pre = 'og' ) {
			if ( ! empty( $og ) && is_array( $og ) ) {
				$media = reset( $og );
				switch ( $name ) {
					case 'pid':
						$search = array(
							$mt_pre.':image:id',
						);
						break;
					default:
						$search = array(
							$mt_pre.':'.$name.':secure_url',
							$mt_pre.':'.$name.':url',
							$mt_pre.':'.$name,
						);
						break;
				}
				foreach ( $search as $key )
					if ( isset( $media[$key] ) &&
						! empty( $media[$key] ) &&
							$media[$key] != -1 )
								return $media[$key];
			}
			return '';
		}
	}
}

?>
