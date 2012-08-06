<?php
global $post;
query_posts('posts_per_page=-1');
$all_posts = array();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php 
$custom = get_post_custom($post->ID);
$geodata = $custom['mygpGeotagsGeoMetatags'][0];
$meta_sd = $custom["pprss_event_startdate"][0];
$meta_ed = $custom["pprss_event_enddate"][0];
$meta_ad = $custom["pprss_event_allday"][0];

$all_posts[] = array(
	            'title' => get_the_title(),
		    'author' => get_the_author_meta( 'display_name'),
		    'author_url' => get_the_author_meta( 'user_url'),
		    'date_published' => get_the_date(),
		    'event_start_date' => $meta_sd,
		    'event_end_date' => $meta_ed,
		    'event_allday' => $meta_ad,
		    'event_location' => $geodata['position'],
		    'content' => get_the_content()
);



?>
<?php endwhile; 
  echo json_encode($all_posts);
else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
