<?php
/**
 *  Widget Form
 *
 * @description: The backend widget form output
 * @since      : 1.0
 * @created    : 4/28/13
 */
global $mysubscribe2;
?>

<p>
	<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Widget Title', 's2w' ); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</p><!-- /Widget Title -->


<h4 class="s2w-toggler"><?php _e( 'Display Options:', 'ywp' ); ?><span></span></h4>

<div class="display-options toggle-item">


	<p>
		<label for="<?php echo $this->get_field_id( 'widget_style' ); ?>"><?php _e( 'Widget Theme' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'A widget theme will assign preset styles to the widget to easily integrate with your website\'s unique design.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<select name="<?php echo $this->get_field_name( 'widget_style' ); ?>" id="<?php echo $this->get_field_id( 'widget_style' ); ?>" class="widefat profield">
			<?php
			$options = array(
				__( 'Bare Bones', 's2w' ),
				__( 'Minimal Light', 's2w' ),
				__( 'Minimal Dark', 's2w' ),
				__( 'Shadow Light', 's2w' ),
				__( 'Shadow Dark', 's2w' )
			);
			//Counter for Option Values
			$counter = 0;

			foreach ( $options as $option ) {
				echo '<option value="' . $option . '" id="' . $option . '"', $widget_style == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				$counter ++;
			}
			?>
		</select>
	</p>
	<!-- Widget Theme -->

	<p>
		<label for="<?php echo $this->get_field_id( 'subscribe_btn_color' ); ?>"><?php _e( 'Subscribe Button Color' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Use this option to customize the color of the subscribe submit button to suit your needs.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<select name="<?php echo $this->get_field_name( 'subscribe_btn_color' ); ?>" id="<?php echo $this->get_field_id( 'subscribe_btn_color' ); ?>" class="widefat profield">
			<?php
			$options = array(

				0 => array(
					__( 'None (uses your theme style)', 's2w' ),
					'#FFF', //option bg color
					'#000', //option text color
					''
				),
				1 => array(
					__( 'White Bordered', 's2w' ),
					'#FFF', //option bg color
					'#000', //option text color
					's2w-btn-default'
				),
				2 => array(
					__( 'Blue Primary', 's2w' ),
					'#428BCA',
					'#FFF',
					's2w-btn-primary'
				),
				3 => array(
					__( 'Green Success', 's2w' ),
					'#5CB85C',
					'#FFF',
					's2w-btn-success'
				),
				4 => array(
					__( 'Info Blue', 's2w' ),
					'#5BC0DE',
					'#FFF',
					's2w-btn-info'
				),
				5 => array(
					__( 'Warning Orange', 's2w' ),
					'#F0AD4E',
					'#FFF',
					's2w-btn-warning'
				),
				6 => array(
					__( 'Danger Red', 's2w' ),
					'#D9534F',
					'#FFF',
					's2w-btn-danger'
				)
			);
			//Counter for Option Values
			$counter = 0;
			foreach ( $options as $option ) {

				echo '<option style="background-color:' . $option[1] . ';color:' . $option[2] . ';" value="' . $option[3] . '" id="' . $option[3] . '"', $subscribe_btn_color == $option[3] ? ' selected="selected"' : '', '>', $option[0], '</option>';
				$counter ++;
			}
			?>
		</select>
	</p>
	<!-- Subscribe Btn Color -->

	<p>
		<label for="<?php echo $this->get_field_id( 'unsubscribe_btn_color' ); ?>"><?php _e( 'Submit Button Color' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Use this option to customize the color of the unsubscribe submit button to suit your needs.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<select name="<?php echo $this->get_field_name( 'unsubscribe_btn_color' ); ?>" id="<?php echo $this->get_field_id( 'unsubscribe_btn_color' ); ?>" class="widefat profield">
			<?php
			$options = array(

				0 => array(
					__( 'None (uses your theme style)', 's2w' ),
					'#FFF', //option bg color
					'#000', //option text color
					''
				),
				1 => array(
					__( 'White Bordered', 's2w' ),
					'#FFF', //option bg color
					'#000', //option text color
					's2w-btn-default'
				),
				2 => array(
					__( 'Blue Primary', 's2w' ),
					'#428BCA',
					'#FFF',
					's2w-btn-primary'
				),
				3 => array(
					__( 'Green Success', 's2w' ),
					'#5CB85C',
					'#FFF',
					's2w-btn-success'
				),
				4 => array(
					__( 'Info Blue', 's2w' ),
					'#5BC0DE',
					'#FFF',
					's2w-btn-info'
				),
				5 => array(
					__( 'Warning Orange', 's2w' ),
					'#F0AD4E',
					'#FFF',
					's2w-btn-warning'
				),
				6 => array(
					__( 'Danger Red', 's2w' ),
					'#D9534F',
					'#FFF',
					's2w-btn-danger'
				)
			);
			//Counter for Option Values
			$counter = 0;
			foreach ( $options as $option ) {

				echo '<option style="background-color:' . $option[1] . ';color:' . $option[2] . ';" value="' . $option[3] . '" id="' . $option[3] . '"', $unsubscribe_btn_color == $option[3] ? ' selected="selected"' : '', '>', $option[0], '</option>';
				$counter ++;
			}
			?>
		</select>
	</p>
	<!-- Unsubscribe Btn Color -->


	<p>
		<label for="<?php echo $this->get_field_name( 'widgetprecontent' ); ?>"><?php _e( 'Pre-Content', 's2w' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This content will appear BEFORE the opt-in form. HTML is allowed.  Note, content is run through wpautop function prior to output.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<textarea class="widefat" id="<?php echo $this->get_field_name( 'widgetprecontent' ); ?>" name="<?php echo $this->get_field_name( 'widgetprecontent' ); ?>" rows="2" cols="25"><?php echo $widgetprecontent; ?></textarea>
	</p><!-- /Pre-Content Size -->


	<p>
		<label for="<?php echo $this->get_field_name( 'widgetpostcontent' ); ?>"><?php _e( 'Post-Content', 's2w' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This content will appear AFTER the opt-in form. Again, HTML is allowed. Note, content is run through wpautop function prior to output.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id( 'widgetpostcontent' ); ?>" name="<?php echo $this->get_field_name( 'widgetpostcontent' ); ?>" rows="2" cols="25"><?php echo $widgetpostcontent; ?></textarea>
	</p><!-- /Pre-Content Size -->

	<p>
		<input id="<?php echo $this->get_field_id( 'hide_subscribe_button' ); ?>" name="<?php echo $this->get_field_name( 'hide_subscribe_button' ); ?>" type="checkbox" value="none" <?php echo checked( 'none', $hide_subscribe_button, false ); ?> />
		<label for="<?php echo $this->get_field_id( 'hide_subscribe_button' ); ?>"><?php _e( 'Hide Subscribe button', 's2w' ); ?>
			<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Hide the subscribe button on the frontend. Helpful if you would like the form to be an <strong>unsubscribe-only</strong> form. Be sure you have the <em>Show Unsubscribe Button</em> option checked if this option is enabled.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p>

	<p>
		<input id="<?php echo $this->get_field_id( 'hide_unsubscribe_button' ); ?>" name="<?php echo $this->get_field_name( 'hide_unsubscribe_button' ); ?>" type="checkbox" value="show" <?php echo checked( 'show', $hide_unsubscribe_button, false ); ?>/>
		<label for="<?php echo $this->get_field_id( 'hide_unsubscribe_button' ); ?>"><?php _e( 'Show Unsubscribe button', 's2w' ); ?>
			<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Display the unsubscribe button.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p><!-- /.display options -->


	<p>
		<input id="<?php echo $this->get_field_id( 'hide_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_widget_title' ); ?>" type="checkbox" value="hide" <?php echo checked( 'hide', $hide_widget_title, false ); ?>/>
		<label for="<?php echo $this->get_field_id( 'hide_widget_title' ); ?>"><?php _e( 'Hide widget title', 's2w' ); ?>
			<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Hide the widget heading. This option may be useful for some themes.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p><!-- /.display options -->


</div><!-- /.display-options -->


<h4 class="s2w-toggler"><?php _e( 'Submission Options:', 'ywp' ); ?><span></span></h4>

<div class="submission-options toggle-item">


	<p>
		<label for="<?php echo $this->get_field_name( 'postto' ); ?>"><?php _e( 'Post form content to page', 's2' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is the page the opt-in form will post the form data and redirect the user to for subscriptions.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'postto' ); ?>" name="<?php echo $this->get_field_name( 'postto' ); ?>">
			<option value="<?php echo $mysubscribe2->subscribe2_options['s2page']; ?>"><?php _e( 'Use Subscribe2 default', 's2w' ); ?></option>
			<option value="home" <?php if ( $postto === 'home' ) {
				echo 'selected="selected"';
			} ?>>
				<?php _e( 'Use Home Page', 's2w' ); ?></option>
			<?php $mysubscribe2->pages_dropdown( $postto ); ?>
		</select>
	</p>
	<!-- /Post form content to page -->

	<p class="s2w-ajax-alert">
		<em>Advanced AJAX form submission is only available in the
			<a href="<?php echo S2W_WIDGET_UPGRADE_LINK; ?>" target="_blank" class="new-window" title="<?php _e( 'Get immediate access after purchase to additional features, priority support and auto updates.', 'ywp' ); ?>">Premium version</a> of Subscribe2 Widget Pro.</em><img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'AJAX provides a more seamless user experience by elimating the need for page reload.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_name( 'validation_message' ); ?>"><?php _e( 'Validation message', 's2w' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is the message that appears when a user submits the newsletter form with incorrect data.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<textarea class="widefat" id="<?php echo $this->get_field_name( 'validation_message' ); ?>" name="<?php echo $this->get_field_name( 'validation_message' ); ?>" rows="2" cols="25"><?php echo $validation_message; ?></textarea>
	</p><!-- /validation_message -->

	<p>
		<label for="<?php echo $this->get_field_name( 'success_message' ); ?>"><?php _e( 'Success message', 's2w' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is the message that appears upon successful form submit.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<textarea class="widefat" id="<?php echo $this->get_field_name( 'success_message' ); ?>" name="<?php echo $this->get_field_name( 'success_message' ); ?>" rows="2" cols="25"><?php echo $success_message; ?></textarea>
	</p><!-- /success message -->

	<p>
		<label for="<?php echo $this->get_field_name( 'unsubscribe_message' ); ?>"><?php _e( 'Unsubscribe message', 's2w' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is the message that appears for users who have elected to unsubscribe.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<textarea class="widefat" id="<?php echo $this->get_field_name( 'unsubscribe_message' ); ?>" name="<?php echo $this->get_field_name( 'unsubscribe_message' ); ?>" rows="2" cols="25"><?php echo $unsubscribe_message; ?></textarea>
	</p>
	<!-- /unsubscribe message -->

	<p>
		<label for="<?php echo $this->get_field_name( 'already_registered_message' ); ?>"><?php _e( 'Already registered message', 's2w' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is the message that appears for users who have already subscribe and are attempting to resubscribe.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<textarea class="widefat" id="<?php echo $this->get_field_name( 'already_registered_message' ); ?>" name="<?php echo $this->get_field_name( 'already_registered_message' ); ?>" rows="2" cols="25"><?php echo $already_registered_message; ?></textarea>
	</p>
	<!-- /already registered message -->


</div><!--/.submission-options -->


<h4 class="s2w-toggler"><?php _e( 'Advanced Options:', 'ywp' ); ?><span></span></h4>

<div class="advanced-options toggle-item">


	<p>
		<label for="<?php echo $this->get_field_name( 'div' ); ?>"><?php echo __( 'Custom container class', 's2w' ); ?>:<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'A custom container class allows you to add additional styles to the frontend widget using CSS.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'div' ); ?>" name="<?php echo $this->get_field_name( 'div' ); ?>" type="text" value="<?php echo $div; ?>" />
	</p><!-- /Custom Container Class -->


</div><!-- /.advanced-options -->


<div class="pro-option">
	<p>Want more features? Get the
		<a href="<?php echo S2W_WIDGET_UPGRADE_LINK; ?>" target="_blank" class="new-window" title="<?php _e( 'Get immediate access after purchase to additional features, priority support and auto updates.', 'ywp' ); ?>"><?php _e( 'Premium Version', 's2w' ); ?></a>
	</p>
</div>