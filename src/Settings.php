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

		add_action(
			'cmb2_admin_init',
			array( $this, 'register_options_metabox' )
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
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function register_options_metabox() {
		$cmb_options = \new_cmb2_box(
			array(
				'id'           => 'disable-attachment-pages',
				'title'        => esc_html__( 'Disables attachment page URLs', 'disable-attachment-pages' ),
				'object_types' => array( 'options-page' ),
				'option_key'   => 'disable-attachment-pages',
				'icon_url'     => 'dashicons-external',
				'capability'   => 'manage_options',
				'parent_slug'  => 'options-general.php',
			)
		);

		$cmb_options->add_field(
			array(
				'name'             => __( 'Redirect to:', 'disable-attachment-pages' ),
				'desc'             => __( 'Note: if you choose parent page, but parent page is trashed/deleted, we will redirect to homepage instead.', 'disable-attachment-pages' ),
				'id'               => 'default_redirection',
				'type'             => 'select',
				'show_option_none' => false,
				'default'          => 'home_page',
				'options'          => array(
					'home_page'   => __( 'Home page', 'disable-attachment-pages' ),
					'parent_page' => __( 'Parent page', 'disable-attachment-pages' ),
					'file_url'    => __( 'File', 'disable-attachment-pages' ),
				),
				'sanitization_cb'  => array( $this, 'sanitize_action' ),
			)
		);
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

		$options = shortcode_atts(
			array( 'default_redirection' => 'home_page' ),
			(array) get_option( 'disable-attachment-pages', array() )
		);

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
	 * Sanitize action.
	 *
	 * @param string|null $action Action.
	 *
	 * @return string
	 */
	public function sanitize_action( ?string $action ): string {
		$action = trim( $action );

		$actions = array( 'home_page', 'parent_page', 'file_url' );

		return in_array( $action, $actions, true ) ? $action : 'home_page';
	}
}
