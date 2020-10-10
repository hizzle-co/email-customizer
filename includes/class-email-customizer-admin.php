<?php
/**
 * Main admin class.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Contains the main admin class.
 *
 */
class Email_Customizer_Admin extends Email_Customizer_Presstomizer {

	/**
	 * Class constructor.
	 *
	 * @param string $id An alphanumeric unique id for your specific instance.
	 */
	public function __construct( $id ) {

		parent::__construct( $id );
		$this->load_admin_hooks();

	}

	/**
	 * Loads admin hooks.
	 *
	 */
	public function load_admin_hooks() {

		add_action( 'admin_menu', array( $this, 'display_customizer_link' ) );

		if ( isset( $_GET[ $this->id ] ) ) {
			add_action( 'presstomizer_frontend_display_email_customizer_customize', array( $this, 'load_email_template' ) );
			add_action( 'customize_register', array( $this, 'add_general_panel' ) );
			add_action( 'customize_register', array( $this, 'add_header_panel' ) );
			add_action( 'customize_register', array( $this, 'add_body_panel' ) );
			add_action( 'customize_register', array( $this, 'add_footer_panel' ) );
			add_action( 'customize_register', array( $this, 'add_css_panel' ) );
		}

    }

	/**
	 * Add an admin link to the customizer.
	 */
	public function display_customizer_link() {

		add_submenu_page(
			'themes.php',
			__( 'Email Customizer', 'email-customizer' ),
			__( 'Email Customizer', 'email-customizer' ),
			$this->get_capability(),
			$this->get_customizer_url() ,
			null
		);

	}

	/**
	 * Loads the email template class.
	 */
	public function load_email_template() {
		$template = new Email_Customizer_Template( array() );
		$template->render();
	}

