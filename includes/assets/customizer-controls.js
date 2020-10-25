( function( $ ) {

	// Loader.
	var loader = '<span class="spinner" style="visibility: visible"></span>';

	// Closes the theme switcher when the close button is clicked.
	$( '.email-customizer-popup-close' ).on(
		'click',
		function() {
			$( '.email-customizer-change-theme-popup' )
				.removeClass( 'email-customizer-change-theme-popup-show' )
		}
	);

	// Create the switch themes button...
	$( '#customize-info .customize-help-toggle' )
		.after( '<div style="text-align: right;"><button></button></div>' )
		.next()
		.find( 'button' )
		.text( email_customizer_i10n.changeTheme )
		.attr( 'aria-label', email_customizer_i10n.changeTheme )
		.attr( 'type', 'button' )
		.addClass( 'button change-theme email-customizer-change-theme-button' )
		.on(
			'click',
			function (e) {
				e.preventDefault();

				// Display the lightbox
				$( '.email-customizer-change-theme-popup' ).addClass( 'email-customizer-change-theme-popup-show' );
			}
		);

		// Changes the template.
		$( '.email-customizer-change-theme-popup-submit' ).on(
			'click',
			function( e ) {
				e.preventDefault();

				// Save changes.
				$('#save').click();

				// Get the template.
				var template = $( '.email-customizer-change-theme-popup select' ).val()

				// Display the loader.
				$( '.email-customizer-change-theme-popup' ).html( loader )

				// Reload the page.
				window.location = email_customizer_i10n.switcherURL.replace( '%template%', template )
			}
		);
		
} )( jQuery );
