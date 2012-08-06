<?php

/*
Plugin Name: Planning Publisher
Plugin URI: http://wordpress.org/#
Description: Publishes a json feed containing information about planning events.
Author: Chris Abraham
Version: 0.1
Author URI: http://openplans.org
*/


add_action('template_redirect', 'planningpublisher_redirect');

function planningpublisher_redirect() {
    if (strstr($_SERVER['REQUEST_URI'], '/planningfeed')) {
        include_wordpress_template('planningfeed.php');
        exit;
    }
}

if ( !function_exists('include_wordpress_template')) {
  function include_wordpress_template($t) {
    global $wp_query;
    if ($wp_query->is_404) {
        $wp_query->is_404 = false;
        $wp_query->is_archive = true;
    }
    header("HTTP/1.1 200 OK");
    include($t);
  }
}

?>