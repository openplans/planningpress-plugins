<?php
/**
 * Plugin Name: MyGeoPosition.com Geotagging: Geotags / GeoMetatags / GeoFeedtags / GeoMicroformats / Maps
 * Plugin URI: http://www.mygeoposition.com
 * Description: Create geo-posttags, geo-metatags, geo-feedtags, geo-microformats and maps for posts and pages. Display the geotagged location in form of a map before, after or within the post. An easy-to-use geopicker map with auto-locating functionality helps entering locations.
 * Version: 1.3.1
 * Author: Daniel Filzhut
 * Author URI: http://www.filzhut.de
 */



$mygpGeotagsGeoMetatags_key = "mygpGeotagsGeoMetatags";



/**
 * Define script location.
 *
 */
if ( !defined('WP_CONTENT_URL') ) {
	define('MYGP_GEOTAGS_GEOMETATGS_PLUGINPATH',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
	define('MYGP_GEOTAGS_GEOMETATGS_PLUGINDIR', ABSPATH.'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
} else {
	define('MYGP_GEOTAGS_GEOMETATGS_PLUGINPATH', WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
	define('MYGP_GEOTAGS_GEOMETATGS_PLUGINDIR', WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
}



/**
 * Load translations
 *
 */
function mygpGeotagsGeoMetatags_loadTexts() {

  global $mygpGeotagsGeoMetatags_key, $mygpGeotagsGeoMetatags_metaBoxes;

  load_plugin_textdomain( $mygpGeotagsGeoMetatags_key, '', dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

  $mygpGeotagsGeoMetatags_metaBoxes = array(
    "placename" => array(
        "name" => "placename",
        "title" => "geo.placename",
        "description" => __('City or other placename. Example: New York', $mygpGeotagsGeoMetatags_key)),
    "region" => array(
        "name" => "region",
        "title" => "geo.region",
        "description" => __('Country and state. Example: US-NY', $mygpGeotagsGeoMetatags_key)),
    "position" => array(
        "name" => "position",
        "title" => "geo.position",
        "description" => __('Latitude and Longitude. Example: 40.7143528;-74.0059731', $mygpGeotagsGeoMetatags_key)),
    "addMap" => array(
        "name" => "addMap",
        "title" => __('Show map', $mygpGeotagsGeoMetatags_key),
        "description" => __('', $mygpGeotagsGeoMetatags_key),
        "options" => array(
        		"default" => __('default', $mygpGeotagsGeoMetatags_key),
        		"true" => __('yes', $mygpGeotagsGeoMetatags_key),
        		"false" => __('no', $mygpGeotagsGeoMetatags_key),
        	)
        ),
    "mapLocation" => array(
        "name" => "mapLocation",
        "title" => __('Map location', $mygpGeotagsGeoMetatags_key),
        "description" => __('', $mygpGeotagsGeoMetatags_key),
        "options" => array(
        		"default" => __('default', $mygpGeotagsGeoMetatags_key),
        		"before" => __('before post', $mygpGeotagsGeoMetatags_key),
        		"after" => __('after post', $mygpGeotagsGeoMetatags_key),
        		"shortcode" => __('replace [mygp_map] in post', $mygpGeotagsGeoMetatags_key),
        	)
        ),
    "zoomLevel" => array(
        "name" => "zoomLevel",
        "title" => __('Zoom level', $mygpGeotagsGeoMetatags_key),
        "description" => __('', $mygpGeotagsGeoMetatags_key),
        "options" => array(
        		"default" => __('default', $mygpGeotagsGeoMetatags_key),
        		"1" => __('1 - World', $mygpGeotagsGeoMetatags_key),
        		"2" => __('2', $mygpGeotagsGeoMetatags_key),
        		"3" => __('3 - Continent', $mygpGeotagsGeoMetatags_key),
        		"4" => __('4', $mygpGeotagsGeoMetatags_key),
        		"5" => __('5 - Country', $mygpGeotagsGeoMetatags_key),
        		"6" => __('6', $mygpGeotagsGeoMetatags_key),
        		"7" => __('7 - State', $mygpGeotagsGeoMetatags_key),
        		"8" => __('8', $mygpGeotagsGeoMetatags_key),
        		"9" => __('9', $mygpGeotagsGeoMetatags_key),
        		"10" => __('10', $mygpGeotagsGeoMetatags_key),
        		"11" => __('11 - City', $mygpGeotagsGeoMetatags_key),
        		"12" => __('12', $mygpGeotagsGeoMetatags_key),
        		"13" => __('13', $mygpGeotagsGeoMetatags_key),
        		"14" => __('14 - District', $mygpGeotagsGeoMetatags_key),
        		"15" => __('15', $mygpGeotagsGeoMetatags_key),
        		"16" => __('16 - Street', $mygpGeotagsGeoMetatags_key),
        		"17" => __('17', $mygpGeotagsGeoMetatags_key),
        		"18" => __('18 - Street corner', $mygpGeotagsGeoMetatags_key),
        		"19" => __('19', $mygpGeotagsGeoMetatags_key),
        		"20" => __('20', $mygpGeotagsGeoMetatags_key),
        		"21" => __('21 - House', $mygpGeotagsGeoMetatags_key),
        	)
        )
  );
  
  include('settings.php');
  
}
add_action( 'init', 'mygpGeotagsGeoMetatags_loadTexts' );



/**
 * Add CSS to html head.
 *
 */
function mygpGeotagsGeoMetatags_addCustomHeaderTags(){	
  echo '<link rel="stylesheet" type="text/css" href="' . MYGP_GEOTAGS_GEOMETATGS_PLUGINPATH . 'mygp_geotags_geometatags.css" />';
} 
add_action('admin_head', 'mygpGeotagsGeoMetatags_addCustomHeaderTags');
add_action('wp_head', 'mygpGeotagsGeoMetatags_addCustomHeaderTags');

    
    
/**
 * Add metabox to post editor.
 *
 */
function mygpGeotagsGeoMetatags_createMetaBox() {
	
    global $mygpGeotagsGeoMetatags_key;
 
    if (function_exists('add_meta_box')) {
        add_meta_box('new-meta-boxes', __('MyGeoPosition.com Geotags / GeoMetatags / GeoFeedtags / GeoMicroformats', $mygpGeotagsGeoMetatags_key), 'mygpGeotagsGeoMetatags_displayMetaBox', 'post', 'normal', 'high');
        add_meta_box('new-meta-boxes', __('MyGeoPosition.com Geotags / GeoMetatags / GeoFeedtags / GeoMicroformats', $mygpGeotagsGeoMetatags_key), 'mygpGeotagsGeoMetatags_displayMetaBox', 'page', 'normal', 'high');
    }
    
}
add_action('admin_menu', 'mygpGeotagsGeoMetatags_createMetaBox');

    
    
/**
 * Meta box code.
 *
 */
function mygpGeotagsGeoMetatags_displayMetaBox() {
	
    global $post, $mygpGeotagsGeoMetatags_metaBoxes, $mygpGeotagsGeoMetatags_key;
 
    ?>
 
	<div class="form-wrap form-wrap-mygp-geotags-geometatags">
	 
	<?php
    wp_nonce_field(plugin_basename(__FILE__), $mygpGeotagsGeoMetatags_key . '_wpnonce', false, true); 
    foreach($mygpGeotagsGeoMetatags_metaBoxes as $metaBox) {
    	
        $data = get_post_meta($post->ID, $mygpGeotagsGeoMetatags_key, true);
        
        if ( is_array( $metaBox[ 'options' ] ) ) {?>
 
			<div class="form-field form-required form-field-mygp-select form-field-mygp-<?php echo $metaBox[ 'name' ]; ?>">
				<label for="geopicker-<?php echo $metaBox[ 'name' ]; ?>"><?php echo $metaBox[ 'title' ]; ?></label>
				<select name="<?php echo $metaBox[ 'name' ]; ?>" id="geopicker-<?php echo $metaBox[ 'name' ]; ?>">
				<?php
				while ( list($key, $value) = each( $metaBox[ 'options' ] ) ) {
					if ($key == "default") {
						$key = "";
						$value = $value . " (" . $metaBox[ 'options' ][ mygpGeotagsGeoMetatags_getOption($metaBox[ 'name' ]) ] . ")";
					}
					echo "<option value='" . $key . "' " . ($data[ $metaBox[ 'name' ] ] == $key  ? ' selected="selected"' : '') . ">" . $value . "</option>"; 
				}
				?>
				</select>
				<p><?php echo $metaBox[ 'description' ]; ?></p>
			</div>
		 
		<?php
        } else {?>
 
			<div class="form-field form-required form-field-mygp-input form-field-mygp-<?php echo $metaBox[ 'name' ]; ?>">
				<label for="geopicker-<?php echo $metaBox[ 'name' ]; ?>"><?php echo $metaBox[ 'title' ]; ?></label>
				<input type="text" name="<?php echo $metaBox[ 'name' ]; ?>" id="geopicker-<?php echo $metaBox[ 'name' ]; ?>" value="<?php echo htmlspecialchars($data[ $metaBox[ 'name' ] ]); ?>" />
				<p><?php echo $metaBox[ 'description' ]; ?></p>
			</div>
		 
		<?php
    	}
    }
    ?>

	<div class="form-field form-field-mygp-geotags-geometatags-geopicker form-required">
	
	<script type="text/javascript">
	 
    /**
     * JSON2.js
     */
    
    if(!this.JSON){this.JSON={};}
    (function(){function f(n){return n<10?'0'+n:n;}
    if(typeof Date.prototype.toJSON!=='function'){Date.prototype.toJSON=function(key){return isFinite(this.valueOf())?this.getUTCFullYear()+'-'+
    f(this.getUTCMonth()+1)+'-'+
    f(this.getUTCDate())+'T'+
    f(this.getUTCHours())+':'+
    f(this.getUTCMinutes())+':'+
    f(this.getUTCSeconds())+'Z':null;};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(key){return this.valueOf();};}
    var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(string){escapable.lastIndex=0;return escapable.test(string)?'"'+string.replace(escapable,function(a){var c=meta[a];return typeof c==='string'?c:'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4);})+'"':'"'+string+'"';}
    function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key);}
    if(typeof rep==='function'){value=rep.call(holder,key,value);}
    switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null';}
    gap+=indent;partial=[];if(Object.prototype.toString.apply(value)==='[object Array]'){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null';}
    v=partial.length===0?'[]':gap?'[\n'+gap+
    partial.join(',\n'+gap)+'\n'+
    mind+']':'['+partial.join(',')+']';gap=mind;return v;}
    if(rep&&typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==='string'){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}else{for(k in value){if(Object.hasOwnProperty.call(value,k)){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}
    v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+
    mind+'}':'{'+partial.join(',')+'}';gap=mind;return v;}}
    if(typeof JSON.stringify!=='function'){JSON.stringify=function(value,replacer,space){var i;gap='';indent='';if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' ';}}else if(typeof space==='string'){indent=space;}
    rep=replacer;if(replacer&&typeof replacer!=='function'&&(typeof replacer!=='object'||typeof replacer.length!=='number')){throw new Error('JSON.stringify');}
    return str('',{'':value});};}
    if(typeof JSON.parse!=='function'){JSON.parse=function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v;}else{delete value[k];}}}}
    return reviver.call(holder,key,value);}
    text=String(text);cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return'\\u'+
    ('0000'+a.charCodeAt(0).toString(16)).slice(-4);});}
    if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j;}
    throw new SyntaxError('JSON.parse');};}}());
	
	
	 /**
	  *   This implementation of the MyGeoPosition.com GeoPicker is exclusively developed for the Wordpress Admin Area.
	  *   It makes sure, that the GeoPicker is NOT running in the administration area javascript context.
	  *   Please DO NOT copy&paste/reuse this implementation, as it might change without prior notice.
	  *   For information on a regular implementation, please check  http://api.mygeoposition.com.
	  */
		
		var tagsContainerQuery = "textarea#tax-input-post_tag";
		var tagsUpdateButtonQuery = "#post_tag input[type='submit'], #post_tag input[type='button']";
	 
	  function lookupGeoData() {
	  
      if (!window.postMessage) {
        alert('<?php echo __('Sorry, the Wordpress version of the geopicker does only run in IE8+, FF4+, Safari or Chrome.', $mygpGeotagsGeoMetatags_key); ?>');
        return;
      }
        
      // Initialize geopicker
      var geopickerUrl = 'http://api.mygeoposition.com/api/geopicker/id-wordpresspipm/?langCode=<?php echo substr(get_locale(), 0, 2); ?>';
          if (document.getElementById('geopicker-position').value) {
            geopickerUrl += '&startAddress=' + document.getElementById('geopicker-position').value.replace(/;/, ",");
          } else if (document.getElementById('geopicker-placename').value) {
            geopickerUrl += '&startAddress=' + document.getElementById('geopicker-placename').value;
          } else if (document.getElementById('geopicker-region').value) {
            geopickerUrl += '&startAddress=' + document.getElementById('geopicker-region').value;
          } else {
            geopickerUrl += '&autolocate=<?php echo mygpGeotagsGeoMetatags_getOption('autolocate'); ?>';
          }
      var mgpGeoWindow = window.open("", "MGPGeoPickerWindow", "width=488,height=518,location=no,menubar=no,resizable=no,status=no,toolbar=no");
      mgpGeoWindow.focus();
      mgpGeoWindow.document.write("<" + "html><" + "head><title>GeoPicker</title></" + "head><" + "body style=\"padding:0px;margin:0px;\">");
      mgpGeoWindow.document.write("<iframe src=\"" + geopickerUrl + "\" width=488 height=518 border=0 frameborder=0 style=\"padding:0px;margin:0px;\"></iframe>");
      mgpGeoWindow.document.write("<script type=\"text/javascript\">");
      mgpGeoWindow.document.write("function receiveMessage(event) {");
      mgpGeoWindow.document.write(" if(event.origin.match(/mygeoposition\.com$/g)) {");					
      mgpGeoWindow.document.write("  var geodata = window.opener.JSON.parse(event.data);");
      mgpGeoWindow.document.write("  window.opener.document.getElementById('geopicker-region').value = geodata.country.short + '-' + geodata.state.short;");
      mgpGeoWindow.document.write("  window.opener.document.getElementById('geopicker-placename').value = geodata.address;");
      mgpGeoWindow.document.write("  window.opener.document.getElementById('geopicker-position').value = geodata.lat + ';' + geodata.lng;");

      <?php
      if (mygpGeotagsGeoMetatags_getOption('addPosttags') != 'false') {
      ?>
      mgpGeoWindow.document.write("  window.opener.clearGeoPostTags();");
      mgpGeoWindow.document.write("  window.opener.addPostTags('geotagged,geo:lat=' + geodata.lat + ',geo:lon=' + geodata.lng);");
      mgpGeoWindow.document.write("  window.opener.jQuery(\"#geopicker-placename\").focus();");
      <?php
      }
      ?>

      <?php
      if (mygpGeotagsGeoMetatags_getOption('addCityToPosttags') != 'false') {
      ?>
      mgpGeoWindow.document.write("  window.opener.addPostTags(geodata.city.short);");
      mgpGeoWindow.document.write("  window.opener.jQuery(\"#geopicker-placename\").focus();");
      <?php
      }
      ?>
        
      mgpGeoWindow.document.write("  window.close();");
      mgpGeoWindow.document.write(" }");
      mgpGeoWindow.document.write("}");
      mgpGeoWindow.document.write("if (window.addEventListener) { window.addEventListener('message', receiveMessage, false);}");
      mgpGeoWindow.document.write("else {window.attachEvent('onmessage', receiveMessage);}");
      mgpGeoWindow.document.write("</" + "script>");
      mgpGeoWindow.document.write("</" + "body></" + "html>");
      
	  }
	  
	  function clearGeoData() {
	  	  document.getElementById('geopicker-placename').value = '';
	  	  document.getElementById('geopicker-region').value = '';
	  	  document.getElementById('geopicker-position').value = '';
	      clearGeoPostTags();
	  }
	  
	  function clearGeoPostTags() {
	      if (jQuery) {
	        var items = jQuery(tagsContainerQuery).val();
	        if (items == undefined) {
		        return;
	        }
	        var itemsArray = items.split(",");
	        var itemsNew = '';
	      	for (var i = 0; i < itemsArray.length; i++) {
	      	  if (!itemsArray[i].match(/geo:/) && !itemsArray[i].match(/geotagged/)) {
	      	    itemsNew += itemsArray[i] + ",";
	      	  }	      		
	      	}
	      	jQuery(tagsContainerQuery).val(itemsNew);
	      	jQuery(tagsUpdateButtonQuery).trigger('click');
	      }
	  }
	  
	  function addPostTags(newTag) {
	      if (jQuery) {
	      	jQuery(tagsContainerQuery).val(jQuery(tagsContainerQuery).val() + "," + newTag);
	      	jQuery(tagsUpdateButtonQuery).trigger('click');
	      }
	  }
	  
		document.write('<button type="button" class="button" onclick="lookupGeoData();" id="mygpGeoPickerButton"><?php echo __('Open Geopicker tool', $mygpGeotagsGeoMetatags_key); ?></button>');
	</script>
	  <button type="button" class="button" onclick="clearGeoData();" id="mygpClearGeoDataButton"><?php echo __('Clear geo data', $mygpGeotagsGeoMetatags_key); ?></button>
	  <p id="addGeoPicker"><?php echo __('Add a free GeoPicker to your website or application', $mygpGeotagsGeoMetatags_key); ?>: <a href="http://api.mygeoposition.com" target="_blank">http://api.mygeoposition.com</a></p>
	</div>
	 
	<div style="clear:both;"></div>
		
	</div>

