<?php
/**
 * Plugin Name: Email Customizer
 * Description: Easily replace the plain text WordPress emails with beautiful HTML emails that match your brand colors. All without writing a single line of code.
 * Plugin URI: https://github.com/hizzle-co/email-customizer
 * Author: Noptin Team
 * Version: 1.0.1
 * Author URI: https://noptin.com
 *
 * Text Domain: email-customizer
 *
 * @package Email-CUSTOMIZER
 *
 * Email Customizer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Email Customizer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Returns the current plugin version.
 *
 * @since 1.0.0
 * @return string
 */
function email_customizer_get_version() {
    return '1.0.1';
}

/**
 * Returns the main plugin file.
 *
 * @since 1.0.0
 * @return string
 */
function email_customizer_get_plugin_file() {
    return __FILE__;
}

/**
 * Displays an admin error when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function email_customizer_fail_php_version() {

	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'Email Customizer requires PHP version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'email-customizer' ), '5.6' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );

}

/**
 * Display an admin error when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function email_customizer_fail_wp_version() {

	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'Email Customizer requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'email-customizer' ), '4.7' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );

}

/**
 * Display an admin error when the site doesn't have the DOMDocument extension.
 *
 * @since 1.0.0
 *
 * @return void
 */
function email_customizer_fail_dom_document() {

	/* translators: %s: DOMDocument */
	$message      = sprintf( esc_html__( 'Email Customizer requires that you install the %s PHP extension. Please contact your web-host for instructions on how to do that.', 'email-customizer' ), 'DOMDocument' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );

}

/**
 * Load Email Customizer textdomain.
 *
 * @since 1.0.0
 * @return void
 */
function email_customizer_load_plugin_textdomain() {

	load_plugin_textdomain(
		'email-customizer',
		false,
		'email-customizer/languages/'
	);

}
add_action( 'plugins_loaded', 'email_customizer_load_plugin_textdomain' );


// Ensure that this environment is supported then load our plugin.
if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
	add_action( 'admin_notices', 'email_customizer_fail_php_version' );
} else if ( ! version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
	add_action( 'admin_notices', 'email_customizer_fail_wp_version' );
} else if ( ! class_exists( 'DOMDocument' ) ) {
	add_action( 'admin_notices', 'email_customizer_fail_dom_document' );
} else {
    require plugin_dir_path( __FILE__ ) . 'includes/class-email-customizer.php';
    $GLOBALS['email_customizer'] = new Email_Customizer();
}
