<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbSitesubmenuSitelicenses' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbSitesubmenuSitelicenses extends NgfbAdmin {

		public function __construct( &$plugin, $id, $name, $lib ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->menu_lib = $lib;
		}

		protected function set_form_property() {
			$def_site_opts = $this->p->opt->get_site_defaults();
			$this->form = new SucomForm( $this->p, NGFB_SITE_OPTIONS_NAME, $this->p->site_options, $def_site_opts );
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_licenses',
				_x( 'Extension Plugins and Pro Licenses', 'metabox title', 'nextgen-facebook' ), 
					array( &$this, 'show_metabox_licenses' ), $this->pagehook, 'normal' );

			// add a class to set a minimum width for the network postboxes
			add_filter( 'postbox_classes_'.$this->pagehook.'_'.$this->pagehook.'_licenses', 
				array( &$this, 'add_class_postbox_network' ) );
		}

		public function add_class_postbox_network( $classes ) {
			array_push( $classes, 'postbox_network' );
			return $classes;
		}

		public function show_metabox_licenses() {
			$this->licenses_metabox_content( true );	// $network = true
		}
	}
}

?>
