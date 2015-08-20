<?php

function eme_actions_init() {
   // first the no cache headers
   nocache_headers();
   eme_load_textdomain();

   // now, first update the DB if needed
   $db_version = get_option('eme_version');
   if ($db_version && $db_version != EME_DB_VERSION) {
      // add possible new options
      eme_add_options();

      // update the DB tables
      // to do: check if the DB update succeeded ...
      eme_create_tables();

      // now set the version correct
      update_option('eme_version', EME_DB_VERSION);

      // let the admin side know if the update succeeded
      update_option('eme_update_done',1);         
   }

   // now first all ajax ops: exit needed
   if (isset ( $_GET ['eme_ical'] ) && $_GET ['eme_ical'] == 'public_single' && isset ( $_GET ['event_id'] )) {
      header("Content-type: text/calendar; charset=utf-8");
      header("Content-Disposition: inline; filename=eme_single.ics");
      eme_ical_single();
      exit;
   }
   if (isset ( $_GET ['eme_ical'] ) && $_GET ['eme_ical'] == 'public') {
      header("Content-type: text/calendar; charset=utf-8");
      header("Content-Disposition: inline; filename=eme_public.ics");
      eme_ical();
      exit;
   }
   if (isset($_POST['eme_ajaxCalendar']) && $_POST['eme_ajaxCalendar'] == true) {
      header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
      eme_filter_calendar_ajax();
      exit;
   }
   if (isset ( $_GET['eme_rss'] ) && $_GET['eme_rss'] == 'main') {
      header ( "Content-type: text/xml" );
      eme_rss();
      exit;
   }
   if (isset($_POST['eme_ajax_action']) && $_POST['eme_ajax_action'] == 'client_clock_submit') {
      eme_client_clock_callback();
      exit();
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == 'booking_data' && is_admin() && isset($_GET['event_id'])) {
      header("Content-type: application/json; charset=utf-8");
      echo '{"bookedSeats":'.eme_get_booked_seats(intval($_GET['event_id'])).',"availableSeats":'.eme_get_available_seats(intval($_GET['event_id'])).'}';
      exit();
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == 'autocomplete_locations' && is_admin()) {
      eme_locations_search_ajax();
      exit;
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == 'autocomplete_people' && is_admin()) {
      eme_people_search_ajax();
      exit;
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == 'booking_printable' && is_admin() && isset($_GET['event_id'])) {
      eme_printable_booking_report(intval($_GET['event_id']));
      exit();
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == 'booking_csv' && is_admin() && isset($_GET['event_id'])) {
      eme_csv_booking_report(intval($_GET['event_id']));
      exit();
   }

   if (is_admin() && current_user_can( get_option('eme_cap_registrations')) && isset($_REQUEST['eme_admin_action']) &&
       $_REQUEST['eme_admin_action'] == 'remove_booking' && isset($_REQUEST['booking_id'])) {
      $booking_id=intval($_REQUEST['booking_id']);
      $booking = eme_get_booking ($booking_id);
      eme_delete_booking($booking_id);
      if (get_option('eme_deny_mail_event_edit')) {
         eme_email_rsvp_booking($booking,"denyRegistration");
      }
      exit();
   }

   if (isset($_GET['query']) && $_GET['query'] == 'GlobalMapData') {
      $eventful = isset($_GET['eventful'])?$_GET['eventful']:false;
      $map_id = isset($_GET['map_id'])?$_GET['map_id']:0;
      $eventful = ($eventful==="true" || $eventful==="1") ? true : $eventful;
      $eventful = ($eventful==="false" || $eventful==="0") ? false : $eventful;
      eme_global_map_json((bool)$eventful,$_GET['scope'],$_GET['category'],$map_id);
      exit();
   }

   if (isset($_GET['eme_eventAction']) && ($_GET['eme_eventAction']=="paypal_notification" || $_GET['eme_eventAction']=="paypal_ipn")) {
      eme_paypal_notification();
      exit();
   }
   if (isset($_GET['eme_eventAction']) && ($_GET['eme_eventAction']=="2co_notification" || $_GET['eme_eventAction']=="2co_ins")) {
      eme_2co_notification();
      exit();
   }
   if (isset($_GET['eme_eventAction']) && $_GET['eme_eventAction']=="webmoney_notification") {
      eme_webmoney_notification();
      exit();
   }
   if (isset($_GET['eme_eventAction']) && $_GET['eme_eventAction']=="mollie_notification") {
      eme_mollie_notification();
      exit();
   }
   if (isset($_POST['eme_eventAction']) && ($_POST['eme_eventAction']=="fdgg_notification" || $_POST['eme_eventAction']=="fdgg_ipn")) {
      eme_fdgg_notification();
      exit();
   }
   if (isset($_GET['eme_eventAction']) && $_GET['eme_eventAction']=="sagepay_notification") {
      eme_sagepay_notification();
      // sagepay doesn't use a notification url, but sends the status along as part of the return url, so we just check
      // the status and set payed or not, but then we continue regular flow of events
   }
   if (isset($_GET['eme_cancel_booking']) && is_user_logged_in()) {
	   $booking_id=intval($_GET['eme_cancel_booking']);
	   $booking=eme_get_booking($booking_id);
      $event=eme_get_event($booking['event_id']);
      $future=strtotime($event['event_start_date']." ".$event['event_start_time']) > time()?1:0;
	   $current_userid=get_current_user_id();
	   if ($booking['wp_id']==$current_userid && $future) {
		   eme_delete_booking($booking_id);
	   }
   }

}
add_action('init','eme_actions_init');

