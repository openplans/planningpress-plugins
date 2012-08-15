=== GeoJSON Maps ===
Contributors: atogle, cabraham
Donate link: http://openplans.org/donate/
Tags: maps, geojson, gis
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows people to display GeoJSON feeds on a map.

== Description ==

GeoJSON Maps facilitates displaying multiple GeoJSON feeds on a map.  It has a custom legend to toggle on/off each data layer.  The map can be displayed on a Wordpress post or page via a short code.  All you need is some [GeoJSON](http://geojson.org/) feeds (both json and jsonp) and some styling markup.  See [here](http://demo.planningpress.org/map/) for a demo.  

GeoJSON Maps uses [Argo](https://github.com/openplans/argo).  Its configuration is provided [here](https://github.com/openplans/argo/wiki/Configuration-Guide).  Take note of the "rules" and "popupContent" items to be entered when setting up a layer.  So, in the Street Vendors layer from the [demo](http://demo.planningpress.org/map/), "Layer Rules" is set to:

    [{
      condition: 'true',
      style: {color: '#444444', radius: 1, opacity: 0.9}
    }]

For the Crashes layer, "Popup Text" is set to: 

    {{value}} crashes

== Installation ==

1. Upload GeoJSON Maps to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Adjust global map settings by going to Settings | GeoJSON Maps
1. Add layers to the map using the Map Layers custom post type in the admin interface; for Style Rules, see: https://github.com/openplans/argo/wiki/Configuration-Guide
1. Place shortcode [geojson-map] on page or post where you want the map to display

== Screenshots ==

1. Map with two data layers turned on.
2. Hovering over the legend.
3. The admin display of the various map layers.
4. The details of a layer.

== Changelog ==

= 0.1 (8/16/2012) =
* initial release
