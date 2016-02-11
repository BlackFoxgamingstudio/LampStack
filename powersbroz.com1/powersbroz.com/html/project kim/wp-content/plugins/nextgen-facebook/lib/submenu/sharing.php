<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSharing' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSubmenuSharing extends NgfbAdmin {

		public $website = array();

		protected $website_id = '';
		protected $website_name = '';

		public function __construct( &$plugin, $id, $name, $lib ) {
			$this->p =& $plugin;
			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->menu_lib = $lib;
			$this->set_objects();
			$this->p->util->add_plugin_actions( $this, array(
				'form_content_metaboxes_sharing' => 1,
			) );
			$this->p->util->add_plugin_filters( $this, array(
				'messages_tooltip' => 2,
			) );
		}

		private function set_objects() {
			foreach ( $this->p->cf['*']['lib']['website'] as $id => $name ) {
				$classname = NgfbConfig::load_lib( false, 'website/'.$id, 'ngfbsubmenusharing'.$id );
				if ( $classname !== false && class_exists( $classname ) )
					$this->website[$id] = new $classname( $this->p, $id, $name );
			}
		}

		// display two-column metaboxes for sharing buttons
		public function action_form_content_metaboxes_sharing( $pagehook ) {
			if ( isset( $this->website ) ) {
				foreach ( range( 1, ceil( count( $this->website ) / 2 ) ) as $row ) {
					echo '<div class="website-row">', "\n";
					foreach ( range( 1, 2 ) as $col ) {
						$pos_id = 'website-row-'.$row.'-col-'.$col;
						echo '<div class="website-col-', $col, '" id="', $pos_id, '" >';
						do_meta_boxes( $pagehook, $pos_id, null ); 
						echo '</div>', "\n";
					}
					echo '</div>', "\n";
				}
				echo '<div style="clear:both;"></div>';
			}
		}

		public function filter_messages_tooltip( $text, $idx ) {
			if ( strpos( $idx, 'tooltip-buttons_' ) !== 0 )
				return $text;

			switch ( $idx ) {
				case ( strpos( $idx, 'tooltip-buttons_pos_' ) === false ? false : true ):
					$text = sprintf( __( 'Social sharing buttons can be added to the top, bottom, or both. Each sharing button must also be enabled below (see the <em>%s</em> options).', 'nextgen-facebook' ), _x( 'Show Button in', 'option label', 'nextgen-facebook' ) );
					break;
				case 'tooltip-buttons_on_index':
					$text = __( 'Add the social sharing buttons to each entry of an index webpage (for example, <strong>non-static</strong> homepage, category, archive, etc.). Social sharing buttons are not included on index webpages by default.', 'nextgen-facebook' );
					break;
				case 'tooltip-buttons_on_front':
					$text = __( 'If a static Post or Page has been selected for the homepage, you can add the social sharing buttons to that static homepage as well (default is unchecked).', 'nextgen-facebook' );
					break;
				case 'tooltip-buttons_add_to':
					$text = __( 'Enabled social sharing buttons are added to the Post, Page, Media, and Product webpages by default. If your theme (or another plugin) supports additional custom post types, and you would like to include social sharing buttons on these webpages, check the appropriate option(s) here.', 'nextgen-facebook' );
					break;
			}
			return $text;
		}

		protected function add_meta_boxes() {
			$col = 0;
			$row = 0;

			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_sharing',
				_x( 'Social Sharing Buttons', 'metabox title', 'nextgen-facebook' ),
					array( &$this, 'show_metabox_sharing' ), $this->pagehook, 'normal' );

			foreach ( $this->p->cf['*']['lib']['website'] as $id => $name ) {
				$classname = __CLASS__.$id;
				if ( class_exists( $classname ) ) {
					$col = $col == 1 ? 2 : 1;
					$row = $col == 1 ? $row + 1 : $row;
					$pos_id = 'website-row-'.$row.'-col-'.$col;
					$name = $name == 'GooglePlus' ? 'Google+' : $name;

					add_meta_box( $this->pagehook.'_'.$id, $name, 
						array( &$this->website[$id], 'show_metabox_website' ), $this->pagehook, $pos_id );

					add_filter( 'postbox_classes_'.$this->pagehook.'_'.$this->pagehook.'_'.$id, 
						array( &$this, 'add_class_postbox_website' ) );

					$this->website[$id]->form = &$this->get_form_reference();
				}
			}

			// these metabox ids should be closed by default (array_diff() selects everything except those listed)
			$ids = array_diff( array_keys( $this->p->cf['plugin']['ngfb']['lib']['website'] ), 
				array( 'facebook', 'gplus', 'twitter' ) );
			$this->p->mods['util']['user']->reset_metabox_prefs( $this->pagehook, $ids, 'closed' );
		}

		public function add_class_postbox_website( $classes ) {
			$show_opts = NgfbUser::show_opts();
			$classes[] = 'postbox_website';
			if ( ! empty( $show_opts ) )
				$classes[] = 'postbox_show_'.$show_opts;
			return $classes;
		}

		public function show_metabox_sharing() {
			$metabox = 'sharing';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array(
				'include' => _x( 'Include Buttons', 'metabox tab', 'nextgen-facebook' ),
				'position' => _x( 'Buttons Position', 'metabox tab', 'nextgen-facebook' ),
				'preset' => _x( 'Button Presets', 'metabox tab', 'nextgen-facebook' ),
			) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		public function show_metabox_website() {
			$metabox = 'website';
			$key = $this->website_id;
			$this->p->util->do_table_rows( 
				array_merge( 
					$this->get_rows( $metabox, $key ),
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form )
				),
				'metabox-'.$metabox.'-'.$key,
				'metabox-website'
			);
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();
			switch ( $metabox.'-'.$key ) {

				case 'sharing-include':

					$rows[] = '<tr><td colspan="2">'.$this->p->msgs->get( 'info-'.$metabox.'-'.$key ).'</td></tr>';

					$rows[] = $this->p->util->get_th( _x( 'Include on Index Webpages',
						'option label', 'nextgen-facebook' ), null, 'buttons_on_index' ).
					'<td>'.$this->form->get_checkbox( 'buttons_on_index' ).'</td>';

					$rows[] = $this->p->util->get_th( _x( 'Include on Static Homepage',
						'option label', 'nextgen-facebook' ), null, 'buttons_on_front' ).
					'<td>'.$this->form->get_checkbox( 'buttons_on_front' ).'</td>';

					break;

				case 'sharing-position':

					$rows[] = $this->p->util->get_th( _x( 'Position in Content Text',
						'option label', 'nextgen-facebook' ), null, 'buttons_pos_content' ).
					'<td>'.$this->form->get_select( 'buttons_pos_content',
						$this->p->cf['sharing']['position'] ).'</td>';

					$rows[] = $this->p->util->get_th( _x( 'Position in Excerpt Text',
						'option label', 'nextgen-facebook' ), null, 'buttons_pos_excerpt' ).
					'<td>'.$this->form->get_select( 'buttons_pos_excerpt', 
						$this->p->cf['sharing']['position'] ).'</td>';

					break;
			}
			return $rows;
		}

		// called by each website's settings class to display a list of checkboxes
		// Show Button in: Content, Excerpt, Admin Edit, etc.
		protected function show_on_checkboxes( $prefix ) {
			$col = 0;
			$max = 2;
			$html = '<table>';
			$show_on = apply_filters( $this->p->cf['lca'].'_sharing_show_on', 
				$this->p->cf['sharing']['show_on'], $prefix );
			foreach ( $show_on as $suffix => $desc ) {
				$col++;
				$class = isset( $this->p->options[$prefix.'_on_'.$suffix.':is'] ) &&
					$this->p->options[$prefix.'_on_'.$suffix.':is'] === 'disabled' &&
					! $this->p->check->aop( 'ngfb' ) ? 'show_on blank' : 'show_on';
				if ( $col == 1 )
					$html .= '<tr><td class="'.$class.'">';
				else $html .= '<td class="'.$class.'">';
				$html .= $this->form->get_checkbox( $prefix.'_on_'.$suffix ).
					_x( $desc, 'option value', 'nextgen-facebook' ).'&nbsp; ';
				if ( $col == $max ) {
					$html .= '</td></tr>';
					$col = 0;
				} else $html .= '</td>';
			}
			$html .= $col < $max ? '</tr>' : '';
			$html .= '</table>';
			return $html;
		}
	}
}

?>
