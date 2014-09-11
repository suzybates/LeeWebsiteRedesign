<?php
/**
 *  Widget Frontend Output
 *
 * @description: The frontend widget form output
 * @since      : 1.0
 * @created    : 4/28/13
 */

//Initialize s2 classes
//We are using some of the s2 class functions here
global $mysubscribe2;
$s2Class = $mysubscribe2;
$s2Class->s2init();
$s2Class->load_strings();
$options = get_option( 's2w_widget_settings' );
$cssDisable = ! isset( $options["s2w_widget_disable_css"] ) ? '' : $options["s2w_widget_disable_css"];
global $wpdb, $user_ID, $user_email;

/**
 * Widget Themes
 *
 * Enqueue the appropriate style according to the selection
 */

//Only enqueue styles if CSS option is not disabled
if ( $cssDisable != '1' ) {

	//Theme styles
	if ( $widget_style === 'Minimal Light' || $widget_style === 'Minimal Dark' ) {
		//enqueue theme style
		wp_register_style( 's2w_widget_style_minimal', plugins_url( '/includes/style/s2w-theme-minimal.css', dirname( dirname( __FILE__ ) ) ) );
		wp_enqueue_style( 's2w_widget_style_minimal' );

	}
	if ( $widget_style === 'Shadow Light' || $widget_style === 'Shadow Dark' ) {
		wp_register_style( 's2w_widget_style_shadow', plugins_url( '/includes/style/s2w-theme-shadow.css', dirname( dirname( __FILE__ ) ) ) );
		wp_enqueue_style( 's2w_widget_style_shadow' );
	}

	//enqueue common style
	wp_register_style( 's2w_widget_common', plugins_url( '/includes/style/s2w-common.css', dirname( dirname( __FILE__ ) ) ) );
	wp_enqueue_style( 's2w_widget_common' );

}

/**
 * Set Widget Wrap Class
 * Add the width from $style to the class within the $before widget variable
 * http://wordpress.stackexchange.com/questions/18942/add-class-to-before-widget-from-within-a-custom-widget
 */

//Widget Style
$style = "s2w-" . sanitize_title( $widget_style ) . "-style";

//Output Custom DIV class
if ( ! empty( $div ) ) {
	$style = $style . ' ' . $div;
}

// no 'class' attribute - add one with the value of width
if ( ! empty( $before_widget ) && strpos( $before_widget, 'class' ) === false ) {
	$before_widget = str_replace( '>', 'class="' . $style . '"', $before_widget );
} // there is 'class' attribute - append width value to it
elseif ( ! empty( $before_widget ) && strpos( $before_widget, 'class' ) !== false ) {
	$before_widget = str_replace( 'class="', 'class="' . $style . ' ', $before_widget );
} //no 'before_widget' at all so wrap widget with div
else {
	$before_widget = '<div class="s2w-widget-pro">';
	$before_widget = str_replace( 'class="', 'class="' . $style . ' ', $before_widget );
}
//output before_widget
if ( isset( $before_widget ) ) {
	echo $before_widget;
}

// if the title is set & the user hasn't disabled title output
if ( ! empty( $title ) ) {
	/* Add class to before_widget from within a custom widget
 http://wordpress.stackexchange.com/questions/18942/add-class-to-before-widget-from-within-a-custom-widget
 */
	// no 'class' attribute - add one with the value of width
	if ( ! empty( $before_title ) && strpos( $before_title, 'class' ) === false ) {
		$before_title = str_replace( '>', ' class="s2w-widget-title">', $before_title );
	} //widget title has 'class' attribute
	elseif ( ! empty( $before_title ) && strpos( $before_title, 'class' ) !== false ) {
		$before_title = str_replace( 'class="', 'class="s2w-widget-title ', $before_title );
	} //no 'title' at all so wrap widget with div
	else {
		$before_title = '<h3 class="">';
		$before_title = str_replace( 'class="', 'class="s2w-widget-title ', $before_title );
	}
	$after_title = empty( $after_title ) ? '</h3>' : $after_title;

	if ( $hide_widget_title != 'hide' ) {
		echo $before_title . $title . $after_title;
	}


}