<?php
}

    
    
/**
 * Save entered meta data.
 *
 */
function mygpGeotagsGeoMetatags_saveMetaData($post_id) {
	
    global $mygpGeotagsGeoMetatags_metaBoxes, $mygpGeotagsGeoMetatags_key;
 
    foreach($mygpGeotagsGeoMetatags_metaBoxes as $metaBox) {
        $data[ $metaBox[ 'name' ] ] = htmlspecialchars($_POST[ $metaBox[ 'name' ] ]);
    }
 
    if (!wp_verify_nonce($_POST[ $mygpGeotagsGeoMetatags_key . '_wpnonce' ], plugin_basename(__FILE__)))
        return $post_id;
 
    if (!current_user_can('edit_post', $post_id))
        return $post_id;
 
    update_post_meta($post_id, $mygpGeotagsGeoMetatags_key, $data);
    
} 
add_action('save_post', 'mygpGeotagsGeoMetatags_saveMetaData');


    
/**
 * Add metatags to post in frontend.
 *
 */
function mygpGeotagsGeoMetatags_addGeoMetatags() {
	
    global $wp_query, $mygpGeotagsGeoMetatags_key;
    
    if (is_single() or is_page()) {
    	
        if ($wp_query->post && mygpGeotagsGeoMetatags_getOption('addMetatags') != 'false') {
        	
            $data = get_post_meta($wp_query->post->ID, $mygpGeotagsGeoMetatags_key, true);
            
            if ($data[ 'region' ] != "") {
                echo "<meta name=\"geo.region\" content=\"" . $data[ 'region' ] . "\" />\n";
            }
            
            if ($data[ 'placename' ] != "") {
                echo "<meta name=\"geo.placename\" content=\"" . $data[ 'placename' ] . "\" />\n";
            }
            
            if ($data[ 'position' ] != "") {
                echo "<meta name=\"geo.position\" content=\"" . $data[ 'position' ] . "\" />\n";
                echo "<meta name=\"ICBM\" content=\"" . str_replace(";", ",", $data[ 'position' ]) . "\" />\n";
            }
            
        }
        
    }
    
} 
add_action('wp_head', 'mygpGeotagsGeoMetatags_addGeoMetatags');

    
/**
 * Add geo namespaces to news feeds.
 *
 */
