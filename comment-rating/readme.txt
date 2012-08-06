=== Comment Rating ===
Contributors: bobking
Tags: comments, vote, poll, polls, image, images, performance, rating, ratings, comment, AJAX, javascript, automatic, button, plugin, plugins, Dislike, Like, embed, Formatting, user, users, visitors, text, counter, cms, highlight, digg, integration, thumb, tool, tools, style, youtube, community, star, any, google, buttons, seo, save, saving, highlight, highlighter, addtoany
Donate link: http://WealthyNetizen.com/donate/
Requires at least: 2.3
Tested up to: 3.1.3 
Stable tag: 2.9.28

Allows visitors to rate comments in Like vs. Dislike fashion with
clickable images. Poorly-rated & highly-rated comments are displayed differently.
This plugin is simple and light-weight. 

== Description ==

If you're tired of moderating readers' comments on your blog, stop doing
that and let your readers decide which comment deserves to be shown.
If you're getting outrageous comments on your blog, don't get too
angry yet.  Let's see how many readers feel the same.  You can
do these tasks (and more) with the Comment Rating plugin.

Comment Rating makes "user moderated content" possible.
This plugin automatically embeds clickable images in comments using
light-weight AJAX javascript (no heavy jQuery) to allow visitors rate comments in
Like vs. Dislike fashion.  The votes are displayed along with the
comments in either two numbers, one combined, or both.

Once the user ratings are in place, the comments can be displayed
and styled accordingly.  Poorly rated comments (too many Dislikes,
not enough Likes) can be hidden in a click-to-see link, just like
those on Digg.  Highly-rated comments (a lot Likes and few Dislikes)
can be highlighted.  Hotly-debated comments (many Likes and
Dislikes) can also be highlighted to draw more attention, to encourage
more votes and comments. 

The thresholds for all three types of comments are configurable.
So are the styling.  Styling can be done with background color,
opacity,fonts, etc. on the comment as well as the entire comment box.

This plugin allows using Wordpress as a general CMS in a
Web 2.0 fashion.  User generated content can be rated and their
display influnced by other users. An example website is
<a href="http://captionwit.com">Caption Wit</a>.

Summary of key features;

*  Auto-insert to comments AJAX based clickable images 
*  Configurable display of two vote numbers, a combined one or both
*  Preventing voting fraud with one vote per IP address. This is less subject to manipulation than cookie based approaches. The author of a comment cannot rate his/her own comment.
*  Styling of popular and mostly debated comments based on on the votes
*  Poorly rated comments can be hidden in a click-to-see fashion. There's no point of silencing your own voice on your blog.  When readers mark a comment by the blog author or admin as poorly rated, the comment will not be hidden, but only marked as poorly rated.  
*  Styling of the vote numbers differently. 
*  Mouseover effect on images to entice voting
*  Choice of images and image size
*  Localization for multi-lingual support
*  Functions are provide for theme customization.
*  Allow vote types of: positive only, negative only votes or both.
*  Store votes in wp_comments table comment_karma field. Stored votes can be: positive only, negative only or combined.
*  A widget is provided as Comment Rating Widget to display the ratings along with recent comments.  
*  Simple and light-weight (i.e. high performance).  It's also wp-cache and wp-super-cache friendly.

There's also a <a href="http://wealthynetizen.com/comment-rating-pro/"> Pro version</a> which has all the functionality of the standard version, and the following features.

* Styling comment image and text with a CSS class
* Voting by logged-in users only. This gives you the capability of running Comment Rating on a membership-based site.  Non-logged-in visitors  always see a gray icons and a message reminding them to log in or register.
* Voting fraud detection by user ID. This is the strictest form of voting fraud prevention.  Only logged in users can vote and one vote per user ID.
* Voting fraud detection by cookie. Votes are counted per cookie. This solves the problem that most of your readers are behind a NAT firewall. Cookie expiration can be set to a fix date or by days.
* Disabling voting restrictions, i.e. disabling one-vote-per-IP-address and allow unrestricted voting.
* Control where to  insert comment rating images: Posts, Pages or both, or on selected post IDs.
* Additional functions to retrieve comments ordered by their ratings
* Additional icons to make your blog unique


More details about <a href="http://wealthynetizen.com/comment-rating-pro/"> the Comment Rating Pro and how to obtain it can be found here</a>.

Comment Rating plugin is built on top of Alex Bailey's discontinued Comment Karma.
Thanks to Jean-Paul Horn and many other users for ideas and suggestions.


== Installation ==

1. After download the plug in, you can upload and install it from 
Wordpress Dashboard -> Plugins -> Add New. Alternatively, you can
unpack and upload the dir with files to the wp-content/plugins folder on your blog.  

1. Activate the plugin.

1. You can configure the options under Setting -> Comment Rating.
The default options should be good enough. It works out of box. You
are done. Sit back and have a look at your blog.

1. If you want to tailor the display format further, you can turn
off auto-insertion into comments and add the following line to an
appropriate place in your theme "comments.php" file within the comment loop.
 if(function_exists(ckrating_display_karma)) { ckrating_display_karma(); }

