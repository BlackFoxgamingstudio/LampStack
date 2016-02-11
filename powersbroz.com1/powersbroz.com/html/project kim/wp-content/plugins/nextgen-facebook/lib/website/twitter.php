<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharingTwitter' ) && class_exists( 'NgfbSubmenuSharing' ) ) {

	class NgfbSubmenuSharingTwitter extends NgfbSubmenuSharing {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->website_id = $id;
			$this->website_name = $name;
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();
			
			$rows[] = $this->p->util->get_th( _x( 'Show Button in',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			( $this->show_on_checkboxes( 'twitter' ) ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Preferred Order',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'twitter_order', 
				range( 1, count( $this->p->admin->submenu['sharing']->website ) ), 'short' ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'JavaScript in',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'twitter_script_loc', $this->p->cf['form']['script_locations'] ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Default Language',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'twitter_lang', SucomUtil::get_pub_lang( 'twitter' ) ).'</td>';

			/*
			$rows[] = $this->p->util->get_th( _x( 'Count Position',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'twitter_count', array( 'none' => '', 
			'horizontal' => 'Horizontal', 'vertical' => 'Vertical' ) ).'</td>';
			*/

			$rows[] = $this->p->util->get_th( _x( 'Button Size',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'twitter_size', array( 'medium' => 'Medium', 'large' => 'Large' ) ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Tweet Text Source',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'twitter_caption', $this->p->cf['form']['caption_types'] ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Tweet Text Length',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_input( 'twitter_cap_len', 'short' ).' '.
				_x( 'characters or less', 'option comment', 'nextgen-facebook' ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( _x( 'Do Not Track',
				'option label (short)', 'nextgen-facebook' ), 'short', null,
			__( 'Disable tracking for Twitter\'s tailored suggestions and ads feature.', 'nextgen-facebook' ) ).
			'<td>'.$this->form->get_checkbox( 'twitter_dnt' ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Add via @username',
				'option label (short)', 'nextgen-facebook' ), 'short', null, 
			sprintf( __( 'Append the website\'s business @username to the tweet (see the <a href="%1$s">Twitter</a> options tab on the %2$s settings page). The website\'s @username will be displayed and recommended after the webpage is shared.', 'nextgen-facebook' ), $this->p->util->get_admin_url( 'general#sucom-tabset_pub-tab_twitter' ), _x( 'General', 'lib file description', 'nextgen-facebook' ) ) ).
			( $this->p->check->aop( 'ngfb' ) ? '<td>'.$this->form->get_checkbox( 'twitter_via' ).'</td>' :
				'<td class="blank">'.$this->form->get_no_checkbox( 'twitter_via' ).'</td>' );

			$rows[] = $this->p->util->get_th( _x( 'Recommend Author',
				'option label (short)', 'nextgen-facebook' ), 'short', null, 
			sprintf( __( 'Recommend following the author\'s Twitter @username (from their profile) after sharing a webpage. If the <em>%1$s</em> option is also checked, the website\'s @username is suggested first.', 'nextgen-facebook' ), _x( 'Add via @username', 'option label (short)', 'wpsso-rrssb' ) ) ).
			( $this->p->check->aop( 'ngfb' ) ? 
				'<td>'.$this->form->get_checkbox( 'twitter_rel_author' ).'</td>' :
				'<td class="blank">'.$this->form->get_no_checkbox( 'twitter_rel_author' ).'</td>' );

			$rows[] = $this->p->util->get_th( _x( 'Shorten URLs with',
				'option label (short)', 'nextgen-facebook' ), 'short', null, 
			sprintf( __( 'If you select a URL shortening service here, you must also enter its <a href="%1$s">%2$s</a> on the %3$s settings page.', 'nextgen-facebook' ), $this->p->util->get_admin_url( 'advanced#sucom-tabset_plugin-tab_apikeys' ), _x( 'Service API Keys', 'metabox tab', 'nextgen-facebook' ), _x( 'Advanced', 'lib file description', 'nextgen-facebook' ) ) ).
			( $this->p->check->aop( 'ngfb' ) ? 
				'<td>'.$this->form->get_select( 'plugin_shortener', $this->p->cf['form']['shorteners'], 'short' ).'&nbsp; ' :
				'<td class="blank">'.$this->p->cf['form']['shorteners'][$this->p->options['plugin_shortener']].' &mdash; ' ).
			sprintf( __( 'using these <a href="%1$s">%2$s</a>', 'nextgen-facebook' ), $this->p->util->get_admin_url( 'advanced#sucom-tabset_plugin-tab_apikeys' ), _x( 'Service API Keys', 'metabox tab', 'nextgen-facebook' ) ).'</td>';

			return $rows;
		}
	}
}

if ( ! class_exists( 'NgfbSharingTwitter' ) ) {

	class NgfbSharingTwitter {

		private static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'twitter_on_content' => 1,
					'twitter_on_excerpt' => 0,
					'twitter_on_sidebar' => 0,
					'twitter_on_admin_edit' => 1,
					'twitter_order' => 1,
					'twitter_script_loc' => 'header',
					'twitter_lang' => 'en',
					'twitter_count' => 'horizontal',
					'twitter_caption' => 'title',
					'twitter_cap_len' => 140,
					'twitter_size' => 'medium',
					'twitter_via' => 1,
					'twitter_rel_author' => 1,
					'twitter_dnt' => 1,
				),
			),
		);

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 'get_defaults' => 1 ) );
		}

		public function filter_get_defaults( $opts_def ) {
			return array_merge( $opts_def, self::$cf['opt']['defaults'] );
		}

		public function get_html( $atts = array(), &$opts = array() ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			global $post; 
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$use_post = isset( $atts['use_post'] ) ?
				$atts['use_post'] : true;
			$src_id = $this->p->util->get_source_id( 'twitter', $atts );
			$atts['add_page'] = isset( $atts['add_page'] ) ?
				$atts['add_page'] : true;	// get_sharing_url argument
			$long_url = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $src_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url',
					$atts['url'], $use_post, $atts['add_page'], $src_id );

			$short_url = apply_filters( $this->p->cf['lca'].'_shorten_url',
				$long_url, $opts['plugin_shortener'] );

			if ( ! array_key_exists( 'lang', $atts ) )
				$atts['lang'] = empty( $opts['twitter_lang'] ) ? 'en' : $opts['twitter_lang'];
			$atts['lang'] = apply_filters( $this->p->cf['lca'].'_lang', 
				$atts['lang'], SucomUtil::get_pub_lang( 'twitter' ) );

			if ( array_key_exists( 'tweet', $atts ) )
				$atts['caption'] = $atts['tweet'];

			if ( ! array_key_exists( 'caption', $atts ) ) {
				if ( empty( $atts['caption'] ) ) {
					$caption_len = $this->p->util->get_tweet_max_len( $long_url, 'twitter', $short_url );
					$atts['caption'] = $this->p->webpage->get_caption( 
						$opts['twitter_caption'],	// title, excerpt, both
						$caption_len,			// max caption length 
						$use_post,			// 
						true,				// use_cache
						true, 				// add_hashtags
						true, 				// encode
						'twitter_desc',			// metadata key
						$src_id				// 
					);
				}
			}

			if ( ! array_key_exists( 'via', $atts ) ) {
				if ( ! empty( $opts['twitter_via'] ) && 
					$this->p->check->aop( 'ngfb' ) )
						$atts['via'] = preg_replace( '/^@/', '', $opts['tc_site'] );
				else $atts['via'] = '';
			}

			if ( ! array_key_exists( 'related', $atts ) ) {
				if ( ! empty( $opts['twitter_rel_author'] ) && 
					! empty( $post ) && $use_post === true && $this->p->check->aop( 'ngfb' ) )
						$atts['related'] = preg_replace( '/^@/', '', 
							get_the_author_meta( $opts['plugin_cm_twitter_name'], $post->author ) );
				else $atts['related'] = '';
			}

			// hashtags are included in the caption instead
			if ( ! array_key_exists( 'hashtags', $atts ) )
				$atts['hashtags'] = '';

			if ( ! array_key_exists( 'dnt', $atts ) ) 
				$atts['dnt'] = $opts['twitter_dnt'] ? 'true' : 'false';

			$html = '<!-- Twitter Button --><div '.$this->p->sharing->get_css( 'twitter', $atts ).'>';
			$html .= '<a href="'.$prot.'//twitter.com/share" class="twitter-share-button" data-lang="'. $atts['lang'].'" ';
			$html .= 'data-url="'.$short_url.'" data-counturl="'.$long_url.'" data-text="'.$atts['caption'].'" ';
			$html .= 'data-via="'.$atts['via'].'" data-related="'.$atts['related'].'" data-hashtags="'.$atts['hashtags'].'" ';
			$html .= 'data-count="'.$opts['twitter_count'].'" data-size="'.$opts['twitter_size'].'" data-dnt="'.$atts['dnt'].'"></a></div>';

			if ( $this->p->debug->enabled )
				$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html."\n";
		}
		
		public function get_script( $pos = 'id' ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_file_url( apply_filters( $this->p->cf['lca'].'_js_url_twitter',
				$prot.'//platform.twitter.com/widgets.js', $pos ) );

			return '<script type="text/javascript" id="twitter-script-'.$pos.'">'.
				$this->p->cf['lca'].'_insert_js( "twitter-script-'.$pos.'", "'.$js_url.'" );</script>'."\n";
		}
	}
}

?>
