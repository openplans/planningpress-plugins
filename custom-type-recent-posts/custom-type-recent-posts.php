<?php
/*
Plugin Name: Custom Type Recent Posts
Version: 0.1
Plugin URI: http://blog.merlinox.com/custom-type-recent-posts
Description: A plugin for wordpress to select which custom type on "Recent Posts" widget. Please <a href="http://bit.ly/9epXiE">make a donation</a> if you are satisfied.
Author: Merlinox
Author URI: http://www.merlinox.com
Inspired to: Enhanced Recent Posts (http://enhanced-recent-posts.vincentprat.info)
*/

/*  Copyright 2010 Riccardo Mares  (email : merlinox@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


//############################################################################
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('You are not allowed to call this page directly.'); 
}
//############################################################################

//############################################################################
// you can deactivate the javascript effect by setting the next variable to false
define('CUSTOMTYPE_RECENT_POSTS_USE_JAVASCRIPT', true);					
//############################################################################

//############################################################################
// plugin directory
define('CUSTOMTYPE_RECENT_POSTS_DIR', dirname (__FILE__));	

// i18n plugin domain 
define('CUSTOMTYPE_RECENT_POSTS_NAME', 'customtype-recent-posts');

// The options of the plugin
define('CUSTOMTYPE_RECENT_POSTS_PLUGIN_OPTIONS', 'cuty_rp_plugin_options');	
define('CUSTOMTYPE_RECENT_POSTS_WIDGET_OPTIONS', 'cuty_rp_widget_options');	
//############################################################################

//############################################################################
// Include the plugin files
require_once(CUSTOMTYPE_RECENT_POSTS_DIR . '/includes/plugin-class.php');
require_once(CUSTOMTYPE_RECENT_POSTS_DIR . '/includes/widget-class.php');
//############################################################################

//############################################################################
// Init the plugin classes
global $cuty_rp_plugin, $cuty_rp_widget;

$cuty_rp_plugin = new CustomTypeRecentPostsPlugin();
$cuty_rp_widget = new CustomTypeRecentPostsWidget();
//############################################################################

//############################################################################
// Load the plugin text domain for internationalisation
if (!function_exists('cuty_rp_init_i18n')) {
	function cuty_rp_init_i18n() {
		load_plugin_textdomain(CUSTOMTYPE_RECENT_POSTS_NAME, 'wp-content/plugins/customtype-recent-posts');
	} // function cuty_rp_init_i18n()

	cuty_rp_init_i18n();
} // if (!function_exists('cuty_rp_init_i18n'))
//############################################################################

//############################################################################
// Add filters and actions
add_action('widgets_init', array(&$cuty_rp_widget, 'register_widget'));

if (is_admin()) {
	add_action(
		'activate_customtype-recent-posts/customtype-recent-posts.php',
		array(&$cuty_rp_plugin, 'activate'));
} else {
}
//############################################################################

//############################################################################
// Template functions for direct use in themes

//############################################################################


?>