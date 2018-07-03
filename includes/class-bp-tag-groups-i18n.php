<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/includes
 * @author     Adarsh verma <adarsh.srmcem@gmail.com>
 */
class Bp_Tag_Groups_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bp-tag-groups',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
