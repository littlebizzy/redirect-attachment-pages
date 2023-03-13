<?php
/**
 * Plugin Name: Disable Attachment Pages
 * Plugin URI: https://www.littlebizzy.com/plugins/disable-attachment-pages
 * Description: Disables attachment page URLs
 * Version: 1.3.0
 * Author: LittleBizzy
 * Author URI: https://www.littlebizzy.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * GitHub Plugin URI: littlebizzy/disable-attachment-pages
 * Primary Branch: master
 * Prefix: DSATCH
 * Text Domain:       disable-attachment-pages
 * Domain Path:       /languages/
 *
 * @package LittleBizzy\DisableAttachmentPages
 */

declare( strict_types=1 );

// phpcs:disable Squiz.Commenting.FunctionComment.SpacingAfterParamType

namespace LittleBizzy\DisableAttachmentPages;

defined( 'ABSPATH' ) || exit;

define( 'DISABLE_ATTACHMENT_PAGES_FILE', __FILE__ );

require_once __DIR__ . '/vendor/autoload.php';

new Bootstrap();
