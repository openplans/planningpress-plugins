<?php
/*
Plugin Name: Media Tags
Plugin URI: http://www.codehooligans.com/projects/wordpress/media-tags/
Description: Provides ability to tag media/attachments via Media Management screens
Author: Paul Menard
Version: 3.0.4
Author URI: http://www.codehooligans.com
*/

include_once ( dirname(__FILE__) . "/mediatags_config.php");
include_once ( dirname(__FILE__) . '/mediatags_admin.php' );
include_once ( dirname(__FILE__) . "/mediatags_rewrite.php");
include_once ( dirname(__FILE__) . "/mediatags_template_functions.php");
include_once ( dirname(__FILE__) . "/mediatags_shortcodes.php");
include_once ( dirname(__FILE__) . "/mediatags_settings.php");
include_once ( dirname(__FILE__) . "/mediatags_thirdparty.php");
include_once ( dirname(__FILE__) . "/mediatags_feed.php");
include_once ( dirname(__FILE__) . "/mediatags_export_import.php");
include_once ( dirname(__FILE__) . "/mediatags_bulk_admin.php");

class MediaTags {

	var $plugindir_url;
	var $thirdparty;
	var $default_caps;
	var $plugin_version;
	
	function MediaTags()
	{
		global $wp_version;
		
		$this->plugin_version = MEDIA_TAGS_VERSION;

		$plugindir_node 						= dirname(plugin_basename(__FILE__));	
		//$this->plugindir_url 					= get_bloginfo('wpurl') . "/wp-content/plugins/". $plugindir_node;
		$this->plugindir_url 					= WP_CONTENT_URL . "/plugins/". $plugindir_node;
		
		
	
		// Setup flags for third-party plugins we can integrate with
		$this->thirdparty->google_sitemap 		= false;

		$this->default_caps						= array();
		$this->default_caps['manage_terms'] 	= MEDIATAGS_MANAGE_TERMS_CAP;
		$this->default_caps['edit_terms'] 		= MEDIATAGS_EDIT_TERMS_CAP;
		$this->default_caps['delete_terms'] 	= MEDIATAGS_DELETE_TERMS_CAP;
		$this->default_caps['assign_terms'] 	= MEDIATAGS_ASSIGN_TERMS_CAP;
		
		add_action( 'init', 							array(&$this, 'init') );		
		add_action( 'admin_init', 						'mediatags_admin_init' );

		register_activation_hook(__FILE__, array(&$this, 'mediatags_activate_plugin'));
		//register_deactivation_hook( __FILE__, array(&$this, 'mediatags_deactivate_plugin') );
		
		// Support for the Google Sitemap XML plugin
		add_action("sm_buildmap", 'mediatags_google_sitemap_pages');				

		// Add our sub-panel to the Media section. But only if WP 2.7 or higher!
		// Not sure why this has to be here and not in admin_init. 
	    if ( version_compare( $wp_version, '2.7', '>=' ) )
		{
			add_action('admin_menu', 'mediatags_admin_panels');
		}
	}

	function init() {

		$plugin_dir = basename(dirname(__FILE__))."/lang";
		load_plugin_textdomain( MEDIA_TAGS_I18N_DOMAIN, null, $plugin_dir );
		
		$this->register_taxonomy();
		add_filter( 'body_class', 						'mediatags_body_class' );
		add_filter( 'pre_get_posts',					'mediatags_pre_get_posts_filter' );

		add_action( 'wp_head', 							'mediatags_wp_head' );
		
		add_shortcode( 'media-tags', 					'mediatags_shortcode_handler' );		
		add_action( 'template_redirect', 				'mediatags_template_redirect' );	
	}

