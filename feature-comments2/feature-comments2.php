<?php
/*
Plugin Name: Feature Comments 2
Plugin URI: http://wpprogrammer.com/feature-comments-wordpress-plugin/
Description: Lets the admin add "featured" or "buried" css class to selected comments. Handy to highlight comments that add value to your post.
Version: 1.1.1
Author: Utkarsh Kukreti
Author URI: http://utkar.sh

== Release Notes ==
2010-06-26 - v1.1.1 - Fixed bug, which showed feature/bury links to all users, instead of users with 'moderate_comments' capability.
2010-06-26 - v1.1 - Major update. Added support for featuring comments via ajax, both in the backend and frontend.
2010-04-16 - v1.0.3 - Fixed a bug introduced in the last update.
2010-04-12 - v1.0.2 - Refactored source code
2010-01-13 - v1.0.1 - Added missing screenshot files
2010-01-12 - v1.0 - First version.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
Online: http://www.gnu.org/licenses/gpl.txt
*/

FeatureComments2::init();
abstract class FeatureComments2
{
        static $actions = array('feature' => 'Feature', 'unfeature' => 'Unfeature');  
        /** Filters & Actions **/
        static function init()
        {
                /* Backend */
                //add_action('edit_comment', array('FeatureComments2', 'save_meta_box_postdata'));
                //                add_action('admin_menu', array('FeatureComments2', 'add_meta_box'));
                add_action('wp_ajax_feature_comments', array('FeatureComments2', 'ajax'));
                add_filter('edit_comment_link', array('FeatureComments2', 'comment_text'), 10, 3);
                //                add_filter('comment_row_actions', array('FeatureComments2', 'comment_row_actions'));

                add_action('wp_print_scripts', array('FeatureComments2', 'wp_print_scripts'));
                add_action('admin_print_scripts', array('FeatureComments2', 'wp_print_scripts'));
                add_action('wp_print_styles', array('FeatureComments2', 'wp_print_styles'));
                //add_action('admin_print_styles', array('FeatureComments2', 'wp_print_styles'));

                /* Frontend */
                add_filter('comment_class', array('FeatureComments2', 'comment_class'));

        }

