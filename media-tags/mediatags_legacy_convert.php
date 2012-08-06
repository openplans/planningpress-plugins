<?php

function mediatags_plugin_version_check()
{
	// We get the version from the main WP wp_options table. 
	$media_tags_version = get_option('media-tags-version');
	
	// If we don't find the setting we can assume the plugin version is either
	//	a) Never been installed
	//	b) An older version was installed which means we need to convert. 
	
	
	if (!$media_tags_version)
	{
		// Here we need to convert the existing legacy media tags into the terms table. 
		$legacy_master_media_tags = legacy_load_master_media_tags();
		if ($legacy_master_media_tags)
		{
			foreach($legacy_master_media_tags as $legacy_slug => $legacy_name)
			{
				if ( ! ($id = term_exists( $legacy_slug, MEDIA_TAGS_TAXONOMY ) ) )
					wp_insert_term($legacy_name, MEDIA_TAGS_TAXONOMY, array('slug' => $legacy_slug));
			}
			//$media_tags_tmp = (array) get_terms(MEDIA_TAGS_TAXONOMY, 'hide_empty=0');
			//echo "media_tags_tmp<pre>"; print_r($media_tags_tmp); echo "</pre>";				
		}
		
		// Now we need to grab all the attachments in the system. Then for each one grab the meta info
		// load the media tags then set the terms relationship
		$post_attachments = get_posts('post_type=attachment&numberposts=-1');
		if ($post_attachments)
		{
			foreach($post_attachments as $attachment)
			{
				$legacy_media_meta = wp_get_attachment_metadata($attachment->ID);
				if ($legacy_media_meta)
				{
					if (isset($legacy_media_meta['image_meta']['media_tags']))
						$legacy_post_media_tags_str = $legacy_media_meta['image_meta']['media_tags'];
					else
						$legacy_post_media_tags_str = "";
				
					$legacy_post_media_tags_array = legacy_get_post_media_tags($attachment->ID, $legacy_post_media_tags_str);
					if ($legacy_post_media_tags_array)
					{
						wp_set_object_terms($attachment->ID, $legacy_post_media_tags_array, MEDIA_TAGS_TAXONOMY);
					}
				}
			}				

			foreach($post_attachments as $attachment)
			{
				$media_tags_tmp 	= (array)wp_get_object_terms($attachment->ID, MEDIA_TAGS_TAXONOMY);
			}
		}
		
		// Then insert/update the options table with the current plugin version so we don't have to check each time. 
		update_option('media-tags-version', MEDIA_TAGS_VERSION);
	}
	else if ($media_tags_version < MEDIA_TAGS_VERSION)
	{
		// Here we might need to do something for other variations. 
	}
}

function legacy_load_master_media_tags()
{
	//$master_images_tags_list = "One 1, Two 2, Three 3";
	$master_media_tags_list = get_option('media-tags');
	if ($master_media_tags_list)
	{
		$master_media_tags_tmp = split(',', $master_media_tags_list);
		if ($master_media_tags_tmp)
		{
			$master_media_tags = array();
			foreach($master_media_tags_tmp as $tag_idx => $tag_val)
			{
				if (!strlen($tag_val))
					continue;

				$tag_val_n 	= strtolower(trim($tag_val));
				$tag_val_n 	= str_replace(' ', '-', $tag_val_n);

				if (array_key_exists($tag_val_n, $master_media_tags) === false)
				{
					$master_media_tags[$tag_val_n] = trim($tag_val);
				}					
			}
			asort($master_media_tags, SORT_STRING);
			return $master_media_tags;
		}
	}
}

function legacy_get_post_media_tags($post_id, $post_media_tags_list)
{
	$post_media_tags = array();

	$post_media_tags_tmp = split(',', $post_media_tags_list);
	if ($post_media_tags_tmp)
	{
		foreach($post_media_tags_tmp as $idx => $tag_val)
		{
			$tag_val_n = strtolower(trim($tag_val));
			$tag_val_n = str_replace(' ', '-', $tag_val_n);
		
			$post_media_tags[$tag_val_n] = $tag_val;
		}
		asort($post_media_tags);
	}
	return $post_media_tags;
}

?>