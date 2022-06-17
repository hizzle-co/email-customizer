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
		add_filter( 'astra_customizer_configurations', '__return_empty_array', 999999 );

		if ( class_exists( 'Astra_Customizer' ) ) {
			remove_action( 'customize_preview_init', array( Astra_Customizer::get_instance(), 'preview_init' ) );
			remove_filter( 'customize_controls_enqueue_scripts', array( Astra_Customizer::get_instance(), 'enqueue_customizer_scripts' ), 999 );
		}

		// Load our own template.
		add_action( 'template_redirect', array( $this, 'maybe_display_frontend' ) );

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_controls_scripts' ) );
		add_action( 'customize_preview_init', array( $this, 'customizer_preview_scripts' ) );

		add_action( 'wp_head', array( $this, 'remove_all_header_actions' ), -1000 );
		add_action( 'wp_head', array( $this, 'remove_external_scripts' ), 7 ); // wp_print_styles loaded at 8

		add_action( 'wp_footer', array( $this, 'remove_all_footer_actions' ), -1000 );
		add_action( 'wp_footer', array( $this, 'remove_external_scripts' ), 19 ); // wp_print_footer_scripts loaded at 20

		do_action( "presstomizer_{$this->id}_set_up_custom_customizer" );
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

		if ( is_customize_preview() && empty( $_POST['wp_customize_render_partials'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$this->display_frontend();
			exit;
		}

	}

	/**
	 * Displays our page on the frontend.
	 *
	 * Ensure you call `do_action( 'wp_head' )` && `do_action( 'wp_footer' )` if "is_customize_preview()" returns true.
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
				'url'     => rawurlencode( $this->get_frontend_url( $action_prefix ) ),
				'return'  => rawurlencode( $current_url ),
				$this->id => $action_prefix,
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
	 * Checks if a script is built into WP.
	 *
	 * @return bool
	 */
	public function is_built_in( $handle ) {
		return strpos( $handle, 'customize-' ) === 0 || strpos( $handle, $this->id ) === 0;
	}

	/**
	 * Returns an array of scripts to exclude from our customizer instance
	 *
	 * @return array
	 */
	public function get_allowed_scripts() {

		return apply_filters(
			"presstomizer_{$this->id}_allowed_scripts",
			array(
				'jquery-core',
				'jquery',
				'customize-preview',
				'customize-controls',
				'query-monitor',
				'dashicons-css',
				$this->id,
			)
		);

	}

	/**
	 * If we are in our template strip everything out and leave it clean.
	 *
	 * @since 1.0.0
	 */
	public function remove_external_scripts() {
		global $wp_scripts, $wp_styles;

		$exceptions = $this->get_allowed_scripts();

		if ( is_object( $wp_scripts ) && isset( $wp_scripts->queue ) && is_array( $wp_scripts->queue ) ) {

			foreach ( $wp_scripts->queue as $handle ) {
				$src = isset( $wp_scripts->registered[ $handle ] ) ? $wp_scripts->registered[ $handle ]->src : '';
				if ( ! in_array( $handle, $exceptions, true ) && ! $wp_scripts->in_default_dir( $src ) && ! $this->is_built_in( $handle ) ) {
					wp_dequeue_script( $handle );
				}
			}
		}

		if ( is_object( $wp_styles ) && isset( $wp_styles->queue ) && is_array( $wp_styles->queue ) ) {

			foreach ( $wp_styles->queue as $handle ) {
				if ( ! in_array( $handle, $exceptions, true ) && ! $this->is_built_in( $handle ) ) {
					wp_dequeue_style( $handle );
				}
			}
		}

	}

	/**
	 * Checks if a given callback is from a whitelisted class.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function is_whitelisted( $cb ) {

		if ( ! is_array( $cb ) || ! is_object( $cb[0] ) ) {
			return false;
		}

		$class_name = get_class( $cb[0] );

		if ( strpos( $class_name, 'WP_Customize' ) === 0 ) {
			return true;
		}

		return in_array( $class_name, array( 'QM_Collector_Assets_Scripts', 'QM_Collector_Assets_Styles', 'QM_Dispatcher_Html' ), true );

	}

	/**
	 * Removes all functions attached to a given hook's priority.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function remove_action_handles( $handles, $exceptions ) {

		if ( ! is_array( $handles ) ) {
			return array();
		}

		// Loop through all callbacks in the level...
		foreach ( $handles as $id => $data ) {

			// ... and remove handles that are not in our exceptions list.
			if ( ! in_array( $data['function'], $exceptions, true ) && ! $this->is_whitelisted( $data['function'] ) ) {
				unset( $handles[ $id ] );
			}
		}

		return $handles;
	}

	/**
	 * Remove header actions.
	 *
	 * @since 1.0.0
	 */
	public function remove_all_header_actions() {
		global $wp_filter;

		// Callbacks to skip.
		$action_exceptions = array(
			'wp_enqueue_scripts',
			'wp_print_head_scripts',
			'wp_print_styles',
			'wp_generator',
			'wp_site_icon',
			'wp_no_robots',
			array( $this, 'remove_external_scripts' ),
		);

		// Remove all callbacks that are not in the above array.
		foreach ( $wp_filter['wp_head'] as $priority => $handles ) {
			$wp_filter['wp_head'][ $priority ] = $this->remove_action_handles( $handles, $action_exceptions ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

	}

	/**
	 * Remove footer actions.
	 *
	 * @since 1.0.0
	 */
	public function remove_all_footer_actions() {
		global $wp_filter;

		// Callbacks to skip.
		$action_exceptions = array(
			'wp_print_footer_scripts',
			'wp_admin_bar_render',
			array( $this, 'remove_external_scripts' ),
		);

		// Remove all callbacks that are not in the above array.
		foreach ( $wp_filter['wp_footer'] as $priority => $handles ) {
			$wp_filter['wp_footer'][ $priority ] = $this->remove_action_handles( $handles, $action_exceptions ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

	}

	/**
	 * Register custom customizer scripts.
	 *
	 * @since 1.0.0
	 */
	public function customizer_controls_scripts() {
		do_action( "presstomizer_{$this->id}_enqueue_controls_scripts" );
	}

	/**
	 * Enqueue scripts for preview area
	 *
	 * @since 1.0.0
	 */
	public function customizer_preview_scripts() {
		do_action( "presstomizer_{$this->id}_enqueue_preview_scripts" );
	}

}
