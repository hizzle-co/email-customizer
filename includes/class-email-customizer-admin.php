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
		add_action( 'admin_init', array( $this, 'maybe_switch_template' ) );

		if ( isset( $_GET[ $this->id ] ) || $this->is_autosaving() ) {
			add_action( 'customize_register', array( $this, 'add_general_panel' ) );
			add_action( 'customize_register', array( $this, 'add_header_panel' ) );
			add_action( 'customize_register', array( $this, 'add_body_panel' ) );
			add_action( 'customize_register', array( $this, 'add_footer_panel' ) );
			add_action( 'customize_register', array( $this, 'add_css_panel' ) );
			add_action( 'customize_register', array( $this, 'register_partials' ) );
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'render_templates' ) );
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
			$this->get_customizer_url(),
			null
		);

	}

	/**
	 * Handles $_GET requests to switch templates.
	 */
	public function maybe_switch_template() {

		if ( current_user_can( $this->get_capability() ) && isset( $_GET['email-customizer-switch-template'] ) ) {
			$this->switch_template( sanitize_text_field( $_GET['email-customizer-switch-template'] ) );
		}

	}

	/**
	 * Switches templates.
	 */
	public function switch_template( $template_name ) {
		$template = new Email_Customizer_Template( array() );
		$method   = sanitize_key( $template_name ) . '_template';

		if ( is_callable( array( $template, $method ) ) ) {
			update_option( 'email_customizer', $template->$method() );
			wp_redirect( $this->get_customizer_url() );
			exit;
		}
 
	}

	/**
	 * Loads the email template class.
	 */
	public function load_email_template() {
		$template = new Email_Customizer_Template( get_option( 'email_customizer', array() ) );
		$template->render();
	}

	/**
	 * Displays our page on the frontend.
	 *
	 */
	public function display_frontend() {
		$this->load_email_template();
	}

	/**
	 * Register custom customizer scripts.
	 *
	 * @since 1.0.0
	 */
	public function customizer_preview_scripts() {
		$version = filemtime( plugin_dir_path( __FILE__ ) . 'assets/customizer-preview.js' );
		wp_enqueue_script( 'email_customizer_customize', plugin_dir_url( __FILE__ ) . 'assets/customizer-preview.js', array( 'jquery' ), $version, true );
	}

	/**
	 * Register custom customizer scripts.
	 *
	 * @since 1.0.0
	 */
	public function customizer_controls_scripts() {
		$version = filemtime( plugin_dir_path( __FILE__ ) . 'assets/customizer-controls.css' );
		wp_enqueue_style( 'email_customizer_customize', plugin_dir_url( __FILE__ ) . 'assets/customizer-controls.css', array(), $version );

		$version = filemtime( plugin_dir_path( __FILE__ ) . 'assets/customizer-controls.js' );
		wp_enqueue_script( 'email_customizer_customize_controls', plugin_dir_url( __FILE__ ) . 'assets/customizer-controls.js', array( 'jquery' ), $version, true );

		wp_localize_script(
			'email_customizer_customize_controls',
			'email_customizer_i10n',
			array(
				'changeTheme' => __( 'Change Template', 'email-customizer' ),
				'close'       => __( 'Close', 'email-customizer' ),
				'switcherURL' => add_query_arg( 'email-customizer-switch-template', '%template%', admin_url() ),
			)
		);

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
	 * Helper function to add a text input control.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance
	 * @param string $setting_id The setting id
	 * @param string $label The control label
	 * @param string $section The section id
	 * @param string $default_value The default value
	 *
	 */
	public function add_text( $wp_customize, $setting_id, $label, $section, $default_value = '' ) {

		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'                  => 'option',
				'default'               => $default_value,
				'transport'             => 'postMessage',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => 'sanitize_text_field',
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
					'type'          => 'text',
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
				'transport'             => 'postMessage',
				'capability'            => 'edit_theme_options',
				'sanitize_callback'     => array( $this, 'sanitize_image' ),
				'sanitize_js_callback'  => '',
			)
		);
		
		$this->add_control(
			$wp_customize,
			new WP_Customize_Image_Control(
				$wp_customize,
				sanitize_key( $setting_id ),
				array(
					'label'       => $label,
					'section'     => $section,
					'settings'    => $setting_id,
					'mime_type'   => 'image',
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
				'default'               => Email_Customizer_Defaults::additional_css(),
				'transport'             => 'postMessage',
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

		// Container Width.
		$this->add_text(
			$wp_customize,
			'email_customizer[container_width]',
			__( 'Container Width', 'email-templates' ),
			'email_customizer_general',
			Email_Customizer_Defaults::container_width()
		);

		// Container Width.
		$this->add_text(
			$wp_customize,
			'email_customizer[header_left_width]',
			__( 'Left Header Width', 'email-templates' ),
			'email_customizer_general',
			Email_Customizer_Defaults::header_left_width()
		);

		// Spacing Height.
		$this->add_text(
			$wp_customize,
			'email_customizer[spacing]',
			__( 'Section Spacing', 'email-templates' ),
			'email_customizer_general',
			Email_Customizer_Defaults::row_spacing()
		);

		// Background Image.
		$this->add_image(
			$wp_customize,
			'email_customizer[bg_image]',
			__( 'Background Image', 'email-customizer' ),
			'email_customizer_general',
			Email_Customizer_Defaults::bg_image()
		);

		// Background Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[bg_color]',
			__( 'Background Color', 'email-customizer' ),
			'email_customizer_general',
			Email_Customizer_Defaults::bg_color()
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
			Email_Customizer_Defaults::logo()
		);

		// Header text.  - Text on the left.
		$this->add_code(
			$wp_customize,
			'email_customizer[header_1]',
			__( 'Header Text 1', 'email-templates' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_1()
		);

		// Header text.  - Text on the right.
		$this->add_code(
			$wp_customize,
			'email_customizer[header_2]',
			__( 'Header Text 2', 'email-templates' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_2()
		);

		// Text Size.
		$this->add_text(
			$wp_customize,
			'email_customizer[header_font_size]',
			__( 'Font Size', 'email-templates' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_font_size()
		);

		// Background Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[header_bg]',
			__( 'Background Color', 'email-customizer' ),
			'email_customizer_header',
			Email_Customizer_Defaults::header_bg()
		);

		// Text Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[header_text_color]',
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

		// Header text.
		$this->add_code(
			$wp_customize,
			'email_customizer[before_content]',
			__( 'Before Content', 'email-templates' ),
			'email_customizer_content'
		);

		// Text Size.
		$this->add_text(
			$wp_customize,
			'email_customizer[content_font_size]',
			__( 'Font Size', 'email-templates' ),
			'email_customizer_content',
			Email_Customizer_Defaults::content_font_size()
		);

		// Background Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[content_bg]',
			__( 'Background Color', 'email-customizer' ),
			'email_customizer_content',
			Email_Customizer_Defaults::content_bg()
		);

		// Text Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[content_text_color]',
			__( 'Text Color', 'email-customizer' ),
			'email_customizer_content',
			Email_Customizer_Defaults::content_text_color()
		);

		// Link Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[content_link_color]',
			__( 'Link Color', 'email-customizer' ),
			'email_customizer_content',
			Email_Customizer_Defaults::content_link_color()
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
			'email_customizer[footer_1]',
			__( 'Footer Text 1', 'email-templates' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_1()
		);

		// Footer text.  - Text on the right.
		$this->add_code(
			$wp_customize,
			'email_customizer[footer_2]',
			__( 'Footer Text 2', 'email-templates' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_2()
		);

		// Text Size.
		$this->add_text(
			$wp_customize,
			'email_customizer[footer_font_size]',
			__( 'Font Size', 'email-templates' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_font_size()
		);

		// Background Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[footer_bg]',
			__( 'Background Color', 'email-customizer' ),
			'email_customizer_footer',
			Email_Customizer_Defaults::footer_bg()
		);

		// Text Color.
		$this->add_color(
			$wp_customize,
			'email_customizer[footer_text_color]',
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

	/**
	 * Renders a refresh partial.
	 *
	 * @param WP_Customize_Partial $partial
	 */
	public function render_partial( $partial ) {

		$customized = json_decode( wp_unslash( $_POST['customized'] ), true );
		
		if ( empty( $customized ) || ! isset( $customized[ $partial->id ] ) ) {
			return "&nbsp;";
		}

		$value = Email_Customizer_Template::parse_tags( $customized[ $partial->id ] );
		return empty( $value ) ? "&nbsp;" : wp_kses_post( $value );
	}

	/**
	 * Registers refresh partials.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function register_partials( $wp_customize ) {

		// Left header text.
		$wp_customize->selective_refresh->add_partial(
			'email_customizer[header_1]',
			array(
				'selector'        => '.heading__left-title-text',
				'render_callback' => array( $this, 'render_partial' ),
			)
		);

		// Right header text.
		$wp_customize->selective_refresh->add_partial(
			'email_customizer[header_2]',
			array(
				'selector'        => '.heading__right-title',
				'render_callback' => array( $this, 'render_partial' ),
			)
		);

		// Before content text.
		$wp_customize->selective_refresh->add_partial(
			'email_customizer[before_content]',
			array(
				'selector'        => '.hero-section',
				'render_callback' => array( $this, 'render_partial' ),
			)
		);

		// Footer 1 text.
		$wp_customize->selective_refresh->add_partial(
			'email_customizer[footer_1]',
			array(
				'selector'        => '.footer-1',
				'render_callback' => array( $this, 'render_partial' ),
			)
		);

		// Footer 2 text.
		$wp_customize->selective_refresh->add_partial(
			'email_customizer[footer_2]',
			array(
				'selector'        => '.footer-2',
				'render_callback' => array( $this, 'render_partial' ),
			)
		);

	}

	/**
	 * Returns template options.
	 *
	 * @return array An array of options.
	 */
	public function get_options() {
		$options = get_option( 'email_customizer' );
		return is_array( $options ) ? $options : array();
	}

	/**
	 * Returns a single template option.
	 *
	 * @return string The option value.
	 */
	public function get_option( $option, $default = '' ) {
		$options = $this->get_options( 'email_customizer' );
		return isset( $options[$option] ) ? $options[$option] : $default;
	}

	/**
	 * Checks if we're autosaving our instance.
	 *
	 */
	public function is_autosaving() {

		if ( empty( $_POST['customize_changeset_data'] ) && empty( $_POST['customized'] ) ) {
			return false;
		}

		if ( ! empty( $_POST['customize_changeset_data'] ) ) {
			return strpos( $_POST['customize_changeset_data'], 'email_customizer' ) !== false;
		}

		return strpos( $_POST['customized'], 'email_customizer' ) !== false;

	}

	/**
	 * Displays control templates.
	 *
	 */
	public function render_templates() {
		require_once plugin_dir_path( __FILE__ ) . 'template/customizer-controls.php';
	}

}

// Test Emails.
