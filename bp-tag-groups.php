<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/vermadarsh
 * @since             1.0.0
 * @package           Bp_Tag_Groups
 *
 * @wordpress-plugin
 * Plugin Name:       BuddyPress Tag groups
 * Plugin URI:        https://github.com/vermadarsh/bp-tag-groups
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Adarsh verma
 * Author URI:        https://github.com/vermadarsh
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bp-tag-groups
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BPGRPTG_PLUGIN_VERSION', '1.0.0' );

/**
 * Constant defined for plugin path
 */
if( ! defined( 'BPGRPTG_PLUGIN_PATH' ) ) {
	define( 'BPGRPTG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Constant defined for plugin path
 */
if( ! defined( 'BPGRPTG_PLUGIN_URL' ) ) {
	define( 'BPGRPTG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bp-tag-groups-activator.php
 */
function activate_bp_tag_groups() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-tag-groups-activator.php';
	Bp_Tag_Groups_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bp-tag-groups-deactivator.php
 */
function deactivate_bp_tag_groups() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-tag-groups-deactivator.php';
	Bp_Tag_Groups_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bp_tag_groups' );
register_deactivation_hook( __FILE__, 'deactivate_bp_tag_groups' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bp-tag-groups.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bp_tag_groups() {

	$plugin = new Bp_Tag_Groups();
	$plugin->run();

}
run_bp_tag_groups();
