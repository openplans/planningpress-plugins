<?php
//
//  SETTINGS CONFIGURATION CLASS
//
//  By Olly Benson / v 1.2 / 13 July 2011 / http://code.olib.co.uk
//  Modified / Bugfix by Karl Cohrs / 17 July 2011 / http://karlcohrs.com
//  Adapted for MyGeoPosition.com Geotags / GeoMetatags / 01 November 2011
//
//	http://code.olib.co.uk/2011/07/12/wordpress-settings-api-a-quick-implementation/
//
//  HOW TO USE
//  * add a include() to this file in your plugin.
//  * amend the config class below to add your own settings requirements.
//  * to avoid potential conflicts recommended you do a global search/replace on this page to replace 'mygpGeotagsGeoMetatags_settings' with something unique
//  * Full details of how to use Settings see here: http://codex.wordpress.org/Settings_API
 
class mygpGeotagsGeoMetatags_settings_config {
	
 
	// MAIN CONFIGURATION SETTINGS
	 
	var $group = "mygpGeotagsGeoMetatags"; // defines setting groups (should be bespoke to your settings)
	var $page_name = "mygpGeotagsGeoMetatags_display"; // defines which pages settings will appear on. Either bespoke or media/discussion/reading etc
	 
	//  DISPLAY SETTINGS
	//  (only used if bespoke page_name)
	 
	var $title = "MyGeoPosition.com Geotags/GeoMetatags/GeoFeedtags";  // page title that is displayed
	var $intro_text = "Some basic settings for this plugin."; // text below title
	var $nav_title = "MyGP Geotags"; // how page is listed on left-hand Settings panel
	 
	//  SECTIONS
	//  Each section should be own array within $sections.
	//  Should contatin title, description and fields, which should be array of all fields.
	//  Fields array should contain:
	//  * label: the displayed label of the field. Required.
	//  * description: the field description, displayed under the field. Optional
	//  * suffix: displays right of the field entry. Optional
	//  * default_value: default value if field is empty. Optional
	//  * dropdown: allows you to offer dropdown functionality on field. Value is array listed below. Optional
	//  * function: will call function other than default text field to display options. Option
	//  * callback: will run callback function to validate field. Optional
	//  * All variables are sent to display function as array, therefore other variables can be added if needed for display purposes
	 
	var $sections = array(
	      'geopicker' => array(
	          'title' => 'Geopicker settings',
	          'description' => "The geopicker popup, which shows the map where you can search for a location.",
	          'fields' => array(
	            'autolocate' => array (
	              'label' => "Autolocate",
	              'description' => "When opening the geopicker map, try to autolocate me if possible (W3C Geolocation API)",
	              'dropdown' => "dd_onoff",
	              'default_value' => "true"),
	              )
	          ),
	      'tags' => array(
	          'title' => 'Geotagging settings',
	          'description' => "Which kind of tags should be displayed?",
	          'fields' => array(
	            'addMetatags' => array (
	              'label' => "Add geo-meta-tags",
	              'description' => "Render geo metatags in html head section of pages/posts",
	              'dropdown' => "dd_onoff",
	              'default_value' => "true"),
	            'addPosttags' => array (
	              'label' => "Add geo-post-tags",
	              'description' => "Automatically add geo post tags when updating the metatags in backend",
	              'dropdown' => "dd_onoff",
	              'default_value' => "true"),
	            'addCityToPosttags' => array (
	              'label' => "Add city to post-tags",
	              'description' => "Automatically add the name of the city to the post tags when updating metatags in backend",
	              'dropdown' => "dd_onoff",
	              'default_value' => "true"),
	            'addFeedtags' => array (
	              'label' => "Add geo-feed-tags",
	              'description' => "Add the configured location of a post to the RSS/Atom/RDF news feed (in Geo, GeoRSS/KML, GeoURL and ICBM format)",
	              'dropdown' => "dd_onoff",
	              'default_value' => "true"),
	            'addMicroformats' => array (
	              'label' => "Add geo-microformats",
	              'description' => "Add the configured location of a post to the HTML source code (in http://microformats.org/wiki/geo format)", 
	              'dropdown' => "dd_onoff",
	              'default_value' => "true")
	              )
	          ),
	      'map' => array(
	          'title' => 'Map settings',
	          'description' => "Based on the latitude/longitude entered into the geo.position field, a map is displayed before/after/within the post.",
	          'fields' => array(
	            'addMap' => array (
	              'label' => "Show map",
	              'description' => "", 
	              'dropdown' => "dd_onoff",
	              'default_value' => "true"),
	            'mapLocation' => array (
	              'label' => "Map location",
	              'description' => "", 
	              'dropdown' => "dd_location",
	              'default_value' => "after"), 
	            'zoomLevel' => array (
	              'label' => "Zoom level",
	              'description' => "", 
	              'dropdown' => "dd_zoomlevel",
	              'default_value' => "12"),  
	            'mapType' => array (
	              'label' => "Map type",
	              'description' => "", 
	              'dropdown' => "dd_maptype",
	              'default_value' => "ROADMAP"), 
	            'mapWidth' => array (
	              'label' => "Map width",
	              'description' => "", 
	              'default_value' => "100%"),  
	            'mapHeight' => array (
	              'label' => "Map height",
	              'description' => "", 
	              'default_value' => "200px"),  
	            'mapFloat' => array (
	              'label' => "Map float",
	              'description' => "", 
	              'dropdown' => "dd_float",
	              'default_value' => "none"), 
	              ),
	          ),
	    );
	 
