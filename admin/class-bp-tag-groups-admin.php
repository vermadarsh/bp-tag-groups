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

		/**
		 * Save the updated tag by the administrator.
		 */
		if ( isset( $_POST[ 'bpgrptg-update-tag-submit' ] ) && wp_verify_nonce( $_POST[ 'bpgrptg-update-tag-nonce' ], 'bpgrptg-update-tag' ) ) {
			$this->bpgrptg_update_tag();
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
                'admin_settings_url'            =>  admin_url( 'options-general.php?page=bp-tag-groups' ),
                'add_tag_error_empty'           =>  esc_html__( 'Please enter tag name.', 'bp-tag-groups' ),
                'add_tag_error_already_added'   =>  esc_html__( 'This tag has been added already.', 'bp-tag-groups' ),
                'delete_tag_cnf_msg'            =>  esc_html__( 'Deleting this tag will remove itself from the groups it is tagged with. Do you really want to delete this tag?', 'bp-tag-groups' ),
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
        $add_this_tag = true;
		$bp_group_default_tags = get_option( 'bp_group_default_tags' );
        if( ! is_array( $bp_group_default_tags ) ) {
            $bp_group_default_tags = array();
        }

        if( ! empty( $bp_group_default_tags ) ) {
            foreach( $bp_group_default_tags as $tag ) {
                if( $tag['tag_name'] === $tag_name ) {
                    $add_this_tag = false;
                    break;
                }
            }
        }

        if( true === $add_this_tag ) {
            $bp_group_default_tags[] = array(
                'tag_name'  =>  $tag_name,
                'tag_desc'  =>  $tag_desc
            );
            update_option( 'bp_group_default_tags', $bp_group_default_tags );
            ?>
            <div class="notice updated" id="message">
                <p>
                    <?php echo sprintf( __( 'Tag added: %1$s', 'bp-tag-groups' ), '<strong>' . $tag_name . '</strong>' );?>
                </p>
            </div>
            <?php
        } else {
            ?>
            <div class="notice error notice-error notice-alt" id="message">
                <p>
                    <?php echo sprintf( __( '%1$s tag already exists.', 'bp-tag-groups' ), '<strong>' . $tag_name . '</strong>' );?>
                </p>
            </div>
            <?php
        }

	}

	/**
	 * Function called to update tags and save them in database.
	 *
	 * @since    1.0.0
	 */
	public function bpgrptg_update_tag() {

	    $old_tag_name = sanitize_text_field( $_POST['bpgrptg-tag-old-name'] );
		$tag_name = sanitize_text_field( $_POST['bpgrptg-tag-name'] );
		$tag_desc = sanitize_text_field( $_POST['bpgrptg-tag-description'] );
		$update_this_tag = true;

		$bp_group_default_tags = get_option( 'bp_group_default_tags' );
		if( ! is_array( $bp_group_default_tags ) ) {
			$bp_group_default_tags = array();
		}

		if( ! empty( $bp_group_default_tags ) ) {
			foreach( $bp_group_default_tags as $tag ) {
				if( $tag['tag_name'] === $tag_name ) {
					$update_this_tag = false;
					break;
				}
			}
		}

		if( true === $update_this_tag ) {
			global $wpdb;
			$groupmeta_tbl = $wpdb->prefix . 'bp_groups_groupmeta';
			$meta_ids = $wpdb->get_results( "SELECT `id` FROM {$groupmeta_tbl} WHERE `meta_key` = '_bpgrptg_group_tag' AND `meta_value` = '{$old_tag_name}'" );
			if( ! empty( $meta_ids ) ) {
				foreach( $meta_ids as $m_id ) {
					$wpdb->update( $groupmeta_tbl, array( 'meta_value' => $tag_name ), array( 'id' => $m_id->id ) );
				}
			}

			// Update the default tags
			$key = array_search( $old_tag_name, array_column( $bp_group_default_tags, 'tag_name' ) );

			$bp_group_default_tags[ $key ] = array(
                'tag_name'  =>  $tag_name,
                'tag_desc'  =>  $tag_desc
            );

			update_option( 'bp_group_default_tags', $bp_group_default_tags );
			?>
            <div class="notice updated" id="message">
                <p>
					<?php echo sprintf( __( 'Tag updated: %1$s', 'bp-tag-groups' ), '<strong>' . $tag_name . '</strong>' );?>
                </p>
            </div>
			<?php
			wp_safe_redirect( admin_url( 'options-general.php?page=bp-tag-groups' ) );
			exit;
		} else {
			?>
            <div class="notice error notice-error notice-alt" id="message">
                <p>
					<?php echo sprintf( __( 'The %1$s tag is already in use.', 'bp-tag-groups' ), '<strong>' . $tag_name . '</strong>' );?>
                </p>
            </div>
			<?php
		}

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

        $columns['bpgrptg_tags'] = esc_html__( 'Tags', 'bp-tag-groups' );
        return $columns;

    }

    /**
     * Function called to show the custom column data
     *
     * @param string $retval
     * @param $column_name
     * @param $item
     */
    public function bpgrptg_groups_list_tbl_column_content( $retval = '', $column_name, $item ){

        if( 'bpgrptg_tags' === $column_name ) {
            $group_id = $item['id'];
            $group_meta = groups_get_groupmeta( $group_id );
            $tags = ! empty( $group_meta['_bpgrptg_group_tag'] ) ? $group_meta['_bpgrptg_group_tag'] : array();
            echo ! empty( $tags ) ? implode( ',', $tags ) : '';
        }

    }

    /**
     * AJAX called to delete tags.
     */
    public function bpgrptg_delete_tag() {

        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
        if( ! empty( $action ) && 'bpgrptg_delete_tag' === $action ) {
            $tag_name = filter_input( INPUT_POST, 'tag_name', FILTER_SANITIZE_STRING );
            $is_tagged = filter_input( INPUT_POST, 'is_tagged', FILTER_SANITIZE_STRING );

            global $bp_tag_groups, $wpdb;
            $groupmeta_tbl = $wpdb->prefix . 'bp_groups_groupmeta';
            $default_tags = $bp_tag_groups->bp_group_default_tags;
            $key_to_unset = '';
            foreach( $default_tags as $key => $default_tag ) {
                if( $tag_name === $default_tag['tag_name'] ) {
                    $key_to_unset = (int)$key;
                    break;
                }
            }

            if( '' !== $key_to_unset ) {
                unset( $default_tags[ $key ] );
            }
            update_option( 'bp_group_default_tags', $default_tags );

            /**
             * Check for remaining tags
             */
            $remaining_tags = count( $default_tags );
            $html = '';
            if( 0 === $remaining_tags ) {
                $html = '<tr id="tag-not-found"><td>' . esc_html__( 'No tags found.', 'bp-tag-groups' ) . '</td></tr>';
            }
            $remaining_tags_message = sprintf( _n( '%1$s item', '%1$s items', $remaining_tags ), $remaining_tags );

            // Delete this tag from the groups it is tagged to
            if( 'yes' === $is_tagged ) {
                $meta_ids = $wpdb->get_results( "SELECT `id` FROM {$groupmeta_tbl} WHERE `meta_key` = '_bpgrptg_group_tag' AND `meta_value` = '{$tag_name}'" );
                if( ! empty( $meta_ids ) ) {
                    foreach( $meta_ids as $m_id ) {
                        $wpdb->delete( $groupmeta_tbl, array( 'id' => $m_id->id ) );
                    }
                }
            }

            $result = array(
                'message'                   =>  'bpgrptg-tag-deleted',
                'html'                      =>  $html,
                'remaining_tags_message'    =>  $remaining_tags_message
            );
            wp_send_json_success( $result );
        }

    }

    /**
     * AJAX called to search tags.
     */
    public function bpgrptg_search_tag() {

        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
        if( ! empty( $action ) && 'bpgrptg_search_tag' === $action ) {
            $keyword = filter_input( INPUT_POST, 'keyword', FILTER_SANITIZE_STRING );

            global $bp_tag_groups, $wpdb;
            $group_meta_tbl = $wpdb->prefix . 'bp_groups_groupmeta';
            $default_tags = $bp_tag_groups->bp_group_default_tags;
            $search_html = '';
            $found_tags = 0;
            foreach( $default_tags as $key => $default_tag ) {
                $tag_name_pos = stripos( $default_tag['tag_name'], $keyword );
                $tag_desc_pos = stripos( $default_tag['tag_desc'], $keyword );
                if( false !== $tag_name_pos || false !== $tag_desc_pos ) {
                    $found_tags++;
                    $groups = $wpdb->get_results( "SELECT `group_id` FROM {$group_meta_tbl} WHERE `meta_key` = '_bpgrptg_group_tag' AND `meta_value` = '{$default_tag['tag_name']}'" );
                    $tag_groups_count = count( $groups );
                    $search_html .= '<tr id="tag-' . $default_tag['tag_name'] . '">';
                    $search_html .= '<td class="name column-name has-row-actions column-primary" data-colname="Name">';
                    $search_html .= '<strong><a class="row-title" href="javascript:void(0);">' . $default_tag['tag_name'] . '</a></strong><br>';
                    $search_html .= '<div class="row-actions">';
                    $search_html .= '<span class="edit"><a href="' . admin_url( 'options-general.php?page=bp-tag-groups&action=edit&tag=' . $default_tag['tag_name'] ) . '">' . esc_html__( 'Edit', 'bp-tag-groups' ) . '</a> | </span>';
                    $search_html .= '<span class="delete"><a href="javascript:void(0);" class="delete-tag bpgrptg-delete-tag aria-button-if-js" role="button">' . esc_html__( 'Delete', 'bp-tag-groups' ) . '</a></span>';
                    $search_html .= '</div>';
                    $search_html .= '</td>';
                    $search_html .= '<td class="description column-description" data-colname="Description">';
                    $search_html .= '<span aria-hidden="true">' . ( ! empty( $default_tag['tag_desc'] ) ) ? $default_tag['tag_desc'] : '—' . '</span>';
                    $search_html .= '</td>';
                    $search_html .= '<td class="posts column-posts" data-colname="Count">' . $tag_groups_count . '</td>';
                    $search_html .= '</tr>';
                }
            }

            if( '' === $search_html ) {
                $search_html = '<tr id="tag-not-found" class="bpgrptg-no-tag-matched"><td colspan="3">' . esc_html__( 'No tags matched your search results.', 'bp-tag-groups' ) . '</td></tr>';
            }

            $found_tags_text = sprintf( _n( '%1$s tag found', '%1$s tags found', $found_tags ), $found_tags );

            $result = array(
                'message'                   =>  'bpgrptg-tag-searched',
                'found_tags'                =>  $found_tags,
                'found_tags_text'           =>  $found_tags_text,
                'html'                      =>  $search_html
            );
            wp_send_json_success( $result );
        }

    }

}