More about custom installation in the <a href="http://wealthynetizen.com/comment-rating-plugin-faq/"> Comment Rating plugin FAQ</a>.

== Frequently Asked Questions ==

For complete and most up-to-date FAQ, please see <a href="http://wealthynetizen.com/comment-rating-plugin-faq/"> Comment Rating plugin FAQ</a>.

* Why are the voting image in gray?

If the thumbs are grey, it's most likely working well. Comment
author cannot vote on his/her own comments. That's why the voting
images are grayed out and don't respond to mouse-over. But if you
change to a different IP address, you'll be able to see the
clickable images and mouse-over effects.

If all your computers go through the same ADSL/Cable router, they
all have the same external IP address. The thumb will stay gray,
until your IP address changes (e.g. rebooting the router).

To others, the thumbs should be in color.

* Can I style the whole comment box of highly-rated/poorly-rated/hotly-debated comments

Yes. the comment styling uses the new comment_class filter
(introduced in WordPress 2.7). If your theme doesn't use
WordPress 2.7 wp_list_comments(), you'll only see the comment
text background being styled or highlighted. To fix the problem, you
need to add comment_class into your existing theme. For example
code, please see here.  

* My nested comment box highlighting is messed up!

When using nested comments, the styling of a highly or poorly-rated
comment is passed on to every comment below it. This means that
every comment nested below a low-rated comment becomes semi-opaque,
even if it is high-rated itself. The end result is huge blocks of
dim text that are difficult to read.

Also highly-rated comments pass on that style to everything below
it, resulting in huge blocks of styled text that obscure the rating
of comments that are not really highly rated.

The problem is caused by styling the whole comment box. To solve the problem, just turn off comment box styling.

* How do I set the thresholds of highly-rated/poorly-rated/hotly-debated comments?

This is the tricky part. Setting the thresholds too low, every comment becomes highlighted or hidden. Setting them too high, nothing changes and you cannot draw readers attention.

Every blog's readers are different.  Some have passionate and
active readers who vote on almost every comment.  Some have
indifferent readers who don't want to click.

There's no magic formula.  You'll have to experiment.  Hopefully, it's fun to play with the numbers.

* Can a comment be both highly-rated, and poorly-rated or hotly-debated?

Yes, if you're not careful with your thresholds. Won't there be messy formatting? No, there won't be. Rest assured.  Comment Rating will use only one style based on the following descending priorities: highly-rated, poorly-rated, hotly-debated.

* Can I style the whole comment box of highly-rated/poorly-rated/hotly-debated comments

Yes. the comment styling uses the new comment_class filter
(introduced in WordPress 2.7). If your theme doesn't use
WordPress 2.7 wp_list_comments(), you'll only see the comment
text background being styled or highlighted. To fix the problem, you
need to add comment_class into your existing theme. For example
code, please see here.  


== ToDo List ==

If you want to request a feature, please post to <a href="http://wealthynetizen.com/comment-rating-plugin-todo-list/"> Comment Rating ToDo List</a>.

== Screenshots ==

1. Example a Wordpress installation right after installation.

2. An example website <a href="http://captionwit.com">Caption Wit</a>.

3. Comment Rating Pro Option page showing the rich & flexible configuration features.

4. Comment Rating standard option page.

== Changelog ==

= 2.9.28 =

Tested on WP 3.1.3.  Still works like a charm

= 2.9.27 =

de_DE translation by <a href="http://www.professionaltranslation.com/"> Professional Translation </a>

= 2.9.26 =

pt_BR translation by frq.

= 2.9.25 =

Support IP based fraud detection for servers behind a reverse proxy.  Thank to Marc Gortz from the German online marketing agency <a href="http://www.klickfreundlich.de/">klickfreundlich</a>.

= 2.9.24 =

Urgent update: fix a loophole which allows potential SQL Injection
attack 

= 2.9.22 =

Tested on 3.0.4 and add explanations.

= 2.9.21 =

Added CSRF attack protection.  Thanks <a href="http://krebsonsecurity.com">krebsonsecurity.com </a> for reporting the problem and providing part of the solution.

= 2.9.20 =

Tested in WP 3.0.2

= 2.9.19 =

Polish translation by <a href="http://tinydirect.com"> Tinydirect </a>


= 2.9.18 =

More screen shots

= 2.9.17 =

Update feature descriptions and screen shots

= 2.9.15 =

Tested on Wordpress 3.0.  Everything is fine.

= 2.9.14 =

Albanian translation by <a href="http://wpalb.com">WPAlb.com</a> 

= 2.9.13 =

Tested on Wordpress 2.9.2.  Still works like a charm.

= 2.9.12 =

Fix the bug that text are displayed in different line, due to
specifics in certain themes, e.g. Suffusion.

= 2.9.11 =

Add "margin: 0px;" to image style.  This should prevent voting icons
and vote numbers being in separate lines.

= 2.9.10 =

Tested under Wordpress 2.9 and 2.9.1.
All working well.

= 2.9.9 =

Set filter priority to 9000.  Late enough to avoid most conflicts, early enough to avoid conflicting
with WP Threaded Comment