	 // DROPDOWN OPTIONS
	 // For drop down choices.  Each set of choices should be unique array
	 // Use key => value to indicate name => display name
	 // For default_value in options field use key, not value
	 // You can have multiple instances of the same dropdown options
	 
	var $dropdown_options = array (
	    'dd_onoff' => array (
	        'false' => "disabled",
	        'true' => "enabled",
	        ),
	    'dd_location' => array (
	        'before' => "Before post",
	        'after' => "After post",
	        'shortcode' => "Replace [mygp_map] in post",
	        ),
	    'dd_zoomlevel' => array (
	        '1' => "1 - World",
	        '2' => "2",
	        '3' => "3 - Continent",
	        '4' => "4",
	        '5' => "5 - Country",
	        '6' => "6",
	        '7' => "7 - State",
	        '8' => "8",
	        '9' => "9",
	        '10' => "10",
	        '11' => "11 - City",
	        '12' => "12",
	        '13' => "13",
	        '14' => "14 - District",
	        '15' => "15",
	        '16' => "16 - Street",
	        '17' => "17",
	        '18' => "18 - Street corner",
	        '19' => "19",
	        '20' => "20",
	        '21' => "21 - House",
	        ),
	    'dd_maptype' => array (
	        'ROADMAP' => "roadmap",
	        'SATELLITE' => "satellite",
	        'HYBRID' => "hybrid",
	        'TERRAIN' => "terrain",
	        ),
	    'dd_float' => array (
	        'none' => "none",
	        'left' => "left",
	        'right' => "right",
	        ),
	    );
 
//  end class
};
 
class mygpGeotagsGeoMetatags_settings {
 
	function mygpGeotagsGeoMetatags_settings($settings_class) {
	    global $mygpGeotagsGeoMetatags_settings;
	    $mygpGeotagsGeoMetatags_settings = get_class_vars($settings_class);
	 
	    if (function_exists('add_action')) :
	      add_action('admin_init', array( &$this, 'plugin_admin_init'));
	      add_action('admin_menu', array( &$this, 'plugin_admin_add_page'));
	      endif;
	}
	 
	function plugin_admin_add_page() {
	  global $mygpGeotagsGeoMetatags_key, $mygpGeotagsGeoMetatags_settings;
	  add_options_page(__($mygpGeotagsGeoMetatags_settings['title'], $mygpGeotagsGeoMetatags_key), __($mygpGeotagsGeoMetatags_settings['nav_title'], $mygpGeotagsGeoMetatags_key), 'manage_options', $mygpGeotagsGeoMetatags_settings['page_name'], array( &$this,'plugin_options_page'));
	  }
	 
