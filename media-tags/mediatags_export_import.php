<?php
function mediatags_wp_export_metadata()
{
	$post_attachments = get_posts('post_type=attachment&numberposts=-1');
	if ($post_attachments)
	{
		foreach($post_attachments as $attachment)
		{
			$mediatag_post_terms = get_the_terms( $attachment->ID, MEDIA_TAGS_TAXONOMY );
			if ($mediatag_post_terms)
			{
				$terms = array();
				foreach($mediatag_post_terms as $term)
				{
					$terms[] = $term->slug;
				}
				if (count($terms))
					update_post_meta( $attachment->ID, 'post_media_tags_export', implode(',', $terms));
			}			
		}
	}	
}

function mediatags_wp_import_metadata($post_id='', $key='', $value='')
{
	if (!$post_id) return;
	if (!$key) return;
	if (!$value) return;
	
	if ($key == 'post_media_tags_export')
	{
		$mediatag_meta_items = split(",", $value);
		if ($mediatag_meta_items)
			wp_set_object_terms($post_id, $mediatag_meta_items, MEDIA_TAGS_TAXONOMY);
	}
}
?>