<?php
/**
 * Main template class.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders an email template.
 *
 */
class Email_Customizer_Template {

	/**
	 * @var array
	 */
	public $args = array();

	/**
	 * Class constructor.
	 * 
	 * @param array $args Template args.
	 */
	public function __construct( $args ) {

		if ( ! is_array( $args ) ) {
			$args =  array();
		}

		$defaults = array(
			'footer_2'           => Email_Customizer_Defaults::footer_2(),
			'footer_1'           => Email_Customizer_Defaults::footer_1(),
			'header_2'           => Email_Customizer_Defaults::header_2(),
			'header_1'           => Email_Customizer_Defaults::header_1(),
			'container_width'    => Email_Customizer_Defaults::container_width(),
			'header_left_width'  => Email_Customizer_Defaults::container_width(),
			'row_spacing'        => Email_Customizer_Defaults::row_spacing(),
			'bg_color'           => Email_Customizer_Defaults::bg_color(),
			'bg_image'           => Email_Customizer_Defaults::bg_image(),
			'logo'               => Email_Customizer_Defaults::logo(),
			'header_font_size'   => Email_Customizer_Defaults::header_font_size(),
			'header_bg'          => Email_Customizer_Defaults::header_bg(),
			'header_text_color'  => Email_Customizer_Defaults::header_text_color(),
			'header_link_color'  => Email_Customizer_Defaults::header_link_color(),
			'content_font_size'  => Email_Customizer_Defaults::content_font_size(),
			'content_bg'         => Email_Customizer_Defaults::content_bg(),
			'content_text_color' => Email_Customizer_Defaults::content_text_color(),
			'content_link_color' => Email_Customizer_Defaults::content_link_color(),
			'footer_font_size'   => Email_Customizer_Defaults::footer_font_size(),
			'footer_bg'          => Email_Customizer_Defaults::footer_bg(),
			'footer_text_color'  => Email_Customizer_Defaults::footer_text_color(),
			'footer_link_color'  => Email_Customizer_Defaults::footer_link_color(),
			'additional_css'     => Email_Customizer_Defaults::additional_css(),
			'content'            => Email_Customizer_Defaults::default_content(),
			'preview_text'       => '',
		);

		$this->args = wp_parse_args( $args, $defaults );
		$this->args = apply_filters( 'email_customizer_args', $this->args );
	}

	/**
	 * Displays the template.
	 *
	 */
	public function render() {

		extract( $this->args );

		$path = plugin_dir_path( __FILE__ ) . 'template/';
		require_once $path . 'header.php';
		require_once $path . 'heading.php';
		require_once $path . 'content.php';
		require_once $path . 'bottom.php';
		require_once $path . 'footer.php';

	}
	
	/**
	 * Retrieves the template's html
	 *
	 */
	public function get_html() {
		ob_start();
		$this->render();
		return ob_get_clean();
	}

}
