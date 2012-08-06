<?php

/*
Plugin Name: TFP Role Capabilities 
Plugin URI: http://openplans.org/
Description: Edits the default WordPress roles and capabilities
Author: Andrew Cochran
Author URI: http://openplans.org/team/#andy-cochran
*/

// store the 'Contributor' role
$tfp_editor = get_role('editor');

// add a capability of 'upload_files'
$tfp_editor->add_cap('edit_theme_options');


?>