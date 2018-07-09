<?php
/**
 * Created by PhpStorm.
 * User: Adarsh Verma
 * Date: 09-07-2018
 * Time: 10:51
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit, if accessed directly.
global $bp_tag_groups;
$default_tags = $bp_tag_groups->bp_group_default_tags;

$tag_data = array_filter( $default_tags, function ( $item ) use ( $tag ) {
	if ( stripos( $item['tag_name'], $tag ) !== false ) {
		return true;
	}
	return false;
});
$tag_exists = ! empty( $tag_data ) ? true : false;
?>
<h2><?php echo sprintf( esc_html__( 'Edit Tag: %1$s', 'bp-tag-groups' ), $tag );?></h2>
<!-- Check if tag exists of such name -->
<?php if( true === $tag_exists ) :?>
    <?php
	$array_keys = array_keys( $tag_data );
	$key = $array_keys[0];
    ?>
    <p class="bpgrptg-edit-tag-warning"><?php esc_html_e( 'NOTE: Updating this tag will update its presence in every group it would be used in.', 'bp-tag-groups' );?></p>
    <form id="bpgrptg-update-tag" method="POST" action="" class="validate">
        <div class="form-field form-required term-name-wrap">
            <label for="bpgrptg-tag-name"><?php esc_html_e( 'Name', 'bp-tag-groups' );?></label>
            <input name="bpgrptg-tag-name" type="text" size="40" aria-required="true" required value="<?php echo ! empty( $_POST['bpgrptg-tag-name'] ) ? $_POST['bpgrptg-tag-name'] : $tag_data[ $key ]['tag_name'];?>">
            <p><?php esc_html_e( 'The name is how it appears on your site.', 'bp-tag-groups' );?></p>
        </div>
        <div class="form-field term-description-wrap">
            <label for="bpgrptg-tag-description"><?php esc_html_e( 'Description', 'bp-tag-groups' );?></label>
            <textarea name="bpgrptg-tag-description" id="bpgrptg-tag-description" rows="5" cols="40"><?php echo ! empty( $_POST['bpgrptg-tag-description'] ) ? $_POST['bpgrptg-tag-description'] : $tag_data[ $key ]['tag_desc'];?></textarea>
            <p><?php esc_html_e( 'The description is not prominent by default; however, some themes may show it.', 'bp-tag-groups' );?></p>
        </div>

        <p class="submit">
			<?php wp_nonce_field( 'bpgrptg-update-tag', 'bpgrptg-update-tag-nonce' ); ?>
            <input type="hidden" name="bpgrptg-tag-old-name" value="<?php echo $tag;?>">
            <input type="submit" name="bpgrptg-update-tag-submit" class="button button-primary" value="<?php esc_html_e( 'Update Tag', 'bp-tag-groups' );?>">
            <input type="button" id="bpgrptg-update-tag-cancel" class="button button-secondary" value="<?php esc_html_e( 'Cancel', 'bp-tag-groups' );?>">
        </p>
    </form>
<?php else :?>
    <p class="bpgrptg-no-tag-exists"><?php esc_html_e( 'No such tag exists !!', 'bp-tag-groups' );?></p>
<?php endif;?>
