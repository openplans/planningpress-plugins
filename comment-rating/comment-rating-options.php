<?php
/*
Author: Bob King
Author URI: http://WealthyNetizen.com/
License: Copyright 2009 Bob King.  http://WealthyNetizen.com/

    The program is distributed under the terms of the GNU General
    Public License GPLv3.

    This file is part of Comment Rating Wordpress plugin

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
   <p>
   The Pro version of Comment Rating allows voting by logged-in users only, fraud
   detection with user ID, IP, <br/>
   Cookie or off entirely, styling with
   CSS, controling inserting rating images to Posts only, Pages only or both, <br/>
   or on selected post IDs. etc. <a href="http://wealthynetizen.com/comment-rating-pro/">Comment Rating Pro</a>. <br/><br/>
   Comment Rating also has a companion widget plugin <a href="http://wealthynetizen.com/comment-rating-widget/">Comment Rating Widget</a>. <br/>
   To sort comments by votes, hide poor comments entirely, or show images neatly, 
   <a href="http://wealthynetizen.com/donate/customize-your-theme/">ask me to help</a>. <br/>

   Questions or Problems: <a href="http://wealthynetizen.com/comment-rating-plugin-faq/">
   Comment Rating Plugin FAQ</a>. 
   Feature requests: <a href="http://wealthynetizen.com/comment-rating-plugin-todo-list/">Comment
   Rating Todo List</a>.
   </p>

   <form id="ckrating_option" name="ckrating_option" action="options-general.php?page=ckrating" method="post">

   <table style="margin-bottom:5px">
   <tr>
   <th style="text-align:left;" colspan="2">
   </th>
   </tr>
   <tr>
   <td>
      Position the images above or below comments:
   </td>
   <td>
     <select name="ckrating_position" id="ckrating_position">
<?php
   if (get_option('ckrating_position') == 'below')
      print('<option selected="selected" value="below">Below</option>
             <option value="above">Above</option>');
   else
      print('<option value="below">Below</option>
             <option selected="selected" value="above">Above</option>');
?>
   </select>
   </td>
   </tr>
   <tr>
   <td>
       Words before the rating images (default):
   </td>
   <td>
      <input type="text" size="50" name="ckrating_words" value="<?php echo get_option('ckrating_words'); ?>">
   </td>
   </tr>
   <tr>
   <td>
       Turn off rating for comments by admin/author :
   </td>
   <td>
   <select name="ckrating_admin_off" id="ckrating_admin_off">
      <option value="yes" <?php if (get_option('ckrating_admin_off') == 'yes') print('selected="selected"');?> >Yes</option>
      <option value="no" <?php if (!(get_option('ckrating_admin_off') == 'yes')) print('selected="selected"');?>>No</option>
   </select>
   </td>
   </tr>
   <tr>
   <td>
       Style comment box: (When using nested comments,
       choose 'No' to avoid messy styling.)
   </td>
   <td>
   <select name="ckrating_style_comment_box" id="ckrating_style_comment_box">
      <option value="yes" <?php if (!(get_option('ckrating_style_comment_box') == 'no')) print('selected="selected"');?> >Yes</option>
      <option value="no" <?php if (get_option('ckrating_style_comment_box') == 'no') print('selected="selected"');?>>No</option>
   </select>
   </td>
   </tr>
   <tr>
   <td>
       Select allowed vote type (Likes, Dislikes or Both):
   </td>
   <td>
   <select name="ckrating_vote_type" id="ckrating_vote_type">
      <option value="likes" <?php if (!(get_option('ckrating_vote_type') == 'likes')) print('selected="selected"');?> >Likes Only</option>
      <option value="dislikes" <?php if (get_option('ckrating_vote_type') == 'dislikes') print('selected="selected"');?>>Dislikes Only</option>
      <option value="both" <?php if ( get_option('ckrating_vote_type') !== 'dislikes' &&
                                      get_option('ckrating_vote_type') !== 'likes' )
                                 print('selected="selected"');?>>Both</option>
   </select>
   </td>
   </tr>
   <tr>
   <td>
      Select the mouse-over image effect:
   </td>
   <td>
       <input type="radio" name="ckrating_mouseover" value="1"
          <?php if (get_option('ckrating_mouseover') == 1) echo 'checked';?> >
          No effect
       <input type="radio" name="ckrating_mouseover" value="2"
          <?php if (get_option('ckrating_mouseover') == 2) echo 'checked';?> >
          Enlarge 
   </td>
   </tr>
   <tr><td><br/></td> </tr>
   <tr>
   <td>
      Highly-rated comments have (Likes - Dislikes) >=
   </td>
   <td>
      <input type="text" size="2" name="ckrating_goodRate"
      value="<?php echo get_option('ckrating_goodRate'); ?>"> 
   </td>
   </tr>
   <tr>
   <td>
       Style highly-rated comments with:
   </td>
   <td>
      <input type="text" size="50" name="ckrating_styleComment"
      value="<?php echo get_option('ckrating_styleComment'); ?>">
   </td>
   </tr>
   <tr>
   <td>
       Words before the images for the highly-rated:
   </td>
   <td>
      <input type="text" size="50" name="ckrating_words_good" value="<?php echo get_option('ckrating_words_good'); ?>">
   </td>
   </tr>
   <tr><td><br/></td> </tr>
   <tr>
   <td>
      Poorly-rated comments have (Dislikes - Likes) >=
   </td>
   <td>
      <input type="text" size="2" name="ckrating_negative" value="<?php echo get_option('ckrating_negative'); ?>"> 
   </td>
   </tr>
   <tr>
   <td>
      Style poorly-rated comments as:
   </td>
   <td>
       <input type="text" size="50" name="ckrating_hide_style" value="<?php echo get_option('ckrating_hide_style') ?>">
   </td>
   </tr>
   <tr>
   <td>
       Words before the images for the poorly-rated:
   </td>
   <td>
      <input type="text" size="50" name="ckrating_words_poor" value="<?php echo get_option('ckrating_words_poor'); ?>">
   </td>
   </tr>
   <tr><td><br/></td> </tr>
   <tr>
   <td>
      Hotly-debated comments have (Likes + Dislikes) >=
   </td>
   <td>
      <input type="text" size="2" name="ckrating_debated"
      value="<?php echo get_option('ckrating_debated'); ?>"> 
   </td>
   </tr>
   <tr>
   <td>
       Style hotly-debated comments with:
   </td>
   <td>
      <input type="text" size="50" name="ckrating_style_debated"
      value="<?php echo get_option('ckrating_style_debated'); ?>">
   </td>
   </tr>
   <tr>
   <td>
       Words before the images for the hotly-debated:
   </td>
   <td>
      <input type="text" size="50" name="ckrating_words_debated" value="<?php echo get_option('ckrating_words_debated'); ?>">
   </td>
   </tr>
   <tr><td><br/></td> </tr>
   <tr>
   <td>
       Show two vote values, one combined or both:
   </td>
   <td>
   <select name="ckrating_value_display" id="ckrating_value_display">
<?php
   if (get_option('ckrating_value_display') == 'one')
      print('<option selected="selected" value="one">One</option>
            <option value="two">Two</option>
            <option value="three">Three</option>');
   else if (get_option('ckrating_value_display') == 'two')
      print('<option value="one">One</option>
            <option selected="selected" value="two">Two</option>
            <option value="three">Three</option>');
   else
      print('<option value="one">One</option>
            <option value="two">Two</option>
            <option selected="selected" value="three">Three</option>');
?>
   </select>
   </td>
   </tr>
   <tr>
   <td>
      Style the Likes number as:
   </td>
   <td>
       <input type="text" size="50" name="ckrating_likes_style" value="<?php echo get_option('ckrating_likes_style') ?>">
   </td>
   </tr>
   <tr>
   <td>
      Style the DisLikes number as:
   </td>
   <td>
       <input type="text" size="50" name="ckrating_dislikes_style" value="<?php echo get_option('ckrating_dislikes_style') ?>">
   </td>
   </tr>
   <tr>
   <td>
      Select the image style:
   </td>
   <td>
       <input type="radio" name="ckrating_image_index" value="1"
          <?php if (get_option('ckrating_image_index') == 1) echo 'checked';?> >
       <img src="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/comment-rating/images/1_16_up.png'; ?>" /><img src="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/comment-rating/images/1_16_down.png'; ?>" />
       <input type="radio" name="ckrating_image_index" value="2"
          <?php if (get_option('ckrating_image_index') == 2) echo 'checked';?> >
       <img src="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/comment-rating/images/2_16_up.png'; ?>" /><img src="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/comment-rating/images/2_16_down.png'; ?>" />
       <input type="radio" name="ckrating_image_index" value="3"
          <?php if (get_option('ckrating_image_index') == 3) echo 'checked';?> >
       <img src="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/comment-rating/images/3_16_up.png'; ?>" /><img src="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/comment-rating/images/3_16_down.png'; ?>" />
   </td>
   </tr>
   <tr>
   <td>
      Select the image size (in pixels):
   </td>
   <td>
      <select name="ckrating_image_size" id="ckrating_image_size">
         <option <?php if (get_option('ckrating_image_size') == 14) echo 'selected="selected"';?> value="14">14</option>
         <option <?php if (get_option('ckrating_image_size') == 16) echo 'selected="selected"';?> value="16">16</option>
         <option <?php if (get_option('ckrating_image_size') == 20) echo 'selected="selected"';?> value="20">20</option>
      </select>
   </td>
   </tr>
<!--Added by Eric Peterka, 12-31-2009, ToolTip Text options -->
   <tr>
   <td>
      Tooltip text for images:
   </td>
   <td>
      <img src="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/comment-rating/images/3_16_up.png'; ?>" />
      <input type="text" name="ckrating_up_alt_text" value="<?php echo get_option('ckrating_up_alt_text'); ?>">
      <br />
      <img src="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/comment-rating/images/3_16_down.png'; ?>" />
      <input type="text" name="ckrating_down_alt_text" value="<?php echo get_option('ckrating_down_alt_text'); ?>">
   </td>
   </tr>
<!--End of addition by Eric Peterka, 12-31-2009, ToolTip Text options -->
   <tr> <td> <br/></td> <td> <br/> </td> </tr>
   <tr> <td> <b>Advanced Options</b></td> 
   <td> You don't need to change the following unless you plan to customize your theme.</td>
   </tr>
   <tr>
   <td>
       Value for comment_karma (Likes, Dislikes or Both):
   </td>
   <td>
   <select name="ckrating_karma_type" id="ckrating_karma_type">
      <option value="likes" <?php if (!(get_option('ckrating_karma_type') == 'likes')) print('selected="selected"');?> >Likes Only</option>
      <option value="dislikes" <?php if (get_option('ckrating_karma_type') == 'dislikes') print('selected="selected"');?>>Dislikes Only</option>
      <option value="both" <?php if ( get_option('ckrating_karma_type') !== 'dislikes' &&
                                      get_option('ckrating_karma_type') !== 'likes' )
                                 print('selected="selected"');?>>Both</option>
   </select>
   </td>
   </tr>
   <tr>
   <td>
      Turn off auto-insert into comments:
   </td>
   <td>
      <select name="ckrating_auto_insert" id="ckrating_auto_insert">
<?php
   if (get_option('ckrating_auto_insert') == 'yes')
      print('<option value="no">Yes</option>
            <option selected="selected" value="yes">No</option>');
   else 
      print('<option selected="selected" value="no">Yes</option>
            <option value="yes">No</option>');
?>
   </select>
   </td>
   </tr>
   <tr>
   <td>
      Turn off inline style sheet:
   </td>
   <td>
      <select name="ckrating_inline_style_off" id="ckrating_inline_style_off">
         <option <?php if (get_option('ckrating_inline_style_off') == 'yes') echo 'selected="selected"';?> value="yes">Yes</option>
         <option <?php if (!(get_option('ckrating_inline_style_off') == 'yes')) echo 'selected="selected"';?> value="no">No</option>');
      </select>
   </td>
   </tr>
   <tr>
   <td>
      Turn off Javascript loading:
   </td>
   <td>
      <select name="ckrating_javascript_off" id="ckrating_javascript_off">
         <option <?php if (get_option('ckrating_javascript_off') == 'yes') echo 'selected="selected"';?> value="yes">Yes</option>
         <option <?php if (!(get_option('ckrating_javascript_off') == 'yes')) echo 'selected="selected"';?> value="no">No</option>');
      </select>
   </td>
   </tr>
   <tr> <td> <br/></td> <td> <br/> </td> </tr>
   <tr>
   <td>
   <input type="hidden" name="ckrating_hidden" value="Y">
   <input type="submit" class="button-primary" value="Update options" />
   </td>
   <td>
   <input type="submit" class="button-primary" name="Reset" value="Reset options to default" />
   <br/><b>If you see any blank value above,<br/>please reset everything to default first.</b>
   </td>
   </tr>
</table>
</form>
   <br/>
   <p> 
   <b>Localization:</b>
   All user readable output will be in localized language as per WPLANG defined in your wp-config.php.
   The option page will remain in English to minimize translation work.
   If your language is not available, please help out with the
   translation.  See the simple procedure <a href="http://wealthynetizen.com/comment-rating-plugin-faq/">here</a>. 
   </p>

   <p> 
   <b>Customizing Theme:</b>
   The Comment Rating plugin can automatically insert
   ratings and images into comments. You can also turn off
   auto-insertion and customize your theme in
   the "comments.php" file within the "Comments Loop" with the following line.
   <br/>
      if(function_exists(ckrating_display_karma)) { ckrating_display_karma(); }
   </p>
   <p>
   <b>Styling the Comment Box:</b>
   The comment styling uses the new
   comment_class filter (introduced in Wordpress 2.7). If your
   theme doesn't use Wordpress 2.7 wp_list_comments(),
   you'll only see the comment text background being styled/highlighted. To
   fix the problem, you need to add comment_class into your existing
   theme. For example code, please see <a
   href="http://brassblogs.com/blog/wordpress-27-and-comment-display">here</a>.
   </p>

<h2>News</h2> <ul style="list-style-type:disc;margin-left: 15px; margin-right:20px;">
<?php
   require_once(ABSPATH . WPINC . '/rss.php');
		
   $resp = _fetch_remote_file('http://WealthyNetizen.com/feed/');
   if ( is_success( $resp->status ) ) {
      $rss =  _response_to_rss( $resp );			
      $blog_posts = array_slice($rss->items, 0, 3);
      
      $posts_arr = array();
      foreach ($blog_posts as $item) {
         echo '<li><a href="'.$item['link'].'">'.$item['title'].'</a><br>'.$item['description'].'</li>';
      }
   } 
   print('</ul>');
?>
</div>
