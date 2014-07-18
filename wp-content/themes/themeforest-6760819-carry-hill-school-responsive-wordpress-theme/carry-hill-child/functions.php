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

