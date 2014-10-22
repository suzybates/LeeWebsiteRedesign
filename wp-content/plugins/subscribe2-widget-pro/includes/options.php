<?php
/**
 *  Admin options page. Creates a page to set your OAuth settings for the Subscribe2 API v2.
 *  A special thanks to the Subscribe2 It plugin for the great code!
 *
 */

//Subscribe2 Options Page
function s2w_widget_add_options_page() {

	// Add the menu option under Settings, shows up as "s2w API Settings" (second param)
	$page = add_submenu_page( 'options-general.php', //The parent page of this menu
		__( 'Subscribe2 Widget Pro Settings', 's2w' ), //The Menu Title
		__( 'Subscribe2 Widget', 's2w' ), //The Page Title
		'manage_options', // The capability required for access to this item
		'subscribe2-widget-pro', // the slug to use for the page in the URL
		's2w_widget_options_form' ); // The function to call to render the page

	/* Using registered $page handle to hook script load */
	add_action( 'admin_print_scripts-' . $page, 's2w_options_scripts' );


}

//Add Subscribe2 Widget Pro option scripts to admin head - will only be loaded on plugin options page
function s2w_options_scripts() {
	//register admin JS
	wp_enqueue_script( 's2w_widget_options_js', plugins_url( 'includes/js/options.js', dirname( __FILE__ ) ) );

	//register our stylesheet
	wp_register_style( 's2w_widget_css', plugins_url( 'includes/style/options.css', dirname( __FILE__ ) ) );
	// It will be called only on plugin admin page, enqueue our stylesheet here
	wp_enqueue_style( 's2w_widget_css' );

}

//Load Widget JS Script ONLY on Widget page
function s2w_widget_scripts( $hook ) {
	if ( $hook == 'widgets.php' ) {
		wp_enqueue_script( 's2w_widget_admin_scripts', plugins_url( 'includes/js/admin-widget.js', dirname( __FILE__ ) ) );
		wp_enqueue_style( 's2w_widget_admin_css', plugins_url( 'includes/style/admin-widget.css', dirname( __FILE__ ) ) );
	} else {
		return;
	}
}

add_action( 'admin_enqueue_scripts', 's2w_widget_scripts' );

//Initiate the Subscribe2 Widget
function s2w_widget_init() {
	// Register the s2w_widget settings as a group
	register_setting( 's2w_widget_settings', 's2w_widget_settings' );

	//Custom Plugin Links
	add_filter( 'plugin_row_meta', 's2w_add_plugin_meta_links', 10, 2 );
	add_filter( 'plugin_action_links', 's2w_add_plugin_page_links', 10, 2 );


}

// Output the s2w_widget option setting value
function s2w_widget_option( $setting, $options ) {
	// If the old setting is set, output that
	if ( get_option( $setting ) != '' ) {
		echo get_option( $setting );
	} else {
		if ( is_array( $options ) ) {
			echo $options[$setting];
		}
	}
}


