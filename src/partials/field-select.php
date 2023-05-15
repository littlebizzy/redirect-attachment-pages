<?php

defined( 'ABSPATH' ) || exit;

use LittleBizzy\DisableAttachmentPages\Settings;

/* @global $args */

$field_id = esc_attr( $args['label_for'] );

?>

<select id="<?php echo $field_id; ?>" name="disable-attachment-pages[<?php echo $field_id ?>]">

	<?php foreach ( $args['options'] as $value => $label ) : ?>
		<option
			<?php selected( $value, Settings::get_default_redirection() ); ?>
			value="<?php echo esc_attr( $value ); ?>"
		>
			<?php echo esc_html( $label ); ?>
		</option>
	<?php endforeach; ?>

</select>

<?php if ( isset( $args['description'] ) ) : ?>
	<p class="description">
		<?php echo esc_html( $args['description'] ); ?>
	</p>
<?php endif; ?>
