jQuery(document).ready(function(){
// These were moved to the admin footer action. In WP 3.1 there are hooks to insert these into the buk action dropdown.
//	jQuery('form#posts-filter select[name=action]').append('<option value="media-tags">Media-Tags</option>');				
//	jQuery('form#posts-filter select[name=action2]').append('<option value="media-tags">Media-Tags</option>');				
	
	jQuery('form#posts-filter').submit(function(){
		var is_mediatag_action = jQuery('form#posts-filter select[name=action]').val();
		var is_mediatag_action2 = jQuery('form#posts-filter select[name=action2]').val();

		if ((is_mediatag_action == "media-tags") || (is_mediatag_action2 == "media-tags"))
		{
			var select_media_items = "";
			var select_media_items_cnt = 0;
			var select_media_items_text = "";
			
			jQuery('form#posts-filter tbody').find('input[type=checkbox]:checked').each( function(){
				if (select_media_items != "")
					select_media_items = select_media_items+",";
				select_media_items = select_media_items+jQuery(this).val();
				select_media_items_cnt = select_media_items_cnt + 1;
				
				var p_parent_id = jQuery(this).parent().parent().attr('id');

				var select_media_items_anchor = jQuery('tr#'+p_parent_id+' td.column-media a:first').each(function() {
					if (select_media_items_text != "")
						select_media_items_text = select_media_items_text+", ";
					select_media_items_text = select_media_items_text+jQuery(this).text();
				});
				var select_media_items_anchor = jQuery('tr#'+p_parent_id+' td.column-title a:first').each(function() {
					if (select_media_items_text != "")
						select_media_items_text = select_media_items_text+", ";
					select_media_items_text = select_media_items_text+jQuery(this).text();
				});
			});
			
			if (select_media_items == "")
			{
				show_media_tags_error_popup();
				//jQuery("#media-tags-bulk-selection-error").dialog('open');
			}
			else
			{
				jQuery('span#media-items-count').html( "<strong>("+select_media_items_cnt+"):<br />"+select_media_items_text+"</strong>" );
				show_media_tags_bulk_admin();
			}	
			return false;
		}	
	});
	
	function show_media_tags_bulk_admin()
	{
		var dialog_buttons = {};

		var button_name = "";
		button_name = jQuery('#media-tags-bulk-content-buttons .submit').text();
		if (button_name != "")
			dialog_buttons[button_name] = function() { mediatags_process_bulk_selections('library'); }

		button_name = "";
		button_name = jQuery('#media-tags-bulk-content-buttons .cancel').text();
		if (button_name != "")
			dialog_buttons[button_name] = function() { 
				jQuery('#media-tags-bulk-panel input[@name=media_tags_input]:checked').attr('checked', '');
				
				jQuery(this).dialog("close"); 
				return false; 
			}
		
		jQuery("#media-tags-bulk-panel").dialog({
			autoOpen: false,
			width: 600,
			height: 500,
			resizable: true,
			buttons: dialog_buttons
		});
		jQuery('#media-tags-bulk-panel input#media_tags_action_assign').attr('checked', 'checked');
		
		jQuery("#media-tags-bulk-panel").dialog('open');		
	}

	function show_media_tags_error_popup()
	{
		var button_name = jQuery('#media-tags-bulk-content-buttons .close').text();
		var dialog_buttons = {};
		dialog_buttons[button_name] = function() { jQuery(this).dialog("close"); return false;}

		jQuery("#media-tags-bulk-selection-error").dialog({
			autoOpen: false,
			width: 300,
			height: 200,
			resizable: true,
			position: 'center',
			buttons: dialog_buttons
		});
		jQuery("#media-tags-bulk-selection-error").dialog('open');
	}

});