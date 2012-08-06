=== Page in Widget ===
Contributors: carlfredrik.hero
Tags: page, widget
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.1

A tiny plugin that displays a page content in a widget.

== Description ==

The Page in Widget widget lets you display a page content inside a widget. This way you have more control how the content is displayed, and it's much easier than hacking your own HTML.

The output is filtered through the_content-filter which means that paragraph tags are added, just as any other post or page.

== Installation ==

1. Upload the zipfile to the `/wp-content/plugins/` directory
2. Extract and remove it
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Drag the new widget to desired sidebar, choose a title for the widget and select the appropriate page.

== Changelog ==

= 1.1 =
* The plugin now consider the `<!--more-->` tag
* Added option to show/hide the more link
* Added proper filtering to output

= 1.0 =
* Initial release