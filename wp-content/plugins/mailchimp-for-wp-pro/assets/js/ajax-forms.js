window.mc4wpAjaxForms = (function() {


	/**
	 * @var object Shorthand for global jQuery object
	 */
	var $ = window.jQuery;

	/**
	 * @var object The context in which a form was submitted
	 */
	var $context;

	/**
	 * @var object Fallback for IE8
	 */
	var console = console || { log: function() {} };

	/**
	 * Initializes the MailChimp for WordPress JS functionality
	 */
	function init() {

		// jQuery.ajaxForm is undefined, bail.
		if( $.fn.ajaxForm === undefined ) {
			console.log( "MailChimp for WP: jquery-forms.js is not loaded properly. Not activating AJAX.")
			return;
		}

		// Add AJAX to forms
		$(".mc4wp-ajax form").ajaxForm({
			data        : { action: 'mc4wp_submit_form' },
			dataType    : 'json',
			url         : mc4wp_vars.ajaxurl,
			delegation  : true,
			success     : onAjaxSuccess,
			error       : onAjaxError,
			beforeSubmit: beforeSubmit
		});

		// Add class to form after submit button was clicked
		$('.mc4wp-form').find('[type="submit"]').click( function() {
			 $(this).parents('.mc4wp-form').addClass('mc4wp-form-submitted');
		});

	}

	/**
	 * Runs before an AJAX form is submitted
	 * @param data
	 * @param $form
	 */
	function beforeSubmit( data, $form ) {

		var $ajaxLoader, $submitButton;

		$context = $form.parent('.mc4wp-form');

		// Remove possibly added classes
		$context.removeClass('mc4wp-form-success');
		$context.removeClass('mc4wp-form-error');

		// Hide possible errors from previous sign-up request
		$context.find('.mc4wp-alert').hide();
		$context.find("#mc4wp-mailchimp-error").remove();

		// find loader and button in form mark-up
		$submitButton = $form.find('input[type="submit"], button[type="submit"]').first();

		// Disable submit button
		$submitButton.attr( 'disabled', 'disabled' );

		// Find loading text out of data attribute
		$loadingText = $submitButton.data('loading-text');

		// If loading text is set, store the original button text and change text
		if( $loadingText ) {
			$submitButton.data('original-text', buttonText( $submitButton ) );
			buttonText( $submitButton, $loadingText );
		} else {

			// Loading text was not set, use the AJAX loader
			// Insert loader after submit button
			$ajaxLoader = $context.find('.mc4wp-ajax-loader');
			$ajaxLoader.insertAfter( $submitButton );
			$ajaxLoader.show().css('display', 'inline-block');

			// Add small left-margin if loader doesn't have one yet
			if( parseInt( $ajaxLoader.css( 'margin-left' ) ) < 5 ) {
				$ajaxLoader.css('margin-left', '5px');
			}
		}

		// Trigger mc4wp.ajax.before JS event to hook into
		var event = $.Event( "mc4wp.ajax.before" );
		event.form = $context;
		$( document ).trigger( event );
	}

	/**
	 * Get or set the text of a submit button
	 *
	 * @param $button string
	 * @param newText string
	 * @returns string
	 */
	function buttonText( $button, newText ) {


		if( $button.is('button' ) ) {
			// if it's a button, get the text
			var buttonText = $button.text();

			if( newText !== undefined ) {
				$button.text( newText );
			}

		} else {
			// if it's an input field, get the value
			var buttonText = $button.val();

			if( newText !== undefined ) {
				$button.val( newText );
			}
		}

		return buttonText;
	}

	/**
	 * Runs after every successful AJAX request
	 *
	 * @param response
	 * @param status
	 */
	function onAjaxSuccess( response, status ) {

		// Trigger mc4wp.ajax.after JS event to hook into
		var event = $.Event( "mc4wp.ajax.after" );
		event.form = $context;
		event.response = response;
		$( document ).trigger( event );

		// hide ajax loader
		var $ajaxLoader;
		$ajaxLoader = $context.find('.mc4wp-ajax-loader');
		$ajaxLoader.hide();

		// Find submit button
		$submitButton = $context.find('input[type="submit"], button[type="submit"]').first();

		// Restore button text
		var originalText = $submitButton.data('original-text');
		if( originalText ) {
			buttonText( $submitButton, originalText );
		}

		// Re-enable submit button
		$submitButton.removeAttr( 'disabled' );

		// Act on response parameters
		if(response.success) {
			onSubscribeSuccess( response.redirect, response.hide_form, response.data );
		} else {
			onSubscribeError( response.error, response.data );
		}

	}

	/**
	 * Runs after every failed AJAX request
	 *
	 * @param response
	 */
	function onAjaxError( response ) {

		// Just log the request
		console.log(response);
	}

	function onSubscribeSuccess( redirect_url, hide_form, data ) {

		// Find form element
		var $form = $context.find('form');

		// add class for successful forms
		$context.addClass('mc4wp-form-success');

		// Show success message
		$context.find('div.mc4wp-success-message').show();

		// Reset form to original state
		$form.trigger( 'reset' );

		// Redirect to the specified location
		if( redirect_url && redirect_url != '' ) {
			window.setTimeout(function() {
				window.location.replace( redirect_url );
			}, 2500);

		}

		// Hide the form if the "hide form" option is selected
		if( hide_form ) {
			$form.hide();
		}

		// Trigger mc4wp.success JS event to hook into
		var event = $.Event( "mc4wp.success" );
		event.form = $context;
		event.formData = data;
		$( document ).trigger( event );
	}

	/**
	 * @param error
	 */
	function onSubscribeError( error, data ) {
		var error_type, $message;
		error_type = ( error.type == '' ) ? 'error' : error.type;

		// Show error in console if no MailChimp list is selected
		if( error_type === 'no_lists_selected' ) {
			error_type = 'error';
			console.log( 'You didn\'t select a MailChimp list to subscribe to in the form settings.' );
		}

		// add class for error forms
		$context.addClass('mc4wp-form-error');

		// Show error div
		$message = $context.find('.mc4wp-' + error_type + '-message').show();

		// Show MailChimp error to admins
		if( error.show && error_type == 'error' && error.mailchimp_error != '' ) {
			$('<div class="mc4wp-alert mc4wp-notice" id="mc4wp-mailchimp-error"><strong>MailChimp returned this error:</strong><br>' + error.mailchimp_error + '<br><br><em>this message is only visible to administrators</em></div>').insertAfter( $message );
		}

		// Trigger mc4wp.error JS event to hook into
		var event = $.Event( "mc4wp.error" );
		event.form = $context;
		event.formData = data;
		event.error = error;
		$( document ).trigger( event );
	}

	return {
		init: function() {
			init();
		}
	}

})();

jQuery( document).ready( function() {
	mc4wpAjaxForms.init();
} );