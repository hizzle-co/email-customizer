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
	public function __construct( $args, $is_preview = false ) {

		if ( ! is_array( $args ) ) {
			$args = array();
		}

		if ( $is_preview && isset( $args['content'] ) ) {
			unset( $args['content'] );
		}

		$this->args = array_merge( $this->default_template(), $args );
		$this->args = apply_filters( 'email_customizer_template_args', $this->args );
		$this->args = $this->prepare_args( $this->args );

	}

	/**
	 * Prints a side by side comparison of the args.
	 *
	 */
	public function debug( $args ) {
		ksort( $args );
		ksort( $this->args );
		echo '<div style="display: flex">';
		noptin_dump( $args );
		noptin_dump( $this->args );
		echo '</div>';
	}

	/**
	 * Displays the top part.
	 *
	 */
	public function render_top() {

		extract( $this->args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		$path = apply_filters( 'email_customizer_template_path', plugin_dir_path( __FILE__ ) . 'template/' );
		include $path . 'header.php';
		include $path . 'heading.php';
		include $path . 'content-top.php';

	}

	/**
	 * Displays the bottom part.
	 *
	 */
	public function render_bottom() {

		extract( $this->args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		$path = apply_filters( 'email_customizer_template_path', plugin_dir_path( __FILE__ ) . 'template/' );
		include $path . 'content-bottom.php';
		include $path . 'bottom.php';
		include $path . 'footer.php';
	}

	/**
	 * Displays the template.
	 *
	 */
	public function render() {

		extract( $this->args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		$path = apply_filters( 'email_customizer_template_path', plugin_dir_path( __FILE__ ) . 'template/' );
		include $path . 'header.php';
		include $path . 'heading.php';
		include $path . 'content.php';
		include $path . 'bottom.php';
		include $path . 'footer.php';

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

	/**
	 * Prepares the template args.
	 *
	 * @return array $args An array of args to prepare.
	 */
	public static function prepare_args( $args ) {

		foreach ( array( 'footer_2', 'footer_1', 'header_2', 'header_1', 'before_content' ) as $key ) {
			$value        = wp_kses_post( self::parse_tags( $args[ $key ] ) );
			$value        = empty( $value ) ? '&nbsp;' : $value;
			$args[ $key ] = $value;
		}

		return $args;
	}

	/**
	 * parses the tags in a heading.
	 *
	 * @return string $string The string to parse.
	 */
	public static function parse_tags( $string ) {

		// Prepare merge tags.
		$blog_url = ( 'page' === get_option( 'show_on_front' ) && did_action( 'init' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : get_home_url();
		$tags     = array(
			'{{BLOG_URL}}'         => esc_url_raw( $blog_url ),
			'{{HOME_URL}}'         => esc_url_raw( get_home_url() ),
			'{{ADMIN_EMAIL}}'      => sanitize_email( get_bloginfo( 'admin_email', 'display' ) ),
			'{{BLOG_NAME}}'        => get_bloginfo( 'name', 'display' ),
			'{{BLOG_DESCRIPTION}}' => get_bloginfo( 'description', 'display' ),
			'{{DATE}}'             => date_i18n( get_option( 'date_format' ) ),
			'{{TIME}}'             => date_i18n( get_option( 'time_format' ) ),
			'{{YEAR}}'             => date_i18n( 'Y' ),
			'{{MONTH}}'            => date_i18n( 'F' ),
			'{{DAY}}'              => date_i18n( 'F' ),
		);

		foreach ( $tags as $tag => $value ) {
			$string = str_ireplace( $tag, $value, $string );
		}

		return $string;
	}

	/**
	 * Returns the default template.
	 *
	 * @return array $args Template args.
	 */
	public function default_template() {

		return array(
			'footer_2'           => Email_Customizer_Defaults::footer_2(),
			'footer_1'           => Email_Customizer_Defaults::footer_1(),
			'header_2'           => Email_Customizer_Defaults::header_2(),
			'header_1'           => Email_Customizer_Defaults::header_1(),
			'container_width'    => Email_Customizer_Defaults::container_width(),
			'header_left_width'  => Email_Customizer_Defaults::header_left_width(),
			'spacing'            => Email_Customizer_Defaults::row_spacing(),
			'bg_color'           => Email_Customizer_Defaults::bg_color(),
			'bg_image'           => Email_Customizer_Defaults::bg_image(),
			'logo'               => Email_Customizer_Defaults::logo(),
			'header_font_size'   => Email_Customizer_Defaults::header_font_size(),
			'header_bg'          => Email_Customizer_Defaults::header_bg(),
			'header_text_color'  => Email_Customizer_Defaults::header_text_color(),
			'header_link_color'  => Email_Customizer_Defaults::header_link_color(),
			'before_content'     => '',
			'content_font_size'  => Email_Customizer_Defaults::content_font_size(),
			'content_bg'         => Email_Customizer_Defaults::content_bg(),
			'content_text_color' => Email_Customizer_Defaults::content_text_color(),
			'content_link_color' => Email_Customizer_Defaults::content_link_color(),
			'footer_font_size'   => Email_Customizer_Defaults::footer_font_size(),
			'footer_bg'          => Email_Customizer_Defaults::footer_bg(),
			'footer_text_color'  => Email_Customizer_Defaults::footer_text_color(),
			'footer_link_color'  => Email_Customizer_Defaults::footer_link_color(),
			'custom_css'         => Email_Customizer_Defaults::additional_css(),
			'content'            => Email_Customizer_Defaults::default_content(),
			'preview_text'       => '',
		);

	}

	/**
	 * Returns the flat template.
	 *
	 * @return array $args Template args.
	 */
	public function flat_template() {

		return array(
			'footer_2'           => '',
			'footer_1'           => Email_Customizer_Defaults::flat_footer_1(),
			'header_2'           => Email_Customizer_Defaults::header_2(),
			'header_1'           => Email_Customizer_Defaults::header_1(),
			'container_width'    => Email_Customizer_Defaults::container_width(),
			'header_left_width'  => Email_Customizer_Defaults::header_left_width(),
			'spacing'            => '0px',
			'bg_color'           => '#ffffff',
			'bg_image'           => '',
			'logo'               => Email_Customizer_Defaults::logo(),
			'header_font_size'   => Email_Customizer_Defaults::header_font_size(),
			'header_bg'          => Email_Customizer_Defaults::header_bg(),
			'header_text_color'  => Email_Customizer_Defaults::header_text_color(),
			'header_link_color'  => Email_Customizer_Defaults::header_link_color(),
			'before_content'     => '',
			'content_font_size'  => Email_Customizer_Defaults::content_font_size(),
			'content_bg'         => Email_Customizer_Defaults::content_bg(),
			'content_text_color' => Email_Customizer_Defaults::content_text_color(),
			'content_link_color' => Email_Customizer_Defaults::content_link_color(),
			'footer_font_size'   => Email_Customizer_Defaults::footer_font_size(),
			'footer_bg'          => Email_Customizer_Defaults::footer_bg(),
			'footer_text_color'  => Email_Customizer_Defaults::footer_text_color(),
			'footer_link_color'  => Email_Customizer_Defaults::footer_link_color(),
			'custom_css'         => Email_Customizer_Defaults::flat_template_additional_css(),
			'content'            => Email_Customizer_Defaults::default_content(),
			'preview_text'       => '',
		);

	}

	/**
	 * Returns the dark template.
	 *
	 * @return array $args Template args.
	 */
	public function dark_template() {

		return array(
			'footer_2'           => Email_Customizer_Defaults::footer_2(),
			'footer_1'           => Email_Customizer_Defaults::footer_1(),
			'header_2'           => str_ireplace( 'black', 'white', Email_Customizer_Defaults::header_2() ),
			'header_1'           => Email_Customizer_Defaults::header_1(),
			'container_width'    => Email_Customizer_Defaults::container_width(),
			'header_left_width'  => Email_Customizer_Defaults::header_left_width(),
			'spacing'            => Email_Customizer_Defaults::row_spacing(),
			'bg_color'           => '#f5f5f5',
			'bg_image'           => '',
			'logo'               => '',
			'header_font_size'   => Email_Customizer_Defaults::header_font_size(),
			'header_bg'          => '#222222',
			'header_text_color'  => '#ffffff',
			'header_link_color'  => '#aaaaaa',
			'before_content'     => '',
			'content_font_size'  => Email_Customizer_Defaults::content_font_size(),
			'content_bg'         => '#222222',
			'content_text_color' => '#ffffff',
			'content_link_color' => '#aaaaaa',
			'footer_font_size'   => Email_Customizer_Defaults::footer_font_size(),
			'footer_bg'          => '#222222',
			'footer_text_color'  => '#ffffff',
			'footer_link_color'  => '#aaaaaa',
			'custom_css'         => Email_Customizer_Defaults::additional_css(),
			'content'            => Email_Customizer_Defaults::default_content(),
			'preview_text'       => '',
		);

	}

	/**
	 * Returns the hero image template.
	 *
	 * @return array $args Template args.
	 */
	public function hero_image_template() {

		return array(
			'footer_2'           => Email_Customizer_Defaults::footer_2(),
			'footer_1'           => Email_Customizer_Defaults::footer_1(),
			'header_2'           => Email_Customizer_Defaults::header_2(),
			'header_1'           => Email_Customizer_Defaults::header_1(),
			'container_width'    => Email_Customizer_Defaults::container_width(),
			'header_left_width'  => Email_Customizer_Defaults::header_left_width(),
			'spacing'            => '0px',
			'bg_color'           => Email_Customizer_Defaults::bg_color(),
			'bg_image'           => Email_Customizer_Defaults::bg_image(),
			'logo'               => Email_Customizer_Defaults::logo(),
			'header_font_size'   => Email_Customizer_Defaults::header_font_size(),
			'header_bg'          => Email_Customizer_Defaults::header_bg(),
			'header_text_color'  => Email_Customizer_Defaults::header_text_color(),
			'header_link_color'  => Email_Customizer_Defaults::header_link_color(),
			'before_content'     => Email_Customizer_Defaults::before_content(),
			'content_font_size'  => Email_Customizer_Defaults::content_font_size(),
			'content_bg'         => Email_Customizer_Defaults::content_bg(),
			'content_text_color' => Email_Customizer_Defaults::content_text_color(),
			'content_link_color' => Email_Customizer_Defaults::content_link_color(),
			'footer_font_size'   => Email_Customizer_Defaults::footer_font_size(),
			'footer_bg'          => Email_Customizer_Defaults::footer_bg(),
			'footer_text_color'  => Email_Customizer_Defaults::footer_text_color(),
			'footer_link_color'  => Email_Customizer_Defaults::footer_link_color(),
			'custom_css'         => Email_Customizer_Defaults::additional_css(),
			'content'            => Email_Customizer_Defaults::default_content(),
			'preview_text'       => '',
		);

	}

	/**
	 * Returns the simple template.
	 *
	 * @return array $args Template args.
	 */
	public function simple_template() {

		return array(
			'footer_2'           => Email_Customizer_Defaults::footer_2(),
			'footer_1'           => Email_Customizer_Defaults::footer_1(),
			'header_2'           => str_ireplace( 'black', 'white', Email_Customizer_Defaults::header_2() ),
			'header_1'           => Email_Customizer_Defaults::header_1(),
			'container_width'    => Email_Customizer_Defaults::container_width(),
			'header_left_width'  => Email_Customizer_Defaults::header_left_width(),
			'spacing'            => '0px',
			'bg_color'           => Email_Customizer_Defaults::bg_color(),
			'bg_image'           => Email_Customizer_Defaults::bg_image(),
			'logo'               => Email_Customizer_Defaults::logo(),
			'header_font_size'   => Email_Customizer_Defaults::header_font_size(),
			'header_bg'          => '#8224e3',
			'header_text_color'  => '#ffffff',
			'header_link_color'  => '#d6d6d6',
			'before_content'     => '',
			'content_font_size'  => Email_Customizer_Defaults::content_font_size(),
			'content_bg'         => Email_Customizer_Defaults::content_bg(),
			'content_text_color' => Email_Customizer_Defaults::content_text_color(),
			'content_link_color' => Email_Customizer_Defaults::content_link_color(),
			'footer_font_size'   => Email_Customizer_Defaults::footer_font_size(),
			'footer_bg'          => Email_Customizer_Defaults::footer_bg(),
			'footer_text_color'  => Email_Customizer_Defaults::footer_text_color(),
			'footer_link_color'  => Email_Customizer_Defaults::footer_link_color(),
			'custom_css'         => Email_Customizer_Defaults::simple_template_additional_css(),
			'content'            => Email_Customizer_Defaults::default_content(),
			'preview_text'       => '',
		);

	}

}