	/**
	 * Helper function to add a color control.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance
	 * @param string $setting_id The setting id
	 * @param string $label The control label
	 * @param string $section The section id
	 * @param string $default_value The default value
	 *
	 */
	public function add_color( $wp_customize, $setting_id, $label, $section, $default_value ) {

		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'                  => 'option',
				'default'               => $default_value,
				'transport'             => 'postMessage',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => 'sanitize_hex_color',
				'sanitize_js_callback'  => 'maybe_hash_hex_color',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Color_Control(
				$wp_customize,
				sanitize_key( $setting_id ),
				array(
					'label'      => $label,
					'section'    => $section,
					'settings'   => $setting_id,
				)
			)
		);
	}

	/**
	 * Helper function to add a code input control.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance
	 * @param string $setting_id The setting id
	 * @param string $label The control label
	 * @param string $section The section id
	 * @param string $default_value The default value
	 *
	 */
	public function add_code( $wp_customize, $setting_id, $label, $section, $default_value = '' ) {

		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'                  => 'option',
				'default'               => $default_value,
				'transport'             => 'postMessage',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => 'wp_kses_post',
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Control(
				$wp_customize,
				sanitize_key( $setting_id ),
				array(
					'label'         => $label,
					'type'          => 'textarea',
					'section'       => $section,
					'settings'      => $setting_id,
				)
			)
		);

	}

	/**
	 * Helper function to add an image control.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance
	 * @param string $setting_id The setting id
	 * @param string $label The control label
	 * @param string $section The section id
	 * @param string $default_value The default value
	 *
	 */
	public function add_image( $wp_customize, $setting_id, $label, $section, $default_value = '' ) {

		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'                  => 'option',
				'default'               => $default_value,
				'transport'             => 'refresh',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => array( $this, 'sanitize_image' ),
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Cropped_Image_Control(
				$wp_customize,
				sanitize_key( $setting_id ),
				array(
					'label'       => $label,
					'section'     => $section,
					'settings'    => $setting_id,
					'mime_type'   => 'image',
					'flex_width'  => true,
					'flex_height' => true,
					'height'      => 150,
					'width'       => 300,
				)
			)
		);

	}

	/**
	 * Sanitizes an image image
	 * Control: text, WP_Customize_Image_Control
	 *
	 */
	public function sanitize_image( $input, $setting ) {
		$image = $this->validate_image( $input, $setting->default );
		return empty( $image ) ? '' : esc_url_raw( $image );
	}

	/**
	 * Validation: image
	 * Control: text, WP_Customize_Image_Control
	 *
	 */
	public function validate_image( $input, $default = '' ) {

		// Valid mime types.
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tiff|tif'     => 'image/tiff',
			'ico'          => 'image/x-icon',
			'heic'         => 'image/heic',
		);

		// Return an array with file extension and mime_type.
		$file = wp_check_filetype( $input, $mimes );

		// If $input has a valid mime_type, return it;
		// otherwise, return the default.
		return ( $file['ext'] ? $input : $default );
	}

	/**
	 * Adds the "Custom CSS" panel to the customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function add_css_panel( $wp_customize ) {

		$section_description  = '<p>';
		$section_description .= __( 'Add your own CSS code here to customize the appearance and layout of your emails.', 'email-customizer' );
		$section_description .= sprintf(
			' <a href="%1$s" class="external-link" target="_blank">%2$s<span class="screen-reader-text"> %3$s</span></a>',
			esc_url( __( 'https://codex.wordpress.org/CSS' ) ),
			__( 'Learn more about CSS', 'email-customizer' ),
			/* translators: Accessibility text. */
			__( '(opens in a new tab)', 'email-customizer' )
		);
		$section_description .= '</p>';

		$section_description .= '<p id="editor-keyboard-trap-help-1">' . __( 'When using a keyboard to navigate:', 'email-customizer' ) . '</p>';
		$section_description .= '<ul>';
		$section_description .= '<li id="editor-keyboard-trap-help-2">' . __( 'In the editing area, the Tab key enters a tab character.', 'email-customizer' ) . '</li>';
		$section_description .= '<li id="editor-keyboard-trap-help-3">' . __( 'To move away from this area, press the Esc key followed by the Tab key.', 'email-customizer' ) . '</li>';
		$section_description .= '<li id="editor-keyboard-trap-help-4">' . __( 'Screen reader users: when in forms mode, you may need to press the Esc key twice.', 'email-customizer' ) . '</li>';
		$section_description .= '</ul>';
		$section_description .= '<p class="section-description-buttons">';
		$section_description .= '<button type="button" class="button-link section-description-close">' . __( 'Close', 'email-customizer' ) . '</button>';
		$section_description .= '</p>';

		$this->add_section(
			$wp_customize,
			'email_customizer_css',
			array(
				'title'              => __( 'Additional CSS', 'email-customizer' ),
				'description_hidden' => true,
				'description'        => $section_description,
			)
		);

		$wp_customize->add_setting(
			'email_customizer[custom_css]',
			array(
				'type'                  => 'option',
				'default'               => '',
				'transport'             => 'refresh',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => '',
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Code_Editor_Control(
				$wp_customize,
				'email_customizer_custom_css',
				array(
					'label'       => __( 'Custom CSS', 'email-templates' ),
					'section'     => 'email_customizer_css',
					'settings'    => 'email_customizer[custom_css]',
					'code_type'   => 'text/css',
					'input_attrs' => array(
						'aria-describedby' => 'editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4',
					),
				)
			)
		);
// #customize-control-email_customizer_custom_css .customize-control-code_editor textarea
// #customize-control-email_customizer_custom_css .customize-control-code_editor .CodeMirror
// height: 500px; :last-child .CodeMirror {
//    height: calc(100vh - 185px);
//}
	}

	/**
	 * Adds the "General" panel to the customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function add_general_panel( $wp_customize ) {

		// Register the panel.
		$this->add_section(
			$wp_customize,
			'email_customizer_general',
			array(
				'title'         => __( 'General', 'email-customizer' ),
			)
		);

		// Container Width
		$wp_customize->add_setting(
			'email_customizer[width]',
			array(
				'type'                  => 'option',
				'default'               => Email_Customizer_Defaults::container_width(),
				'transport'             => 'refresh',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => '',
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Control(
				$wp_customize,
				'email_customizer_width',
				array(
					'label'         => __( 'Container Width', 'email-templates' ),
					'type'          => 'text',
					'section'       => 'email_customizer_general',
					'settings'      => 'email_customizer[width]',
				)
			)
		);

		// Container Width.
		$wp_customize->add_setting(
			'email_customizer[header_left_width]',
			array(
				'type'                  => 'option',
				'default'               => Email_Customizer_Defaults::header_left_width(),
				'transport'             => 'refresh',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => '',
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Control(
				$wp_customize,
				'email_customizer_header_left_width',
				array(
					'label'         => __( 'Left Header Width', 'email-templates' ),
					'type'          => 'text', 
					'section'       => 'email_customizer_general',
					'settings'      => 'email_customizer[header_left_width]',
				)
			)
		);

		// Spacing Width
		$wp_customize->add_setting(
			'email_customizer[spacing]',
			array(
				'type'                  => 'option',
				'default'               => Email_Customizer_Defaults::row_spacing(),
				'transport'             => 'refresh',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => '',
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Control(
				$wp_customize,
				'email_customizer_spacing',
				array(
					'label'         => __( 'Section Spacing', 'email-templates' ),
					'type'          => 'text',
					'section'       => 'email_customizer_general',
					'settings'      => 'email_customizer[spacing]',
				)
			)
		);

		// Background Image.
		$this->add_image(
			$wp_customize,
			'email_customizer[bg_image]',
			__( 'Background Image', 'email-customizer' ),
			'email_customizer_general',
			Email_Customizer_Defaults::bg_image(),
		);

		// Background Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[bg_color]',
			__( 'Background Color', 'email-customizer' ),
			'email_customizer_general',
			Email_Customizer_Defaults::bg_color(),
		);

	}

	/**
	 * Adds the "Header" panel to the customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function add_header_panel( $wp_customize ) {

		// Register the panel.
		$this->add_section(
			$wp_customize,
			'email_customizer_header',
			array(
				'title'         => __( 'Header', 'email-customizer' ),
			)
		);

		// Logo.
		$this->add_image(
			$wp_customize,
			'email_customizer[logo]',
			__( 'Logo', 'email-customizer' ),
			'email_customizer_header',
			Email_Customizer_Defaults::logo(),
		);

		// Header text.  - Text on the left.
		$this->add_code(
			$wp_customize,
			'email_customizer[header_text_left]',
			__( 'Header Text 1', 'email-templates' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_1(),
		);

		$wp_customize->selective_refresh->add_partial(
			'email_customizer[header_text_left]',
			array(
				'selector'        => '.heading__left-title-div',
				'render_callback' => array( $this, 'left_title' ),
			)
		);

		// Header text.  - Text on the right.
		$this->add_code(
			$wp_customize,
			'email_customizer[header_text_right]',
			__( 'Header Text 2', 'email-templates' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_2(),
		);

		// Text Size.
		$wp_customize->add_setting(
			'email_customizer[header_size]',
			array(
				'type'                  => 'option',
				'default'               => Email_Customizer_Defaults::header_font_size(),
				'transport'             => 'refresh',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => '',
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Control(
				$wp_customize,
				'email_customizer_header_size',
				array(
					'label'         => __( 'Font Size', 'email-templates' ),
					'type'          => 'text',
					'section'       => 'email_customizer_header',
					'settings'      => 'email_customizer[header_size]',
				)
			)
		);

		// Background Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[header_bg_color]',
			__( 'Background Color', 'email-customizer' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_bg()
		);

		// Text Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[header_color]',
			__( 'Text Color', 'email-customizer' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_text_color()
		);

		// Link Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[header_link_color]',
			__( 'Link Color', 'email-customizer' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_link_color()
		);

	}

	/**
	 * Adds the "Content" panel to the customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function add_body_panel( $wp_customize ) {

		// Register the panel.
		$this->add_section(
			$wp_customize,
			'email_customizer_content',
			array(
				'title'         => __( 'Content', 'email-customizer' ),
			)
		);

		// Header text.  - Text on the right.
		$this->add_code(
			$wp_customize,
			'email_customizer[before_content]',
			__( 'Before Content', 'email-templates' ),
			'email_customizer_content',
			Email_Customizer_Defaults::before_content(),
		);

		// Text Size.
		$wp_customize->add_setting(
			'email_customizer[content_size]',
			array(
				'type'                  => 'option',
				'default'               => Email_Customizer_Defaults::content_font_size(),
				'transport'             => 'refresh',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => '',
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Control(
				$wp_customize,
				'email_customizer_content_size',
				array(
					'label'         => __( 'Font Size', 'email-templates' ),
					'type'          => 'text',
					'section'       => 'email_customizer_content',
					'settings'      => 'email_customizer[content_size]',
				)
			)
		);

		// Background Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[content_bg_color]',
			__( 'Background Color', 'email-customizer' ),
			'email_customizer_content',
			Email_Customizer_Defaults::content_bg(),
		);

		// Text Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[content_color]',
			__( 'Text Color', 'email-customizer' ),
			'email_customizer_content',
			Email_Customizer_Defaults::content_text_color(),
		);

		// Link Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[content_link_color]',
			__( 'Link Color', 'email-customizer' ),
			'email_customizer_content',
			Email_Customizer_Defaults::content_link_color(),
		);

	}

	/**
	 * Adds the "Footer" panel to the customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function add_footer_panel( $wp_customize ) {

		// Register the panel.
		$this->add_section(
			$wp_customize,
			'email_customizer_footer',
			array(
				'title'         => __( 'Footer', 'email-customizer' ),
			)
		);

		// Footer text.  - Text on the left.
		$this->add_code(
			$wp_customize,
			'email_customizer[footer_text_left]',
			__( 'Footer Text 1', 'email-templates' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_1(),
		);

		// Footer text.  - Text on the right.
		$this->add_code(
			$wp_customize,
			'email_customizer[footer_text_right]',
			__( 'Footer Text 2', 'email-templates' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_2(),
		);

		// Text Size.
		$wp_customize->add_setting(
			'email_customizer[footer_size]',
			array(
				'type'                  => 'option',
				'default'               => Email_Customizer_Defaults::footer_font_size(),
				'transport'             => 'refresh',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => '',
				'sanitize_js_callback'  => '',
			)
		);

		$this->add_control(
			$wp_customize,
			new WP_Customize_Control(
				$wp_customize,
				'email_customizer_footer_size',
				array(
					'label'         => __( 'Font Size', 'email-templates' ),
					'type'          => 'text',
					'section'       => 'email_customizer_footer',
					'settings'      => 'email_customizer[footer_size]',
				)
			)
		);

		// Background Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[footer_bg_color]',
			__( 'Background Color', 'email-customizer' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_bg()
		);

		// Text Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[footer_color]',
			__( 'Text Color', 'email-customizer' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_text_color()
		);

		// Link Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[footer_link_color]',
			__( 'Link Color', 'email-customizer' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_link_color()
		);

	}

}
