<?php
/**
 * Contains the class for creating custom customizer screens.
 */

/**
 * Provides an environment for creating custom customizer screens.
 *
 * @since 1.0.0
 */
class Email_Customizer_Presstomizer {

	/**
	 * Contains the unique id of this instance.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Contains a cache of all our panels.
	 *
	 * @var array
	 */
	protected $panels = array();

	/**
	 * Contains a cache of all our sections.
	 *
	 * @var array
	 */
	protected $sections = array();

	/**
	 * Contains a cache of all our sections.
	 *
	 * @var array
	 */
	protected $controls = array();

	/**
	 * Class constructor.
	 *
	 * @param string $id An alphanumeric unique id for your specific instance.
	 */
	public function __construct( $id ) {
		$this->id = sanitize_key( $id );
		add_action( 'init', array( $this, 'set_up_custom_customizer' ) );
	}

	/**
	 * Sets up our custom customizer.
	 *
	 */
	public function set_up_custom_customizer() {

		// Ensure this is our customizer request.
		if ( ! is_customize_preview() || ! isset( $_GET[ $this->id ] ) ) {
			return;
		}

		// Remove sections/panels/controls that are not ours.
		add_filter( 'customize_section_active', array( $this, 'remove_third_party_sections' ), 999999, 2 );
		add_filter( 'customize_panel_active', array( $this, 'remove_third_party_panels' ), 999999, 2 );
		add_filter( 'customize_control_active', array( $this, 'remove_third_party_controls' ), 999999, 2 );

		// Do not load core components.
		add_filter( 'customize_loaded_components', '__return_empty_array', 999999 );

		// Load our own template.
		add_action( 'template_redirect', array( $this, 'maybe_display_frontend' ) );

		// Scripts.
		add_action( "presstomizer_{$this->id}_footer", array( $this, 'print_footer' ) );

	}

	/**
	 * Remover other sections
	 *
	 * @param bool                 $is_active  Whether the Customizer section is active.
	 * @param WP_Customize_Section $section WP_Customize_Section instance.
	 *
	 * @return bool
	 */
	public function remove_third_party_sections( $is_active, $section ) {
		return $is_active && in_array( $section->id, $this->sections, true );
	}

	/**
	 * Remover other panels
	 *
	 * @param bool               $active Whether the Customizer panel is active.
	 * @param WP_Customize_Panel $panel  WP_Customize_Panel instance.
	 *
	 * @return bool
	 */
	public function remove_third_party_panels( $is_active, $panel ) {
		return $is_active && in_array( $panel->id, $this->panels, true );
	}

	/**
	 * Remover other controls
	 *
	 * @param bool                $active Whether the Customizer panel is active.
	 * @param WP_Customize_Control $control  WP_Customize_Control instance.
	 *
	 * @return bool
	 */
	public function remove_third_party_controls( $is_active, $control ) {
		return $is_active && in_array( $control->id, $this->controls, true );
	}

	/**
	 * Add a new customizer panel.
	 *
	 * Use this method to register a panel instead of directly calling WP_Customize_Manager::add_panel
	 * so that we can link the panel to your customizer instance.
	 *
	 * @param WP_Customize_Manager      $customizer An instance of the customize manager class.
	 * @param WP_Customize_Panel|string $id         Customize Panel object, or ID.
	 * @param array                     $args       Optional. Array of properties for the new Panel object.
	 * @see WP_Customize_Manager::add_panel
	 *
	 * @return WP_Customize_Panel
	 */
	public function add_panel( $customizer, $id, $args = array() ) {
		$this->panels[] = is_string( $id ) ? $id : $id->id;
		return $customizer->add_panel( $id, $args );
	}

	/**
	 * Add a new customizer section.
	 *
	 * Use this method to register a section instead of directly calling WP_Customize_Manager::add_section
	 * so that we can link the section to your customizer instance.
	 *
	 * @param WP_Customize_Manager        $customizer An instance of the customize manager class.
	 * @param WP_Customize_Section|string $id         Customize Section object, or ID.
	 * @param array                       $args       Optional. Array of properties for the new Section object.
	 * @see WP_Customize_Manager::add_section
	 *
	 * @return WP_Customize_Section
	 */
	public function add_section( $customizer, $id, $args = array() ) {
		$this->sections[] = is_string( $id ) ? $id : $id->id;
		return $customizer->add_section( $id, $args );
	}

	/**
	 * Add a new customizer control.
	 *
	 * Use this method to register a control instead of directly calling WP_Customize_Manager::add_control
	 * so that we can link the control to your customizer instance.
	 *
	 * @param WP_Customize_Manager        $customizer An instance of the customize manager class.
	 * @param WP_Customize_Control|string $id — Customize Control object, or ID.
	 * @param array                       $args       Optional. Array of properties for the new control object.
	 * @see WP_Customize_Manager::add_control
	 *
	 * @return WP_Customize_Control
	 */
	public function add_control( $customizer, $id, $args = array() ) {
		$this->controls[] = is_string( $id ) ? $id : $id->id;
		return $customizer->add_control( $id, $args );
	}

	/**
	 * Tries to display our frontend page.
	 *
	 * @return string
	 */
	public function maybe_display_frontend() {

		if ( is_customize_preview() ) {
			$this->display_frontend();
			exit;
		}

	}

	/**
	 * Displays our page on the frontend.
	 *
	 * Ensure you call `do_action( "presstomizer_{$this->id}_footer" )` if "is_customize_preview()" returns true.
	 */
	public function display_frontend() {
		do_action( "presstomizer_frontend_display_{$this->id}" );
	}

	/**
	 * Returns the URL to our customizer instance.
	 *
	 * @param string $action_prefix Optional action prefix.
	 */
	public function get_customizer_url( $action_prefix = 1 ) {

		$current_url   = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		return add_query_arg(
			array(
				'url'             => urlencode( $this->get_frontend_url( $action_prefix ) ),
				'return'          => urlencode( $current_url ),
				$this->id         => $action_prefix,
			),
			wp_customize_url()
		);

	}

	/**
	 * Returns the URL to our customizer frontend instance.
	 *
	 * @param string $action_prefix Optional action prefix.
	 */
	public function get_frontend_url( $action_prefix = 1 ) {
		return add_query_arg( $this->id, $action_prefix, home_url() );
	}

	/**
	 * Returns the capability that can access our custom customizer.
	 *
	 * @return string
	 */
	public function get_capability() {
		return apply_filters( "presstomizer_capability_for_{$this->id}", 'customize' );
	}

	/**
	 * If we are in our template strip everything out and leave it clean.
	 *
	 * @since 1.0.0
	 */
	public function print_footer() {
		$this->remove_scripts();
		wp_print_footer_scripts();
	}

	/**
	 * If we are in our template strip everything out and leave it clean.
	 *
	 * @since 1.0.0
	 */
	public function remove_scripts() {
		global $wp_scripts;

		$exceptions = apply_filters(
			"presstomizer_{$this->id}_allowed_scripts",
			array(
				'jquery',
				'customize-preview',
				'customize-controls',
			)
		);

		if ( is_object( $wp_scripts ) && isset( $wp_scripts->queue ) && is_array( $wp_scripts->queue ) ) {

			foreach ( $wp_scripts->queue as $handle ) {

				if ( ! in_array( $handle, $exceptions, true ) ) {
					wp_dequeue_script( $handle );
				}
				
			}

		}

	}

}
// TODO: Rename the "customize_controls_print_footer_scripts" && "customize_controls_enqueue_scripts" actions && "customize_controls_print_styles" and attach the code wp hooks.
