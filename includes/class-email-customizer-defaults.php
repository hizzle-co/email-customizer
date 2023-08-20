<?php
/**
 * Contains the default template values.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Contains the default template values.
 *
 */
class Email_Customizer_Defaults {

	/**
	 * Second footer text;
	 */
	public static function footer_2() {

		return sprintf(
			/* translators: %1: opening link tag, %2 closing link tag */
			__( 'Powered by the %1$sEmail Customizer%2$s WordPress plugin', 'email-customizer' ),
			'<a href="https://hizzle.co/">',
			'</a>'
		);

	}

	/**
	 * First Footer Text;
	 */
	public static function footer_1() {

		return '&copy;{{YEAR}} {{BLOG_NAME}}';

	}

	/**
	 * First Footer Text on the flat template;
	 */
	public static function flat_footer_1() {

		return sprintf(
			/* translators: %1: blog name, %2 current date */
			__( 'This email was sent from %1$s on %2$s', 'email-customizer' ),
			'<a href="{{BLOG_URL}}">{{BLOG_NAME}}</a>',
			'{{DATE}}'
		);

	}

	/**
	 * Second header text;
	 */
	public static function header_2() {

		$urls = array(
			'https://twitter.com'   => esc_url_raw( plugin_dir_url( __FILE__ ) . 'assets/twitter-black.png' ),
			'https://facebook.com'  => esc_url_raw( plugin_dir_url( __FILE__ ) . 'assets/facebook-black.png' ),
			'https://instagram.com' => esc_url_raw( plugin_dir_url( __FILE__ ) . 'assets/instagram-black.png' ),
		);

		$markup = array();

		foreach ( $urls as $url => $image ) {
			$markup[] = "<a href='$url'><img src='$image' width='16' alt=''></a>";
		}

		return implode( '&nbsp;', $markup );

	}

	/**
	 * First Header Text;
	 */
	public static function header_1() {
		return '{{BLOG_NAME}}';
	}

	/**
	 * Container Width;
	 */
	public static function container_width() {
		return '600px';
	}

	/**
	 * Header Left Width;
	 */
	public static function header_left_width() {
		return '400px';
	}

	/**
	 * Row Spacing;
	 */
	public static function row_spacing() {
		return '20px';
	}

	/**
	 * Background image.
	 */
	public static function bg_image() {
		return '';
	}

	/**
	 * Background color.
	 */
	public static function bg_color() {
		return '#f5f5f5';
	}

	/**
	 * Logo.
	 */
	public static function logo() {

		$custom_logo_id = get_theme_mod( 'custom_logo' );

		// We have a logo. Logo is go.
		if ( $custom_logo_id ) {
			return wp_get_attachment_image_src( $custom_logo_id, 'full', false );
		}

		return '';
	}

	/**
	 * Header font size.
	 */
	public static function header_font_size() {
		return '20px';
	}

	/**
	 * Header bg color.
	 */
	public static function header_bg() {
		return '#ffffff';
	}

	/**
	 * Header color.
	 */
	public static function header_text_color() {
		return '#212121';
	}

	/**
	 * Header link color.
	 */
	public static function header_link_color() {
		return '#0073aa';
	}

	/**
	 * Content font size.
	 */
	public static function content_font_size() {
		return '16px';
	}

	/**
	 * Content bg color.
	 */
	public static function content_bg() {
		return '#ffffff';
	}

	/**
	 * Content color.
	 */
	public static function content_text_color() {
		return '#212121';
	}

	/**
	 * Content link color.
	 */
	public static function content_link_color() {
		return '#0073aa';
	}

	/**
	 * Footer font size.
	 */
	public static function footer_font_size() {
		return '13px';
	}

	/**
	 * Footer bg color.
	 */
	public static function footer_bg() {
		return '#ffffff';
	}

	/**
	 * Footer color.
	 */
	public static function footer_text_color() {
		return '#aaaaaa';
	}

	/**
	 * Footer link color.
	 */
	public static function footer_link_color() {
		return '#a1a1a1';
	}

	/**
	 * Additional CSS
	 */
	public static function additional_css() {
		return '.components__inner {
	border-radius: 0px;
	text-align: left
}

.hero-section p,
.components__footer .components__inner,
.components__header .components__inner,
.content {
	padding: 30px 35px;
}

.hero-section p {
	padding: 5px 35px;
	margin: 0;
}

.components__inner .footer-1,
.components__inner .footer-2 {
	text-align: center;
	font-weight: 400;
}
';
	}

	/**
	 * Additional CSS
	 */
	public static function simple_template_additional_css() {
		return '.components__inner {
	border-radius: 0px;
	text-align: left
}

.hero-section p,
.components__footer .components__inner,
.content {
	padding: 10px 35px;
}

.components__header .components__inner {
	padding: 30px 35px;
}

.hero-section p {
	padding: 5px 35px;
	margin: 0;
}

.components__inner .footer-1,
.components__inner .footer-2 {
	text-align: center;
	font-weight: 400;
}

';
	}

	/**
	 * Additional CSS for the flat template.
	 */
	public static function flat_template_additional_css() {
		return '.components__inner {
	border-radius: 0px;
	text-align: left
}

.hero-section p,
.components__footer .components__inner,
.components__header .components__inner,
.content {
	padding: 30px 35px;
}

.hero-section p {
	padding: 5px 35px;
	margin: 0;
}

.components__inner .footer-1,
.components__inner .footer-2 {
	text-align: left;
	font-weight: 400;
}

.email-body .template__container{
	margin: 0;
}
';
	}

	/**
	 * Default content.
	 */
	public static function before_content() {
		$url     = esc_url_raw( plugin_dir_url( __FILE__ ) . 'assets/zuzzana.jpg' );
		$alt     = esc_attr__( 'white hippie glasses neon light decor on door', 'email-customizer' );
		$image   = "<img src='$url' alt='$alt' class='hero-image'>";
		$credits = sprintf(
			/* translators: %1: photographer name, %2 stock photography site */
			__( 'Photo by %1$s on %2$s', 'email-customizer' ),
			'Zuzanna Adamczyk',
			'Unsplash'
		);

		return "$image <p><i>$credits</i></p>";
	}

	/**
	 * Default content.
	 */
	public static function default_content() {

		$content  = '<p>' . __( 'All plain emails that WordPress sends from your website will use this template. The email content will appear here', 'email-customizer' ) . '</p>';
		$content .= '<p>' . __( 'You can use any of these placeholders in the header and footer texts and they will be replaced by the actual values.', 'email-customizer' ) . '</p>';
		$content .= '<ul>
			<li>{{BLOG_URL}}</li>
			<li>{{HOME_URL}}</li>
			<li>{{BLOG_NAME}}</li>
			<li>{{BLOG_DESCRIPTION}}</li>
			<li>{{DATE}}</li>
			<li>{{TIME}}</li>
			<li>{{YEAR}}</li>
			<li>{{MONTH}}</li>
			<li>{{DAY}}</li>
		</ul>';

		$content .= '<a href="https://github.com/hizzle-co/email-customizer/issues/new/choose">' . __( 'You are welcome to report bugs and request features via GitHub.', 'email-customizer' ) . '</a>';
		return $content;
	}

}
