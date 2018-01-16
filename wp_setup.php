<?php
/*
Plugin Name: WordPress Setup
Plugin URI: http://slipfire.com
Description: WordPress setup
Version: 2.0
Author: SlipFire
Author URI: http://slipfire.com
Text Domain: wp-setup
Domain Path: /languages
License: GPLv2
*/


remove_action( 'wp_head', 'wp_generator' ); // WordPress version
remove_action( 'wp_head', 'wlwmanifest_link' ); // Windows Live Writer

add_action( 'admin_menu', 'wp_setup_all_options_menu' );

add_filter( 'admin_bar_menu', 'wp_setup_change_howdy' );

add_action( 'admin_menu', 'wp_setup_register_submenus' );

add_filter( 'admin_footer_text', 'wp_setup_admin_footer_text' );

add_action( 'wp_dashboard_setup', 'wp_setup_add_dashboard_widgets' );

add_filter( 'post_class', 'wp_setup_post_class' );

// add_action('get_header', 'wp_setup_maintenance_mode');  // Uncomment to turn on maintenance mode


function wp_setup_admin_footer_text( $footer_text ) {

  return str_replace( '</a>.', '</a>. ' . wp_setup_support_text(), $footer_text) ;

}

function wp_setup_support_text() {

  $email = 'support@mydomain.com';
  $subject = 'Support Request: ' . home_url();
  $body = 'Request made from page: ' . esc_url($_SERVER['REQUEST_URI']);


  $open_href = '<a href="mailto:' . $email . '?subject=' . $subject . '&amp;body=' . $body . '">';
  $close_href = '</a>';

  return 'Managed by <a href="//slipfire.com">SlipFire</a>. ' . $open_href . 'Email for Support' . $close_href;
}

function wp_setup_post_class( $classes ) {

  global $post;

  if( has_post_thumbnail( $post->ID ) ) {

    $classes[] = 'has_featured_image';

  }

    return $classes;
}


function wp_setup_change_howdy( $wp_admin_bar ) {

  $my_account = $wp_admin_bar->get_node('my-account');

  $wp_admin_bar->add_node(array(
    'id' => 'my-account'
    ,'title' => str_replace('Howdy, ', 'Hi ', $my_account->title)
  ));
}


function wp_setup_all_options_menu() {

  if ( current_user_can( 'manage_options' ) ) {

    global $submenu;

    $submenu['options-general.php'][] = array(
      'All Options',
      'manage_options',
      'options.php'
    );

  }
}

function wp_setup_maintenance_mode() {

  if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'We are currently performing maintenance on this site. Please try again shortly.' );
  }

}


function wp_setup_register_submenus() {
  add_submenu_page( 'tools.php', 'PHP Info', 'PHP Info', 'manage_options', 'wpnyc-phpinfo', 'wp_setup_phpinfo_page_callback');
}

function wp_setup_phpinfo_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php 'PHP Info'; ?></h1>
        <?php wp_setup_clean_phpinfo(); ?>
    </div>

    <?php
}

function wp_setup_clean_phpinfo() {
  ob_start();
  phpinfo();
  $phpinfo = ob_get_contents();
  ob_end_clean();

  $phpinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$phpinfo);
  $phpinfo = str_ireplace('width="600"','class="form-table widefat"',$phpinfo);

  echo $phpinfo;
}


function wp_setup_add_dashboard_widgets() {
  wp_add_dashboard_widget('dashboard_widget', 'Support and Help', 'wp_setup_dashboard_widget_data');
}

function wp_setup_dashboard_widget_data( $post, $callback_args ) {
  echo wp_setup_support_text();
}


function wp_setup_pre( $output ) {

    echo "<pre>\r\n";
    print_r($output);
    echo "</pre>\r\n";

}
