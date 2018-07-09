<?php
/**
 * Created by PhpStorm.
 * User: adars
 * Date: 7/9/2018
 * Time: 9:17 PM
 */
if( ! defined( 'ABSPATH' ) ) exit; // Exit, if accessed directly.

$gid  = bp_get_group_id();
$group_meta = groups_get_groupmeta( $gid );
$tags = ! empty( $group_meta['_bpgrptg_group_tag'] ) ? $group_meta['_bpgrptg_group_tag'] : array();
?>
<label for="group-tag"><?php _e( 'Group Tags (optional)', 'bp-tag-groups' ); ?></label>
<input type="text" name="group-tag" id="bpgrptg-new-tag-input" aria-required="true" value="" />
<input type="button" class="button bpgrptg-tagadd" value="<?php esc_html_e( 'Add', 'bp-tag-groups' );?>">
<ul class="tagchecklist bpgrptg-tags-list" role="list">
    <?php
    $hidden_tags = array();
    if( ! empty( $tags ) ) :
        foreach( $tags as $tag ) :
            $hidden_tags[] = $tag;
            ?>
            <li>
                <button type="button" id="bpgrptg-remove-tag-<?php echo $tag;?>" class="bpgrptg-remove-tag ntdelbutton" data-tag="<?php echo $tag;?>">
                    <span class="remove-tag-icon" aria-hidden="true"></span>
                </button>&nbsp;<?php echo $tag;?>
            </li>
        <?php
        endforeach;
    endif;
    ?>
</ul>
<input type="hidden" name="bpgrptg-this-group-tags" id="bpgrptg-this-group-tags" value='<?php echo ( ! empty( $hidden_tags ) ) ? json_encode( $hidden_tags ) : '';?>'>