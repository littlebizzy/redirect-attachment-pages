<?php

defined( 'ABSPATH' ) || exit;

?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="<?= esc_url( admin_url( 'options.php' ) ) ?>" method="post">
		<?php
		settings_fields( 'redirect-attachment-pages' );

		do_settings_sections( 'redirect-attachment-pages' );

		submit_button( __( 'Save Settings', 'redirect-attachment-pages' ) );
		?>
	</form>
</div>
