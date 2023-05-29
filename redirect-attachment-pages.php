<?php
/**
 * Plugin Name: Redirect Attachment Pages
 * Plugin URI: https://www.littlebizzy.com/plugins/redirect-attachment-pages
 * Description: Redirect attachment page URLs
 * Version: 1.4.0
 * Author: LittleBizzy
 * Author URI: https://www.littlebizzy.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * GitHub Plugin URI: littlebizzy/redirect-attachment-pages
 * Primary Branch: master
 * Prefix: DSATCH
 * Text Domain: redirect-attachment-pages
 * Domain Path: /languages/
 *
 * @package LittleBizzy\RedirectAttachmentPages
 */

declare( strict_types=1 );

// phpcs:disable Squiz.Commenting.FunctionComment.SpacingAfterParamType

namespace LittleBizzy\RedirectAttachmentPages;

defined( 'ABSPATH' ) || exit;

define( 'REDIRECT_ATTACHMENT_PAGES_FILE', __FILE__ );

require_once __DIR__ . '/src/Bootstrap.php';

new Bootstrap();
