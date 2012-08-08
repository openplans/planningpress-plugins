<?php
/*
Plugin Name: GeoJSON Maps
Plugin URI: http://openplans.org
Description: Allows people to publish GeoJSON feeds on a map and embed it in a Wordpress page.
Version: 0.1
Author: Chris Abraham
Author URI: http://cjyabraham.com
License: GPL2
*/

/*  Copyright 2011  Juergen Schulze  (email : 1manfactory@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?><?php

// some definition we will use
define( 'GM_PUGIN_NAME', 'GeoJSON Maps');
define( 'GM_PLUGIN_DIRECTORY', 'geojson-maps');
define( 'GM_CURRENT_VERSION', '0.1' );
define( 'GM_CURRENT_BUILD', '1' );
// i18n plugin domain for language files
define( 'EMU2_I18N_DOMAIN', 'gm' );

// load language files
function gm_set_lang_file() {
	# set the language file
	$currentLocale = get_locale();
	if(!empty($currentLocale)) {
		$moFile = dirname(__FILE__) . "/lang/" . $currentLocale . ".mo";
		if (@file_exists($moFile) && is_readable($moFile)) {
			load_textdomain(EMU2_I18N_DOMAIN, $moFile);
		}

	}
}
gm_set_lang_file();

// create custom plugin settings menu
add_action( 'admin_menu', 'gm_create_menu' );

//call register settings function
add_action( 'admin_init', 'gm_register_settings' );


function gm_create_menu() {
	add_options_page(__('GeoJSON Maps Options', EMU2_I18N_DOMAIN), __("GeoJSON Maps", EMU2_I18N_DOMAIN), 9,  GM_PLUGIN_DIRECTORY.'/gm_settings_page.php');
}


function gm_register_settings() {
	//register settings
	register_setting( 'gm-settings-group', 'gm_zoom' );
	register_setting( 'gm-settings-group', 'gm_minzoom' );
	register_setting( 'gm-settings-group', 'gm_maxzoom' );
	register_setting( 'gm-settings-group', 'gm_maxbounds' );
	register_setting( 'gm-settings-group', 'gm_lat' );
	register_setting( 'gm-settings-group', 'gm_lng' );
}


add_action( 'init', 'gm_create_post_type' );
function gm_create_post_type() {
  register_post_type( 'gm_layer',
                    array(
	                  'labels' => array(
			                    'name' => __( 'Map Layers' ),
					    'singular_name' => __( 'Map Layer' ),
			                    'add_new_item' => __( 'Add New Layer' ),
					    'edit_item' => __( 'Edit Layer' ),
			                    'search_items' => __( 'Search Map Layers' ),
					    'not_found' =>  __('No map layers found'),
					   ),
			  'public' => true,
			  'exclude_from_search' => true,
			  'has_archive' => true,
			  'supports' => array('title', 'editor', 'page-attributes'),
			  'rewrite' => array('slug' => 'layers')
	                 )
		    );
}


/* Define the custom box */
add_action( 'add_meta_boxes', 'gm_add_custom_boxs' );

/* Do something with the data entered */
add_action( 'save_post', 'gm_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function gm_add_custom_boxs() {
    add_meta_box( 
        'gm_meta',
        'Layer Info',
        'gm_inner_custom_box',
        'gm_layer',
	'side'
    );

    add_meta_box(
        'gm_rules',
	'Layer Rules',
	'gm_layer_rules',
	'gm_layer',
	'normal'
	);
    add_meta_box(
        'gm_popup',
	'Popup Text',
	'gm_layer_popup',
	'gm_layer',
	'normal'
	);

}

function gm_layer_rules( $post ) {
  $gm_rules = get_post_meta($post->ID, 'gm_rules', true);

  // The actual fields for data entry
  echo '<textarea id="gm_rules" name="gm_rules" rows="4" style="width:100%">';
  echo $gm_rules;
  echo '</textarea>';
}

function gm_layer_popup( $post ) {
  $gm_popup = get_post_meta($post->ID, 'gm_popup', true);

  // The actual fields for data entry
  echo '<textarea id="gm_popup" name="gm_popup" rows="3" style="width:100%">';
  echo $gm_popup;
  echo '</textarea>';
}

/* Prints the box content */
function gm_inner_custom_box( $post ) {
  $url = get_post_meta($post->ID, 'gm_url', true);
  $property = get_post_meta($post->ID, 'gm_property', true);
  $type = get_post_meta($post->ID, 'gm_type', true);
  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'gm_noncename' );

  // The actual fields for data entry
  echo '<p><label for="gm_url">';
  echo "GeoJSON URL";
  echo '</label><br/>';
  echo '<input type="text" id="gm_url" name="gm_url" value="' . $url . '" size="30" /></p>';
  echo '<p><label for="gm_type">';
  echo 'Type';
  echo '</label> <br/>';
  echo '<select id="gm_type" name="gm_type"><option value="geoserver"';
  echo ($type=='geoserver') ? ' selected="selected"' : '';
  echo '>geoserver</option><option value="json"';
  echo ($type=='json') ? ' selected="selected"' : '';
  echo '>json</option><option value="jsonp"';
  echo ($type=='jsonp') ? ' selected="selected"' : '';
  echo '>jsonp</option></select></p>';
  echo '<p><label for="gm_property">';
  echo 'Property';
  echo '</label> <br/>';
  echo '<input type="text" id="gm_property" name="gm_property" value="' . $property . '" size="15" /></p>';
}

