<?php
        /*
        Plugin Name: Comment Rating
        Plugin URI: http://wealthynetizen.com/wordpress-plugin-comment-rating/
        Description: Allows visitors to rate comments in a Like vs.  Dislike fashion with clickable images. Poorly-rated & highly-rated comments can be displayed differently. This plugin is simple and light-weight.  Configure it at <a href="options-general.php?page=ckrating">Settings &rarr; Comment Rating</a>.
        Author: Bob King
        Author URI: http://wealthynetizen.com
        Version: 2.9.28
        */

        /*
   Copyright 2009, Bob King, http://wealthynetizen.com

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
        */

load_plugin_textdomain('ckrating', "/wp-content/plugins/comment-rating/");

define('COMMENTRATING_VERSION', '2.9.28');
define('COMMENTRATING_PATH', WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)) );
define('COMMENTRATING_NAME', plugin_basename(dirname(__FILE__)) );


//add_action('comment_post', 'ckrating_comment_posted');//Hook into WordPress
add_action('admin_menu', 'ckrating_options_page');
add_action('wp_head', 'ckrating_add_highlight_style');
// late enough to avoid most conflicts, early enough to avoid conflicting
// with WP Threaded Comment
add_filter('comment_text', 'ckrating_display_filter', 9000);
add_filter('comment_class', 'ckrating_comment_class', 10 , 4 );
add_action('init', 'ckrating_add_javascript');  // add javascript in the footer


        global $table_prefix, $wpdb;
   // caching database query per comment
   $ck_cache = array('ck_ip'=>"", 'ck_comment_id'=>0, 'ck_rating_up'=>0, 'ck_rating_down'=>0);

        $table_name = $table_prefix . "comment_rating";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
        {
                ckrating_install();
        }
   // Use the last new option added.  Reset all option to defaults
   // for all upgrades.
   if (!get_option('ckrating_style_comment_box')) ckrating_reset_default();

function ckrating_options_page(){
   add_options_page('Comment Rating Options', 'Comment Rating', 8, 'ckrating', 'ckrating_show_options_page');
}

