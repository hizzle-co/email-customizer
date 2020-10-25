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
	 * @var bool  $forced_html Whether or not this was previously a text email.
	 */
	protected $forced_html = false;

	/**
	 * Class constructor.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {

		add_filter( 'wp_mail', array( $this, 'maybe_wrap_email' ), 100 );
		add_action( 'wp_mail_content_type', array( $this, 'maybe_force_html' ), 100 );
		add_action( 'email_customizer_email_content', array( $this, 'maybe_remove_body' ), 5 );
		add_action( 'email_customizer_email_content', array( $this, 'maybe_convert_to_html' ), 8 );

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
		preg_match( "/<body[^>]*>(.*?)<\/body>/is", $maybe_html_content, $matches );

		if ( isset( $matches[1] ) ) {
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

		if ( $this->forced_html ) {

			$text_content      = wp_kses_post( wpautop( $text_content ) );
			$this->forced_html = false;

		}

		return $text_content;

	}

	/**
	 * Force all emails to use HTML.
	 *
	 * @since 1.0.0
	 * @param string $type The email type.
	 * @return string
	 */
	public function maybe_force_html( $type ) {

		if ( apply_filters( 'email_customizer_disable_template_wrap', false ) ) {
			return $type;
		}

		if ( $type != 'text/html' ) {
			$this->forced_html = true;
		}

		return 'text/html';

	}

	/**
	 * Wrap emails with our template.
	 *
	 * @since 1.0.0
	 * @param array $args The email args.
	 * @return string
	 */
	public function maybe_wrap_email( $args ) {

		if ( apply_filters( 'email_customizer_disable_template_wrap', false ) ) {
			return $args;
		}

		do_action( 'email_customizer_before_wrap_email', $args, $this );
		$args['message'] = $this->add_template( $args['message'] );
		do_action( 'email_customizer_after_wrap_email', $args, $this );

		return $args;
	}

	/**
	 * Wraps a given content with our template.
	 *
	 * @param $email_content string The email content.
	 * @since 1.0.0
	 * @return string
	 */
	protected function add_template( $email_content ) {

		$email_content   = apply_filters( 'email_customizer_email_content', $email_content );
		$args            = get_option( 'email_customizer', array() );
		$args            = is_array( $args ) ? $args : array();
		$args['content'] = $email_content;
		$template        = new Email_Customizer_Template( $args );

		ob_start();
		$template->render();
		return $this->inline_css( ob_get_clean() );

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

}
