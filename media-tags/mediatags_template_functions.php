<?php
function is_mediatag()
{
	if ($is_mediatags = get_query_var('is_mediatags'))
		return true;
	else
		return false;
}

function in_mediatag($mediatag_id = '')
{
	global $wp_version;
	
	if (!$mediatag_id) return;
	
	$mediatag_var = get_query_var(MEDIA_TAGS_QUERYVAR);
	if ($mediatag_var)
	{	
	    if ( version_compare( $wp_version, '3.0', '<' ) )
			$mediatag_term = is_term( $mediatag_var, MEDIA_TAGS_TAXONOMY );
		else
			$mediatag_term = term_exists( $mediatag_var, MEDIA_TAGS_TAXONOMY );
		if ($mediatag_id === $mediatag_term['term_id'])
			return true;
	}
	return false;	
}

function has_mediatag( $tag = '', $_post = null ) {

	if ( $_post ) {
		$_post = get_post( $_post );
	} 
	else {
		$_post =& $GLOBALS['post'];
	}
	if ( !$_post )
		return false;

	$r = is_object_in_term( $_post->ID, MEDIA_TAGS_TAXONOMY, $tag );
	
	if ( is_wp_error( $r ) )
		return false;
	return $r;
}


function &get_mediatags( $args = '' ) {
	
	$media_tags = get_terms( MEDIA_TAGS_TAXONOMY, $args );

	if ( empty( $media_tags ) ) {
		$return = array();
		return $return;
	}

	$media_tags = apply_filters( 'get_mediatags', $media_tags, $args );
	return $media_tags;
}

function list_mediatags($args = '' ) {
	
	$defaults = array(
		'echo' => '1'		
	);
	$r = wp_parse_args( $args, $defaults );
	
	$media_tag_list = get_mediatags( $args );
	if (!$media_tag_list)
	{
		$return = array();
		return $return;
	}		
	
	$media_tag_list = apply_filters( 'list_mediatags', $media_tag_list, $args );
	if (!$media_tag_list)
	{
		$return = array();
		return $return;
	}		
	
	$media_tag_list_items = "";
	foreach($media_tag_list as $media_tag_item)
	{
		$media_tag_list_items .= '<li><a href="'. get_mediatag_link($media_tag_item->term_id). '">'. 
			$media_tag_item->name. '</a></li>';
	}
	
	if ($r['echo'] == 1)
		echo $media_tag_list_items;
	else
		return $media_tag_list_items;
}

// Return the href link value for a given tag_id
// modeled after WP get_tag_link() function
function get_mediatag_link( $mediatag_id, $is_feed=false )
{
	global $wp_rewrite;
	
	$term_link = get_term_link( intval($mediatag_id), MEDIA_TAGS_TAXONOMY );
	if ( !is_wp_error($term_link) )
	{
		if ($is_feed == true)
		{
			if (isset($wp_rewrite) && $wp_rewrite->using_permalinks())
			{
				$term_link .= "feed/";
			}
			else
			{
				$term_link .= "&feed=rss2";
			}
		}
		return $term_link;
	}
}

// Standard template function modeled after WP the_tags function. Used to list tags for a given post. 
function the_mediatags( $before = 'Media-Tags: ', $sep = ', ', $after = '' ) {
	return the_terms( 0, MEDIA_TAGS_TAXONOMY, $before, $sep, $after );
}

function get_attachments_by_media_tags($args='')
{
	global $mediatags;
	
	return $mediatags->get_attachments_by_media_tags($args);
}

function single_mediatag_title()
{
	global $wp_version; 
	
	$mediatag_var = get_query_var(MEDIA_TAGS_QUERYVAR);
	if ($mediatag_var) {	
	    if ( version_compare( $wp_version, '3.0', '<' ) )
			$mediatag_term = is_term( $mediatag_var, MEDIA_TAGS_TAXONOMY );
		else
			$mediatag_term = term_exists( $mediatag_var, MEDIA_TAGS_TAXONOMY );

		if (isset($mediatag_term['term_id'])) {
			$media_tag = &get_term( $mediatag_term['term_id'], MEDIA_TAGS_TAXONOMY );
			echo $media_tag->name;
		}
	}	
}

function mediatags_cloud( $args='' ) {
	if (function_exists('wp_tag_cloud'))
	{
		$defaults = array(
			'taxonomy' => MEDIA_TAGS_TAXONOMY		
		);
		$r = wp_parse_args( $args, $defaults );
		return wp_tag_cloud( $r );
	}
}

function get_the_mediatags( $id = 0 ) {
	return apply_filters( ‘get_the_mediatags’, get_the_terms( $id, MEDIA_TAGS_TAXONOMY ) );
}

function mediatags_description( $id = 0 ) {
	return term_description( $id, MEDIA_TAGS_TAXONOMY );
}

function mediatags_body_class($classes, $class='' )
{
	global $wp_version;
	
	$mediatag_var = get_query_var(MEDIA_TAGS_QUERYVAR);
	if ($mediatag_var)
	{	
		$classes[] = 'media-tags-archive';
		$classes[] = 'media-tags-slug-'. $mediatag_var;

	    if ( version_compare( $wp_version, '3.0', '<' ) )
			$mediatag_term = is_term( $mediatag_var, MEDIA_TAGS_TAXONOMY );
		else
			$mediatag_term = term_exists( $mediatag_var, MEDIA_TAGS_TAXONOMY );
		if ($mediatag_term)
			$classes[] = 'media-tags-term-id-'. $mediatag_term['term_id'];
	}
	return $classes;
}

function mediatags_get_post_mediatags($post_id) 
{
	$media_tags_tmp 	= (array)wp_get_object_terms($post_id, MEDIA_TAGS_TAXONOMY);
	
	$post_media_tags = array();
	if ($media_tags_tmp)
	{
		$post_media_tags = array(); 
		foreach($media_tags_tmp as $p_media_tag)
		{
			$post_media_tags[$p_media_tag->slug] = $p_media_tag;
		}
	}
	return $post_media_tags;
}
