<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbGplAdminAdvanced' ) ) {

	class NgfbGplAdminAdvanced {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 
				'plugin_content_rows' => 2,
				'plugin_social_rows' => 2,
				'plugin_integration_rows' => 2,
				'plugin_cache_rows' => 3,
				'plugin_apikeys_rows' => 2,
				'cm_custom_rows' => 2,
				'cm_builtin_rows' => 2,
				'taglist_tags_rows' => 4,
			), 20 );
		}

		public function filter_plugin_content_rows( $rows, $form ) {

			$rows[] = '<td colspan="2" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Use Filtered (SEO) Title',
				'option label', 'nextgen-facebook' ), null, 'plugin_filter_title' ).
			$this->get_nocb_cell( 'plugin_filter_title' );
			
			$rows[] = $this->p->util->get_th( _x( 'Apply WordPress Content Filters',
				'option label', 'nextgen-facebook' ), null, 'plugin_filter_content' ).
			$this->get_nocb_cell( 'plugin_filter_content' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Apply WordPress Excerpt Filters',
				'option label', 'nextgen-facebook' ), null, 'plugin_filter_excerpt' ).
			$this->get_nocb_cell( 'plugin_filter_excerpt' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Content Starts at 1st Paragraph',
				'option label', 'nextgen-facebook' ), null, 'plugin_p_strip' ).
			$this->get_nocb_cell( 'plugin_p_strip' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Use Image Alt if No Content',
				'option label', 'nextgen-facebook' ), null, 'plugin_use_img_alt' ).
			$this->get_nocb_cell( 'plugin_use_img_alt' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Image Alt Text Prefix',
				'option label', 'nextgen-facebook' ), null, 'plugin_img_alt_prefix' ).
			'<td class="blank">'.$form->options['plugin_img_alt_prefix'].'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'WP Caption Paragraph Prefix',
				'option label', 'nextgen-facebook' ), null, 'plugin_p_cap_prefix' ).
			'<td class="blank">'.$form->options['plugin_p_cap_prefix'].'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Check for Embedded Media',
				'option label', 'nextgen-facebook' ), null, 'plugin_embedded_media' ).
			'<td class="blank">'.
			'<p>'.$this->get_nocb( 'plugin_slideshare_api' ).' Slideshare Presentations</p>'.
			'<p>'.$this->get_nocb( 'plugin_vimeo_api' ).' Vimeo Videos</p>'.
			'<p>'.$this->get_nocb( 'plugin_wistia_api' ).' Wistia Videos</p>'.
			'<p>'.$this->get_nocb( 'plugin_youtube_api' ).' YouTube Videos and Playlists</p>'.
			'</td>';

			return $rows;
		}

		public function filter_plugin_social_rows( $rows, $form, $network = false ) {

			$rows[] = '<td colspan="2" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			if ( $network ) {
				$rows[] = $this->p->util->get_th( _x( 'Show Social Img / Desc Columns for',
					'option label', 'nextgen-facebook' ), null, 'plugin_social_columns',
						array( 'rowspan' => 3 ) ).
				$this->get_nocb_cell( 'plugin_columns_post', 
					__( 'Posts, Pages, and Custom Post Types', 'nextgen-facebook' ) ).
				$this->p->admin->get_site_use( $form, $network, 'plugin_columns_post' );
	
				$rows[] = '<tr class="hide_in_basic">'.
				$this->get_nocb_cell( 'plugin_columns_taxonomy', 
					__( 'Taxonomy (Categories and Tags)', 'nextgen-facebook' ) ).
				$this->p->admin->get_site_use( $form, $network, 'plugin_columns_taxonomy' );
	
				$rows[] = '<tr class="hide_in_basic">'.
				$this->get_nocb_cell( 'plugin_columns_user', 
					__( 'Users' ) ).
				$this->p->admin->get_site_use( $form, $network, 'plugin_columns_user' );
			} else {
				$rows[] = $this->p->util->get_th( _x( 'Show Social Img / Desc Columns for',
					'option label', 'nextgen-facebook' ), null, 'plugin_social_columns' ).
				'<td class="blank">'.
				'<p>'.$this->get_nocb( 'plugin_columns_post', 
					__( 'Posts, Pages, and Custom Post Types', 'nextgen-facebook' ) ).'</p>'.
				'<p>'.$this->get_nocb( 'plugin_columns_taxonomy',
					__( 'Taxonomy (Categories and Tags)', 'nextgen-facebook' ) ).'</p>'.
				'<p>'.$this->get_nocb( 'plugin_columns_user',
					__( 'Users' ) ).'</p>'.
				'</td>';
			}

			$checkboxes = '';
			foreach ( $this->p->util->get_post_types() as $post_type )
				$checkboxes .= '<p>'.$this->get_nocb( 'plugin_add_to_'.$post_type->name ).' '.
					$post_type->label.' '.( empty( $post_type->description ) ?
						'' : '('.$post_type->description.')' ).'</p>';

			$checkboxes .= '<p>'.$this->get_nocb( 'plugin_add_to_taxonomy' ).
				' '.__( 'Taxonomy (Categories and Tags)', 'nextgen-facebook' ).'</p>';

			$checkboxes .= '<p>'.$this->get_nocb( 'plugin_add_to_user' ).
				' '.__( 'User Profile', 'nextgen-facebook' ).'</p>';

			$rows[] = $this->p->util->get_th( _x( 'Include Social Settings Metabox on',
				'option label', 'nextgen-facebook' ), null, 'plugin_add_to' ).
			'<td class="blank">'.$checkboxes.'</td>';
			
			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Add Tabs to Social Settings Metabox',
				'option label', 'nextgen-facebook' ), null, 'plugin_add_tab' ).
			'<td class="blank">'.
			'<p>'.$this->get_nocb( 'plugin_add_tab_preview' ).' '.
				_x( 'Social Preview', 'metabox tab', 'nextgen-facebook' ).'</p>'.
			'<p>'.$this->get_nocb( 'plugin_add_tab_tags' ).' '.
				_x( 'Head Tags', 'metabox tab', 'nextgen-facebook' ).'</p>'.
			'<p>'.$this->get_nocb( 'plugin_add_tab_validate' ).' '.
				_x( 'Validate', 'metabox tab', 'nextgen-facebook' ).'</p>'.
			'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Image URL Custom Field',
				'option label', 'nextgen-facebook' ), null, 'plugin_cf_img_url' ).
			'<td class="blank">'.$form->get_hidden( 'plugin_cf_img_url' ).
				$this->p->options['plugin_cf_img_url'].'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Video URL Custom Field',
				'option label', 'nextgen-facebook' ), null, 'plugin_cf_vid_url' ).
			'<td class="blank">'.$form->get_hidden( 'plugin_cf_vid_url' ).
				$this->p->options['plugin_cf_vid_url'].'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Video Embed HTML Custom Field',
				'option label', 'nextgen-facebook' ), null, 'plugin_cf_vid_embed' ).
			'<td class="blank">'.$form->get_hidden( 'plugin_cf_vid_embed' ).
				$this->p->options['plugin_cf_vid_embed'].'</td>';
			
			return $rows;
		}

		public function filter_plugin_integration_rows( $rows, $form ) {

			$rows[] = '<td colspan="3" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( '&lt;html&gt; Attributes Filter Hook',
				'option label', 'nextgen-facebook' ), null, 'plugin_html_attr_filter' ).
			'<td class="blank">Name:&nbsp;'.$this->p->options['plugin_html_attr_filter_name'].', '.
				'Priority:&nbsp;'.$this->p->options['plugin_html_attr_filter_prio'].'</td>';

			$rows[] = $this->p->util->get_th( _x( '&lt;head&gt; Attributes Filter Hook',
				'option label', 'nextgen-facebook' ), null, 'plugin_head_attr_filter' ).
			'<td class="blank">Name:&nbsp;'.$this->p->options['plugin_head_attr_filter_name'].', '.
				'Priority:&nbsp;'.$this->p->options['plugin_head_attr_filter_prio'].'</td>';
			
			$rows[] = $this->p->util->get_th( _x( 'Check for Duplicate Meta Tags',
				'option label', 'nextgen-facebook' ), null, 'plugin_check_head' ).
			$this->get_nocb_cell( 'plugin_check_head' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Use WP Locale for Language',
				'option label', 'nextgen-facebook' ), null, 'plugin_filter_lang' ).
			$this->get_nocb_cell( 'plugin_filter_lang' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Create Missing WP Media Images',
				'option label', 'nextgen-facebook' ), null, 'plugin_auto_img_resize' ).
			$this->get_nocb_cell( 'plugin_auto_img_resize' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Enforce Image Dimensions Check',
				'option label', 'nextgen-facebook' ), null, 'plugin_ignore_small_img' ).
			$this->get_nocb_cell( 'plugin_ignore_small_img' );

			$rows[] = $this->p->util->get_th( _x( 'Allow Upscaling of Small WP Images',
				'option label', 'nextgen-facebook' ), null, 'plugin_upscale_images' ).
			$this->get_nocb_cell( 'plugin_upscale_images' ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Maximum Image Upscale Percentage',
				'option label', 'nextgen-facebook' ), null, 'plugin_upscale_img_max' ).
			'<td class="blank">'.$this->p->options['plugin_upscale_img_max'].' %</td>';

			if ( ! empty( $this->p->cf['*']['lib']['shortcode'] ) ) {
				$rows[] = '<tr class="hide_in_basic">'.
				$this->p->util->get_th( _x( 'Enable Plugin Shortcode(s)',
					'option label', 'nextgen-facebook' ), null, 'plugin_shortcodes' ).
				$this->get_nocb_cell( 'plugin_shortcodes' );
			}

			if ( ! empty( $this->p->cf['*']['lib']['widget'] ) ) {
				$rows[] = '<tr class="hide_in_basic">'.
				$this->p->util->get_th( _x( 'Enable Plugin Widget(s)',
					'option label', 'nextgen-facebook' ), null, 'plugin_widgets' ).
				$this->get_nocb_cell( 'plugin_widgets' );
			}

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Enable WP Excerpt for Pages',
				'option label', 'nextgen-facebook' ), null, 'plugin_page_excerpt' ).
			$this->get_nocb_cell( 'plugin_page_excerpt' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Enable WP Tags for Pages',
				'option label', 'nextgen-facebook' ), null, 'plugin_page_tags' ).
			$this->get_nocb_cell( 'plugin_page_tags' );

			return $rows;
		}

		public function filter_plugin_cache_rows( $rows, $form, $network = false ) {

			$rows[] = '<td colspan="'.( $network ? 4 : 2 ).'" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg', array( 'lca' => 'ngfb' ) ).'</td>';

			$rows['plugin_object_cache_exp'] = $this->p->util->get_th( _x( 'Object Cache Expiry',
				'option label', 'nextgen-facebook' ), null, 'plugin_object_cache_exp' ).
			'<td nowrap class="blank">'.$this->p->options['plugin_object_cache_exp'].' seconds</td>'.
			$this->p->admin->get_site_use( $form, $network, 'plugin_object_cache_exp' );

			$rows['plugin_verify_certs'] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Verify Peer SSL Certificate',
				'option label', 'nextgen-facebook' ), null, 'plugin_verify_certs' ).
			$this->get_nocb_cell( 'plugin_verify_certs' ).
			$this->p->admin->get_site_use( $form, $network, 'plugin_verify_certs' );

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Report Cache Purge Count',
				'option label', 'nextgen-facebook' ), null, 'plugin_cache_info' ).
			$this->get_nocb_cell( 'plugin_cache_info' ).
			$this->p->admin->get_site_use( $form, $network, 'plugin_cache_info' );

			return $rows;
		}

		public function filter_plugin_apikeys_rows( $rows, $form ) {

			$rows[] = '<td colspan="2" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg', array( 'lca' => 'ngfb' ) ).'</td>';

			$rows['plugin_shortener'] = $this->p->util->get_th( _x( 'Preferred URL Shortening Service',
				'option label', 'nextgen-facebook' ), null, 'plugin_shortener' ).
			'<td class="blank">[none]</td>';

			$rows['plugin_shortlink'] = $this->p->util->get_th( _x( '<em>Get Shortlink</em> Gives Shortened URL',
				'option label', 'nextgen-facebook' ), null, 'plugin_shortlink' ).
			$this->get_nocb_cell( 'plugin_shortlink' );

			$rows['plugin_min_shorten'] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Minimum URL Length to Shorten',
				'option label', 'nextgen-facebook' ), null, 'plugin_min_shorten' ). 
			'<td nowrap class="blank">'.$this->p->options['plugin_min_shorten'].' '.
				_x( 'characters', 'option comment', 'nextgen-facebook' ).'</td>';

			$rows['plugin_bitly_login'] = $this->p->util->get_th( _x( 'Bit.ly Username',
				'option label', 'nextgen-facebook' ), null, 'plugin_bitly_login' ).
			'<td class="blank mono">'.$this->p->options['plugin_bitly_login'].'</td>';

			$rows['plugin_bitly_api_key'] = $this->p->util->get_th( _x( 'Bit.ly API Key',
				'option label', 'nextgen-facebook' ), null, 'plugin_bitly_api_key' ).
			'<td class="blank mono">'.$this->p->options['plugin_bitly_api_key'].'</td>';

			$rows['plugin_owly_api_key'] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Ow.ly API Key',
				'option label', 'nextgen-facebook' ), null, 'plugin_owly_api_key' ).
			'<td class="blank mono">'.$this->p->options['plugin_owly_api_key'].'</td>';

			$rows['plugin_google_api_key'] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Google Project App BrowserKey',
				'option label', 'nextgen-facebook' ), null, 'plugin_google_api_key' ).
			'<td class="blank mono">'.$this->p->options['plugin_google_api_key'].'</td>';

			$rows['plugin_google_shorten'] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Google URL Shortener API is ON',
				'option label', 'nextgen-facebook' ), null, 'plugin_google_shorten' ).
			'<td class="blank">'._x( $this->p->cf['form']['yes_no'][$this->p->options['plugin_google_shorten']],
				'option value', 'nextgen-facebook' ).'</td>';

			return $rows;
		}

		public function filter_cm_custom_rows( $rows, $form ) {

			$rows[] = '<td colspan="4" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$rows[] = '<td></td>'.
			$this->p->util->get_th( _x( 'Show',
				'column title', 'nextgen-facebook' ), 'left checkbox' ).
			$this->p->util->get_th( _x( 'Contact Field Name',
				'column title', 'nextgen-facebook' ), 'left medium', 'custom-cm-field-name' ).
			$this->p->util->get_th( _x( 'Profile Contact Label',
				'column title', 'nextgen-facebook' ), 'left wide' );

			$sorted_opt_pre = $this->p->cf['opt']['pre'];
			ksort( $sorted_opt_pre );

			foreach ( $sorted_opt_pre as $id => $pre ) {

				$cm_enabled = 'plugin_cm_'.$pre.'_enabled';
				$cm_name = 'plugin_cm_'.$pre.'_name';
				$cm_label = 'plugin_cm_'.$pre.'_label';

				// check for the lib website classname for a nice 'display name'
				$name = empty( $this->p->cf['*']['lib']['website'][$id] ) ? 
					ucfirst( $id ) : $this->p->cf['*']['lib']['website'][$id];
				$name = $name == 'GooglePlus' ? 'Google+' : $name;

				// not all social websites have a contact method field
				if ( isset( $this->p->options[$cm_enabled] ) ) {
					$rows[] = $this->p->util->get_th( $name, 'medium' ).
					'<td class="blank checkbox">'.$this->get_nocb( $cm_enabled ).'</td>'.
					'<td class="blank">'.$form->get_no_input( $cm_name, 'medium' ).'</td>'.
					'<td class="blank">'.$form->get_no_input( $cm_label ).'</td>';
				}
			}

			return $rows;
		}

		public function filter_cm_builtin_rows( $rows, $form ) {

			$rows[] = '<td colspan="4" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$rows[] = '<td></td>'.
			$this->p->util->get_th( _x( 'Show',
				'column title', 'nextgen-facebook' ), 'left checkbox' ).
			$this->p->util->get_th( _x( 'Contact Field Name',
				'column title', 'nextgen-facebook' ), 'left medium', 'custom-cm-field-name' ).
			$this->p->util->get_th( _x( 'Profile Contact Label',
				'column title', 'nextgen-facebook' ), 'left wide' );

			$sorted_wp_cm = $this->p->cf['wp']['cm'];
			ksort( $sorted_wp_cm );

			foreach ( $sorted_wp_cm as $id => $name ) {

				$cm_enabled = 'wp_cm_'.$id.'_enabled';
				$cm_name = 'wp_cm_'.$id.'_name';
				$cm_label = 'wp_cm_'.$id.'_label';

				if ( array_key_exists( $cm_enabled, $this->p->options ) ) {
					$rows[] = $this->p->util->get_th( $name, 'medium' ).
					'<td class="blank checkbox">'.$this->get_nocb( $cm_enabled ).'</td>'.
					'<td>'.$form->get_no_input( $cm_name, 'medium' ).'</td>'.
					'<td class="blank">'.$form->get_no_input( $cm_label ).'</td>';
				}
			}

			return $rows;
		}

		public function filter_taglist_tags_rows( $rows, $form, $network = false, $tag = '[^_]+' ) {
			$og_cols = 2;
			$cells = array();
			foreach ( $this->p->opt->get_defaults() as $opt => $val ) {
				if ( strpos( $opt, 'add_' ) === 0 &&
					preg_match( '/^add_('.$tag.')_([^_]+)_(.+)$/', $opt, $match ) ) {
					switch ( $opt ) {
						case 'add_meta_name_generator':
							continue 2;
						case 'add_meta_name_canonical':
						case 'add_meta_name_description':
							$highlight = ' highlight';
							break;
						// non standard / internal meta tags
						case 'add_meta_property_og:image:cropped':
						case 'add_meta_property_og:video:embed_url':
						case 'add_meta_property_og:image:id':
						case ( strpos( $opt, 'add_meta_property_pinterest:' ) === 0 ? true : false ):
						case ( strpos( $opt, 'add_meta_property_product:rating:' ) === 0 ? true : false ):
							$highlight = ' is_disabled';
							break;
						default:
							$highlight = '';
							break;
					}
					$cells[] = '<!-- '.( implode( ' ', $match ) ).' -->'.	// required for sorting
						'<td class="checkbox blank">'.$this->get_nocb( $opt ).'</td>'.
						'<td class="xshort'.$highlight.'">'.$match[1].'</td>'.
						'<td class="taglist'.$highlight.'">'.$match[2].'</td>'.
						'<th class="taglist'.$highlight.'">'.$match[3].'</th>';
				}
			}
			sort( $cells );
			$col_rows = array();
			$per_col = ceil( count( $cells ) / $og_cols );
			foreach ( $cells as $num => $cell ) {
				if ( empty( $col_rows[ $num % $per_col ] ) )
					$col_rows[ $num % $per_col ] = '<tr class="hide_in_basic">';	// initialize the array
				$col_rows[ $num % $per_col ] .= $cell;					// create the html for each row
			}
			return array_merge( $rows, $col_rows );
		}

		private function get_nocb( $name, $text = '' ) {
			return '<input type="checkbox" disabled="disabled" '.
				checked( $this->p->options[$name], 1, false ).'/>'.
					( empty( $text ) ? '' : ' '.$text );
		}

		private function get_nocb_cell( $name, $text = '', $comment = '' ) {
			return '<td class="blank">'.$this->get_nocb( $name, $text ).
				( empty( $comment ) ? '' : ' '.$comment ).'</td>';
		}
	}
}

?>
