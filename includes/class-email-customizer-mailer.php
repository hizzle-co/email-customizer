<?php
/**
 * Contains the mailer class.
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The mailer class.
 *
 * @since      1.0.0
 *
 */
class Email_Customizer_Mailer {

	/**
	 * @var bool Whether or not the email was initially a HTML email
	 */
	public $forced_html = false;

	/**
	 * @var null|string
	 */
	public static $custom_footer_1 = null;

	/**
	 * @var null|string
	 */
	public static $custom_footer_2 = null;

	/**
	 * @var string
	 */
	public static $preview_text = '';

	/**
	 * Class constructor.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {

		add_filter( 'wp_mail', array( $this, 'maybe_wrap_email' ), 11 );
		add_action( 'wp_mail_content_type', array( $this, 'maybe_force_html' ), 100 );
		add_action( 'email_customizer_email_content', array( $this, 'maybe_remove_body' ), 5 );
		add_action( 'email_customizer_email_content', array( $this, 'maybe_convert_to_html' ), 8 );
		add_filter( 'noptin_email_after_apply_template', array( $this, 'maybe_process_noptin_email' ), 10, 2 );

	}

	/**
	 * Wrap emails with our template.
	 *
	 * @since 1.0.0
	 * @param array $args The email args.
	 * @return string
	 */
	public function maybe_wrap_email( $args ) {

		if ( apply_filters( 'email_customizer_disable_template_wrap', $this->is_wrapped( $args['message'] ), $args ) ) {
			return $args;
		}

		do_action( 'email_customizer_before_wrap_email', $args, $this );
		$args['message'] = $this->add_template( $args['message'] );
		do_action( 'email_customizer_after_wrap_email', $args, $this );

		return $args;
	}

	/**
	 * Prints the email header
	 *
	 * @param string $email_heading The email heading.
	 * @since 1.0.5
	 */
	public function template_top( $email_heading ) {

		$args                 = get_option( 'email_customizer', array() );
		$args                 = is_array( $args ) ? $args : array();
		$args['preview_text'] = self::$preview_text;
		$template             = new Email_Customizer_Template( $args );

		$template->render_top();

		if ( ! empty( $email_heading ) ) {
			echo '<h1 style="margin-bottom: 30px;font-size: 2rem;">' . wp_kses_post( $email_heading ) . '</h1>';
		}
	}

	/**
	 * Prints the email footer
	 *
	 * @since 1.0.5
	 */
	public function template_bottom() {

		$args     = get_option( 'email_customizer', array() );
		$args     = is_array( $args ) ? $args : array();
		$template = new Email_Customizer_Template( $args );

		$template->render_bottom();

	}

	/**
	 * Wraps a given content with our template.
	 *
	 * @param $email_content string The email content.
	 * @since 1.0.0
	 * @return string
	 */
	protected function add_template( $email_content ) {

		$email_content = apply_filters( 'email_customizer_email_content', $email_content );
		$args          = get_option( 'email_customizer', array() );
		$args          = is_array( $args ) ? $args : array();

		if ( ! is_null( self::$custom_footer_1 ) ) {
			$args['footer_1']      = self::$custom_footer_1;
			self::$custom_footer_1 = null;
		}

		if ( ! is_null( self::$custom_footer_2 ) ) {
			$args['footer_2']      = self::$custom_footer_2;
			self::$custom_footer_1 = null;
		}

		$args['preview_text'] = self::$preview_text;
		$args['content']      = $email_content;
		$template             = new Email_Customizer_Template( $args );

		ob_start();
		$template->render();
		return $this->inline_css( ob_get_clean() );

	}

	/**
	 * Checks if the email is already wrapped between `<body>` tags.
	 *
	 * @since 1.0.0
	 * @param string $maybe_html_content The email content.
	 * @return bool
	 */
	public function is_wrapped( $maybe_html_content ) {

		$matches = array();
		preg_match( '/<body[^>]*>(.*?)<\/body>/is', $maybe_html_content, $matches );

		if ( ! empty( $matches[1] ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Retrieves the contents of the `<body>` tags of HTML emails.
	 *
	 * @since 1.0.0
	 * @param string $maybe_html_content The email content.
	 * @return string
	 */
	public function maybe_remove_body( $maybe_html_content ) {

		$matches = array();
		preg_match( '/<body[^>]*>(.*?)<\/body>/is', $maybe_html_content, $matches );

		$this->forced_html = empty( $matches[1] );

		if ( ! empty( $matches[1] ) ) {
			return trim( $matches[1] );
		}

		return $maybe_html_content;

	}

	/**
	 * Converts a text email to HTML.
	 *
	 * @since 1.0.0
	 * @param string $text_content The email content.
	 * @return string
	 */
	public function maybe_convert_to_html( $text_content ) {
		return $this->forced_html ? wp_kses_post( wpautop( force_balance_tags( make_clickable( $text_content ) ) ) ) : $text_content;
	}

	/**
	 * Force all emails to use HTML.
	 *
	 * @since 1.0.0
	 * @param string $type The email type.
	 * @return string
	 */
	public function maybe_force_html( $type ) {

		if ( ! apply_filters( 'email_customizer_force_html', true ) ) {
			return $type;
		}

		return 'text/html';

	}

	/**
	 * Inlines CSS into the email to make it compatible with more clients.
	 *
	 * @param string $content The email content.
	 * @return string
	 */
	public function inline_css( $content ) {

		// Maybe abort early;
		if ( ! class_exists( 'Pelago\Emogrifier\CssInliner' ) ) {
			return $content;
		}

		try {

			$emogrifier = Pelago\Emogrifier\CssInliner::fromHtml( $content );
			return $emogrifier->inlineCss()->render();

		} catch ( Exception $e ) {
			return $content;
		}

	}

	/**
	 * Process a custom email.
	 *
	 * @since 1.0.0
	 * @param string $email.
	 * @param Noptin_Email_Generator $generator
	 * @return string
	 */
	public function maybe_process_noptin_email( $email, $generator ) {

		if ( 'default' === $generator->template ) {
			self::$custom_footer_2 = $generator->footer_text;
			self::$preview_text    = $generator->preview_text;
			return $this->add_template( $email );
		}

		return $email;
	}

}
