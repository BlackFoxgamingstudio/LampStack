<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharingReddit' ) && class_exists( 'NgfbSubmenuSharing' ) ) {

	class NgfbSubmenuSharingReddit extends NgfbSubmenuSharing {

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
			( $this->show_on_checkboxes( 'reddit' ) ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Preferred Order',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'reddit_order', 
				range( 1, count( $this->p->admin->submenu['sharing']->website ) ), 
					'short' ).'</td>';

			$rows[] = $this->p->util->get_th( _x( 'Button Type',
				'option label (short)', 'nextgen-facebook' ), 'short' ).'<td>'.
			$this->form->get_select( 'reddit_type', 
				array( 
					'static-wide' => 'Interactive Wide',
					'static-tall-text' => 'Interactive Tall Text',
					'static-tall-logo' => 'Interactive Tall Logo',
				)
			).'</td>';

			return $rows;
		}
	}
}

if ( ! class_exists( 'NgfbSharingReddit' ) ) {

	class NgfbSharingReddit {

		private static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'reddit_on_content' => 0,
					'reddit_on_excerpt' => 0,
					'reddit_on_sidebar' => 0,
					'reddit_on_admin_edit' => 1,
					'reddit_order' => 7,
					'reddit_type' => 'static-wide',
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
			$prot = empty( $_SERVER['HTTPS'] ) ?
				'http:' : 'https:';
			$use_post = isset( $atts['use_post'] ) ?
				$atts['use_post'] : true;
			$src_id = $this->p->util->get_source_id( 'reddit', $atts );
			$atts['add_page'] = isset( $atts['add_page'] ) ?
				$atts['add_page'] : true;	// get_sharing_url argument

			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $src_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $src_id );

			if ( empty( $atts['title'] ) ) 
				$atts['title'] = $this->p->webpage->get_title( 
					null,				// max length
					null,				// trailing
					$use_post,			//
					true,				// use_cache
					false,				// add_hashtags
					true,				// encode
					null,				// metadata key
					$src_id
				);

			switch ( $opts['reddit_type'] ) {
				case 'static-tall-text':
					$js_url = $prot.'//www.reddit.com/static/button/button2.js';
					break;
				case 'static-tall-logo':
					$js_url = $prot.'//www.reddit.com/static/button/button3.js';
					break;
				case 'static-wide':
				default:	// just in case
					$js_url = $prot.'//www.reddit.com/static/button/button1.js';
					break;
			}
			$js_url = $this->p->util->get_cache_file_url( apply_filters( $this->p->cf['lca'].'_js_url_reddit', $js_url, '' ) );

			$html = '<!-- Reddit Button -->';
			$html .= '<script type="text/javascript">reddit_url=\''.$atts['url'].'\'; reddit_title=\''.$atts['title'].'\';</script>';
			$html .= '<div '.$this->p->sharing->get_css( 'reddit', $atts ).'>';
			$html .= '<script type="text/javascript" src="'.$js_url.'"></script></div>';

			if ( $this->p->debug->enabled )
				$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html."\n";
		}
	}
}

?>
