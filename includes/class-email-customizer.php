<?php
/**
 * Main plugin class.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Contains the main plugin class.
 *
 */
class Email_Customizer {

	/**
	 * Class constructor.
	 */
	public function __construct(){

        // Load plugin files.
        $this->include_files();

        // Init the admin.
        new Email_Customizer_Admin( 'email_customizer_customize' );
	}

	/**
	 * Includes plugin files.
	 *
	 */
	public function include_files() {

		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-defaults.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-presstomizer.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-template.php';

    }

}
