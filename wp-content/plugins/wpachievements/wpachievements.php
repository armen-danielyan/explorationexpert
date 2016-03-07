<?php
/**
 * Plugin Name: WPAchievements
 * Plugin URI: http://wpachievements.net
 * Description: Plugin for setting up, controlling and displaying user rankings, points and achievements.
 * Author: Powerfusion, Digital Builder
 * Version: 7.30
 * Author URI: http://wpachievements.net
 */
/**
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 add_action('init', 'wpa_text_domain');
 //*************** Include Localization ***************\\
 function wpa_text_domain(){
 load_plugin_textdomain( 'wpachievements', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');
 }

 //*************** Setup Definations ***************\\
 define( 'WPACHIEVEMENTS_TEXT_DOMAIN', 'wpachievements' );
 define( 'WPACHIEVEMENTS_MYARCADE', 'F241D3F7A06D35E769A9D67F9524C4CD1' );
 define( 'WPACHIEVEMENTS_MYARCADE_ALT', 'myarcade_init' );
 define( 'WPACHIEVEMENTS_CUBEPOINTS', 'cp_getPoints' );
 define( 'WPACHIEVEMENTS_MYCRED', 'mycred_add' );
 define( 'WPACHIEVEMENTS_BUDDYPRESS', 'BP_VERSION' );
 define( 'WPACHIEVEMENTS_BUDDYPRESS_INT', 'cubepoints_bp_profile' );
 define( 'WPACHIEVEMENTS_FAVORITES', 'wpfp_link' );
 define( 'WPACHIEVEMENTS_SCORES', 'myscore_get_todays_scores' );
 define( 'WPACHIEVEMENTS_CONTESTS', 'myarcadecontest_debuglog' );
 define( 'WPACHIEVEMENTS_GFORMS', 'gform_get_meta' );
 define( 'WPACHIEVEMENTS_GDRATING', 'GDStarRating' );
 define( 'WPACHIEVEMENTS_SIMPLEPRESS', 'sp_setup_globals' );
 define( 'WPACHIEVEMENTS_BBPRESS', 'bbPress' );
 define( 'WPACHIEVEMENTS_BUDDYSTREAM', 'buddystream_init' );
 define( 'WPACHIEVEMENTS_WPECOMMERCE', 'WP_eCommerce' );
 define( 'WPACHIEVEMENTS_INVITE_ANYONE', 'invite_anyone_init' );
 define( 'WPACHIEVEMENTS_WOOCOMMERCE', 'Woocommerce' );
 define( 'WPACHIEVEMENTS_LEARNDASH', 'sfwd_lms_has_access' );
 define( 'WPACHIEVEMENTS_JIGOSHOP', 'jigoshop_init' );
 define( 'WPACHIEVEMENTS_WOOCOMMERCE_PAR', 'WC_Points_Rewards' );
 define( 'WPACHIEVEMENTS_WPCOURSEWARE', 'WPCW_plugin_init' );
 define( 'WPACHIEVEMENTS_USERPRO', 'userpro_init' );
 if(function_exists('is_multisite') && is_multisite()){
   global $wpdb;
   define( 'WPACHIEVEMENTS_ACTIVITY_TABLE', $wpdb->get_blog_prefix(1).'wpachievements_activity');
 } else{
   global $wpdb;
   define( 'WPACHIEVEMENTS_ACTIVITY_TABLE', $wpdb->prefix.'achievements');
 }
 define( 'WPACHIEVEMENTS_PATH', plugin_dir_path(__FILE__) );

 if(function_exists(WPACHIEVEMENTS_MYARCADE) || function_exists(WPACHIEVEMENTS_MYARCADE_ALT)){
	define( 'WPACHIEVEMENTS_POST_TEXT', __('Game', WPACHIEVEMENTS_TEXT_DOMAIN) );
 } else{
	define( 'WPACHIEVEMENTS_POST_TEXT', __('Post', WPACHIEVEMENTS_TEXT_DOMAIN) );
 }

 //*************** Register Post Types ***************\\
 $labels = array(
    'name' => __( 'Achievements', WPACHIEVEMENTS_TEXT_DOMAIN ),
    'singular_name' => __( 'Achievement', WPACHIEVEMENTS_TEXT_DOMAIN ),
    'add_new' => __( 'Add New Achievement' , WPACHIEVEMENTS_TEXT_DOMAIN ),
    'add_new_item' => __( 'Add New Achievement' , WPACHIEVEMENTS_TEXT_DOMAIN ),
    'edit_item' =>  __( 'Edit Achievement' , WPACHIEVEMENTS_TEXT_DOMAIN ),
    'new_item' => __( 'New Achievement' , WPACHIEVEMENTS_TEXT_DOMAIN ),
    'view_item' => __('View Achievement', WPACHIEVEMENTS_TEXT_DOMAIN),
    'search_items' => __('Search Achievements', WPACHIEVEMENTS_TEXT_DOMAIN),
    'not_found' =>  __('No Achievements Found', WPACHIEVEMENTS_TEXT_DOMAIN),
    'not_found_in_trash' => __('No Achievements Found in Trash', WPACHIEVEMENTS_TEXT_DOMAIN),
  );
  register_post_type('wpachievements', array(
    'labels' => $labels,
    'public' => false,
    'show_ui' => true,
    'hierarchical' => true,
    'rewrite' => false,
    'query_var' => "wpachievements",
    'supports' => array(
      'title'
    ),
    'show_in_menu'  => false,
  ));

  $labels = array(
    'name' => __( 'Quests', WPACHIEVEMENTS_TEXT_DOMAIN ),
    'singular_name' => __( 'Quest', WPACHIEVEMENTS_TEXT_DOMAIN ),
    'add_new' => __( 'Add New Quest' , WPACHIEVEMENTS_TEXT_DOMAIN ),
    'add_new_item' => __( 'Add New Quest' , WPACHIEVEMENTS_TEXT_DOMAIN ),
    'edit_item' =>  __( 'Edit Quest' , WPACHIEVEMENTS_TEXT_DOMAIN ),
    'new_item' => __( 'New Quest' , WPACHIEVEMENTS_TEXT_DOMAIN ),
    'view_item' => __('View Quest', WPACHIEVEMENTS_TEXT_DOMAIN),
    'search_items' => __('Search Quests', WPACHIEVEMENTS_TEXT_DOMAIN),
    'not_found' =>  __('No Quests Found', WPACHIEVEMENTS_TEXT_DOMAIN),
    'not_found_in_trash' => __('No Quests Found in Trash', WPACHIEVEMENTS_TEXT_DOMAIN),
  );
  register_post_type('wpquests', array(
    'labels' => $labels,
    'public' => false,
    'show_ui' => true,
    'hierarchical' => true,
    'rewrite' => false,
    'query_var' => "wpquests",
    'supports' => array(
      'title'
    ),
    'show_in_menu'  => false,
  ));

 //*************** Setup Install Data ***************\\
 function wpachievements_data_install(){

  global $wpdb,$wpa_post_id;

  if(function_exists('is_multisite') && is_multisite()){
    $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
    add_blog_option(1,'wpachievements_ranks_data', array(0=>__('Newbie',WPACHIEVEMENTS_TEXT_DOMAIN)));
  } else{
    $table = $wpdb->prefix.'achievements';
    add_option('wpachievements_ranks_data', array(0=>__('Newbie',WPACHIEVEMENTS_TEXT_DOMAIN)));
  }

  if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
   $sql =
   "CREATE TABLE " . $table . " (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    uid bigint(20) NOT NULL,
    type VARCHAR(256) NOT NULL,
    rank TEXT NOT NULL,
    data TEXT NOT NULL,
    points bigint(20) NOT NULL,
    postid bigint(20) NOT NULL,
    UNIQUE KEY id (id)
   );";
   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
  }

  /*if(function_exists('is_multisite') && is_multisite()){
    update_blog_option(1, 'wpachievements_important_notice_status', '');
  } else{
    update_option('wpachievements_important_notice_status', '');
  }*/

 } register_activation_hook( __FILE__, 'wpachievements_data_install' );

 $mainplugindir = dirname( __FILE__ );
 require_once($mainplugindir.'/wpachievements_setup.php');
 require_once($mainplugindir.'/wpachievements_widget.php');

 global $cp_module;
 if($cp_module){
  if(in_array_r('BuddyPress Integration', $cp_module)){
    function remove_dup() {
      global $wp_admin_bar;
      $wp_admin_bar->remove_menu('my-points');
    } add_action( 'wp_before_admin_bar_render', 'remove_dup' );
  }

 }

 //*************** Setup Uninstall Data ***************\\
 function wpachievements_data_uninstall(){

   global $wpdb;

   if(function_exists('is_multisite') && is_multisite()){
     $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
     delete_blog_option(1,'wpachievements_achievements_data');
     delete_blog_option(1,'wpachievements_ranks_data');
     delete_blog_option(1,'wpach_of_template');
     delete_blog_option(1,'wpach_of_shortname');
   } else{
     $table = $wpdb->prefix.'achievements';
     delete_option('wpachievements_achievements_data');
     delete_option('wpachievements_ranks_data');
     delete_option('wpach_of_template');
     delete_option('wpach_of_shortname');
   }

   $wpdb->query( "DROP TABLE $table" );
   $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE `achievements_count`" );
   $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE `achievements_gained`" );

 } register_uninstall_hook( __FILE__, 'wpachievements_data_uninstall' );


 if(!function_exists('get_user_id_from_string')){
   function get_user_id_from_string( $string ) {
   $user_id = 0;
   if ( is_email( $string ) ) {
    $user = get_user_by('email', $string);
    if ( $user ){
      $user_id = $user->ID;
    }
   } elseif ( is_numeric( $string ) ) {
    $user_id = $string;
   } else {
    $user = get_user_by('login', $string);
    if ( $user ){
      $user_id = $user->ID;
    }
   }
   return $user_id;
   }
 }

 if( get_bloginfo('version') >= 3.6 ){
  require 'update/update.php';
  $WPAchievementsUpdates = PucFactory::buildUpdateChecker('http://api.wpachievements.net/update/?action=get_metadata&slug=wpachievements', __FILE__, 'wpachievements');
  $WPAchievementsUpdates->addQueryArgFilter('wpachievements_license');
  function wpachievements_license($queryArgs) {
   $settings = get_option('wpachievements_license_key');
   if ( !empty($settings) ) {
     $queryArgs['license_key'] = $settings;
   }
   $queryArgs['site_url'] = home_url();
   return $queryArgs;
  }
 }
?>