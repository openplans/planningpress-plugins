=== Street View Comments ===
Contributors: acochran, atogle, cabraham
Donate link: http://openplans.org/donate/
Tags: comments, street view, maps, gis
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Gathers comments attached to certain views of a Google Street View display along a street or avenue.  

== Description ==

Street View Comments gathers comments attached to certain views of a Google Street View display along a street or avenue.  It is easily configured for various points or intersections along a street and is embedded into a post or page via a shortcode.  Viewers choose an intersection and zoom to a view then leave a comment.  Comments can be moderated and managed within Wordpress.

See a demo of the plugin [here](http://demo.planningpress.org/what-needs-improving-on-broome-street).  By default, when you activate the plugin, the same data from this demo will load as intersections.  Place [street-view-comments] in a post or page to see the demo.

Street View Comments uses [Fitzgerald](https://github.com/openplans/fitzgerald).

== Installation ==

1. Upload Street View Comments to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure intersections by going to wp-admin/edit.php?post_type=svc_intersection
1. Create a background image showing overhead map of intersections (680x140px)
1. Use shortcode on post or page to insert Street View Comments: [street-view-comments background="http://url-to-background-image" mainstreet="main street name"]

== Screenshots ==

1. Move to different intersections. See comments other people have left.
2. Leave a comment.
3. Setup and modify intersections.
4. Moderate comments.

== Changelog ==

= 0.1 (7/31/2012) =
* initial release
