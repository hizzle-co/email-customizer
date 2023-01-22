<?php
/**
 * Contains the main plugin class.
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The main plugin class.
 *
 * @since 1.0.0
 */
class Email_Customizer {

	/**
	 * @var Email_Customizer_Admin
	 * @since 1.0.0
	 */
	public $admin;

	/**
	 * @var Email_Customizer_Mailer
	 * @since 1.0.0
	 */
	public $mailer;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {

		// Load plugin files.
		$this->include_files();

		// Init the admin.
		$this->admin  = new Email_Customizer_Admin( 'email_customizer_customize' );

		// Init the mailer.
		$this->mailer = new Email_Customizer_Mailer();

		// Init the WC integration.
		add_action( 'woocommerce_email', array( $this, 'init_wc' ) );
	}

	/**
	 * Includes plugin files.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function include_files() {

		require_once plugin_dir_path( email_customizer_get_plugin_file() ) . 'vendor/autoload.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-defaults.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-presstomizer.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-template.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-email-customizer-mailer.php';

	}

	/**
	 * Initializes the WC integration.
	 *
	 * @since 1.0.0
	 * @param WC_Email $email
	 */
	public function init_wc( $email ) {

		// Bail if we're not customizing WooCommerce emails.
		$options = get_option( 'email_customizer', array() );

		if ( empty( $options['style_woocommerce_emails'] ) ) {
			return;
		}

		// Remove the default header and footer.
		remove_action( 'woocommerce_email_header', array( $email, 'email_header' ) );
		remove_action( 'woocommerce_email_footer', array( $email, 'email_footer' ) );

		// Add our own header and footer.
		add_action( 'woocommerce_email_header', array( $this->mailer, 'template_top' ) );
		add_action( 'woocommerce_email_footer', array( $this->mailer, 'template_bottom' ) );
	}
}
