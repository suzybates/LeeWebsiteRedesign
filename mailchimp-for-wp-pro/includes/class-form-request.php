<?php
if( ! defined( 'MC4WP_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * Handles form submissions
 */
class MC4WP_Form_Request {

	/**
	 * @var int
	 */
	private $form_instance_number = 0;

	/**
	 * @var array
	 */
	private $posted_data = array();

	/**
	 * @var bool
	 */
	private $success = false;

	/**
	 * @var string
	 */
	private $error_code = 'error';

	/**
	 * @var string
	 */
	private $mailchimp_error = '';

	/**
	 * @var int The ID of the form that was submitted
	 */
	private $form_id = 0;

	/**
	 * @var array
	 */
	private $form_options = array();

	/**
	 * Constructor
	 *
	 * Hooks into the `init` action to start the process of subscribing the person who filled out the form
	 */
	public function __construct() {

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			add_action( 'wp_ajax_nopriv_mc4wp_submit_form', array( $this, 'act_ajax' ) );
			add_action( 'wp_ajax_mc4wp_submit_form', array( $this, 'act_ajax' ) );
		} else {
			add_action( 'init', array( $this, 'act' ) );
		}
	}

	/**
	 * @return bool
	 */
	public function is_successful() {
		return $this->success;
	}

	/**
	 * @return string
	 */
	public function get_error_code() {
		return $this->error_code;
	}

	/**
	 * @return array
	 */
	public function get_posted_data() {
		return $this->posted_data;
	}

	/**
	 * @return int
	 */
	public function get_form_instance_number() {
		return $this->form_instance_number;
	}

	/**
	 * @return int
	 */
	public function get_form_id() {
		return $this->form_id;
	}

	/**
	 * @return string
	 */
	public function get_mailchimp_error() {
		return $this->mailchimp_error;
	}

	public function act_ajax() {

		unset( $_POST['action'] );

		$this->act( true );

		$response = array(
			'success' => $this->success,
			'data' => $this->get_posted_data()
		);

		if( true === $this->success ) {
			$form_opts = mc4wp_get_form_settings( $this->get_form_id(), true );
			$response['redirect'] = ( empty( $form_opts['redirect'] ) ) ? false : $form_opts['redirect'];
			$response['hide_form'] = ( (bool) $form_opts['hide_after_success'] );
		} else {
			$response['error'] = array(
				'type' => $this->get_error_code(),
				'mailchimp_error' => $this->get_mailchimp_error(),
				'show' => current_user_can('manage_options')
			);
		}

		// clear output, some plugins might have thrown errors by now.
		if( ob_get_level() > 0 ) {
			ob_end_clean();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		die();

	}

	/**
	 * Acts on the submitted data
	 * - Validates internal fields
	 * - Formats email and merge_vars
	 * - Sends off the subscribe request to MailChimp
	 * - Returns state
	 *
	 * @param bool $doing_ajax
	 *
	 * @return bool True on success, false on failure.
	 */
	public function act( $doing_ajax = false ) {

		// store number of submitted form
		$this->form_instance_number = absint( $_POST['_mc4wp_form_instance'] );
		$this->form_id = absint( $_POST['_mc4wp_form_id'] );
		$this->form_options = mc4wp_get_form_settings( $this->get_form_id(), true );

		// validate form nonce
		if ( ! isset( $_POST['_mc4wp_form_nonce'] ) || ! wp_verify_nonce( $_POST['_mc4wp_form_nonce'], '_mc4wp_form_nonce' ) ) {
			$this->error_code = 'invalid_nonce';
			return false;
		}

		// ensure honeypot was not filed
		if ( isset( $_POST['_mc4wp_required_but_not_really'] ) && ! empty( $_POST['_mc4wp_required_but_not_really'] ) ) {
			$this->error_code = 'spam';
			return false;
		}

		// check if captcha was present and valid
		if( isset( $_POST['_mc4wp_has_captcha'] ) && $_POST['_mc4wp_has_captcha'] == 1 && function_exists( 'cptch_check_custom_form' ) && cptch_check_custom_form() !== true ) {
			$this->error_code = 'invalid_captcha';
			return false;
		}

		/**
		 * @filter mc4wp_valid_form_request
		 *
		 * Use this to perform custom form validation.
		 * Return true if the form is valid or an error string if it isn't.
		 * Use the `mc4wp_form_messages` filter to register custom error messages.
		 */
		$valid_form_request = apply_filters( 'mc4wp_valid_form_request', true );
		if( $valid_form_request !== true ) {
			$this->error_code = $valid_form_request;
			return false;
		}

		// get entered form data (sanitized)
		$this->sanitize_form_data();
		$data = $this->get_posted_data();

		// validate email
		if( ! isset( $data['EMAIL'] ) || ! is_email( $data['EMAIL'] ) ) {
			$this->error_code = 'invalid_email';
			return false;
		}

		// setup merge_vars array
		$merge_vars = $data;

		// take email out of $data array, use the rest as merge_vars
		$email = $merge_vars['EMAIL'];
		unset( $merge_vars['EMAIL'] );

		// validate groupings
		if( isset( $data['GROUPINGS'] ) && is_array( $data['GROUPINGS'] ) ) {
			$merge_vars['GROUPINGS'] = $this->format_groupings_data( $data['GROUPINGS'] );
		}

		// subscribe the given email / data combination
		$this->success = $this->subscribe( $email, $merge_vars );

		// bail early if we're doing AJAX
		if( $doing_ajax ) {
			return $this->success;
		}

		// do stuff on success, non-AJAX only
		if( true === $this->success ) {

			// check if we want to redirect the visitor
			if ( ! empty( $this->form_options['redirect'] ) ) {
				wp_redirect( $this->form_options['redirect'] );
				exit;
			}

			// return true on success
			return true;
		}

		// subscription failed

		/**
		 * @action mc4wp_form_error_{ERROR_CODE}
		 *
		 * Use to hook into various sign-up errors. Hook names are:
		 *
		 * - mc4wp_form_error_error                     General errors
		 * - mc4wp_form_error_invalid_email             Invalid email address
		 * - mc4wp_form_error_already_subscribed        Email is already on selected list(s)
		 * - mc4wp_form_error_required_field_missing    One or more required fields are missing
		 * - mc4wp_form_error_no_lists_selected         No MailChimp lists were selected
		 *
		 * @param   int     $form_id        The ID of the submitted form
		 * @param   string  $email          The email of the subscriber
		 * @param   array   $merge_vars     Additional list fields, like FNAME etc (if any)
		 */
		do_action( 'mc4wp_form_error_' . $this->get_error_code(), $this->get_form_id(), $email, $merge_vars );

		// return false on failure
		return false;
	}

	/**
	 * Format GROUPINGS data according to the MailChimp API requirements
	 *
	 * @param $data
	 *
	 * @return array
	 */
	private function format_groupings_data( $data ) {

		$sanitized_data = array();

		foreach ( $data as $grouping_id_or_name => $groups ) {

			$grouping = array();

			// set key: grouping id or name
			if ( is_numeric( $grouping_id_or_name ) ) {
				$grouping['id'] = $grouping_id_or_name;
			} else {
				$grouping['name'] = $grouping_id_or_name;
			}

			// comma separated list should become an array
			if( ! is_array( $groups ) ) {
				$groups = explode( ',', $groups );
			}

			$grouping['groups'] = $groups;

			// add grouping to array
			$sanitized_data[] = $grouping;
		}

		return $sanitized_data;
	}

	/**
	 * Get and sanitize posted form data
	 *
	 * - Strips internal MailChimp for WP variables from the posted data array
	 * - Strips ignored fields
	 * - Converts keys to uppercase
	 * - Trims scalar values and strips slashes
	 *
	 * @return array
	 */
	private function sanitize_form_data() {

		$data = array();

		// Ignore those fields, we don't need them
		$ignored_fields = array( 'CPTCH_NUMBER', 'CNTCTFRM_CONTACT_ACTION', 'CPTCH_RESULT', 'CPTCH_TIME' );

		foreach( $_POST as $key => $value ) {

			// Sanitize key
			$key = trim( strtoupper( $key ) );

			// Skip field if it starts with _ or if it's in ignored_fields array
			if( $key[0] === '_' || in_array( strtoupper( $key ), $ignored_fields ) ) {
				continue;
			}

			// Sanitize value
			$value = ( is_scalar( $value ) ) ? sanitize_text_field( $value ) : $value;

			// Add value to array
			$data[ $key ] = $value;
		}

		// strip slashes on everything
		$data = stripslashes_deep( $data );

		// store data somewhere safe
		$this->posted_data = $data;
	}

	/**
	 * Subscribes the given email and additional list fields
	 *
	 * - Guesses FNAME and LNAME, if not set but NAME is.
	 * - Adds OPTIN_IP field
	 * - Validates merge_vars according to selected list(s) requirements
	 * - Checks if a list was selected or given in form
	 *
	 * @param string $email
	 * @param array $merge_vars
	 *
	 * @return bool
	 */
	private function subscribe( $email, $merge_vars = array() ) {

		// Try to guess FNAME and LNAME if they are not given, but NAME is
		if( isset( $merge_vars['NAME'] ) && !isset( $merge_vars['FNAME'] ) && ! isset( $merge_vars['LNAME'] ) ) {

			$strpos = strpos($merge_vars['NAME'], ' ');
			if( $strpos !== false ) {
				$merge_vars['FNAME'] = substr($merge_vars['NAME'], 0, $strpos);
				$merge_vars['LNAME'] = substr($merge_vars['NAME'], $strpos);
			} else {
				$merge_vars['FNAME'] = $merge_vars['NAME'];
			}
		}

		// set ip address
		if( ! isset( $merge_vars['OPTIN_IP'] ) && isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$merge_vars['OPTIN_IP'] = $_SERVER['REMOTE_ADDR'];
		}

		$api = mc4wp_get_api();

		// get lists to subscribe to
		$lists = $this->get_lists();

		if ( empty( $lists ) ) {
			$this->error_code = 'no_lists_selected';
			return false;
		}

		// validate fields according to mailchimp list field types
		$merge_vars = $this->validate_merge_vars( $merge_vars );
		if( false === $merge_vars ) {
			return false;
		}

		do_action( 'mc4wp_before_subscribe', $email, $merge_vars, $this->get_form_id() );

		$result = false;
		$email_type = $this->get_email_type();

		foreach ( $lists as $list_id ) {
			// allow plugins to alter merge vars for each individual list
			$list_merge_vars = apply_filters( 'mc4wp_merge_vars', $merge_vars, $this->get_form_id(), $list_id );

			// send a subscribe request to MailChimp for each list
			$result = $api->subscribe( $list_id, $email, $list_merge_vars, $email_type, $this->form_options['double_optin'], $this->form_options['update_existing'], $this->form_options['replace_interests'], $this->form_options['send_welcome'] );
		}

		do_action( 'mc4wp_after_subscribe', $email, $merge_vars, $this->get_form_id(), $result );

		if ( $result !== true ) {
			// subscribe request failed, store error.
			$this->success = false;
			$this->error_code = $result;
			$this->mailchimp_error = $api->get_error_message();
			return false;
		}

		// subscription succeeded

		// store user email in a cookie
		$this->set_email_cookie( $email );

		// send an email copy if that is desired
		if( $this->form_options['send_email_copy'] == true ) {
			$this->send_email( $email, $merge_vars );
		}

		/**
		 * @deprecated Don't use, will be removed in v2.0
		 * TODO: remove this
		 */
		$from_url = ( isset( $_SERVER['HTTP_REFERER'] ) ) ? $_SERVER['HTTP_REFERER'] : '';
		do_action( 'mc4wp_subscribe_form', $email, $lists, $this->get_form_id(), $merge_vars, $from_url );

		// Store success result
		$this->success = true;

		return true;
	}

	/**
	 * Send an email with a subscription summary to a given email address
	 *
	 * @param string $email
	 * @param array $merge_vars
	 */
	private function send_email( $email, array $merge_vars = array() ) {

		$form_id = $this->get_form_id();

		// bail if receiver is empty
		if( '' === $this->form_options['email_copy_receiver'] ) {
			return;
		}

		// email receiver
		$to = explode( ',', str_replace( ' ', '', $this->form_options['email_copy_receiver'] ) );

		$form = get_post( $form_id );
		$form_name = $form->post_title;

		// email subject
		$subject = "New MailChimp Sign-Up - ". get_bloginfo('name');

		// build email message
		ob_start();

		?>
		<h3>MailChimp for WordPress: <?php _e( 'New Sign-Up', 'mailchimp-for-wp' ); ?></h3>
		<p><?php printf( __( '<strong>%s</strong> signed-up at %s on %s using the form "%s".', 'mailchimp-for-wp' ), $email, date( 'H:i' ), date( 'd/m/Y' ), $form_name ); ?></p>
		<table cellspacing="0" cellpadding="10" border="0" style="border:1px solid #bbb;">
			<tbody>
			<tr>
				<td><strong>EMAIL</strong></td>
				<td><?php echo $email; ?></td>
			</tr>
			<?php
			foreach( $merge_vars as $field_name => $value ) {

				if( $field_name === 'GROUPINGS' ) {
					foreach( $value as $grouping ) {

						$grouping_name = isset( $grouping['name'] ) ? $grouping['name'] : $grouping['id'];
						$groups = implode( ', ', $grouping['groups'] );
						?>
						<tr>
							<td><strong>GROUPING:</strong> <?php echo $grouping_name; ?></td>
							<td><?php echo $groups; ?></td>
						</tr>
					<?php
					}
				} else {

					if( is_array( $value ) ) {
						$value = implode( ", ", $value );
					}

					?>
					<tr>
						<td><strong><?php echo $field_name; ?></strong></td>
						<td><?php echo $value; ?></td>
					</tr>
				<?php
				}
			}
			?>
			</tbody>
		</table>

		<?php  if( $this->form_options['double_optin'] ) { ?>
			<p style="color:#666;"><?php printf( __( 'Note that you\'ve enabled double opt-in for the "%s" form. The user won\'t be added to the selected MailChimp lists until they confirm their email address.', 'mailchimp-for-wp' ), $form_name ); ?></p>
		<?php } ?>

		<p style="color:#666;"><?php _e( 'This email was auto-sent by the MailChimp for WordPress plugin.', 'mailchimp-for-wp' ); ?></p>
		<?php
		$message = ob_get_contents();
		ob_end_clean();

		/**
		 * @filter mc4wp_email_summary_receiver
		 * @expects string|array String or array of emails
		 * @param   int     $form_id        The ID of the submitted form
		 * @param   string  $email          The email of the subscriber
		 * @param   array   $merge_vars     Additional list fields, like FNAME etc (if any)
		 *
		 * Use to set email addresses to send the email summary to
		 */
		$receivers = apply_filters( 'mc4wp_email_summary_receiver', $to, $form_id, $email, $merge_vars );

		/**
		 * @filter mc4wp_email_summary_subject
		 * @expects string|array String or array of emails
		 * @param   int     $form_id        The ID of the submitted form
		 * @param   string  $email          The email of the subscriber
		 * @param   array   $merge_vars     Additional list fields, like FNAME etc (if any)
		 *
		 * Use to set subject of email summaries
		 */
		$subject = apply_filters( 'mc4wp_email_summary_subject', $subject, $form_id, $email, $merge_vars );

		/**
		 * @filter mc4wp_email_summary_message
		 * @expects string|array String or array of emails
		 * @param   int     $form_id        The ID of the submitted form
		 * @param   string  $email          The email of the subscriber
		 * @param   array   $merge_vars     Additional list fields, like FNAME etc (if any)
		 *
		 * Use to set or customize message of email summaries
		 */
		$message = apply_filters( 'mc4wp_email_summary_message', $message, $form_id, $email, $merge_vars );


		// send email
		wp_mail( $receivers, $subject, $message, "Content-Type: text/html" );
	}

	/**
	 * Validates the posted fields against merge_vars of selected list(s)
	 *
	 * @param array $data
	 *
	 * @return array|boolean Array of data on success, false on error
	 */
	private function validate_merge_vars( array $data ) {

		$list_ids = $this->get_lists();
		$mailchimp = new MC4WP_MailChimp();

		foreach( $list_ids as $list_id ) {

			$list = $mailchimp->get_list( $list_id, false, true );

			// make sure list was found
			if( ! is_object( $list ) ) {
				continue;
			}

			// loop through list fields
			foreach( $list->merge_vars as $merge_var ) {

				// skip email field, it's validated elsewhere
				if( $merge_var->tag === 'EMAIL' ) {
					continue;
				}

				$posted_value = ( isset( $data[ $merge_var->tag ] ) && '' !== $data[ $merge_var->tag ] ) ? $data[ $merge_var->tag ] : '';

				// check if required field is given
				if( $merge_var->req && '' === $posted_value ) {
					$this->error_code = 'required_field_missing';
					return false;
				}

				// format birthday fields in MM/DD format, required by MailChimp
				if( $merge_var->field_type === 'birthday' && $posted_value !== '' ) {
					$data[ $merge_var->tag ] = date( 'm/d', strtotime( $data[ $merge_var->tag ] ) );
				}

				// format address fields
				if( $merge_var->field_type === 'address' && $posted_value !== '' ) {

					if( ! isset( $posted_value['addr1'] ) ) {

						// addr1, addr2, city, state, zip, country
						$address_pieces = explode( ',', $posted_value );

						// try to fill it.... this is a long shot
						$data[ $merge_var->tag ] = array(
							'addr1' => $address_pieces[0],
							'city'  => ( isset( $address_pieces[1] ) ) ?   $address_pieces[1] : '',
							'state' => ( isset( $address_pieces[2] ) ) ?   $address_pieces[2] : '',
							'zip'   => ( isset( $address_pieces[3] ) ) ?   $address_pieces[3] : ''
						);

					} else {
						// form contains the necessary fields already: perfection
						$data[ $merge_var->tag ] = $posted_value;
					}
				}


			}
		}

		return $data;
	}

	/**
	 * Gets the email_type
	 *
	 * @return string The email type to use for subscription coming from this form
	 */
	private function get_email_type( ) {

		$email_type = 'html';

		// get email type from form
		if( isset( $_POST['_mc4wp_email_type'] ) ) {
			$email_type = trim( $_POST['_mc4wp_email_type'] );
		}

		// allow plugins to override this email type
		$email_type = apply_filters( 'mc4wp_email_type', $email_type, $this->get_form_id() );

		return $email_type;
	}

	/**
	 * Get MailChimp List(s) to subscribe to
	 *
	 * @return array Array of selected MailChimp lists
	 */
	private function get_lists() {

		$lists = $this->form_options['lists'];

		// get lists from form, if set.
		if( isset( $_POST['_mc4wp_lists'] ) && ! empty( $_POST['_mc4wp_lists'] ) ) {

			$lists = $_POST['_mc4wp_lists'];

			// make sure lists is an array
			if( ! is_array( $lists ) ) {
				$lists = array( trim( $lists ) );
			}

		}

		// allow plugins to alter the lists to subscribe to
		$lists = apply_filters( 'mc4wp_lists', $lists, $this->get_form_id() );

		return $lists;
	}

	/**
	 * Stores the given email in a cookie for 30 days
	 *
	 * @param string $email
	 */
	private function set_email_cookie( $email ) {
		setcookie( 'mc4wp_email', $email, strtotime( '+30 days' ), '/' );
	}

}