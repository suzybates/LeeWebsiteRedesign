<?php 
if( ! defined("MC4WP_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

?>
<p class="help"><?php printf( __( 'Need help? Have a look at the <a href="%s">plugin documentation</a> or email me directly at <a href="%s">support@mc4wp.com</a>.', 'mailchimp-for-wp' ), 'http://docs.dannyvankooten.com/', 'mailto:support%40mc4wp.com?subject=MailChimp%20for%20WP%20premium%20support&body=Hi%20Danny%2C%0A%0AMy%20website%3A%20' . site_url() . '%0AMailChimp%20for%20WP%20v' . MC4WP_VERSION . '%0ALicense%20Key:%20'. $this->license_manager->get_license_key() .'%0AWordPress%20v' . get_bloginfo('version') . '%0APHP%20v' . phpversion() . '%0A%0A' ); ?></p>
<p class="help">What's next? Submit your feature requests or vote for new features using <a href="http://www.google.com/moderator/#15/e=20c6b7&t=20c6b7.40">this Google Moderator tool</a>.</p>