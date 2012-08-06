<?php
//#################################################################
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('You are not allowed to call this page directly.'); 
}
//#################################################################

//#################################################################
// Some constants 
//#################################################################

//#################################################################
// The plugin class
if (!class_exists("CustomTypeRecentPostsPlugin")) {

class CustomTypeRecentPostsPlugin {
	var $current_version = '0.1';
	var $options;
	
	/**
	* Constructor
	*/
	function CustomTypeRecentPostsPlugin() {
		$this->load_options();
	}
	
	/**
	* Function to be called when the plugin is activated
	*/
	function activate() {
		global $cuty_rp_widget;
		
		$active_version = $this->options['active_version'];
		
		if ($active_version==$this->current_version) {
			// do nothing
		} else {
			if ($active_version=='') {			
				add_option(CUSTOMTYPE_RECENT_POSTS_PLUGIN_OPTIONS, 
					$this->options, 
					'Custom Type Recent Posts plugin options');
				add_option(CUSTOMTYPE_RECENT_POSTS_WIDGET_OPTIONS, 
					$cuty_rp_widget->options, 
					'Custom Type Recent Posts widget options');
			} 
		}
		
		// Update version number & save new options
		$this->options['active_version'] = $this->current_version;
		$this->save_options();
	}
	
	/**
	* Function that echoes the recent posts
	*/
	function list_recent_posts($args = '') {	
		$defaults = array(
			'posts_to_show'		=> 5,
			'custom_post_type'		=> '',
			'orderby'			=> 'date'
		);
		
		$r = wp_parse_args( $args, $defaults );

		// Build the parameter string for query posts
		//-- post_type
		//$query_param = 'showposts=' . $r['posts_to_show'] . '&what_to_show=posts&nopaging=0&post_status=publish&orderby=' . $r['orderby'];
		$query_param = 'showposts=' . $r['posts_to_show'] . '&post_type=' . $r['custom_post_type'] . '&nopaging=0&post_status=publish&orderby=' . $r['orderby'];
		
		// Query the DB
		//--
		$posts = new WP_Query($query_param);
		
		if ($posts->have_posts()) {		
			echo '<ul class="customtype-recent-posts">' . "\n";		
			while ($posts->have_posts()) {
				$posts->the_post();
			
?>
				<li><a href="<?php the_permalink() ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
<?php
			
			}		
			echo '</ul>' . "\n";
		}
		
		// Restore global post data stomped by the_post().
		//--
		wp_reset_query();
	}
	
	/**
	* Load the options from database (set default values in case options are not set)
	*/
	function load_options() {
		$this->options = get_option(CUSTOMTYPE_RECENT_POSTS_PLUGIN_OPTIONS);
		
		if ( !is_array($this->options) ) {
			$this->options = array(
				'active_version'		=> ''
			);
		}
	}
	
	/**
	* Save options to database
	*/
	function save_options() {
		update_option(CUSTOMTYPE_RECENT_POSTS_PLUGIN_OPTIONS, $this->options);
	}
	
} // class CustomTypeRecentPostsPlugin
} // if (!class_exists("CustomTypeRecentPostsPlugin"))
	
?>