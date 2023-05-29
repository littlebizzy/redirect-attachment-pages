<?php
/**
 * Class RequestHandler
 *
 * @package LittleBizzy\RedirectAttachmentPages
 */

declare( strict_types=1 );

//phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_readfile

namespace LittleBizzy\RedirectAttachmentPages;

/**
 * Class RequestHandler
 *
 * @package LittleBizzy\RedirectAttachmentPages
 */
class RequestHandler {
	const ATTACHMENT_REDIRECT_CODE = 301;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Redirect attachment page.
		add_action( 'template_redirect', array( $this, 'redirect' ) );
	}

	/**
	 * Redirect attachment page.
	 *
	 * @return void
	 */
	public function redirect(): void {
		if ( is_attachment() ) {
			$this->do( Settings::get_default_redirection() );
		}
	}

	/**
	 * Do selected action.
	 *
	 * @param string $action Action.
	 *
	 * @return void
	 */
	protected function do( string $action ): void {
		$method_name = 'redirect_to_' . $action;
		if ( method_exists( $this, $method_name ) ) {
			call_user_func( array( $this, $method_name ) );
		}
	}

	/**
	 * Redirect to home page.
	 *
	 * @return void
	 */
	protected function redirect_to_home_page(): void {
		wp_safe_redirect( site_url(), self::ATTACHMENT_REDIRECT_CODE );

		exit();
	}

	/**
	 * Redirect to file URL.
	 *
	 * @return void
	 */
	protected function redirect_to_file_url(): void {
		global $post;

		$file = get_attached_file( $post->ID );
		if ( file_exists( $file ) ) {
			header( 'Content-type: ' . $post->post_mime_type );
			readfile( $file );

			exit();
		} else {
			$this->redirect_to_home_page();
		}
	}

	/**
	 * Redirect to parent page.
	 *
	 * @return void
	 */
	protected function redirect_to_parent_page(): void {
		global $post;

		if (
			0 === $post->post_parent
			||
			'trash' === get_post_status( $post->post_parent )
		) {
			$this->redirect_to_home_page();
		} else {
			// Redirect to post/page from where attachment was uploaded.
			wp_safe_redirect( get_permalink( $post->post_parent ), self::ATTACHMENT_REDIRECT_CODE );

			exit();
		}
	}
}
