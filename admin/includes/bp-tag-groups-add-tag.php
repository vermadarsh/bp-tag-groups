<?php
/**
 * Created by PhpStorm.
 * User: Adarsh Verma
 * Date: 09-07-2018
 * Time: 10:51
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit, if accessed directly.
?>
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
