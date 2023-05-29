<?php
/**
 * Class Bootstrap
 *
 * @package LittleBizzy\RedirectAttachmentPages
 */

declare( strict_types=1 );

namespace LittleBizzy\RedirectAttachmentPages;

require_once __DIR__ . '/Settings.php';
require_once __DIR__ . '/RequestHandler.php';

/**
 * Class Bootstrap
 *
 * @package LittleBizzy\RedirectAttachmentPages
 */
class Bootstrap {
	/**
	 * Constructor.
	 */
	public function __construct() {
		load_plugin_textdomain(
			'redirect-attachment-pages',
			false,
			plugin_basename( dirname( REDIRECT_ATTACHMENT_PAGES_FILE ) ) . '/languages'
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
			array( 'redirect-attachment-pages/redirect-attachment-pages.php' )
		);
	}
}
