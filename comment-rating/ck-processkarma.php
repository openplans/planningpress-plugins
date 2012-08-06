<?php
        /*
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

require_once('../../../wp-config.php');
require_once('../../../wp-includes/functions.php');

// CSRF attack protection. Check the Referal field to be the same
// domain of the script

$k_id = strip_tags($wpdb->escape($_GET['id']));
$k_action = strip_tags($wpdb->escape($_GET['action']));
$k_path = strip_tags($wpdb->escape($_GET['path']));
$k_imgIndex = strip_tags($wpdb->escape($_GET['imgIndex']));
if (isset( $_COOKIE['user_profile_url']))
  $k_user_profile_url = $_COOKIE['user_profile_url'];
else
  $k_user_profile_url = '';

// prevent SQL injection
if (!is_numeric($k_id)) die('error|Query error');

$table_name = $wpdb->prefix . 'comment_rating';
$comment_table_name = $wpdb->prefix . 'comments';
$ip = getenv("HTTP_X_FORWARDED_FOR") ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");

if($k_id && $k_action && $k_path) {
    //Check to see if the comment id exists and grab the rating
    $query = "SELECT * FROM `$table_name` WHERE ck_comment_id = $k_id";
    $result = mysql_query($query);

    if(!$result) { die('error|mysql: '.mysql_error()); }

   if(mysql_num_rows($result))
        {
      $duplicated = 0;  // used as a counter to off set duplicated votes
      $row = @mysql_fetch_assoc($result);
      $total = $row['ck_rating_up'] - $row['ck_rating_down'];
      if($k_action == 'add') {
         $rating = $row['ck_rating_up'] + 1 - $duplicated;
         $direction = 'up';
         $out_direction = 'up';
         $total = $total + 1 - $duplicated;
      }
      elseif($k_action == 'subtract')
      {
         $rating = $row['ck_rating_up'] - 1 - $duplicated;
         $direction = 'up';
         $out_direction = 'down';
         $total = $total - 1 - $duplicated;
      } else {
            die('error|Try again later'); //No action given.
      }

      if (!$duplicated)
      {
         $query = "UPDATE `$table_name` SET ck_rating_$direction = '$rating' WHERE ck_comment_id = $k_id";
         $result = mysql_query($query);
         if(!$result)
         {
            die('error|query '.$query);
            //die('error|Query error');
         }
         if($k_action=='add')
           $voterecord = '1';
         else
           $voterecord = '-1';
         $table_name = $table_prefix . "comment_rating_votes";
         $query = "INSERT `$table_name` (ck_comment_id, ck_ip, vote, user_profile_url) VALUES ($k_id, '$ip', $voterecord, '$k_user_profile_url');";
         $result = mysql_query($query);
         if(!$result)
         {
            die('error|query '.$query);
            //die('error|Query error');
         }

         // Now duplicated votes will not
         if(!mysql_affected_rows())
         {
            die('error|affected '. $rating);
         }

         $karma_modified = 0;
         if (get_option('ckrating_karma_type') == 'likes' && $k_action == 'add') {
            $karma_modified = 1; $karma = $rating;
         }
         if (get_option('ckrating_karma_type') == 'dislikes' && $k_action == 'subtract') {
            $karma_modified = 1; $karma = $rating;
         }
         if (get_option('ckrating_karma_type') == 'both') {
            $karma_modified = 1; $karma = $total;
         }

         if ($karma_modified) {
            $query = "UPDATE `$comment_table_name` SET comment_karma = '$karma' WHERE comment_ID = $k_id";
            $result = mysql_query($query);
            if(!$result) die('error|Comment Query error');
         }
      }
   } else {
        die('error|Comment doesnt exist'); //Comment id not found in db, something wrong ?
   }
} else {
    die('error|Fatal: html format error');
}

// Add the + sign,
if ($total > 0) { $total = "+$total"; }

// set cookie for fraud control.
// Cookie is just a list of comment ID separated by a space.
   $expireTime = 1600000000;

if (isset( $_COOKIE['Comment_Rating'])) {
  if($k_action == 'add') {
    $newvalue = $_COOKIE['Comment_Rating'] . ",$k_id";
  } else {
    $values = explode(',',$_COOKIE['Comment_Rating']);
    foreach($values as $key => $value) {
      if ($value == $k_id) unset($values[$key]);
    }
    $newvalue = implode(',', $values);
  }
  setcookie("Comment_Rating", $newvalue, $expireTime, "/");
} else {
  setcookie("Comment_Rating", "$k_id", $expireTime, "/");
}

//This sends the data back to the js to process and show on the page
// The dummy field will separate out any potential garbage that
// WP-superCache may attached to the end of the return.
echo("done|$k_id|$rating|$k_path|$out_direction|$rating|$k_imgIndex|dummy");
?>
