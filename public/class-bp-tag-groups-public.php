<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/public
 * @author     Adarsh verma <adarsh.srmcem@gmail.com>
 */
class Bp_Tag_Groups_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name . '-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
		wp_enqueue_style( $this->plugin_name, BPGRPTG_PLUGIN_URL . 'public/css/bp-tag-groups-public.css' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_enqueue_scripts() {

	    global $bp_tag_groups;
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( $this->plugin_name, BPGRPTG_PLUGIN_URL . 'public/js/bp-tag-groups-public.js' );
        wp_localize_script(
            $this->plugin_name,
            'BPGRPTG_Public_JS_Obj',
            array(
                'ajaxurl'						=>	admin_url( 'admin-ajax.php' ),
                'loader_url'					=>	includes_url( 'images/spinner-2x.gif' ),
                'default_group_tags'            =>  $bp_tag_groups->bp_group_default_tags,
                'add_tag_error_already_added'   =>  esc_html__( 'This tag has been added already.', 'bp-tag-groups' ),
            )
        );

	}

    /**
     * Function called to create section to allow selecting group tags while creating groups.
     */
	public function bpgrptg_add_tag_create_group() {

        $file = BPGRPTG_PLUGIN_PATH . 'public/includes/bp-tag-groups-add-tag.php';
        if( file_exists( $file ) ) {
            require_once ( $file );
        }

    }

    /**
     * Function called to create section to allow selecting group tags
     */
    public function bpgrptg_add_tag_group_edit_details() {

        $file = BPGRPTG_PLUGIN_PATH . 'public/includes/bp-tag-groups-add-tag-edit-details.php';
        if( file_exists( $file ) ) {
            require_once ( $file );
        }

    }

}
