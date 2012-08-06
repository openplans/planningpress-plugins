<?php
function mediatags_admin_init()
{
	global $mediatags, $wp_version;

	add_action( 'admin_head', 						'mediatags_admin_head_proc' );
	add_action( 'admin_footer', 					'mediatags_admin_footer' );		
	add_action( 'plugins_loaded', 					'mediatag_thirdparty_support' );
			
	add_action( 'wp_ajax_media_tags_bulk_action', 	'media_tags_bulk_action_callback' );
	add_filter( 'attachment_fields_to_edit', 		'mediatags_show_fields_to_edit', 11, 2 );
	add_filter( 'attachment_fields_to_save', 		'meditags_process_attachment_fields_to_save', 11, 2 );
	add_action( 'delete_attachment', 				'mediatags_delete_attachment_proc' );

	// Add dropdowns above Media > Library listing
	add_action( 'restrict_manage_posts', 			'mediatags_filter_posts' );

	add_action( 'wp_ajax_get_mediatags_ajax', 		'mediatags_get_mediatags_ajax' );
	
	// These hook into the Media Upload popup tabs
	add_filter( 'media_upload_tabs', 				'mediatag_upload_tab' );
	add_action( 'media_upload_mediatags', 			'media_upload_mediatags' );

	// Handle Export/Import interaction
	add_action('export_wp', 						'mediatags_wp_export_metadata');
	add_action('import_post_meta', 					'mediatags_wp_import_metadata', 10, 3);

	$mediatag_admin_bulk_library 					= get_option('mediatag_admin_bulk_library', 'yes'); 
	$mediatag_admin_bulk_inline 					= get_option('mediatag_admin_bulk_inline', 'yes'); 
	
	if ($mediatag_admin_bulk_inline == "yes")
	{
		add_filter('media_upload_gallery', 'media_upload_gallery_tab', 10, 1);	// The Gallery Tab
		add_filter('media_upload_library', 'media_upload_gallery_tab', 10, 1);	// The Media Library Tab
	}

	// If we are viewing the Media > Library page. This needs hooks to display the jQuery-UI popup for the Bulk admin
	// if enabled via the Media-Tags > Settings page.
	if (mediataga_check_url('wp-admin/upload.php'))		
	{
		wp_enqueue_style( 'mediatags-stylesheet', $mediatags->plugindir_url .'/css/mediatags_style_admin.css', 
			false, $mediatags->plugin_version);

		if (($mediatag_admin_bulk_library == "yes") && (current_user_can( MEDIATAGS_ASSIGN_TERMS_CAP )))
		{
			if (MEDIA_TAGS_POPUP == 'JQUERY-UI')
			{
				wp_enqueue_script('jquery'); 
				wp_enqueue_script('jquery-ui-core'); 
				wp_enqueue_script('jquery-ui-dialog');
				wp_enqueue_style( 'mediatags-jquery-ui', 
					$mediatags->plugindir_url .'/js/jquery-ui/css/flick/jquery-ui-1.7.3.custom.css',
					array('mediatags-stylesheet'), $mediatags->plugin_version );
			}
			wp_enqueue_script('mediatags-bulk-common', $mediatags->plugindir_url .'/js/mediatags_bulk_common.js',
				array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), $mediatags->plugin_version);
			wp_enqueue_script('mediatags-bulk-library', $mediatags->plugindir_url .'/js/mediatags_bulk_library.js',
				array('jquery', 'mediatags-bulk-common'), $mediatags->plugin_version);
			wp_enqueue_script('mediatags', $mediatags->plugindir_url .'/js/mediatags.js',
				array('jquery'), $mediatags->plugin_version);

// This logic didn't make it into 3.1 final. Lost it somewhere between 3.1rc2 and rc3
//		    if ( version_compare( $wp_version, '3.0.999', '>' ) )
//			{
//				add_filter( 'bulk_actions-upload', 			'mediatags_admin_media_bulk_actions' );
//			}							
		}
	}
	// Else If we are viewing the Media popup via the Post/Page editor. if enabled via the Media-Tags > Settings page.
	else if (mediataga_check_url('wp-admin/media-upload.php'))			
	{		
		wp_enqueue_style( 'mediatags-stylesheet', $mediatags->plugindir_url .'/css/mediatags_style_admin.css',
			false, $mediatags->plugin_version);

		if (($mediatag_admin_bulk_inline == "yes") && (current_user_can( MEDIATAGS_ASSIGN_TERMS_CAP ))
		 && ((isset($_GET['tab'])) && (($_GET['tab'] == "gallery") || ($_GET['tab'] == "library"))) )
		{
			wp_enqueue_script('mediatags-bulk-common', $mediatags->plugindir_url .'/js/mediatags_bulk_common.js',
				array('jquery'), $mediatags->plugin_version);
			wp_enqueue_script('mediatags-bulk-inline', $mediatags->plugindir_url .'/js/mediatags_bulk_inline.js',
				array('jquery', 'mediatags-bulk-common'), $mediatags->plugin_version);
			wp_enqueue_script('mediatags', $mediatags->plugindir_url .'/js/mediatags.js',
				array('jquery'), $mediatags->plugin_version);			
		}
	}
	else if (mediataga_check_url('wp-admin/admin.php'))			
	{
		if ((isset($_GET['page'])) 
			&& ( ($_GET['page'] == "mediatags_settings_panel") 
				|| ($_GET['page'] == "mediatags_roles_panel")
				|| ($_GET['page'] == "mediatags_help_panel")
				|| ($_GET['page'] == "mediatags_thirdparty_panel") ))		  
		{
			wp_enqueue_style( 'mediatags-stylesheet', $mediatags->plugindir_url .'/css/mediatags_style_admin.css',
				false, $mediatags->plugin_version);			
			wp_enqueue_script('mediatags', $mediatags->plugindir_url .'/js/mediatags.js',
				array('jquery'), $mediatags->plugin_version);			
		}
	}
	else if (mediataga_check_url('wp-admin/media.php'))				
	{
		wp_enqueue_style( 'mediatags-stylesheet', $mediatags->plugindir_url .'/css/mediatags_style_admin.css',
			false, $mediatags->plugin_version);
		wp_enqueue_script('mediatags', $mediatags->plugindir_url .'/js/mediatags.js',
			array('jquery'), $mediatags->plugin_version);			
	}

