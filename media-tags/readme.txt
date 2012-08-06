=== Media Tags ===
Contributors: Paul Menard
Donate link: http://www.codehooligans.com/donations/
Tags: media-tags, media tags, media, tags, images, attachments, documents, taxonomy, shortcode, permalinks, role management, bulk admin, gallery
Requires at least: 3.0.1
Tested up to: 3.1
Stable tag: 3.0.4

== Description ==

[Plugin Homepage](http://www.codehooligans.com/projects/wordpress/media-tags/ "Media-Tags Plugin for WordPress")

Adds an input to the media upload and management screens. This input field can be used to "tag" a media file. Works with images, documents or anything.

Media-Tags 3.0 has been completely updated. Key features included in the new version are:

* Bulk Administration of media items. This feature on both the Media > Library and Media Upload popup for the Post admin screen allow you to assign/remove Media-Tags to a selected group of media items. In previous versions you would need to edit each media item. 

* Roles management. Under the Media-Tags Settings panel is a new Roles management panel. This panel allows you to fine tune the access by individual users. 

* Internationalization. This is a much needed and requested features. Now all text handled by the plugin are using the WordPress i18n hooks to support translation into other languages. 

* Removed over 1000 lines of custom code. This old code was used to provide basic functionality for the tagging and URL rewrites. Since WordPress core functions have progressed over the last two years this custom code is no longer needed. This means the plugin will run cleaner and is more stable than previous releases. 

* Better support for WordPress standard Taxonomy templates. In the past the plugin has supported a custom theme template, mediatag.php. The plugin now support the more standard WordPress templates taxonomy-media-tags.php.

* A new Help section. This new Help section provides many topics from general use to shortcodes tricks to template files support questions. Check it out. 

* Many other features have been added. Too many to mention here. 

[Plugin Homepage](http://www.codehooligans.com/projects/wordpress/media-tags/ "Media-Tags Plugin")


== Installation ==

1. Upload the extracted plugin folder and contained files to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. If you are upgrading from an earlier version of Media Tags the tags will be converted automatically when the plugin is activated.
4. Navigate to any Post/Page where a media file has been attached. Click on the 'Add Media' option on the post content icon bar. Select a media item from the Gallery. Click the 'Show' link to show the media item details. You will notice the new 'Media Tags' input field just below the WordPress Size option. 
4. All used tags will be displayed as checkboxes below the new Media Tag input field. 
5. Also all used media tags can be managed via the new Media Tags interface as part of the Media section in wp-admin. Management of Media tag is now part of a familiar interface like Tags and Categories. 
6. Tweak out your template to display media tags (optional).

== Frequently Asked Questions == 

The FAQ section has been written as a Help section within the plugin's settings panel. You will need to download and install the plugin to review this information.

== Screenshots ==

1. The Media Tags example via the Media > Library edit screen. Shows the added Media-Tags fields
2. The Media-Tags Bulk Management popup via the Media > Library listing screen. (new in 3.0!)
3. The Media Tags Management Roles Management screen (new in 3.0!)
3. The Media-Tags Bulk Management panel via the media popup Gallery tab (new in 3.0!)

== Changelog == 

= 3.0.3 =
2010-12-16
* Fixed code to allow searches within a media-tags term. For example your side http://www.somesite.com/media-tags/flags?s=xxx would return results from all media-tags terms not just within the 'flags' term. So had to add back in some of the filters for the Where and Join of the query (yuk!). The search is applied in default WordPress fashion to the attachment title an description fields. Will try to include the alt text and caption field in some later release. 

Thanks to Carlos for commenting on my blog and bringing this to my attention. You can read the thread of comments here.  http://www.codehooligans.com/projects/wordpress/media-tags/#comment-71043

* Corrected an issue with the compare of the global 'wp_version'. Was using the PHP function floatval but this return inaccurate value when the version is something like '3.0.x'. Now using the PHP function version_compare. 

* Within in the Media > Library enabled search to filter listing by Media-Tags. Select the Media-Tag term from the dropdown. Then enter something into the search and submit. 

* No other known bugs at this time. 

= 3.0.4 =
* Bugs fixed
Fixed hard-coded /wp-content/ path in the pugin. Thanks to justinph for to support solution http://wordpress.org/support/topic/plugin-media-tags-doesnt-work-if-you-dont-use-wp-contents

Fixed conflict on the Media Library page when you attempt to Attach a media item to a page. There is some ugly JavaScript used by WordPress which was picking up one of the Media-tags radio buttons. Added some code to hide the Media-Tags elements. And posted a trac ticket. Thanks to jc364 for bringing this to my attention and providing the needed details. http://wordpress.org/support/topic/plugin-media-tags-not-able-to-attach-media-to-page

Fixed an issue with some legacy code which prevented assigning or creating tags of pure numbers. Thanks to Tom who posted that via my blog.
http://www.codehooligans.com/projects/wordpress/media-tags/#comment-81887

Fixed a compatibility issue with the newly released WordPress 3.1. In the early release candidate versions of 3.1 these was some cool code changes to the way some of the page listing and bulk actions are handled. Seems between WP3.1rc2 and WP3.1rc3 these cool changes were purposely blocked. I had not noticed this until recently. This issue effected access to the Media-tags bulk action via the dropdown on the Media Library page. 

Added a new section to the Settings panel. This new section allow you to completely change the labels used by Media-tags. Thanks to Laetitia Debruyne for writing and asking for some method to convert these to her language, French. This is not so much a translation thing it i18n. But with many plugins they register post types or taxonomies and the user has not ability to change these. Well, for Media-Tags now you do. 
 
Many users have been requesting a way to display or filter all untagged media. This is coming in the next release. This release is mostly bug fixes. 

= 3.0.3 =
* More bugs fixed. 


= 3.0.2 =
2010-12-07
* More bug fixed on 'substr_compare' related code causing PHP Warning messages. 

= 3.0.1 =
2010-12-06
* Bug fixed on 'substr_compare' related code causing PHP Warning messages. 

= 3.0 =
2010-12-05

* Added a Bulk Admin interface on the Media > Library page and the Media upload popup. This new bulk admin interface allows selection of multiple media items then assignment of Media-Tag terms to those items. 

* Added a User Roles Management. This section allows you to selectively grant/deny permission for certain Media-Tags management actions.

* New Help section. Provides help and information on many topics submitted as comments/question. 

* Added some new template functions which allow more ways to access information about Media-Tag terms and items. 

* Major code cleanup. Removed almost 1000 lines of previously custom code to handle the Term admin interface and rewrite rules. Now using many of the core functions provided by WordPress. 

Thanks to email from Chris Webb who provided much of the original code to load the Show Common and Show Uncommon Media-Tags via AJAX. 

Thanks to all who provided comments and voiced opinions on features to be added to Media-Tags. The plugin has become a very rich tool used by many. Your voices have been heard. I still have a long list of features to add in upcoming releases. Please be patient. 


= 2.2.9.2 = 
2010-07-17
* Changes includes more fixes for things broken under WP 3.0. Sorry for all the recent releases. Just trying to stay on top of the fixes. Also, patched some code for the export/import logic. Added two new shortcode and template function parameters. First is 'post_type'. This should allow filtering of Media-Tag media by post, page or your custom post types. Only a single post_type is supported. Second, is nopaging. This was reported by someone having trouble getting a sidebar widget to display all the Media-Tag items. 

Still working in some bug and enhancement. Keep the bug reports coming. Thanks to all for making this a great plugin.

= 2.2.9.1 = 
2010-06-20
* Change to the rewrite init processing. Found out I was doing it wrong. Thanks for clope on the WordPress.org forums via this thread. http://wordpress.org/support/topic/360613

Hopefully this makes Media-Tags place nicer with larger systems. 

= 2.2.9 =
2010-06-08: Changes include:
* Corrected an taxonomy registration during the initializing of the plugin. Testing with WP 3.0 RC1. 

= 2.2.8 =
2010-04-22: Changes include:
* Some code tweaks to streamline the logic.
* Corrected an initializing issue with the plugin that effected the init process which in turn effected the rewrite setup and use of the mediatag.php template file. 

= 2.2.7 =
2010-04-22: Changes include:
* Some code tweaks to streamline the logic.

* Bug fix: Better Init method. Thanks to Mike Schinkel for pointing out the error of my ways on this. Also for suggesting using the WP_DEBUG to make sure I have all the holes on the dike plugged. 

* Bug fix: Erroneous compare argument on the activate logic media_tags.php in the init function. Thanks to Tom for that note http://www.codehooligans.com/2009/08/17/media-tags-2-2-plugin-for-wordpress-released/#comment-48664

* Bug fix: Fixed some hard-coded table name prefixes. To all I apologize for this issue. For some reason early code I lifted from another plugin I didn't scan. In the mediatags_rewrite.php where the SQL WHERE is manipulated for matching the rewrite URL the queries had hard-coded prefixed as in 'wp_posts.', etc. This prevents the Media Tags plugin from working on non-standard database setup and also for WPMU. This should now be working. 

* Some initial testing with WordPress 3.0 Beta 1. Things seem to work fine with this plugin. But open for further testing. 

= 2.2.6 = 
2010-01-30: Changes include:
* Some code tweaks to streamline the logic.

* Added RSS output option to Media-Tag Settings page. When enabled will allow direct RSS for an item archive. for example given a Media-Tag archive like http://www.somesite.com/media-tags/my-tag where my-tag is a Media-Tag item you can access the RSS by accessing http://www.somesite.com/media-tags/my-tag/feed.

* Export/Import logic for Media Tags. I've utilized form action in WordPress that allow complete export and import of Media-Tags elements when using the WordPress export Tool. There is currently not a stand alone method to just export Media-Tags.

* Coming soon a few Media-Tags widgets. 

= 2.2.5 =
2009-08-30: Changes include:
* Bug fixes to Admin screens. Namely one but for the Permalink slug field. 

* Added a 'View' option on the Media Tags Management screen on the quite menu options. This will let you preview the media-tag item in your theme. 

* Added new function for mediatags_cloud(); This will generate the tag cloud or tags. Note this new function is a wrapper for the new WordPress core function wp_tg_cloud() added in 2.8. If used on an older version of WordPress there will be no output. http://codex.wordpress.org/Template_Tags/wp_tag_cloud

* Added a column to the Media Library view. This new column lists the item's media tags. The content in this column is much like the Tags column on the Posts listing. The Media Tag is linked so it will filter the Media Library display. Thanks to the many commentors for that simple item. 

* Added some logic to split the media tags into sections on both the Media Library item view as well as the Media Upload view. The thought here is to display the media tags in three sets. The first set is the items' select media-tags. The second set is the common media tags. The third set is the uncommon media tags.

* Code cleanup.

* Coming in some future release will be a bulk management option. This option will allow you to select item(s) from your Media Library and set the media tag. Still working on the interface logic. 

= 2.2 =
2009-08-16: Changes include:
* New Media Tags tab on the media upload popup. Now you can search by the Media Tag items. Functionality similar to Media section.

* Now you can control the permalink prefix which was previously hard-coded as '/media-tags/'. Go to Settings -> Permalinks. You should see a new input field below the Category and Tag fields. This lets you use something unique to your site.

* Integration with some other plugins. The first to be added is the famous Google XML Sitemaps plugin. Now when you build your XML Sitemap you can include Media Tag URLs just like WordPress Categories and Tags. Look for the options under Settings -> Media Tags (New menu).

* Some general code cleanup. Namely a conflict the Media Tags plugin was causing with Role Scoper and some other plugins. 

* Renamed the original plugin file from 'meta_tags.php' to 'media_tags.php'.

= 2.1.3 =
* 2009-08-06: Changes include a addition of new template function 'single_mediatag_title()' for use on the archive.php template or the new mediatag.php template file. Thanks to Carlos for the comment regarding this. http://www.codehooligans.com/2009/07/15/media-tags-20-released/comment-page-1/#comment-42956

Also included are some small changes to cleanup the core media tag search code. One change as suggested by Jozik http://www.codehooligans.com/2009/07/15/media-tags-20-released/comment-page-1/#comment-42927 to correct the get_terms argument to search by slug as default. To search by name you may use the 'search_by=name' function argument. This new 'search_by' parameter is also supported via the shortcodes. 

= 2.1.2 =
* 2009-07-24: Changes include a bug in the SQL 'Where' parsing to display media tags via the site. The bug prevented non-authenticated users from seeing the items. Correct SQL Where parsing. Thanks for Francisco Ernesto Teixeira for find this issue and reporting it http://www.codehooligans.com/2009/07/15/media-tags-20-released/#comment-42663

= 2.1.1 =
* 2009-07-23: Changes to fix relative paths when WordPress is not installed into the root. Thanks to Ilan Y. Cohen for the tip on this bug. 

= 2.1 =
* 2009-07-23: Changes include code cleanup and correcting link under new Media-Tags Management screen.

= 2.0 =
* 2009-07-15: Major update to the plugin. New Permalink, shortcodes, Media Tags management interface.

= 1.0.1 =
* 2009-02-04: Found an issue with the returned attachment when calling get_posts. Changed this to get_children.

= 1.0 =
* 2008-12-13: Initial release
