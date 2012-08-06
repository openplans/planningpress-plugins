<?php
/*
Plugin Name: Recent comments widget with excerpts
Plugin URI: http://www.tacticaltechnique.com/wordpress/recent-comments-widget-with-excerpts/
Description: Modifies the built-in recent comments widget to show excerpts instead of post titles
Author: Corey Salzano
Version: 0.101109
Author URI: http://www.tacticaltechnique.com/
*/

//      i wrote this plugin because i needed it
//              if you enjoy using it, at least say thank you

/**
 * Recent_Comments widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Recent_Comments_Excerpts extends WP_Widget {

        function WP_Widget_Recent_Comments_Excerpts() {
                $widget_ops = array('classname' => 'widget_recent_comments', 'description' => __( 'The most recent comments' ) );
                $this->WP_Widget('recent-comments', __('Recent Comments'), $widget_ops);
                $this->alt_option_name = 'widget_recent_comments';

                if ( is_active_widget(false, false, $this->id_base) )
                        add_action( 'wp_head', array(&$this, 'recent_comments_style') );

                add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
                add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
        }

        function recent_comments_style() { ?>
        <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
        }

        function flush_widget_cache() {
                wp_cache_delete('recent_comments', 'widget');
        }

        function widget( $args, $instance ) {
                global $wpdb, $comments, $comment;

                extract($args, EXTR_SKIP);
                $title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Comments') : $instance['title']);
                if ( !$number = (int) $instance['number'] )
                        $number = 5;
                else if ( $number < 1 )
                        $number = 1;
                else if ( $number > 15 )
                        $number = 15;

                if ( !$comments = wp_cache_get( 'recent_comments', 'widget' ) ) {
                        $comments = $wpdb->get_results("SELECT $wpdb->comments.*, $wpdb->posts.post_title FROM $wpdb->comments JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID WHERE comment_approved = '1' AND post_status = 'publish' ORDER BY comment_date_gmt DESC");
                 //$comments = $wpdb->get_results("SELECT $wpdb->comments.* FROM $wpdb->comments JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID WHERE comment_approved = '1' AND post_status = 'publish' ORDER BY comment_date_gmt DESC");
                        wp_cache_add( 'recent_comments', $comments, 'widget' );
                }

                foreach((array) $comments as $i=>$comment) {
                  if (FeatureComments::is_comment_buried($comment->comment_ID))
                    unset($comments[$i]);
                }

                $comments = array_slice( (array) $comments, 0, $number );
?>
                <?php echo $before_widget; ?>
                        <?php if ( $title ) echo $before_title . $title . $after_title; ?>
                        <ul id="recentcomments"><?php
                        if ( $comments ) : foreach ( (array) $comments as $comment) :
                            //    print_r($comment);  
                                $aRecentComment = get_comment($comment->comment_ID);
                                $aRecentCommentTxt = trim( substr( $aRecentComment->comment_content, 0, 140 )) . "...";
                            //    echo  '<li class="recentcomments">' . /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('%2$s <cite>&ndash;%1$s</cite>', 'widgets'), get_comment_author_link() . " on " . $comment->post_title, '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $aRecentCommentTxt . '</a>') . '</li>';
                                  
                                  //comment link
                                  $output_commentpost = '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $comment->post_title . '</a>' ;

                                  echo  '<li class="recentcomments">' . /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('%2$s <br/>&ndash;%1$s', 'widgets'), $comment->comment_author . ", on " . $output_commentpost, $aRecentCommentTxt) . '</li>';
                        endforeach; endif;?></ul>
                <?php echo $after_widget; ?>
<?php
        }

        function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];
                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_recent_comments']) )
                        delete_option('widget_recent_comments');

                return $instance;
        }

        function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
                <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

                <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
                <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
                <small><?php _e('(at most 15)'); ?></small></p>
<?php
        }
}

function WP_Widget_Recent_Comments_Excerpts_Init() {
        register_widget('WP_Widget_Recent_Comments_Excerpts');
}
add_action('widgets_init', 'WP_Widget_Recent_Comments_Excerpts_Init');
?>