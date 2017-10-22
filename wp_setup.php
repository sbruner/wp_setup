<?php
/*
Plugin Name: WordPress Setup
Plugin URI: http://slipfire.com
Description: WordPress setup
Version: 1.0
Author: SlipFire
Author URI: http://slipfire.com
Text Domain: wp-setup
Domain Path: /languages
License: GPLv2
*/


remove_action( 'wp_head', 'wp_generator' ); // WordPress version
remove_action( 'wp_head', 'wlwmanifest_link' ); // Windows Live Writer

add_filter( 'admin_footer_text', 'wp_setup_admin_footer_text' );

add_filter('post_class', 'wp_setup_post_class');

add_filter( 'admin_bar_menu', 'wp_setup_change_howdy' );

add_action( 'admin_menu', 'wp_setup_all_options_menu' );

add_action('get_header', 'wp_setup_maintenance_mode');



function wp_setup_admin_footer_text($footer_text) {

  return str_replace('</a>.', '</a>. Managed by <a href="//slipfire.com">SlipFire</a>', $footer_text);

}

function wp_setup_post_class($classes) {

  global $post;

	if( has_post_thumbnail($post->ID) ) {
    $classes[] = 'has_featured_image';
  }

		return $classes;
}


function wp_setup_change_howdy($wp_admin_bar) {

  $my_account = $wp_admin_bar->get_node('my-account');

  $wp_admin_bar->add_node(array(
    'id' => 'my-account'
    ,'title' => str_replace('Howdy, ', 'Hi ', $my_account->title)
  ));
}


function wp_setup_all_options_menu() {

  if (current_user_can('manage_options')) {

    global $submenu;

    $submenu['options-general.php'][] = array(
      'All Options',
      'manage_options',
      'options.php'
    );

  }
}

function wp_setup_maintenance_mode() {

  if (!current_user_can('manage_options') ) {
    wp_die('We are currently performing maintenance on this site. Please try again shortly.');
  }

}

function wp_setup_pre( $output ) {

    echo "<pre>\r\n";
    print_r($output);
    echo "</pre>\r\n";

}