	function register_taxonomy() {
		// Add new taxonomy, make it hierarchical (like categories)
/*
		$labels = mediatags_get_taxonomy_labels();
		  $labels = array(
		    'name' 				=> _x( 'Media-Tags', 			'taxonomy general name', 		MEDIA_TAGS_I18N_DOMAIN ),
		    'singular_name' 	=> _x( 'Media-Tag', 			'taxonomy singular name', 		MEDIA_TAGS_I18N_DOMAIN ),
		    'search_items' 		=> _x( 'Search Media-Tags', 	'taxonomy search items', 		MEDIA_TAGS_I18N_DOMAIN ),
			'popular_items' 	=> _x( 'Popular Media-Tags', 	'taxonomy popular item', 		MEDIA_TAGS_I18N_DOMAIN),		
		    'all_items' 		=> _x( 'All Media-Tags', 		'taxonomy all items', 			MEDIA_TAGS_I18N_DOMAIN ),
		    'parent_item' 		=> _x( 'Parent Media-Tag', 		'taxonomy parent item', 		MEDIA_TAGS_I18N_DOMAIN ),
		    'parent_item_colon' => _x( 'Parent Media-Tag:', 	'taxonomy parent item colon', 	MEDIA_TAGS_I18N_DOMAIN ),
		    'edit_item' 		=> _x( 'Edit Media-Tag', 		'taxonomy edit item', 			MEDIA_TAGS_I18N_DOMAIN ), 
		    'update_item' 		=> _x( 'Update Media-Tag', 		'taxonomy update item', 		MEDIA_TAGS_I18N_DOMAIN ),
		    'add_new_item' 		=> _x( 'Add New Media-Tag', 	'taxonomy add new item', 		MEDIA_TAGS_I18N_DOMAIN ),
		    'new_item_name' 	=> _x( 'New Media-Tag Name', 	'taxonomy new item name', 		MEDIA_TAGS_I18N_DOMAIN ),
		  );
*/
		$labels = mediatags_get_taxonomy_labels();
	

		register_taxonomy(MEDIA_TAGS_TAXONOMY, MEDIA_TAGS_TAXONOMY, array(
		    'hierarchical' 		=> false,
		    'labels' 			=> $labels,
			'show_ui' 			=> false,
			'show_in_nav_menus'	=> true,
			'show_tagcloud'		=> true,
		    'query_var' 		=> true,
		    'rewrite' 			=> array( 'slug' => MEDIA_TAGS_URL, 'with_front' => true ),
			'capabilities' 		=> $this->default_caps
		  ));
	}

	function mediatags_activate_plugin()
	{
		// First see if we need to convert the data. This really only applied to pre-Taxonomy versions
		include_once ( dirname (__FILE__) . '/mediatags_legacy_convert.php' );
		mediatags_plugin_version_check();

		// Support for Role Manager plugin http://www.im-web-gefunden.de/wordpress-plugins/role-manager/
		mediatags_add_default_capabilities();
		//mediatags_reconcile_counts();
	}
	
	function mediatags_deactivate_plugin()
	{
		// Nothing to do really. 
	}
	
	// Still support the original legacy version of the function. 
	// Force use of the post_parent parameter. Users wanting to search globally across all media tags should
	// switch to using the get_attachments_by_media_tags() function.
	function get_media_by_tag($args='')
	{
		global $post;
		
		$r = wp_parse_args( $args, $defaults );
		if (!isset($r['post_parent']))
		{
			if ($post)
				$r['post_parent'] = $post->ID;
			else
				return;
		}	
		return $this->get_attachments_by_media_tags($args);
	}
	
