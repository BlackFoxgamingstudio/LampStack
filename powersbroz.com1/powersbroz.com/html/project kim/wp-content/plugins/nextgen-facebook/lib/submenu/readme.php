<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuReadme' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSubmenuReadme extends NgfbAdmin {

		public function __construct( &$plugin, $id, $name, $lib ) {
			$this->p =& $plugin;
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->menu_lib = $lib;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_readme',
				_x( 'Read Me', 'metabox title', 'nextgen-facebook' ),
					array( &$this, 'show_metabox_readme' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_readme() {
			$metabox = 'readme';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'description' => _x( 'Description', 'metabox tab', 'nextgen-facebook' ),
				'faq' => _x( 'FAQ', 'metabox tab', 'nextgen-facebook' ),
				'notes' => _x( 'Other Notes', 'metabox tab', 'nextgen-facebook' ),
				'changelog' => _x( 'Changelog', 'metabox tab', 'nextgen-facebook' ),
			) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}
		
		protected function get_rows( $metabox, $key ) {
			$lca = $this->p->cf['lca'];
			$rows = array();
			switch ( $metabox.'-'.$key ) {
				case 'readme-description':
					$rows[] = '<td>'.( empty( self::$readme_info[$lca]['sections']['description'] ) ? 
						'Content not Available' : self::$readme_info[$lca]['sections']['description'] ).'</td>';
					break;

				case 'readme-faq':
					$rows[] = '<td>'.( empty( self::$readme_info[$lca]['sections']['frequently_asked_questions'] ) ?
						'Content not Available' : self::$readme_info[$lca]['sections']['frequently_asked_questions'] ).'</td>';
					break;

				case 'readme-notes':
					$rows[] = '<td>'.( empty( self::$readme_info[$lca]['remaining_content'] ) ?
						'Content not Available' : self::$readme_info[$lca]['remaining_content'] ).'</td>';
					break;

				case 'readme-changelog':
					$rows[] = '<td>'.( empty( self::$readme_info[$lca]['sections']['changelog'] ) ?
						'Content not Available' : self::$readme_info[$lca]['sections']['changelog'] ).'</td>';
					break;
			}
			return $rows;
		}
	}
}

?>
