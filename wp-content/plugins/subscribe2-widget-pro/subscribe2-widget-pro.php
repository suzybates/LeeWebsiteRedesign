<?php
/*
Plugin Name: Subscribe2 Widget Pro
Plugin URI: http://wordimpress.com/
Description: An enhanced Subscribe2 WordPress widget that will help you increase newsletter conversions.
Version: 2.1
Author: Devin Walker
Author URI: http://imdev.in/
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'S2W_PLUGIN_NAME', 'subscribe2-widget-pro' );
define( 'S2W_PLUGIN_NAME_PLUGIN', 'subscribe2-widget-pro/subscribe2-widget-pro.php' );
define( 'S2W_WIDGET_PRO_PATH', WP_PLUGIN_DIR . '/' . S2W_PLUGIN_NAME );
define( 'S2W_WIDGET_PRO_URL', WP_PLUGIN_URL . '/' . S2W_PLUGIN_NAME );
define( 'S2W_WIDGET_UPGRADE_LINK', 'http://wordimpress.com/plugins/subscribe2-widget-pro/' );
define( 'S2W_DEBUG', false ); //helpful for debugging

$s2wOptions = get_option( 's2w_widget_settings' );

register_activation_hook( __FILE__, 's2w_widget_activate' );
register_uninstall_hook( __FILE__, 's2w_widget_uninstall' );
add_action( 'admin_init', 's2w_widget_init' );
add_action( 'admin_menu', 's2w_widget_add_options_page' );

/**
 * Run function when plugin is Activated
 *
 */
function s2w_widget_activate() {
	//Check to see if Subscribe2 Plugin is active
	if ( ! is_plugin_active( 'subscribe2/subscribe2.php' ) ) {
		//Throw error message
		_e( '<p>Subscribe2 Widget Pro plugin requires Subscribe2 to be installed and activated to work properly. Please install the Subscribe2 plugin and reactivate Subscribe2 Widget Pro.</p>', 's2w' );
		exit;
	}
	$options = get_option( 's2w_widget_settings' );

}

/**
 * Run function when plugin is Deactivated
 */

function s2w_widget_uninstall() {
	// Delete options when uninstalled
	delete_option( 's2w_widget_settings' );
}


/**
 * Adds Subscribe2 Widget Pro Options Page
 */
require_once( dirname( __FILE__ ) . '/includes/options.php' );


/**
 * @TODO: Localize the Plugin for Other Languages
 *
 */
//load_plugin_textdomain('s2w' , false, dirname( plugin_basename(__FILE__) ) . '/languages/' );


/**
 * Check for Subscribe2
 */
function is_subscribe2_activated() {
	//Check to see if Subscribe2 Plugin is active
	$activePlugins = get_option( 'active_plugins' );
	if ( in_array( 'subscribe2/subscribe2.php', $activePlugins ) == false ) {
		return false; //plugin is NOT active
	} else {
		return true; //plugin is active
	}

}

function s2w_admin_notice() {
	_e( '<div id="message" class="updated"><p>Subscribe2 Widget Pro plugin requires <strong><a href="http://wordpress.org/extend/plugins/subscribe2/" target="_blank" title="Download and install Subscribe2">Subscribe2</a></strong> to be installed and activated to work properly. Please install the Subscribe2 plugin to use Subscribe2 Widget Pro.</p></div>', 's2w' );

}


function s2w_get_IP() {
	foreach ( array( 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ) as $key ) {
		if ( array_key_exists( $key, $_SERVER ) === true ) {
			foreach ( array_map( 'trim', explode( ',', $_SERVER[$key] ) ) as $ip ) {
				if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
					return $ip;
				}
			}
		}
	}
}


/**
 * Add links to Plugin listings view
 *
 * @param $links
 *
 * @return mixed
 */
function s2w_add_plugin_page_links( $links, $file ) {
	if ( $file == S2W_PLUGIN_NAME_PLUGIN ) {
		// Add Widget Page link to our plugin
		$link = s2w_get_options_link();
		array_unshift( $links, $link );

		// Add Support Forum link to our plugin
		$link = s2w_get_support_forum_link();
		array_unshift( $links, $link );
	}

	return $links;
}

function s2w_add_plugin_meta_links( $meta, $file ) {
	if ( $file == S2W_PLUGIN_NAME_PLUGIN ) {
		$meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/subscribe2-widget-pro' target='_blank' title='" . __( 'Rate Subscribe2 Widget Pro', 's2w' ) . "'>" . __( 'Rate Plugin', 's2w' ) . "</a>";
		$meta[] = __( 'Upgrade to <a href="http://wordimpress.com/wordpress-plugin-development/subscribe2-widget-pro/" target="_blank">Subscribe2 Widget Pro Premium</a>', 's2w' );
	}

	return $meta;
}

function s2w_get_support_forum_link( $linkText = '' ) {
	if ( empty( $linkText ) ) {
		$linkText = __( 'Support', 's2w' );
	}

	return '<a href="http://wordimpress.com/support/forum/subscribe2-widget-pro/" target="_blank" title="Get Support">' . $linkText . '</a>';
}

function s2w_get_options_link( $linkText = '' ) {
	if ( empty( $linkText ) ) {
		$linkText = __( 'Settings', 's2w' );
	}

	return '<a href="options-general.php?page=subscribe2-widget-pro">' . $linkText . '</a>';
}


/**
 * Get the Widget
 */
if ( ! class_exists( 'Subscribe2_Widget_Pro' ) && is_subscribe2_activated() ) {

	require 'includes/widget.php';

} else {

	//Throw notice if subscribe2 is not activated
	add_action( 'admin_notices', 's2w_admin_notice' );

}
