<?php
function media_tags_bulk_action_callback() {
//	echo "_REQUEST<pre>"; print_r($_REQUEST); echo "<pre>";
//exit;	
	if (isset($_REQUEST['media_tags_action']))
		$media_tags_action = $_REQUEST['media_tags_action'];
	else
		$media_tags_action = "";

	if (isset($_REQUEST['media_tags_input']))
		$media_tags_input = $_REQUEST['media_tags_input'];
	else
		$media_tags_input = "";

	if ((isset($_REQUEST['select_media_tags'])) && (strlen($_REQUEST['select_media_tags'])))
		$select_media_tags = explode(",",$_REQUEST['select_media_tags']);
	else
		$select_media_tags = array();
		
	if ((isset($_REQUEST['select_media_items'])) && (strlen($_REQUEST['select_media_items'])))
		$select_media_items = explode(",", $_REQUEST['select_media_items']);
	else
		$select_media_items = array();


	//echo "media_tags_action=[". $media_tags_action ."]<br />";
	//echo "media_tags_input=[". $media_tags_input ."]<br />";
	//echo "select_media_tags<pre>"; print_r($select_media_tags); echo "</pre>";
	//echo "select_media_items<pre>"; print_r($select_media_items); echo "</pre>";
	
	//mediatag_process_admin_forms($media_tags_action, $select_media_items, $select_media_tags, $media_tags_input);
	
	// First process any new Tags entered via the input field...
	if ((strlen($media_tags_input)) && ($media_tags_action == "media_tags_assign"))
	{
		$tags_tmp_array = split(',', $media_tags_input);
		if ($tags_tmp_array)
		{
			foreach($tags_tmp_array as $idx => $tag_val)
			{
				$tag_slug = sanitize_title_with_dashes($tag_val);

				if ( ! ($id = term_exists( $tag_slug, MEDIA_TAGS_TAXONOMY ) ) )
				{
					//echo "id<pre>"; print_r($id); echo "</pre>";
					//echo "tag_val=[". $tag_val."]<br />";
					//echo "tag_slug=[". $tag_slug."]<br />";
					$inserted_term_id = wp_insert_term($tag_val, MEDIA_TAGS_TAXONOMY, array('slug' => $tag_slug));
					if (isset($inserted_term_id['term_id']))
						$select_media_tags[] = $inserted_term_id['term_id'];
				}
				else
					$select_media_tags[] = $id['term_id'];
			}
		}
	}
		
	if ( (strlen($media_tags_action)) && (count($select_media_items)) && (count($select_media_tags)) )
	{
		$selected_media_tag_terms = array();
		//$selected_media_tag_terms = get_terms(MEDIA_TAGS_TAXONOMY, array('include' => $select_media_tags));
		foreach($select_media_tags as $media_tag_id)
		{
			$selected_media_tag_terms[] = get_term($media_tag_id, MEDIA_TAGS_TAXONOMY);
		}
		//echo "selected_media_tag_terms<pre>"; print_r($selected_media_tag_terms); echo "</pre>\n";
		
		if ($media_tags_action == "media_tags_assign")
		{
			foreach($select_media_items as $select_media_item_id)
			{
				$media_tag_slugs = array();
				
				$media_item_terms_current = wp_get_object_terms($select_media_item_id, MEDIA_TAGS_TAXONOMY);
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
					foreach($media_item_terms_current as $idx => $current_term)
						$media_tag_slugs[$current_term->slug] = $current_term->slug;

					foreach($selected_media_tag_terms as $selected_media_tag_term)
						$media_tag_slugs[$selected_media_tag_term->slug] = $selected_media_tag_term->slug;
				}
				if (count($media_tag_slugs))
				{
					// If the Media Item does not have any assigned Media-Tag we simple assign the selected Media-Tags
					wp_set_object_terms($select_media_item_id, $media_tag_slugs, MEDIA_TAGS_TAXONOMY);								
				}
			}
		}
		else if ($media_tags_action == "media_tags_assign")
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

	die();
}


function mediatags_bulk_admin_panel()
{
	?>
	<div id="media-tags-bulk-panel" title="<?php _e('Bulk Media-Tags Management', MEDIA_TAGS_I18N_DOMAIN); ?>" style="display:none">

		<p class="header"><?php _e('Assign or Remove Media Tags from the selected Media items. <strong>Note this action cannot be undone!</strong>', MEDIA_TAGS_I18N_DOMAIN); ?></p>

		<div id="media-tags-error"></div>
		<div id="media-items-count-container">
			<p><?php _e('Number of Media Items Selected: ', MEDIA_TAGS_I18N_DOMAIN); ?><span id="media-items-count"></span></p>
		</div>
		<div id="media-tag-action-container">
			<input type="radio" name="media_tags_action" id="media_tags_action_assign" value="media_tags_assign">&nbsp;
			<label for="media_tags_action_assign"><?php _e('Assign Selected items to...', MEDIA_TAGS_I18N_DOMAIN); ?></label><br />
			<input type="radio" name="media_tags_action" id="media_tags_action_remove" value="media_tags_remove">&nbsp;
			<label for="media_tags_action_remove"><?php _e('Remove Selected items from...', MEDIA_TAGS_I18N_DOMAIN); ?></label><br />
		</div>
		<div id="mediatag-new-input-container">
			<label for="media_tags_input"><?php _e('Enter new multiple Media Tags separated with comma', MEDIA_TAGS_I18N_DOMAIN);
			 	?></label><br />
			<input type="text" name="media_tags_input" id="media_tags_input" value="" />
		</div>
		<div style="clear:both; height: 10px"></div>
		<?php
			$mediatag_terms = (array) get_terms( MEDIA_TAGS_TAXONOMY, array('get' => 'all') );
			if ($mediatag_terms)
			{
				?>
				<div class="media-tags-bulk-list-common">
					<p><strong><?php _e('Select from the media tag(s) below', MEDIA_TAGS_I18N_DOMAIN); ?></strong></p>
					<ul class="media-tags-list"><?php
					foreach($mediatag_terms  as $idx => $tag_item)
					{
						?><li><input type="checkbox" id="bulk-media-tag-<?php echo $tag_item->term_id; ?>"
						 	class="bulk-media-tag-item" value="<?php echo $tag_item->term_id; ?>"
							name='bulk-media-tag-<?php echo $tag_item->term_id; ?>'  />
							<label for='bulk-media-tag-<?php 
								echo $tag_item->term_id; ?>'><?php echo $tag_item->name; ?></label></li><?php
					}
					?>
					</ul>
				</div>
				<div style="clear:both"></div>
				<?php
			}					
		?>
		<p class="ml-submit"><input type="submit" class="button savebutton" 
				style="display:none;" name="meditags-save" id="meditags-save" value="<?php _e('Update Media-Tags', MEDIA_TAGS_I18N_DOMAIN); ?>" /></p>		
	</div>
	<?php
}
?>