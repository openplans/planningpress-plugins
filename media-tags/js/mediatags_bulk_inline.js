jQuery(document).ready(function(){
	if (jQuery('form#gallery-form').length > 0)
	{
		var mediatag_bulk_block = jQuery('div#media-tags-bulk-panel').html();
		jQuery('#media-tags-bulk-panel').remove();

		var mediatags_label = jQuery('#media-tags-bulk-content-buttons .media-tags').text();
		var mediatags_show = jQuery('#media-tags-bulk-content-buttons .show').text();
		var mediatags_hide = jQuery('#media-tags-bulk-content-buttons .hide').text();

		var mediatags_showhide_link = '<span class="media-tab-bulk-trigger-container">'+mediatags_label+': <a class="media-tag-bulk-gallery-trigger" href="#">'+mediatags_show+'</a></span>';		
		jQuery(mediatags_showhide_link).insertBefore('div#sort-buttons span');

		var mediatags_bulk_container = '<div id="media-tag-bulk-gallery-container"><div id="media-tags-bulk-panel" style="display:none">'+mediatag_bulk_block+'</div></div>';
		jQuery(mediatags_bulk_container).insertBefore('form#gallery-form');		

		// Define the event which will Show or Hide the Media-Tags Bulk Mgmt div. 
		jQuery("a.media-tag-bulk-gallery-trigger").click(function () {
			if (jQuery('div#media-tags-bulk-panel').is(':visible'))
			{
				jQuery('div#media-tags-bulk-panel').slideUp('slow');
				jQuery('a.media-tag-bulk-gallery-trigger').text(mediatags_show);
			}
			else
			{
				jQuery('div#media-tags-bulk-panel').slideDown('slow');
				jQuery('a.media-tag-bulk-gallery-trigger').text(mediatags_hide);			
			}
			return false;
		});

		// Add a new TH in the last position for a header. 
		var mediatag_header = '<th class="media-tags-bulk-column"><input class="bulk-media-tag-item" type="checkbox" name="mediatag-check-all" id="mediatag-check-all"  /></th>';
		jQuery('form#gallery-form table.widefat th:nth-child(3)').after(mediatag_header);

		// Then for each row add a checkbox on the end of the row. 
		jQuery('form#gallery-form #media-items div.media-item').each(function(){
			var item_id = jQuery(this).attr('id');
			var item_parts = item_id.split('-');
			var item_media_id = item_parts[2];
			var mediatag_checkbox = '<div class="media-tags-bulk-column"><input class="bulk-media-tag-item" type="checkbox" name="mediatag-'+item_media_id+'" id="mediatag-'+item_media_id+'" value="'+item_media_id+'" /></div>';
			jQuery('div#'+item_id+' a.describe-toggle-on').before(mediatag_checkbox);
		});
	
		// Add an event trigger to select all checkboxes on the rows. 
		jQuery('th.media-tags-bulk-column input#mediatag-check-all').click(function(){
			if (jQuery(this).is(':checked'))
			{
				jQuery('div.media-tags-bulk-column input[type=checkbox]').each( function(){
					jQuery(this).attr('checked', true);
				});
			}
			else
			{
				jQuery('div.media-tags-bulk-column input[type=checkbox]').each( function(){
					jQuery(this).attr('checked', false);
				});				
			}
		});

		// Work the Media-Tag button event
		jQuery('input#meditags-save').click(function(){
			mediatags_process_bulk_selections('inline');
		});
	}
	
	if (jQuery('form#library-form').length > 0)
	{
		var mediatag_bulk_block = jQuery('div#media-tags-bulk-panel').html();
		jQuery('#media-tags-bulk-panel').remove();

		var mediatags_label = jQuery('#media-tags-bulk-content-buttons .media-tags').text();
		var mediatags_show = jQuery('#media-tags-bulk-content-buttons .show').text();
		var mediatags_hide = jQuery('#media-tags-bulk-content-buttons .hide').text();
		var mediatags_all = jQuery('#media-tags-bulk-content-buttons .all').text();

		var mediatag_header = '<div class="media-tags-bulk-header">'+mediatags_all+'<input class="bulk-media-tag-item" type="checkbox" name="mediatag-check-all" id="mediatag-check-all"  /></div>';
		jQuery('div#media-items').before(mediatag_header);

		var mediatags_showhide_link = '<span class="media-tab-bulk-trigger-container">'+mediatags_label+': <a class="media-tag-bulk-gallery-trigger" href="#">'+mediatags_show+'</a></span>';		
		//jQuery('p#media-search').before(mediatags_showhide_link);
		jQuery('div.media-tags-bulk-header').before(mediatags_showhide_link);
		
		var mediatags_bulk_container = '<div id="media-tag-bulk-gallery-container"><div id="media-tags-bulk-panel" style="display:none">'+mediatag_bulk_block+'</div></div><div style="clear:both"></div>';
		//jQuery(mediatags_bulk_container).insertBefore('div#media-items');		
		jQuery('div#media-items').before(mediatags_bulk_container);


		// Then for each row add a checkbox on the end of the row. 
		jQuery('form#library-form #media-items div.media-item').each(function(){
			var item_id = jQuery(this).attr('id');
			var item_parts = item_id.split('-');
			var item_media_id = item_parts[2];
			var mediatag_checkbox = '<div class="media-tags-bulk-column"><input class="bulk-media-tag-item" type="checkbox" name="mediatag-'+item_media_id+'" id="mediatag-'+item_media_id+'" value="'+item_media_id+'" /></div>';
			jQuery('div#'+item_id+' a.describe-toggle-on').before(mediatag_checkbox);
		});

		// Define the event which will Show or Hide the Media-Tags Bulk Mgmt div. 
		jQuery("a.media-tag-bulk-gallery-trigger").click(function () {
			if (jQuery('div#media-tags-bulk-panel').is(':visible'))
			{
				jQuery('div#media-tags-bulk-panel').slideUp('slow');
				jQuery('a.media-tag-bulk-gallery-trigger').text(mediatags_show);
			}
			else
			{
				jQuery('div#media-tags-bulk-panel').slideDown('slow');
				jQuery('a.media-tag-bulk-gallery-trigger').text(mediatags_hide);			
			}
			return false;
		});

		// Add an event trigger to select all checkboxes on the rows. 
		jQuery('div.media-tags-bulk-header input#mediatag-check-all').click(function(){
			if (jQuery(this).is(':checked'))
			{
				jQuery('div.media-tags-bulk-column input[type=checkbox]').each( function(){
					jQuery(this).attr('checked', true);
				});
			}
			else
			{
				jQuery('div.media-tags-bulk-column input[type=checkbox]').each( function(){
					jQuery(this).attr('checked', false);
				});				
			}
		});
		
		// Work the Media-Tag button event
		jQuery('input#meditags-save').click(function(){
			mediatags_process_bulk_selections('inline');
			return false;
		});		
		
	}	
});