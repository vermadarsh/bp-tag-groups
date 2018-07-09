<?php
/**
 * Created by PhpStorm.
 * User: Adarsh Verma
 * Date: 06-07-2018
 * Time: 10:49
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit, if accessed directly.

$gid  = filter_input( INPUT_GET, 'gid', FILTER_SANITIZE_NUMBER_INT );
$group_meta = groups_get_groupmeta( $gid );
$tags = ! empty( $group_meta['_bpgrptg_group_tag'] ) ? $group_meta['_bpgrptg_group_tag'] : array();
?>
<div class="inside">
    <div class="tagsdiv" id="bp-group-tag">
        <p class="bpgrptg-add-tag-error"></p>
        <div class="jaxtag">
            <div class="nojs-tags hide-if-js">
                <label for="tax-input-post_tag"><?php esc_html_e( 'Add or remove tags', 'bp-tag-groups' );?></label>
            </div>
            <div class="ajaxtag hide-if-no-js">
                <label class="screen-reader-text" for="new-tag-post_tag"><?php esc_html_e( 'Add New Tag', 'bp-tag-groups' );?></label>
                <p>
                    <input type="text" id="bpgrptg-new-tag-input" name="bpgrptg-new-tag-input" class="newtag form-input-tip ui-autocomplete-input" size="16" autocomplete="off" aria-describedby="new-tag-post_tag-desc" value="" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-owns="ui-id-1">
                    <input type="button" class="button bpgrptg-tagadd" value="<?php esc_html_e( 'Add', 'bp-tag-groups' );?>">
                </p>
            </div>
        </div>
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

    </div>
    <!--<p class="hide-if-no-js">
        <button type="button" class="button-link tagcloud-link" id="link-post_tag" aria-expanded="true">Choose from the
            most used tags
        </button>
    <div id="tagcloud-post_tag" class="the-tagcloud">
        <ul class="wp-tag-cloud" role="list">
            <li><a href="#" role="button" class="tag-cloud-link tag-link-18 tag-link-position-1"
                   style="font-size: 22pt;" aria-label="firstpost (2 items)">firstpost</a></li>
            <li><a href="#" role="button" class="tag-cloud-link tag-link-19 tag-link-position-2" style="font-size: 8pt;"
                   aria-label="mypost (1 item)">mypost</a></li>
        </ul>
    </div>
    </p>-->
</div>
