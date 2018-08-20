<?php
/**
 * Plugin Name:       Integração Mapas Culturais - Cultura Viva
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       @TODO
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/darciro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'IntegracaoMapasCV' ) ) :

	class IntegracaoMapasCV {

		public function __construct() {
		}

		public function get_header () {
		}


    }

	// Initialize our plugin
	$gewp = new IntegracaoMapasCV();

endif;