function ckrating_show_options_page() {
        global $table_prefix, $wpdb;
   if ($_POST[ 'ckrating_hidden' ] == 'Y') {
      if (isset($_POST['Reset'])) {
         ckrating_reset_default();
                   echo '<div id="message" class="updated fade"><p><strong>Comment Rating Options are set to default.</strong></p></div>';
      }
      else {
         update_option('ckrating_auto_insert', $_POST['ckrating_auto_insert']);
         update_option('ckrating_inline_style_off', $_POST['ckrating_inline_style_off']);
         update_option('ckrating_javascript_off', $_POST['ckrating_javascript_off']);
         update_option('ckrating_position', $_POST['ckrating_position']);
         update_option('ckrating_words', urldecode($_POST['ckrating_words']));
         update_option('ckrating_words_good', urldecode($_POST['ckrating_words_good']));
         update_option('ckrating_words_poor', urldecode($_POST['ckrating_words_poor']));
         update_option('ckrating_words_debated', urldecode($_POST['ckrating_words_debated']));
         update_option('ckrating_goodRate', $_POST['ckrating_goodRate']);
         update_option('ckrating_styleComment', urldecode($_POST['ckrating_styleComment']));
         update_option('ckrating_negative', $_POST['ckrating_negative']);
         update_option('ckrating_hide_style', urldecode($_POST['ckrating_hide_style']));
         update_option('ckrating_admin_off', $_POST['ckrating_admin_off']);
         update_option('ckrating_style_comment_box', $_POST['ckrating_style_comment_box']);
         update_option('ckrating_value_display', $_POST['ckrating_value_display']);
         update_option('ckrating_likes_style', urldecode($_POST['ckrating_likes_style']));
         update_option('ckrating_dislikes_style', urldecode($_POST['ckrating_dislikes_style']));
         update_option('ckrating_image_index', $_POST['ckrating_image_index']);
         update_option('ckrating_image_size', $_POST['ckrating_image_size']);
//EP-12-31-2009 Added options for ToolTip text
         update_option('ckrating_up_alt_text', $_POST['ckrating_up_alt_text']);
         update_option('ckrating_down_alt_text', $_POST['ckrating_down_alt_text']);
//EP-12-31-2009 End of added options
         update_option('ckrating_style_debated', urldecode($_POST['ckrating_style_debated']));
         update_option('ckrating_debated', $_POST['ckrating_debated']);
         update_option('ckrating_mouseover', $_POST['ckrating_mouseover']);
         update_option('ckrating_vote_type', $_POST['ckrating_vote_type']);

         // Update comment_karma if the karma_type changes.
         if (get_option('ckrating_karma_type') != $_POST['ckrating_karma_type']) {
            update_option('ckrating_karma_type', $_POST['ckrating_karma_type']);
            $ck_result = mysql_query('SELECT ck_comment_id, ck_rating_up, ck_rating_down FROM ' . $table_prefix . 'comment_rating');
            $comment_table_name = $table_prefix . 'comments';
            if(!$ck_result) { mysql_error(); }

            while($ck_row = mysql_fetch_array($ck_result, MYSQL_ASSOC)) //Wee loop
            {
               if (get_option('ckrating_karma_type') == 'likes') { $karma = $ck_row['ck_rating_up']; }
               else if (get_option('ckrating_karma_type') == 'dislikes') { $karma = $ck_row['ck_rating_down']; }
               else { $karma = $ck_row['ck_rating_up'] - $ck_row['ck_rating_down']; }
               $query = "UPDATE `$comment_table_name` SET comment_karma = '$karma' WHERE comment_ID = '" .  $ck_row['ck_comment_id'] . "'";
               $result = mysql_query($query);
            }
         }

         echo '<div id="message" class="updated fade"><p><strong>Comment Rating Options updated.</strong></p></div>';
      }
   }
?>
   <div class="wrap">
   <div id="icon-options-general" class="icon32">
   <br/>
   </div>
   <h2>Comment Rating Options (Version: <?php print(COMMENTRATING_VERSION);?>)</h2>
<?php
   if (0 == get_option('ckrating_show_thankyou') % 4)
      print('
         <div style="width: 75%; background-color: yellow;">
         <em><b> Thank you for choosing Comment Rating.  If you like the
         plugin, please help promoting its use. You can rate it at
         <a href="http://wordpress.org/extend/plugins/comment-rating/">WordPress.org Plugins</a>.
         </b>
         </em>
         </div>
         ');
   update_option('ckrating_show_thankyou', get_option('ckrating_show_thankyou')+1);

        include(COMMENTRATING_PATH.'/comment-rating-options.php');
}

// set the default values to options
function ckrating_reset_default() {
   update_option('ckrating_auto_insert', 'yes');
   update_option('ckrating_inline_style_off', 'no');
   update_option('ckrating_javascript_off', 'no');
   update_option('ckrating_position', 'below');
   update_option('ckrating_words', 'Like or Dislike:');
   update_option('ckrating_words_good', 'Well-loved. Like or Dislike:');
   update_option('ckrating_words_poor', 'Poorly-rated. Like or Dislike:');
   update_option('ckrating_words_debated', 'Hot debate. What do you think?');
   update_option('ckrating_negative', 3);
   update_option('ckrating_goodRate', 4);
   update_option('ckrating_debated', 8);
   update_option('ckrating_styleComment', 'background-color:#FFFFCC !important');
   update_option('ckrating_hide_style', 'opacity:0.6;filter:alpha(opacity=60) !important');
   update_option('ckrating_style_debated', 'background-color:#FFF0F5 !important');
   update_option('ckrating_admin_off', 'no');
   update_option('ckrating_style_comment_box', 'yes');
   update_option('ckrating_value_display', 'two');
   update_option('ckrating_likes_style', 'font-size:12px; color:#009933');
   update_option('ckrating_dislikes_style', 'font-size:12px; color:#990033');
   update_option('ckrating_image_index', 1);
   update_option('ckrating_image_size', 14);
//EP-12-31-2009 Added options for ToolTip text.  Note, to BoB, should all the default strings be localized?
   update_option('ckrating_up_alt_text', __('Thumb up', 'ckrating'));
   update_option('ckrating_down_alt_text', __('Thumb down', 'ckrating'));
//EP-12-31-2009 End of added options
   update_option('ckrating_mouseover', 2);
   update_option('ckrating_vote_type', 'both');
   update_option('ckrating_karma_type', 'both');
}

function ckrating_install() //Install the needed SQl entries.
{
   global $table_prefix, $wpdb;

   $table_name = $table_prefix . "comment_rating";

   $sql = 'DROP TABLE `' . $table_name . '`';  // drop the existing table
   mysql_query($sql);
   $sql = 'CREATE TABLE `' . $table_name . '` (' //Add table
      . ' `ck_comment_id` BIGINT(20) NOT NULL, '
      . ' `ck_ip` varchar(30), '
      . ' `ck_rating_up` INT,'
      . ' `ck_rating_down` INT'
      . ' )'
      . ' ENGINE = myisam;';
   mysql_query($sql);
   $sql = 'ALTER TABLE `' . $table_name . '` ADD INDEX (`ck_comment_id`);';  // add index
   mysql_query($sql);

   echo "comment_rating tables created";

   $table_name = $table_prefix . "comment_rating_votes";

   $sql = 'DROP TABLE `' . $table_name . '`';  // drop the existing table
   mysql_query($sql);
   $sql = 'CREATE TABLE `' . $table_name . '` (' //Add table
      . ' `id` BIGINT(20) unsigned NOT NULL AUTO_INCREMENT, '
      . ' `ck_comment_id` BIGINT(20) NOT NULL, '
      . ' `ck_ip` varchar(30), '
      . ' `vote` INT,'
      . ' `user_profile_url` varchar(200),'
      . ' `posted_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,'
      . ' PRIMARY KEY (`id`) )'
      . ' ENGINE = myisam;';

   mysql_query($sql);
   $sql = 'ALTER TABLE `' . $table_name . '` ADD INDEX (`ck_comment_id`);';  // add index
   mysql_query($sql);

   echo "comment_rating_votes tables created";


   $ck_result = mysql_query('SELECT id FROM bikeshare_points'); //Put all IDs in our new table
   while($ck_row = mysql_fetch_array($ck_result, MYSQL_ASSOC)) //Wee loop
   {
      mysql_query("INSERT INTO $table_name (ck_comment_id, ck_ip, ck_rating_up, ck_rating_down) VALUES ('" . $ck_row['id'] . "', '', 0, 0)");
   }
}

function ckrating_comment_posted($ck_comment_id) //When comment posted this executes
{
   global $table_prefix, $wpdb;
   $ip = getenv("HTTP_X_FORWARDED_FOR") ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");
   $table_name = $table_prefix . "comment_rating";
   mysql_query("INSERT INTO $table_name (ck_comment_id, ck_ip, ck_rating_up, ck_rating_down) VALUES ('" . $ck_comment_id . "', '" . $ip . "', 1, 0)"); //Adds the new comment ID into our made table, with the users IP


// set cookie for fraud control.
// Cookie is just a list of comment ID separated by a space.
   $expireTime = 1600000000;

   if (isset($_COOKIE['Comment_Rating'])) {
      if (isset( $_COOKIE['Comment_Rating'])) {
         $value = $_COOKIE['Comment_Rating'] . ",$ck_comment_id";
         setcookie("Comment_Rating", $value, $expireTime, "/");
      }
      else
         setcookie("Comment_Rating", "$ck_comment_id", $expireTime, "/");
   }
   else
      setcookie("Comment_Rating", "$ck_comment_id", $expireTime, "/");

}

// cache DB results to prevent multiple access to DB
function ckrating_get_rating($comment_id)
{
   global $ck_cache, $table_prefix, $wpdb;

   // return it if the value is in the cache
   if ($comment_id == $ck_cache['ck_comment_id']) return;

   $table_name = $table_prefix . "comment_rating";
   $ck_sql = "SELECT ck_ip, ck_rating_up, ck_rating_down FROM `$table_name` WHERE ck_comment_id = $comment_id";
   $ck_result = mysql_query($ck_sql);

   $ck_cache['ck_comment_id'] = $comment_id;
   if(!$ck_result) {
      $ck_cache['ck_ip'] = '';
      $ck_cache['ck_rating_up'] = 0;
      $ck_cache['ck_rating_down'] = 0;
      mysql_query("INSERT INTO $table_name (ck_comment_id, ck_ip, ck_rating_up, ck_rating_down) VALUES ('" . $comment_id . "', '', 0, 0)");
   }
   else if(!$ck_row = mysql_fetch_array($ck_result, MYSQL_ASSOC)) {
      $ck_cache['ck_ip'] = '';
      $ck_cache['ck_rating_up'] = 0;
      $ck_cache['ck_rating_down'] = 0;
      mysql_query("INSERT INTO $table_name (ck_comment_id, ck_ip, ck_rating_up, ck_rating_down) VALUES ('" . $comment_id . "', '', 0, 0)");
   }
   else {
      $ck_cache['ck_ip'] = $ck_row['ck_ip'];
      $ck_cache['ck_rating_up'] = $ck_row['ck_rating_up'];
      $ck_cache['ck_rating_down'] = $ck_row['ck_rating_down'];
   }
}

// Display images and rating in comments
function ckrating_display_content()
{
   global $ck_cache;
   $plugin_path = get_bloginfo('wpurl').'/wp-content/plugins/comment-rating';
   $ck_link = str_replace('http://', '', get_bloginfo('wpurl'));
   $ck_comment_ID = get_comment_ID();
   $content = '';
   ckrating_get_rating($ck_comment_ID);

   $imgIndex = get_option('ckrating_image_index') . '_' . get_option('ckrating_image_size') . '_';
   $votedInCookie = false;
   if (isset( $_COOKIE['Comment_Rating'])) {
      $value = $_COOKIE['Comment_Rating'];
      $cookieIDs = split(',', $value);
      $votedInCookie = in_array($ck_comment_ID, $cookieIDs);
   }
   if($votedInCookie) {
      $imgUp = $imgIndex . "gray_up.png";
      $imgDown = $imgIndex . "gray_down.png";
      $imgStyle = 'style="padding: 0px; margin: 0px; border: none;"';
      $onclick_add = '';
      $onclick_sub = '';
   }
   else {
      $imgUp = $imgIndex . "up.png";
      $imgDown = $imgIndex . "down.png";
      if (get_option('ckrating_mouseover') == 1)
         // no effect
         $imgStyle = 'style="padding: 0px; border: none; cursor: pointer;"';
      else
         // enlarge
         $imgStyle = 'style="padding: 0px; border: none; cursor: pointer;" onmouseover="this.width=this.width*1.3" onmouseout="this.width=this.width/1.2"';
//      $onclick_add = "onclick=\"javascript:ckratingKarma('$ck_comment_ID', 'add', '{$ck_link}/wp-content/plugins/comment-rating/', '$imgIndex');\" title=\"". __('Thumb up','ckrating'). "\"";
//      $onclick_sub = "onclick=\"javascript:ckratingKarma('$ck_comment_ID', 'subtract', '{$ck_link}/wp-content/plugins/comment-rating/', '$imgIndex')\" title=\"". __('Thumb down', 'ckrating') ."\"";
//EP-12-31-2009 Replaced two lines above with line below for Tooltip Text option.  I think __() is the localization. We shouldn't need that for these strings now.
      $onclick_add = "onclick=\"javascript:ckratingKarma('$ck_comment_ID', 'add', '{$ck_link}/wp-content/plugins/comment-rating/', '$imgIndex');\" title=\"". get_option('ckrating_up_alt_text')."\"";
      $onclick_sub = "onclick=\"javascript:ckratingKarma('$ck_comment_ID', 'subtract', '{$ck_link}/wp-content/plugins/comment-rating/', '$imgIndex')\" title=\"".get_option('ckrating_down_alt_text')."\"";
   }

   $total = $ck_cache['ck_rating_up'] - $ck_cache['ck_rating_down'];
   if ($total > 0) $total = "+$total";
   //Use onClick for the image instead, fixes the style link underline problem as well.
   if ( ((int)$ck_cache['ck_rating_up'] - (int)$ck_cache['ck_rating_down'])
           >= (int)get_option('ckrating_goodRate')) {
      $content .= get_option('ckrating_words_good');
   }
   else if ( ((int)$ck_cache['ck_rating_down'] - (int)$ck_cache['ck_rating_up'])
            >= (int)get_option('ckrating_negative')) {
      $content .= get_option('ckrating_words_poor');
   }
   else if ( ((int)$ck_cache['ck_rating_down'] + (int)$ck_cache['ck_rating_up'])
            >= (int)get_option('ckrating_debated')) {
      $content .= get_option('ckrating_words_debated');
   }
   else
      $content .= get_option('ckrating_words');

   $likesStyle = 'style="' . get_option('ckrating_likes_style') .  ';"';
   $dislikesStyle = 'style="' . get_option('ckrating_dislikes_style') .  ';"';
   // apply ckrating_vote_type
   if ( get_option('ckrating_vote_type') != 'dislikes' )
   {
      $content .= " <img $imgStyle id=\"up-$ck_comment_ID\" src=\"{$plugin_path}/images/$imgUp\" alt=\"".__('Thumb up', 'ckrating') ."\" $onclick_add />";
      if ( get_option('ckrating_value_display') != 'one' )
         $content .= " <span id=\"karma-{$ck_comment_ID}-up\" $likesStyle>{$ck_cache['ck_rating_up']}</span>";
   }
   if ( get_option('ckrating_vote_type') != 'likes' )
   {
      $content .= "&nbsp;<img $imgStyle id=\"down-$ck_comment_ID\" src=\"{$plugin_path}/images/$imgDown\" alt=\"". __('Thumb down', 'ckrating')."\" $onclick_sub />"; //Phew
      if ( get_option('ckrating_value_display') != 'one' )
         $content .= " <span id=\"karma-{$ck_comment_ID}-down\" $dislikesStyle>{$ck_cache['ck_rating_down']}</span>";
   }

   $totalStyle = '';
   if ($total > 0) $totalStyle = $likesStyle;
   else if ($total < 0) $totalStyle = $dislikesStyle;
   if ( get_option('ckrating_value_display') == 'one' )
      $content .= " <span id=\"karma-{$ck_comment_ID}-total\" $totalStyle>{$total}</span>";
   if ( get_option('ckrating_value_display') == 'three' )
      $content .= " (<span id=\"karma-{$ck_comment_ID}-total\" $totalStyle>{$total}</span>)";

   return array($content, $ck_cache['ck_rating_up'], $ck_cache['ck_rating_down']);
}

// Display images and rating for widget on sidebar
function ckrating_display_sidebar($ck_comment_ID)
{
   global $ck_cache;
   $plugin_path = get_bloginfo('wpurl').'/wp-content/plugins/comment-rating';
   $ck_link = str_replace('http://', '', get_bloginfo('wpurl'));
   $content = '';
   ckrating_get_rating($ck_comment_ID);

   $imgIndex = get_option('ckrating_image_index') . '_' . get_option('ckrating_image_size') . '_';
   $imgUp = $imgIndex . "up.png";
   $imgDown = $imgIndex . "down.png";
   $imgStyle = 'style="padding: 0px; border: none;"';
   $onclick_add = '';
   $onclick_sub = '';

   $total = $ck_cache['ck_rating_up'] - $ck_cache['ck_rating_down'];
   if ($total > 0) $total = "+$total";
   //Use onClick for the image instead, fixes the style link underline problem as well.

   $likesStyle = 'style="' . get_option('ckrating_likes_style') .  ';"';
   $dislikesStyle = 'style="' . get_option('ckrating_dislikes_style') .  ';"';
   // Use ckrating_karma_type to determine the image shape
   if ( get_option('ckrating_karma_type') != 'dislikes' )
   {
      $content .= "&nbsp;<img $imgStyle src=\"{$plugin_path}/images/$imgUp\" alt=\"".__('Thumb up', 'ckrating') ."\" $onclick_add />";
      if ( get_option('ckrating_value_display') != 'one' )
         $content .= "&nbsp;<span $likesStyle>{$ck_cache['ck_rating_up']}</span>";
   }
   if ( get_option('ckrating_karma_type') != 'likes' )
   {
      $content .= "&nbsp;<img $imgStyle src=\"{$plugin_path}/images/$imgDown\" alt=\"". __('Thumb down', 'ckrating')."\" $onclick_sub />"; //Phew
      if ( get_option('ckrating_value_display') != 'one' )
         $content .= "&nbsp;<span $dislikesStyle>{$ck_cache['ck_rating_down']}</span>";
   }

   $totalStyle = '';
   if ($total > 0) $totalStyle = $likesStyle;
   else if ($total < 0) $totalStyle = $dislikesStyle;
   if ( get_option('ckrating_value_display') == 'one' )
      $content .= "&nbsp;<span id=\"karma-{$ck_comment_ID}-total\" $totalStyle>{$total}</span>";
   if ( get_option('ckrating_value_display') == 'three' )
      $content .= "&nbsp;(<span id=\"karma-{$ck_comment_ID}-total\" $totalStyle>{$total}</span>)";

   return $content;
}

function ckrating_display_filter($text)
{
   $ck_comment_ID = get_comment_ID();
   $ck_comment = get_comment($ck_comment_ID);
   $ck_comment_author = $ck_comment->comment_author;
   $ck_author_name = get_the_author();

   if (get_option('ckrating_admin_off') == 'yes' &&
       ($ck_author_name == $ck_comment_author || $ck_comment_author == 'admin')
      )
      return $text;

   $arr = ckrating_display_content();

   // $content is the modifed comment text.
   $content = $text;

   if (((int)$arr[1] - (int)$arr[2]) >= (int)get_option('ckrating_goodRate')) {
      $content = '<div style="' . get_option('ckrating_styleComment') . '">' .
               $text .  '</div>';
   }
   else if ( ((int)$arr[2] - (int)$arr[1])>= (int)get_option('ckrating_negative') &&
             ! ($ck_author_name == $ck_comment_author || $ck_comment_author == 'admin')
           )
   {
      $content = '<p>'.__('Hidden due to','ckrating').' '.__('low','ckrating');
      if ( (get_option('ckrating_inline_style_off') == 'yes') &&
           (get_option('ckrating_javascript_off') == 'yes')) {
         $content .= ' '. __('comment rating','ckrating');
      }
      else {
         $content .= ' <a href="http://wealthynetizen.com/wordpress-plugin-comment-rating/" title="'
               .__('Rated by other readers', 'ckrating').'">'
               .__('comment rating','ckrating').'</a>.';
      }
      $content .= " <a href=\"javascript:crSwitchDisplay('ckhide-$ck_comment_ID');\" title=\"".__('Click to see comment','ckrating')."\">".__('Click here to see', 'ckrating')."</a>.</p>" .
              "<div id='ckhide-$ck_comment_ID' style=\"display:none; ".get_option('ckrating_hide_style').';">' .
              $text .
              "</div>";
   }
   else if (((int)$arr[1] + (int)$arr[2]) >= (int)get_option('ckrating_debated')) {
      $content = '<div style="' . get_option('ckrating_style_debated') . '">' .
               $text .  '</div>';
   }

   // No auto insertion of images and ratings
   if (get_option('ckrating_auto_insert') != 'yes')
      return $content;

   // Add the images and ratings
   if (get_option('ckrating_position') == 'below')
      return $content. '<p>' . $arr[0] . '</p>';
   else
      return '<p>' . $arr[0] . '</p>' . $content;
}

function ckrating_display_karma()
{
   $arr = ckrating_display_content();
   print $arr[0];
}

function ckrating_add_javascript() {
   if (get_option('ckrating_javascript_off') == 'yes') return;

   wp_enqueue_script('comment-rating', plugins_url('comment-rating/ck-karma.js'), array(), false, true);
}

function ckrating_add_highlight_style() {
   if (get_option('ckrating_inline_style_off') == 'yes') return;

   echo '
<!-- Comment Rating plugin Version: '.COMMENTRATING_VERSION. ' by Bob King, http://wealthynetizen.com/, dynamic comment voting & styling. -->
<style type="text/css" media="screen">
   .ckrating_highly_rated {'. get_option('ckrating_styleComment') . ';}
   .ckrating_poorly_rated {'. get_option('ckrating_hide_style') . ';}
   .ckrating_hotly_debated {'. get_option('ckrating_style_debated') . ';}
</style>

';
}

function ckrating_comment_class (  $classes, $class, $comment_id, $page_id){
   // Don't style the comment box
   if (get_option('ckrating_style_comment_box') == 'no') return $classes;

   global $ck_cache;
   //get the comment object, in case $comment_id is not passed.
   $ck_comment_ID = get_comment_ID();
   ckrating_get_rating($ck_comment_ID);

   if ( ((int)$ck_cache['ck_rating_up'] - (int)$ck_cache['ck_rating_down'])
              >= (int)get_option('ckrating_goodRate')) {
      //add comment highlighting class
      $classes[] = "ckrating_highly_rated";
   }
   else if ( ((int)$ck_cache['ck_rating_down'] - (int)$ck_cache['ck_rating_up'])
            >= (int)get_option('ckrating_negative')) {
      //add hiding comment class
      $classes[] = "ckrating_poorly_rated";
   }
   else if ( ((int)$ck_cache['ck_rating_down'] + (int)$ck_cache['ck_rating_up'])
            >= (int)get_option('ckrating_debated')) {
      $classes[] = "ckrating_hotly_debated";
   }

   //send the array back
   return $classes;
}
?>