function mygpGeotagsGeoMetatags_addGeoNamespaces(){
	
	if (mygpGeotagsGeoMetatags_getOption("addFeedtags") != 'false') {
	
		echo "xmlns:geo=\"http://www.w3.org/2003/01/geo/wgs84_pos#\"\n";
		echo "\txmlns:georss=\"http://www.georss.org/georss\" xmlns:gml=\"http://www.opengis.net/gml\"\n";
		echo "\txmlns:geourl=\"http://geourl.org/rss/module/\"\n";
		echo "\txmlns:icbm=\"http://postneo.com/icbm\"\n";
	
	}
	
}
add_action('rss2_ns', 'mygpGeotagsGeoMetatags_addGeoNamespaces');
add_action('atom_ns', 'mygpGeotagsGeoMetatags_addGeoNamespaces');
add_action('rdf_ns', 'mygpGeotagsGeoMetatags_addGeoNamespaces');



/**
 * Add geo tags to news feeds
 *
 */
function mygpGeotagsGeoMetatags_addGeoFeedtags(){
	
    global $wp_query, $mygpGeotagsGeoMetatags_key;
	
	if (mygpGeotagsGeoMetatags_getOption("addFeedtags") != 'false') {

		$data = get_post_meta($wp_query->post->ID, $mygpGeotagsGeoMetatags_key, true);
		
		$dataSplitted = "";            
		if ($data[ 'position' ] != "") {
			$dataSplitted = explode(";", $data[ 'position' ]);
		}
			
		$lat = trim($dataSplitted[0]);
		$lon = trim($dataSplitted[1]);
	
		if ($lat != "" && $lon != "") {
			echo "\t<geo:lat>$lat</geo:lat>\n\t\t<geo:long>$lon</geo:long>\n";
			echo "\t\t<georss:where>\n\t\t\t<gml:Point>\n\t\t\t\t<gml:pos>$lat $lon</gml:pos>\n\t\t\t</gml:Point>\n\t\t</georss:where>\n";
			echo "\t\t<georss:point>$lat $lon</georss:point>\n";
			echo "\t\t<geourl:latitude>$lat</geourl:latitude>\n\t\t<geourl:longitude>$lon</geourl:longitude>\n";
			echo "\t\t<icbm:latitude>$lat</icbm:latitude>\n\t\t<icbm:longitude>$lon</icbm:longitude>\n";
		}
	
	}
	
}
add_action('rss2_item', 'mygpGeotagsGeoMetatags_addGeoFeedtags');
add_action('atom_entry', 'mygpGeotagsGeoMetatags_addGeoFeedtags');
add_action('rdf_item', 'mygpGeotagsGeoMetatags_addGeoFeedtags');



