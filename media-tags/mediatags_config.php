<?php
define('MEDIA_TAGS_VERSION', "3.0");
define('MEDIA_TAGS_DATA_VERSION', "2.0");

define('MEDIA_TAGS_TAXONOMY', 'media-tags');

define('MEDIA_TAGS_ADMIN_MENU_KEY', 'media-tags');
define('MEDIA_TAGS_REWRITERULES','1');

define('MEDIA_TAGS_URL_DEFAULT', MEDIA_TAGS_TAXONOMY);
$mediatag_base = get_option('mediatag_base', 'media-tags');
define('MEDIA_TAGS_URL', $mediatag_base);

define('MEDIA_TAGS_QUERYVAR', 'media-tags');

define('MEDIA_TAGS_TEMPLATE', 'mediatag.php');
define('MEDIA_TAGS_RSS_TEMPLATE', 'mediatags_rss.php');

define('MEDIA_TAGS_I18N_DOMAIN', 'media-tags');
// Per the ThickBox website it is no longer supported. 
//DEFINE('MEDIA_TAGS_POPUP', 'THICKBOX');
DEFINE('MEDIA_TAGS_POPUP', 'JQUERY-UI');

// Support for User Roles
define( 'MEDIATAGS_SETTINGS_CAP', 		'mediatags_settings' );
define( 'MEDIATAGS_MANAGE_TERMS_CAP', 	'mediatags_manage_terms' );
define( 'MEDIATAGS_EDIT_TERMS_CAP', 	'mediatags_edit_terms' );
define( 'MEDIATAGS_DELETE_TERMS_CAP', 	'mediatags_delete_terms' );
define( 'MEDIATAGS_ASSIGN_TERMS_CAP', 	'mediatags_assign_terms' );
define( 'MEDIATAGS_MANAGE_ROLE_CAP', 	'mediatags_manage_role_cap' );

$mediatags_caps = array();
$mediatags_caps[MEDIATAGS_SETTINGS_CAP] 		= _x('Manage Media-Tags Settings', 'user roles label', 	MEDIA_TAGS_I18N_DOMAIN);
$mediatags_caps[MEDIATAGS_MANAGE_ROLE_CAP] 		= _x('Manage Role/Cap', 'user roles label', 			MEDIA_TAGS_I18N_DOMAIN);
$mediatags_caps[MEDIATAGS_MANAGE_TERMS_CAP] 	= _x('Manage Media-Tags Terms', 'user roles label', 	MEDIA_TAGS_I18N_DOMAIN);
$mediatags_caps[MEDIATAGS_EDIT_TERMS_CAP] 		= _x('Edit Media-Tags Terms', 'user roles label', 		MEDIA_TAGS_I18N_DOMAIN);
$mediatags_caps[MEDIATAGS_DELETE_TERMS_CAP] 	= _x('Delete Media-Tags Terms', 'user roles label', 	MEDIA_TAGS_I18N_DOMAIN);
$mediatags_caps[MEDIATAGS_ASSIGN_TERMS_CAP] 	= _x('Assign Media-Tags Terms', 'user roles label', 	MEDIA_TAGS_I18N_DOMAIN);
