<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuAdvanced' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSubmenuAdvanced extends NgfbAdmin {

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
			add_meta_box( $this->pagehook.'_plugin', 
				_x( 'Advanced Settings', 'metabox title', 'nextgen-facebook' ), 
					array( &$this, 'show_metabox_plugin' ), $this->pagehook, 'normal' );

			add_meta_box( $this->pagehook.'_contact_fields',
				_x( 'Contact Field Names and Labels', 'metabox title', 'nextgen-facebook' ), 
					array( &$this, 'show_metabox_contact_fields' ), $this->pagehook, 'normal' );

			add_meta_box( $this->pagehook.'_taglist',
				_x( 'Header Tags List', 'metabox title', 'nextgen-facebook' ), 
					array( &$this, 'show_metabox_taglist' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_plugin() {
			$metabox = 'plugin';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'settings' => _x( 'Plugin Settings', 'metabox tab', 'nextgen-facebook' ),
				'content' => _x( 'Content and Filters', 'metabox tab', 'nextgen-facebook' ),
				'social' => _x( 'Social Settings', 'metabox tab', 'nextgen-facebook' ),
				'integration' => _x( 'WP / Theme Integration', 'metabox tab', 'nextgen-facebook' ),
				'cache' => _x( 'File and Object Cache', 'metabox tab', 'nextgen-facebook' ),
				'apikeys' => _x( 'Service API Keys', 'metabox tab', 'nextgen-facebook' ),
			) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', 
						array(), $this->form, false ) );	// $network = false
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		public function show_metabox_contact_fields() {
			$metabox = 'cm';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'custom' => _x( 'Custom Contacts', 'metabox tab', 'nextgen-facebook' ),
				'builtin' => _x( 'Built-In Contacts', 'metabox tab', 'nextgen-facebook' ),
			) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', 
						array(), $this->form, false ) );	// $network = false
			$this->p->util->do_table_rows( 
				array( '<td>'.$this->p->msgs->get( 'info-'.$metabox ).'</td>' ),
				'metabox-'.$metabox.'-info'
			);
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		public function show_metabox_taglist() {
			$metabox = 'taglist';
			$this->p->util->do_table_rows( 
				array( '<td>'.$this->p->msgs->get( 'info-'.$metabox ).'</td>' ),
				'metabox-'.$metabox.'-info'
			);
			$this->p->util->do_table_rows( 
				apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tags_rows', array(), $this->form ),
				'metabox-'.$metabox.'-tags'
			);
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();
			switch ( $metabox.'-'.$key ) {
				case 'plugin-settings':

					$rows['plugin_preserve'] = $this->p->util->get_th( _x( 'Preserve Settings on Uninstall',
						'option label', 'nextgen-facebook' ), null, 'plugin_preserve' ).
					'<td>'.$this->form->get_checkbox( 'plugin_preserve' ).'</td>';

					$rows['plugin_debug'] = $this->p->util->get_th( _x( 'Add Hidden Debug Messages', 
						'option label', 'nextgen-facebook' ), null, 'plugin_debug' ).
					'<td>'.( SucomUtil::get_const( 'NGFB_HTML_DEBUG' ) ? 
						$this->form->get_no_checkbox( 'plugin_debug' ).' NGFB_HTML_DEBUG constant enabled' :
						$this->form->get_checkbox( 'plugin_debug' ) ).'</td>';

					$rows['plugin_show_opts'] = $this->p->util->get_th( _x( 'Options to Show by Default',
						'option label', 'nextgen-facebook' ), null, 'plugin_show_opts' ).
					'<td>'.$this->form->get_select( 'plugin_show_opts', 
						$this->p->cf['form']['show_options'] ).'</td>';

					break;
			}
			return $rows;
		}
	}
}

?>