//	if (mediataga_check_url('wp-admin/async-upload.php'))		
//	{
		//wp_enqueue_style( 'mediatags-stylesheet', $mediatags->plugindir_url .'/css/mediatags_style_admin.css',
		//	false, $mediatags->plugin_version);
		//wp_enqueue_script('mediatags', $mediatags->plugindir_url .'/js/mediatags.js',
		//	array('jquery'), $mediatags->plugin_version);			
//	}

	if (function_exists('mediatags_settings_api_init'))
		mediatags_settings_api_init();

	//add_filter( 'manage_media_columns', 			'mediatags_library_column_header' );
	add_filter( 'manage_upload_columns', 			'mediatags_library_column_header' );
	add_filter( 'manage_edit-media-tags_columns', 	'mediatags_terms_column_header' );
			
	add_action( 'manage_media_custom_column', 		'mediatags_library_column_row', 10, 2 );
	add_filter( 'manage_media-tags_custom_column', 	'mediatags_terms_column_row', 10, 3 );

    if ( version_compare( $wp_version, '3.0.999', '>' ) )
	{
		//add_filter( 'bulk_actions-upload', 			'mediatags_admin_media_bulk_actions' );

		add_filter( 'manage_upload_sortable_columns', 'mediatags_admin_media_sort_columns' );
		add_filter( 'manage_edit-media-tags_sortable_columns', 'mediatags_admin_terms_sort_columns' );
		add_filter( 'get_terms_args', 'mediatags_admin_terms_args_filter', 10, 2);
	}
}

function mediataga_check_url($url='')
{
	if (!$url) return;
	
	$_REQUEST_URI = explode('?', $_SERVER['REQUEST_URI']);
	$url_len 	= strlen($url);
	$url_offset = $url_len * -1;

	// If out test string ($url) is longer than the page URL. skip
	if (strlen($_REQUEST_URI[0]) < $url_len) return;

	if ($url == substr($_REQUEST_URI[0], $url_offset, $url_len))
			return true;
}

// New for WP 3.1 - Adds out Bulk action item to the Bulk admin dropdown
function mediatags_admin_media_bulk_actions($actions)
{
	$actions[MEDIA_TAGS_TAXONOMY] = _x('Media-Tags', 'column name', MEDIA_TAGS_I18N_DOMAIN);
	return $actions;
}

function mediatags_admin_media_sort_columns($cols)
{
	$cols[MEDIA_TAGS_TAXONOMY] = MEDIA_TAGS_TAXONOMY;
	return $cols;
}

function mediatags_admin_terms_sort_columns($cols)
{
	$cols['mediatags_used'] = 'mediatags_used';
	return $cols;	
}

function mediatags_wp_head()
{
	add_mediatags_alternate_link();
}

function mediatags_admin_head_proc()
{
	// All header output moved to the admin_init function.
}