function eme_actions_admin_init() {
   eme_options_register();

   // let the admin know the DB has been updated
   if (current_user_can( get_option('eme_cap_settings') ) && isset($_GET['eme_disable_update_message']) && $_GET['eme_disable_update_message'] == 'true')
      delete_option('eme_update_done');
   if (get_option('eme_update_done')) {
      add_action('admin_notices', 'eme_explain_dbupdate_done');
   }

   // make sure the captcha doesn't cause problems
   if (get_option('eme_captcha_for_booking') && !function_exists('imagecreatetruecolor'))
      update_option('eme_captcha_for_booking', 0);

   // flush the SEO rules if the event page has been changed
   eme_handle_get();
}
add_action('admin_init','eme_actions_admin_init');

function eme_actions_widgets_init() {
   register_widget( 'WP_Widget_eme_list' );
   register_widget( 'WP_Widget_eme_calendar' );
}
add_action( 'widgets_init', 'eme_actions_widgets_init' );

// Client clock usage and captcha need a session
if (get_option('eme_use_client_clock') || get_option('eme_captcha_for_booking')) {
   // If needed, add high priority action to enable session variables.
   if (!session_id()) add_action('init', 'session_start', 1);
}

add_action('wp_head', 'eme_general_head' );
add_action('wp_footer', 'eme_general_footer');
if (get_option('eme_load_js_in_header')) {
   add_action('wp_head', 'eme_ajaxize_calendar');
} else {
   add_action('wp_footer', 'eme_ajaxize_calendar');
}