/**
 * Create and return map (based on current post)
 *
 */
function mygpGeotagsGeoMetatags_getMap() {

	global $wp_query, $mygpGeotagsGeoMetatags_key;
	
	$postId = $wp_query->post->ID;
	$data = get_post_meta($postId, $mygpGeotagsGeoMetatags_key, true);
	
	$dataSplitted = "";            
	if ($data[ 'position' ] != "") {
		$dataSplitted = explode(";", $data[ 'position' ]);
	} else {
		return "";		
	}
	
	$mapId = 'mygpMap' . $postId;
	$html = '<div id="' . $mapId . '" style="float:' . mygpGeotagsGeoMetatags_getOption("mapFloat", $postId) . ';width:' . mygpGeotagsGeoMetatags_getOption("mapWidth", $postId) . ';height:' . mygpGeotagsGeoMetatags_getOption("mapHeight", $postId) . ';" class="mygpMap"></div>';
	$html .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.1&sensor=false"></script>
<script type="text/javascript">
    var latlng = new google.maps.LatLng(' . $dataSplitted[0] . ', ' . $dataSplitted[1] . ');
    var myOptions = {
      zoom: ' . mygpGeotagsGeoMetatags_getOption("zoomLevel", $postId) . ',
      center: latlng,
      mapTypeId: google.maps.MapTypeId.' . mygpGeotagsGeoMetatags_getOption("mapType", $postId) . '
    };
    var map = new google.maps.Map(document.getElementById("' . $mapId . '"), myOptions);
	map.disableDoubleClickZoom = false;
	map.scrollwheel = true;
	var marker = new google.maps.Marker({
	  position: latlng,
	  map: map
	});
</script>';
	
	return $html;
	
}