//See if the user is logged in first
get_currentuserinfo();
if ( $user_ID && S2W_DEBUG !== true ) {

	//Use s2's built in strings to handle this only if not debugging
	echo $s2Class->profile;

} //User is not logged in so proceed with subscribe form output
else {

	$subscribe_email = ! isset( $_POST['email'] ) ? '' : $s2Class->sanitize_email( $_POST['email'] );
	$check           = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM $wpdb->users WHERE user_email = %s", $subscribe_email ) );

	//does the supplied email belong to a registered user?
	if ( $check != '' ) {

		// this is a registered email
		echo $s2Class->please_log_in;

	} //Display Success Message for signed up users (non-AJAX submissions)
	elseif ( isset( $_POST["subscribe"] ) ) {


		//email not in subscribe db so subscribe them
		if ( $s2Class->is_public( $subscribe_email ) != '1' ) {

			echo "<div class='s2w-alert s2w-success'>" . ( ! empty( $success_message ) ? $success_message : __( "Thank you for subscribing, please check your email to confirm your subscription.", 's2w' ) ) . "</div>";

		} // lets see if they've tried to subscribe previously
		else {

			echo "<div class='s2w-alert s2w-error'>" . ( ! empty( $already_registered_message ) ? $already_registered_message : __( "This email address has already been subscribed.", 's2w' ) ) . "</div>";

		}


	} //User is trying to unsubscribe from this site
	elseif ( isset( $_POST["unsubscribe"] ) ) {

		echo "<div class='s2w-alert s2w-success s2w-unsubscribe'>" . ( ! empty( $unsubscribe_message ) ? $unsubscribe_message : __( "Subscription removal request received. Please check your email to confirm your request.", 's2w' ) ) . "</div>";

	} //Show Widget Form (no action from user yet)
	else {

		//Determine where this form will post data to
		if ( ! empty( $postto ) && $postto !== 'home' ) {
			$postPermalink = get_permalink( $postto );
		} elseif ( $postto == 'home' ) {
			$postPermalink = get_bloginfo( 'wpurl' );
		} ?>

		<div class="s2w-widget-pro" data-validation-error="<?php echo $validation_message; ?>">

			<?php
			//widget pre-content
			if ( ! empty( $widgetprecontent ) ) {
				echo wpautop( $widgetprecontent );
			} ?>

			<form action="<?php echo $postPermalink; ?>" method="post" class="s2w-form">

				<?php
				//Pre widget form action
				do_action( 'subscribe2_widget_pro_form_fields_before' ); ?>

				<input type="hidden" value="<?php echo s2w_get_IP(); ?>" name="ip">

				<label for="s2email"><?php _e( 'Your email:', 's2w' ); ?></label>

				<input type="email" onblur="if (this.value == '') {this.value = 'Enter email address...';}" onfocus="if (this.value == 'Enter email address...') {this.value = '';}" size="20" value="Enter email address..." id="s2email" class="s2w-email s2w-input-field" name="email">

				<div class="s2w-button-wrap<?php if ( $hide_unsubscribe_button == 'show' && $hide_subscribe_button != 'none' ) {
					echo ' s2w-two-btns';
				} ?>">
					<?php if ( $hide_subscribe_button != 'none' ) { ?>
						<input type="submit" value="Subscribe" name="subscribe" class="s2w-widget-submit-btn s2w-subscribe<?php
						//Button color class output
						if ( $subscribe_btn_color != '' ) {
							echo ' s2w-btn ' . $subscribe_btn_color;
						} ?>">
					<?php
					}

					/**
					 * Show Unsubscribe button if checked
					 */
					if ( $hide_unsubscribe_button == 'show' ) {
						?>
						<input type="submit" value="Unsubscribe" name="unsubscribe" class="s2w-widget-submit-btn s2w-unsubscribe<?php
						//Button color class output
						if ( $unsubscribe_btn_color != '' ) {
							echo ' s2w-btn ' . $unsubscribe_btn_color;
						} ?>">

					<?php } ?>
				</div>


				<?php if ( $disable_antispam !== 'no' ) { ?>
					<div style="display:none !important">
						<label for="name">Leave Blank:</label>
						<input type="text" class="s2w-name" name="name" />
						<label for="uri">Don't Change:</label>
						<input type="text" class="s2w-uri" name="uri" value="http://" />
					</div>
				<?php } ?>

			</form>

			<?php
			//Post Content
			if ( ! empty( $widgetpostcontent ) ) {
				echo wpautop( $widgetpostcontent );
			}
			?>

			<?php
			//Post widget form action
			do_action( 'subscribe2_widget_pro_form_fields_after' ); ?>

		</div>

	<?php
	} //end check for $_POST submission

} //endif user logged in

if ( isset( $after_widget ) ) {
	echo $after_widget;
}