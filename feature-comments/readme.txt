=== Plugin Name ===
Contributors: utkarsh
Donate link: http://wpprogrammer.com
Tags: comments
Requires at least: 2.9
Tested up to: 3.0
Stable tag: trunk

Lets the admin add "featured" or "buried" css class to selected comments. Handy to highlight comments that add value to your post.

== Description ==

Lets the admin add "featured" or "buried" css class to selected comments. Handy to highlight comments that add value to your post.

This plugin makes use of the commentmeta table, which was introduced in WordPress 2.9 Hence, the plugin is not compatible with versions before 2.9

This plugin also requires PHP5.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload 'feature-comments' directory to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

All the options will be automatically added to the edit comments table, and single comment edit screen

== Screenshots ==

1. Comment Edit Table
2. Single Comment Edit
3. Class added to comment, as seen on the frontend (screenshot shows source viewed in Firebug)

== Changelog ==
= 1.1.1 =
* Fixed bug, which showed feature/bury links to all users, instead of users with 'moderate_comments' capability.

= 1.1 =
* Major update
* Anyone with 'moderate_comments' capability is now able to feature/bury comments both from the frontend and backend
* Added support for featuring comments using ajax.
* The edit comments section now highlights featured comments, and reduces the opacity of buried comments.
* Fixed some E_NOTICE's

= 1.0.3 =
* Fixed a bug introduced in the last update

= 1.0.2 =
* Refactored source code

= 1.0.1 =
* Added missing screenshot files

= 1.0 =
* First version