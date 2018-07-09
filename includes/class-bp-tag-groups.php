<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/includes
 * @author     Adarsh verma <adarsh.srmcem@gmail.com>
 */
class Bp_Tag_Groups {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bp_Tag_Groups_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BPGRPTG_PLUGIN_VERSION' ) ) {
			$this->version = BPGRPTG_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'bp-tag-groups';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_globals();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bp_Tag_Groups_Loader. Orchestrates the hooks of the plugin.
	 * - Bp_Tag_Groups_i18n. Defines internationalization functionality.
	 * - Bp_Tag_Groups_Admin. Defines all hooks for the admin area.
	 * - Bp_Tag_Groups_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once BPGRPTG_PLUGIN_PATH . 'includes/class-bp-tag-groups-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once BPGRPTG_PLUGIN_PATH . 'includes/class-bp-tag-groups-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once BPGRPTG_PLUGIN_PATH . 'admin/class-bp-tag-groups-admin.php';

		/**
		 * The class responsible for defining the global variable of the plugin.
		 */
		require_once BPGRPTG_PLUGIN_PATH . 'includes/class-bp-tag-groups-global.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once BPGRPTG_PLUGIN_PATH . 'public/class-bp-tag-groups-public.php';

		$this->loader = new Bp_Tag_Groups_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bp_Tag_Groups_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bp_Tag_Groups_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Bp_Tag_Groups_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'bpgrptg_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'bpgrptg_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'bpgrptg_plugin_settings_page' );
		$this->loader->add_action( 'bp_groups_admin_meta_boxes', $plugin_admin, 'bpgrptg_edit_grp_metabox' );
		$this->loader->add_action( 'groups_settings_updated', $plugin_admin, 'bpgrptg_update_grp_metabox' );
		$this->loader->add_filter( 'bp_groups_list_table_get_columns', $plugin_admin, 'bpgrptg_groups_list_tbl_column', 10, 1 );
		$this->loader->add_filter( 'bp_groups_admin_get_group_custom_column', $plugin_admin, 'bpgrptg_groups_list_tbl_column_content', 10, 3 );
		$this->loader->add_action( 'wp_ajax_bpgrptg_delete_tag', $plugin_admin, 'bpgrptg_delete_tag' );
		$this->loader->add_action( 'wp_ajax_bpgrptg_search_tag', $plugin_admin, 'bpgrptg_search_tag' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bp_Tag_Groups_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'bpgrptg_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'bpgrptg_enqueue_scripts' );
		$this->loader->add_action( 'bp_after_group_details_creation_step', $plugin_public, 'bpgrptg_add_tag_create_group' );
        $this->loader->add_action( 'groups_custom_group_fields_editable', $plugin_public, 'bpgrptg_add_tag_group_edit_details' );

	}

	/**
	 * The global variable of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_globals() {

		global $bp_tag_groups;
		$bp_tag_groups = new Bp_Tag_Groups_Global( $this->get_plugin_name(), $this->get_version() );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bp_Tag_Groups_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
