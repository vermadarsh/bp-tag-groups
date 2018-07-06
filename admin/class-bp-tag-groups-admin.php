<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/admin
 * @author     Adarsh verma <adarsh.srmcem@gmail.com>
 */
class Bp_Tag_Groups_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		/**
		 * Save the added tag by the administrator.
		 */
		if ( isset( $_POST[ 'bpgrptg-add-tag-submit' ] ) && wp_verify_nonce( $_POST[ 'bpgrptg-add-tag-nonce' ], 'bpgrptg-add-tag' ) ) {
			$this->bpgrptg_add_tag();
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, BPGRPTG_PLUGIN_URL . 'admin/css/bp-tag-groups-admin.css' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_enqueue_scripts() {

	    global $bp_tag_groups;
	    wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( $this->plugin_name, BPGRPTG_PLUGIN_URL . 'admin/js/bp-tag-groups-admin.js' );
		wp_localize_script(
			$this->plugin_name,
			'BPGRPTG_Admin_JS_Obj',
			array(
				'ajaxurl'						=>	admin_url( 'admin-ajax.php' ),
				'loader_url'					=>	includes_url( 'images/spinner-2x.gif' ),
                'default_group_tags'            =>  $bp_tag_groups->bp_group_default_tags,
                'add_tag_error_empty'           =>  esc_html__( 'Please enter tag name.', 'bp-tag-groups' ),
                'add_tag_error_already_added'   =>  esc_html__( 'This tag hal been added already.', 'bp-tag-groups' ),
			)
		);

	}

	/**
	 * Function called to create settings page for the plugin
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_plugin_settings_page() {

		add_options_page( __( 'BP Tag Groups Settings', 'bp-tag-groups' ), __( 'BP Tag Groups', 'bp-tag-groups' ), 'manage_options', $this->plugin_name, array( $this, 'bpgrptg_admin_settings_page' ) );

	}

	/**
	 * Function called to create settings page for the plugin
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_admin_settings_page() {

		$file = BPGRPTG_PLUGIN_PATH . 'admin/includes/bp-tag-groups-admin-listing.php';
		if( file_exists( $file ) ) {
			require_once ( $file );
		}

	}

	/**
	 * Function called to add tags and save them in database.
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_add_tag() {

		$tag_name = sanitize_text_field( $_POST['bpgrptg-tag-name'] );
		$tag_desc = sanitize_text_field( $_POST['bpgrptg-tag-description'] );

		$bp_group_default_tags = get_option( 'bp_group_default_tags' );

		if( ! is_array( $bp_group_default_tags ) ) {
			$bp_group_default_tags = array();
		}

		$bp_group_default_tags[] = array(
			'tag_name'  =>  $tag_name,
			'tag_desc'  =>  $tag_desc
		);
		//debug( $bp_group_default_tags ); die;
		update_option( 'bp_group_default_tags', $bp_group_default_tags );
		?>
		<div class="notice updated" id="message">
			<p>
				<?php echo sprintf( __( 'Tag added: %1$s', 'bp-tag-groups' ), '<strong>' . $tag_name . '</strong>' );?>
			</p>
		</div>
		<?php

	}

	/**
	 * Function called to add custom metabox in edit group admin panel.
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_edit_grp_metabox() {

		add_meta_box( 'bpgrptg-grp-tag', esc_html__( 'Tags', 'bp-tag-groups' ), array( &$this, 'bpgrptg_tag_grp_metabox_content'), get_current_screen()->id, 'side', 'core');

    }

	/**
	 * Function called to add custom metabox content in edit group admin panel.
	 *
	 * @since    1.0.0
	 */
    public function bpgrptg_tag_grp_metabox_content( $item = false ) {

	    $file = BPGRPTG_PLUGIN_PATH . 'admin/includes/bp-tag-groups-metabox.php';
	    if( file_exists( $file ) ) {
		    require_once ( $file );
	    }

    }

	/**
     * Function called to update the group tags in group meta
     *
	 * @param $group_id
	 */
    public function bpgrptg_update_grp_metabox( $group_id ) {

        $this_group_tags = $_POST['bpgrptg-this-group-tags'];
        if( ! empty( $this_group_tags ) ) {
	        $this_group_tags = stripslashes( $this_group_tags );
	        $this_group_tags = str_replace( '[', '', $this_group_tags );
	        $this_group_tags = str_replace( ']', '', $this_group_tags );
	        $this_group_tags = explode( ',', $this_group_tags );
	        groups_delete_groupmeta( $group_id, '_bpgrptg_group_tag' );
	        foreach( $this_group_tags as $tag ) {
	            $tag = trim( $tag, '"' );
		        groups_add_groupmeta( $group_id, '_bpgrptg_group_tag', $tag );
	        }
        }

    }

	/**
     * Function hooked to add a custom column to bp groups list table.
     *
	 * @param $columns
	 *
	 * @return array
	 */
    public function bpgrptg_groups_list_tbl_column( $columns ) {

        $columns[] = esc_html__( 'Tags', 'bp-tag-groups' );
        return $columns;

    }

}