= 2.9.8 =

Cannot run Comment Rating as the last filter.  This conflicts with
WP Threaded Comment plugin.  Weighing the evil, now we are now in
conflict with Kaskus Emoticons plugin.

= 2.9.7 =

Add choice to tooltips for "Thumb up" or "Thumb down", contributed
by <a href="http://seemaximumresults.com"> Eric Peterka</a>. 

= 2.9.6 =

* Make sure Comment Rating is the last filter to run.  This avoids
conflicts with Kaskus Emoticons plugin.
* Fix the duplicated image id causing problem with Comment Rating Widget

= 2.9.5 =

Remove browser not supporting XMLHttpRequest object.  It doesn't
help ordinary user. 

= 2.9.4 =

There's no point of silencing your own voice on your blog.  When
readers mark a comment by the blog author or admin as poorly rated,
the comment will not be hidden, but only marked as poorly rated.

= 2.9.3 =

Fix the multiple alert message when the voting icons are double clicked.

= 2.9.2 =

Tested on Wordpress 2.8.6.  Added non-breakable space between voting
images and numbers

= 2.9.1 =

Belorussian translation by <a href="http://www.fatcow.com"> FatCow</a>

= 2.9.0 =

Added function to enable widget (Comment Rating Widget plugin) to display the ratings along with recent comments.  

= 2.8.6 =

Fixed the bug when auto-insert is turned off, highlighting won't
have any effect

= 2.8.5 =

Fixed a bug in Spanish translation.

= 2.8.4 =

French translation by Charlie Borghini.

= 2.8.3 =

Tested on WordPress 2.8.5

= 2.8.2 =

*  Store votes in wp_comments table comment_karma field. Stored votes can be: positive only, negative only or combined.
*  Add database access caching, improve efficiency.
*  Fix the known bug that, If you enable Comment Rating, disable it and then enable it again,  the comments were made during the disabling period will not have any database record and won't show any rating images.

= 2.8.1 =

Fix a Javascript bug when choosing Likes-only or Dislikes-only.

= 2.8.0 =

Allow vote type of: positive only, negative only votes or both.

= 2.7.8 =

Arabic Yemen translation by  <a href="http://www.teedoz.com">Thamood Binmahfooz</a>

= 2.7.7 =

* Add options for mouseover effect
* Spanish translation by <a href="http://www.plataformanetworks.com">David Basulto</a>.

= 2.7.6 =

Fix missing closing tag in hidden comments.

= 2.7.5 =

* Add youtub style voting images.
* Fix the hidden comment not displayed bug, introduced in version 2.7.4 

= 2.7.4 =

* Add option to turn off inline style sheet and javascript loading.
This helps power users to customize their theme efficiently.

* Bulgarian translation by <a href="http://itws.eu">ITWS</a>.

= 2.7.2 =

NL translation by <a href="http://www.iphoneclub.nl">iPhoneclub.nl</a>.

= 2.7.1 =

* Added localization capability. 
* Please help out with translation.

= 2.7.0 =

* Add hotly-debated highlighting.
* Add option to turn off comment box, to avoid messy styling for
nested comments.
* Wording before the image now changes with the rating of the comment.
* Made all javascript function/var name unique.
* Fix the get_bloginfo('wpurl') bug.
* Fix an incompatibility with WP Super Cache.

= 2.6.1 =

* 2.6.0 turns out to be rather stable.  
* Add thumb image titles. 

= 2.6.0 =

* Allow display of 1, 2 or both voting numbers.  
* Add styling of the numbers. 
* Add mouseover effect to images
* Add choice of images.
* Add choice of image size

= 2.5.2 =

* Turn off duplicated vote error message.  Duplicated votes are
skipped in counting.
* Add an empty space between vote number and image.

= 2.5.1 =

Fix javascript loading problem in admin pages.

= 2.5.0 =

* Add option to choose one number or two.
* Use checkmark to indicate voting success.

= 2.4.6 =

Fix a bug that caused non-valide XHTML.

= 2.4.4 =

* Add "!important" to default styling to avoid being overriden.
* Change option page layout.  change highly/poorly rated criteria.

= 2.4.2 =

Enhance the highly-rated comment styling to the entire comment box.

= 2.4.1 =

* Change default threshholds on highlight good comments and hide poor comments.

* Fixing the bug: when auto-insert is turned off, comment highlighting
and hiding are missing too.

= 2.4.0 =

Adding styling to highly-rated comments.

= 2.3.3 =

Correct image alt text.  Remove mentioning of IP address in error
message.

= 2.3.2 =

* Fix a bug for rating threshhold.
* Add fine-tuned control to turn off rating for admin/author's comments.

= 2.3 =

Comments disliked too much by readers will be hidden in a click-to-show link.

= 2.2 =

Add flexibility with an option page.

= 2.1 =

Add auto-insertion rating images into comments, and javascript to footer

= 2.0 =

* Change the vote counter from 1 (total) to 2 (Likes and Dislikes) and
display them separately.
* Added index to vote count table. Improved the plug-in performance