	function get_attachments_by_media_tags($args='')
	{
		global $post;

		$defaults = array(
			'call_source' => '',
			'display_item_callback' => 'default_item_callback',
			'media_tags' => '', 
			'media_types' => null,
			'numberposts' => '-1',
			'orderby' => 'menu_order',			
			'order' => 'ASC',
			'offset' => '0',
			'post_type'	=> '',
			'return_type' => '',
			'search_by' => 'slug',
			'size' => 'medium',
			'tags_compare' => 'OR',
			'nopaging'	=> ''
		);
		$r = wp_parse_args( $args, $defaults );
		
		if ((!$r['media_tags']) || (strlen($r['media_tags']) == 0))
			return;
		
//		if ((!$r['post_parent']) || (strlen($r['post_parent']) == 0))
//		{
//			if ($post)
//				$r['post_parent'] = $post->ID;
//			else
//				return;
//		}
		
		// Future support for multiple post_parents --- Coming Soon!
//		if (strlen($r['post_parent']))
//		{
//			if (!is_array($r['post_parent']))
//			{
//				$r['post_parent'] = (array) $r['post_parent'];				
//			}			
//		}
//		echo "post_parent<pre>"; print_r($r['post_parent']); echo "</pre>";

		// Force 'OR' on compare if searching by name (not slug). This is because the name search will return multiple
		// values per each 'media_tags' searched item.
		if ($r['search_by'] != 'slug')
			$r['tags_compare'] = 'OR';

		// First split the comma-seperated media-tags list into an array
		$r['media_tags_array'] = split(',', $r['media_tags']);
		if ($r['media_tags_array'])
		{
			foreach($r['media_tags_array'] as $idx => $val)
			{
				$r['media_tags_array'][$idx] = sanitize_title_with_dashes($val);
			}
		}

		// Next split the comma-seperated media-types list into an array
		if ($r['media_types'])
		{
			$r['media_types_array'] = split(',', $r['media_types']);
			if ($r['media_types_array'])
			{
				foreach($r['media_types_array'] as $idx => $val)
				{
					$r['media_types_array'][$idx] = sanitize_title_with_dashes($val);
				}
			}
		}
		//echo "r<pre>"; print_r($r); echo "</pre>";
		
		// Next lookup each term in the terms table. 
		$search_terms_array = array();
		if ($r['media_tags_array'])
		{
			foreach($r['media_tags_array'] as $search_term)
			{
				$get_terms_args['hide_empty'] = 0;

				if ($r['search_by'] != "slug")
					$get_terms_args['search'] = $search_term;
				else
					$get_terms_args['slug'] = $search_term;
					
				$terms_item = get_terms( MEDIA_TAGS_TAXONOMY, $get_terms_args );
				if ($terms_item)
					$search_terms_array[$search_term] = $terms_item;
			}
		}
		
		//echo "search_terms_array<pre>"; print_r($search_terms_array); echo "</pre>";
		
		$objects_ids_array = array();
		if (count($search_terms_array))
		{
			foreach($search_terms_array as $search_term_items)
			{
				if ($search_term_items) {
					foreach($search_term_items as $search_term_item)
					{				
						$objects_ids = get_objects_in_term($search_term_item->term_id, MEDIA_TAGS_TAXONOMY);
						if ($objects_ids)
							$objects_ids_array[$search_term_item->slug] = $objects_ids;
						else
							$objects_ids_array[$search_term_item->slug] = array();
					}
				}
			}
		}
		
		if (count($objects_ids_array) > 1)
		{
			foreach($objects_ids_array as $idx_ids => $object_ids_item)
			{
				if ((!isset($array_unique_ids)) && ($idx_ids == 0))
				{
					$array_unique_ids = $object_ids_item;
				}
				if (strtoupper($r['tags_compare']) == strtoupper("AND"))
				{
					$array_unique_ids = array_unique(array_intersect($array_unique_ids, $object_ids_item));
				}
				else
				{
					$array_unique_ids = array_unique(array_merge($array_unique_ids, $object_ids_item));
				}
			}			
			sort($array_unique_ids);
		}
		else if (count($objects_ids_array) == 1)		
		{
			foreach($objects_ids_array as $idx_ids => $object_ids_item)
			{
				$array_unique_ids = $object_ids_item;
				break;
			}
		}
				
		$object_ids_str = "";
		if ($array_unique_ids)
		{
			$object_ids_str = implode(',', $array_unique_ids); 
		}

		if ($object_ids_str)
		{
			$query_array = array(
				'post_type'			=> 'attachment',
				'numberposts'		=> 	-1
			);
			
			if ((isset($r['post_parent'])) && (intval($r['post_parent']) > 0))
				$query_array['post_parent'] = $r['post_parent'];
			if ((isset($r['nopaging'])) && (strlen($r['nopaging']))) 
				$query_array['nopaging'] = $r['nopaging'];
			if ((isset($r['post_type'])) && (strlen($r['post_type']))) 
				$query_array['post_type'] = $r['post_type'];

			//echo "query_array<pre>"; print_r($query_array); echo "</pre>";
			$attachment_posts = get_posts($query_array);

			$attachment_posts_ids = array();
			if ($attachment_posts)
			{
				foreach($attachment_posts as $attachment_post)
				{
					$attachment_posts_ids[] = $attachment_post->ID;
				}
			}

			$result = array_intersect($array_unique_ids, $attachment_posts_ids);
			if ($result)
			{				
				$get_post_args['post_type'] 	= "attachment";
				$get_post_args['numberposts'] 	= $r['numberposts'];
				$get_post_args['offset']		= $r['offset'];
				$get_post_args['orderby']		= $r['orderby'];
				$get_post_args['order']			= $r['order'];
				$get_post_args['include']		= implode(',', $result);

				$attachment_posts = get_posts($get_post_args);
				
				// Now that we have the list of all matching posts we need to filter by the media type is provided
				if ((isset($r['media_types_array'])) && (count($r['media_types_array'])))
				{
					foreach($attachment_posts as $attachment_idx => $attachment_post)
					{
						$ret_mime_match = wp_match_mime_types($r['media_types_array'], $attachment_post->post_mime_type);
						//echo "ret_mime_match<pre>"; print_r($ret_mime_match); echo "</pre>";
						if (!$ret_mime_match)
							unset($attachment_posts[$attachment_idx]);
					}
				}

				// If the calling system doesn't want the whole list.
				if (($r['offset'] > 0) || ($r['numberposts'] > 0))
					$attachment_posts = array_slice($attachment_posts, $r['offset'], $r['numberposts']);
				
				if ($r['return_type'] === "li")
				{
					$attachment_posts_list = "";
					foreach($attachment_posts as $attachment_idx => $attachment_post)
					{
						if ((strlen($r['display_item_callback']))
						 && (function_exists($r['display_item_callback'])))
							$attachment_posts_list .= call_user_func($r['display_item_callback'], $attachment_post, $r['size']);
					}
					return $attachment_posts_list;
				}
				else
					return $attachment_posts;
			}

		}
	}
}
$mediatags = new MediaTags();