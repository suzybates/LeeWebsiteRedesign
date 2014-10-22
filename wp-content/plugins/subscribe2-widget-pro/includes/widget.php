<?php
/**
 *  Subscribe2 Widget Class
 *
 * @description: The class that is responsible for the Subscribe2 Widget
 * @since      : 1.0
 * @created    : 4/27/13
 */

class Subscribe2_Widget_Pro extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'subscribe2_widget', // Base ID
			'Subscribe2 Widget Pro', // Name
			array( 'description' => __( 'Customize and display a Subscribe2 widget anywhere on your site.', 's2w' ), ) // Args
		);

		//Only Load scripts when widget or shortcode is active
		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_enqueue_scripts', array( &$this, 'add_s2w_widget_frontend_scripts' ) );

		}


	}

	/**
	 * Adds Subscribe2 Widget Pro Scripts
	 */
	public function add_s2w_widget_frontend_scripts() {

		$options  = get_option( 's2w_widget_settings' );
		$s2wJSurl = plugins_url( '/includes/js/subscribe2-widget-pro.js', dirname( __FILE__ ) );

		//Subscribe2 Widget Pro JS
		wp_register_script( 's2w-widget-js', $s2wJSurl, array( 'jquery' ) );
		wp_enqueue_script( 's2w-widget-js' );

	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {

		extract( $args );
		$defaultTitle = __( 'Subscribe to ' . get_bloginfo( 'name' ) . '', 'ywp' );

		//Subscribe2 Widget Options
		$title                      = empty( $instance['title'] ) ? __( $defaultTitle, 'subscribe2_widget_pro' ) : $instance['title'];
		$div                        = $instance['div'];
		$widget_style               = empty( $instance['widget_style'] ) ? '' : $instance['widget_style'];
		$subscribe_btn_color        = empty( $instance['subscribe_btn_color'] ) ? '' : $instance['subscribe_btn_color'];
		$unsubscribe_btn_color      = empty( $instance['unsubscribe_btn_color'] ) ? '' : $instance['unsubscribe_btn_color'];
		$widgetprecontent           = empty( $instance['widgetprecontent'] ) ? '' : $instance['widgetprecontent'];
		$widgetpostcontent          = empty( $instance['widgetpostcontent'] ) ? '' : $instance['widgetpostcontent'];
		$hide_subscribe_button      = empty( $instance['hide_subscribe_button'] ) ? '' : $instance['hide_subscribe_button'];
		$hide_unsubscribe_button    = empty( $instance['hide_unsubscribe_button'] ) ? '' : $instance['hide_unsubscribe_button'];
		$hide_widget_title          = empty( $instance['hide_widget_title'] ) ? '' : $instance['hide_widget_title'];
		$postto                     = empty( $instance['postto'] ) ? '' : $instance['postto'];
		$validation_message         = empty( $instance['validation_message'] ) ? '' : $instance['validation_message'];
		$success_message            = empty( $instance['success_message'] ) ? '' : $instance['success_message'];
		$already_registered_message = empty( $instance['already_registered_message'] ) ? '' : $instance['already_registered_message'];
		$unsubscribe_message        = empty( $instance['unsubscribe_message'] ) ? '' : $instance['unsubscribe_message'];
		$disable_antispam           = empty( $instance['disable_antispam'] ) ? '' : $instance['disable_antispam'];


		/*
		 * Output Subscribe2 Widget Pro
		 */

		include( 'templates/widget-output.php' );

	}


	/**
	 * @DESC: Saves the widget options
	 * @SEE WP_Widget::update
	 */
	function update( $new_instance, $old_instance ) {

		$instance                               = $old_instance;
		$instance['title']                      = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['div']                        = strip_tags( stripslashes( $new_instance['div'] ) );
		$instance['widget_style']               = stripslashes( $new_instance['widget_style'] );
		$instance['subscribe_btn_color']        = stripslashes( $new_instance['subscribe_btn_color'] );
		$instance['unsubscribe_btn_color']      = stripslashes( $new_instance['unsubscribe_btn_color'] );
		$instance['widgetprecontent']           = stripslashes( $new_instance['widgetprecontent'] );
		$instance['widgetpostcontent']          = stripslashes( $new_instance['widgetpostcontent'] );
		$instance['hide_subscribe_button']      = strip_tags( stripslashes( $new_instance['hide_subscribe_button'] ) );
		$instance['hide_unsubscribe_button']    = strip_tags( stripslashes( $new_instance['hide_unsubscribe_button'] ) );
		$instance['hide_widget_title']          = strip_tags( stripslashes( $new_instance['hide_widget_title'] ) );
		$instance['postto']                     = stripslashes( $new_instance['postto'] );
		$instance['validation_message']         = stripslashes( $new_instance['validation_message'] );
		$instance['success_message']            = stripslashes( $new_instance['success_message'] );
		$instance['already_registered_message'] = stripslashes( $new_instance['already_registered_message'] );
		$instance['unsubscribe_message']        = stripslashes( $new_instance['unsubscribe_message'] );
		$instance['disable_antispam']           = stripslashes( $new_instance['disable_antispam'] );

		return $instance;
	}


	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 */
	function form( $instance ) {

		// set some defaults, getting any old options first
		$defaultTitle = __( 'Subscribe to ' . get_bloginfo( 'name' ) . '', 's2w' );

		$title                      = ! isset( $instance['title'] ) ? $defaultTitle : htmlspecialchars( $instance['title'], ENT_QUOTES );
		$div                        = ! isset( $instance['div'] ) ? '' : htmlspecialchars( $instance['div'], ENT_QUOTES );
		$widget_style               = ! isset( $instance['widget_style'] ) ? '' : $instance['widget_style'];
		$subscribe_btn_color        = ! isset( $instance['subscribe_btn_color'] ) ? 's2w-btn-success' : $instance['subscribe_btn_color'];
		$unsubscribe_btn_color      = ! isset( $instance['unsubscribe_btn_color'] ) ? 's2w-btn-warning' : $instance['unsubscribe_btn_color'];
		$widgetprecontent           = ! isset( $instance['widgetprecontent'] ) ? '' : htmlspecialchars( $instance['widgetprecontent'], ENT_QUOTES );
		$widgetpostcontent          = ! isset( $instance['widgetpostcontent'] ) ? '' : htmlspecialchars( $instance['widgetpostcontent'], ENT_QUOTES );
		$hide_subscribe_button      = ! isset( $instance['hide_subscribe_button'] ) ? '' : htmlspecialchars( $instance['hide_subscribe_button'], ENT_QUOTES );
		$hide_unsubscribe_button    = ! isset( $instance['hide_unsubscribe_button'] ) ? '' : htmlspecialchars( $instance['hide_unsubscribe_button'], ENT_QUOTES );
		$hide_widget_title          = ! isset( $instance['hide_widget_title'] ) ? '' : htmlspecialchars( $instance['hide_widget_title'], ENT_QUOTES );
		$postto                     = ! isset( $instance['postto'] ) ? '' : htmlspecialchars( $instance['postto'], ENT_QUOTES );
		$validation_message         = ! isset( $instance['validation_message'] ) ? __( 'There was a problem with your submission. Errors have been highlighted below.', 's2w' ) : esc_attr( $instance['validation_message'] );
		$success_message            = ! isset( $instance['success_message'] ) ? __( 'Thank you for signing up! Please check your email to confirm your subscription.', 's2w' ) : esc_attr( $instance['success_message'] );
		$unsubscribe_message        = ! isset( $instance['unsubscribe_message'] ) ? __( 'Please check your email to confirm your subscription removal from ' . get_bloginfo( 'name' ) . '. We are sorry to see you go!', 's2w' ) : htmlspecialchars( $instance['unsubscribe_message'], ENT_QUOTES );
		$already_registered_message = ! isset( $instance['already_registered_message'] ) ? __( 'This email address has already been subscribed to ' . get_bloginfo( 'name' ) . '.', 's2w' ) : htmlspecialchars( $instance['already_registered_message'], ENT_QUOTES );
		$disable_antispam           = ! isset( $instance['disable_antispam'] ) ? '' : htmlspecialchars( $instance['disable_antispam'], ENT_QUOTES );


		/**
		 *  Get the widget admin form
		 */
		include( 'templates/widget-admin-form.php' );

		//End Form Output

	}

}

/*
 * @DESC: Register Twitter Widget Pro widget
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "Subscribe2_Widget_Pro" );' ) );