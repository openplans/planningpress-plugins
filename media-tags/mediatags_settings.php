<?php
function mediatag_settings_boxheader($id, $title, $right = false) 
{
	?>
	<div id="<?php echo $id; ?>" class="postbox media-tags-settings-postbox">
		<h3 class="hndle"><span><?php echo $title ?></span></h3>
		<div class="inside">
	<?php
}

function mediatag_settings_boxfooter( $right = false) {
	?>
		</div>
	</div>
	<?php
}

function mediatag_settings_sidebar() {
	mediatag_settings_boxheader('mediatag-about', __('About this Plugin', MEDIA_TAGS_I18N_DOMAIN)); ?>

	<p><a class="" target="_blank" href="http://www.codehooligans.com/projects/wordpress/media-tags/"><?php _e('Plugin Homepage',MEDIA_TAGS_I18N_DOMAIN); ?></a></p>
	<?php 
	mediatag_settings_boxfooter(true);	

	mediatag_settings_boxheader('mediatag-donate', __('Make a Donation', MEDIA_TAGS_I18N_DOMAIN)); ?>
	<p><?php _e('Show your support of this plugin by making a small donation to support future development. No donation amount too small.', MEDIA_TAGS_I18N_DOMAIN); ?></p>
	<p><a class="" target="_blank" href="http://www.codehooligans.com/donations/"><?php _e('Make a donation today', MEDIA_TAGS_I18N_DOMAIN); ?></a></p>
	<?php 
	mediatag_settings_boxfooter(true);	

	if ((isset($_REQUEST['page'])) && ($_REQUEST['page'] != "mediatags_help_panel"))
	{
		mediatag_settings_boxheader('mediatag-help', __('Need Help?', MEDIA_TAGS_I18N_DOMAIN)); 
		?>
		<p><a class="" href="admin.php?page=mediatags_help_panel"><?php _e('Media-Tag Help',MEDIA_TAGS_I18N_DOMAIN); ?></a></p>
		<?php 
		mediatag_settings_boxfooter(true);	
	}
}

function mediatags_settings_api_init() {
	
	if (isset($_POST['mediatag_base']))
	{
		update_option( 'mediatag_base', $_POST['mediatag_base'] );
	}
	
	if (function_exists('add_settings_field'))
	{
		// Add a new field to the Permalinks Options section to allow override of the default 'media-tags' slug.
		add_settings_field('mediatag_base', 'Media-Tags', 'mediatags_setting_permalink_proc', 'permalink', 'optional');
	}
}
  
function mediatags_setting_permalink_proc() {

	$mediatag_base = get_option('mediatag_base');
	if (!$mediatag_base)
		$mediatag_base = "media-tags";
		
	?><input name="mediatag_base" id="mediatag_base" type="text" 
	value="<?php echo $mediatag_base; ?>" class="regular-text code" /> 
	<?php echo sprintf(__("(<i>default is '%s'</i> )<br />"), MEDIA_TAGS_URL_DEFAULT );  ?> 
	<?php _e("<strong>Note</strong> Be careful not to use a prefix that may conflict with other WordPress standard prefixes like 'category', 'tag', a Page slug, etc.", MEDIA_TAGS_I18N_DOMAIN);
} 


