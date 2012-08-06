<?php

/*
Plugin Name: Recent Posts (Full Content)
Plugin URI: http://openplans.org/
Description: A widget to display the most recent blog posts with content.
Author: Andy Cochran
Version: 0.1
Author URI: http://openplans.org/team/#andy-cochran
*/

class RecentPostsFullContent extends WP_Widget {
    /** constructor */
    function RecentPostsFullContent() {
        parent::WP_Widget(false, $name = 'Recent Posts (Full Content)');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $num_posts = apply_filters('widget_num_posts', $instance['num_posts']);
        ?>
            <?php echo $before_widget; ?>
                <?php
                query_posts( 'post_type=post&posts_per_page=' . $num_posts );
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
    $instance['num_posts'] = strip_tags($new_instance['num_posts']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $num_posts = esc_attr($instance['num_posts']);
        if (!$num_posts)
          $num_posts = 0;
        ?>
            <p><label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number of Posts:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo $num_posts; ?>" /></label></p>
        <?php
    }

} // class RecentPostsFullContent

// register RecentPostsFullContent widget
add_action('widgets_init', create_function('', 'return register_widget("RecentPostsFullContent");'));

?>