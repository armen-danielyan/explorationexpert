<?php

add_action('init','wpach_of_options');

if (!function_exists('wpach_of_options')) {
function wpach_of_options(){

//Theme Shortname
$shortname = "wpachievements";


//Populate the options array
global $tt_options;
$tt_options = get_option('wpach_of_options');


//Access the WordPress Pages via an Array
$tt_pages = array();
$tt_pages_obj = get_pages( array( 'sort_column' => 'post_title' ) );
foreach ($tt_pages_obj as $tt_page) {
  $tt_pages[$tt_page->ID] = $tt_page->post_title;
}
$tt_pages_tmp = array_unshift($tt_pages, "None");


//Access the WordPress Categories via an Array
$tt_categories = array();
$tt_categories_obj = get_categories('hide_empty=0');
foreach ($tt_categories_obj as $tt_cat) {
$tt_categories[$tt_cat->cat_ID] = $tt_cat->cat_name;}
$categories_tmp = array_unshift($tt_categories, "Select a category:");

//Sample Advanced Array - The actual value differs from what the user sees
$sample_advanced_array = array("image" => "The Image","post" => "The Post");

//Folder Paths for "type" => "images"
$sampleurl =  plugins_url('wpachievements/admin/images/sample-layouts/');

//User Roles Array
$roles = array("Administrator","Editor","Author","Contributor");

//Yes or No Array
$yesno = array("Yes","No");

//Enable or Disable Array
$enabledisable = array("Enable","Disable");

/*-----------------------------------------------------------------------------------*/
/* Create The Custom Site Options Panel
/*-----------------------------------------------------------------------------------*/
$options = array(); // do not delete this line - sky will fall

/* Option Page 1 - General Settings */
$options[] = array( "name" => __('General Settings', WPACHIEVEMENTS_TEXT_DOMAIN),
      "type" => "heading");

$options[] = array( "name" => __('Purchase Code',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('This is required to get future updates.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_license_key",
      "std" => "",
      "type" => "text");

$options[] = array( "name" => __('User Role', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Select the minimum user role that can modify WPAchievements.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_role",
      "std" => "Administrator",
      "type" => "select",
      "options" => $roles);

$options[] = array( "name" => __('Hide Admins from Leaderboard', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => '<small>'.__('Select whether to hide admins from the leaderboards.', WPACHIEVEMENTS_TEXT_DOMAIN).'</small>',
      "id" => $shortname."_hide_admin",
      "std" => "",
      "type" => "checkbox");

$options[] = array( "name" => __('Achievements Page', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Select which page to use to display the custom Achievements page.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_ach_page",
      "std" => "None",
      "type" => "select",
      "options" => $tt_pages);


$options = apply_filters('wpachievements_admin_general_settings', $options, $shortname);


$options[] = array( "name" => __('Hide Similar Achievements', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => '<small>'.__('Example: If user has achievement for 10 comments and for 20 comments, only 20 comment achievement will be shown in widgets, shortcodes etc.', WPACHIEVEMENTS_TEXT_DOMAIN).'</small>',
      "id" => $shortname."_sim_ach",
      "std" => "",
      "type" => "checkbox");

$options[] = array( "name" => __('RTL Language', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Select whether you are translating the plugin into a RTL Language.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_rtl_lang",
      "std" => "",
      "type" => "checkbox");


/* Option Page 2 - Achievements Popup */
$options[] = array( "name" => __('Achievement Popup', WPACHIEVEMENTS_TEXT_DOMAIN),
      "type" => "heading");

$options[] = array( "name" => __('Popup Automatic Checks', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Choose the number of seconds in between automatic checks for achievements. (Enter 0 to disable)  <strong>NOTE:</strong> This can cause speed issues.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_pcheck",
      "std" => "5",
      "type" => "text");

$options[] = array( "name" => __('Show Sharing Buttons', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Select whether to show the Facebook and Twitter buttons.<br/><strong>NOTE:</strong> Facebook App info must be entered.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_pshare",
      "std" => "",
      "type" => "checkbox");

$options[] = array( "name" => __('Popup Time', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Enter the number of seconds before the popup box fades away.<br/>(Enter 0 to disable fade away)', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_ptim",
      "std" => "0",
      "type" => "text");

$options[] = array( "name" => __('Popup background Color', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Choose the colour of the popup box.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_pcol",
      "std" => "#333333",
      "type" => "color");

if(function_exists('is_multisite') && is_multisite()){
  global $wpdb;

  $query = "SELECT * FROM {$wpdb->blogs}, {$wpdb->registration_log}
  WHERE site_id = '{$wpdb->siteid}'
  AND {$wpdb->blogs}.blog_id = {$wpdb->registration_log}.blog_id";

  $blog_list = $wpdb->get_results( $query, ARRAY_A );

  $pi = array();

  $blog_list[-1] = array( 'blog_id' => 1 );
  ksort($blog_list);
  foreach( $blog_list as $k => $info ) {
    $bid = $info['blog_id'];
    $pi[ $bid ] = get_blog_option( $bid, 'active_plugins' );
  }
  $pi = array_filter($pi);
  $active = array();
  foreach($pi as $k => $v_array) {
    $active = array_merge($active, $v_array);
  }

} else{
  $active = get_option('active_plugins');
}

/* Option Page 3 - Facebook Settings */
$options[] = array( "name" => __('Facebook Settings', WPACHIEVEMENTS_TEXT_DOMAIN),
      "type" => "heading");

$options[] = array( "name" => __('Important Information!', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => "",
      "id" => $shortname."_sample_callout",
      "std" => 'For the Facebook capabilities to work correctly you need to create a <a href="https://developers.facebook.com/" target="_blank">Facebook Application</a><br /><br />To do so, follow these steps:<ul><li><span>Click on "My Apps" -> "Add a New App" and select "Website"</span></li><li><span>Enter your App name (e.g WPAchievements) and click on "Create New Facebook App ID"</span></li><li><span>On Category select "Apps for pages" and click "Create App-ID"</span></li><li><span>Scroll to section "Tell us about your website" and enter your site URL: <strong>'.home_url().'</strong></span></li><li><span>Scroll to "Next Steps" and click on "Skip to Developer Dashboard"</span></li><li><span>Copy the values from the field: App ID/ and App Secret, and enter them below:</span></li></ul><br />Check our detailed documentation if you have difficulties to follow the steps above. There you will find screenshots for every single step.<br />',
      "type" => "info");

$options[] = array( "name" => __('Facebook App ID/API Key', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Enter the App ID/API Key from your Facebook App.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_appID",
      "std" => "",
      "type" => "text");

$options[] = array( "name" => __('Facebook App Secret', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Enter the App Secret from your Facebook App.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_appSecret",
      "std" => "",
      "type" => "text");

/* Option Page 4 - Points Settings */
$options[] = array( "name" => __('Point Settings', WPACHIEVEMENTS_TEXT_DOMAIN),
      "type" => "heading");

if( function_exists(WPACHIEVEMENTS_CUBEPOINTS) || in_array('cubepoints/cubepoints.php',$active) ){

$options[] = array( "name" => __('Important Information!', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => "",
      "id" => $shortname."_sample_callout",
      "std" => '<strong>CubePoints will overwrite some of these settings.</strong> <br/>To get the best from WPAchievements you should consider deactivating CubePoints.<br/>',
      "type" => "info");

}


if( function_exists(WPACHIEVEMENTS_MYCRED) || in_array('mycred/mycred.php',$active) ){

$options[] = array( "name" => __('myCRED Points Setting',WPACHIEVEMENTS_TEXT_DOMAIN),
      "class" => "separator first",
      "type" => "separator");
$options[] = array( "name" => __('myCRED Points Type',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Enter the unique id of the point type you wish to use.<br/><strong>Note:</strong> Leave blank to use default.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_mycred_point_type",
      "std" => "",
      "type" => "text");

}


if( class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR) ){

$options[] = array( "name" => __('WooCommerce Points and Rewards',WPACHIEVEMENTS_TEXT_DOMAIN),
      "class" => "separator first",
      "type" => "separator");

$options[] = array( "name" => __('Sync All Points', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('This will make all points gained be added to WooCommerce Points and Rewards.<br/><strong>Note:</strong> This is not recommeded as user can gain points quickly.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_wcpr_sync_enabled",
      "std" => "",
      "type" => "checkbox");

}

$options = apply_filters('wpachievements_admin_settings', $options, $shortname);


/* Option Page 5 - Trigger Validation */
$options[] = array( "name" => __('Trigger Validation', WPACHIEVEMENTS_TEXT_DOMAIN),
      "type" => "heading");

$options = apply_filters('wpachievements_admin_ach_extra', $options, $shortname);


update_option('wpach_of_template',$options);
update_option('wpach_of_shortname',$shortname);

}
}
?>