/* When the post is saved, saves our custom data */
function gm_save_postdata( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['gm_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( !current_user_can( 'edit_post', $post_id ) )
        return;

  // OK, we're authenticated: we need to find and save the data

  $mydata = $_POST['gm_url'];
  update_post_meta($post_id, 'gm_url', $mydata);
  $mydata = $_POST['gm_property'];
  update_post_meta($post_id, 'gm_property', $mydata);
  $mydata = $_POST['gm_type'];
  update_post_meta($post_id, 'gm_type', $mydata);
  $mydata = $_POST['gm_rules'];
  update_post_meta($post_id, 'gm_rules', $mydata);
  $mydata = $_POST['gm_popup'];
  update_post_meta($post_id, 'gm_popup', $mydata);

}

function gm_layer_custom_columns($column) {
    global $post;
    $custom = get_post_custom();
    switch ($column){
    case "gm_col_order":
      echo $post->menu_order;
      break;
    }
}
add_action ("manage_posts_custom_column", "gm_layer_custom_columns");

function gm_layer_edit_columns($columns) {
    $extracolumns = array(
    		          "title" => $columns['title'],
                          "gm_col_order" => "Order",
			  "date" => $columns['date']
                          );
    return $extracolumns;
}
add_filter ("manage_edit-gm_layer_columns", "gm_layer_edit_columns");

add_filter( 'manage_edit-gm_layer_sortable_columns', 'gm_layer_sortable_column' );  
function gm_layer_sortable_column( $columns ) {  
    $columns['gm_col_order'] = 'menu_order';  
    return $columns;  
}  

wp_enqueue_script( 'jquery' );

function gm_show_map( $atts ){

  $out = '<div id="argo-container"><div id="argo-map"></div><div id="argo-legend"></div></div>';
  $out .= '<script src="' . plugins_url('js/lib/leaflet-0.4.2/leaflet.js', __FILE__) . '"></script>';
  $out .= '<script src="' . plugins_url('js/lib/underscore-1.3.3.min.js', __FILE__) . '"></script>';
  $out .= '<script src="' . plugins_url('js/lib/backbone-0.9.2.min.js', __FILE__) . '"></script>';
  $out .= '<script src="' . plugins_url('js/views.js', __FILE__) . '"></script>';

  $out .= getArgo();

  $out .= <<<EOD
    <script>
      var layerCollection = new Backbone.Collection(Argo.demoOptions.layers);
      var mapView = new Argo.MapView({
            el: '#argo-map',
            map: Argo.demoOptions.map,
            collection: layerCollection
          }),
          legendView = new Argo.LegendView({
            el: '#argo-legend',
            collection: layerCollection
          });

    </script>
EOD;


  return $out;


}

add_shortcode( 'geojson-map', 'gm_show_map' );

add_action('wp_head', 'gm_insert_in_head');
function gm_insert_in_head() {

    echo '<link rel="stylesheet" href="' . plugins_url('js/lib/leaflet-0.4.2/leaflet.css', __FILE__) . '" />';
    echo '<!--[if lte IE 8]><link rel="stylesheet" href="' . plugins_url('js/lib/leaflet-0.4.2/leaflet.ie.css', __FILE__) . '" /><![endif]-->';
?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<?php
    echo '<link rel="stylesheet" href="' . plugins_url('css/style.css', __FILE__) . '" />';
    echo '<link rel="stylesheet" href="' . plugins_url('css/extra-style.css', __FILE__) . '" />';

}

function getArgo() {
  global $post, $wp_query;
  $temp_query = $wp_query;

  $out = "\n<script>\nvar Argo = Argo || {};\n";
  $out .= "Argo.demoOptions = {\nmap: {\n";
  $out .= "center: [" . get_option('gm_lat') . "," . get_option('gm_lng') . "],\n";
  $out .= "zoom: " . get_option('gm_zoom') . ",\n";
  $out .= "minZoom: " . get_option('gm_minzoom') . ",\n";
  $out .= "maxZoom: " . get_option('gm_maxzoom') . ",\n";
  $out .= "maxBounds: [" . get_option('gm_maxbounds') . "]\n";
  $out .= "},\nlayers: [\n";

  query_posts('post_type=gm_layer&posts_per_page=-1&orderby=menu_order&order=asc');
  $postnum = 0;
  if ( have_posts() ) : while ( have_posts() ) : the_post();
    $postnum += 1;
    if ($postnum > 1)
      $out .= ",";
    $out .= "{\n";
    $out .= "id: '" . $post->post_name . "',\n";
    $out .= "url: '" . get_post_meta($post->ID, 'gm_url', true) . "',\n";
    $out .= "property: '" . get_post_meta($post->ID, 'gm_property', true) . "',\n";
    $out .= "title: '" . get_the_title() . "',\n";
    $out .= "type: '" . get_post_meta($post->ID, 'gm_type', true) . "',\n";
    $out .= "description: '" . get_the_content() . "',\n";
    $out .= "popupContent: '" . get_post_meta($post->ID, 'gm_popup', true) . "',\n";
    $out .= "rules: " . get_post_meta($post->ID, 'gm_rules', true) . "\n";
    $out .= "}\n";
  endwhile; endif;

  $out .= "]\n};\n</script>\n";
  $wp_query = $temp_query;
  return $out;
}

?>
