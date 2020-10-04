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
		include plugin_dir_path( __FILE__ ) . 'email-template.php';
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
				'title'         => __( 'Settings', 'email-customizer' ),
				'description'   => __( 'Use this panel to set the general appearance of your emails.', 'email-customizer' ),
			)
		);

		// Add options to it.
		$wp_customize->add_setting( 'email_customizer[template]', array(
			'type'                  => 'option',
			'default'               => 'boxed',
			'transport'             => 'refresh',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_template', array(
				'label'         => __( 'Choose one', 'email-templates' ),
				'type'          => 'select',
				'section'       => 'email_customizer_general',
				'settings'      => 'email_customizer[template]',
				'choices'       => apply_filters( 'mailtpl/template_choices', array(
					'boxed'    => 'Boxed',
					'fullwidth' => 'Fullwidth'
				)),
				'description'   => ''
			)
		) );

	}

}