/**
 * Add map to post
 *
 */
function mygpGeotagsGeoMetatags_addMap($content) {

	global $wp_query, $mygpGeotagsGeoMetatags_key;
	
	$postId = $wp_query->post->ID;
	$shortcode = '[mygp_map]';
	
	if (mygpGeotagsGeoMetatags_getOption("addMap", $postId) == 'false') {
		return str_replace($shortcode, "", $content);
	}

	$html = mygpGeotagsGeoMetatags_getMap();
	if ($html == '') {
		return str_replace($shortcode, "", $content);
	}

	switch(mygpGeotagsGeoMetatags_getOption('mapLocation', $postId)) {
		case 'before':
			$content = str_replace($shortcode, '', $content);
			$content = $html . '' . $content;
			break;
		case 'after':
			$content = str_replace($shortcode, '', $content);
			$content = $content . '' . $html;
			break;
		case 'shortcode':
			$content = str_replace($shortcode, $html, $content);
			break;
	}

    return $content;
}
add_filter('the_content', 'mygpGeotagsGeoMetatags_addMap');



/**
 * Add microformats to post
 *
 */
function mygpGeotagsGeoMetatags_addMicroformats($content) {

	global $wp_query, $mygpGeotagsGeoMetatags_key;
	
	$postId = $wp_query->post->ID;
	$data = get_post_meta($postId, $mygpGeotagsGeoMetatags_key, true);
	
	$dataSplitted = "";            
	if ($data[ 'position' ] != "") {
		$dataSplitted = explode(";", $data[ 'position' ]);
	} else {
		return $content;		
	}
	
	if (mygpGeotagsGeoMetatags_getOption("addMicroformats") == 'false') {
		return $content;
	}

	// http://microformats.org/wiki/geo
	// http://www.google.com/support/webmasters/bin/answer.py?hl=en&answer=146861
	// TODO: http://schema.org/GeoCoordinates 
	// TODO: http://www.data-vocabulary.org/Geo/ 
	$content = $content . '
<div id="geo-post-' . $postId . '" class="geo geo-post" style="display:none">
   <span class="latitude" title="' . $dataSplitted[0] . '">
      ' . $dataSplitted[0] . '
      <span class="value-title" title="' . ($dataSplitted[0]) . '"></span>
   </span>
   <span class="longitude" title="' . $dataSplitted[1] . '">
      ' . $dataSplitted[1] . '
      <span class="value-title" title="' . ($dataSplitted[1]) . '"></span>
   </span>
</div>';
		
    return $content;
}
add_filter('the_content', 'mygpGeotagsGeoMetatags_addMicroformats');