// Generate the admin form
function s2w_widget_options_form() {
	?>

	<div class="wrap" xmlns="http://www.w3.org/1999/html">
		<!-- Plugin Title -->
		<div id="s2w-title-wrap">
			<div id="icon-s2w" class=""></div>
			<h2><?php _e( 'Subscribe2 Widget Pro Settings', 's2w' ); ?></h2>
			<label class="label basic-label">Basic Version</label>
			<a href="<?php echo S2W_WIDGET_UPGRADE_LINK; ?>" title="Upgrade to Subscribe2 Widget Premium" target="_blank" class="update-link new-window">Upgrade to Premium</a>

		</div>

		<form id="subscribe2-settings" method="post" action="options.php">

			<div class="metabox-holder">
				<div class="postbox-container" style="width:75%">
					<div id="main-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox" id="subscribe2-widget-intro">
							<div class="handlediv" title="Click to toggle"><br>
							</div>
							<h3 class="hndle"><span><?php _e( 'Subscribe2 Widget Pro', 's2w' ); ?></span>
							</h3>

							<div class="inside">
								<p><?php _e( 'Thanks for choosing Subscribe2 Widget Pro. This plugin allows you to add a Subscribe2 conversion form to your website via an easy to use and intuitive widget.', 's2w' ); ?></p>

									<p>
										<strong><?php _e( 'Like this plugin?  Give it a like on Facebook, Follow Us on Twitter or Google+:', 's2w' ); ?></strong></p>
								<div class="social-items-wrap">

									<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FWordImpress%2F353658958080509&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=220596284639969" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
									<a href="https://twitter.com/wordimpress" class="twitter-follow-button" data-show-count="false">Follow @wordimpress</a>
									<script>!function (d, s, id) {
											var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
											if (!d.getElementById(id)) {
												js = d.createElement(s);
												js.id = id;
												js.src = p + '://platform.twitter.com/widgets.js';
												fjs.parentNode.insertBefore(js, fjs);
											}
										}(document, 'script', 'twitter-wjs');</script>
									<div class="google-plus">
										<!-- Place this tag where you want the +1 button to render. -->
										<div class="g-plusone" data-size="medium" data-annotation="inline" data-width="200" data-href="https://plus.google.com/117062083910623146392"></div>


										<!-- Place this tag after the last +1 button tag. -->
										<script type="text/javascript">
											(function () {
												var po = document.createElement('script');
												po.type = 'text/javascript';
												po.async = true;
												po.src = 'https://apis.google.com/js/plusone.js';
												var s = document.getElementsByTagName('script')[0];
												s.parentNode.insertBefore(po, s);
											})();
										</script>
									</div>
									<!--/.google-plus -->
								</div>
								<!-- /.inside -->
							</div>
							<!-- /.inside -->
						</div>
						<!-- /#subscribe2-widget-intro -->

						<div class="postbox" id="subscribe2-widget-options">
							<div class="handlediv" title="Click to toggle"><br>
							</div>
							<h3 class="hndle"><span><?php _e( 'Subscribe2 Widget Pro Settings', 's2w' ); ?></span></h3>

							<div class="inside">
								<?php
								// Tells Wordpress that the options we registered are being handled by this form
								settings_fields( 's2w_widget_settings' );

								// Retrieve stored options, if any
								$options = get_option( 's2w_widget_settings' );

								// Debug, show stored options
								if ( S2W_DEBUG == true ) {
									echo "<pre>";
									var_dump( $options );
									echo "</pre>";
								} ?>

								<div class="control-group">
									<div class="control-label">
										<label for="s2w_widget_disable_output"><?php _e( 'Optimize Subscribe2 Output <span>(recommended)</span>:', 's2w' ); ?>
											<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Subscribe2 outputs unnecessary CSS and javascript to your website that will cause it to <strong>load slower</strong>. This option ensures the files are not loaded on the frontend of your website. A faster website means happier visitors and ultimately more conversions.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
									</div>
									<div class="controls">
										<p>
											<em><?php _e( 'This option is only available in the Premium plugin.', 's2w' ); ?></em>
										</p>
									</div>
								</div>
								<!--/.control-group -->


								<div class="control-group">
									<div class="control-label">
										<label for="s2w_widget_disable_css"><?php _e( 'Disable Plugin CSS Output:', 's2w' ); ?>
											<img src="<?php echo S2W_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Disabling the widget\'s CSS output is useful for more complete control over customizing the widget styles. Helpful for integration into custom theme designs.', 's2w' ); ?>" class="tooltip-info" width="16" height="16" /></label>
									</div>
									<div class="controls">
										<input type="checkbox" id="s2w_widget_disable_css" name="s2w_widget_settings[s2w_widget_disable_css]" value="1"
												<?php
												if ( isset( $options['s2w_widget_disable_css'] ) ) {
													checked( 1, $options['s2w_widget_disable_css'] );
												}

												?>
												/>
									</div>
								</div>
								<!--/.control-group -->

							</div>
							<!-- /.inside -->
						</div>
						<!-- /#subscribe2-widget-options -->

						<div class="control-group">
							<div class="controls">
								<input class="button-primary" type="submit" name="submit" value="<?php _e( 'Update', 's2w' ); ?>" />
							</div>
						</div>


					</div>
					<!-- /#main-sortables -->
				</div>
				<!-- /.postbox-container -->

				<div class="alignright" style="width:24%">
					<div id="sidebar-sortables" class="meta-box-sortables ui-sortable">

						<div id="s2w-pro-premium" class="postbox">

							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'Subscribe2 Widget Premium', 's2w' ); ?></span></h3>

							<div class="inside">
								<p><?php _e( 'Subscribe2 Widget Pro Premium is a significant upgrade to Subscribe2 Widget Pro that adds features and functionality not found within the default widget. Features include a faster AJAX form submission method, customizable labels, validation messages and input values, optimized Subscribe2 output, an advanced shortcode and so much more! Also included is 1-year of priority support and auto updates.', 's2w' ); ?>.</p>
							</div>
						</div>
						<!-- /.premium-metabox -->


						<div id="subscribe2-widget-pro-support" class="postbox">
							<div class="handlediv" title="Click to toggle"><br>
							</div>
							<h3 class="hndle"><span><?php _e( 'Need Support?', 's2w' ); ?></span></h3>

							<div class="inside">
								<p><?php _e( 'If you have any problems with this plugin or ideas for improvements or enhancements, please use the <a href="http://wordimpress.com/support/forum/subscribe2-widget-pro/" target="_blank" title="Get support for Subscribe2 Widget Pro" class="new-window">Support Forums</a>.', 's2w' ); ?></p>
							</div>
							<!-- /.inside -->
						</div>
						<!-- /.subscribe2-widget-pro-support -->

					</div>
					<!-- /#sidebar-sortables -->

					<a href="http://wordimpress.com/" class="wordimpress-link" target="_blank"></a>

				</div>
				<!-- .alignright -->


			</div>
			<!-- /.metabox-holder -->
		</form>

	</div><!-- /#wrap -->

<?php
} //end s2w_widget_options_form
?>