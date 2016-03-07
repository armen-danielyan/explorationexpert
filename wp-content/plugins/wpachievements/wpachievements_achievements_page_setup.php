<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function achievement_page_redirect() {
  global $wp,$wpdb;
  if(function_exists('is_multisite') && is_multisite()){
    $wpa_page_id = get_blog_option(1,'wpachievements_ach_page');
  } else{
    $wpa_page_id = get_option('wpachievements_ach_page');
  }
  if(!empty($wpa_page_id) && $wpa_page_id != '' && $wpa_page_id != 'None' && $wpa_page_id != 'none'){
    if (is_page($wpa_page_id)) {
      if ( file_exists(get_template_directory().'/wpachievements_achievements_page.php') ) {
        $return_template = get_template_directory().'/wpachievements_achievements_page.php';
        wpa_do_theme_redirect($return_template);
      } else{
        $plugindir = dirname( __FILE__ );
        $return_template = $plugindir.'/wpachievements_achievements_page.php';
        wpa_do_theme_redirect($return_template);
      }
    }
  }
} add_action("template_redirect", 'achievement_page_redirect');
function wpa_do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    }
}
?>