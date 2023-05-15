<?php
/**
 * Class Settings
 *
 * @package LittleBizzy\DisableAttachmentPages
 */

declare( strict_types=1 );

// phpcs:disable Squiz.Commenting.FunctionComment.SpacingAfterParamType

namespace LittleBizzy\DisableAttachmentPages;

use BadMethodCallException;

/**
 * Class Settings
 *
 * @method static get_options(): array
 * @method static get_default_redirection(): string
 *
 * @package LittleBizzy\DisableAttachmentPages
 */
class Settings {
	/**
	 * Settings constructor.
	 */
	public function __construct() {
		// This will add the direct "Settings" link inside wp plugins menu.
		add_filter(
			'plugin_action_links_disable-attachment-pages/disable-attachment-pages.php',
			array( $this, 'settings_link' )
		);

		// Register options page.
		add_action(
			'admin_menu',
			array( $this, 'register_options_page' )
		);

		// Register settings.
		add_action(
			'admin_init',
			array( $this, 'settings_init' )
		);
	}

	/**
	 * Add link to settings page.
	 *
	 * @param array<string, string> $links Links.
	 *
	 * @return array<string, string>
	 */
	public function settings_link( array $links ): array {
		$settings = array(
			'setting' => sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=disable-attachment-pages' ),
				__( 'Settings', 'disable-attachment-pages' )
			),
		);

		return array_merge( $settings, $links );
	}

	/**
	 * Register options page.
	 */
	public function register_options_page(): void {
		add_options_page(
			esc_html__( 'Disables attachment page URLs', 'disable-attachment-pages' ),
			esc_html__( 'Disables attachment page URLs', 'disable-attachment-pages' ),
			'manage_options',
			'disable-attachment-pages',
			array( $this, 'options_page_html' )
		);
	}

	/**
	 * Render options page.
	 *
	 * @return void
	 */
	public function options_page_html(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		settings_errors( 'disable-attachment-pages_messages' );

		require_once __DIR__ . '/partials/options-page-html.php';
	}

	/**
	 * Register option and settings.
	 */
	public function settings_init(): void {
		// Register setting for 'disable-attachment-pages' page.
		register_setting(
			'disable-attachment-pages',
			'disable-attachment-pages',
			array(
				'type'              => 'array',
				'default'           => array( 'default_redirection' => 'home_page' ),
				'sanitize_callback' => array( $this, 'sanitize_options' )
			)
		);

		// Register section in the 'disable-attachment-pages' page.
		add_settings_section(
			'default',
			'',
			'__return_empty_string',
			'disable-attachment-pages'
		);

		// Register field in the 'default' section, inside the 'disable-attachment-pages' page.
		add_settings_field(
			'default_redirection',
			__( 'Redirect to:', 'disable-attachment-pages' ),
			array( $this, 'field_select_cb' ),
			'disable-attachment-pages',
			'default',
			array(
				'label_for'   => 'default_redirection',
				'description' => __( 'Note: if you choose parent page, but parent page is trashed/deleted, we will redirect to homepage instead.', 'disable-attachment-pages' ),
				'default'     => 'home_page',
				'options'     => array(
					'home_page'   => __( 'Home page', 'disable-attachment-pages' ),
					'parent_page' => __( 'Parent page', 'disable-attachment-pages' ),
					'file_url'    => __( 'File', 'disable-attachment-pages' ),
				),
			)
		);
	}

	/**
	 * Field select callbakc function.
	 *
	 * @param array $args Arguments.
	 *
	 * @void
	 */
	public function field_select_cb( array $args ): void {
		require_once __DIR__ . '/partials/field-select.php';
	}

	/**
	 * Get option value magic method.
	 *
	 * @param string $name Getter functions name.
	 * @param array $arguments Functions arguments.
	 *
	 * @return mixed
	 *
	 * @throws BadMethodCallException Exception if not a getter function.
	 */
	public static function __callStatic( string $name, array $arguments ) {
		if ( 0 !== strpos( $name, 'get_' ) ) {
			throw new BadMethodCallException( $name . ' is not defined in ' . __CLASS__ );
		}

		$options = (array) get_option( 'disable-attachment-pages' );

		$option_name = substr( $name, 4 );

		if ( 'options' === $option_name ) {
			return $options;
		} elseif ( isset( $options[ $option_name ] ) ) {
			return $options[ $option_name ];
		} else {
			throw new BadMethodCallException( $option_name . ' is not defined in ' . __CLASS__ );
		}
	}

	/**
	 * Sanitize options.
	 *
	 * @param array $options Options.
	 *
	 * @return array
	 */
	public function sanitize_options( array $options ): array {
		$redirections = array( 'home_page', 'parent_page', 'file_url' );

		$default_redirection = trim( $options['default_redirection'] );

		$options['default_redirection'] = in_array( $default_redirection, $redirections, true ) ? $default_redirection : 'home_page';

		return $options;
	}
}
