<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbLoader' ) ) {

	class NgfbLoader {

		private $p;

		public function __construct( &$plugin, $activate = false ) {
			$this->p =& $plugin;
			if ( $this->p->debug->enabled )
				$this->p->debug->mark( 'load modules' );	// begin timer
			$this->modules();
			if ( $this->p->debug->enabled )
				$this->p->debug->mark( 'load modules' );	// end timer
		}

		private function modules() {
			if ( is_admin() ) {
				// save time on known admin pages we don't modify
				switch ( basename( $_SERVER['PHP_SELF'] ) ) {
					case 'index.php':		// Dashboard
					case 'upload.php':		// Media
					case 'edit-comments.php':	// Comments
					case 'themes.php':		// Appearance
					case 'plugins.php':		// Plugins
					case 'tools.php':		// Tools
						if ( $this->p->debug->enabled )
							$this->p->debug->log( 'no modules required for current page' );
						return;
				}
			}
			foreach ( $this->p->cf['plugin'] as $lca => $info ) {
				$type = $this->p->is_avail['aop'] &&
					$this->p->is_avail['util']['um'] &&
					$this->p->check->aop( $lca, true, -1 ) === -1 ?
						'pro' : 'gpl';
				if ( ! isset( $info['lib'][$type] ) )
					continue;
				foreach ( $info['lib'][$type] as $sub => $lib ) {
					if ( $sub === 'admin' &&
						! is_admin() )
							continue;
					foreach ( $lib as $id => $name ) {
						if ( $this->p->is_avail[$sub][$id] ) {
							$classname = apply_filters( $lca.'_load_lib', false, "$type/$sub/$id" );
							if ( $classname !== false && class_exists( $classname ) )
								$this->p->mods[$sub][$id] = new $classname( $this->p );
						}
					}
				}
			}
		}
	}
}

?>
