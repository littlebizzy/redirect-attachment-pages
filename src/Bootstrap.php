<?php
/**
 * Class Bootstrap
 *
 * @package LittleBizzy\DisableAttachmentPages
 */

declare( strict_types=1 );

namespace LittleBizzy\DisableAttachmentPages;

/**
 * Class Bootstrap
 *
 * @package LittleBizzy\DisableAttachmentPages
 */
class Bootstrap {
	/**
	 * Constructor.
	 */
	public function __construct() {
		load_plugin_textdomain(
			'disable-attachment-pages',
			false,
			plugin_basename( dirname( DISABLE_ATTACHMENT_PAGES_FILE ) ) . '/languages'
		);

		new Settings();
		new RequestHandler();

		// Disable wordpress.org updates.
		add_filter(
			'gu_override_dot_org',
			array( $this, 'disable_builtin_updates' )
		);
	}

	/**
	 * Disable wordpress.org updates.
	 *
	 * @param array $overrides Overrides.
	 *
	 * @return array
	 */
	public function disable_builtin_updates( array $overrides ): array {
		return array_merge(
			$overrides,
			array( 'disable-attachment-pages/disable-attachment-pages.php' )
		);
	}
}