	function plugin_options_page() {
	  global $mygpGeotagsGeoMetatags_key, $mygpGeotagsGeoMetatags_settings;
	printf('</pre>
	<div>
	<h2>%s</h2>
	%s
	<form action="options.php" method="post">',__($mygpGeotagsGeoMetatags_settings['title'], $mygpGeotagsGeoMetatags_key),__($mygpGeotagsGeoMetatags_settings['intro_text'], $mygpGeotagsGeoMetatags_key));
	 settings_fields($mygpGeotagsGeoMetatags_settings['group']);
	 do_settings_sections($mygpGeotagsGeoMetatags_settings['page_name']);
	 printf('<br /><input type="submit" name="Submit" value="%s" /></form></div>
	<p>&nbsp;</p>
	<p><a href="http://www.mygeoposition.com" target="_blank"><img src="%simages/powered-by-mygeopositioncom.png" /></a><br /></p>
	<p><b>%s</b>: <a href="http://api.mygeoposition.com" target="_blank">http://api.mygeoposition.com</a></p>
	<p>%s: <a href="http://www.mygeoposition.com" target="_blank">http://www.mygeoposition.com</a></p>
	<p>%s: <a href="mailto:info@filzhut.de?subject=MyGeoPosition.com GeoTags GeoMetatags WordPress Plugin" target="_blank">info@filzhut.de</a></p>
	<pre>
	', __('Save Changes', $mygpGeotagsGeoMetatags_key), MYGP_GEOTAGS_GEOMETATGS_PLUGINPATH, __('Add a free GeoPicker to your website or application', $mygpGeotagsGeoMetatags_key), __('MyGeoPosition.com - Free address geocoding / geo-metatags / geotags / kml files', $mygpGeotagsGeoMetatags_key), __('Comments, questions, bug reports? Please contact us', $mygpGeotagsGeoMetatags_key));
	  }
	 
	function plugin_admin_init(){
	  global $mygpGeotagsGeoMetatags_key, $mygpGeotagsGeoMetatags_settings;
	  foreach ($mygpGeotagsGeoMetatags_settings["sections"] AS $section_key=>$section_value) :
	    add_settings_section($section_key, __($section_value['title'], $mygpGeotagsGeoMetatags_key), array( &$this, 'plugin_section_text'), $mygpGeotagsGeoMetatags_settings['page_name'], $section_value);
	    foreach ($section_value['fields'] AS $field_key=>$field_value) :
	      $function = (!empty($field_value['dropdown'])) ? array( &$this, 'plugin_setting_dropdown' ) : array( &$this, 'plugin_setting_string' );
	      $function = (!empty($field_value['function'])) ? $field_value['function'] : $function;
	      $callback = (!empty($field_value['callback'])) ? $field_value['callback'] : NULL;
	      add_settings_field($mygpGeotagsGeoMetatags_settings['group'].'_'.$field_key, __($field_value['label'], $mygpGeotagsGeoMetatags_key), $function, $mygpGeotagsGeoMetatags_settings['page_name'], $section_key,array_merge($field_value,array('name' => $mygpGeotagsGeoMetatags_settings['group'].'_'.$field_key)));
	      register_setting($mygpGeotagsGeoMetatags_settings['group'], $mygpGeotagsGeoMetatags_settings['group'].'_'.$field_key,$callback);
	      endforeach;
	    endforeach;
	  }
	 
	function plugin_section_text($value = NULL) {
	  global $mygpGeotagsGeoMetatags_key, $mygpGeotagsGeoMetatags_settings;
	  printf("
	%s
	 
	",__($mygpGeotagsGeoMetatags_settings['sections'][$value['id']]['description'], $mygpGeotagsGeoMetatags_key));
	}
	 
	function plugin_setting_string($value = NULL) {
	  global $mygpGeotagsGeoMetatags_key;
	  $options = get_option($value['name']);
	  $default_value = (!empty ($value['default_value'])) ? $value['default_value'] : NULL;
	  printf('<input id="%s" type="text" name="%1$s[text_string]" value="%2$s" size="40" /> %3$s%4$s',
	    $value['name'],
	    (!empty ($options['text_string'])) ? $options['text_string'] : $default_value,
	    (!empty ($value['suffix'])) ? $value['suffix'] : NULL,
	    (!empty ($value['description'])) ? sprintf("<em>%s</em>", __($value['description'], $mygpGeotagsGeoMetatags_key)) : NULL);
	  }
	 
	function plugin_setting_dropdown($value = NULL) {
	  global $mygpGeotagsGeoMetatags_key, $mygpGeotagsGeoMetatags_settings;
	  $options = get_option($value['name']);
	  $default_value = (!empty ($value['default_value'])) ? $value['default_value'] : NULL;
	  $current_value = ($options['text_string']) ? $options['text_string'] : $default_value;
	    $chooseFrom = "";
	    $choices = $mygpGeotagsGeoMetatags_settings['dropdown_options'][$value['dropdown']];
	  foreach($choices AS $key=>$option) :
	    $chooseFrom .= sprintf('<option value="%s" %s>%s</option>',
	      $key,($current_value == $key ) ? ' selected="selected"' : NULL, __($option, $mygpGeotagsGeoMetatags_key));
	    endforeach;
	    printf('
	<select id="%s" name="%1$s[text_string]">%2$s</select>
	%3$s',$value['name'],$chooseFrom,
	  (!empty ($value['description'])) ? sprintf("<em>%s</em>",__($value['description'], $mygpGeotagsGeoMetatags_key)) : NULL);
	  }

 
//end class
}
 
$mygpGeotagsGeoMetatags_settings_init = new mygpGeotagsGeoMetatags_settings('mygpGeotagsGeoMetatags_settings_config');
?>