<?php

/*
Plugin Name: Recent Resources Widget
Plugin URI: http://openplans.org/
Description: A widget to display the most recent posts from custom post type "resouce".
Author: Andy Cochran
Version: 0.1
Author URI: http://openplans.org/team/#andy-cochran
*/

class ResourcesWidget extends WP_Widget {
    /** constructor */
    function ResourcesWidget() {
        parent::WP_Widget(false, $name = 'Recent Resources');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        ?>
            <?php echo $before_widget; ?>
                <?php if ( $title ) 
                    echo $before_title . $title . $after_title; 


                query_posts( 'post_type=resource&posts_per_page=5' );
                if ( have_posts() )
                    ?>
                    <ul class="recent-recources-widget">
                    <?php
                    while ( have_posts() ) : the_post(); ?>
                        <li class="<?php $terms = get_the_terms($post->ID, 'resource_tags'); foreach ($terms as $term) { echo 'recent-resource-' . $term->slug; break; } ?>">
                            <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
                            <div class="entry-meta">
                                <?php twentyten_posted_on(); ?>
                            </div><!-- .entry-meta -->
                        </li>
                    <?php 
                    endwhile; // end of the loop.
                    ?>
                    </ul>
                    <p><a href="<?php echo home_url( '/' ); ?>resources/">See All Data &amp; Findings</a></p>
                    <?php
                wp_reset_query();
                ?>


              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 
    }

} // class ResourcesWidget

// register ResourcesWidget widget
add_action('widgets_init', create_function('', 'return register_widget("ResourcesWidget");'));

?>