/**
 * Return plugin options.
 *
 */
function mygpGeotagsGeoMetatags_getOption($option, $postId = '') {
	
	global $mygpGeotagsGeoMetatags_key;
	
	if ($postId != '') {
	
		$data = get_post_meta($postId, $mygpGeotagsGeoMetatags_key, true);
		
		if ($data[$option] != '') {
			
			return $data[$option];
			
		}
		
	}
    
    $value = get_option('mygpGeotagsGeoMetatags_' . $option);
    
    if (isset($value['text_string'])) {
    	
    	if (is_bool($value['text_string']) && $value['text_string']) {
    		return "true";    		
    	}
    	
    	if (is_bool($value['text_string']) && !$value['text_string']) {
    		return "false";    		
    	}
    	
    	return $value['text_string'];
    	
    } else {
    	
    	switch($option) {
    		case 'autolocate';
    		  return 'true';
    		  break;
    		case 'addMetatags';
    		  return 'true';
    		  break;
    		case 'addPosttags';
    		  return 'true';
    		  break;
    		case 'addCityToPosttags';
    		  return 'true';
    		  break;
    		case 'addFeedtags';
    		  return 'true';
    		  break;
    		case 'addMicroformats';
    		  return 'true';
    		  break;
    		case 'addMap';
    		  return 'true';
    		  break;
    		case 'mapLocation';
    		  return 'after';
    		  break;
    		case 'mapWidth';
    		  return '100%';
    		  break;
    		case 'mapHeight';
    		  return '200px'; 
    		  break;
    		case 'mapFloat';
    		  return 'none';
    		  break;
    		case 'mapType';
    		  return 'ROADMAP';
    		  break;
    		case 'zoomLevel';
    		  return '12';
    		  break;
    	}
    	
    }
	
}

?>