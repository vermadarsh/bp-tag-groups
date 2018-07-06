<?php
/**
 * The file that defines the global plugin class
 *
 * A class definition that includes attributes and functions used across plugin.
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/includes
 * @author     Adarsh verma <adarsh.srmcem@gmail.com>
 */
class Bp_Tag_Groups_Global {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Admin settings of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $plugin_admin_settings    The  vriable to hold the complete admin settings.
	 */
	public $bp_group_default_tags;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $plugin_version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $plugin_version;
		$this->setup_plugin_global();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	private function setup_plugin_global() {

		global $bp_tag_groups;
		$bp_group_default_tags = get_option( 'bp_group_default_tags' );

		$this->bp_group_default_tags = array();
		if( is_array( $bp_group_default_tags ) && ! empty( $bp_group_default_tags ) ) {
			$this->bp_group_default_tags = $bp_group_default_tags;
		}

	}

}
