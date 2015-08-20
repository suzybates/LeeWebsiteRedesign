<?php 

//add_action('register_sidebar', 'ch_filter_sidebars');
//
//function ch_filter_sidebars($sidebars) {
//    
//    global $wp_registered_sidebars;
//    
//     if ($sidebars['id'] == 'ch-footer-top-sidebar') {
//        // you can make footer widgets as wide as you want
//        // available css clases: one whole, one half, one fourth, one third, one fifth, one sixth...
//        $wp_registered_sidebars['ch-footer-top-sidebar']['before_widget'] = '<div id="%1$s" class="widget one half double-padded %2$s">';
//        $wp_registered_sidebars['ch-footer-top-sidebar']['after_widget'] = '</div>';
//     }
//    
//}

//function carry_hill_add_loginout_navitem($items, $args) {
//     
//     if ($args->theme_location == 'primary-menu') {
//	$login_item = '<li class="login">'.wp_loginout($_SERVER['REQUEST_URI'], false).'</li>';
//        $items .= $login_item;
//	return $items;
//     }
//}
//add_filter('wp_nav_menu_items', 'carry_hill_add_loginout_navitem', 10, 2);

////change breadcrumb labels
//function carry_hill_breadcrumb_trail_args($args) {
//     
//    $args['labels']['home'] = 'Holla';
//    return $args;
//}
//add_filter('breadcrumb_trail_args', 'carry_hill_breadcrumb_trail_args');

$userGuid = "61f82d28-c9f3-4bec-be8f-cd1cc0b7cab7";
$apiKey = "AccmyJLuXl4XR8V1/ubdW+B4Bwtfr6cxuHDKNNI/jXFHPFqQI2Q6RxMtTQ4TK59Vyu1zfoN/YTeU6Me8l4+oyg==";

function aisd_menu_query($connectorGuid, $input, $userGuid, $apiKey, $additionalInput) {

  $url = "https://api.import.io/store/connector/" . $connectorGuid . "/_query?_user=" . urlencode($userGuid) . "&_apikey=" . urlencode($apiKey);

  $data = array("input" => $input);
  if ($additionalInput) {
    $data["additionalInput"] = $additionalInput;
  }

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($data));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  $result = curl_exec($ch);
  curl_close($ch);

  return json_decode($result);
}

function my_post_types($types) {
	$types[] = 'newsletter_item';
	$types[] = 'volunteer_item';
	return $types;
}

add_filter('s2_post_types', 'my_post_types');

//display 16 products per page
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 16;' ), 20 );
