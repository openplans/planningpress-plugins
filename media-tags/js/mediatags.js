jQuery(document).ready(function(){

	jQuery('div.media-tags-list-used').show();
	jQuery('div.media-tags-list-common').hide();
	jQuery('div.media-tags-list-uncommon').hide();

	jQuery("a.media-tags-show-hide-used").click(function () {
		jQuery("div#media-tags-list-used", this).slideToggle('slow');
		jQuery(this).text(jQuery(this).text() == 'Show Media Tags for this attachment' ? 'Media Tags for this attachment' : 'Show Media Tags for this attachment');
		return false;
	});

	jQuery("a.media-tags-show-hide-common").click(process_common_mediatags);
	
	function process_common_mediatags(e) {
		var self = this;
		var post_id = jQuery(self).attr('post-id');
		e.preventDefault();

		if (jQuery('div#media-tags-list-common-'+post_id).is(':visible'))
		{
			jQuery('div#media-tags-list-common-'+post_id).slideUp('fast');	
		}
		else
		{
			if (jQuery('div#media-tags-list-common-'+post_id+' .media-tags-list').children().length == 0) 
			{
				var data = {action:'get_mediatags_ajax', mediatags_type: 'common', post_id: jQuery(self).attr('post-id')};

				jQuery('.loading-spinner', self).remove();
				jQuery('<img class="loading-spinner" src="images/wpspin_light.gif" />').prependTo(self).css({float:'left'});

				jQuery.post(ajaxurl, data, function(resp_media_tags) 
				{
					if (resp_media_tags) 
					{
						jQuery('div#media-tags-list-common-'+post_id+' .media-tags-list').html(resp_media_tags);
						jQuery('div#media-tags-list-common-'+post_id).slideDown('fast');
						jQuery(self).text(jQuery(self).text() == 'Show Common Media Tags' ? 'Hide Common Media Tags' : 'Show Common Media Tags');
						jQuery('.loading-spinner', self).remove();
					}
				}, 'html');
			} 
			else 
			{
				jQuery('div#media-tags-list-common-'+post_id).slideDown('fast');
				jQuery(self).text(jQuery(self).text() == 'Show Common Media Tags' ? 'Hide Common Media Tags' : 'Show Common Media Tags');
			}
		}
		return false;
	}

	jQuery("a.media-tags-show-hide-uncommon").click(process_uncommon_mediatags);

	function process_uncommon_mediatags (e) {
		var self = this;
		var post_id = jQuery(self).attr('post-id');
		
		e.preventDefault();
		
		if (jQuery('div#media-tags-list-uncommon-'+post_id+' .media-tags-list').children().length == 0) 
		{
			var data = {action:'get_mediatags_ajax', mediatags_type: 'uncommon', post_id: jQuery(self).attr('post-id')};
			
			jQuery('.loading-spinner', self).remove();
			jQuery('<img class="loading-spinner" src="images/wpspin_light.gif" />').prependTo(self).css({float:'left'});
			
			jQuery.post(ajaxurl, data, function(resp_media_tags) {
				if (resp_media_tags) {
					jQuery('div#media-tags-list-uncommon-'+post_id+' .media-tags-list').html(resp_media_tags);
					jQuery('div#media-tags-list-uncommon-'+post_id).slideToggle('fast');
					jQuery(self).text(jQuery(self).text() == 'Show Uncommon Media Tags' ? 'Hide Uncommon Media Tags' : 'Show Uncommon Media Tags');
					jQuery('.loading-spinner', self).remove();
				}
			}, 'html');
		} 
		else {
			jQuery('div#media-tags-list-uncommon'+post_id).slideToggle('fast');
			jQuery(self).text(jQuery(self).text() == 'Show Uncommon Media Tags' ? 'Hide Uncommon Media Tags' : 'Show Uncommon Media Tags');
		}
		return false;
	}



	// When using the Media Upload Popup we are adding a 'Media-Tags' Tab to the popup. When a selection is made to filter the Library
	// for a specific Media-Tag we then want to remove the 'mediatag_id' from the Tab URLs. The Reason is if the user no longer
	// wants to filter the Library tab they are stuck. 
	FindTabHREF('li#tab-type a', 'mediatag_id');
	FindTabHREF('li#tab-type_url a', 'mediatag_id');
	FindTabHREF('li#tab-gallery a', 'mediatag_id');
	FindTabHREF('li#tab-library a', 'mediatag_id');
	FindTabHREF('li#tab-mediatags a', 'mediatag_id');
	
	function FindTabHREF(query_pattern, query_string_search)
	{
		if (jQuery(query_pattern).attr('href'))
		{
			var media_library_url = jQuery(query_pattern).attr('href');
			if (media_library_url != "")	
			{
				var url_new = RemoveParameterFromUrl(media_library_url, query_string_search);
				if (url_new != "")
					jQuery(query_pattern).attr('href', url_new);
			}
		}	
	}

	function RemoveParameterFromUrl( url_old, parameter ) 
	{
		var url_new = "";
		
		var urlparts= url_old.split('?');   
		if (urlparts.length>=2) 
		{
	    	var prefix= encodeURIComponent(parameter)+'=';
	    	var pars= urlparts[1].split(/[&;]/g);
	    	for (var i= pars.length; i-->0;)               
			{
	        	if (pars[i].lastIndexOf(prefix, 0)!==-1)   
	            	pars.splice(i, 1);
			}
	    	url_new= urlparts[0]+'?'+pars.join('&');
		}
		return url_new;
	}
});
