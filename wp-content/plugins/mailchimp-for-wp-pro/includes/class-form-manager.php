<?php

if( ! defined("MC4WP_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class MC4WP_Form_Manager
{
	/**
	 * @var array
	 */
	private $options = array();

	/**
	* @var int
	*/
	private $form_instance_number = 1;

	/**
	* @var boolean
	*/
	private $loaded_ajax_scripts = false;

    /**
     * @var bool
     */
    private $loader_css_printed = false;

	/**
	 * @var MC4WP_Form_Request
	 */
	private $form_request;

	/**
	* Constructor
	*/
	public function __construct() 
	{
		$this->options = $opts = mc4wp_get_options( 'form' );

		add_action( 'init', array( $this, 'initialize' ) );
		add_action( 'template_redirect', array( $this, 'show_form_preview' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_stylesheet' ) );

		// enable shortcodes in text widgets
		add_filter( 'widget_text', 'shortcode_unautop' );
		add_filter( 'widget_text', 'do_shortcode' );

		add_shortcode( 'mc4wp_form', array( $this, 'output_form' ) );

		// deprecated. use mc4wp_form.
		add_shortcode( 'mc4wp-form', array( $this, 'output_form' ) );
				
		// has a form been submitted, either by ajax or manually?
		if( isset( $_POST['_mc4wp_form_submit'] ) ) {
			$this->form_request = new MC4WP_Form_Request;
		}

	}

	/**
	* Initialize form stuff
	*
	* - Registers post type
	* - Registers scripts
	*/
	public function initialize() {

		// register post type
		register_post_type( 'mc4wp-form', array(
			'labels' => array(
				'name' => 'MailChimp Sign-up Forms',
				'singular_name' => 'Sign-up Form',
				'add_new_item' => 'Add New Form',
				'edit_item' => 'Edit Form',
				'new_item' => 'New Form',
				'all_items' => 'All Forms',
				'view_item' => null
				),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => false
			)
		);

		if( ! is_admin() ) {

			// should we load the minified script version?
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.js' : '.min.js';

			// register placeholder script, which will later be enqueued for IE only
			wp_register_script( 'mc4wp-placeholders', MC4WP_PLUGIN_URL . 'assets/js/third-party/placeholders.min.js', array(), MC4WP_VERSION, true );

			// register ajax script
			wp_register_script( 'mc4wp-ajax-forms', MC4WP_PLUGIN_URL . 'assets/js/ajax-forms' . $suffix, array( 'jquery-form' ), MC4WP_VERSION, true );

			// register non-AJAX script (that handles form submissions)
			wp_register_script( 'mc4wp-form-request', MC4WP_PLUGIN_URL . 'assets/js/form-request' . $suffix, array(), MC4WP_VERSION, true );

			// Load AJAX scripts on all pages if lazy load is disabled
			$lazy_load_ajax = apply_filters( 'mc4wp_lazy_load_ajax_scripts', true );
			if( true !== $lazy_load_ajax ) {
				$this->load_ajax_scripts();
			}
		}

	}

	/**
	* Loads a basic HTML template to preview forms
	* @return boolean
	*/
	public function show_form_preview()
	{
		if( ! isset( $_GET['_mc4wp_css_preview'] ) ) {
			return false;
		}

		require MC4WP_PLUGIN_DIR . 'includes/views/pages/form-preview.php';
		die();
	}

	/**
	* Tells the plugin which shipped stylesheets to load.
	*
	* @return bool True if a stylesheet was enqueued
	*/
	public function load_stylesheet( ) {

		if( $this->options['css'] == false || isset( $_GET['_mc4wp_css_preview'] ) ) {
			return false;
		}

		$opts = $this->options;
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		if( $opts['css'] === 'custom' ) {

			// load the custom stylesheet
			$custom_stylesheet = get_option( 'mc4wp_custom_css_file', false );

			// prevent query on every pageload if option does not exist
			if( false === $custom_stylesheet ) {
				update_option( 'mc4wp_custom_css_file', '' );
			}

			// load stylesheet
			if( is_string( $custom_stylesheet ) && $custom_stylesheet !== '' ) {
				wp_enqueue_style( 'mc4wp-custom-form-css', $custom_stylesheet, array(), MC4WP_VERSION, 'all' );
			}

		} elseif( $opts['css'] !== 1 && $opts['css'] !== 'default' ) {

			if( $opts['css'] === 'custom-color' ) {
				// load the custom color theme
				$custom_color = urlencode( $opts['custom_theme_color'] );
				wp_enqueue_style( 'mailchimp-for-wp-form-theme-' . $opts['css'], MC4WP_PLUGIN_URL . "assets/css/form-theme-custom.php?custom-color=" . $custom_color, array(), MC4WP_VERSION, 'all' );

			} else {
				// load one of the default form themes
				$form_theme = $opts['css'];
				if( in_array( $form_theme, array( 'blue', 'green', 'dark', 'light', 'red' ) ) ) {
					wp_enqueue_style( 'mailchimp-for-wp-form-theme-' . $opts['css'], MC4WP_PLUGIN_URL . 'assets/css/form-theme-' . $opts['css'] . $suffix . '.css', array(), MC4WP_VERSION, 'all' );
				}
			}

		} else {
			// load just the basic form reset
			wp_enqueue_style( 'mailchimp-for-wp-form', MC4WP_PLUGIN_URL . 'assets/css/form'. $suffix .'.css', array(), MC4WP_VERSION, 'all' );
		}

		return true;
	}

	/**
	* Get CSS classes to add to a form element
	*
	* @param int $form_id
	* @return string
	*/ 
	public function get_form_css_classes( $form_id ) {

		$settings = mc4wp_get_form_settings( $form_id, true );

		/**
		 * @filter mc4wp_form_css_classes
		 * @expects array
		 *
		 * Can be used to add additional CSS classes to the form container
		 */
		$css_classes = apply_filters( 'mc4wp_form_css_classes', array( 'form' ) );

		// the following classes MUST be used
		$css_classes[] = 'mc4wp-form';
		$css_classes[] = 'mc4wp-form-' . $form_id;

		if( $settings['ajax'] ) { 
			$css_classes[] = 'mc4wp-ajax'; 
		}

		// Add form classes if a Form Request was captured
		if( is_object( $this->form_request ) && $this->form_request->get_form_instance_number() === $this->form_instance_number ) {

			$css_classes[] = 'mc4wp-form-submitted';

			if( $this->form_request->is_successful() ) {
				$css_classes[] = 'mc4wp-form-success';
			} else {
				$css_classes[] = 'mc4wp-form-error';
			}

		}

		return implode( ' ', $css_classes );
	}

	/**
	* Outputs a form with the given ID
	*
	* @param array $atts
	* @param string $content
	* @return string 
	*/
	public function output_form( $atts = array(), $content = '' )
	{
		// include the necessary functions file
		if( ! function_exists( 'mc4wp_replace_variables' ) ) {
			include_once MC4WP_PLUGIN_DIR . 'includes/functions/template.php';
		}

		// try to get default form ID if it wasn't specified in the shortcode atts
		if( false === isset( $atts['id'] ) ) {

			// try to get default form id
			$atts['id'] = get_option( 'mc4wp_default_form_id', false );
			if( false === $atts['id'] ) {

				if( current_user_can( 'manage_options' ) ) {
					return '<p>'. sprintf( __( '<strong>Error:</strong> Please specify a form ID. Example: %s.', 'mailchimp-for-wp' ), '<code>[mc4wp_form id="321"]</code>' ) .'</p>';
				}

				return '';
			}
		}

		// Get the form with the specified ID
		$form = get_post( $atts['id'] );

		// did we find a valid form with this ID?
		if( ! is_object( $form ) || $form->post_type !== 'mc4wp-form' ) {

			if( current_user_can( 'manage_options' ) ) {
				return '<p>'. __( '<strong>Error:</strong> Sign-up form not found. Please check if you used the correct form ID.', 'mailchimp-for-wp' ) .'</p>';
			}

			return '';
		}

		// was this form submitted?
		$was_submitted = ( is_object( $this->form_request ) && $this->form_request->get_form_instance_number() === $this->form_instance_number );
		$opts = mc4wp_get_form_settings( $form->ID, true );

		// add some useful css classes
		$css_classes = $this->get_form_css_classes( $form->ID );

		// Start building content string
		$opening_html = "<!-- MailChimp for WP Pro v" . MC4WP_VERSION . " -->";
		$opening_html .= '<div id="mc4wp-form-' . $this->form_instance_number . '" class="' . $css_classes . '">';

		/**
		 * @filter mc4wp_form_action
		 * @expects string
		 *
		 * Sets the `action` attribute of the form element. Defaults to the current URL.
		 */
		$form_action = apply_filters( 'mc4wp_form_action', mc4wp_get_current_url() );
		$opening_html .= '<form method="post" action="' . $form_action . '">';

		// Generate before & after fields HTML
		$before_fields = apply_filters( 'mc4wp_form_before_fields', '' );
		$after_fields = apply_filters( 'mc4wp_form_after_fields', '' );

		// do not add form fields if form was submitted and hide_after_success is enabled
		if( ! $was_submitted || ! $opts['hide_after_success'] || ! $this->form_request->is_successful() ) {

			// replace special values
			$visible_fields = __( $form->post_content, 'mailchimp-for-wp' );
			$visible_fields = str_ireplace( array( '%N%', '{n}' ), $this->form_instance_number, $visible_fields );
			$visible_fields = mc4wp_replace_variables( $visible_fields, array_values( $opts['lists'] ) );

			// insert captcha
			if( function_exists( 'cptch_display_captcha_custom' ) ) {
				$captcha_fields = '<input type="hidden" name="_mc4wp_has_captcha" value="1" /><input type="hidden" name="cntctfrm_contact_action" value="true" />' . cptch_display_captcha_custom();
				$visible_fields = str_ireplace( array( '{captcha}', '[captcha]' ), $captcha_fields, $visible_fields );
			}

			/**
			 * @filter mc4wp_form_content
			 * @param int $form_id The ID of the form that is being shown
			 * @expects string
			 *
			 * Can be used to customize the content of the form mark-up, eg adding additional fields.
			 */
			$visible_fields = apply_filters( 'mc4wp_form_content', $visible_fields, $form->ID );

			// hidden fields
			$hidden_fields = '<textarea name="_mc4wp_required_but_not_really" style="display: none !important;"></textarea>';
			$hidden_fields .= '<input type="hidden" name="_mc4wp_form_id" value="'. $form->ID .'" />';
			$hidden_fields .= '<input type="hidden" name="_mc4wp_form_instance" value="'. $this->form_instance_number .'" />';
			$hidden_fields .= '<input type="hidden" name="_mc4wp_form_submit" value="1" />';
			$hidden_fields .= '<input type="hidden" name="_mc4wp_form_nonce" value="'. wp_create_nonce( '_mc4wp_form_nonce' ) .'" />';
			$hidden_fields .= "</form>";
		} else {
			$visible_fields = '';
			$hidden_fields = '';
		}

		$response_html = '';

		// does this form have AJAX enabled?
		if( $opts['ajax'] ) {

			// load ajax scripts (in footer)
			$this->load_ajax_scripts();

			// set response html
			$response_html = $this->get_form_ajax_messages_html( $form->ID );
		}

		// was form submited?
		if( $was_submitted) {

			// enqueue scripts (in footer) if form was submited
			wp_enqueue_script( 'mc4wp-form-request' );
			wp_localize_script( 'mc4wp-form-request', 'mc4wpFormRequestData', array(
					'success' => ( $this->form_request->is_successful() ) ? 1 : 0,
					'submittedFormId' => $this->form_request->get_form_instance_number(),
					'postData' => stripslashes_deep( $_POST )
				)
			);

			// set response html
			$response_html = $this->get_form_message_html( $form->ID );
		}

		// add form response to content, if no {response} tag present
		if( '' !== $response_html && ( stristr( $visible_fields, '{response}' ) === false || $opts['hide_after_success'] ) ) {

			/**
			 * @filter mc4wp_form_message_position
			 * @expects string before|after
			 *
			 * Can be used to change the position of the form success & error messages.
			 * Valid options are 'before' or 'after'
			 */
			$message_position = apply_filters( 'mc4wp_form_message_position', 'after' );

			switch( $message_position ) {
				case 'before':
					$before_fields = $before_fields . $response_html;
					break;

				case 'after':
					$after_fields = $response_html . $after_fields;
					break;
			}

			// reset response html, we only need it once
			$response_html = '';
		}

		// Always replace {response} tag, either with empty string or actual response
		$visible_fields = str_ireplace( '{response}', $response_html, $visible_fields );

		$closing_html = "</div><!-- / MailChimp for WP Pro -->";

		// increase form instance number in case there is more than one form on a page
		$this->form_instance_number++;

		// make sure scripts are enqueued later
		global $is_IE;
		if( isset( $is_IE ) && $is_IE ) {
			wp_enqueue_script( 'mc4wp-placeholders' );
		}

		// concatenate and return the HTML parts
		return $opening_html . $before_fields . $visible_fields . $hidden_fields . $after_fields . $closing_html;
	}

	/**
	 * Get the HTML for all (hidden) AJAX success & error messages
	 *
	 * @param int $form_id The id of the form for which the message string should be built
	 *
	 * @return string
	 */
	private function get_form_ajax_messages_html( $form_id ) {

		// Add AJAX loader span to output
		$html = '<span class="mc4wp-ajax-loader" style="display: none !important;"></span>';

		// Add all error and success messages to output, but hidden.
		$messages = $this->get_form_messages( $form_id);
		foreach( $messages as $key => $message ) {
			$html .= '<div style="display: none !important;" class="mc4wp-alert mc4wp-' . esc_attr( $message['type'] ) . ' mc4wp-'. esc_attr( $key ) .'-message">' . $message['text'] . '</div>';
		}

		return $html;
	}

	/**
	 * Returns the HTML for success or error messages
	 *
	 * @param int $form_id
	 *
	 * @return string
	 */
	private function get_form_message_html( $form_id ) {

		// don't show message if form wasn't submitted
		if( ! is_object( $this->form_request ) ) {
			return '';
		}

		// get all form messages
		$messages = $this->get_form_messages( $form_id );

		// retrieve correct message
		$type = ( $this->form_request->is_successful() ) ? 'success' : $this->form_request->get_error_code();
		$message = ( isset( $messages[ $type ] ) ) ? $messages[ $type ] : $messages['error'];

		/**
		 * @filter mc4wp_form_error_message
		 * @deprecated 2.0.5
		 * @use mc4wp_form_messages
		 *
		 * Used to alter the error message, don't use. Use `mc4wp_form_messages` instead.
		 */
		$message['text'] = apply_filters('mc4wp_form_error_message', $message['text'], $this->form_request->get_error_code() );

		$html = '<div class="mc4wp-alert mc4wp-'. $message['type'].'">' . $message['text'] . '</div>';

		// show additional MailChimp API errors to administrators
		if( false === $this->form_request->is_successful() && current_user_can( 'manage_options' ) ) {
			// show MailChimp error message (if any) to administrators
			$api = mc4wp_get_api();
			if( $api->has_error() ) {
				$html .= '<div class="mc4wp-alert mc4wp-error"><strong>Admin notice:</strong> '. $api->get_error_message() . '</div>';
			}
		}

		return $html;
	}

	/**
	 * Returns the various error and success messages in array format
	 *
	 * Example:
	 * array(
	 *      'invalid_email' => array(
	 *          'type' => 'css-class',
	 *          'text' => 'Message text'
	 *      ),
	 *      ...
	 * );
	 *
	 * @param int $form_id
	 *
	 * @return array
	 */
	public function get_form_messages( $form_id ) {

		$opts = mc4wp_get_form_settings( $form_id, true );

		$messages = array(
			'already_subscribed' => array(
				'type' => 'notice',
				'text' => $opts['text_already_subscribed']
			),
			'error' => array(
				'type' => 'error',
				'text' => $opts['text_error']
			),
			'invalid_email' => array(
				'type' => 'error',
				'text' => $opts['text_invalid_email']
			),
			'success' => array(
				'type' => 'success',
				'text' => $opts['text_success']
			),
			'invalid_captcha' => array(
				'type' => 'error',
				'text' => $opts['text_invalid_captcha']
			),
			'required_field_missing' => array(
				'type' => 'error',
				'text' => $opts['text_required_field_missing']
			)
		);

		/**
		 * @filter mc4wp_form_messages
		 * @expects array
		 *
		 * Allows registering custom form messages, useful if you're using custom validation using the `mc4wp_valid_form_request` filter.
		 */
		$messages = apply_filters( 'mc4wp_form_messages', $messages, $form_id );

		return $messages;
	}


	/**
	 * Load the necessary AJAX scripts
	 *
	 * @return bool
	 */
	public function load_ajax_scripts() {

		if( $this->loaded_ajax_scripts ) {
			return false;
		}

		// get ajax scripts to load in the footer
		wp_enqueue_script( 'mc4wp-ajax-forms' );

		// Print inline CSS in footer
		add_action( 'wp_footer', array( $this, 'print_loader_css') );

		// Print vars required by AJAX script
		$scheme = ( is_ssl() ) ? 'https' : 'http';
		wp_localize_script( 'mc4wp-ajax-forms', 'mc4wp_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php', $scheme )
			)
		);

		// set flag to ensure ajax scripts are only loaded once
		$this->loaded_ajax_scripts = true;

		return true;
	}

	/**
	* Prints the AJAX Loader CSS (Inline)
	*/
	public function print_loader_css() {

        $print_css = apply_filters( 'mc4wp_print_ajax_loader_styles', true );

        if( $print_css !== true || $this->loader_css_printed === true ) {
            return false;
        }

		?><style type="text/css">
		.mc4wp-ajax-loader{ 
			vertical-align: middle; 
			height: 16px; 
			width:16px; 
			border:0; 
			background: url("<?php echo esc_html( MC4WP_PLUGIN_URL . 'assets/img/ajax-loader.gif' ); ?>"); 
		}
		</style><?php

        $this->loader_css_printed = true;
		return true;
	}

}