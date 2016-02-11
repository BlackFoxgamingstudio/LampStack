<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSubmenuSetup' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSubmenuSetup extends NgfbAdmin {

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
			add_meta_box( $this->pagehook.'_guide',
				_x( 'Setup Guide', 'metabox title', 'nextgen-facebook' ),
					array( &$this, 'show_metabox_guide' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_guide() {
			echo '<table class="sucom-setting '.$this->p->cf['lca'].' setup-metabox">';
			echo '<tr><td>'.$this->p->msgs->get( 'info-review' ).'</td></tr>';
			echo '<tr><td>';
			echo $this->p->util->get_remote_content( 
				$this->p->cf['plugin'][$this->p->cf['lca']]['url']['setup'],
				constant( $this->p->cf['uca'].'_PLUGINDIR' ).'setup.html'
			);
			echo '</td></tr></table>';
		}
	}
}

?>
