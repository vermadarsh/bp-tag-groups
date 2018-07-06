<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Tag_Groups
 * @subpackage Bp_Tag_Groups/admin/includes
 */

global $bp_tag_groups, $wpdb;
$group_meta_tbl = $wpdb->prefix . 'bp_groups_groupmeta';
$group_tags = $bp_tag_groups->bp_group_default_tags;
$group_tags_count = count( $group_tags );
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap nosubsub">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'BuddyPress Groups Tags', 'bp-tag-groups' );?></h1>
    <hr class="wp-header-end">

    <p class="search-box">
        <label class="screen-reader-text" for="tag-search-input"><?php esc_html_e( 'Search Tags:', 'bp-tag-groups' );?></label>
        <input type="search" id="bpgrptg-search-tags-input" placeholder="<?php esc_html_e( 'Enter keyword...', 'bp-tag-groups' )?>">
        <input type="button" id="bpgrptg-search-tags-submit" class="button" value="<?php esc_html_e( 'Search Tags', 'bp-tag-groups' )?>">
    </p>

    <div id="col-container" class="wp-clearfix">
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h2><?php esc_html_e( 'Add New Tag', 'bp-tag-groups' );?></h2>
                    <form id="bpgrptg-add-tag" method="POST" action="" class="validate">
                        <div class="form-field form-required term-name-wrap">
                            <label for="bpgrptg-tag-name"><?php esc_html_e( 'Name', 'bp-tag-groups' );?></label>
                            <input name="bpgrptg-tag-name" id="bpgrptg-tag-name" type="text" value="" size="40" aria-required="true" required>
                            <p><?php esc_html_e( 'The name is how it appears on your site.', 'bp-tag-groups' );?></p>
                        </div>
                        <div class="form-field term-description-wrap">
                            <label for="bpgrptg-tag-description"><?php esc_html_e( 'Description', 'bp-tag-groups' );?></label>
                            <textarea name="bpgrptg-tag-description" id="bpgrptg-tag-description" rows="5" cols="40"></textarea>
                            <p><?php esc_html_e( 'The description is not prominent by default; however, some themes may show it.', 'bp-tag-groups' );?></p>
                        </div>

                        <p class="submit">
	                        <?php wp_nonce_field( 'bpgrptg-add-tag', 'bpgrptg-add-tag-nonce' ); ?>
                            <input type="submit" name="bpgrptg-add-tag-submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Add New Tag', 'bp-tag-groups' );?>">
                        </p>
                    </form>
                </div>

            </div>
        </div><!-- /col-left -->

        <div id="col-right">
            <div class="col-wrap">
                <div class="tablenav top">
                    <div class="tablenav-pages one-page">
                        <span class="displaying-num"><?php echo sprintf( _n( '%1$s item', '%1$s items', $group_tags_count ), $group_tags_count );?></span>
                        <!--<span class="pagination-links">
                            <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                            <span class="tablenav-pages-navspan" aria-hidden="true">›</span>
                        </span>-->
                    </div>
                    <br class="clear">
                </div>
                <h2 class="screen-reader-text"><?php esc_html_e( 'Tags list', 'bp-tag-groups' );?></h2>
                <table class="wp-list-table widefat fixed striped tags">
                    <thead>
                        <tr>
                            <th scope="col" id="name" class="manage-column column-name column-primary sortable desc">
                                <a href="javascript:void(0);"><span><?php esc_html_e( 'Name', 'bp-tag-groups' );?></span></a>
                            </th>
                            <th scope="col" id="description" class="manage-column column-description sortable desc">
                                <a href="javascript:void(0);"><span><?php esc_html_e( 'Description', 'bp-tag-groups' );?></span></a>
                            </th>
                            <th scope="col" id="posts" class="manage-column column-posts num sortable desc">
                                <a href="javascript:void(0);"><span><?php esc_html_e( 'Count', 'bp-tag-groups' );?></span></a>
                            </th>
                        </tr>
                    </thead>

                    <tbody id="the-list" data-wp-lists="list:tag">
                        <?php if( 0 === $group_tags_count ) :?>
                            <tr id="tag-not-found">
                                <td><?php esc_html_e( 'No tags found.', 'bp-tag-groups' );?></td>
                            </tr>
                        <?php else :?>
                            <?php foreach( $group_tags as $index => $tag ) :?>
                                <?php
		                        $groups = $wpdb->get_results( "SELECT `group_id` FROM {$group_meta_tbl} WHERE `meta_key` = '_bpgrptg_group_tag' AND `meta_value` = '{$tag['tag_name']}'" );
                                $tag_groups_count = count( $groups );
                                ?>
                                <tr id="tag-<?php echo $index;?>">
                                    <td class="name column-name has-row-actions column-primary" data-colname="Name">
                                        <strong><a class="row-title" href="javascript:void(0);"><?php echo $tag['tag_name'];?></a></strong><br>
                                        <div class="row-actions">
                                            <span class="edit"><a href="javascript:void(0);"><?php esc_html_e( 'Edit', 'bp-tag-groups' );?></a> | </span>
                                            <span class="delete"><a href="javascript:void(0);" class="delete-tag bpgrptg-delete-tag aria-button-if-js" role="button"><?php esc_html_e( 'Delete', 'bp-tag-groups' );?></a></span>
                                        </div>
                                    </td>
                                    <td class="description column-description" data-colname="Description">
                                        <span aria-hidden="true"><?php echo ! empty( $tag['tag_desc'] ) ? $tag['tag_desc'] : '—';?></span>
                                    </td>
                                    <td class="posts column-posts" data-colname="Count"><a href="javascript:void(0);"><?php echo $tag_groups_count;?></a></td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div><!-- /col-right -->
    </div><!-- /col-container -->
</div>