        # Scripts
        static function wp_print_scripts()
        {
                if(current_user_can('moderate_comments'))
                {
                        wp_enqueue_script( 'feature_comments', plugin_dir_url( __FILE__ ) . 'feature-comments.js', array( 'jquery' ) );
                        wp_localize_script( 'feature_comments', 'feature_comments', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
                }
        }

        # Styles
        static function wp_print_styles()
        {
                if(current_user_can('moderate_comments'))
                {
                        ?>
                                <style>
                                        .feature-comments.unfeature, .feature-comments.unbury {display:none;}
                                        .featured.feature-comments.feature { display:none;}
                                        .featured.feature-comments.unfeature { display:inline;}
                                        .buried.feature-comments.bury { display:none;}
                                        .buried.feature-comments.unbury { display:inline;}

                                        #the-comment-list tr.featured { background-color: #dfd; }
                                        #the-comment-list tr.buried { opacity: 0.5; }
                                </style>
                        <?php
                }
        }

        static function ajax()
        {

                if( !isset($_POST['do']) ) die;

                $action = $_POST['do'];
                $actions = array_keys(self::$actions);

                if( in_array($action, $actions) )
                {
                        $comment_id = absint( $_POST['comment_id'] );

                        if ( !$comment = get_comment( $comment_id ) || !current_user_can('edit_post', $comment->comment_post_ID))
                                die;

                        switch($action)
                        {
                                case 'feature':
                                        update_metadata('comment', $comment_id, 'featured', '1');
                                break;

                                case 'unfeature':
                                        update_metadata('comment', $comment_id, 'featured', '0');
                                break;

                                case 'bury':
                                        update_metadata('comment', $comment_id, 'buried', '1');
                                break;

                                case 'unbury':
                                        update_metadata('comment', $comment_id, 'buried', '0');
                                break;
                        }
                }
                die;
        }

        static function comment_text($comment_text)
        {
                if(is_admin() || !current_user_can('moderate_comments')) return $comment_text;
                global $comment;
                $comment_id = $comment->comment_ID;
                $data_id = ' data-comment_id=' . $comment_id;

                $current_status = implode(' ', self::comment_class());

                $o = ' | ';
                foreach(self::$actions as $action => $label)
                        $o .= "<a class='feature-comments {$current_status} {$action}' data-do='{$action}' {$data_id} title='{$label} this comment'>{$label}</a> ";
                return $comment_text . $o;
        }

        static function comment_row_actions($actions)
        {
                global $comment, $post, $approve_nonce;
                $comment_id = $comment->comment_ID;
                $data_id = ' data-comment_id=' . $comment_id;

                $current_status = implode(' ', self::comment_class());
                $o = '';
                $o .= "<a data-do='unfeature' {$data_id} class='feature-comments unfeature {$current_status} dim:the-comment-list:comment-{$comment->comment_ID}:unfeatured:e7e7d3:e7e7d3:new=unfeatured vim-u' title='" . esc_attr__( 'Unfeature this comment' ) . "'>" . __( 'Unfeature' ) . '</a>';
                $o .= "<a data-do='feature' {$data_id} class='feature-comments feature {$current_status} dim:the-comment-list:comment-{$comment->comment_ID}:unfeatured:e7e7d3:e7e7d3:new=featured vim-a' title='" . esc_attr__( 'Feature this comment' ) . "'>" . __( 'Feature' ) . '</a>';
                $o .= ' | ';
                $o .= "<a data-do='unbury' {$data_id} class='feature-comments unbury {$current_status} dim:the-comment-list:comment-{$comment->comment_ID}:unburied:e7e7d3:e7e7d3:new=unburied vim-u' title='" . esc_attr__( 'Unbury this comment' ) . "'>" . __( 'Unbury' ) . '</a>';
                $o .= "<a data-do='bury' {$data_id}  class='feature-comments bury {$current_status} dim:the-comment-list:comment-{$comment->comment_ID}:unburied:e7e7d3:e7e7d3:new=buried vim-a' title='" . esc_attr__( 'Bury this comment' ) . "'>" . __( 'Bury' ) . '</a>';
                $o = "<span class='$current_status'>$o</span>";

                $actions['feature_comments'] = $o;
                return $actions;
        }

        static function add_meta_box()
        {
                add_meta_box('comment_meta_box', __( 'Feature Comments'), array('FeatureComments2', 'comment_meta_box'), 'comment', 'normal');
        }

        static function save_meta_box_postdata($comment_id)
        {
                if(!wp_verify_nonce( $_POST['nonce'], plugin_basename(__FILE__)))
                        return;
                if ( !current_user_can('moderate_comments', $comment_id) )
                        comment_footer_die( __('You are not allowed to edit comments on this post.') );
                update_metadata('comment', $comment_id, 'featured', isset($_POST['featured']) ? '1' : '0');
                update_metadata('comment', $comment_id, 'buried', isset($_POST['buried']) ? '1' : '0');
        }

        static function comment_meta_box()
        {
                global $comment;
                $comment_id = $comment->comment_ID;
                echo '<p>';
                echo '<input type="hidden" name="nonce" id="nonce" value="' .
                wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
                echo '<input id = "featured" type="checkbox" name="featured" value="true" ' . (self::is_comment_featured($comment_id)? 'checked' : '') . ' />';
                echo ' <label for="featured">' . __("Featured") . '</label>';
                echo '<br/>';
                echo '<input id = "buried" type="checkbox" name="buried" value="true" ' . (self::is_comment_buried($comment_id)? 'checked' : '') . ' />';
                echo ' <label for="buried">' . __("Buried") . '</label> ';
                echo '</p>';
        }

        static function comment_class($classes = array())
        {
                global $comment;
                $comment_id = $comment->comment_ID;

                if(self::is_comment_featured($comment_id))
                        $classes [] = 'featured';

                if(self::is_comment_buried($comment_id))
                        $classes [] = 'buried';

                return $classes;
        }

        # Private
                private static function is_comment_featured($comment_id)
                {
                        if('1' == get_metadata('comment', $comment_id, 'featured', true))
                                return 1;
                        return 0;
                }

                static function is_comment_buried($comment_id)
                {
                        if('1' == get_metadata('comment', $comment_id, 'buried', true))
                                return 1;
                        return 0;
                }
}
?>