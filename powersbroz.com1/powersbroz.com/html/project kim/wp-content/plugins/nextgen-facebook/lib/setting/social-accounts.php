<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSettingSocialAccounts' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSettingSocialAccounts extends NgfbAdmin {

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
			add_meta_box( $this->pagehook.'_social_accounts',
				_x( 'Website / Business Social Accounts', 'metabox title', 'nextgen-facebook' ), 
					array( &$this, 'show_metabox_social_accounts' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_social_accounts() {
			$metabox = $this->menu_id;
			echo '<table class="sucom-setting '.$this->p->cf['lca'].'">';
			echo '<tr><td colspan="2">'.$this->p->msgs->get( 'info-'.$metabox ).'</td></tr>';

			foreach ( array_merge( $this->get_rows( $metabox, 'general' ), 
				apply_filters( $this->p->cf['lca'].'_'.$metabox.'_general_rows', 
					array(), $this->form ) ) as $num => $row ) 
						echo '<tr>'.$row.'</tr>';
			echo '</table>';
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();

			switch ( $metabox.'-'.$key ) {

				case 'social-accounts-general':

					$rows[] = $this->p->util->get_th( _x( 'Facebook Business Page URL',
						'option label', 'nextgen-facebook' ), null, 'fb_publisher_url' ).
					'<td>'.$this->form->get_input( 'fb_publisher_url', 'wide' ).'</td>';

					$rows[] = $this->p->util->get_th( _x( 'Google+ Business Page URL',
						'option label', 'nextgen-facebook' ), null, 'google_publisher_url' ).
					'<td>'.$this->form->get_input( 'seo_publisher_url', 'wide' ).'</td>';

					$rows[] = $this->p->util->get_th( _x( 'Pinterest Company Page URL',
						'option label', 'nextgen-facebook' ), null, 'rp_publisher_url'  ).
					'<td>'.$this->form->get_input( 'rp_publisher_url', 'wide' ).'</td>';

					$rows[] = $this->p->util->get_th( _x( 'Twitter Business @username',
						'option label', 'nextgen-facebook' ), null, 'tc_site' ).
					'<td>'.$this->form->get_input( 'tc_site' ).'</td>';

					$rows[] = $this->p->util->get_th( _x( 'Instagram Business URL',
						'option label', 'nextgen-facebook' ), null, 'instgram_publisher_url' ).
					'<td>'.$this->form->get_input( 'instgram_publisher_url', 'wide' ).'</td>';

					$rows[] = $this->p->util->get_th( _x( 'LinkedIn Company Page URL',
						'option label', 'nextgen-facebook' ), null, 'linkedin_publisher_url'  ).
					'<td>'.$this->form->get_input( 'linkedin_publisher_url', 'wide' ).'</td>';

					$rows[] = $this->p->util->get_th( _x( 'MySpace Business (Brand) URL',
						'option label', 'nextgen-facebook' ), null, 'myspace_publisher_url'  ).
					'<td>'.$this->form->get_input( 'myspace_publisher_url', 'wide' ).'</td>';

					break;
			}
			return $rows;
		}
	}
}

?>
