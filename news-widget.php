<?php

/*
Plugin Name: New Post Widget
Plugin URI: http://openplans.org/
Description: A widget to display the most recent blog post.
Author: Andy Cochran
Version: 0.1
Author URI: http://openplans.org/team/#andy-cochran
*/

class NewPostWidget extends WP_Widget {
    /** constructor */
    function NewPostWidget() {
        parent::WP_Widget(false, $name = 'New Post Widget');    
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {        
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        ?>
            <?php echo $before_widget; ?>
                <?php if ( $title ) 
                    echo $before_title . $title . $after_title; 


                query_posts( 'post_type=post&posts_per_page=1' );
                if ( have_posts() )
                    ?>
                    <?php
                    while ( have_posts() ) : the_post(); ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-header">
                        <h4 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
                        <div class="entry-meta">
                            <?php twentyten_posted_on(); ?>
                        </div><!-- .entry-meta -->
                    </div>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->

                    <div class="entry-utility">
                        <?php twentyten_posted_in(); ?>
                        <?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
                    </div><!-- .entry-utility -->
                </div><!-- #post-## -->
                    <?php 
                    endwhile; // end of the loop.
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

} // class NewPostWidget

// register NewPostWidget widget
add_action('widgets_init', create_function('', 'return register_widget("NewPostWidget");'));

?>