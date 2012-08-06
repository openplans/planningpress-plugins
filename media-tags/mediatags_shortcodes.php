<?php
// [media-tags media_tags="alt-views,page-full,thumb" tags_compare="AND" orderby="menu_order" display_item_callback=""]

function mediatags_shortcode_handler($atts, $content=null, $tableid=null) 
{
	global $post, $mediatags;
	
	if ((!isset($atts['return_type'])) || ($atts['return_type'] != "li"))
		$atts['return_type'] = "li";

	if (!isset($atts['before_list']))
		$atts['before_list'] = "<ul>";

	if (!isset($atts['after_list']))
		$atts['after_list'] = "</ul>";


	if ((!isset($atts['display_item_callback'])) || (strlen($atts['display_item_callback']) == 0))
		$atts['display_item_callback'] = 'default_item_callback';

	if ((isset($atts['post_parent'])) && ($atts['post_parent'] == "this"))
		$atts['post_parent'] = $post->ID;
		
	$atts['call_source'] = "shortcode";
		
	//echo "atts<pre>"; print_r($atts); echo "</pre>";
	
	if (!is_object($mediatags)) 
		$mediatags = new MediaTags();
		
	$output = $mediatags->get_attachments_by_media_tags($atts);
	if ($output)
	{
		if (isset($atts['before_list']))
		{
			$output = $atts['before_list'] . $output;
		}
		
		if (isset($atts['after_list']))
		{
			$output = $output .$atts['after_list'];			
		}
	}
	return $output;
}

// This is the default callback function for displaing the media tag items. You can override this by creating your own function under 
// your theme and passing the name of that function as parameter 'display_item_callback'. 
// Your function needs to support the one argument $post_item which is the attachment item itself. 

// In the example (default) function below I use an optional second argument to control the size of the image displayed. The size argument is passed into get_attachments_by_media_tags() to control which image is output. As you can define your own callback function you can obviously control which version of the image you are going to display. 
function default_item_callback($post_item, $size='medium')
{
//	echo "post_item<pre>"; print_r($post_item); echo "</pre>";
	$image_src 	= wp_get_attachment_image_src($post_item->ID, $size);
	
	return '<li class="media-tag-list" id="media-tag-item-'.$post_item->ID.'"><img 
		src="'.$image_src[0].'" width="'.$image_src[1].'" height="'.$image_src[2].'"
			title="'.$post_item->post_title.'" /></li>';
}

function mediatags_item_callback_with_caption($post_item, $size='medium')
{
	$image_src 	= wp_get_attachment_image_src($post_item->ID, $size);
	
	$output_str = '<li class="media-tag-list" id="media-tag-item-'.$post_item->ID.'">';

	// WP stores the Caption into the post_excerpt 
	if (strlen($post_item->post_excerpt))
	{
		$output_str .= '[caption id="attachment_'. $post_item->ID. '" 
			align="alignnone" width="'. $image_src[1] .'" caption="'. $post_item->post_excerpt .'"]';
	}
	$output_str .= '<img src="'.$image_src[0].'" width="'.$image_src[1].'" height="'.$image_src[2].'"
			title="'.$post_item->post_title.'" />';
			
	if (strlen($post_item->post_excerpt))
	{
		$output_str .= '[/caption]';
	}
	return do_shortcode($output_str);
}

function mediatag_item_callback_show_meta($post_item, $size='medium')
{
//	$image_src 	= wp_get_attachment_image_src($post_item->ID, $size);
	$media_meta = wp_get_attachment_metadata($post_item->ID);
//	echo "media_meta<pre>"; print_r($media_meta); echo "</pre>";
	
	$image_meta = $media_meta['image_meta'];

	//print_r ($metadata);
	$meta_out = '';
	if($image_meta['camera']) 				$meta_out .= $image_meta['camera'].' ';
	if($image_meta['focal_length']) 		$meta_out .= '@ '. $image_meta['focal_length'] .' mm ';
	if($image_meta['shutter_speed']) 		$meta_out .= '- ¹/'. (1/($image_meta['shutter_speed'])) .' sec';
	if($image_meta['aperture']) 			$meta_out .= ', ƒ/'.$image_meta['aperture'] .' ';
	if($image_meta['iso']) 					$meta_out .= ', ISO '.$image_meta['iso'] .' ';
	if($image_meta['created_timestamp']) 	$meta_out .= ' on '.date('j F, Y', $image_meta['created_timestamp']) .' ';

	return $meta_out;
}
?>