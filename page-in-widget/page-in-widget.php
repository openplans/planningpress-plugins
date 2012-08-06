<?php
/**
 * @package Page_in_widget
 * @version 1.1
 */
/*
  Plugin Name: Page in Widget
  Plugin URI: http://carl-fredrik.net/wordpress/page-in-widget.html
  Description: Displays a page content in a widget
  Version: 1.1
  Author: Carl-Fredrik Herö
  Author URI: http://carl-fredrik.net
  License: GPL2
 */

/*  Copyright 2010  Carl-Fredrik Herö (carlfredrik.hero@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * page_in_widget_Widget Class
 */
class page_in_widget_Widget extends WP_Widget {

	/** constructor */
	function page_in_widget_Widget() {
		parent::WP_Widget(false, 'Page in widget', array('description' => 'Displays a page content in a widget'));
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$page_id = (int) $instance['page_id'];
		$more = (int) $instance['more'];

		if(!$page_id){
			echo 'Page in widget:: No Page id set.';
			return;
		}

        echo $before_widget;

		if ($title) {
			echo $before_title . $title . $after_title;
		}

		$page = get_page($page_id, OBJECT, 'display');
		$content = apply_filters('the_content', $this->get_the_content($page, $more));

		echo $content;

		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_id'] = (int) $new_instance['page_id'];
		$instance['more'] = (int) $new_instance['more'];
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		$title = '';
		$page_id = 0;
		$checked = '';

		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}

		if (isset($instance['page_id'])) {
			$page_id = (int) esc_attr($instance['page_id']);
		}

		if(isset($instance['more'])){
			if($instance['more'] == 1){
				$checked = 'checked="checked"';
			}
		}

		$pageIdArgs = array(
			'selected' => $page_id,
			'name' => $this->get_field_name('page_id'),
		);
?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><?php wp_dropdown_pages($pageIdArgs); ?></p>
		<p><label for="<?php echo $this->get_field_id('more'); ?>"><input id="<?php echo $this->get_field_id('more'); ?>" name="<?php echo $this->get_field_name('more'); ?>" type="checkbox" value="1" <?php echo $checked; ?> /> <?php _e('Show more link'); ?></label></p>
<?php
	}

	/* Local version of get_the_content function,
	 * adapted to suit the widget
	 */
	function get_the_content($post, $more = 1) {
		global $preview;

		$more_link_text = __( '(more...)' );

		if(!$more){
			$more_link_text = '';
		}
		$output = '';

		$id = $post->ID;

		$content = $post->post_content;

		if ( preg_match('/<!--more(.*?)?-->/', $content, $matches) ) {
			$content = explode($matches[0], $content, 2);
			if ( !empty($matches[1]) && !empty($more_link_text) ) {
				$more_link_text = strip_tags(wp_kses_no_null(trim($matches[1])));
			}
		} else {
			$content = array($content);
		}

		$output .= $content[0];
		if ( count($content) > 1 ) {

			if ( ! empty($more_link_text) ) {
				$output .= apply_filters( 'the_content_more_link', ' <a href="' . get_permalink($id) . "#more-$id\" class=\"more-link\">$more_link_text</a>", $more_link_text );
			}

			$output = force_balance_tags($output);

		}
		if ( $preview ) // preview fix for javascript bug with foreign languages
			$output =	preg_replace_callback('/\%u([0-9A-F]{4})/', create_function('$match', 'return "&#" . base_convert($match[1], 16, 10) . ";";'), $output);

		return $output;
	}

}

// class page_in_widget_Widget
// register page_in_widget
add_action('widgets_init', create_function('', 'return register_widget("page_in_widget_Widget");'));