function mediatags_admin_footer()
{
	global $mediatags, $wp_version;
	
	if (function_exists('get_current_screen'))
		$current_screen = get_current_screen();
	else
	{
		global $current_screen;			
	}
	//echo "current_screen<pre>"; print_r($current_screen); echo "</pre>";

	if ((isset($current_screen->id)) 
	 && (($current_screen->id == "upload") || ($current_screen->id == "media-upload")) )
	{
		$mediatag_admin_bulk_library = get_option('mediatag_admin_bulk_library', 'yes'); 
		$mediatag_admin_bulk_inline = get_option('mediatag_admin_bulk_inline', 'yes'); 
		
		if ($mediatag_admin_bulk_library == "yes")
		{	
			mediatags_bulk_admin_panel();	
			?>
			<div id="media-tags-bulk-selection-error" title="Media Tags Selection Error" style="display:none"><?php echo __('<p>You must first select which Media Items to change.</p><p>Please close this dialog window and make your selection.</p>', MEDIA_TAGS_I18N_DOMAIN); ?>
			</div>
			<?php
			show_mediataga_admin_buttons_text();

			// In WP 3.1 we can use a hook to add items to the bulk action dropdown. Prior to 3.1 we do this via jQuery.
			// 2011-02-24: No this was removed in 3.1 final. Lost is between 3.1rc2 and rc3.
//		    if ( version_compare( $wp_version, '3.0.999', '<' ) )
			{
				?>
				<script type='text/javascript'>
				jQuery(document).ready(function() {
					jQuery('form#posts-filter select[name=action]').append('<option value="media-tags"><?php 
						echo _x('Media-Tags', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></option>');				
					jQuery('form#posts-filter select[name=action2]').append('<option value="media-tags"><?php 
						echo _x('Media-Tags', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></option>');				
						
					jQuery('select#media-tags').change(function(){
						var media_tags_filter = jQuery(this).val();
						if (media_tags_filter !== "")
						{
							//alert('media_tags_filter=['+media_tags_filter+']');
							if (jQuery('form.search-form input#media-tags-search').length)
							{
								jQuery('form.search-form input#media-tags-search').val(media_tags_filter);
							}
							else
							{
								jQuery('form.search-form').append('<input type="hidden" id="media-tags-search" name="media-tags" value="'+media_tags_filter+'"/>');
								
							}
						}
						else
						{
							jQuery('form.search-form input#media-tags-search').remove();	
						}
					});
						
					<?php
						if ((isset($_GET['media-tags'])) && (strlen($_GET['media-tags'])))
						{
							?>
							jQuery('form.search-form').append('<input type="hidden" id="media-tags-search" name="media-tags" value="<?php echo $_GET['media-tags']; ?>"/>');
							<?php
						}
					?>	
				});
				</script>
				<?php
			}
/*			else if ( version_compare( $wp_version, '3.0.999', '>' ) )
			{
				?>
				<script type='text/javascript'>				
					jQuery(document).ready(function() {
				
						jQuery('select#media-tags').change(function(){
							var media_tags_filter = jQuery(this).val();
							if (media_tags_filter !== "")
							{
								alert('media_tags_filter=['+media_tags_filter+']');
								if (jQuery('form#posts-filter input#media-tags-search').length)
								{
									jQuery('form#posts-filter input#media-tags-search').val(media_tags_filter);
								}
								else
								{
									jQuery('form#posts-filter').append('<input type="hidden" id="media-tags-search" name="media-tags" value="'+media_tags_filter+'"/>');
							
								}
							}
							else
							{
								jQuery('form# input#media-tags-search').remove();	
							}
						});
					
						<?php
							if ((isset($_REQUEST['media-tags'])) && (strlen($_REQUEST['media-tags'])))
							{
								?>
								jQuery('form#posts-filter').append('<input type="hidden" id="media-tags-search" name="media-tags" value="<?php echo $_GET['media-tags']; ?>"/>');
								<?php
							}
						?>	
					});
				</script>
				<?php
			}		    			
*/			
		}
	}
}	

function show_mediataga_admin_buttons_text()
{
	// My way of setting some text that can be pulled in via JS. This is much better than hard-coded inside the JS code. Plus I can provided language support for these different buttons labels. 
	?>
	<div id="media-tags-bulk-content-buttons" style="display: none">
		<div class="close"><?php echo _x('Close', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></div>
		<div class="submit"><?php echo _x('Submit', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></div>
		<div class="cancel"><?php echo _x('Cancel', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></div>
		<div class="media-tags"><?php echo _x('Media-Tags', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></div>
		<div class="show"><?php echo _x('Show', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></div>
		<div class="hide"><?php echo _x('Hide', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></div>		
		<div class="all"><?php echo _x('All Media-Tags', 'bulk admin label', MEDIA_TAGS_I18N_DOMAIN); ?></div>		
	</div>
	<?php
}

function mediatags_admin_panels()
{
	// Adds the 'Media-Tags' submenu option under the Media nav

	add_media_page( _x("Media-Tags", 'menu label', MEDIA_TAGS_I18N_DOMAIN),
					_x("Media-Tags", 'page label', MEDIA_TAGS_I18N_DOMAIN),
					MEDIATAGS_MANAGE_TERMS_CAP,
					"edit-tags.php?taxonomy=media-tags" );
	
	// Adds the 'Media-Tags' top-level menu panel
	
	add_menu_page( 	_x("Media-Tags Settings", 'page label', MEDIA_TAGS_I18N_DOMAIN), 
					_x("Media-Tags", 'menu label', MEDIA_TAGS_I18N_DOMAIN),
					MEDIATAGS_SETTINGS_CAP,
					'mediatags_settings_panel', 
					'mediatags_settings_panel');

	add_submenu_page( 'mediatags_settings_panel', 
					_x('Media-Tags Settings', 'page label', MEDIA_TAGS_I18N_DOMAIN), 
					_x('Settings', 'menu label', 'menu label', MEDIA_TAGS_I18N_DOMAIN), 
					MEDIATAGS_SETTINGS_CAP,
					'mediatags_settings_panel', 
					'mediatags_settings_panel');

	add_submenu_page( 'mediatags_settings_panel', 
					_x('Roles Management','page label', MEDIA_TAGS_I18N_DOMAIN), 
					_x('Roles Management', 'menu label', MEDIA_TAGS_I18N_DOMAIN), 
					MEDIATAGS_MANAGE_ROLE_CAP,
					'mediatags_roles_panel', 
					'mediatags_roles_panel');

	add_submenu_page( 'mediatags_settings_panel', 
					_x('Third Party Settings','page label', MEDIA_TAGS_I18N_DOMAIN), 
					_x('Third Party Settings', 'menu label', MEDIA_TAGS_I18N_DOMAIN), 
					MEDIATAGS_SETTINGS_CAP,
					'mediatags_thirdparty_panel', 
					'mediatags_thirdparty_panel');

	add_submenu_page( 'mediatags_settings_panel', 
					_x('Help', 'page label', MEDIA_TAGS_I18N_DOMAIN), 
					_x('Help', 'menu label', MEDIA_TAGS_I18N_DOMAIN), 
					MEDIATAGS_SETTINGS_CAP,
					'mediatags_help_panel', 
					'mediatags_help_panel');
}

function mediatags_add_default_capabilities() 
{
	$role = get_role('contributor');
	$role->add_cap(MEDIATAGS_ASSIGN_TERMS_CAP);

	$role = get_role('author');
	$role->add_cap(MEDIATAGS_ASSIGN_TERMS_CAP);

	$role = get_role('editor');
	$role->add_cap(MEDIATAGS_MANAGE_TERMS_CAP);
	$role->add_cap(MEDIATAGS_ASSIGN_TERMS_CAP);
	$role->add_cap(MEDIATAGS_EDIT_TERMS_CAP);
	$role->add_cap(MEDIATAGS_DELETE_TERMS_CAP);
	
	$role = get_role('administrator');
	$role->add_cap(MEDIATAGS_SETTINGS_CAP);
	$role->add_cap(MEDIATAGS_MANAGE_TERMS_CAP);
	$role->add_cap(MEDIATAGS_ASSIGN_TERMS_CAP);
	$role->add_cap(MEDIATAGS_EDIT_TERMS_CAP);
	$role->add_cap(MEDIATAGS_DELETE_TERMS_CAP);	
	$role->add_cap(MEDIATAGS_MANAGE_ROLE_CAP);	
}

function mediatags_filter_posts()
{
	// First need to check the screen. We only want to show the Media-Tags dropdown on the Media > Library page
	if (function_exists('get_current_screen'))
		$current_screen = get_current_screen();
	else
	{
		global $current_screen;			
	}

	if ((isset($current_screen->id)) && ($current_screen->id == "upload")) 
	{
		mediatags_show_library_select(MEDIA_TAGS_TAXONOMY, '', MEDIA_TAGS_TAXONOMY, __('All Media-Tags'));
	}
}

// Called from mediatags_filter_posts to display and handle the actual Media-tags dropdown.
function mediatags_show_library_select($term_slug='', $select_label='', $select_key='', $select_default_option='')
{
	if (!$term_slug) return;

	if (strlen($select_label) == 0)
		$select_label = $term_slug;

	if (strlen($select_key) == 0)
		$select_key = $term_slug;

	if (isset($_REQUEST[$term_slug]))
		$filter_term = $_REQUEST[$term_slug];
	else
		$filter_term = "";
		
	$posttype_terms 			= (array) get_terms( $term_slug, array('get' => 'all') );				
	if ($posttype_terms)
	{
		?>
		<select id="<?php echo $select_key; ?>" name="<?php echo $select_key; ?>">
			<option selected="selected" value=""><?php echo $select_default_option; ?></option><?php
			foreach($posttype_terms as $_idx => $_item)
			{
				$is_selected = "";
				//if (array_search($_item->term_id, $selected_posttype_terms) !== false)
				if ($filter_term == $_item->slug)				
					$is_selected = ' selected="selected" ';
					
				?><option value="<?php echo $_item->slug; ?>" <?php echo $is_selected; ?>><?php echo $_item->name; ?></option><?php
			}
			?>
		</select>
		<?php
	}
}

// Builds the Media > Library column cell which displays the Media-Tags used for the specific Media item
function mediatags_terms_column_row( $something, $column_name, $term_id ) 
{
	$row_content = "";
	
	if ($column_name == "mediatags_used")
	{
		$media_tag = get_term( $term_id, MEDIA_TAGS_TAXONOMY );
		if ($media_tag)
		{
			if ($media_tag->count > 0)
			{
				$row_content = '<a href="'. 
					get_mediatag_admin_library_link( $term_id). '">'.  $media_tag->count. '</a>';
			}
			else
			{
				$row_content = $media_tag->count;
			}			
		}		
	}	
	return $row_content;
}

// Build the output displayed when a user edits a Media item
function mediatags_show_fields_to_edit($form_fields, $post) 
{		
	if (current_user_can( MEDIATAGS_ASSIGN_TERMS_CAP ))
	{
		if (mediataga_check_url('/wp-admin/async-upload.php'))		
			$post_media_tags_fields = mediatags_get_fields($post->ID, true);
		else
			$post_media_tags_fields = mediatags_get_fields($post->ID, false);

		if (strlen($post_media_tags_fields))
			$post_media_tags_fields = "<br />". __('Enter media tags in the space above. Enter multiple tags 
				separated with comma. Or select from the tag(s) below', MEDIA_TAGS_I18N_DOMAIN) . $post_media_tags_fields;
		else
			$post_media_tags_fields = "<br />". __('Enter media tags in the space above. 
			Enter multiple tags separated with comma.', MEDIA_TAGS_I18N_DOMAIN);
	
    	$form_fields['media-meta'] = array(
       		'label' => __('Media-Tags:', MEDIA_TAGS_I18N_DOMAIN),
   			'input' => 'html',
   			'html' => "<input type='text' name='attachments[$post->ID][media_tags_input]' 
				id='attachments[$post->ID][media_tags_input]'
       			size='50' value='' />
				$post_media_tags_fields "
		);
	}
	return $form_fields;
}


function mediatags_get_fields($post_id, $force_load_tags = false)
{
	if (!is_object(get_post($post_id))) return '';
	
	$master_media_tag_fields = "";
	
	$post_media_tags = mediatags_get_post_mediatags($post_id);
	
	$used_tags_array = array();
	foreach ($post_media_tags as $tslug => $ttag) {
		$used_tags_array[] = '<li><input type="checkbox" id="label-'.$post_id.'-'.$tslug.'" '
			.'name="attachments['.$post_id.'][media_tags_checkbox]['.$tslug.']" checked="checked" />'
			.'<label for="label-'.$post_id.'-'.$tslug.'">'.__($ttag->name).'</label></li>';
	}
	if ($force_load_tags == true)
		$click_event = 'onclick="return false"';
	else
		$click_event = '';
		
	if (count($used_tags_array))
		$master_media_tag_fields .= '<a id="media-tags-used-'. $post_id .'" '. $click_event .' class="media-tags-show-hide-used" 
			post-id="'.$post_id.'" href="#">Media Tags for this attachment</a>'
			.'<div id="media-tags-list-used-'. $post_id .'" class="media-tags-list-used"><ul 
				class="media-tags-list">'.implode('', $used_tags_array).'</ul></div>';	
				
	$master_media_tag_fields .= '<a id="media-tags-common-'. $post_id .'" '. $click_event .' class="media-tags-show-hide-common" 
		post-id="'.$post_id.'" href="#">Show Common Media Tags</a>'
		.'<div id="media-tags-list-common-'. $post_id .'" class="media-tags-list-common"><ul 
		class="media-tags-list">';
		
	if ($force_load_tags == true)
	{
		$master_media_tag_fields .= mediatags_load_post_mediatags_type($post_id, 'common');
	}		
	$master_media_tag_fields .= '</ul></div>';
	
	$master_media_tag_fields .= '<a id="media-tags-uncommon-'.$post_id.'" '. $click_event .' class="media-tags-show-hide-uncommon" 
		post-id="'.$post_id.'" href="#">Show Uncommon Media Tags</a>'
		.'<div id="media-tags-list-uncommon-'. $post_id .'" class="media-tags-list-uncommon"><ul 
		class="media-tags-list">';
	if ($force_load_tags == true)
	{
		$master_media_tag_fields .= mediatags_load_post_mediatags_type($post_id, 'uncommon');
	}		
	$master_media_tag_fields .= '</ul></div>';
		
	return $master_media_tag_fields;
}

function mediatags_get_mediatags_ajax()
{
	if (!isset($_POST['post_id']) || empty($_POST['post_id'])) return '';
	$post_id = $_POST['post_id'];
	if (!is_object(get_post($post_id))) return '';

	if (!isset($_POST['mediatags_type']) || empty($_POST['mediatags_type'])) return '';
	
	$mediatags_type = $_POST['mediatags_type'];
	die(mediatags_load_post_mediatags_type($post_id, $mediatags_type));
}

function mediatags_load_post_mediatags_type($post_id, $mediatags_type)
{
	$post_media_tags = mediatags_get_post_mediatags($post_id);
	$master_list = mediatags_load_master();

	$meditags_items = array();
	if ( (is_array($master_list)) && (count($master_list)) )
	{
		foreach ($master_list as $master_slug => $master_tag) 
		{
			// If the Media-Tag term is already assocated with the post item. Skip it. 
			if (in_array($master_slug, array_keys($post_media_tags))) continue;
			
			if ($mediatags_type == "common")
			{
				if ($master_tag->count > 0)
				{
					$meditags_items[] = '<li><input type="checkbox" id="label-'. $post_id .'-'. $master_slug .'" '
					.'name="attachments['. $post_id .'][media_tags_checkbox]['. $master_slug .']" />'
					.'<label for="label-'. $post_id .'-'. $master_slug .'">'. __($master_tag->name) .'</label>';
				}
			}
			else if ($mediatags_type == "uncommon")
			{
				if ($master_tag->count == 0)
				{
					$meditags_items[] = '<li><input type="checkbox" id="label-'. $post_id .'-'. $master_slug .'" '
					.'name="attachments['. $post_id .'][media_tags_checkbox]['. $master_slug .']" />'
					.'<label for="label-'. $post_id .'-'. $master_slug .'">'. __($master_tag->name) .'</label>';
				}
			}
		}
	}
	if (count($meditags_items))
		return implode('', $meditags_items);
	else
	{
		if ($mediatags_type == "common")
			return 'No Common Media-Tags found.';
		else if ($mediatags_type == "uncommon")
			return 'No Uncommon Media-Tags found.';
	}
}

function meditags_process_attachment_fields_to_save($post, $attachment) 
{	
/*
	$media_tags_action = "assign";
	
	$select_media_tags = array();

	if (isset($attachment['media_tags_checkbox']))
	{
		foreach($attachment['media_tags_checkbox']  as $tag_idx => $tag_val)
		{
			$select_media_tags[] = $tag_idx;
		}
	}

	if (isset($attachment['media_tags_input']))
		$media_tags_input = $attachment['media_tags_input'];
	else
		$media_tags_input = "";

	$select_media_items = array();
	$select_media_items[] = $post['ID'];

//	mediatag_process_admin_forms($media_tags_action, $select_media_items, $select_media_tags, $media_tags_input);
*/		
	
	$media_tags_array = array();

	if (isset($attachment['media_tags_checkbox']))
	{
		foreach($attachment['media_tags_checkbox']  as $tag_idx => $tag_val)
		{
			$media_tags_array[] = $tag_idx;
		}
	}

	if (strlen($attachment['media_tags_input']))
	{
		$tags_tmp_array = split(',', $attachment['media_tags_input']);
		if ($tags_tmp_array)
		{
			foreach($tags_tmp_array as $idx => $tag_val)
			{
				$tag_slug = sanitize_title_with_dashes($tag_val);
				
				if ( ! ($id = term_exists( $tag_slug, MEDIA_TAGS_TAXONOMY ) ) )
					wp_insert_term($tag_val, MEDIA_TAGS_TAXONOMY, array('slug' => $tag_slug));
				
				$media_tags_array[] = $tag_slug;
			}
		}
	}
	$media_tags_slugs = array();
	foreach($media_tags_array as $media_tags_item)
		$media_tags_slugs[$media_tags_item] = $media_tags_item;

	if ($media_tags_array)
	{
		$media_tags_slugs = array();
		foreach($media_tags_array as $media_tags_item)
			$media_tags_slugs[$media_tags_item] = sprintf("%s", $media_tags_item) ;
		
		wp_set_object_terms($post['ID'], $media_tags_slugs, MEDIA_TAGS_TAXONOMY);			
	}
	else
	{
		wp_set_object_terms($post['ID'], "", MEDIA_TAGS_TAXONOMY);				
	}

    return $post;
}
/*
function mediatag_process_admin_forms($media_tags_action, $select_media_items, $select_media_tags, $media_tags_input='')
{
	// First process any new Tags entered via the input field...
	if ((strlen($media_tags_input)) && ($media_tags_action == "assign"))
	{
		$tags_tmp_array = split(',', $media_tags_input);
		if ($tags_tmp_array)
		{
			foreach($tags_tmp_array as $idx => $tag_val)
			{
				$tag_slug = sanitize_title_with_dashes($tag_val);

				if ( ! ($id = term_exists( $tag_slug, MEDIA_TAGS_TAXONOMY ) ) )
				{
					$inserted_term_id = wp_insert_term($tag_val, MEDIA_TAGS_TAXONOMY, array('slug' => $tag_slug));
					if (isset($inserted_term_id['term_id']))
					{
						$_term = get_term($inserted_term_id['term_id'], MEDIA_TAGS_TAXONOMY);
						if ($_term)
							$select_media_tags[] = $_term->slug;
					}
				}
				else
				{
					$_term = get_term($id['term_id'], MEDIA_TAGS_TAXONOMY);
					if ($_term)
						$select_media_tags[] = $_term->slug;
				}
			}
		}
	}
		
	if ( (strlen($media_tags_action)) && (count($select_media_items)) && (count($select_media_tags)) )
	{
//		echo "media_tags_action=[".$media_tags_action."]<br />\n";
//		echo "select_media_tags<pre>"; print_r($select_media_tags); echo "</pre>\n";
//		echo "select_media_items<pre>"; print_r($select_media_items); echo "</pre>\n";
		
		$selected_media_tag_terms = array();
		//$selected_media_tag_terms = get_terms(MEDIA_TAGS_TAXONOMY, array('include' => $select_media_tags));
		foreach($select_media_tags as $media_tag_id)
		{
			echo "media_tag_id=[". $media_tag_id ."]<br />";
			$selected_media_tag_terms[] = get_term($media_tag_id, MEDIA_TAGS_TAXONOMY);
		}
		echo "selected_media_tag_terms<pre>"; print_r($selected_media_tag_terms); echo "</pre>\n";
		
		if ($media_tags_action == "assign")
		{
			foreach($select_media_items as $select_media_item_id)
			{
				$media_tag_slugs = array();
				
				//echo "select_media_item_id=[". $select_media_item_id ."]<br />";
				$media_item_terms_current = wp_get_object_terms($select_media_item_id, MEDIA_TAGS_TAXONOMY);
				//echo "media_item_terms_current<pre>"; print_r($media_item_terms_current); echo "</pre>";
				if (!$media_item_terms_current)
				{
					if ($selected_media_tag_terms)
					{
						foreach($selected_media_tag_terms as $selected_media_tag_term)
							$media_tag_slugs[$selected_media_tag_term->slug] = $selected_media_tag_term->slug;
					}
				}
				else
				{
					// Here we need to combine the media item's already defined media-tag and the new media-tags
					//echo "media_item_terms_current<pre>"; print_r($media_item_terms_current); echo "</pre>";
					foreach($media_item_terms_current as $idx => $current_term)
						$media_tag_slugs[$current_term->slug] = $current_term->slug;

					if ($selected_media_tag_terms)
					{
						echo "selected_media_tag_terms<pre>"; print_r($selected_media_tag_terms); echo "</pre>";
						foreach($selected_media_tag_terms as $selected_media_tag_term)
							$media_tag_slugs[$selected_media_tag_term->slug] = $selected_media_tag_term->slug;
					}
				}
				if (count($media_tag_slugs))
				{
					// If the Media Item does not have any assigned Media-Tag we simple assign the selected Media-Tags
					wp_set_object_terms($select_media_item_id, $media_tag_slugs, MEDIA_TAGS_TAXONOMY);								
				}
			}
		}
		else if ($media_tags_action == "remove")
		{
			foreach($select_media_items as $select_media_item_id)
			{
				$media_tag_slugs = array();
				
				$media_item_terms_current = wp_get_object_terms($select_media_item_id, MEDIA_TAGS_TAXONOMY);
				if ($media_item_terms_current)
				{
					foreach($selected_media_tag_terms as $selected_media_tag_term)
					{
						foreach($media_item_terms_current as $current_idx => $current_term)
						{
							if ($current_term->term_id == $selected_media_tag_term->term_id)
								unset($media_item_terms_current[$current_idx]);
						}						
					}
					foreach($media_item_terms_current as $current_idx => $current_term)
						$media_tag_slugs[$current_term->slug] = $current_term->slug;
					if (count($media_tag_slugs))
						wp_set_object_terms($select_media_item_id, $media_tag_slugs, MEDIA_TAGS_TAXONOMY);								
					else
						wp_set_object_terms($select_media_item_id, $media_tag_slugs, MEDIA_TAGS_TAXONOMY);								
				}
			}			
		}
	}	
}
*/

function mediatags_load_master()
{
	$media_tags_tmp = (array) get_terms(MEDIA_TAGS_TAXONOMY, 'hide_empty=0');
	if ($media_tags_tmp)
	{
		$master_media_tags_array = array(); 
		foreach($media_tags_tmp as $m_media_tag)
		{
			$master_media_tags_array[$m_media_tag->slug] = $m_media_tag;
		}
		return $master_media_tags_array;
	}
}

function mediatags_delete_attachment_proc($postid = '')
{
	if (!$postid) return;
	
	wp_delete_object_term_relationships($postid, array(MEDIA_TAGS_TAXONOMY));	
}


function media_upload_gallery_tab($tab_content='')
{
	mediatags_bulk_admin_panel();
	show_mediataga_admin_buttons_text();		
}

function mediatag_upload_tab($tabs='')
{
	$tabs['mediatags'] = _x('Media Tags', 'tab label', MEDIA_TAGS_I18N_DOMAIN);
	return $tabs;
}

function media_upload_mediatags()
{
	if ( isset($_POST['send']) ) {
		// Return it to TinyMCE
		return media_send_to_editor($html);
	}
	$errors = null;
	return wp_iframe( 'media_upload_mediatags_form', $errors );
}

function media_upload_mediatags_form($errors)
{
	global $wpdb, $wp_query, $wp_locale, $type, $tab, $post_mime_types, $ngg;
	
	media_upload_header();

	$post_id 	= intval($_REQUEST['post_id']);
	$galleryID 	= 0;
	$total 		= 1;
	$picarray 	= false;
	
	if ((isset($_REQUEST['type'])) && (strlen($_REQUEST['type'])))
		$type = "type=".$_REQUEST['type']."&";
	
		//$form_action_url = get_option('siteurl') . "/wp-admin/media-upload.php?".$type."tab=library&post_id=".$post_id;
		$form_action_url = "media-upload.php?".$type."tab=library&post_id=".$post_id;
	
	?>
	<div style="clear:both"></div>
	<?php $mediatag_items = get_mediatags(); ?>	
	
	<form action="">
	<div id="media-items">
	<?php
		if ($mediatag_items)
		{
			foreach($mediatag_items as $mediatag_item)
			{
				?>
				<div id="mediatag-item-<?php echo $mediatag_item->term_id; ?>" class="media-item">
					<div class="filename" style="display: block; float: left; width: 70%"><?php 
						echo $mediatag_item->name; ?></div>
						
					<div class="mediatag-item-count" 
						style="display: block; float: right; width: 10%; line-height:36px;overflow:hidden;padding:0 10px;">
					<?php 
						$mediatags_library_link = MEDIA_TAGS_TAXONOMY ."=". $mediatag_item->slug;
						$mediatag_count = ( $mediatag_item->count > 0 ) ? '<a href="'.
							$form_action_url .'&amp;'. $mediatags_library_link .'">'. $mediatag_item->count .'</a>' : '0';
						echo $mediatag_count;
					?>
					</div>
				</div>
				<?php
			}
		}
	?>
	</div>
	</form>
	<?php
}

function mediatags_reconcile_counts()
{
	// This part of the function is to reconcile the counts on the mediatag items. Seems there was
	// an issue in a previous version where the count could be wrong. 
	$mediatag_items = get_mediatags();		
	echo "mediatag_items<pre>"; print_r($mediatag_items); echo "</pre>";
	if ($mediatag_items) 
	{	
		foreach($mediatag_items as $mediatag_item) 
		{
			$media_attachments =  get_objects_in_term($mediatag_item->term_id, MEDIA_TAGS_TAXONOMY);
			if ($media_attachments) 
			{			
				foreach($media_attachments as $media_idx => $media_attachment_id) 
				{
					if (!get_post($media_attachment_id))
					{
						mediatags_delete_attachment_proc($media_attachment_id);	
					}
				}					
			}
		}
	}
}


/* Adds column header to the Media Library panel where we show the linked Media-Tags per row */
function mediatags_library_column_header( $cols ) {
	$cols[MEDIA_TAGS_TAXONOMY] = _x('Media Tags', 'column name', MEDIA_TAGS_I18N_DOMAIN);
	return $cols;
}

function mediatags_terms_column_header($cols)
{
	if (isset($cols['posts']))
		unset($cols['posts']);

	$cols['mediatags_used'] = _x('Media Used', 'column name', MEDIA_TAGS_I18N_DOMAIN);
	
	return $cols;
}

function mediatags_admin_terms_args_filter($args, $taxonomies)
{
	if (((isset($_REQUEST['action'])) && ($_REQUEST['action'] == "fetch-list"))
	 && ((isset($_REQUEST['taxonomy'])) && ($_REQUEST['taxonomy'] == MEDIA_TAGS_TAXONOMY)))	
	{
		if ((isset($args['orderby'])) && ($args['orderby'] == "mediatags_used"))
			$args['orderby'] = "count";
	}
	
	return $args;
}

/* Adds the row content, link Media-Tags, for each Library item row */

function mediatags_library_column_row( $column_name, $id ) {

	if ( $column_name == MEDIA_TAGS_TAXONOMY ) 
	{
		//$media_attachments =  get_objects_in_term($id, MEDIA_TAGS_TAXONOMY);
		$media_attachments = get_the_terms( $id, MEDIA_TAGS_TAXONOMY );
		if ($media_attachments)
		{
			$media_tag_list_items = "";
			foreach($media_attachments as $media_idx => $media_attachment)
			{
				if (strlen($media_tag_list_items)) $media_tag_list_items .= ", ";
				
				$media_tag_list_items .= '<a href="'. 
					get_mediatag_admin_library_link( $media_attachment->term_id). '">'.  $media_attachment->name. '</a>';				
			}
			echo $media_tag_list_items;
		}
	}
}

function get_mediatag_admin_edit_link( $mediatag_id ) {
	$base_url = get_option('siteurl')."/wp-admin/upload.php?page=". MEDIA_TAGS_ADMIN_MENU_KEY;

	$media_tag = &get_term( $mediatag_id, MEDIA_TAGS_TAXONOMY );
	if ( is_wp_error( $media_tag ) )
		return $media_tag;

	$edit_href = $base_url ."&action=editmediatag&amp;mediatag_ID=".$mediatag_id;

	return $edit_href;
}

function get_mediatag_admin_search_link( $mediatag_id ) {

	$base_url = get_option('siteurl')."/wp-admin/upload.php?page=". MEDIA_TAGS_ADMIN_MENU_KEY;

	$media_tag = &get_term( $mediatag_id, MEDIA_TAGS_TAXONOMY );
	if ( is_wp_error( $media_tag ) )
		return $media_tag;
		
	$edit_href = $base_url ."&action=searchmediatag&amp;s=".$media_tag->slug;

	return $edit_href;
}

function get_mediatag_admin_library_link( $mediatag_id ) {

//	$base_url = get_option('siteurl')."/wp-admin/upload.php?";
	$base_url = "upload.php?";

	$media_tag = &get_term( $mediatag_id, MEDIA_TAGS_TAXONOMY );
	if ( is_wp_error( $media_tag ) )
		return;
		
//	$edit_href = $base_url ."mediatag_id=".$media_tag->term_id;
	$edit_href = $base_url . MEDIA_TAGS_TAXONOMY ."=".$media_tag->slug;

	return $edit_href;
}

function mediatags_get_taxonomy_labels()
{
	$labels_default = array(
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
	
	$mediatag_labels = get_option('mediatags_labels');
	if ($mediatag_labels)	
		return array_merge($labels_default, $mediatag_labels);
	else
		return $labels_default;
}
?>