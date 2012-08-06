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
// The Widget class
if (!class_exists("CustomTypeRecentPostsWidget")) {

class CustomTypeRecentPostsWidget {
	var $options;
	
	/**
	* Constructor
	*/
	function CustomTypeRecentPostsWidget() {
		$this->load_options();
	}
		
	/**
	* Function to register the Widget functions
	*/
	function register_widget() {
		$name = __('Custom Type Recent Posts', CUSTOMTYPE_RECENT_POSTS_NAME);
		$control_ops = array(
			'width' => 400, 'height' => 350, 
			'id_base' => 'enh-rp');
		$widget_ops = array(
			'classname' => 'cuty_rp', 
			'description' => __('Widget to select which custom type on "Recent Posts" widget.', 
								CUSTOMTYPE_RECENT_POSTS_NAME));

		if (!is_array($this->options)) {
			$this->options = array();
		}
								
		$registered = false;
		foreach (array_keys($this->options) as $o) {
			// Old widgets can have null values for some reason
			//--
			if (	!isset($this->options[$o]['posts_to_show']))
				continue;
			
			// $id should look like {$id_base}-{$o}
			//--
			$id = "enh-rp-$o";
			$registered = true;
			wp_register_sidebar_widget( 
				$id, $name, 
				array(&$this, 'render_widget'), 
				$widget_ops, array( 'number' => $o ) );
			wp_register_widget_control( 
				$id, $name, 
				array(&$this, 'render_control_panel'), 
				$control_ops, array( 'number' => $o ) );
		}

		// If there are none, we register the widget's existance with a generic template
		//--
		if (!$registered) {
			wp_register_sidebar_widget( 
				'enh-rp-1', $name, 
				array(&$this, 'render_widget'), 
				$widget_ops, array( 'number' => -1 ) );
			wp_register_widget_control( 
				'enh-rp-1', $name, 
				array(&$this, 'render_control_panel'), 
				$control_ops, array( 'number' => -1 ) );
		}
	}
	
	/**
	* Function to render the widget control panel
	*/
	function render_control_panel($widget_args=1) {
		global $wp_registered_widgets;
		static $updated = false;
		
		// Get the widget ID
		//--
		if (is_numeric($widget_args)) {
			$widget_args = array('number' => $widget_args);
		}
		$widget_args = wp_parse_args($widget_args, array('number' => -1));
		extract($widget_args, EXTR_SKIP);
	
		if (!$updated && !empty($_POST['sidebar'])) {
			$sidebar = (string) $_POST['sidebar'];

			$sidebars_widgets = wp_get_sidebars_widgets();
			if ( isset($sidebars_widgets[$sidebar]) )
				$this_sidebar = &$sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();

			foreach ( $this_sidebar as $_widget_id ) {
				// Remove all widgets of this type from the sidebar.  We'll add the new data in a second.  This makes sure we don't get any duplicate data
				// since widget ids aren't necessarily persistent across multiple updates
				//--
				if (	'cuty_rp' == $wp_registered_widgets[$_widget_id]['classname'] 
					&& 	isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
					
					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
					if (!in_array( "enh-rp-$widget_number", $_POST['widget-id'])) // the widget has been removed.
						unset($this->options[$widget_number]);
				}
			}

			foreach ( (array) $_POST['widget_cuty_rp'] as $widget_number => $widget_cuty_rp ) {
				if ( !isset($widget_cuty_rp['posts_to_show']) && isset($this->options[$widget_number]) ) // user clicked cancel
					continue;
					
				$this->options[$widget_number]['widget_title'] 	= strip_tags(stripslashes($widget_cuty_rp['widget_title']));
				$this->options[$widget_number]['custom_post_type']	= strip_tags(stripslashes($widget_cuty_rp['custom_post_type']));
				$this->options[$widget_number]['posts_to_show']	= $widget_cuty_rp['posts_to_show'];
				$this->options[$widget_number]['orderby']		= $widget_cuty_rp['orderby'];
			}

			$this->save_options();
			$updated = true;
		}

		if ( -1 == $number ) {
			$widget_title 	= '';
			$posts_to_show 	= 5;
			$custom_post_type 	= '';
			$number 		= '%i%';
		} else {
			$widget_title 	= attribute_escape($this->options[$number]['widget_title']);
			$posts_to_show 	= $this->options[$number]['posts_to_show'];
			$custom_post_type 	= $this->options[$number]['custom_post_type'];
			$orderby 		= $this->options[$number]['orderby'];
		}
		
		if ($posts_to_show<1) {
			$posts_to_show = 1;
		}

		// The widget control
		//--
		
	?>
	
<input type="hidden" id="cuty_rp-submit-<?php echo $number; ?>" name="widget_cuty_rp[<?php echo $number; ?>][submit]" value="1" />
<p>
	<label><?php _e('Title:', CUSTOMTYPE_RECENT_POSTS_NAME); ?><br/>
	<input style="width: 250px;" id="cuty_rp-widget_title-<?php echo $number; ?>" name="widget_cuty_rp[<?php echo $number; ?>][widget_title]" type="text" value="<?php echo $widget_title; ?>" /></label>
</p>

<br/>

<p>
	<label><?php _e('Number of posts to show:', CUSTOMTYPE_RECENT_POSTS_NAME); ?>
	<br/>
	<input style="width: 250px;" id="cuty_rp-posts_to_show-<?php echo $number; ?>" name="widget_cuty_rp[<?php echo $number; ?>][posts_to_show]" type="text" value="<?php echo $posts_to_show; ?>" /></label>
</p>

<p>
	<label><?php _e('Custom post type:', CUSTOMTYPE_RECENT_POSTS_NAME); ?>
	<select id="cuty_rp-custom_post_type-<?php echo $number; ?>" name="widget_cuty_rp[<?php echo $number; ?>][custom_post_type]">
		<?php $post_types=get_post_types('','names'); 
		foreach ($post_types as $post_type ) { ?>
			<option value="<?php echo $post_type; ?>" <?php $this->render_selected($custom_post_type==$post_type); ?>><?php echo $post_type; ?></option>
		<?php } ?>
	</select>
</p>

<p>
	<label><?php _e('Order posts by:', CUSTOMTYPE_RECENT_POSTS_NAME); ?><br/>
	<select id="cuty_rp-orderby-<?php echo $number; ?>" name="widget_cuty_rp[<?php echo $number; ?>][orderby]">
		<option value="date" <?php $this->render_selected($orderby=='date'); ?>><?php _e('Publication date', CUSTOMTYPE_RECENT_POSTS_NAME); ?></option>
		<option value="modified" <?php $this->render_selected($orderby=='modified'); ?>><?php _e('Modification date', CUSTOMTYPE_RECENT_POSTS_NAME); ?></option> 
		<option value="rand" <?php $this->render_selected($orderby=='rand'); ?>><?php _e('Random', CUSTOMTYPE_RECENT_POSTS_NAME); ?></option>
	</select>
	</label>
</p>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#cuty_rp-show-select-<?php echo $number; ?>').change(function() {
		if (jQuery(this).val() == 'show-all') {
			jQuery('#include-tab-<?php echo $number; ?>').filter(':visible').slideUp();
			jQuery('#exclude-tab-<?php echo $number; ?>').filter(':visible').slideUp();
		} else if (jQuery(this).val() == 'show-include') {
			jQuery('#include-tab-<?php echo $number; ?>').filter(':hidden').slideDown();
			jQuery('#exclude-tab-<?php echo $number; ?>').filter(':visible').slideUp();
		} else {
			jQuery('#include-tab-<?php echo $number; ?>').filter(':visible').slideUp();
			jQuery('#exclude-tab-<?php echo $number; ?>').filter(':hidden').slideDown();
		}
	}).change();

});
</script>


