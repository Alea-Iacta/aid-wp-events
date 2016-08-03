<?php
/*
Plugin Name: alea iacta - Events
Description: A Plugin to manage and display events
Version: 0.1
Author: Florian Bentele
Author URI: http://aid.sg
License: GPLv2
 
 	Copyright 2016  Florian Bentele

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

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
define("AID_EVENTS_PLUGIN", plugins_url('', __FILE__));

add_action( 'admin_menu', 'aid_admin_menu' );
add_action( 'init', 'aid_add_post_type' );
add_action( 'save_post', 'aid_save_custom_fields' );
add_action( 'admin_enqueue_scripts', 'aid_add_datepicker_script' );


function aid_admin_menu() {
	add_menu_page( 'Events', 'Events', 'publish_posts', 'events', 'aid_admin_page');
}

function aid_admin_page(){
	echo "<h1>Hello World</h1>";
}


function aid_add_post_type() {
  register_post_type( 'aid_event',
    array(
      'labels' => array(
        'name' => __( 'Events' ),
        'singular_name' => __( 'Event' )
      ),
      'menu_position' => 5,
      'menu_icon' => 'dashicons-calendar-alt',
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'events'),
    )
  );
  
  add_meta_box('start-date', 'Startdatum', 'aid_startdate', 'aid_event', 'side', 'low');
}


function aid_startdate(){
	global $post;
	$custom = get_post_custom($post->ID);
	?>
	<p><label>Startdatum:</label><br>
	<input class="datepicker" name="start-date" value="<?php echo $custom['start-date'][0]; ?>" />
	</p>
	<p>
	<label>Enddatum:</label><br>
	<input class="datepicker" name="end-date" value="<?php echo $custom['end-date'][0]; ?>" />
	</p>
	<?php
}

function aid_save_custom_fields(){
	global $post;
	update_post_meta($post->ID, "start-date", $_POST["start-date"]);
	update_post_meta($post->ID, "end-date", $_POST["end-date"]);
}

function aid_add_datepicker_script(){
	wp_enqueue_script( 'aid-datepicker', AID_EVENTS_PLUGIN . '/aid-datepicker.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), time(), true);
	wp_enqueue_style( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'aid-admin-style', AID_EVENTS_PLUGIN . '/aid-admin-styles.css' );
}