function mediatags_settings_panel()
{
	$update_message = "";
	if ( (isset($_REQUEST['mediatags_settings_panel'])) 
	  && (wp_verify_nonce($_REQUEST['mediatags_settings_panel'], 'mediatags_settings_panel')) )
	{
		$update_message = "";
		//echo "_REQUEST<pre>"; print_r($_REQUEST); echo "</pre>";

		if (isset($_REQUEST['mediatag_labels']))
		{
			update_option('mediatags_labels', $_REQUEST['mediatag_labels']);
		}

		if (isset($_REQUEST['mediatag_admin_bulk_library']))
		{
			if (strtolower($_REQUEST['mediatag_admin_bulk_library']) == strtolower("yes"))
				$mediatag_admin_bulk_library = "yes";
			else
				$mediatag_admin_bulk_library = "no";

			update_option( 'mediatag_admin_bulk_library', $mediatag_admin_bulk_library );
			$update_message = "Media-Tags Settings have been updated.";
		}

		if (isset($_REQUEST['mediatag_admin_bulk_inline']))
		{
			if (strtolower($_REQUEST['mediatag_admin_bulk_inline']) == strtolower("yes"))
				$mediatag_admin_bulk_inline = "yes";
			else
				$mediatag_admin_bulk_inline = "no";

			update_option( 'mediatag_admin_bulk_inline', $mediatag_admin_bulk_inline );
			$update_message = "Media-Tags Settings have been updated.";
		}

		if (isset($_REQUEST['mediatag_template_archive']))
		{
			if (strtolower($_REQUEST['mediatag_template_archive']) == strtolower("yes"))
				$mediatag_template_archive = "yes";
			else
				$mediatag_template_archive = "no";

			update_option( 'mediatag_template_archive', $mediatag_template_archive );
			$update_message = "Media-Tags Settings have been updated.";
		}

		if (isset($_REQUEST['mediatag_rss_feed']))
		{
			if (strtolower($_REQUEST['mediatag_rss_feed']) == strtolower("yes"))
				$mediatag_rss_feed = "yes";
			else
				$mediatag_rss_feed = "no";

			update_option( 'mediatag_rss_feed', $mediatag_rss_feed );
			$update_message = _x("Media-Tags Settings have been updated.", 'update message', MEDIA_TAGS_I18N_DOMAIN);
		}
	}
	$title = _x('Media-Tags Settings', 'settings panel title', MEDIA_TAGS_I18N_DOMAIN);
	?>
	<form class="mediatags_settings_panel" method="get" action="#">
		<input type="hidden" name="page" value="mediatags_settings_panel" />	
		<?php wp_nonce_field('mediatags_settings_panel', 'mediatags_settings_panel'); ?>
	<div class="wrap nosubsub">
		<?php //screen_icon(); ?>
		<h2><?php echo $title; ?></h2>
		<?php 
			if ( strlen($update_message)) { 
				?><div id="message" class="updated fade"><p><?php echo $update_message; ?></p></div><?php 
			} 
		?>		
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div class="inner-sidebar">
				<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
					<?php mediatag_settings_sidebar(); ?>
				</div>
			</div>
			<div class="has-sidebar sm-padded" >
				<div id="post-body-content" class="">			
					<div class="meta-box-sortabless">			


						<?php 
							$mediatag_admin_bulk_library = get_option('mediatag_admin_bulk_library', 'yes'); 
							if (!$mediatag_admin_bulk_library)
								$mediatag_admin_bulk_library = "yes";

							$mediatag_admin_bulk_inline = get_option('mediatag_admin_bulk_inline', 'yes'); 
							if (!$mediatag_admin_bulk_inline)
								$mediatag_admin_bulk_inline = "yes";

							mediatag_settings_boxheader('mediatag-settings-bulk-admin', 
								__('Media-Tags Bulk Admin Interface', MEDIA_TAGS_I18N_DOMAIN));

						?>

						<p><?php _e("Turn On/Off the Media-Tags Bulk Admin Interfaces. There are two sections where some heavy JavaScript code is added to the page:", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<select id="mediatag_admin_bulk_library" name="mediatag_admin_bulk_library">
							<option selected="selected" value="no"><?php echo _x('Off', 'select option', 
								MEDIA_TAGS_I18N_DOMAIN); ?></option>
							<option <?php if ($mediatag_admin_bulk_library == "yes") { echo ' selected="selected" ';} ?> 
								value="yes"><?php echo _e('On', MEDIA_TAGS_I18N_DOMAIN); ?></option>
						</select>&nbsp;
						<label for="mediatag_admin_bulk_library"><?php 
							_e('Media Library Page',  MEDIA_TAGS_I18N_DOMAIN); ?></label><br />

						<select id="mediatag_admin_bulk_inline" name="mediatag_admin_bulk_inline">
							<option selected="selected" value="no"><?php echo _x('Off', 'select option', 
								MEDIA_TAGS_I18N_DOMAIN); ?></option>
							<option <?php 
								if ($mediatag_admin_bulk_inline == "yes") { echo ' selected="selected" ';} ?> 
									value="yes"><?php echo _x('On', 'select option', MEDIA_TAGS_I18N_DOMAIN); ?></option>
						</select>&nbsp;
						<label for="mediatag_admin_bulk_inline"><?php _e('Media Upload Popup (Gallery & Media Library Tabs)', 
							MEDIA_TAGS_I18N_DOMAIN); ?></label><br /><br />
						<?php mediatag_settings_boxfooter(false); ?>



						<?php 
							$mediatag_template_archive = get_option('mediatag_template_archive', 'yes'); 
							if (!$mediatag_template_archive)
								$mediatag_template_archive = "no";
						?>
						<?php mediatag_settings_boxheader('mediatag-settings-template-archive', 
								__('Media-Tags Archive Template display', MEDIA_TAGS_I18N_DOMAIN)); ?>
						<p><?php _e("When viewing an archive of Media-Tags like http://www.mysite.com/media-tags/some-tag-slug, the default output will be the list of attachments matching the media-tag. If the attachment is an image it will be displayed in the post item body. Using the select option below you can prevent this default action.", MEDIA_TAGS_I18N_DOMAIN); ?></p>
					
						<p><?php _e("To understand the theme template files used by the Media-Tags plugin visit the help section.", MEDIA_TAGS_I18N_DOMAIN); ?></p>					
						<select id="mediatag_template_archive" name="mediatag_template_archive">
							<option selected="selected" value="no"><?php echo _x('No', 'select option', 
								MEDIA_TAGS_I18N_DOMAIN); ?></option>
							<option <?php if ($mediatag_template_archive == "yes") { echo ' selected="selected" ';} ?> 
								value="yes"><?php echo _e('Yes', MEDIA_TAGS_I18N_DOMAIN); ?></option>
						</select>&nbsp;
						<label for="mediatag_template_archive"><?php 
							_e('Use the default Archive Template display?',  MEDIA_TAGS_I18N_DOMAIN); ?></label><br />
						
						<?php mediatag_settings_boxfooter(false); ?>





						<?php 
							mediatag_settings_boxheader('mediatag-options-rssfeed', 
								__('RSS Feed for Media-Tags', MEDIA_TAGS_I18N_DOMAIN));

						$mediatag_rss_feed = get_option('mediatag_rss_feed', 'yes'); 
						if (!$mediatag_rss_feed)
							$mediatag_rss_feed = "yes";

						?>

						<p><?php _e("The Media-Tags plugin now supports RSS feed when viewing an archive. For example when viewing the URL http://www.mysite.com/media-tags/some-tag/ you can now access the RSS listing by adding '/feed' to the end of the URL as in http://www.mysite.com/media-tags/some-tag/feed/", MEDIA_TAGS_I18N_DOMAIN); ?></p>
						<p><?php _e("You can customize the RSS output by adding your own Media-Tags RSS template to your theme. Check the topic under the help section.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<select id="mediatag_rss_feed" name="mediatag_rss_feed">
							<option selected="selected" value="yes"><?php 
								echo _x('On', 'select option', MEDIA_TAGS_I18N_DOMAIN); ?></option>
							<option <?php if ($mediatag_rss_feed == "no"){ echo ' selected="selected" ';} ?> value="no"><?php
								echo _x('Off', 'select option', MEDIA_TAGS_I18N_DOMAIN); ?></option>
						</select>
						<label for="mediatag_rss_feed"><?php _e('Turn the Media-Tag RSS Feed option On/Off', 
							MEDIA_TAGS_I18N_DOMAIN); ?></label>
						<?php mediatag_settings_boxfooter(false); ?>


						<?php 
							$mediatag_labels = mediatags_get_taxonomy_labels();
							$mediatag_labels_legend = array(
								'name' => ' Used to control the label used on the HTML Title for Archives.'
							);
							
						?>
						<?php mediatag_settings_boxheader('mediatag-settings-labels', 
								__('Media-Tags Labels', MEDIA_TAGS_I18N_DOMAIN)); ?>
						<p><?php _e('This settings section lets you override some of the labels used. Most of these labels are used on the <a href="edit-tags.php?taxonomy=media-tags">Media-Tags</a> management page.',
						 	MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<?php
							foreach($mediatag_labels as $label_key => $label_val)
							{
								$label_display = ucwords(strtolower(str_replace('_', ' ', $label_key)));
								?>
								<label for="mediatag_label_<?php echo $label_key; ?>"><?php echo $label_display; ?><?php
									if (isset($mediatag_labels_legend[$label_key]))
									{ echo ': '. $mediatag_labels_legend[$label_key]; }
								?></label><br />
								<input id="mediatag_label_<?php echo $label_key; ?>" 
									name="mediatag_labels[<?php echo $label_key; ?>]" value="<?php echo $label_val; ?>" /><br /><br />
								<?php
							}
						?>
						<p><?php _e('Please refer to the WordPress Codex page for <a target="_blank" href="http://codex.wordpress.org/Function_Reference/register_taxonomy">register_taxonomy</a> for full descriptions f the labels and how they are used.'); ?></p>
						<?php mediatag_settings_boxfooter(false); ?>



					</div>
				</div>
			</div>
		</div>
		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Update Options', MEDIA_TAGS_I18N_DOMAIN ) ?>" />
		</p>
	</form>
	</div>
	<?php
}

function mediatags_roles_panel()
{
	global $mediatags_caps;
	global $wpdb, $wp_version;
	
	$current_blog_id = $wpdb->blogid;
	
	$update_message = "";
	
	$current_user_id = get_current_user_id();
	$current_user = new WP_User( $current_user_id );
	
	if ( (isset($_REQUEST['mediatags_roles_panel']))
	  && (wp_verify_nonce($_REQUEST['mediatags_roles_panel'], 'mediatags_roles_panel')) ) 
	{
		if (isset($_REQUEST['media-tags-user-roles']))
		{
			//echo "_REQUEST<pre>"; print_r($_REQUEST); echo "</pre>";

			$mediatags_user_roles = $_REQUEST['media-tags-user-roles'];

//			if ($current_user->has_cap('edit_users'))
			{
//				if ( version_compare( $wp_version, '3.0.999', '<' ) )			    
//				{
					$users = array();

					$user_ids = get_editable_user_ids($current_user_id);
					if ($user_ids)
					{
						foreach($user_ids as $user_id)
						{
							$users[$user_id] = new WP_User( $user_id );
						}
					}				
//				}
//				else
//					$users = get_users();					
				
				if ($users)
				{
					foreach($users as $user_id => $user)
					{
						if ($user)
						{	
							//echo "user<pre>"; print_r($user); echo "</pre>";
							foreach($mediatags_caps as $mediatags_cap => $mediatags_label)
							{
								if ((isset($mediatags_user_roles[$user_id][$mediatags_cap]))
								 && ($mediatags_user_roles[$user_id][$mediatags_cap] == "on"))
									$user->add_cap($mediatags_cap);
								else
									$user->add_cap($mediatags_cap, false);
							}								
						}
					}
				}
			}
			$update_message = _x("Media-Tags Roles have been updated.", 'update message', MEDIA_TAGS_I18N_DOMAIN);
		}
	}
	$title = _x('Media-Tags Roles Management', 'settings panel title', MEDIA_TAGS_I18N_DOMAIN);
	?>
	<div class="wrap nosubsub">
		<?php //screen_icon(); ?>
		<h2><?php echo $title; ?></h2>
		<?php 
			if ( strlen($update_message)) { 
				?><div id="message" class="updated fade"><p><?php echo $update_message; ?></p></div><?php 
			} 
		?>		
		<?php		
			$roles = get_editable_roles();
			$user_roles_array = array();
			foreach($roles as $role_label => $role)
			{
				$user_roles_array[$role_label] 			= array();
				$user_roles_array[$role_label]['name'] 	= $role['name'];
				$user_roles_array[$role_label]['users'] = array();
			}

//			if ( version_compare( $wp_version, '3.0.999', '<' ) )			    
//			{
				$users = array();
				$user_ids = get_editable_user_ids($current_user_id);
				if ($user_ids)
				{
					foreach($user_ids as $user_id)
					{
						$users[$user_id] = new WP_User( $user_id );
					}
				}				
//			}
//			else
//				$users = get_users();					

			if ($users)
			{				
				foreach($users as $user_id => $user)
				{
					//echo "user<pre>"; print_r($user); echo "</pre>";
					
					if ( is_multisite() ) 
					{
						$cap_str = "wp_". $current_blog_id ."_capabilities";
						if (isset($user->$cap_str))
							$user_capabilities = $user->$cap_str;
					}
					else
					{
						$user_capabilities = $user->wp_capabilities;
					}

					if (isset($user_capabilities))
					{
						foreach($user_capabilities as $cap_idx => $cap_val)
						{
							if (isset($user_roles_array[$cap_idx]))
								$user_roles_array[$cap_idx]['users'][$user->data->display_name] = $user;
						}
					}
				}
				//echo "user_roles_array<pre>"; print_r($user_roles_array); echo "</pre>";
				
				?>
				<form class="mediatags-roles-form" method="get" action="#">
					<input type="hidden" name="page" value="mediatags_roles_panel" />
					<?php wp_nonce_field('mediatags_roles_panel', 'mediatags_roles_panel'); ?>
					<div id="poststuff" class="metabox-holder has-right-sidebar">
						<div class="inner-sidebar">
							<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
								<?php mediatag_settings_sidebar(); ?>

								<?php mediatag_settings_boxheader('mediatag-roles-about', __('About this page', MEDIA_TAGS_I18N_DOMAIN)); ?>
								<p><?php _e('This Settings panel allows control of which users can perform what functions regarding Media-Tags tags. These roles can also be administered via some other popular Role Management plugins.', MEDIA_TAGS_I18N_DOMAIN);?></p>
								<?php mediatag_settings_boxfooter(true); ?>								


								<?php mediatag_settings_boxheader('mediatag-roles-legend', __('Roles Legend', MEDIA_TAGS_I18N_DOMAIN)); ?>
								<ul>
									<li><strong><?php echo _x('Manage Settings', 'role legend label', MEDIA_TAGS_I18N_DOMAIN); ?></strong>
									&ndash;	<?php echo _x("Allows the user access to the Media-Tags navigation block. <em>This option
excludes access to the Media-Tags Roles Management panel.</em>", 'role legend text for manage settings', MEDIA_TAGS_I18N_DOMAIN); ?> </li>

									<li><strong><?php echo _x('Manage Role/Cap', 'role legend label', MEDIA_TAGS_I18N_DOMAIN); ?></strong>
									&ndash; <?php echo _x("Allows the user access to the Media-Tags Roles Management panel. (this panel) <em>This option excludes access to the other Media-Tags Settings panels.</em>", 'role legend text for manage roles', MEDIA_TAGS_I18N_DOMAIN); ?></li>

									<li><strong><?php echo _x('Manage Terms', 'role legend label', MEDIA_TAGS_I18N_DOMAIN); ?></strong>
									&ndash; <?php echo _x("Allows users access to manage the 'Media-Tags' menu within the Media navigation block. <em>If this option is set you must also set either 'Edit Terms' or 'Delete Terms' roles.</em>", 'role legend text for manage terms', MEDIA_TAGS_I18N_DOMAIN); ?></li>
									<li><strong><?php echo _x('Edit Terms', 'role legend label', MEDIA_TAGS_I18N_DOMAIN); ?></strong>
									&ndash; <?php echo _x("Allows the user 'Edit' ability within the Media-Tags terms management panel. <em>If you check this option you must also set the 'Manage Terms' Roles option.</em>", 'role legend text for edit terms', MEDIA_TAGS_I18N_DOMAIN); ?></li>
									
									<li><strong><?php echo _x('Delete Terms', 'role legend label', MEDIA_TAGS_I18N_DOMAIN); ?></strong>
									&ndash;	<?php echo _x("Allows the user 'Delete' ability within the Media-Tags terms management panel. <em>If you check this option you must also set the 'Manage Terms' Roles option.</em>", 'role legend text for delete terms', MEDIA_TAGS_I18N_DOMAIN); ?></li>
									
									<li><strong><?php echo _x('Assign Terms', 'role legend label', MEDIA_TAGS_I18N_DOMAIN); ?></strong>
									&ndash; <?php echo _x("Allows the user to assign Media-Tag terms to media items. When set the user will see the 'Media-Tags' fields added to the Media item edit form where you would also see fields for the Title and Caption.", 'role legend text for assign terms', MEDIA_TAGS_I18N_DOMAIN); ?></li>
								</ul>
								<?php mediatag_settings_boxfooter(true); ?>								
							</div>
						</div>

						<div class="has-sidebar sm-padded" >
							<div id="post-body-content" class="">			
								<div class="meta-box-sortabless">	
							
								<?php
								foreach($user_roles_array as $user_role_idx => $user_role_data)
								{
									//echo "user_role_idx=[".$user_role_idx."]<br />";
									$role = get_role($user_role_idx);
									//echo "role<pre>"; print_r($role); echo "</pre>";
									
									mediatag_settings_boxheader('mediatag-options-roles-<?php echo $user_role_idx; ?>', 
									__('Role', MEDIA_TAGS_I18N_DOMAIN). ': '. $user_role_data['name']); 

									if ((isset($user_role_data['users'])) && (count($user_role_data['users'])))
									{
										?>
										<div class="media-tags-role-legend"><?php _e('Indicats default role catabilities',
										 	MEDIA_TAGS_I18N_DOMAIN); ?> &ndash; <span class="color"></span></div>
										<table class="media-tags-role-management" width="100%" cellpadding="2" cellspacing="3">
										<tr>
											<th class="user-id"><?php echo _x('ID', 'user role table header', 
												MEDIA_TAGS_I18N_DOMAIN); ?></th>
											<th class="user"><?php echo _x('User (Email)', 'user role table header', 
												MEDIA_TAGS_I18N_DOMAIN); ?></th>
											<?php
												$role_default = "";
												if ((isset($role->capabilities[MEDIATAGS_SETTINGS_CAP])) 
												 && ($role->capabilities[MEDIATAGS_SETTINGS_CAP] == 1))
												{ $role_default = "role-default"; }
											?>
											<th class="setting <?php echo $role_default; ?>"><?php echo _x('Manage Settings', 
												'user role table header', MEDIA_TAGS_I18N_DOMAIN); ?></th>
												
											<?php 
												$role_default = "";
												if ((isset($role->capabilities[MEDIATAGS_MANAGE_ROLE_CAP])) 
												 && ($role->capabilities[MEDIATAGS_MANAGE_ROLE_CAP] == 1))
												{ $role_default = "role-default"; }
											?>
											<th class="setting <?php echo $role_default; ?>"><?php echo _x('Manage Role/Cap', 
												'user role table header', MEDIA_TAGS_I18N_DOMAIN); ?></th>
												
											<?php 
												$role_default = "";
												if ((isset($role->capabilities[MEDIATAGS_MANAGE_TERMS_CAP])) 
												 && ($role->capabilities[MEDIATAGS_MANAGE_TERMS_CAP] == 1))
												{ $role_default = "role-default"; }
											?>
											<th class="setting <?php echo $role_default; ?>"><?php echo _x('Manage Terms', 
												'user role table header', MEDIA_TAGS_I18N_DOMAIN); ?></th>

											<?php
												$role_default = ""; 
												if ((isset($role->capabilities[MEDIATAGS_EDIT_TERMS_CAP])) 
												 && ($role->capabilities[MEDIATAGS_EDIT_TERMS_CAP] == 1))
												{ $role_default = "role-default"; } 
											?>
											<th class="setting <?php echo $role_default; ?>"><?php echo _x('Edit Terms', 
												'user role table header', MEDIA_TAGS_I18N_DOMAIN); ?></th>

											<?php 
												$role_default = "";
												if ((isset($role->capabilities[MEDIATAGS_DELETE_TERMS_CAP])) 
												 && ($role->capabilities[MEDIATAGS_DELETE_TERMS_CAP] == 1))
												{ $role_default = "role-default"; } 
											?>
											<th class="setting <?php echo $role_default; ?>"><?php echo _x('Delete Terms', 
												'user role table header', MEDIA_TAGS_I18N_DOMAIN); ?></th>
												
											<?php 
												$role_default = "";
												if ((isset($role->capabilities[MEDIATAGS_ASSIGN_TERMS_CAP])) 
												 && ($role->capabilities[MEDIATAGS_ASSIGN_TERMS_CAP] == 1))
												{ $role_default = "role-default"; } 
											?>
											<th class="setting <?php echo $role_default; ?>"><?php echo _x('Assign Terms', 
												'user role table header', MEDIA_TAGS_I18N_DOMAIN); ?></th>
										</tr>
										<?php
										foreach($user_role_data['users'] as $role_user)
										{
											//echo "role_user<pre>"; print_r($role_user); echo "</pre>";
											?><tr><?php
											?><td><?php echo $role_user->data->ID; ?></td><?php
											?><td><?php echo $role_user->data->display_name; ?><?php 
												if ((strtolower($role_user->data->display_name))
												 != (strtolower($role_user->data->user_email)))
												{
													echo " (". $role_user->data->user_email .")"; 	
												} ?></td><?php
											foreach($mediatags_caps as $media_tags_cap => $media_tags_desc)
											{												
												$media_tag_role = str_replace('_', '-', $media_tags_cap);
												$field_id		= "media-tags-user-roles-". $role_user->ID ."-". $media_tag_role;
												$field_name		= "media-tags-user-roles[". $role_user->ID ."][". $media_tags_cap."]";
												$field_class 	= "no";
												$field_label 	= _x("No", 'select option', MEDIA_TAGS_I18N_DOMAIN);
												$field_checked 	= "";
												if ((isset($role_user->allcaps[$media_tags_cap]))
												 && ($role_user->allcaps[$media_tags_cap] == 1))
												{
													$field_class 	= "yes";
													$field_label 	= _x("Yes", 'select option', MEDIA_TAGS_I18N_DOMAIN);
													$field_checked 	= ' checked="checked" ';
												}
												?><td class="<?php echo $field_class; ?>"><?php
													//echo "current_user_id=[". $current_user_id ."] ID=[".$role_user->data->ID."]<br />";
													//echo "media_tags_cap=[".$media_tags_cap."]<br />";
													if ($role_user->data->ID == $current_user_id)
													{
														if (($media_tags_cap != MEDIATAGS_SETTINGS_CAP)
													  	 && ($media_tags_cap != MEDIATAGS_MANAGE_ROLE_CAP) )
														{
															?><input type="checkbox" 
																id="<?php echo $field_id; ?>" 
																name="<?php echo $field_name; ?>" 
																<?php echo $field_checked; ?> /><?php															
														}
														else
														{
															?><input type="hidden" value="on"
																id="<?php echo $field_id; ?>" 
																name="<?php echo $field_name; ?>"  /><?php															
														}
													}
													else
													{
														?><input type="checkbox" 
															id="<?php echo $field_id; ?>" 
															name="<?php echo $field_name; ?>" 
															<?php echo $field_checked; ?> /><?php
													} ?><label for="<?php echo $field_id; ?>"><?php 
														echo $field_label; ?></label></td><?php											
											}
											?></tr><?php
										}
										?></table><?php
									}
									else
									{
										?><p><?php _e('No users at this level.', MEDIA_TAGS_I18N_DOMAIN); ?></p><?php				
									}									
									mediatag_settings_boxfooter(false);
								}
								?>
								</div>
							</div>
						</div>
					</div>
					<p class="submit">
						<input type="submit" name="Submit" value="<?php _e('Update Options', MEDIA_TAGS_I18N_DOMAIN ) ?>" />
					</p>			
				</form>
				<?php
			}
			else
			{
				?><p><?php _e('No users at this level.', MEDIA_TAGS_I18N_DOMAIN); ?></p><?php				
			}
	?></div><?php
}

function mediatags_thirdparty_panel()
{
	$update_message = "";
	if ( (isset($_REQUEST['mediatags_thirdparty_panel'])) 
	  && (wp_verify_nonce($_REQUEST['mediatags_thirdparty_panel'], 'mediatags_thirdparty_panel')) )
	{
		//echo "_REQUEST<pre>"; print_r($_REQUEST); echo "</pre>";
		if (isset($_REQUEST['mediatag_google_plugin']))
		{
			if (strtolower($_REQUEST['mediatag_google_plugin']) == strtolower("yes"))
				$mediatag_google_plugin = "yes";
			else
				$mediatag_google_plugin = "no";

			update_option( 'mediatag_google_plugin', $mediatag_google_plugin );
			$update_message = _x("Media-Tags Third Party Settings have been updated.", 'update message', MEDIA_TAGS_I18N_DOMAIN);
		}
	}
	$title = _x('Media-Tags Third Party Support', 'settings panel title', MEDIA_TAGS_I18N_DOMAIN);
	?>
	<div class="wrap nosubsub">
		<?php //screen_icon(); ?>
		<h2><?php echo $title; ?></h2>
		<p><strong><?php _e('This admin panel provides support functions for Third-Party plugins', MEDIA_TAGS_I18N_DOMAIN); ?></strong></p>
		
		<?php 
			if ( strlen($update_message)) { 
				?><div id="message" class="updated fade"><p><?php echo $update_message; ?></p></div><?php 
			} 
		?>		
		<form class="mediatags_thirdparty_panel" method="get" action="#">
			<input type="hidden" name="page" value="mediatags_thirdparty_panel" />
			<?php wp_nonce_field('mediatags_thirdparty_panel', 'mediatags_thirdparty_panel'); ?>
			
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar">
					<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
						<?php mediatag_settings_sidebar(); ?>
						<?php mediatag_settings_boxheader('mediatag-third-party-suggest', 
							__('Suggest other plugins', MEDIA_TAGS_I18N_DOMAIN)); ?>
						<p>Know of a plugin you think should be integrated with Media-Tags? <a target="_blank" 
							href="http://www.codehooligans.com/contact/">Suggest it</a></p>
						<?php mediatag_settings_boxfooter(true); ?>								
						
					</div>
				</div>
				<div class="has-sidebar sm-padded" >
					<div id="post-body-content" class="">			
						<div class="meta-box-sortabless">			

							<?php 
								mediatag_settings_boxheader('mediatag-options-3rd-google-sitemaps', 
								__('Google XML Sitemaps', MEDIA_TAGS_I18N_DOMAIN));

							$mediatag_google_plugin = get_option('mediatag_google_plugin', 'no'); 
							?>

							<p><?php _e('Include Media-Tag URLs in your Google Sitemap XML file? (Requires the install of the',
							 	MEDIA_TAGS_I18N_DOMAIN); ?> <a
								 href="http://wordpress.org/extend/plugins/google-sitemap-generator/"><?php _e('Google Sitemaps XML',
								 	MEDIA_TAGS_I18N_DOMAIN); ?></a> <?php _e('plugin', MEDIA_TAGS_I18N_DOMAIN); ?>)</p>
									
							<?php if (is_plugin_active('google-sitemap-generator/sitemap.php')) { ?>

								<select id="mediatag_google_plugin" name="mediatag_google_plugin">
									<option selected="selected" value="no"><?php echo _x('No', 'select option', 
										MEDIA_TAGS_I18N_DOMAIN); ?></option>
									<option <?php if ($mediatag_google_plugin == "yes")
									{ echo ' selected="selected" ';} ?> value="yes"><?php 
										echo _x('Yes', 'select option', MEDIA_TAGS_I18N_DOMAIN) ?></option>
								</select>
							<?php } ?>
							<?php mediatag_settings_boxfooter(false); ?>

						</div>
					</div>
				</div>
			</div>
			<?php if (is_plugin_active('google-sitemap-generator/sitemap.php')) { ?>
				<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Options', MEDIA_TAGS_I18N_DOMAIN ) ?>" /></p>
			<?php } ?>
		</form>
	</div>
	<?php	
}

function mediatags_help_panel()
{
	$title = _x('Media-Tags Help/Support', 'settings panel title', MEDIA_TAGS_I18N_DOMAIN);
	?>
	<div class="wrap nosubsub">
		<?php //screen_icon(); ?>
		<h2><?php echo $title; ?></h2>
		<p><strong><?php _e('This admin panel attempts to put together some of the user question submitted on how to use 
		the Media-Tags plugin', MEDIA_TAGS_I18N_DOMAIN); ?></strong></p>
		
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div class="inner-sidebar">
				<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
					<?php mediatag_settings_sidebar(); ?>					
				</div>
			</div>
			<div class="has-sidebar sm-padded" >
				<div id="post-body-content" class="">
					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-topics', 
							__("Topics", MEDIA_TAGS_I18N_DOMAIN));
						?>
						<ul>
							<li><a href="#mediatag-help-what-is"><?php 
								_e("Media-Tags: What does it do?", MEDIA_TAGS_I18N_DOMAIN); ?></a></li>
							<li><a href="#mediatag-help-now-what"><?php 
								_e("I've assigned some tags to my media uploads. Now what?", MEDIA_TAGS_I18N_DOMAIN); ?></a></li>
							<li><a href="#mediatag-help-admin-bulk"><?php 
								_e("New Bulk Admin interfaces (New in Media-Tags 3.0)", MEDIA_TAGS_I18N_DOMAIN); ?></a></li>
							<li><a href="#mediatag-help-how-to-display"><?php 
								_e("But how do I display the Media-Tag items on the front-end?", MEDIA_TAGS_I18N_DOMAIN); ?></a></li>
							<li><a href="#mediatag-help-display-shortcodes"><?php 
								_e("Media-Tags and shortcodes", MEDIA_TAGS_I18N_DOMAIN); ?></a></li>
							<li><a href="#mediatag-help-template_functions"><?php 
								_e("Media-Tags Template Functions", MEDIA_TAGS_I18N_DOMAIN); ?></a></li>
							<li><a href="#mediatag-help-template_files"><?php 
								_e("Media-Tags Archive Template Hierarchy", MEDIA_TAGS_I18N_DOMAIN); ?></a></li>
							<li><a href="#mediatag-help-template_files-rss"><?php 
								_e("Media-Tags Template Files RSS", MEDIA_TAGS_I18N_DOMAIN); ?></a></li>
							<li><a href="#"></a></li>
						</ul>
						<?php mediatag_settings_boxfooter(false); ?>
					</div>
					
					
					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-what-is', 
							__('Media-Tags: What does it do?', MEDIA_TAGS_I18N_DOMAIN));
						?>
						<p><?php _e("The Media-Tags plugin adds the ability to assign tags to attachments much like how you assign tags and categories to a Post. When you upload a media item (image, PDF, Excel, etc.) in WordPress you are presented with a form to update the Title, Caption, Description, etc for that item. The Media-Tags plugin adds new fields to this form where you can add new or assign existing tags to the media item. When you submit the form the Media-Tags information is stored into a custom Taxonomy as part of the database. This Taxonomy is accessible via any of the standard WordPress user functions.", MEDIA_TAGS_I18N_DOMAIN); ?></p>
						<?php mediatag_settings_boxfooter(false); ?>

					</div>
					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-now-what', 
							__("I've assigned some tags to my media uploads. Now what?", MEDIA_TAGS_I18N_DOMAIN));
						?>
						<p><?php _e("Within the WordPress admin interface (/wp-admin) you should see a new menu option under the 'Media' navigation block labelled 'Media-Tags'. This is where you can manage your tags using a familiar interface like the one used for Post Tags. This tag management page will show the Tag name, Description, Slug and how many times the tag is used. If you click on a value in this last column you will be taken to the Media > Library page where you media items are normally listed. But the listing will be filtered to show only those items per that selected Media-Tag. Within the Media > Library page itself there is an additional column added. This column lists the Media-Tags used for the Media Library item.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e("The Media-Tags plugin also supports the new menus system used in WordPress 3.0. If you go to the Appearances > Menus page you will see the Media-Tags listed in the meta boxes on the left side of the page. If you don't see the Media-Tags box try clicking the 'Screen Options' at the top-right of the page. Make sure the Media-Tags is checked.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e("On the Post/Page editor popup screens where you normally add images and other media items to your system the Media-Tags plugin is also visible. Again, when you add a media item you are presented with the standard WordPress information form to set the Title, Caption, Description, etc. Here you will also see the fields to add or assign Media-Tags to you upload. Also, within the tabbed popup you will see a tab for 'Media Tags'. This tab displays the Media-Tag terms used in your system along with the count use of that term. The count is a link. When click the count will take you to the Media Library tab filtering the displayed items to show those by the selected Media-Tag", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<?php mediatag_settings_boxfooter(false); ?>

					</div>


					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-admin-bulk', 
							__("New Bulk Admin interfaces (New in Media-Tags 3.0)", MEDIA_TAGS_I18N_DOMAIN));
						?>
						<p><?php _e("Within the WordPress admin interface (/wp-admin) a new Bulk Administration interface has been added to the Media > Library section as well as the Post/Page media popup.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e("Within the Media > Library section the Bulk Admin interface is accessed via the WordPress Bulk action dropdown. First you need to select which media items to work with. Then select 'Media-Tags' from the Bulk action dropdown and click the 'Apply' button. You will now see the new Media-Tags Bulk Administration popup. Via this popup you can assign or remove Media-Tag terms from the selected media items. You can also add new Media-Tag terms. Click the 'Submit' button to process. The page will refresh.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e("Within the Post/Page Media popup there is a similar Bulk Administration interface on the 'Galery' or  'Media Library' tabs. Look for the 'Media-Tags: show' link just above the media items listing. Instead of a new popup within a popup this will be an inline panel. On the right side of the media popup there is also a select all checkbox to select all media items. Also within each row of media items there is a checkbox to select just that item.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e("Both Bulk Administration section use some heave JavaScript to accomplish the task. If for some reason this JavaScript interferes with other plugins or borks your WordPress interface you can selectively disable these Bulk Administration options via the <a href=\"admin.php?page=mediatags_settings_panel\">Media-Tags Settings</a> panel. ", MEDIA_TAGS_I18N_DOMAIN); ?></p>


						<?php mediatag_settings_boxfooter(false); ?>

					</div>

					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-how-to-display', 
							__("But how do I display the Media-Tag items on the front-end?", MEDIA_TAGS_I18N_DOMAIN));
						?>
						<p><?php _e("By default the Media-Tags plugin creates some custom rewrite rules. These rewrite rules allow you to display archives or Media-Tag terms much like the archive for post tags you may already have on your site. The Media-tags plugin adds a new URL element to your site which is 'media-tags' by default. So if your site URL is http://www.mysite.com/ then the Media-Tags archive will be something like http://www.mysite.com/media-tags/&lt;some term slug&gt;/ The &lt;some term slug&gt; part of the URL will be any given valid Media-Tags slug which contains a number of media items greater than zero.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e("You can change the '/media-tags/' part of the URL. Go to the WordPress admin section. Then into Settings > Permalinks. Toward the bottom of the page in the Optional section you will see an input field to change the '/media-tags/' URL base to something else like gallery, pictures, etc. Just remember this part of the URL must be unique. For example if you already page a WordPress Page in your system named 'Gallery' most likely the URL for that page will also be http://www.mysite.com/gallery/ This means you cannot change '/media-tags/' also to gallery since this means they would share the same URL which is not allowed.", MEDIA_TAGS_I18N_DOMAIN); ?></p>
						<?php mediatag_settings_boxfooter(false); ?>
					</div>

					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-display-shortcodes', 
							__("Media-Tags and shortcodes", MEDIA_TAGS_I18N_DOMAIN));
						?>
						<p><?php _e("The Media-Tags plugin also supports shortcodes you can add directly to you content. This would allow you to add a selection of images to you page like the WordPress gallery.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e("To use the Media-Tags shortcode you need to open or edit a Post/Page in wp-admin. In the content area you need to first make sure you are on the HTML tab and not the VISUAL tab. Once you are using the HTML tab you can enter the Media-Tags shortcode directly into the content area.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e('In it\'s simplest form the shortcode for Media-Tags looks something like this:', MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e('<blockquote>[media-tags media_tags="flags"]</blockquote>', MEDIA_TAGS_I18N_DOMAIN); ?></p>
						
						<p><?php _e('The beginning part of the shortcode <strong><em>[media-tags</em></strong> is required and indicates to WordPress which shortcode functionality to implement. The next part <strong><em>media_tags="flags"</em></strong> is also required. This element tells the system which Media-Tag terms to display. You can use multiple terms here. These terms need to be comma separated.', MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<p><?php _e("Media-Tags support many more optional parameters which are described below. Each parameter below work within the shortcode construct by using the parameter=\"some value\" for example numberposts=\"10\". Most of the parameters used are similar to parmeters used by the WordPress <a target=\"_blank\" href=\"http://codex.wordpress.org/Template_Tags/get_posts\">get_posts</a> function. ", MEDIA_TAGS_I18N_DOMAIN); ?></p>
						
						<ul>
							<li><strong>numberposts</strong>: <?php _e("Default is '-1' for all. The number of items (maximum) to display. If you have 51 items tagged 'Flags' and you set numberposts=\"10\" then only 10 will be displayed.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							
								<li><strong>tags_compare</strong>: <?php _e("Default is 'OR'. Possible values 'OR' or 'AND'. When passing multiple values for the media_tags parameter this parameter controls how they are compares. An 'OR' compare means all elements which mean one or more of the media_tags values. An 'AND' compare mean only elements which match all values.", MEDIA_TAGS_I18N_DOMAIN); ?></li>

								<li><strong>media_types</strong>: <?php _e("Default is '' for all. Multiple values can be used provided they are separated with commas. You can limit the type of the media elements returned to images, PDF, Excel, etc by using this parameter. There is not a section list of possible values. Some example values are jpg, gif, pdf, excel. Note this compare is done by comparing the File Type determined by WordPress. When you edit a Media item look for the 'File type' field on the form. This is usually something like application/vnd.ms-excel or image/jpeg. You can leave off the beginning part 'application/'.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
								
								<li><strong>post_type</strong>: <?php _e("Default is 'attachment'. Currently the only possible values for this parameter is 'attachment'.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
								<li><strong>orderby</strong>: <?php _e("Default is 'menu_order'. Controls the ordering of items displayed. Other possible value(s) are ID,", MEDIA_TAGS_I18N_DOMAIN); ?></li>
								<li><strong>order</strong>: <?php _e("Default 'ASC'.  Controls the Ascending (ASC) or Descending (DESC) order of the items.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
								<li><strong>offset</strong>: <?php _e("Default is '0'. Controls the beginning of the displayed items. If you have 51 items tagged 'Flags' and set the offset=\"10\" then the first 9 items will not be displayed.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
								
								<li><strong>return_type</strong>: <?php _e("Default 'li'. By default the Media-Tags shortcode will return the items as a list items. There is logic within the shortcode functions to output the wrapping &lt;ul&gt;&lt;/ul&gt; code only if items were found.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
								<li><strong>search_by</strong>: <?php _e("Default is 'slug'. No other values are supported.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
								<li><strong>display_item_callback</strong>: <?php _e("The shortcodes functions within the Media-Tags plugin uses a default callback function to display each of the return items from the shortcode selection. You can define your own display function. Look in the plugin folder for the file named 'mediatags_shortcodes.php'. Within this file is a function 'default_item_callback' which can be copied into your own theme's functions.php file. There you can customize the callback function to your needs. You must also rename the function to something other than 'default_item_callback'. Within the Media-Tags shortcode parameters you must set the 'display_item_callback' to call your new function.", MEDIA_TAGS_I18N_DOMAIN); ?></li>

								<li><strong>size</strong>: <?php _e("Default is 'medium'. Related to the output callback function ad displaying of images in WordPress. As you may know WordPress supports 4 images sizes for your needs thumbnail, medium, large and full.</li>
								<li><strong>before_list</strong>: Default is '&lt;ul&gt;. You can override this default and use an ordered list or an unordered list with a specific class or id attribute. You can include a h2 header for the title before the list. </li>
								<li><strong>after_list</strong>: Default is '&lt;/ul&gt;. Obviously this closing element must match the opening element of the 'before_list' parameter.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
						</ul>

						<?php mediatag_settings_boxfooter(false); ?>
					</div>

					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-template_functions', 
							__("Media-Tags Template Functions", MEDIA_TAGS_I18N_DOMAIN));
						?>
						<p><?php _e("If you know PHP and consider yourself a programmer of sorts then the Media-Tags plugin has a good set of template functions you can also use to access the selection of Media-Tag items. All template related functions are located in the plugin file 'mediatags_template_functions.php'", MEDIA_TAGS_I18N_DOMAIN); ?></p>
						
						<ul>
							<li><strong>get_attachments_by_media_tags</strong>: <?php _e("This function works just like the shortcode object in the previous section. But this PHP function allows for better integration and control over the PHP functionality. This function takes an array of key/value pairs like many other WordPress functions. You can refer to the shortcode parameters for the full list of acceptable keys supported.", MEDIA_TAGS_I18N_DOMAIN); ?></p></li>
							<li><strong>is_mediatag</strong>: <?php _e("Functions much like other WordPress is_* functions is_page, is_archive. Returns true is you are viewing a Media-Tags archive page.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>in_mediatag</strong>: <?php _e("Similar to the in_category function.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>has_mediatag</strong>: <?php _e("Similar to has_tag and has_category functions.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>get_mediatags</strong>: <?php _e("Similar to get_categories and get_tags functions.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>list_mediatags</strong>: <?php _e("Returns a list (not array) of Media-Tags terms. Commonly used in the sidebar to list the linked Media-Tags terms.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>get_mediatag_link</strong>: <?php _e("Given a Media-Tags term ID will return the href for that term. There is a second optional argument for returning the RSS version of the link href.", MEDIA_TAGS_I18N_DOMAIN); ?> </li>
							<li><strong>the_mediatags</strong>: <?php _e("Similar to the WordPress function the_tags. Will output a comma separated list of link terms for the viewed attachment. Must be used within the WP loop", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>single_mediatag_title</strong>: <?php _e("Return the Name/Title of currently viewed Media-Tags term. Used like the single_cat_title or single_tag_title functions.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>mediatags_cloud</strong>: <?php _e("A wrapper to the generic wp_tag_cloud function. All the same parameters are supported.", MEDIA_TAGS_I18N_DOMAIN); ?></li>							
							<li><strong>mediatags_description</strong>: <?php _e("Returns the description of a specific Media-Tags term ID if set.", MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>mediatags_get_post_mediatags</strong>: <?php _e("Returns an array of media-tags terms for a given attachment. I know this function reads 'post'. But this is for media items. ", MEDIA_TAGS_I18N_DOMAIN); ?></li>
						</ul>
						
						<?php mediatag_settings_boxfooter(false); ?>
					</div>
					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-template_files', 
							__("Media-Tags Archive Template Hierarchy", MEDIA_TAGS_I18N_DOMAIN));
						?>						
						<p><?php _e("The selection of which theme template file will be used to display the Media-Tags archives follows the published WordPress Template Hierarchy. the following templates are preferred over the previous listing since these template names are supported by WordPress and not the plugin.", MEDIA_TAGS_I18N_DOMAIN); ?></p>
						<ol>
							<li><strong>taxonomy-media-tags-slug.php</strong> example: taxonomy-media-tags-flags.php</li>
							<li><strong>taxonomy-media-tags.php</strong> example: taxonomy-media-tags.php</li>
							<li><strong>taxonomy.php</strong></li>
							<li><strong>archive.php</strong></li>
							<li><strong>index.php</strong></li>
						</ol>

						<p><?php _e("In previous version of the Media-Tags plugin there was support for template files from the following listing. These should be considered deprecated over the above listing of WordPress supported template files. If your theme uses the older template filenames you should be able to rename theme to the newer versions without issue.", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<ol>
							<li><strong>mediatag-term.php</strong> <?php 
								_e("example: mediatag-flag.php rename to taxonomy-media-tags-flags.php", 
								MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>mediatag-id.php</strong> <?php 
								_e("example: mediatag-2.php rename to taxonomy-media-tags-flags.php", 
								MEDIA_TAGS_I18N_DOMAIN); ?></li>
							<li><strong>mediatag.php</strong> <?php 
								_e("rename to taxonomy-media-tags.php", 
								MEDIA_TAGS_I18N_DOMAIN); ?></li>
						</ol>
							
						<?php mediatag_settings_boxfooter(false); ?>
					</div>

					<div class="meta-box-sortabless">			
						<?php 
							mediatag_settings_boxheader('mediatag-help-template_files-rss', 
							__("Media-Tags Template Files RSS", MEDIA_TAGS_I18N_DOMAIN));
						?>
						<p><?php _e("Similar to the Media-Tags Archive Template files. The plugin also supports the use of an RSS template file. When enabled (See Media-Tags > Settings) a new RSS output will be provided on any Media-Tags archive page. So when the user views your page http://www.mysite.com/media-tags/flags/ they can also subscribe to the RSS fee of this archive by accessing the URL. http://www.mysite.com/media-tags/flags/feed/. This RSS output can be customized by copying the plugin file 'mediatags_rss.php' into your active theme folder. You can disable the automatic RSS feed option via the Media-Tags > Settings panel. ", MEDIA_TAGS_I18N_DOMAIN); ?></p>

						<?php mediatag_settings_boxfooter(false); ?>
					</div>

				</div>
			</div>
		</div>
	</div>
	<?php	
}
?>