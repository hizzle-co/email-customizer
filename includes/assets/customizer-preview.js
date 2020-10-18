/**
 * Dynamic Internal/Embedded Style for a Control
 */
function email_customizer_add_dynamic_css( control, style ) {
	control = control.replace( '[', '-' );
	control = control.replace( ']', '' );
	jQuery( 'style#' + control ).remove();

	jQuery( 'head' ).append(
		'<style id="' + control + '">' + style + '</style>'
    );

}

/**
 * Apply CSS for the element
 */
function email_customizer_css( control, css_property, selector, unit ) {

	wp.customize( control, function( value ) {
		value.bind( function( new_value ) {

			// Remove <style> first!
			control = control.replace( '[', '-' );
			control = control.replace( ']', '' );
			jQuery( 'style#' + control + '-' + css_property ).remove();

			if ( new_value ) {

				/**
				 *	If ( unit == 'url' ) then = url('{VALUE}')
				 *	If ( unit == 'px' ) then = {VALUE}px
				 *	If ( unit == 'em' ) then = {VALUE}em
				 *	If ( unit == 'rem' ) then = {VALUE}rem.
				 */
				if ( 'undefined' != typeof unit) {

					if ( 'url' === unit ) {
						new_value = 'url(' + new_value + ')';
					} else {
						new_value = new_value + unit;
					}
				}

				// Concat and append new <style>.
				jQuery( 'head' ).append(
					'<style id="' + control + '-' + css_property + '">'
					+ selector + '	{ ' + css_property + ': ' + new_value + ' }'
					+ '</style>'
				);

			}

		} );
	} );
}

/**
 * Replaces content with text.
 */
function email_customizer_text( control, selector ) {

	wp.customize( control, function( value ) {
		value.bind( function( new_value ) {

			if ( ! new_value ) {
				jQuery( selector ).html( "&nbsp;" );
			} else {
				jQuery( selector ).text( new_value );
			}

		} );
	} );
}

/**
 * Handles images.
 */
function email_customizer_image( control, selector ) {

	wp.customize( control, function( value ) {
		value.bind( function( new_value ) {

			if ( ! new_value ) {
				jQuery( selector ).hide();
			} else {
				jQuery( selector ).show( new_value ).attr( 'src', new_value );
			}

		} );
	} );
}

( function( $ ) {

	// "General" Panel.
	email_customizer_css( 'email_customizer[bg_image]', 'background-image', 'body.email-body', 'url' );
	email_customizer_css( 'email_customizer[bg_color]', 'background-color', 'body.email-body' );
	email_customizer_text( 'email_customizer[custom_css]', '#email-customizer-preview-custom-css' );
	email_customizer_css( 'email_customizer[width]', 'width', '.email-body .template__container, .email-body .hero-image' );
	email_customizer_css( 'email_customizer[header_left_width]', 'width', '.email-body .components__header .heading__left' );
	email_customizer_css( 'email_customizer[spacing]', 'height', '.email-body .card-spacing' );

	// Heading.
	email_customizer_image( 'email_customizer[logo]', '.heading__left-title-div .logo' );
	email_customizer_css( 'email_customizer[header_size]', 'font-size', '.email-body .components__header a, .email-body .components__header p, .email-body .components__header div, .email-body .components__header .components__inner' );
	email_customizer_css( 'email_customizer[header_bg_color]', 'background-color', '.email-body .components__header .components__inner' );
	email_customizer_css( 'email_customizer[header_color]', 'color', '.email-body .components__header .components__inner, .email-body .components__header p, .email-body .components__header div' );
	email_customizer_css( 'email_customizer[header_link_color]', 'color', '.email-body .components__header .heading a' );

	// Content.
	email_customizer_css( 'email_customizer[content_size]', 'font-size', '.email-body .components__content a, .email-body .components__content p, .email-body .components__content div, .email-body .components__content .components__inner' );
	email_customizer_css( 'email_customizer[content_bg_color]', 'background-color', '.email-body .components__content .components__inner' );
	email_customizer_css( 'email_customizer[content_color]', 'color', '.email-body .components__content .components__inner, .email-body .components__content p, .email-body .components__content div' );
	email_customizer_css( 'email_customizer[content_link_color]', 'color', '.email-body .components__content a' );

	// Footer.
	email_customizer_css( 'email_customizer[footer_size]', 'font-size', '.email-body .components__footer a, .email-body .components__footer p, .email-body .components__footer div, .email-body .components__footer .components__inner' );
	email_customizer_css( 'email_customizer[footer_bg_color]', 'background-color', '.components__footer .components__inner' );
	email_customizer_css( 'email_customizer[footer_color]', 'color', '.email-body .components__footer .components__inner, .email-body .components__footer p, .email-body .components__footer div' );
	email_customizer_css( 'email_customizer[footer_link_color]', 'color', '.email-body .components__footer a' );

} )( jQuery );
