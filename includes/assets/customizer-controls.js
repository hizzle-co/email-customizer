( function( $ ) {

	// Close button div html.
	var switchThemesClose = '<div class="close-button-div"><button class="button button-secondary">' + email_customizer_i10n.close + '</button></div>';

	//Loader.
	var loader = '<span class="spinner" style="visibility: visible"></span>';

	// Create the main popup and add a loading spinner to it.
	var switchThemesPopup =
		$( 'body')
			.append( '<div class="email-customizer-change-theme-popup"></div>' )
			.find( '.email-customizer-change-theme-popup' )
			.append( loader );

	// Helper function to attach a close button to a popup.
	var appendPopupClose = function () {
		$(switchThemesPopup)
			.append('<span class="mo-popup-close"><span class="dashicons dashicons-no" style="font-size: 2.5em;cursor: pointer"></span></span>')
			.find('.mo-popup-close')
			.attr('title', email_customizer_i10n.close)
			.on('click', function () {
				$(switchThemesPopup)
					.removeClass( 'email-customizer-change-theme-popup-show' )
					.html(loader)
			});
	};

	// Create the switch themes button...
	var switchThemesButton =
		$( '#customize-info .customize-help-toggle' )
			.after( '<div style="text-align: right;"><button></button></div>' )
			.next()
			.find( 'button' )
			.text( email_customizer_i10n.changeTheme )
			.attr( 'aria-label', email_customizer_i10n.changeTheme )
			.attr( 'type', 'button' )
			.addClass( 'button change-theme email-customizer-change-theme-button' );


	// ... which when clicked loads email templates.
	$( switchThemesButton ).on( 'click', function (e) {
		e.preventDefault();

		// Display the lightbox
		$( switchThemesPopup ).addClass( 'email-customizer-change-theme-popup-show' );

	})

} )( jQuery );