<?php
	}
	
	/**
	* Function to render the widget
	*/
	function render_widget($args, $widget_args=1) {
		global $cuty_rp_plugin;
		
		// Get the options
		//--
		extract($args, EXTR_SKIP);	
		if (is_numeric($widget_args)) {
			$widget_args = array('number' => $widget_args);
		}
		$widget_args = wp_parse_args($widget_args, array('number' => -1));
		extract($widget_args, EXTR_SKIP);
		
		$title = empty($this->options[$number]['widget_title']) 
					? __('Recent Posts', CUSTOMTYPE_RECENT_POSTS_NAME) 
					: $this->options[$number]['widget_title'];

		echo '<!-- Custom Type Recent Posts ' . $cuty_rp_plugin->options[$number]['active_version'] . ' -->';	
		
		echo $before_widget; 
			echo $before_title . $title . $after_title;
			$cuty_rp_plugin->list_recent_posts($this->options[$number]); 
		echo $after_widget;
		
		echo '<!-- Custom Type Recent Posts ' . $cuty_rp_plugin->current_version . ' -->';
	}
	
	/**
	* Load the options from database (set default values in case options are not set)
	*/
	function load_options() {
		$this->options = get_option(CUSTOMTYPE_RECENT_POSTS_WIDGET_OPTIONS);
		
		if ( !is_array($this->options) ) {
			$this->options = array();
		}
	}
	
	/**
	* Save options to database
	*/
	function save_options() {
		update_option(CUSTOMTYPE_RECENT_POSTS_WIDGET_OPTIONS, $this->options);
	}
	
	/**
	* Helper function to output the checked attribute of a checkbox
	*/
	function render_checked($var) {
		if ($var==1 || $var==true) {
			echo 'checked="checked"';
		}
	}
	
	/**
	* Helper function to output the selected attribute of an option
	*/
	function render_selected($var) {
		if ($var==1 || $var==true) {
			echo 'selected="selected"';
		}
	}
} // class CustomTypeRecentPostsWidget

} // if (!class_exists("CustomTypeRecentPostsWidget"))
//#################################################################



?>