jQuery(document).ready(function(){

	jQuery('#media-tags-bulk-panel input[name=media_tags_action]').click(function(){
		var media_tags_action = jQuery("#media-tags-bulk-panel input[name=media_tags_action]:checked").val();
		if (media_tags_action == "remove")
		{
			jQuery('div#mediatag-new-input-container').slideUp('slow');
		}
		else if (media_tags_action == "assign")
		{
			jQuery('div#mediatag-new-input-container').slideDown('slow');			
		}
	});
});

function mediatags_process_bulk_selections(location_type)
{	
	var media_tags_input = "";
	media_tags_input = jQuery("#media-tags-bulk-panel input[name=media_tags_input]").val();
	//alert("media_tags_input=["+media_tags_input+"]");

	var select_media_tags = "";
/*
	if (location_type == "library")
	{
		jQuery('div#media-tags-bulk-panel ul li input.bulk-media-tag-item:checked').each(function(){
			if (select_media_tags != "")
				select_media_tags = select_media_tags+",";
			select_media_tags = select_media_tags+jQuery(this).val();
		});
	}
	else if (location_type == "inline")
	{
		jQuery('div#media-tag-bulk-gallery-container ul li input.bulk-media-tag-item:checked').each(function(){
			if (select_media_tags != "")
				select_media_tags = select_media_tags+",";
			select_media_tags = select_media_tags+jQuery(this).val();
		});			
	}
*/
		jQuery('div#media-tags-bulk-panel input.bulk-media-tag-item:checked').each(function(){
			if (jQuery(this).val() != "on")
			{
				if (select_media_tags != "")
					select_media_tags = select_media_tags+",";
				select_media_tags = select_media_tags+jQuery(this).val();
			}
		});


	//alert("select_media_tags=["+select_media_tags+"]");
	
	if ((select_media_tags == "") && (media_tags_input == ""))
	{
		jQuery('#media-tags-error').html('<p>Please enter or select which Media Tags should be applied to the selected Media Items.</p>');
		jQuery('#media-tags-error').focus();
		return false;
	}				
	else
	{
		jQuery('#media-tags-error').html('<p></p>');
	}
	
	var select_media_items = "";
	if (location_type == "library")
	{			
		jQuery('form#posts-filter tbody').find('input[type=checkbox]:checked').each( function(){
			if (jQuery(this).val() != "on")
			{
				if (select_media_items != "")
					select_media_items = select_media_items+",";
				select_media_items = select_media_items+jQuery(this).val();				
			}
		});
	}
	else if (location_type == "inline")
	{
		//jQuery('div.media-tags-bulk-column input[type=checkbox]:checked').each( function(){
		jQuery('div.media-item input[type=checkbox]:checked').each( function(){
			if (jQuery(this).val() != "on")
			{
				if (select_media_items != "")
					select_media_items = select_media_items+",";
					select_media_items = select_media_items+jQuery(this).val();
			}
		});				
	}
	//alert("select_media_items=["+select_media_items+"]");
	
	if (select_media_items == "")
	{
		jQuery('#media-tags-error').html('<p>You must first select which Media Items to change.</p>');
		jQuery('#media-tags-error').focus();			
		return false;
	}
	else
	{
		jQuery('#media-tags-error').html('<p></p>');
	}
			
	var media_tags_action = "";
	media_tags_action = jQuery("#media-tags-bulk-panel input[name=media_tags_action]:checked").val();
	//alert("media_tags_action=["+media_tags_action+"]");
	
	if (media_tags_action == "")
	{
		jQuery('#media-tags-error').html('<p>You must select and action of Assign or Remove to apply to the Media Items and Media Tags.</p>');
		jQuery('#media-tags-error').focus();			
		return false;		
	}
	else
	{
		jQuery('#media-tags-error').html('<p></p>');
	}

	var data = {
		action: 'media_tags_bulk_action',
		media_tags_action: media_tags_action,
		media_tags_input: media_tags_input,
		select_media_items: select_media_items,
		select_media_tags: select_media_tags
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		location.reload();
	});
}