function eme_admin_register_scripts() {
   wp_register_script( 'eme-jquery-plugin', EME_PLUGIN_URL.'js/jquery-datepick/jquery.plugin.min.js');
   wp_register_script( 'eme-jquery-datepick',EME_PLUGIN_URL.'js/jquery-datepick/jquery.datepick.js',array( 'jquery','eme-jquery-plugin' ));
   wp_register_script( 'eme-jquery-mousewheel', EME_PLUGIN_URL.'js/jquery-mousewheel/jquery.mousewheel.min.js', array('jquery'));
   wp_register_script( 'eme-jquery-timeentry', EME_PLUGIN_URL.'js/timeentry/jquery.timeentry.js', array('jquery','eme-jquery-plugin','eme-jquery-mousewheel'));
   wp_register_script( 'eme-jquery-datatables', EME_PLUGIN_URL."js/jquery-datatables/js/jquery.dataTables.min.js",array( 'jquery' ));
   wp_register_script( 'eme-datatables-clearsearch', EME_PLUGIN_URL."js/jquery-datatables/plugins/datatables_clearsearch.js");
   wp_register_script( 'eme-datatables-colvis', EME_PLUGIN_URL."js/jquery-datatables/extensions/ColVis-1.1.1/js/dataTables.colVis.min.js");
   wp_register_script( 'eme-datatables-colreorder', EME_PLUGIN_URL."js/jquery-datatables/extensions/ColReorder-1.1.3-dev/js/dataTables.colReorder.js");
   wp_register_script( 'eme-datatables-tabletools', EME_PLUGIN_URL."js/jquery-datatables/extensions/TableTools-2.2.4-dev/js/dataTables.tableTools.js");
   wp_register_script( 'eme-google-maps', '//maps.google.com/maps/api/js?v=3.1&sensor=false');
   wp_register_script( 'eme', EME_PLUGIN_URL.'js/eme.js', array('jquery'));
   wp_register_script( 'eme-events', EME_PLUGIN_URL.'js/eme_admin_events.js',array( 'jquery' ));
   wp_register_script( 'eme-registrations', EME_PLUGIN_URL.'js/eme_admin_registrations.js',array( 'jquery' ));
   wp_register_script( 'eme-options', EME_PLUGIN_URL.'js/eme_admin_options.js',array( 'jquery' ));
   wp_register_script( 'eme-sendmails', EME_PLUGIN_URL.'js/eme_admin_send_mails.js',array( 'jquery' ));
   eme_admin_enqueue_js();
}
add_action('admin_enqueue_scripts','eme_admin_register_scripts');

function eme_register_scripts() {
   // the frontend also needs the datepicker (the month filter)
   wp_register_script( 'eme-jquery-plugin', EME_PLUGIN_URL.'js/jquery-datepick/jquery.plugin.min.js');
   wp_register_script( 'eme-jquery-datepick',EME_PLUGIN_URL.'js/jquery-datepick/jquery.datepick.js',array( 'jquery','eme-jquery-plugin' ));
   wp_register_style('eme_stylesheet',EME_PLUGIN_URL."events_manager.css");
   if (get_option('eme_use_client_clock') && !isset($_SESSION['eme_client_unixtime'])) {
   	wp_register_script( 'eme-client_clock_submit', EME_PLUGIN_URL.'js/client-clock.js', array('jquery'));
	wp_enqueue_script('eme-client_clock_submit');
   }
	
   wp_enqueue_style('eme_stylesheet');
   $eme_css_name=get_stylesheet_directory()."/eme.css";
   if (file_exists($eme_css_name)) {
      $eme_css_url=get_stylesheet_directory_uri()."/eme.css";
      wp_register_style('eme_stylesheet_extra',$eme_css_url,'eme_stylesheet');
      wp_enqueue_style('eme_stylesheet_extra');
   }
}
add_action('wp_enqueue_scripts','eme_register_scripts');

add_action('template_redirect', 'eme_template_redir' );
add_action('template_redirect', 'eme_change_canonical_url' );
add_action('admin_notices', 'eme_alert_events_page' );

// when editing other profiles then your own
add_action('edit_user_profile', 'eme_user_profile') ;
add_action('edit_user_profile_update','eme_update_user_profile');
// when editing your own profile
add_action('show_user_profile', 'eme_user_profile') ;
add_action('personal_options_update','eme_update_user_profile');

// it works just fine, but then people can't disable comments on this page
// TODO: until I figure this out, we put this in comment
// add_action( 'pre_get_posts' ,'exclude_this_page' );
// another one working is 'get_posts', but the same prob exists

?>
