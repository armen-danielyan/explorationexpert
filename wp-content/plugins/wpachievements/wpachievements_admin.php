<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 ***********************************************************
 *   W P A C H I E V E M E N T S   A D M I N   S E T U P   *
 ***********************************************************
 */
 //*************** Setup Admin Scripts ***************\\
 add_action( 'admin_enqueue_scripts', 'wpachievements_admin_scripts' );
 add_action( 'admin_head', 'wpachievements_admin_head' );
 function wpachievements_admin_scripts($hook) {
  if( 'update-core.php' == $hook ){
    wp_enqueue_script( 'UpdateScript', plugins_url('update/js/update-script.php', __FILE__), array('jquery') );
  } elseif( 'users.php' == $hook ){
    wp_enqueue_style( 'user_management_style', plugins_url('/css/user-management.css', __FILE__) );
  } elseif( 'user-edit.php' == $hook ){
    wp_enqueue_style( 'JUI', plugins_url('/js/ui-darkness/css/ui-darkness/jquery-ui-1.10.3.custom.css', __FILE__) );
    wp_enqueue_style( 'user_management_style', plugins_url('/css/user-profile.css', __FILE__) );

    wp_enqueue_script( "UIScript", plugins_url("/js/ui-darkness/js/jquery-ui-1.10.3.custom.js", __FILE__) );
    wp_enqueue_script( 'UI_Spinner_Script', plugins_url('/js/ui.spinner.js', __FILE__) );
    wp_register_script( 'user_management_script', plugins_url('/js/user-profile-script.js', __FILE__), array('jquery','media-upload','thickbox') );
    wp_enqueue_media();
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'user_management_script' );
  }

  if( 'toplevel_page_wpachievements_admin' != $hook && 'wpachievements_page_wpachievements_ranks' != $hook && 'wpachievements_page_wpachievements_users' != $hook && 'wpachievements_page_wpachievements_achievements' != $hook && 'wpachievements_page_wpachievements_supported_plugins' != $hook && 'wpachievements_page_wpachievements_faq' != $hook && 'wpachievements_page_wpachievements_documentation' != $hook )
    return;

  if( 'toplevel_page_wpachievements_admin' == $hook ){

  } elseif( 'wpachievements_page_wpachievements_supported_plugins' == $hook ){
    wp_enqueue_style( 'support_admin_style', plugins_url('/css/support-admin.css', __FILE__) );
  } elseif( 'wpachievements_page_wpachievements_faq' == $hook || 'wpachievements_page_wpachievements_documentation' == $hook ){
    wp_enqueue_style( 'wpa_latest_info', plugins_url('/css/info.css', __FILE__) );
    if( get_bloginfo('version') >= 3.8 ){
      wp_enqueue_style( 'wpa_latest_info_3_8', plugins_url('css/info-3.8.css', __FILE__) );
    }
    wp_enqueue_script( 'wpa_latest_info_script', plugins_url('/js/info.js', __FILE__), array('jquery') );
  } else{
    wp_enqueue_style( 'JUI', plugins_url('/js/ui-darkness/css/ui-darkness/jquery-ui-1.10.3.custom.css', __FILE__) );
    wp_enqueue_style( 'UI_Spinner', plugins_url('/css/admin.css', __FILE__) );
    wp_enqueue_style( 'SelectBox', plugins_url('/css/jquery.selectbox.css', __FILE__) );
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_script( "UIScript", plugins_url("/js/ui-darkness/js/jquery-ui-1.10.3.custom.js", __FILE__) );
    wp_enqueue_script( 'UI_Spinner_Script', plugins_url('/js/ui.spinner.js', __FILE__) );
    wp_enqueue_script( 'SelectBox_Script', plugins_url('/js/jquery.selectbox-0.2.js', __FILE__) );
    wp_register_script( 'my-upload', plugins_url('/js/admin-script.js', __FILE__), array('jquery','media-upload','thickbox') );
    wp_enqueue_media();
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'my-upload' );
  }
 }
 function wpachievements_admin_head(){

  wp_register_script( 'wpachievements_admin_menu_script', plugins_url('js/admin-menu-script.js', __FILE__), array('jquery') );
  wp_enqueue_script( 'wpachievements_admin_menu_script' );

  $screen = get_current_screen();

  if( $screen->id == 'edit-wpquests' || $screen->id == 'wpquests'  ){
    wp_register_script( 'wpachievements_admin_menu_active_script', plugins_url('js/admin-menu-script-active.js', __FILE__), array('jquery') );
    wp_enqueue_script( 'wpachievements_admin_menu_active_script' );
  }

  if( $screen->id == 'wpachievements' || $screen->id == 'wpquests' ){
    wp_enqueue_style( 'wpachievements_admin_style', plugins_url('css/admin.css', __FILE__) );
    if( get_bloginfo('version') >= 3.8 ){
      wp_enqueue_style( 'wpachievements_admin_style_3_8', plugins_url('css/admin-3.8.css', __FILE__) );
    }
    wp_register_script( 'wpachievements_admin_script', plugins_url('js/admin-script.js', __FILE__), array('jquery','media-upload','thickbox') );
    wp_enqueue_media();
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'wpachievements_admin_script' );

    add_filter( 'wp_default_editor', 'wpachievements_force_default_editor' );
    wp_dequeue_script( 'autosave' );
  }
 }

 //*************** Setup Admin Menu ***************\\
 if(function_exists('is_multisite') && is_multisite()){
   global $blog_id;
   if($blog_id == 1){
     add_action( 'admin_menu', 'wpachievements_admin_menu' );
   }
 } else{
   add_action('admin_menu', 'wpachievements_admin_menu');
 }
 function wpachievements_admin_menu(){
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    $user_role = get_blog_option(1,'wpachievements_role');
    $rankstatus = get_blog_option(1,'wpachievements_rank_status');
  } else{
    $user_role = get_option('wpachievements_role');
    $rankstatus = get_option('wpachievements_rank_status');
  }
  if($user_role=='Administrator'){$user_role='manage_options';}
  elseif($user_role=='Editor'){$user_role='moderate_comments';}
  elseif($user_role=='Author'){$user_role='edit_published_posts';}
  elseif($user_role=='Contributor'){$user_role='edit_posts';}
  else{$user_role='manage_options';}

  add_utility_page(__("WPAchievements", WPACHIEVEMENTS_TEXT_DOMAIN), __("WPAchievements", WPACHIEVEMENTS_TEXT_DOMAIN), $user_role, 'edit.php?post_type=wpachievements', '', plugins_url('img/logo_small.png', __FILE__) );

  if($rankstatus != 'Disable'){
    add_submenu_page( 'edit.php?post_type=wpachievements', __('WPAchievements - Ranks', WPACHIEVEMENTS_TEXT_DOMAIN), __('Ranks', WPACHIEVEMENTS_TEXT_DOMAIN), $user_role, 'wpachievements_ranks', 'wpachievements_ranks_admin');
  }

  add_submenu_page( 'edit.php?post_type=wpachievements', __('WPAchievements - Settings', WPACHIEVEMENTS_TEXT_DOMAIN), __('Settings', WPACHIEVEMENTS_TEXT_DOMAIN), 'manage_options', 'wpachievements_settings', 'wpachievements_settings_admin');

  /*add_submenu_page( 'edit.php?post_type=wpachievements', __('WPAchievements - Shortcodes', WPACHIEVEMENTS_TEXT_DOMAIN), __('Shortcodes', WPACHIEVEMENTS_TEXT_DOMAIN), 'manage_options', 'wpachievements_documentation', 'wpachievements_documentation');*/


  add_utility_page(__("WPQuests", WPACHIEVEMENTS_TEXT_DOMAIN), __("WPQuests", WPACHIEVEMENTS_TEXT_DOMAIN), $user_role, 'edit.php?post_type=wpquests', '', plugins_url('img/logo_small.png', __FILE__) );

  add_submenu_page( 'edit.php?post_type=wpachievements', __('FAQ', WPACHIEVEMENTS_TEXT_DOMAIN), __('FAQ', WPACHIEVEMENTS_TEXT_DOMAIN), 'manage_options', 'wpachievements_faq', 'wpachievements_faq');

  add_submenu_page( 'edit.php?post_type=wpachievements', __('WPAchievements - Supported Plugins', WPACHIEVEMENTS_TEXT_DOMAIN), __('Supported Plugins', WPACHIEVEMENTS_TEXT_DOMAIN), 'manage_options', 'wpachievements_supported_plugins', 'wpachievements_supported_plugins');

 }

/**
 *****************************************************************
 *   W P A C H I E V E M E N T S   S E T T I N G S   A D M I N   *
 *****************************************************************
 */
 require_once('admin/admin-functions.php');
 require_once('admin/admin-interface.php');
 require_once('admin/admin-settings.php');
 function wpachievements_settings_admin(){
   wpach_of_load_only();
   wpach_of_style_only();
   wpach_siteoptions_options_page();
 }

/**
 ***********************************************************************
 *   W P A C H I E V E M E N T S   A C H I E V E M E N T   A D M I N   *
 ***********************************************************************
 */
 add_action( 'add_meta_boxes', 'wpachievements_add_custom_boxes', 1 );
 add_action( 'save_post', 'wpachievements_save_achievement' );
 add_action( 'save_post', 'wpachievements_save_quest' );

 function wpachievements_add_custom_boxes(){
  add_meta_box(
    'achievement_desc',
    '<strong>'. __( 'Achievement Text', WPACHIEVEMENTS_TEXT_DOMAIN )  .'</strong> - <small>'. __('This text is displayed when a user get the achievement.', WPACHIEVEMENTS_TEXT_DOMAIN).'</small>',
    'wpachievements_desc_box', 'wpachievements', 'normal', 'high'
  );
  add_meta_box(
    'achievement_details',
    '<strong>'. __( 'Achievement Details', WPACHIEVEMENTS_TEXT_DOMAIN )  .'</strong> - <small>'. __('Setup the detials of the achievement.', WPACHIEVEMENTS_TEXT_DOMAIN).'</small>',
    'wpachievements_how_box', 'wpachievements', 'normal', 'high'
  );
  add_meta_box(
    'achievement_image',
    '<strong>'. __( 'Achievement Image', WPACHIEVEMENTS_TEXT_DOMAIN )  .'</strong>',
    'wpachievements_image_box', 'wpachievements', 'side', 'high'
  );

  add_meta_box(
    'achievement_desc',
    '<strong>'. __( 'Quest Text', WPACHIEVEMENTS_TEXT_DOMAIN )  .'</strong> - <small>'. __('This text is displayed when a user get the quest.', WPACHIEVEMENTS_TEXT_DOMAIN).'</small>',
    'wpachievements_desc_box', 'wpquests', 'normal', 'high'
  );
  add_meta_box(
    'achievement_details',
    '<strong>'. __( 'Quest Details', WPACHIEVEMENTS_TEXT_DOMAIN )  .'</strong> - <small>'. __('Setup the detials of the quest.', WPACHIEVEMENTS_TEXT_DOMAIN).'</small>',
    'wpachievements_quest_how_box', 'wpquests', 'normal', 'high'
  );
  add_meta_box(
    'achievement_image',
    '<strong>'. __( 'Quest Image', WPACHIEVEMENTS_TEXT_DOMAIN )  .'</strong>',
    'wpachievements_quest_image_box', 'wpquests', 'side', 'high'
  );
 }

 function wpachievements_desc_box( $post ){
  add_filter( 'wp_default_editor', 'wpachievements_force_default_editor' );
  wp_nonce_field( 'wpachievements_achievement_save', 'wpachievements_achievement_nonce' );
  wp_editor($post->post_content, "achievement_desc_editor", array(
    'media_buttons' => false,
    'textarea_rows' => 5,
    'quicktags' => false,
    'tinymce' => array(
      'theme_advanced_buttons1' => 'bold,italic,underline',
      'theme_advanced_buttons2' => '',
      'theme_advanced_buttons3' => '',
      'theme_advanced_buttons4' => ''
    )
  ));
 }
 function wpachievements_how_box( $post ){
  $cur_rank = get_post_meta( $post->ID, '_achievement_rank', true );
  $cur_trigger = get_post_meta( $post->ID, '_achievement_type', true );
  $cur_points = get_post_meta( $post->ID, '_achievement_points', true );
  $cur_woopoints = get_post_meta( $post->ID, '_achievement_woo_points', true );
  $cur_post = get_post_meta( $post->ID, '_achievement_associated_id', true );
  $cur_occurences = get_post_meta( $post->ID, '_achievement_occurrences', true );
  $cur_order_limit = get_post_meta( $post->ID, '_achievement_woo_order_limit', true );
  $cur_ass_title = get_post_meta( $post->ID, '_achievement_associated_title', true );
  $cur_trigger_id = get_post_meta( $post->ID, '_achievement_trigger_id', true );
  $cur_trigger_desc = get_post_meta( $post->ID, '_achievement_trigger_desc', true );
  $cur_recurring = get_post_meta( $post->ID, '_achievement_recurring', true );
  if( empty($cur_order_limit) ){$cur_order_limit=0;}
  if( empty($cur_points) ){$cur_points=1;}
  if( empty($cur_woopoints) ){$cur_woopoints=0;}
  if( empty($cur_occurences) ){$cur_occurences=1;}

  if(function_exists('is_multisite') && is_multisite()){
    $rankstatus = get_blog_option(1,'wpachievements_rank_status');
    $cur_blog_limit = get_post_meta( $post->ID, '_achievement_blog_limit', true );
  } else{
    $rankstatus = get_option('wpachievements_rank_status');
  }
  if($rankstatus != 'Disable'){
    echo '<span class="pullleft first-select">
      <label for="wpachievements_achievements_data_rank">'.__('Limit to Rank', WPACHIEVEMENTS_TEXT_DOMAIN).':</label><br/>
      <select name="wpachievements_achievements_data_rank" id="wpachievements_achievements_data_rank">';
        if( $cur_rank ){
          if( $cur_rank == 'any' ){$current = 'any';$cur_rank = 'Any Rank';} else{$current = $cur_rank;}
          echo '
          <optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
            <option value="'.$current.'" selected>'.$cur_rank.'</option>
          </optgroup>';
        }
        echo'
        <optgroup label="'.__('Available Ranks', WPACHIEVEMENTS_TEXT_DOMAIN).'">
          <option value="any">'.__('Any Rank', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>';
          if(function_exists('is_multisite') && is_multisite()){
            $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
          } else{
            $ranks = (array)get_option('wpachievements_ranks_data');
          }
          foreach($ranks as $points=>$rank):
            if( is_array( $rank ) ){
              echo '<option value="'.$rank[0].'">'.$rank[0].'</option>';
            } else{
              echo '<option value="'.$rank.'">'.$rank.'</option>';
            }
          endforeach;
        echo '</optgroup>
      </select>
    </span>';
  }
  if( !empty($cur_trigger) ){
    $disabled = ' disabled title="This cannot be changed once the achievement is created."';
  } else{
    $disabled = '';
  }
  if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
    $extra_classes = ' first-select';
  } else{
    $extra_classes = '';
  }
  echo '
  <span class="pullleft'.$extra_classes.'">
    <label for="wpachievements_achievements_data_event">'.__('Trigger Event', WPACHIEVEMENTS_TEXT_DOMAIN).':</label><br/>
    <select id="wpachievements_achievements_data_event" name="wpachievements_achievements_data_event"'.$disabled.'>';
      if( !empty($cur_trigger) ){
        echo '
        <optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
          <option value="'.$cur_trigger.'" selected>'.apply_filters('wpachievements_trigger_description', $cur_trigger).'</option>
        </optgroup>';
      } else{
        echo '<option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
        do_action('wpachievements_admin_events');
      }
      echo '</select>
  </span>';
  if( $cur_recurring == 1 ){
    $checked = ' checked';
  } else{
    $checked = '';
  }
  echo '
  <span class="pullleft wpa_checkbox">
    <label for="wpachievements_achievements_recurring">'.__('Recurring Achievement', WPACHIEVEMENTS_TEXT_DOMAIN).':
    <input type="checkbox" id="wpachievements_achievements_recurring" name="wpachievements_achievements_recurring"'.$checked.' /></label><br/>
  </span>';
  if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
    $cur_first_only = get_post_meta( $post->ID, '_achievement_ld_first_attempt_only', true );
    if( !empty($cur_first_only) && $cur_trigger == 'ld_quiz_perfect' ){
      $show = ' style="display:block !important;"';
    } else{
      $show = '';
    }
    echo '<span id="first_try" class="pullleft"'.$show.'>
      <label for="wpachievements_achievement_ld_first_try">'.__('First Attempt Only', WPACHIEVEMENTS_TEXT_DOMAIN).':</label>
      <select id="wpachievements_achievement_ld_first_try" name="wpachievements_achievement_ld_first_try">';
        if( !empty($cur_first_only) ){
          echo '<optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
            <option value="'.$cur_first_only.'" selected>'.$cur_first_only.'</option>
          </optgroup>';
        }
        echo '<option value="Disabled">'.__('Disabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
        <option value="Enabled">'.__('Enabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
      </select>
    </span>';
  }
  echo '<div class="clear"></div>
  <div id="event_details" style="display:none;">';
    if(function_exists('is_multisite') && is_multisite()){
      echo '<span id="blog_limit" class="pullleft">
        <label for="wpachievements_achievement_blog_limit">'.__('Limit to Blog', WPACHIEVEMENTS_TEXT_DOMAIN).':</label>
        <select id="wpachievements_achievement_blog_limit" name="wpachievements_achievement_blog_limit">';
          if( !empty($cur_blog_limit) ){
            $blog_details = get_blog_details($cur_blog_limit);
            echo '<optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
              <option value="'.$cur_blog_limit.'" selected>'.$blog_details->blogname.'</option>
            </optgroup>';
          }
          $args = array(
            'limit' => 1000,
            'offset' => 0,
          );
          $blog_list = wp_get_sites($args);
          foreach( $blog_list as $blog ){
            $blog_details = get_blog_details($blog['blog_id']);
            echo '<option value="'.$blog['blog_id'].'">'.$blog_details->blogname.'</option>';
          }
          echo '
        </select>
      </span>';
    }
    if( !empty($cur_ass_title) && $cur_trigger == 'cp_bp_group_joined' ){
      $show = ' style="display:block !important;"';
    } else{
      $show = '';
    }
    echo '<span id="ass_title"'.$show.'>
      <label for="wpachievements_achievement_bp_group_title">'.__('Group Title', WPACHIEVEMENTS_TEXT_DOMAIN).': <small>(Optional)</small></label>
      <input type="text" id="wpachievements_achievement_bp_group_title" name="wpachievements_achievement_bp_group_title" value="'.$cur_ass_title.'" />
    </span>';
    if( !empty($cur_post) && ($cur_trigger == 'gform_sub' || $cur_trigger == 'ld_lesson_complete' || $cur_trigger == 'ld_course_complete' || $cur_trigger == 'ld_quiz_pass' || $cur_trigger == 'wpcw_quiz') ){
      if($cur_trigger == 'ld_lesson_complete' || $cur_trigger == 'ld_course_complete'){
        $postid_title = __('Lesson ID', WPACHIEVEMENTS_TEXT_DOMAIN);
      }
      if($cur_trigger == 'ld_course_complete'){
        $postid_title = __('Course ID', WPACHIEVEMENTS_TEXT_DOMAIN);
      }
      if($cur_trigger == 'ld_quiz_pass' || $cur_trigger == 'wpcw_quiz'){
        $postid_title = __('Quiz ID', WPACHIEVEMENTS_TEXT_DOMAIN);
      }
      if($cur_trigger == 'wpcw_module_complete'){
        $postid_title = __('Module ID', WPACHIEVEMENTS_TEXT_DOMAIN);
      }
      if($cur_trigger == 'wpcw_course_complete'){
        $postid_title = __('Course ID', WPACHIEVEMENTS_TEXT_DOMAIN);
      }
      if($cur_trigger == 'gform_sub'){
        $postid_title = __('Form ID', WPACHIEVEMENTS_TEXT_DOMAIN);
      }
      $show = ' style="display:block !important;"';
    } else{
      $postid_title = __('Form ID', WPACHIEVEMENTS_TEXT_DOMAIN);
      $show = '';
    }
    echo '<span id="post_id"'.$show.'>
      <label for="wpachievements_achievements_data_post_id">'.$postid_title.': <small>(Optional)</small></label>
      <input type="text" id="wpachievements_achievements_data_post_id" name="wpachievements_achievements_data_post_id" value="'.$cur_post.'" />
    </span>';
    if( class_exists(WPACHIEVEMENTS_WOOCOMMERCE) ){
      if( !empty($cur_order_limit) ){
        $show = ' style="display:block !important;"';
      } else{
        $show = '';
      }
      echo '
      <span id="woo_order_limit"'.$show.'>
        <label for="wpachievements_achievement_woo_order_limit">'.__('Minimum Order Amount', WPACHIEVEMENTS_TEXT_DOMAIN).': <small>(Optional)</small></label>
        <div class="spinner-holder">
          <div style="position:relative;">
            <span id="wpa_woo_symbol">'.get_woocommerce_currency_symbol().'</span>
            <input type="text" id="wpachievements_achievement_woo_order_limit" name="wpachievements_achievement_woo_order_limit" value="'.$cur_order_limit.'" />
            <ul class="wpmu_spinner_control">
              <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
              <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
            </ul>
          </div>
        </div>
      </span>';
    }
    if( !empty($cur_trigger_id) ){
      $show = ' style="display:block !important;"';
    } else{
      $show = ' style="display:none;"';
    }
    echo '<div id="custom_event_details"'.$show.'>';
      echo '<span>
        <label for="wpachievements_achievements_custom_trigger_id">'.__('Unique Trigger ID', WPACHIEVEMENTS_TEXT_DOMAIN).': &nbsp;&nbsp;<small>'.__('(This must be completey unique and start with a letter!)', WPACHIEVEMENTS_TEXT_DOMAIN).'</small></label>
        <input type="text" id="wpachievements_achievements_custom_trigger_id" name="wpachievements_achievements_custom_trigger_id" value="'.$cur_trigger_id.'" />
      </span>';
      echo '<span>
        <label for="wpachievements_achievements_custom_trigger_desc">'.__('Get this achievement for...', WPACHIEVEMENTS_TEXT_DOMAIN).' &nbsp;&nbsp;<small>'.__('(Example: "adding a comment")', WPACHIEVEMENTS_TEXT_DOMAIN).'</small></label>
        <input type="text" id="wpachievements_achievements_custom_trigger_desc" name="wpachievements_achievements_custom_trigger_desc" value="'.$cur_trigger_desc.'" />
      </span>';
    echo '</div>';

    echo '<label for="wpachievements_achievements_data_event_no">'.__('Number of Occurrences', WPACHIEVEMENTS_TEXT_DOMAIN).':</label>
    <div class="spinner-holder">
      <input type="text" id="wpachievements_achievements_data_event_no" name="wpachievements_achievements_data_event_no" value="'.$cur_occurences.'" />
      <ul class="wpmu_spinner_control">
        <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
        <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
      </ul>
    </div>
    <label for="wpachievements_achievements_data_points">'.__('Points Awarded', WPACHIEVEMENTS_TEXT_DOMAIN).':</label>
    <div class="spinner-holder">
      <input type="text" id="wpachievements_achievements_data_points" name="wpachievements_achievements_data_points" value="'.$cur_points.'" />
      <ul class="wpmu_spinner_control">
        <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
        <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
      </ul>
    </div>';
    if( class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR) ){
      echo '<label for="wpachievements_achievements_data_wc_points">'.__('WooCommerce Points', WPACHIEVEMENTS_TEXT_DOMAIN).': <small>(Optional)</small></label>
      <div class="spinner-holder">
        <input type="text" id="wpachievements_achievements_data_wc_points" name="wpachievements_achievements_data_wc_points" value="'.$cur_woopoints.'" />
        <ul class="wpmu_spinner_control">
          <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
          <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
        </ul>
      </div>';
    }
    echo'

  </div><div class="clear"></div>';
 }
 function wpachievements_image_box( $post ){

  $cur_image = get_post_meta( $post->ID, '_achievement_image', true );

  if( $cur_image ){
    echo '<div id="image_preview_holder"><img src="'.$cur_image.'" alt="Achievement Logo" /><br/><a href="#" id="achievement_image_remove">Remove</a></div>';
  } else{
    echo '<div id="image_preview_holder"></div>';
  }

  echo '<span id="no-image-links"><a href="#" id="achievement_image_pick" class="button button-secondary">Select Image</a> <input id="upload_image" type="text" name="upload_image" value="'.$cur_image.'" /><input class="button button-primary" id="upload_image_button" type="button" value="'.__('Upload Image', WPACHIEVEMENTS_TEXT_DOMAIN).'" /></span>';
  echo '<div id="default-image-selection" style="display:none;">';
  $path = plugin_dir_url(basename(__FILE__)).'wpachievements/img/icons/';
  $handle = opendir(dirname(realpath(__FILE__)).'/img/icons/');
  $ii=0;
  while($file = readdir($handle)){
    if($file !== '.' && $file !== '..'){
      $ii++;
      echo '<span><input type="radio" name="achievement_badge" value="'.$path.$file.'" /><img src="'.$path.$file.'" alt="'.__('Achievement Image', WPACHIEVEMENTS_TEXT_DOMAIN).' '.$ii.'" class="radio_btn" /></span>';
    }
  }
  echo '<div class="clear"></div></div>';

 }


 function wpachievements_quest_how_box( $post ){
  wp_nonce_field( 'wpachievements_quest_save', 'wpachievements_quest_nonce' );
  $cur_details = get_post_meta( $post->ID, '_quest_details', true );
  $cur_rank = get_post_meta( $post->ID, '_quest_rank', true );
  $cur_points = get_post_meta( $post->ID, '_quest_points', true );
  if( empty($cur_points) ){$cur_points=1;}

  if(function_exists('is_multisite') && is_multisite()){
    $rankstatus = get_blog_option(1,'wpachievements_rank_status');
    $cur_blog_limit = get_post_meta( $post->ID, '_quest_blog_limit', true );
  } else{
    $rankstatus = get_option('wpachievements_rank_status');
  }
  if($rankstatus != 'Disable'){
    echo '<span class="pullleft first-select">
      <label for="wpachievements_achievements_data_rank">'.__('Limit to Rank', WPACHIEVEMENTS_TEXT_DOMAIN).':</label><br/>
      <select name="wpachievements_achievements_data_rank" id="wpachievements_achievements_data_rank">';
        if( $cur_rank ){
          if( $cur_rank == 'any' ){$current = 'any';$cur_rank = 'Any Rank';} else{$current = $cur_rank;}
          echo '
          <optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
            <option value="'.$current.'" selected>'.$cur_rank.'</option>
          </optgroup>';
        }
        echo'
        <optgroup label="'.__('Available Ranks', WPACHIEVEMENTS_TEXT_DOMAIN).'">
          <option value="any">'.__('Any Rank', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>';
          if(function_exists('is_multisite') && is_multisite()){
            $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
          } else{
            $ranks = (array)get_option('wpachievements_ranks_data');
          }
          foreach($ranks as $points=>$rank):
            if( is_array( $rank ) ){
              echo '<option value="'.$rank[0].'">'.$rank[0].'</option>';
            } else{
              echo '<option value="'.$rank.'">'.$rank.'</option>';
            }
          endforeach;
        echo '</optgroup>
      </select>
    </span>';
  }

  echo '
  <label for="wpachievements_achievements_data_points">'.__('Points Awarded', WPACHIEVEMENTS_TEXT_DOMAIN).':</label>
  <div class="spinner-holder">
    <input type="text" id="wpachievements_achievements_data_points" name="wpachievements_achievements_data_points" value="'.$cur_points.'" />
    <ul class="wpmu_spinner_control">
      <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
      <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
    </ul>
  </div>';
  if( class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR) ){
    echo '<label for="wpachievements_achievements_data_wc_points">'.__('WooCommerce Points', WPACHIEVEMENTS_TEXT_DOMAIN).': <small>(Optional)</small></label>
    <div class="spinner-holder">
      <input type="text" id="wpachievements_achievements_data_wc_points" name="wpachievements_achievements_data_wc_points" value="'.$cur_woopoints.'" />
      <ul class="wpmu_spinner_control">
        <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
        <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
      </ul>
    </div>';
  }
  $count = count($cur_details);
  if( $count < 2 )
    $count = 2;
  echo '<input type="hidden" name="quest_item_counter" id="quest_item_counter" value="'.$count.'" />';
  echo '<div id="quest_item_holder">';
    if( !empty($cur_details) ){
      $ii=0;
      $detailsCount = count($cur_details);
      foreach( $cur_details as $cur_detail ){
        $ii++;
        if( function_exists(WPACHIEVEMENTS_LEARNDASH) && $ii == 1 ){
          $extra_classes = ' first-select';
        } else{
          $extra_classes = '';
        }
        echo '<div id="quest_item_'.$ii.'">';
          echo '<div class="clear"></div><br/><div class="quest_sep"></div><br/>';
          echo '
          <span class="pullleft'.$extra_classes.'">
            <label for="wpachievements_achievements_data_event_'.$ii.'">'.__('Trigger Event:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label><br/>
            <select id="wpachievements_achievements_data_event_'.$ii.'" name="wpachievements_achievements_data_event_'.$ii.'" class="trigger_select" disabled title="'.__('This cannot be changed once the quest is created.', WPACHIEVEMENTS_TEXT_DOMAIN).'">';
              if( isset($cur_detail['type']) ){
                echo '
                <optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
                  <option value="'.$cur_detail['type'].'" selected>'.apply_filters('wpachievements_trigger_description', $cur_detail['type']).'</option>
                </optgroup>';
              } else{
                echo '<option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
                do_action('wpachievements_admin_events');
              }
            echo '</select>
          </span>';
          if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
            if( isset($cur_detail['ld_first_attempt_only']) && $cur_detail['type'] == 'ld_quiz_perfect' ){
              $show = ' style="display:block !important;"';
            } else{
              $show = '';
            }
            echo '<span id="first_try" class="pullleft"'.$show.'>
              <label for="wpachievements_achievement_ld_first_try_'.$ii.'">'.__('First Attempt Only:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
              <select id="wpachievements_achievement_ld_first_try_'.$ii.'" name="wpachievements_achievement_ld_first_try_'.$ii.'">';
                if( isset($cur_detail['ld_first_attempt_only']) ){
                    echo '<optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
                    <option value="'.$cur_detail['ld_first_attempt_only'].'" selected>'.$cur_detail['ld_first_attempt_only'].'</option>
                  </optgroup>';
                }
                echo '<option value="Disabled">'.__('Disabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
                <option value="Enabled">'.__('Enabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
              </select>
            </span>';
          }
          echo '<div class="clear"></div>';
          if(function_exists('is_multisite') && is_multisite()){
            echo '<span id="blog_limit" class="pullleft">
              <label for="wpachievements_achievement_blog_limit_'.$ii.'">'.__('Limit to Blog:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
              <select id="wpachievements_achievement_blog_limit_'.$ii.'" name="wpachievements_achievement_blog_limit_'.$ii.'">';
                if( isset($cur_detail['blog_limit']) ){
                  $blog_details = get_blog_details($cur_detail['blog_limit']);
                  echo '<optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
                    <option value="'.$cur_detail['blog_limit'].'" selected>'.$blog_details->blogname.'</option>
                  </optgroup>';
                }
                $args = array(
                  'limit' => 1000,
                  'offset' => 0,
                );
                $blog_list = wp_get_sites($args);
                foreach( $blog_list as $blog ){
                  $blog_details = get_blog_details($blog['blog_id']);
                  echo '<option value="'.$blog['blog_id'].'">'.$blog_details->blogname.'</option>';
                }
                echo '
              </select>
            </span>';
          }
          if( isset($cur_detail['associated_title']) && $cur_detail['type'] == 'cp_bp_group_joined' ){
            $show = ' style="display:block !important;"';
            $title = $cur_detail['associated_title'];
          } else{
            $show = '';
            $title = '';
          }
          echo '<span id="ass_title"'.$show.'>
            <label for="wpachievements_achievement_bp_group_title_'.$ii.'">'.__('Group Title: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <input type="text" id="wpachievements_achievement_bp_group_title_'.$ii.'" name="wpachievements_achievement_bp_group_title_'.$ii.'" value="'.$title.'" />
          </span>';
          if( isset($cur_detail['associated_id']) && ( $cur_detail['type'] == 'gform_sub' || $cur_detail['type'] == 'ld_lesson_complete' || $cur_detail['type'] == 'ld_course_complete' || $cur_detail['type'] == 'ld_quiz_pass' || $cur_detail['type'] == 'wpcw_quiz') ){
            if($cur_detail['type'] == 'ld_lesson_complete' || $cur_detail['type'] == 'ld_course_complete'){
              $postid_title = __('Lesson ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN);
            }
            if($cur_detail['type'] == 'ld_course_complete'){
              $postid_title = __('Course ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN);
            }
            if($cur_detail['type'] == 'ld_quiz_pass' || $cur_detail['type'] == 'wpcw_quiz'){
              $postid_title = __('Quiz ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN);
            }
            if($cur_detail['type'] == 'wpcw_module_complete'){
              $postid_title = __('Module ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN);
            }
            if($cur_detail['type'] == 'wpcw_course_complete'){
              $postid_title = __('Course ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN);
            }
            if($cur_detail['type'] == 'gform_sub'){
              $postid_title = __('Form ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN);
            }
            $show = ' style="display:block !important;"';
          } else{
            $postid_title = __('Form ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN);
            $show = '';
          }
          if( isset($cur_detail['associated_id']) ){
            $ass_title = $cur_detail['associated_id'];
          } else{
            $ass_title = '';
          }
          echo '<span id="post_id"'.$show.'>
            <label for="wpachievements_achievements_data_post_id_'.$ii.'">'.$postid_title.'</label>
            <input type="text" id="wpachievements_achievements_data_post_id_'.$ii.'" name="wpachievements_achievements_data_post_id_'.$ii.'" value="'.$ass_title.'" />
          </span>';

          if( isset($cur_detail['ach_id']) ){
            if( $cur_detail['ach_id'] != '' ){
              $show = ' style="display:block !important;"';
              $ach_id = $cur_detail['ach_id'];
            } else{
              $show = 'style="display:none;"';
              $ach_id = '';
            }
          } else{
            $show = 'style="display:none;"';
            $ach_id = '';
          }
          echo '<span id="custom_event_details"'.$show.'>
            <label for="wpachievements_achievements_data_ach_id_'.$ii.'">'.__('Select Achievement:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <select id="wpachievements_achievements_data_ach_id_'.$ii.'" name="wpachievements_achievements_data_ach_id_'.$ii.'" disabled title="'.__('This cannot be changed once the quest is created.', WPACHIEVEMENTS_TEXT_DOMAIN).'>';
              if( isset($cur_detail['ach_id']) ){
                echo '
                <optgroup label="'.__('Currently Selected', WPACHIEVEMENTS_TEXT_DOMAIN).'">
                  <option value="'.$cur_detail['ach_id'].'" selected>'.get_the_title($cur_detail['ach_id']).'</option>
                </optgroup>';
              } else{
                echo '<option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
              }
              echo wpa_quest_achievement_list();
            echo '</select>
          </span>';
          if( class_exists(WPACHIEVEMENTS_WOOCOMMERCE) ){
            if( isset($cur_detail['woo_order_limit']) ){
              $show = ' style="display:block !important;"';
              $limit = $cur_detail['woo_order_limit'];
            } else{
              $show = '';
              $limit = '';
            }
            echo '
            <span id="woo_order_limit"'.$show.'>
              <label for="wpachievements_achievement_woo_order_limit_'.$ii.'">'.__('Minimum Order Amount: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
              <div class="spinner-holder">
                <div style="position:relative;">
                  <span id="wpa_woo_symbol">'.get_woocommerce_currency_symbol().'</span>
                  <input type="text" id="wpachievements_achievement_woo_order_limit_'.$ii.'" name="wpachievements_achievement_woo_order_limit_'.$ii.'" value="'.$limit.'" />
                  <ul class="wpmu_spinner_control">
                    <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
                    <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
                  </ul>
                </div>
              </div>
            </span>';
          }
          if( isset($cur_detail['occurrences']) ){
            $cur_occurences = $cur_detail['occurrences'];
          } else{
            $cur_occurences = '';
          }
          echo '<label for="wpachievements_achievements_data_event_no_'.$ii.'">'.__('Number of Occurrences:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
          <div class="spinner-holder">
            <input type="text" id="wpachievements_achievements_data_event_no_'.$ii.'" name="wpachievements_achievements_data_event_no_'.$ii.'" value="'.$cur_occurences.'" />
            <ul class="wpmu_spinner_control">
              <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
              <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
            </ul>
          </div>';
          if( $detailsCount <= 2 )
            $disabled = ' disabled';
          else
           $disabled = '';

          echo '<a href="#" class="button_quest_remove'.$disabled.'">'.__('Remove Trigger', WPACHIEVEMENTS_TEXT_DOMAIN).'</a>';
        echo '</div>';
      }
    } else{
      echo '<div id="quest_item_1">';
        echo '<div class="clear"></div><br/><div class="quest_sep"></div><br/>';
        if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
          $extra_classes = ' first-select';
        } else{
          $extra_classes = '';
        }
        echo '
        <span class="pullleft'.$extra_classes.'">
          <label for="wpachievements_achievements_data_event_1">'.__('Trigger Event:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label><br/>
          <select id="wpachievements_achievements_data_event_1" name="wpachievements_achievements_data_event_1" class="trigger_select">';
            echo '<option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
            do_action('wpachievements_admin_events');
          echo '</select>
        </span>';
        if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
          echo '<span id="first_try" class="pullleft">
            <label for="wpachievements_achievement_ld_first_try_1">'.__('First Attempt Only:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <select id="wpachievements_achievement_ld_first_try_1" name="wpachievements_achievement_ld_first_try_1">
              <option value="Disabled">'.__('Disabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
              <option value="Enabled">'.__('Enabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
            </select>
          </span>';
        }
        echo '<div class="clear"></div>';
          if(function_exists('is_multisite') && is_multisite()){
            echo '<span id="blog_limit" class="pullleft">
              <label for="wpachievements_achievement_blog_limit_1">'.__('Limit to Blog:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
              <select id="wpachievements_achievement_blog_limit_1" name="wpachievements_achievement_blog_limit_1">';
                $args = array(
                  'limit' => 1000,
                  'offset' => 0,
                );
                $blog_list = wp_get_sites($args);
                foreach( $blog_list as $blog ){
                  $blog_details = get_blog_details($blog['blog_id']);
                  echo '<option value="'.$blog['blog_id'].'">'.$blog_details->blogname.'</option>';
                }
                echo '
              </select>
            </span>';
          }
          echo '<span id="ass_title">
            <label for="wpachievements_achievement_bp_group_title_1">'.__('Group Title: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <input type="text" id="wpachievements_achievement_bp_group_title_1" name="wpachievements_achievement_bp_group_title_1" value="" />
          </span>';
          echo '<span id="post_id">
            <label for="wpachievements_achievements_data_post_id_1">'.__('Form ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <input type="text" id="wpachievements_achievements_data_post_id_1" name="wpachievements_achievements_data_post_id_1" value="" />
          </span>';

          $args = array(
            'post_type' => 'wpachievements',
            'post_status' => 'publish',
            'posts_per_page' => -1,
          );
          $achievement_query = new WP_Query( $args );
          if( $achievement_query->have_posts() ){
            echo '<span id="custom_event_details" style="display:none;">
              <label for="wpachievements_achievements_data_ach_id_1">'.__('Select Achievement:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
              <select id="wpachievements_achievements_data_ach_id_1" name="wpachievements_achievements_data_ach_id_1">
                <option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
                echo wpa_quest_achievement_list();
              echo '</select>
            </span>';
          }

          if( class_exists(WPACHIEVEMENTS_WOOCOMMERCE) ){
            echo '
            <span id="woo_order_limit">
              <label for="wpachievements_achievement_woo_order_limit_1">'.__('Minimum Order Amount: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
              <div class="spinner-holder">
                <div style="position:relative;">
                  <span id="wpa_woo_symbol">'.get_woocommerce_currency_symbol().'</span>
                  <input type="text" id="wpachievements_achievement_woo_order_limit_1" name="wpachievements_achievement_woo_order_limit_1" value="1" />
                  <ul class="wpmu_spinner_control">
                    <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
                    <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
                  </ul>
                </div>
              </div>
            </span>';
          }
          echo '<label for="wpachievements_achievements_data_event_no_1">'.__('Number of Occurrences', WPACHIEVEMENTS_TEXT_DOMAIN).':</label>
          <div class="spinner-holder">
            <input type="text" id="wpachievements_achievements_data_event_no_1" name="wpachievements_achievements_data_event_no_1" value="1" />
            <ul class="wpmu_spinner_control">
              <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
              <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
            </ul>
          </div>';
          echo '<a href="#" class="button_quest_remove disabled">'.__('Remove Trigger', WPACHIEVEMENTS_TEXT_DOMAIN).'</a>';
        echo '</div>';
        echo '<div id="quest_item_2">';
        echo '<div class="clear"></div><br/><div class="quest_sep"></div><br/>';
        if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
          $extra_classes = ' first-select';
        } else{
          $extra_classes = '';
        }
        echo '
        <span class="pullleft'.$extra_classes.'">
          <label for="wpachievements_achievements_data_event_2">'.__('Trigger Event:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label><br/>
          <select id="wpachievements_achievements_data_event_2" name="wpachievements_achievements_data_event_2" class="trigger_select">';
            echo '<option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
            do_action('wpachievements_admin_events');
          echo '</select>
        </span>';
        if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
          echo '<span id="first_try" class="pullleft">
            <label for="wpachievements_achievement_ld_first_try_2">'.__('First Attempt Only:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <select id="wpachievements_achievement_ld_first_try_2" name="wpachievements_achievement_ld_first_try_2">
              <option value="Disabled">'.__('Disabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
              <option value="Enabled">'.__('Enabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
            </select>
          </span>';
        }
        echo '<div class="clear"></div>';
          if(function_exists('is_multisite') && is_multisite()){
            echo '<span id="blog_limit" class="pullleft">
              <label for="wpachievements_achievement_blog_limit_2">'.__('Limit to Blog:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
              <select id="wpachievements_achievement_blog_limit_2" name="wpachievements_achievement_blog_limit_2">';
                $args = array(
                  'limit' => 1000,
                  'offset' => 0,
                );
                $blog_list = wp_get_sites($args);
                foreach( $blog_list as $blog ){
                  $blog_details = get_blog_details($blog['blog_id']);
                  echo '<option value="'.$blog['blog_id'].'">'.$blog_details->blogname.'</option>';
                }
                echo '
              </select>
            </span>';
          }
          echo '<span id="ass_title">
            <label for="wpachievements_achievement_bp_group_title_2">'.__('Group Title: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <input type="text" id="wpachievements_achievement_bp_group_title_2" name="wpachievements_achievement_bp_group_title_2" value="" />
          </span>';
          echo '<span id="post_id">
            <label for="wpachievements_achievements_data_post_id_2">'.__('Form ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <input type="text" id="wpachievements_achievements_data_post_id_2" name="wpachievements_achievements_data_post_id_2" value="" />
          </span>';
          echo '<span id="custom_event_details" style="display:none;">
            <label for="wpachievements_achievements_data_ach_id_2">'.__('Select Achievement:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
            <select id="wpachievements_achievements_data_ach_id_2" name="wpachievements_achievements_data_ach_id_2">
              <option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
              echo wpa_quest_achievement_list();
            echo '</select>
          </span>';
          if( class_exists(WPACHIEVEMENTS_WOOCOMMERCE) ){
            echo '
            <span id="woo_order_limit">
              <label for="wpachievements_achievement_woo_order_limit_2">'.__('Minimum Order Amount: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
              <div class="spinner-holder">
                <div style="position:relative;">
                  <span id="wpa_woo_symbol">'.get_woocommerce_currency_symbol().'</span>
                  <input type="text" id="wpachievements_achievement_woo_order_limit_2" name="wpachievements_achievement_woo_order_limit_2" value="1" />
                  <ul class="wpmu_spinner_control">
                    <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
                    <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
                  </ul>
                </div>
              </div>
            </span>';
          }
          echo '<label for="wpachievements_achievements_data_event_no_2">'.__('Number of Occurrences', WPACHIEVEMENTS_TEXT_DOMAIN).':</label>
          <div class="spinner-holder">
            <input type="text" id="wpachievements_achievements_data_event_no_2" name="wpachievements_achievements_data_event_no_2" value="1" />
            <ul class="wpmu_spinner_control">
              <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
              <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
            </ul>
          </div>';
          echo '<a href="#" class="button_quest_remove disabled">'.__('Remove Trigger', WPACHIEVEMENTS_TEXT_DOMAIN).'</a>';
        echo '</div>';
    }

  echo '<div class="event_details_holder">
    <div class="clear"></div><br/><div class="quest_sep"></div><br/>
    <div class="clear"></div><a href="#" class="button button-primary" id="quest_add">'.__('Add Another Trigger', WPACHIEVEMENTS_TEXT_DOMAIN).'</a><span class="small_loader_icon"></span>
  </div><div class="clear"></div><br/>';
 }


 /**
 *********************************************************
 *   W P A C H I E V E M E N T S   Q U E S T   H T M L   *
 *********************************************************
 */
 function wpachievements_quest_image_box( $post ){

  $cur_image = get_post_meta( $post->ID, '_quest_image', true );

  if( $cur_image ){
    echo '<div id="image_preview_holder"><img src="'.$cur_image.'" alt="Achievement Logo" /><br/><a href="#" id="achievement_image_remove">Remove</a></div>';
  } else{
    echo '<div id="image_preview_holder"></div>';
  }

  echo '<span id="no-image-links"><a href="#" id="achievement_image_pick">Select Image</a> &nbsp;|&nbsp; <input id="upload_image" type="text" name="upload_image" value="'.$cur_image.'" /><input class="button button-primary" id="upload_image_button" type="button" value="'.__('Upload Image', WPACHIEVEMENTS_TEXT_DOMAIN).'" /></span>';
  echo '<div id="default-image-selection" style="display:none;">';
  $path = plugin_dir_url(basename(__FILE__)).'wpachievements/img/icons/';
  $handle = opendir(dirname(realpath(__FILE__)).'/img/icons/');
  $ii=0;
  while($file = readdir($handle)){
    if($file !== '.' && $file !== '..'){
      $ii++;
      echo '<span><input type="radio" name="achievement_badge" value="'.$path.$file.'" /><img src="'.$path.$file.'" alt="'.__('Achievement Image', WPACHIEVEMENTS_TEXT_DOMAIN).' '.$ii.'" class="radio_btn" /></span>';
    }
  }
  echo '<div class="clear"></div></div>';

 }
 add_action('wp_ajax_wpachievements_quest_html', 'get_wpachievements_quest_html');
 function get_wpachievements_quest_html(){
   if( isset($_POST['quest_count']) ){
     $count = (int)$_POST['quest_count'];
     $count++;
     if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
       $extra_classes = ' first-select';
     } else{
       $extra_classes = '';
     }
     echo '<div id="quest_item_'.$count.'">';
       echo '
       <div class="clear"></div><br/><div class="quest_sep"></div><br/>
       <span class="pullleft'.$extra_classes.'">
         <label for="wpachievements_achievements_data_event_'.$count.'">'.__('Trigger Event:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label><br/>
         <select id="wpachievements_achievements_data_event_'.$count.'" name="wpachievements_achievements_data_event_'.$count.'" class="trigger_select">';
           echo '<option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
           do_action('wpachievements_admin_events');
         echo '</select>
       </span>';
       if( function_exists(WPACHIEVEMENTS_LEARNDASH) ){
         echo '<span id="first_try" class="pullleft">
           <label for="wpachievements_achievement_ld_first_try_'.$count.'">'.__('First Attempt Only:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
           <select id="wpachievements_achievement_ld_first_try_'.$count.'" name="wpachievements_achievement_ld_first_try_'.$count.'">
             <option value="Disabled">'.__('Disabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
             <option value="Enabled">'.__('Enabled', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
           </select>
         </span>';
       }
       echo '<div class="clear"></div>';
       if(function_exists('is_multisite') && is_multisite()){
         echo '<span id="blog_limit" class="pullleft">
           <label for="wpachievements_achievement_blog_limit_'.$count.'">'.__('Limit to Blog:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
           <select id="wpachievements_achievement_blog_limit_'.$count.'" name="wpachievements_achievement_blog_limit_'.$count.'">';
             $args = array(
               'limit' => 1000,
               'offset' => 0,
             );
             $blog_list = wp_get_sites($args);
             foreach( $blog_list as $blog ){
               $blog_details = get_blog_details($blog['blog_id']);
               echo '<option value="'.$blog['blog_id'].'">'.$blog_details->blogname.'</option>';
             }
             echo '
           </select>
         </span>';
       }
       echo '<span id="ass_title">
         <label for="wpachievements_achievement_bp_group_title_'.$count.'">'.__('Group Title: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
         <input type="text" id="wpachievements_achievement_bp_group_title_'.$count.'" name="wpachievements_achievement_bp_group_title_'.$count.'" value="" />
       </span>';
       echo '<span id="post_id">
         <label for="wpachievements_achievements_data_post_id_'.$count.'">'.__('Form ID: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
         <input type="text" id="wpachievements_achievements_data_post_id_'.$count.'" name="wpachievements_achievements_data_post_id_'.$count.'" value="" />
       </span>';

       $args = array(
         'post_type' => 'wpachievements',
         'post_status' => 'publish',
         'posts_per_page' => -1,
       );
       $achievement_query = new WP_Query( $args );
       if( $achievement_query->have_posts() ){
         echo '<span id="custom_event_details" style="display:none;">
           <label for="wpachievements_achievements_data_ach_id_'.$count.'">'.__('Select Achievement:', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
           <select id="wpachievements_achievements_data_ach_id_'.$count.'" name="wpachievements_achievements_data_ach_id_'.$count.'">
             <option value="" selected>---------------- '.__('Select', WPACHIEVEMENTS_TEXT_DOMAIN).' ----------------</option>';
             echo wpa_quest_achievement_list();
           echo '</select>
         </span>';
       }

       if( class_exists(WPACHIEVEMENTS_WOOCOMMERCE) ){
         echo '
         <span id="woo_order_limit">
           <label for="wpachievements_achievement_woo_order_limit_'.$count.'">'.__('Minimum Order Amount: <small>(Optional)</small>', WPACHIEVEMENTS_TEXT_DOMAIN).'</label>
           <div class="spinner-holder">
             <div style="position:relative;">
               <span id="wpa_woo_symbol">'.get_woocommerce_currency_symbol().'</span>
               <input type="text" id="wpachievements_achievement_woo_order_limit_'.$count.'" name="wpachievements_achievement_woo_order_limit_'.$count.'" value="1" />
               <ul class="wpmu_spinner_control">
                 <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
                 <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
               </ul>
             </div>
           </div>
         </span>';
       }
       echo '<label for="wpachievements_achievements_data_event_no_'.$count.'">'.__('Number of Occurrences', WPACHIEVEMENTS_TEXT_DOMAIN).':</label>
       <div class="spinner-holder">
         <input type="text" id="wpachievements_achievements_data_event_no_'.$count.'" name="wpachievements_achievements_data_event_no_'.$count.'" value="1" />
         <ul class="wpmu_spinner_control">
           <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_increase" value="&#9650;" /></li>
           <li><input type="button" class="button button-primary wpmu_spinner_btn wpump_spinner_decrease" value="&#9660;" /></li>
         </ul>
       </div>';
      echo '<a href="#" class="button_quest_remove">'.__('Remove Trigger', WPACHIEVEMENTS_TEXT_DOMAIN).'</a>';
     echo '</div>';
   }
   die();
 }


 function wpachievements_force_default_editor() {
  return 'tinymce';
 }

 function wpachievements_save_achievement( $post_id ) {
  if ( isset( $_POST['wpachievements_quest_nonce'] ) )
    return $post_id;
  if ( !isset( $_POST['wpachievements_achievement_nonce'] ) )
    return $post_id;
  $nonce = $_POST['wpachievements_achievement_nonce'];
  if ( !wp_verify_nonce( $nonce, 'wpachievements_achievement_save' ) )
    return $post_id;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return $post_id;
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }
  $ach_title = sanitize_text_field( $_POST['post_title'] );
  $ach_desc = $_POST['achievement_desc_editor'];
  if( isset($_POST['wpachievements_achievements_data_rank']) ){
    $ach_rank = $_POST['wpachievements_achievements_data_rank'];
  } else{
    $ach_rank = 'any';
  }
  $ach_type = $_POST['wpachievements_achievements_data_event'];
  if( $ach_type == 'custom_trigger' ){
    $ach_trigger_id = $_POST['wpachievements_achievements_custom_trigger_id'];
    $ach_trigger_desc = $_POST['wpachievements_achievements_custom_trigger_desc'];
  }
  if( isset($_POST['wpachievements_achievements_data_post_id']) ){
    $ach_postid = sanitize_text_field( $_POST['wpachievements_achievements_data_post_id'] );
  } else{
    $ach_postid = '';
  }
  $ach_occur = sanitize_text_field( $_POST['wpachievements_achievements_data_event_no'] );
  $ach_points = sanitize_text_field( $_POST['wpachievements_achievements_data_points'] );
  if( isset($_POST['wpachievements_achievements_data_wc_points']) ){
    $ach_wcpoints = sanitize_text_field( $_POST['wpachievements_achievements_data_wc_points'] );
  } else{
    $ach_wcpoints = '';
  }
  $ach_img = $_POST['upload_image'];
  if( isset($_POST['wpachievements_achievement_woo_order_limit']) ){
    $ach_order_limit = sanitize_text_field( $_POST['wpachievements_achievement_woo_order_limit'] );
  } else{
    $ach_order_limit = '';
  }
  if( isset($_POST['wpachievements_achievement_ld_first_try']) ){
    $ach_first_try = sanitize_text_field( $_POST['wpachievements_achievement_ld_first_try'] );
  } else{
    $ach_first_try = '';
  }
  if( isset($_POST['wpachievements_achievement_bp_group_title']) ){
    $ach_ass_title = sanitize_text_field( $_POST['wpachievements_achievement_bp_group_title'] );
  } else{
    $ach_ass_title = '';
  }
  if( isset($_POST['wpachievements_achievement_blog_limit']) ){
    $ach_blog_Limit = sanitize_text_field( $_POST['wpachievements_achievement_blog_limit'] );
  } else{
    $ach_blog_Limit = '';
  }
  if( isset($_POST['wpachievements_achievements_recurring']) ){
    $ach_recurring = 1;
  } else{
    $ach_recurring = '';
  }

  $already_exists = get_post_meta( $post_id, '_achievement_points', true );
  if( $already_exists ){
    $ach_prev_points = get_post_meta( $post_id, '_achievement_points', true );
    $ach_prev_wcpoints = get_post_meta( $post_id, '_achievement_woo_points', true );
    $ach_prev_rank = get_post_meta( $post_id, '_achievement_rank', true );
    $ach_prev_occur = get_post_meta( $post_id, '_achievement_occurrences', true );
    $ach_prev_postid = get_post_meta( $post_id, '_achievement_associated_id', true );
    $ach_prev_ass_title = get_post_meta( $post_id, '_achievement_associated_title', true );
    if( $ach_rank != $ach_prev_rank || $ach_points != $ach_prev_points || $ach_wcpoints != $ach_prev_wcpoints || $ach_prev_occur != $ach_occur || $ach_prev_postid != $ach_postid || $ach_ass_title != $ach_prev_ass_title ){
      $ach_data = $ach_title.': '.$ach_desc;
      global $wpdb;
      if(function_exists('is_multisite') && is_multisite()){
        $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
      } else{
        $table = $wpdb->prefix.'achievements';
      }
      $users_gained = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE '_user_gained_%%'", $post_id) );
      if( $users_gained ){
        foreach( $users_gained as $user ){
          $remove_ach = false;
          if( $ach_rank != $ach_prev_rank ){
            $usersrank = wpachievements_getRank($user->meta_value);
            $userrank_lvl = wpachievements_rankToPoints($usersrank);
            $ach_rank_lvl = wpachievements_rankToPoints($ach_rank);
            if( $userrank_lvl < $ach_rank_lvl ){
              $remove_ach = true;
              $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
              $user_ach_count = (int)sizeof($userachievements);
              if( $user_ach_count > 1 ){
                foreach($userachievements as $key => $value){
                  if( $value == $post_id )
                    unset($userachievements[$key]);
                }
                update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );
              } else{
                delete_user_meta( $user->meta_value, 'achievements_gained' );
              }
              do_action( 'wpachievements_remove_achievement', $user->meta_value, $post_id );
              $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_prev_points) );
              $user_ach_count = (int)$user_ach_count - 1;
              wpachievements_decrease_points( $user->meta_value, $ach_prev_points );
              wpachievements_decrease_wcpr_points( $user->meta_value, $ach_prev_wcpoints, 'wpachievements_achievement_removed' );
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_removed', $user->meta_value, -$ach_prev_points, '' );
              }
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_subtract( 'wpachievements_removed', $user->meta_value, $ach_prev_points, '%plural% for Achievement Removed: '.$ach_title, '', '', $pointType );
              }
              delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
              update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
            }
          }
          if( $ach_ass_title != $ach_prev_ass_title && !$remove_ach ){
            $group_id = BP_Groups_Group::group_exists($ach_ass_title);
            if( $ach_rank ){
              $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE type='%s' AND uid=$user->meta_value AND rank='%s' AND postid=%d", $ach_type,$ach_rank,$group_id) );
            } else{
              $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE type='%s' AND uid=$user->meta_value AND postid=%d", $ach_type,$group_id) );
            }
            if( $activities_count < $ach_occur ){
              $remove_ach = true;
              $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
              $user_ach_count = (int)sizeof($userachievements);
              if( $user_ach_count > 1 ){
                foreach($userachievements as $key => $value){
                  if( $value == $post_id )
                    unset($userachievements[$key]);
                }
                update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );
              } else{
                delete_user_meta( $user->meta_value, 'achievements_gained' );
              }
              do_action( 'wpachievements_remove_achievement', $user->meta_value, $post_id );
              $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_prev_points) );
              $user_ach_count = (int)$user_ach_count - 1;
              wpachievements_decrease_points( $user->meta_value, $ach_prev_points );
              wpachievements_decrease_wcpr_points( $user->meta_value, $ach_prev_wcpoints, 'wpachievements_achievement_removed' );
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_removed', $user->meta_value, -$ach_prev_points, '' );
              }
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_subtract( 'wpachievements_removed', $user->meta_value, $ach_prev_points, '%plural% for Achievement Removed: '.$ach_title, '', '', $pointType );
              }
              delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
              update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
            }
          }
          if( $ach_prev_postid != $ach_postid && !$remove_ach ){
            if( $ach_rank ){
              $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE type='$ach_type' AND uid=$user->meta_value AND rank='$ach_rank' AND postid=%d", $ach_postid) );
            } else{
              $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE type='$ach_type' AND uid=$user->meta_value AND postid=%d", $ach_postid) );
            }
            if( $activities_count < $ach_occur ){
              $remove_ach = true;
              $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
              $user_ach_count = (int)sizeof($userachievements);
              if( $user_ach_count > 1 ){
                foreach($userachievements as $key => $value){
                  if( $value == $post_id )
                    unset($userachievements[$key]);
                }
                update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );
              } else{
                delete_user_meta( $user->meta_value, 'achievements_gained' );
              }
              do_action( 'wpachievements_remove_achievement', $user->meta_value, $post_id );
              $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_prev_points) );
              $user_ach_count = (int)$user_ach_count - 1;
              wpachievements_decrease_points( $user->meta_value, $ach_prev_points );
              wpachievements_decrease_wcpr_points( $user->meta_value, $ach_prev_wcpoints, 'wpachievements_achievement_removed' );
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_removed', $user->meta_value, -$ach_prev_points, '' );
              }
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_subtract( 'wpachievements_removed', $user->meta_value, $ach_prev_points, '%plural% for Achievement Removed: '.$ach_title, '', '', $pointType );
              }
              delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
              update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
            }
          }
          if( $ach_prev_occur != $ach_occur && !$remove_ach ){
            $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE type='%s' AND uid=$user->meta_value AND rank='%s'", $ach_type,$ach_rank) );
            if( $activities_count < $ach_occur ){
              $remove_ach = true;
              $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
              $user_ach_count = (int)sizeof($userachievements);
              if( $user_ach_count > 1 ){
                foreach($userachievements as $key => $value){
                  if( $value == $post_id )
                    unset($userachievements[$key]);
                }
                update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );
              } else{
                delete_user_meta( $user->meta_value, 'achievements_gained' );
              }
              do_action( 'wpachievements_remove_achievement', $user->meta_value, $post_id );
              $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_prev_points) );
              $user_ach_count = (int)$user_ach_count - 1;
              wpachievements_decrease_points( $user->meta_value, $ach_prev_points );
              wpachievements_decrease_wcpr_points( $user->meta_value, $ach_prev_wcpoints, 'wpachievements_achievement_removed' );
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_removed', $user->meta_value, -$ach_prev_points, '' );
              }
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_subtract( 'wpachievements_removed', $user->meta_value, $ach_prev_points, '%plural% for Achievement Removed: '.$ach_title, '', '', $pointType );
              }
              delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
              update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);
            }
          }
          if( $ach_points != $ach_prev_points && !$remove_ach ){
            if( $ach_points < $ach_prev_points ){
              $deduct_points = $ach_prev_points - $ach_points;
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_subtract( 'wpachievements_changed', $user->meta_value, $deduct_points, '%plural% Achievement Modified: '.$ach_title, '', '', $pointType );
              }
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_changed', $user->meta_value, -$deduct_points, '' );
              }
              wpachievements_decrease_points( $user->meta_value, $deduct_points );
              wpachievements_decrease_wcpr_points( $user->meta_value, $deduct_points, 'wpachievements_achievement_edited_remove' );
            } else{
              $add_points = $ach_points - $ach_prev_points;
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_add( 'wpachievements_changed', $user->meta_value, $add_points, '%plural% Achievement Modified: '.$ach_title, '', '', $pointType );
              }
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_changed', $user->meta_value, $add_points, '' );
              }
              wpachievements_increase_points( $user->meta_value, $add_points );
              wpachievements_increase_wcpr_points( $user->meta_value, $add_points, 'wpachievements_achievement_edited_add' );
            }
          }
          if(function_exists('is_multisite') && is_multisite()){
            $wcpr_sync = get_blog_option(1, 'wpachievements_wcpr_sync_enabled' );
          } else{
            $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
          }
          if( $wcpr_sync != 'true' ){
            if( $ach_wcpoints != $ach_prev_wcpoints && !$remove_ach ){
              if( $ach_points < $ach_prev_points ){
                $deduct_points = $ach_prev_wcpoints - $ach_wcpoints;
                if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) ){
                  $wcdata = array( 'achievement_id' => $post_id );
                  WC_Points_Rewards_Manager::decrease_points( $user->meta_value, $deduct_points, 'wpachievements_achievement_edited_remove', $wcdata );
                }
              } else{
                $add_points = $ach_wcpoints - $ach_prev_wcpoints;
                if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) ){
                  $wcdata = array( 'achievement_id' => $post_id );
                  WC_Points_Rewards_Manager::increase_points( $user->meta_value, $add_points, 'wpachievements_achievement_edited_add', $wcdata );
                }
              }
            }
          }
        }
      }
    }
  }
  remove_action('save_post', 'wpachievements_save_achievement');
  $wpa_args = array(
    'ID'           => $post_id,
    'post_content' => $ach_desc,
    'post_status'  => 'publish'
  );
  wp_update_post( $wpa_args );
  add_action('save_post', 'wpachievements_save_achievement');

  update_post_meta( $post_id, '_achievement_woo_order_limit', $ach_order_limit );
  update_post_meta( $post_id, '_achievement_rank', $ach_rank );
  update_post_meta( $post_id, '_achievement_type', $ach_type );
  update_post_meta( $post_id, '_achievement_points', $ach_points );
  update_post_meta( $post_id, '_achievement_woo_points', $ach_wcpoints );
  update_post_meta( $post_id, '_achievement_associated_id', $ach_postid );
  update_post_meta( $post_id, '_achievement_occurrences', $ach_occur );
  update_post_meta( $post_id, '_achievement_image', $ach_img );
  update_post_meta( $post_id, '_achievement_ld_first_attempt_only', $ach_first_try );
  update_post_meta( $post_id, '_achievement_associated_title', $ach_ass_title );
  update_post_meta( $post_id, '_achievement_postid', $post_id );
  if( !empty($ach_trigger_id) ){
    update_post_meta( $post_id, '_achievement_trigger_id', $ach_trigger_id );
    update_post_meta( $post_id, '_achievement_trigger_desc', $ach_trigger_desc );
  }
  update_post_meta( $post_id, '_achievement_recurring', $ach_recurring );
  if( !empty($ach_blog_Limit) )
    update_post_meta( $post_id, '_achievement_blog_limit', $ach_blog_Limit );

 }

 add_action('before_delete_post', 'wpachievements_delete_achievement', 1);
 function wpachievements_delete_achievement($post_id) {
   $post = get_post($post_id);
   if ($post->post_type == 'wpachievements') {
     global $wpdb;
     $gained_users = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", '%_user_gained_%', $post_id) );
     if( $gained_users ){
       foreach( $gained_users as $user ){

         $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );

         $ach_ID = get_the_ID();
         $ach_title = get_the_title();
         $ach_desc = get_the_content();
         $ach_data = $ach_title.': '.$ach_desc;
         $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
         $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
         $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );

         if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
           cp_points('wpachievements_removed', $user->meta_value, -$ach_points, $ach_data );
         }
         if(function_exists(WPACHIEVEMENTS_MYCRED)){
           $pointType = get_option('wpachievements_mycred_point_type');
           mycred_subtract( 'wpachievements_removed', $user->meta_value, $ach_points, '%plural% for Achievement Removed: '.$ach_title, '', '', $pointType );
         }
         wpachievements_decrease_points( $user->meta_value, $ach_points, 'wpachievements_removed' );
         wpachievements_decrease_wcpr_points( $user->meta_value, $ach_points, 'wpachievements_achievement_removed' );

         do_action( 'wpachievements_remove_achievement', $user->meta_value, $ach_ID );
         do_action( 'wpachievements_admin_remove_achievement', $user->meta_value, 'wpachievements_removed', $ach_points );

         if(function_exists('is_multisite') && is_multisite()){
           $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
         } else{
           $table = $wpdb->prefix.'achievements';
         }
         $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_points) );

         if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) && ( !empty($ach_woopoints) && $ach_woopoints > 0 ) ){
           if(function_exists('is_multisite') && is_multisite()){
             $wcpr_sync = get_blog_option( 1, 'wpachievements_wcpr_sync_enabled' );
           } else{
             $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
           }
           if( $wcpr_sync != 'true' ){
             $wcdata = array( 'achievement_id' => $ach_ID );
             WC_Points_Rewards_Manager::decrease_points( $user->meta_value, $ach_woopoints, 'wpachievements_achievement_removed', $wcdata );
           }
         }

         delete_post_meta( $ach_ID, '_user_gained_'.$user->meta_value );

         $ach_meta = get_user_meta( $user->meta_value, 'wpachievements_got_new_ach', true );
         if( in_array_r( $ach_title, $ach_meta ) && in_array_r( $ach_desc, $ach_meta ) && in_array_r( $ach_img, $ach_meta ) ){
           foreach( $ach_meta as $key => $value ){
             if( $value["title"] == $ach_title && $value["text"] == $ach_desc && $value["image"] == $ach_img ){ unset($ach_meta[$key]); }
           }
           update_user_meta( $user->meta_value, 'wpachievements_got_new_ach', $ach_meta );
         }

         foreach($userachievements as $key => $value){
           if( $value == $post_id )
             unset($userachievements[$key]);
         }
         update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );

         $user_ach_count = (int)sizeof($userachievements);
         $user_ach_count = $user_ach_count - 1;
         update_user_meta( $user->meta_value, 'achievements_count', $user_ach_count);

       }
     }

     $args = array(
       'post_type' => 'wpquests',
       'post_status' => 'publish',
       'posts_per_page' => -1,
       'meta_query' => array(
         'relation' => 'AND',
         array(
           'key' => '_quest_details',
           'value' => 'wpachievements_achievement',
           'compare' => 'LIKE'
         ),
         array(
           'key' => '_quest_details',
           'value' => $post_id,
           'compare' => 'LIKE'
         )
       )
     );

     $quest_query = new WP_Query( $args );
     if( $quest_query->have_posts() ){
       while( $quest_query->have_posts() ){
         $quest_query->the_post();
         $quest_ID = get_the_ID();
         $quest_details = get_post_meta( $quest_ID, '_quest_details', true );
         foreach( $quest_details as $quest_item ){
           if( $quest_item['ach_id'] == $post_id ){
             wp_delete_post($quest_ID);
           }
         }
       }
     }
     wp_reset_postdata();


   }
   if ($post->post_type == 'wpquests') {
     global $wpdb;
     $gained_users = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", '%_user_gained_%', $post_id) );
     if( $gained_users ){
       foreach( $gained_users as $user ){

         $userquests = get_user_meta( $user->meta_value, 'quests_gained', true );
         $quest_ID = get_the_ID();
         $quest_title = get_the_title();
         $quest_desc = get_the_content();
         $quest_data = $quest_title.': '.$quest_desc;
         $quest_points = get_post_meta( $quest_ID, '_quest_points', true );
         $quest_woopoints = get_post_meta( $quest_ID, '_quest_woo_points', true );
         $quest_img = get_post_meta( $quest_ID, '_quest_image', true );

         if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
           cp_points('wpachievements_quest_removed', $user->meta_value, -$quest_points, $quest_data );
         }
         if(function_exists(WPACHIEVEMENTS_MYCRED)){
           $pointType = get_option('wpachievements_mycred_point_type');
           mycred_subtract( 'wpachievements_quest_removed', $user->meta_value, $quest_points, '%plural% for Quest Removed: '.$quest_title, '', '', $pointType );
         }
         wpachievements_decrease_points( $user->meta_value, $quest_points, 'wpachievements_quest_removed' );
         wpachievements_decrease_wcpr_points( $user->meta_value, $quest_points, 'wpachievements_quest_removed' );

         do_action( 'wpachievements_admin_remove_quest', $user->meta_value, 'wpachievements_quest_removed', $quest_points );

         if(function_exists('is_multisite') && is_multisite()){
           $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
         } else{
           $table = $wpdb->prefix.'achievements';
         }
         $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_quest_removed', '$quest_data', '-%d', '')", $quest_points) );

         if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) && ( !empty($quest_woopoints) && $quest_woopoints > 0 ) ){
           if(function_exists('is_multisite') && is_multisite()){
             $wcpr_sync = get_blog_option( 1, 'wpachievements_wcpr_sync_enabled' );
           } else{
             $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
           }
           if( $wcpr_sync != 'true' ){
             $wcdata = array( 'quest_id' => $quest_ID );
             WC_Points_Rewards_Manager::decrease_points( $user->meta_value, $quest_woopoints, 'wpachievements_quest_removed', $wcdata );
           }
         }

         delete_post_meta( $quest_ID, '_user_gained_'.$user->meta_value );

         $quest_meta = get_user_meta( $user->meta_value, 'wpachievements_got_new_quest', true );
         if( in_array_r( $quest_title, $quest_meta ) && in_array_r( $quest_desc, $quest_meta ) && in_array_r( $quest_img, $quest_meta )  ){
           foreach( $quest_meta as $key => $value ){
             if( $value["title"] == $quest_title && $value["text"] == $quest_desc && $value["image"] == $quest_img ){ unset($quest_meta[$key]); }
           }
         }
         update_user_meta( $user->meta_value, 'wpachievements_got_new_quest', $quest_meta );

         foreach($userquests as $key => $value){
           if( $value == $post_id )
             unset($userquests[$key]);
         }
         update_user_meta( $user->meta_value, 'quests_gained', $userquests );

         $user_ach_count = (int)sizeof($userquests);
         $user_ach_count = $user_ach_count - 1;
         update_user_meta( $user->meta_value, 'quests_count', $user_ach_count);

       }
     }
   }
 }



 function wpachievements_save_quest( $post_id ) {
  if ( !isset( $_POST['wpachievements_quest_nonce'] ) )
    return $post_id;
  $nonce = $_POST['wpachievements_quest_nonce'];
  if ( !wp_verify_nonce( $nonce, 'wpachievements_quest_save' ) )
    return $post_id;
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return $post_id;
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }
  $quest_title = sanitize_text_field( $_POST['post_title'] );
  $quest_desc = $_POST['achievement_desc_editor'];
  if( isset($_POST['wpachievements_achievements_data_rank']) ){
    $quest_rank = $_POST['wpachievements_achievements_data_rank'];
  } else{
    $quest_rank = 'any';
  }

  $quest_points = sanitize_text_field( $_POST['wpachievements_achievements_data_points'] );
  if( isset($_POST['wpachievements_achievements_data_wc_points']) ){
    $quest_wcpoints = sanitize_text_field( $_POST['wpachievements_achievements_data_wc_points'] );
  } else{
    $quest_wcpoints = '';
  }
  $quest_img = $_POST['upload_image'];


  $quest=array(); $ii=0;
  $quest_count = $_POST['quest_item_counter'];
  while( $ii<=$quest_count ){
    $ii++;
    if( !isset($_POST['wpachievements_achievements_data_event_'.$ii]) )
      continue;

    $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['type'] = $_POST['wpachievements_achievements_data_event_'.$ii];

    $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['occurrences'] = sanitize_text_field( $_POST['wpachievements_achievements_data_event_no_'.$ii] );

    if( isset($_POST['wpachievements_achievements_data_ach_id_'.$ii]) ){
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['ach_id'] =  sanitize_text_field( $_POST['wpachievements_achievements_data_ach_id_'.$ii] );
    } else{
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['ach_id'] = '';
    }
    if( isset($_POST['wpachievements_achievements_data_post_id_'.$ii]) ){
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['associated_id'] =  sanitize_text_field( $_POST['wpachievements_achievements_data_post_id_'.$ii] );
    } else{
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['associated_id'] = '';
    }
    if( isset($_POST['wpachievements_achievement_woo_order_limit_'.$ii]) ){
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['woo_order_limit'] = sanitize_text_field( $_POST['wpachievements_achievement_woo_order_limit_'.$ii] );
    } else{
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['woo_order_limit'] = '';
    }
    if( isset($_POST['wpachievements_achievement_ld_first_try_'.$ii]) ){
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['ld_first_attempt_only'] = sanitize_text_field( $_POST['wpachievements_achievement_ld_first_try_'.$ii] );
    } else{
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['ld_first_attempt_only'] = '';
    }
    if( isset($_POST['wpachievements_achievement_bp_group_title_'.$ii]) ){
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['associated_title'] = sanitize_text_field( $_POST['wpachievements_achievement_bp_group_title_'.$ii] );
    } else{
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['associated_title'] = '';
    }
    if( isset($_POST['wpachievements_achievement_blog_limit_'.$ii]) ){
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['blog_limit'] = sanitize_text_field( $_POST['wpachievements_achievement_blog_limit_'.$ii] );
    } else{
      $quest[$ii.'_'.$_POST['wpachievements_achievements_data_event_'.$ii]]['blog_limit'] = '';
    }
  }

  $already_exists = get_post_meta( $post_id, '_quest_details', true );
  if( $already_exists ){

    $quest_prev_points = get_post_meta( $post_id, '_quest_points', true );
    $quest_prev_wcpoints = get_post_meta( $post_id, '_quest_woo_points', true );
    $quest_prev_rank = get_post_meta( $post_id, '_quest_rank', true );

    if( $quest_rank != $quest_prev_rank || $quest_points != $quest_prev_points || $quest_wcpoints != $quest_prev_wcpoints ){
      $quest_data = $quest_title.': '.$quest_desc;
      global $wpdb;
      if(function_exists('is_multisite') && is_multisite()){
        $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
      } else{
        $table = $wpdb->prefix.'achievements';
      }
      $users_gained = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE '_user_gained_%%'", $post_id) );
      if( $users_gained ){
        foreach( $users_gained as $user ){
          $remove_ach = false;
          if( $quest_rank != $quest_prev_rank ){
            $usersrank = wpachievements_getRank($user->meta_value);
            $userrank_lvl = wpachievements_rankToPoints($usersrank);
            $quest_rank_lvl = wpachievements_rankToPoints($quest_rank);
            if( $userrank_lvl < $quest_rank_lvl ){
              $remove_ach = true;
              $userachievements = get_user_meta( $user->meta_value, 'achievements_gained', true );
              $user_quest_count = (int)sizeof($userachievements);
              if( $user_quest_count > 1 ){
                foreach($userachievements as $key => $value){
                  if( $value == $post_id )
                    unset($userachievements[$key]);
                }
                update_user_meta( $user->meta_value, 'achievements_gained', $userachievements );
              } else{
                delete_user_meta( $user->meta_value, 'achievements_gained' );
              }
              do_action( 'wpachievements_remove_achievement', $user->meta_value, $post_id );
              $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user->meta_value, 'wpachievements_removed', '$quest_data', '-%d', '')", $quest_prev_points) );
              $user_quest_count = (int)$user_quest_count - 1;
              wpachievements_decrease_points( $user->meta_value, $quest_prev_points );
              wpachievements_decrease_wcpr_points( $user->meta_value, $quest_prev_wcpoints, 'wpachievements_achievement_removed' );
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_removed', $user->meta_value, -$quest_prev_points, '' );
              }
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_subtract( 'wpachievements_removed', $user->meta_value, $quest_prev_points, '%plural% for Achievement Removed: '.$quest_title, '', '', $pointType );
              }
              delete_post_meta( $post_id, '_user_gained_'.$user->meta_value );
              update_user_meta( $user->meta_value, 'achievements_count', $user_quest_count);
            }
          }
          if( $quest_points != $quest_prev_points && !$remove_ach ){
            if( $quest_points < $quest_prev_points ){
              $deduct_points = $quest_prev_points - $quest_points;
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_subtract( 'wpachievements_changed', $user->meta_value, $deduct_points, '%plural% Achievement Modified: '.$quest_title, '', '', $pointType );
              }
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_changed', $user->meta_value, -$deduct_points, '' );
              }
              wpachievements_decrease_points( $user->meta_value, $deduct_points );
              wpachievements_decrease_wcpr_points( $user->meta_value, $deduct_points, 'wpachievements_achievement_edited_remove' );
            } else{
              $add_points = $quest_points - $quest_prev_points;
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $pointType = get_option('wpachievements_mycred_point_type');
                mycred_add( 'wpachievements_changed', $user->meta_value, $add_points, '%plural% Achievement Modified: '.$quest_title, '', '', $pointType );
              }
              if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
                cp_points('wpachievements_changed', $user->meta_value, $add_points, '' );
              }
              wpachievements_increase_points( $user->meta_value, $add_points );
              wpachievements_increase_wcpr_points( $user->meta_value, $add_points, 'wpachievements_achievement_edited_add' );
            }
          }
          if(function_exists('is_multisite') && is_multisite()){
            $wcpr_sync = get_blog_option(1, 'wpachievements_wcpr_sync_enabled' );
          } else{
            $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
          }
          if( $wcpr_sync != 'true' ){
            if( $quest_wcpoints != $quest_prev_wcpoints && !$remove_ach ){
              if( $quest_points < $quest_prev_points ){
                $deduct_points = $quest_prev_wcpoints - $quest_wcpoints;
                if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) ){
                  $wcdata = array( 'achievement_id' => $post_id );
                  WC_Points_Rewards_Manager::decrease_points( $user->meta_value, $deduct_points, 'wpachievements_achievement_edited_remove', $wcdata );
                }
              } else{
                $add_points = $quest_wcpoints - $quest_prev_wcpoints;
                if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) ){
                  $wcdata = array( 'achievement_id' => $post_id );
                  WC_Points_Rewards_Manager::increase_points( $user->meta_value, $add_points, 'wpachievements_achievement_edited_add', $wcdata );
                }
              }
            }
          }
        }
      }
    }
  }
  remove_action('save_post', 'wpachievements_save_quest');
  $wpa_args = array(
    'ID'           => $post_id,
    'post_content' => $quest_desc,
    'post_status'  => 'publish'
  );
  wp_update_post( $wpa_args );
  add_action('save_post', 'wpachievements_save_quest');

  update_post_meta( $post_id, '_quest_points', $quest_points );
  update_post_meta( $post_id, '_quest_woo_points', $quest_wcpoints );
  update_post_meta( $post_id, '_quest_rank', $quest_rank );
  update_post_meta( $post_id, '_quest_image', $quest_img );
  update_post_meta( $post_id, '_quest_details', $quest );

 }

/**
 ***********************************************************
 *   W P A C H I E V E M E N T S   R A N K S   A D M I N   *
 ***********************************************************
 */
 function wpachievements_ranks_admin(){
   global $ranks,$wpdb;
   if(function_exists('is_multisite') && is_multisite()){
     $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
   } else{
     $ranks = (array)get_option('wpachievements_ranks_data');
   }
   if($ranks[0]==''){
    $ranks[0] = __('Newbie', WPACHIEVEMENTS_TEXT_DOMAIN);
    if(function_exists('is_multisite') && is_multisite()){
      update_blog_option(1,'wpachievements_ranks_data', $ranks);
    } else{
     update_option('wpachievements_ranks_data', $ranks);
    }
   }
  ksort($ranks);
  //*************** Admin Area Layout ***************\\
  echo '<div class="wrap">
    <h1>'.__('Ranks', WPACHIEVEMENTS_TEXT_DOMAIN).'</h1>
    '.__('Setup ranks for your users', WPACHIEVEMENTS_TEXT_DOMAIN).'<br /><br />
    <div id="error_holder"></div>
    <form name="wpachievements_ranks_data_form" method="post" id="add_rank_form">
      <input type="hidden" name="wpachievements_ranks_data_form_submit" value="Y" />
      <h3>'.__('Create New Rank', WPACHIEVEMENTS_TEXT_DOMAIN).'</h3>
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><label for="wpachievements_ranks_data_rank">'.__('Rank Name', WPACHIEVEMENTS_TEXT_DOMAIN).':</label></th>
          <td valign="middle"><input type="text" id="wpachievements_ranks_data_rank" name="wpachievements_ranks_data_rank" value="'.get_option('wpachievements_ranks_data_rank').'" size="40" /></td>
        </tr>
        <tr valign="top">';
          if(function_exists('is_multisite') && is_multisite()){
            global $wpdb;
            if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
              $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status'));
            } else{
              $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status'));
            }
          } else{
            $ranktype = strtolower(get_option('wpachievements_rank_type'));
          }
          if( $ranktype != 'achievements' ){
            echo '<th scope="row"><label for="wpachievements_ranks_data_points">'.__('Points to reach this rank', WPACHIEVEMENTS_TEXT_DOMAIN).':</label></th>';
          } else{
            echo '<th scope="row"><label for="wpachievements_ranks_data_points">'.__('No. Achievements', WPACHIEVEMENTS_TEXT_DOMAIN).':</label></th>';
          }
          echo '<td valign="middle"><input type="text" id="wpachievements_ranks_data_points" name="wpachievements_ranks_data_points" value="0" size="40" /></td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="upload_image">'.__('Rank Image', WPACHIEVEMENTS_TEXT_DOMAIN).':</label></th>
          <td valign="middle"><input id="upload_image" type="text" name="upload_image" value="" /><input class="button button-secondary" id="upload_image_button" type="button" value="'.__('Upload Image', WPACHIEVEMENTS_TEXT_DOMAIN).'" /></td>
        </tr>
      </table>
      <p class="submit">
        <input type="submit" name="Submit" id="rank_save" class="button button-primary" value="'.__('Add Rank', WPACHIEVEMENTS_TEXT_DOMAIN).'" />
        <div class="clear"></div>
      </p>
    </form>
    <br /><br />
    <table id="wpachievements_table" class="widefat datatables rank_table">
      <thead>
        <tr>
          <th scope="col">'.__('Rank', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>
          <th scope="col" width="150" style="text-align:center;">'.__('Image', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>';
          if(function_exists('is_multisite') && is_multisite()){
            global $wpdb;
            if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
              $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status'));
            } else{
              $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status'));
            }
          } else{
            $ranktype = strtolower(get_option('wpachievements_rank_type'));
          }
          if( $ranktype != 'achievements' ){
            echo '<th scope="col" width="150" style="text-align:center;">'.__('Points', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>';
          } else{
            echo '<th scope="col" width="150" style="text-align:center;">'.__('Achievements', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>';
          }
          echo '<th scope="col" width="150">'.__('Action', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th scope="col">'.__('Rank', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>
          <th scope="col" width="150" style="text-align:center;">'.__('Image', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>';
          if(function_exists('is_multisite') && is_multisite()){
            global $wpdb;
            if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
              $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status'));
            } else{
              $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status'));
            }
          } else{
            $ranktype = strtolower(get_option('wpachievements_rank_type'));
          }
          if( $ranktype != 'achievements' ){
            echo '<th scope="col" style="text-align:center;">'.__('Points', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>';
          } else{
            echo '<th scope="col" style="text-align:center;">'.__('Achievements', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>';
          }
          echo '<th scope="col">'.__('Action', WPACHIEVEMENTS_TEXT_DOMAIN).'</th>
        </tr>
      </tfoot>';
        $i=0;
        global $ranks; foreach($ranks as $points=>$rank):
        if( get_bloginfo('version') >= 3.8 ){
          $i++;
          if($i % 2 === 0){$alt='';} else{$alt=' class="alt"';}
        }
        echo '<tr id="rank_'.$points.'"'.$alt.'>
          <td><strong><div id="rank_edit_'.$points.'">'; if(is_array($rank)){ echo $rank[0]; } else{ echo $rank; } echo '</div></strong></td>
          <td style="text-align:center;"><strong><div id="image_edit_'.$points.'">'; if(is_array($rank)){ echo '<img src="'.$rank[1].'" alt="Rank '.$rank[0].' Image" style="max-width:150px;max-height:30px;" />'; } else{ echo 'None'; } echo '</div></strong></td>
          <td style="text-align:center;"><div id="points_edit_'.$points.'">'.$points.'</div></td>
          <td>
            <a href="javascript:void(0);" id="wpachievements_ranks_action_edit_'.$points.'" class="rank_edit_link">'.__('Edit', WPACHIEVEMENTS_TEXT_DOMAIN).'</a>
            <a href="javascript:void(0);" id="wpachievements_ranks_action_save_'.$points.'" class="rank_save_link" style="display:none;">'.__('Save', WPACHIEVEMENTS_TEXT_DOMAIN).'</a>
            <form method="post" name="wpachievements_ranks_action_remove_'.$points.'" id="wpachievements_ranks_action_remove_'.$points.'" style="display:inline;">
              <input type="hidden" name="wpachievements_rank_remove" value="'.$points.'" />
               | <a href="javascript:void(0);" id="ranks_action_remove_'.$points.'" class="wpachievements_rank_remove">'.__('Remove', WPACHIEVEMENTS_TEXT_DOMAIN).'</a>
              <a href="javascript:void(0);" id="rank_cancel_link_'.$points.'" class="rank_cancel_link" style="display:none;">'.__('Cancel', WPACHIEVEMENTS_TEXT_DOMAIN).'</a>
            </form>
          </td>
        </tr>';
        endforeach;
    echo '</table>
  </div>
  <script type="text/javascript">
  jQuery(function () {
    jQuery("#wpachievements_ranks_data_type").selectbox();
  });
  </script>';
 }
/**
 *********************************************************
 *   W P A C H I E V E M E N T S   U S E R   A D M I N   *
 *********************************************************
 */
 //*************** Add Columns to User List ***************\\
 if(function_exists('is_multisite') && is_multisite()){
   add_filter('wpmu_users_columns', 'wpachievements_add_custom_user_columns');
 } else{
   add_filter('manage_users_columns', 'wpachievements_add_custom_user_columns');
 }
 add_action('manage_users_custom_column',  'wpachievements_show_custom_user_columns', 10, 3);

 function wpachievements_add_custom_user_columns($columns){

   return array_merge( $columns,
     array('user_points' => 'Points', 'user_achievements' => 'Achievements') );

 }
 function wpachievements_show_custom_user_columns($value, $column_name, $user_id){

   do_action('wpachievements_user_admin_load', $user_id);

   $user = get_userdata( $user_id );
   if( 'user_points' == $column_name ){
     if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
       $userpoints = (int)cp_getPoints($user_id);
     } elseif(function_exists(WPACHIEVEMENTS_MYCRED)){
       $pointType = get_option('wpachievements_mycred_point_type');
       $userpoints = (int)mycred_get_users_cred( $user_id, $pointType );
     } else{
       $userpoints = (int)get_user_meta( $user_id, 'achievements_points', true );
     }
     if(empty($userpoints)){$userpoints=0;}
     return $userpoints;
   }
   if( 'user_achievements' == $column_name ){
     $userachievement = get_user_meta($user_id, 'achievements_gained', true );
     if(!empty($userachievement) && $userachievement != ''){
       $user_achievements_list = '';
       $iii=0;
       foreach($userachievement as $achievement){
         $user_achievements_list .= get_achievement_name($achievement);
         if( !empty($user_achievements_list) ){
           $iii++;
         }
         if(end($userachievement) !== $achievement){
           $user_achievements_list .= ', ';
         }
       }
       if( $iii < 1 ){
         $user_achievements_list = 'None';
       }
     } else{
       $user_achievements_list = 'None';
     }

     return $user_achievements_list;
   }
   return $value;
 }

 //*************** Add Fields to User Profile ***************\\
 if(function_exists('is_multisite') && is_multisite()){
   if( is_network_admin() ){
     add_action( 'show_user_profile', 'wpachievements_show_extra_profile_fields' );
     add_action( 'edit_user_profile', 'wpachievements_show_extra_profile_fields' );
   }
 } else{
   add_action( 'show_user_profile', 'wpachievements_show_extra_profile_fields' );
   add_action( 'edit_user_profile', 'wpachievements_show_extra_profile_fields' );
 }

 function wpachievements_show_extra_profile_fields( $user ){

   global $current_user;
   get_currentuserinfo();

   do_action('wpachievements_user_profile_load', $user->ID);
   if( function_exists('is_super_admin') && is_super_admin() ){

    if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
     $userpoints = (int)cp_getPoints($user->ID);
    } elseif(function_exists(WPACHIEVEMENTS_MYCRED)){
      $pointType = get_option('wpachievements_mycred_point_type');
     $userpoints = (int)mycred_get_users_cred( $user->ID, $pointType );
    } else{
     $userpoints = (int)get_user_meta( $user->ID, 'achievements_points', true );
    }
    if(empty($userpoints)){$userpoints=0;}
    ?>
    <br/>
    <h3><?php echo __('WPAchievements Management',WPACHIEVEMENTS_TEXT_DOMAIN); ?></h3>
    <?php if(!function_exists(WPACHIEVEMENTS_MYCRED)){ ?>
      <table class="form-table">
       <tr>
        <th><label for="wpa_points"><?php echo __('Points',WPACHIEVEMENTS_TEXT_DOMAIN); ?></label></th>
        <td>
         <input type="text" name="wpa_points" id="wpa_points" value="<?php echo $userpoints; ?>" class="regular-text" /><br />
        </td>
       </tr>
      </table>
      <?php
    }
    $args = array(
      'post_type' => 'wpachievements',
      'post_status' => 'publish',
      'posts_per_page' => -1
    );
    $ii=0;
    $achievement_query = new WP_Query( $args );
    if( $achievement_query->have_posts() ){
      $userachievement = get_user_meta( $user->ID, 'achievements_gained', true );
      ?>
      <table class="form-table">
        <tr>
          <th><label><?php echo __('Achievements',WPACHIEVEMENTS_TEXT_DOMAIN); ?></label></th>
          <td>
          <?php
          while( $achievement_query->have_posts() ){
            $achievement_query->the_post();
            $ach_ID = get_the_ID();
            $ach_title = get_the_title();
            $ach_desc = get_the_content();
            $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
            $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
            if( $ach_woopoints > 0 ){
              $ach_points = sprintf( __('%d Points and %d WooPoints', WPACHIEVEMENTS_TEXT_DOMAIN), $ach_points, $ach_woopoints );
            } else{
              $ach_points = sprintf( __('%d Points', WPACHIEVEMENTS_TEXT_DOMAIN), $ach_points );
            }
            if($userachievement){
              if(in_array($ach_ID,$userachievement)){
                echo '<label><input type="checkbox" checked="checked" name="achi[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
              } else{
                echo '<label><input type="checkbox" name="achi[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
              }
            } else{
              echo '<label><input type="checkbox" name="achi[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
            }
          }
          ?>
          </td>
        </tr>
      </table>
      <br/>
      <?php
    }
    wp_reset_postdata();
    $args = array(
      'post_type' => 'wpquests',
      'post_status' => 'publish',
      'posts_per_page' => -1
    );
    $ii=0;
    $quest_query = new WP_Query( $args );
    if( $quest_query->have_posts() ){
      $userquest = get_user_meta( $user->ID, 'quests_gained', true );
      ?>
      <table class="form-table">
        <tr>
          <th><label><?php echo __('Quests',WPACHIEVEMENTS_TEXT_DOMAIN); ?></label></th>
          <td>
          <?php
          while( $quest_query->have_posts() ){
            $quest_query->the_post();
            $ach_ID = get_the_ID();
            $ach_title = get_the_title();
            $ach_desc = get_the_content();
            $ach_points = get_post_meta( $ach_ID, '_quest_points', true );
            $ach_woopoints = get_post_meta( $ach_ID, '_quest_woo_points', true );
            if( $ach_woopoints > 0 ){
              $ach_points = sprintf( __('%d Points and %d WooPoints', WPACHIEVEMENTS_TEXT_DOMAIN), $ach_points, $ach_woopoints );
            } else{
              $ach_points = sprintf( __('%d Points', WPACHIEVEMENTS_TEXT_DOMAIN), $ach_points );
            }
            if($userquest){
              if(in_array($ach_ID,$userquest)){
                echo '<label><input type="checkbox" checked="checked" name="quest[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
              } else{
                echo '<label><input type="checkbox" name="quest[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
              }
            } else{
              echo '<label><input type="checkbox" name="quest[]" value="'. $ach_ID .'" /> '. $ach_title .' - '. $ach_desc .' <small>('. $ach_points .')</small></label><br />';
            }
          }
          ?>
          </td>
        </tr>
      </table>
      <br/>
      <?php
    }
   }
 }

 if(function_exists('is_multisite') && is_multisite()){
   if( is_network_admin() ){
     add_action( 'personal_options_update', 'wpachievements_save_profile_achievements' );
     add_action( 'edit_user_profile_update', 'wpachievements_save_profile_achievements' );
   }
 } else{
   add_action( 'personal_options_update', 'wpachievements_save_profile_achievements' );
   add_action( 'edit_user_profile_update', 'wpachievements_save_profile_achievements' );
 }

 function wpachievements_save_profile_achievements( $user_id ){
  if( function_exists('is_super_admin') && is_super_admin() ){

   global $wpdb;
   if( !isset($_POST['wpa_points']) ){
     $new_points = 0;
   } else{
     $new_points = $_POST['wpa_points'];
   }
   if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
     $current_points = (int)cp_getPoints($user_id);
     if( $new_points > $current_points ){
       $dif_points = $new_points - $current_points;
       cp_alterPoints($user_id, $dif_points);
       cp_log( 'admin', $user_id, $dif_points, 1);
     }
     if( $new_points < $current_points ){
       $dif_points = $current_points - $new_points;
       cp_alterPoints($user_id, -$dif_points);
       cp_log( 'admin', $user_id, -$dif_points, 1);
     }
   } elseif(!function_exists(WPACHIEVEMENTS_MYCRED)){
     $current_points = get_user_meta( $user_id, 'achievements_points', true );
     if( $new_points != $current_points ){
       update_user_meta( $user_id, 'achievements_points', $new_points );
     }
   }

   if( isset($_POST['achi']) ){
     $newachievements = $_POST['achi'];
   } else{
     $newachievements = '';
   }
   $userachievement = get_user_meta( $user_id, 'achievements_gained', true );

   if( !empty($newachievements) && $newachievements != '' ){
     if( !empty($userachievement) && $userachievement != '' ){
       if( is_array($newachievements) ){
        $addachievements = array_diff($newachievements, $userachievement);
        $removeachievements = array_diff($userachievement, $newachievements);
       } else{
        if( empty($newachievements) || $newachievements == '' ){
          $removeachievements = $newachievements;
        } else{
          if( !array_key_exists($newachievements, $userachievement) ){
            $addachievements = $userachievement;
          }
        }
       }
     } else{
       $addachievements = $newachievements;
       $removeachievements = '';
     }
   } else{
     $addachievements = '';
     $removeachievements = $userachievement;
   }

   if( !empty($addachievements) && $addachievements != '' ){
     $args = array(
       'post_type' => 'wpachievements',
       'post__in' => $addachievements,
       'post_status' => 'publish',
       'posts_per_page' => -1
     );
     $achievement_query = new WP_Query( $args );
     if( $achievement_query->have_posts() ){
       while( $achievement_query->have_posts() ){
         $achievement_query->the_post();
         $ach_ID = get_the_ID();
         $ach_title = get_the_title();
         $ach_desc = get_the_content();
         $ach_data = $ach_title.': '.$ach_desc;
         $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
         $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
         $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );
         $type = 'wpachievements_achievement_'.get_post_meta( $ach_ID, '_achievement_type', true );

         if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
           cp_points('wpachievements_achievement_'.str_replace(" ", "", strtolower($ach_title)), $user_id, $ach_points, $ach_data );
         }
         if(function_exists(WPACHIEVEMENTS_MYCRED)){
           $pointType = get_option('wpachievements_mycred_point_type');
           mycred_add( 'new_achievement', $user_id, $ach_points, '%plural% for Achievement: '.$ach_title, '', '', $pointType );
         }
         wpachievements_increase_points( $user_id, $ach_points );
         wpachievements_increase_wcpr_points( $user_id, $ach_points, 'wpachievements_achievement' );

         $achievement = array( $ach_title, $ach_desc, $ach_points, '', $type, '', $ach_img, $ach_woopoints );

         do_action( 'wpachievements_admin_add_achievement', $user_id, $ach_ID, $achievement );

         if(function_exists('is_multisite') && is_multisite()){
           $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
         } else{
           $table = $wpdb->prefix.'achievements';
         }
         $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user_id, '$type', '$ach_data', '%d', '')", $ach_points) );

         if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) && ( !empty($ach_woopoints) && $ach_woopoints > 0 ) ){
           if(function_exists('is_multisite') && is_multisite()){
             $wcpr_sync = get_blog_option(1, 'wpachievements_wcpr_sync_enabled' );
           } else{
             $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
           }
           if( $wcpr_sync != 'true' ){
             $wcdata = array( 'achievement_id' => $ach_ID );
             WC_Points_Rewards_Manager::increase_points( $user_id, $ach_woopoints, 'wpachievements_achievement', $wcdata );
           }
         }

         $footerTriggers = wpa_get_footer_triggers();
         $typeCheck = get_post_meta( $ach_ID, '_achievement_type', true );

         /*if( in_array($typeCheck,$footerTriggers) ){
           $ach_meta = get_user_meta( $user_id, 'wpachievements_got_new_ach_footer', true );
           if( $ach_meta ){
             if( !in_array_r( $ach_title, $ach_meta ) && !in_array_r( $ach_desc, $ach_meta ) && !in_array_r( $ach_img, $ach_meta )  ){
               $ach_meta[] = array( "title" => $ach_title, "text" => $ach_desc, "image" => $ach_img);
               update_user_meta( $user_id, 'wpachievements_got_new_ach_footer', $ach_meta );
             }
           } else{
             $ach_meta[] = array( "title" => $ach_title, "text" => $ach_desc, "image" => $ach_img);
             update_user_meta( $user_id, 'wpachievements_got_new_ach_footer', $ach_meta );
           }
         } else{*/
           $ach_meta = get_user_meta( $user_id, 'wpachievements_got_new_ach', true );
           if( $ach_meta ){
             if( !in_array_r( $ach_title, $ach_meta ) && !in_array_r( $ach_desc, $ach_meta ) && !in_array_r( $ach_img, $ach_meta )  ){
               $ach_meta[] = array( "title" => $ach_title, "text" => $ach_desc, "image" => $ach_img);
               update_user_meta( $user_id, 'wpachievements_got_new_ach', $ach_meta );
             }
           } else{
             $ach_meta[] = array( "title" => $ach_title, "text" => $ach_desc, "image" => $ach_img);
             update_user_meta( $user_id, 'wpachievements_got_new_ach', $ach_meta );
           }
         /*}*/
         update_post_meta( $ach_ID, '_user_gained_'.$user_id, $user_id );
       }
     }
     wp_reset_postdata();
   }
   if( !empty($removeachievements) && $removeachievements != '' ){
     $args = array(
       'post_type' => 'wpachievements',
       'post__in' => $removeachievements,
       'post_status' => 'publish',
       'posts_per_page' => -1
     );
     $achievement_query = new WP_Query( $args );
     if( $achievement_query->have_posts() ){
       while( $achievement_query->have_posts() ){
         $achievement_query->the_post();
         $ach_ID = get_the_ID();
         $ach_title = get_the_title();
         $ach_desc = get_the_content();
         $ach_data = $ach_title.': '.$ach_desc;
         $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
         $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
         $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );

         if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
           cp_points('wpachievements_removed', $user_id, -$ach_points, $ach_data );
         }
         if(function_exists(WPACHIEVEMENTS_MYCRED)){
           $pointType = get_option('wpachievements_mycred_point_type');
           mycred_subtract( 'wpachievements_removed', $user_id, $ach_points, '%plural% for Achievement Removed: '.$ach_title, '', '', $pointType );
         }
         wpachievements_decrease_points( $user_id, $ach_points, 'wpachievements_removed' );
         wpachievements_decrease_wcpr_points( $user_id, $ach_points, 'wpachievements_achievement_removed' );

         do_action( 'wpachievements_remove_achievement', $user_id, $ach_ID );
         do_action( 'wpachievements_admin_remove_achievement', $user_id, 'wpachievements_removed', $ach_points );

         if(function_exists('is_multisite') && is_multisite()){
           $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
         } else{
           $table = $wpdb->prefix.'achievements';
         }
         $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user_id, 'wpachievements_removed', '$ach_data', '-%d', '')", $ach_points) );

         if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) && ( !empty($ach_woopoints) && $ach_woopoints > 0 ) ){
           if(function_exists('is_multisite') && is_multisite()){
             $wcpr_sync = get_blog_option( 1, 'wpachievements_wcpr_sync_enabled' );
           } else{
             $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
           }
           if( $wcpr_sync != 'true' ){
             $wcdata = array( 'achievement_id' => $ach_ID );
             WC_Points_Rewards_Manager::decrease_points( $user_id, $ach_woopoints, 'wpachievements_achievement_removed', $wcdata );
           }
         }

         delete_post_meta( $ach_ID, '_user_gained_'.$user_id );

         $ach_meta = get_user_meta( $user_id, 'wpachievements_got_new_ach', true );
         if( in_array_r( $ach_title, $ach_meta ) && in_array_r( $ach_desc, $ach_meta ) && in_array_r( $ach_img, $ach_meta ) ){
           foreach( $ach_meta as $key => $value ){
             if( $value["title"] == $ach_title && $value["text"] == $ach_desc && $value["image"] == $ach_img ){ unset($ach_meta[$key]); }
           }
           update_user_meta( $user_id, 'wpachievements_got_new_ach', $ach_meta );
         }

       }
     }
     wp_reset_postdata();
   }
   if( empty($newachievements) || $newachievements == '' ){
    $size = 0;
   } else{
    $size = sizeof($newachievements);
   }
   update_user_meta( $user_id, 'achievements_gained', $newachievements );
   update_user_meta( $user_id, 'achievements_count', $size);




   if( isset($_POST['quest']) ){
     $newquests = $_POST['quest'];
   } else{
     $newquests = '';
   }
   $userquest = get_user_meta( $user_id, 'quests_gained', true );

   if( !empty($newquests) && $newquests != '' ){
     if( !empty($userquest) && $userquest != '' ){
       if( is_array($newquests) ){
        $addquests = array_diff($newquests, $userquest);
        $removequests = array_diff($userquest, $newquests);
       } else{
        if( empty($newquests) || $newquests == '' ){
          $removequests = $newquests;
        } else{
          if( !array_key_exists($newquests, $userquest) ){
            $addquests = $userquest;
          }
        }
       }
     } else{
       $addquests = $newquests;
       $removequests = '';
     }
   } else{
     $addquests = '';
     $removequests = $userquest;
   }

   if( !empty($addquests) && $addquests != '' ){
     $args = array(
       'post_type' => 'wpquests',
       'post__in' => $addquests,
       'post_status' => 'publish',
       'posts_per_page' => -1
     );
     $quest_query = new WP_Query( $args );
     if( $quest_query->have_posts() ){
       while( $quest_query->have_posts() ){
         $quest_query->the_post();
         $quest_ID = get_the_ID();
         $quest_title = get_the_title();
         $quest_desc = get_the_content();
         $quest_data = $quest_title.': '.$quest_desc;
         $quest_points = get_post_meta( $quest_ID, '_quest_points', true );
         $quest_woopoints = get_post_meta( $quest_ID, '_quest_woo_points', true );
         $quest_img = get_post_meta( $quest_ID, '_quest_image', true );
         $type = 'wpachievements_quest';

         if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
           cp_points('wpachievements_quest_'.$quest_ID, $user_id, $quest_points, $quest_data );
         }
         if(function_exists(WPACHIEVEMENTS_MYCRED)){
           $pointType = get_option('wpachievements_mycred_point_type');
           mycred_add( 'new_quest', $user_id, $quest_points, '%plural% for Quest: '.$quest_title, '', '', $pointType );
         }
         wpachievements_increase_points( $user_id, $quest_points );
         wpachievements_increase_wcpr_points( $user_id, $quest_points, 'wpachievements_quest' );

         do_action( 'wpachievements_admin_add_quest', $user_id, $type, $quest_points );

         if(function_exists('is_multisite') && is_multisite()){
           $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
         } else{
           $table = $wpdb->prefix.'achievements';
         }
         $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user_id, '$type', '$quest_data', '%d', '')", $quest_points) );

         if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) && ( !empty($quest_woopoints) && $quest_woopoints > 0 ) ){
           if(function_exists('is_multisite') && is_multisite()){
             $wcpr_sync = get_blog_option(1, 'wpachievements_wcpr_sync_enabled' );
           } else{
             $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
           }
           if( $wcpr_sync != 'true' ){
             $wcdata = array( 'quest_id' => $quest_ID );
             WC_Points_Rewards_Manager::increase_points( $user_id, $quest_woopoints, 'wpachievements_quest', $wcdata );
           }
         }

         $quest_meta = get_user_meta( $user_id, 'wpachievements_got_new_quest', true );
         if( !in_array_r( $quest_title, $quest_meta ) && !in_array_r( $quest_desc, $quest_meta ) && !in_array_r( $quest_img, $quest_meta )  ){
           $quest_meta[] = array( "title" => $quest_title, "text" => $quest_desc, "image" => $quest_img);
           update_user_meta( $user_id, 'wpachievements_got_new_quest', $quest_meta );
           update_post_meta( $quest_ID, '_user_gained_'.$user_id, $user_id );
         }

       }
     }
     wp_reset_postdata();
   }
   if( !empty($removequests) && $removequests != '' ){
     $args = array(
       'post_type' => 'wpquests',
       'post__in' => $removequests,
       'post_status' => 'publish',
       'posts_per_page' => -1
     );
     $quest_query = new WP_Query( $args );
     if( $quest_query->have_posts() ){
       while( $quest_query->have_posts() ){
         $quest_query->the_post();
         $quest_ID = get_the_ID();
         $quest_title = get_the_title();
         $quest_desc = get_the_content();
         $quest_data = $quest_title.': '.$quest_desc;
         $quest_points = get_post_meta( $quest_ID, '_quest_points', true );
         $quest_woopoints = get_post_meta( $quest_ID, '_quest_woo_points', true );
         $quest_img = get_post_meta( $quest_ID, '_quest_image', true );

         if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
           cp_points('wpachievements_quest_removed', $user_id, -$quest_points, $quest_data );
         }
         if(function_exists(WPACHIEVEMENTS_MYCRED)){
           $pointType = get_option('wpachievements_mycred_point_type');
           mycred_subtract( 'wpachievements_quest_removed', $user_id, $quest_points, '%plural% for Quest Removed: '.$quest_title, '', '', $pointType );
         }
         wpachievements_decrease_points( $user_id, $quest_points, 'wpachievements_quest_removed' );
         wpachievements_decrease_wcpr_points( $user_id, $quest_points, 'wpachievements_quest_removed' );

         do_action( 'wpachievements_admin_remove_quest', $user_id, 'wpachievements_quest_removed', $quest_points );

         if(function_exists('is_multisite') && is_multisite()){
           $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
         } else{
           $table = $wpdb->prefix.'achievements';
         }
         $wpdb->query( $wpdb->prepare("INSERT INTO `".$table."` (uid, type, data, points, rank) VALUES ($user_id, 'wpachievements_quest_removed', '$quest_data', '-%d', '')", $quest_points) );

         if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) && ( !empty($quest_woopoints) && $quest_woopoints > 0 ) ){
           if(function_exists('is_multisite') && is_multisite()){
             $wcpr_sync = get_blog_option( 1, 'wpachievements_wcpr_sync_enabled' );
           } else{
             $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
           }
           if( $wcpr_sync != 'true' ){
             $wcdata = array( 'quest_id' => $quest_ID );
             WC_Points_Rewards_Manager::decrease_points( $user_id, $quest_woopoints, 'wpachievements_quest_removed', $wcdata );
           }
         }

         delete_post_meta( $quest_ID, '_user_gained_'.$user_id );

         $quest_meta = get_user_meta( $user_id, 'wpachievements_got_new_quest', true );
         if( in_array_r( $quest_title, $quest_meta ) && in_array_r( $quest_desc, $quest_meta ) && in_array_r( $quest_img, $quest_meta )  ){
           foreach( $quest_meta as $key => $value ){
             if( $value["title"] == $quest_title && $value["text"] == $quest_desc && $value["image"] == $quest_img ){ unset($quest_meta[$key]); }
           }
         }
         update_user_meta( $user_id, 'wpachievements_got_new_quest', $quest_meta );

       }
     }
     wp_reset_postdata();
   }
   if( empty($newquests) || $newquests == '' ){
    $size = 0;
   } else{
    $size = sizeof($newquests);
   }
   update_user_meta( $user_id, 'quests_gained', $newquests );
   update_user_meta( $user_id, 'quests_count', $size);

  }
 }


/**
 ***********************************************************************
 *   W P A C H I E V E M E N T S   S U P P O R T E D   P L U G I N S   *
 ***********************************************************************
 */
 //*************** Update Admin Menu Tabs ***************\\
 function wpachievements_supported_plugins(){
   ?>
   <img src="<?php echo plugins_url('wpachievements/img/logo.png'); ?>" alt="WPAchievements Logo" width="35" style="float:left;margin:6px 5px 0 0;" />
   <h2><?php echo __('WPAchievements - Supported Plugins', WPACHIEVEMENTS_TEXT_DOMAIN); ?></h2>
   <p style="padding-right:20px;">WPAchievements comes complete with a list of user events that you can link achievements to, this enables you to reward your users for being active. Here is a list of supported plugins:</p>
   <div id="plugin-support-images">
     <div class="item"><a href="http://wordpress.org/plugins/buddystream/" target="_blank" title="View: BuddyStream"><img src="<?php echo plugins_url('wpachievements/img/banners/buddystream.png'); ?>" alt="BuddyStream Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/buddypress/" target="_blank" title="View: BuddyPress"><img src="<?php echo plugins_url('wpachievements/img/banners/buddypress.png'); ?>" alt="BuddyPress Banner" /></a></div>
     <div class="item"><a href="http://simple-press.com/" target="_blank" title="View: Simple:Press"><img src="<?php echo plugins_url('wpachievements/img/banners/simplepress.png'); ?>" alt="Simple:Press Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/bbpress/" target="_blank" title="View: bbPress"><img src="<?php echo plugins_url('wpachievements/img/banners/bbpress.png'); ?>" alt="bbPress Banner" /></a></div>
     <div class="item"><a href="http://www.learndash.com/" target="_blank" title="View: LearnDash"><img src="<?php echo plugins_url('wpachievements/img/banners/learndash.png'); ?>" alt="LearnDash Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/gd-star-rating/" target="_blank" title="View: GD Star Rating"><img src="<?php echo plugins_url('wpachievements/img/banners/gd-star-rating.png'); ?>" alt="GD Star Rating Banner" /></a></div>
     <div class="item"><a href="http://www.wpcourseware.com/" target="_blank" title="View: WP-Courseware"><img src="<?php echo plugins_url('wpachievements/img/banners/wp-courseware.png'); ?>" alt="WP-Courseware Banner" /></a></div>
     <div class="item"><a href="http://www.gravityforms.com/" target="_blank" title="View: Gravity Forms"><img src="<?php echo plugins_url('wpachievements/img/banners/gravity-forms.png'); ?>" alt="Gravity Forms Banner" /></a></div>
     <div class="item"><a href="http://myarcadeplugin.com/" target="_blank" title="View: MyArcadePlugin"><img src="<?php echo plugins_url('wpachievements/img/banners/myarcadeplugin.png'); ?>" alt="MyArcadePlugin Banner" /></a></div>
     <div class="item"><a href="http://exells.com/shop/arcade-plugins/myarcadecontest/" target="_blank" title="View: MyArcadeContest"><img src="<?php echo plugins_url('wpachievements/img/banners/myarcadecontest.png'); ?>" alt="MyArcadeContest Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/mycred/" target="_blank" title="View: myCRED"><img src="<?php echo plugins_url('wpachievements/img/banners/mycred.png'); ?>" alt="myCRED Banner" /></a></div>
     <div class="item"><a href="http://codecanyon.net/item/userpro-user-profiles-with-social-login/5958681" target="_blank" title="View: UserPro"><img src="<?php echo plugins_url('wpachievements/img/banners/userpro.png'); ?>" alt="UserPro Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/cubepoints/" target="_blank" title="View: CubePoints"><img src="<?php echo plugins_url('wpachievements/img/banners/cubepoints.png'); ?>" alt="CubePoints Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/woocommerce/" target="_blank" title="View: BuddyStream"><img src="<?php echo plugins_url('wpachievements/img/banners/woocommerce.png'); ?>" alt="WooCommerce Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/wp-e-commerce/" target="_blank" title="View: WP e-Commerce"><img src="<?php echo plugins_url('wpachievements/img/banners/wp-e-commerce.png'); ?>" alt="WP e-Commerce Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/jigoshop/" target="_blank" title="View: Jigoshop"><img src="<?php echo plugins_url('wpachievements/img/banners/jigoshop.png'); ?>" alt="Jigoshop Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/wp-favorite-posts/" target="_blank" title="View: WP Favorite Posts"><img src="<?php echo plugins_url('wpachievements/img/banners/wp-favorite-posts.png'); ?>" alt="WP Favorite Posts Banner" /></a></div>
     <div class="item"><a href="http://wordpress.org/plugins/invite-anyone/" target="_blank" title="View: Invite Anyone"><img src="<?php echo plugins_url('wpachievements/img/banners/invite-anyone.png'); ?>" alt="Invite Anyone Banner" /></a></div>
     <div class="item"><img src="<?php echo plugins_url('wpachievements/img/banners/wordpress.png'); ?>" alt="WordPress Banner" /></div>
   </div>
   <?php
 }



 /**
 *************************************************************************
 *   W P A C H I E V E M E N T S   L A T E S T   I N F O R M A T I O N   *
 *************************************************************************
 */
 /*function wpachievements_important_notice(){
   if(function_exists('is_multisite') && is_multisite()){
     $notice_dismiss = get_blog_option(1, 'wpachievements_important_notice_status');
   } else{
     $notice_dismiss = get_option('wpachievements_important_notice_status');
   }
   wp_enqueue_script( 'ImportantNoticeScript', plugins_url('/js/info-notice.js', __FILE__) );
   if( empty($notice_dismiss) ){
     echo '<div class="updated" style="background-color:#ffd7d7;border-color:#F00;">
       <strong><p style="display:inline-block;">'. __('Important changes have been made to WPAchievements that could effect your website!!!', WPACHIEVEMENTS_TEXT_DOMAIN) .'&nbsp;</p></strong>
       <a href="javascript:void(0);" id="wpa_important_submit">View Changes</a>
     </div>';
   }
 }*/

 /*if(function_exists('is_multisite') && is_multisite()){
   add_action( 'network_admin_notices', 'wpachievements_important_notice' );
   global $blog_id;
   if($blog_id == 1){
     add_action( 'admin_notices', 'wpachievements_important_notice' );
   }
 } else{
   add_action( 'admin_notices', 'wpachievements_important_notice' );
 }*/

function wpachievements_faq() {
  /*if(function_exists('is_multisite') && is_multisite()) {
    $notice_status = get_blog_option(1,'wpachievements_important_notice_status');
    if( empty($notice_status) ){
      update_blog_option(1,'wpachievements_important_notice_status', 'visited');
    }
  }
  else {
    $notice_status = get_option('wpachievements_important_notice_status');
    if( empty($notice_status) ){
      update_option('wpachievements_important_notice_status', 'visited');
    }
  }*/
  ?>
  <div id="wpa_faq" class="wrap">
    <h1 class="wpa_main_title">Frequently Asked Questions</h1>
    <div class="wpa_pull_left">
      <h3>Purchase Code Issues</h3>
      <div class="wpa_faq_question">
        <h3 class="small_text" title="Click to view answer...">It says that my "Purchase Code" is invalid, what should I do?</h3>
        <div class="wpa_faq_answer">
          <p>Firstly, check that the purchase code you have entered is correct; it should look like this: xxxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx</p>
          <p>If the code is correct then follow these steps:</p>
          <ol>
            <li><span>Log in to your WordPress admin area</span></li>
            <li><span>Navigate to "Plugins" -> "Installed Plugins"</span></li>
            <li><span>Locate "WPAchievements" in your plugin list</span></li>
            <li><span>Click the "Check for updates" link</span></li>
            <li><span>If an update is available then navigate back to WPAchievements and click the "Update now" link</span></li>
          </ol>
        </div>
      </div>
      <div class="wpa_faq_question">
        <h3 title="Click to view answer...">It says that my "Purchase Code" is in use on another website, what should I do?</h3>
        <div class="wpa_faq_answer">
          <p>Firstly, If you wish to use WPAchievements on more then one website then you will need to purcase a new license for each website.</p>
          <p>If you have entered your purchase code on another website, such as a test install, and wish to move WPAchievements a new website, then simply follow these steps:</p>
          <ol>
            <li><span>Send an email trough the <a href="http://codecanyon.net/item/wpachievements-wordpress-achievements-plugin/4265703/support">contact form</a> that contains:</span>
              <ul>
                <li><span>Website URL that the purchase code is currently used on</span></li>
                <li><span>Website URL that you wish to use the purchase code on</span></li>
                <li><span>Your purchase code</span></li>
              </ul>
            </li>
            <li><span>Wait until you near from us and then follow these steps:</span>
              <ul>
                <li><span>Log in to your WordPress admin area</span></li>
                <li><span>Navigate to "Plugins" -> "Installed Plugins"</span></li>
                <li><span>Locate "WPAchievements" in your plugin list</span></li>
                <li><span>Click the "Check for updates" link</span></li>
                <li><span>If an update is available then navigate back to WPAchievements and click the "Update now" link</span></li>
              </ul>
            </li>
          </ol>
        </div>
      </div>
    </div>
    <div class="wpa_pull_left">
      <h3>LearnDash Issues</h3>
      <div class="wpa_faq_question">
        <h3 class="small_text" title="Click to view answer...">Achievements for specific quizzes that dont work, why?</h3>
        <div class="wpa_faq_answer">
          <p>If you have created achievements for specific quizzes then you have to make sure that you have entered the correct Quiz ID, this can be located by:</p>
          <ol>
            <li><span>The ID for advanced quizzes can be easily found by going to "Advanced Quiz" and looking at the "ID" column</span></li>
            <li><span>The ID for standard quizzes are more complicated, follow these steps:</span>
              <ul>
                <li><span>Navigate to "Quizzes"</span></li>
                <li><span>Find the quiz that you wish to get the ID for and click to "Edit" the quiz</span></li>
                <li><span>Look at the URL and you will see something like this: "post.php?post=1234&amp;action=edit"</span></li>
                <li><span>The quiz ID is the number that appears after "post.php?post=", in this example it is: 1234</span></li>
              </ul>
            </li>
          </ol>
        </div>
      </div>
      <div class="wpa_faq_question">
        <h3 title="Click to view answer...">When a user completes a quiz they do not get points or achievements until they move to another page, why?</h3>
        <div class="wpa_faq_answer">
          <p>LearnDash quizzes use AJAX to submit quiz results, this means that the results are handled in the background while the user stays on the same page. To overcome this WPAchievements has the ability to run "Automatic Checks", this enabled WPAchievements to check for achievements without needing a page to refresh.</p>
          <p>To activate WPAChievements "Automatic Checks", follow these steps:</p>
          <ol>
            <li><span>Log in to your WordPress admin area</span></li>
            <li><span>Navigate to "WPAchievements" -> "Settings"</span></li>
            <li><span>Click on the "Achievement Popup" tab</span></li>
            <li><span>Change the "Popup Automatic Checks" to the number of seconds that you wish WPAchievements to wait inbetween checks.</span></li>
            <li><span>Click "Save All Changes"</span></li>
          </ol>
          <p><strong>Important Note:</strong> Unless you have a powerful server, we recommend setting the time to around 10-15 seconds so that your server does not become overwhelmed.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <h2>Available Shortcodes</h2>
  <div id="wpa_change_log_outter">
    <div id="wpa_change_log">
      <h2 style="font-weight:bold;">My Achievements</h2>
      <p>Copy this to any post/page to display a list of achievement images that the user has gained. <code class="wpa_code_blue">[wpa_myachievements]</code></p>
      <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
      <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Parameter</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alternate">
            <th>user_id</th>
            <td class="wpa_doc_desc">The ID of the user to list achievement images for. If blank it defaults to current logged in user.</td>
          </tr>
          <tr>
            <th>show_title</th>
            <td class="wpa_doc_desc">Whether to display the title: "My Achievements". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
          </tr>
          <tr class="alternate">
            <th>title_class</th>
            <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
          </tr>
          <tr>
            <th>image_holder_class</th>
            <td class="wpa_doc_desc">This class will be added to the achievement image holder and will allow the use of custom CSS.</td>
          </tr>
          <tr class="alternate">
            <th>image_class</th>
            <td class="wpa_doc_desc">This class will be added to the achievement images in the list and will allow the use of custom CSS.</td>
          </tr>
          <tr>
            <th>image_width</th>
            <td class="wpa_doc_desc">This is the width of each achievement image. Value needs to be in "px". Default is "30"</td>
          </tr>
          <tr class="alternate">
            <th>achievement_limit</th>
            <td class="wpa_doc_desc">Limit the number of achievement images shown. If blank it will show all achievements available.</td>
          </tr>
        </tbody>
      </table>
      <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
      <pre class="wpa_code wpa_code_green">[wpa_myachievements user_id="1" show_title="true" achievement_limit="30"]</pre>
      <pre class="wpa_code wpa_code_green">[wpa_myachievements user_id="2" show_title="false" image_width="20" achievement_limit="10"]</pre>
      <div class="wpa_shortcode_sep"></div>
      <h2 style="font-weight:bold;">Achievements by Rank</h2>
      <p>Copy this to any post/page to display a list of achievement available for the choosen rank. <code class="wpa_code_blue">[wpa_rank_achievements]</code></p>
      <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
      <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Parameter</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alternate">
            <th>user_id</th>
            <td class="wpa_doc_desc">The ID of the user to get the rank to list achievement images for. If blank "rank" parameter will be used.</td>
          </tr>
          <tr>
            <th>rank</th>
            <td class="wpa_doc_desc">The rank to list achievement images for. If blank achievements will not be shown.</td>
          </tr>
          <tr class="alternate">
            <th>show_title</th>
            <td class="wpa_doc_desc">Whether to display the title: "My Achievements". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
          </tr>
          <tr>
            <th>title_class</th>
            <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
          </tr>
          <tr class="alternate">
            <th>image_holder_class</th>
            <td class="wpa_doc_desc">This class will be added to the achievement image holder and will allow the use of custom CSS.</td>
          </tr>
          <tr>
            <th>image_class</th>
            <td class="wpa_doc_desc">This class will be added to the achievement images in the list and will allow the use of custom CSS.</td>
          </tr>
          <tr class="alternate">
            <th>image_width</th>
            <td class="wpa_doc_desc">This is the width of each achievement image. Value needs to be in "px". Default is "30"</td>
          </tr>
          <tr>
            <th>achievement_limit</th>
            <td class="wpa_doc_desc">Limit the number of achievement images shown. If blank it will show all achievements available.</td>
          </tr>
        </tbody>
      </table>
      <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
      <pre class="wpa_code wpa_code_green">[wpa_rank_achievements rank="Newbie" show_title="true" achievement_limit="30"]</pre>
      <pre class="wpa_code wpa_code_green">[wpa_rank_achievements user_id="1" show_title="false" image_width="20" achievement_limit="10"]</pre>
      <div class="wpa_shortcode_sep"></div>
      <h2 style="font-weight:bold;">My Quests</h2>
      <p>Copy this to any post/page to display a list of quest images that the user has gained. <code class="wpa_code_blue">[wpa_myquests]</code></p>
      <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
      <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Parameter</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alternate">
            <th>user_id</th>
            <td class="wpa_doc_desc">The ID of the user to list quest images for. If blank it defaults to current logged in user.</td>
          </tr>
          <tr>
            <th>show_title</th>
            <td class="wpa_doc_desc">Whether to display the title: "My Quests". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
          </tr>
          <tr class="alternate">
            <th>title_class</th>
            <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
          </tr>
          <tr>
            <th>image_holder_class</th>
            <td class="wpa_doc_desc">This class will be added to the quest image holder and will allow the use of custom CSS.</td>
          </tr>
          <tr class="alternate">
            <th>image_class</th>
            <td class="wpa_doc_desc">This class will be added to the quest images in the list and will allow the use of custom CSS.</td>
          </tr>
          <tr>
            <th>image_width</th>
            <td class="wpa_doc_desc">This is the width of each quest image. Value needs to be in "px". Default is "30"</td>
          </tr>
          <tr class="alternate">
            <th>quest_limit</th>
            <td class="wpa_doc_desc">Limit the number of quest images shown. If blank it will show all quests available.</td>
          </tr>
        </tbody>
      </table>
      <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
      <pre class="wpa_code wpa_code_green">[wpa_myquests user_id="1" show_title="false" image_width="30" quest_limit="30"]</pre>
      <pre class="wpa_code wpa_code_green">[wpa_myquests user_id="2" show_title="true" image_class="custom_image_class" quest_limit="10"]</pre>
      <div class="wpa_shortcode_sep"></div>
      <h2 style="font-weight:bold;">My Rank</h2>
      <p>Copy this to any post/page to display the current rank information of the user. <code class="wpa_code_blue">[wpa_myranks]</code></p>
      <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
      <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Parameter</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alternate">
            <th>user_id</th>
            <td class="wpa_doc_desc">The ID of the user to get the rank for. If blank it defaults to current logged in user.</td>
          </tr>
          <tr>
            <th>rank_image</th>
            <td class="wpa_doc_desc">Whether to show the rank image, if one is available. "True" to show or "False" to hide. If blank it defaults to true.</td>
          </tr>
          <tr class="alternate">
            <th>show_title</th>
            <td class="wpa_doc_desc">Whether to display the title: "My Rank". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
          </tr>
          <tr>
            <th>title_class</th>
            <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
          </tr>
        </tbody>
      </table>
      <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
      <pre class="wpa_code wpa_code_green">[wpa_myranks user_id="1" show_title="false" rank_image="true"]</pre>
      <pre class="wpa_code wpa_code_green">[wpa_myranks user_id="2" show_title="true" rank_image="false" title_class="custom_title_class"]</pre>
      <div class="wpa_shortcode_sep"></div>
      <h2 style="font-weight:bold;">Unformatted Leaderboard List</h2>
      <p>Copy this to any post/page to display an unformatted leaderboard list. <code class="wpa_code_blue">[wpa_leaderboard_list]</code></p>
      <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
      <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Parameter</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alternate">
            <th>user_position</th>
            <td class="wpa_doc_desc">Whether to show the trophy icons/place numbering. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
          </tr>
          <tr>
            <th>user_ranking</th>
            <td class="wpa_doc_desc">Whether to show the users rank information. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
          </tr>
          <tr class="alternate">
            <th>type</th>
            <td class="wpa_doc_desc">Whether to order the leaderboard by amount of points or achievements. Example: "Points" or "Achievements". If blank it defaults to Achievements.</td>
          </tr>
          <tr>
            <th>limit</th>
            <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
          </tr>
          <tr>
            <th>list_class</th>
            <td class="wpa_doc_desc">This class will be added to the leaderboard list and will allow the use of custom CSS.</td>
          </tr>
        </tbody>
      </table>
      <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
      <pre class="wpa_code wpa_code_green">[wpa_leaderboard_list user_position="true" user_ranking="false" type="points" limit="10"]</pre>
      <pre class="wpa_code wpa_code_green">[wpa_leaderboard_list user_position="false" user_ranking="true" type="achievements" limit="10"]</pre>
      <div class="wpa_shortcode_sep"></div>
      <h2 style="font-weight:bold;">Custom Achievement Trigger</h2>
      <p>Copy this to any post/page to trigger a custom achievement. <code class="wpa_code_blue">[wpa_custom_achievement]</code></p>
      <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
      <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Parameter</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alternate">
            <th>trigger_id</th>
            <td class="wpa_doc_desc">This is the unique "Trigger ID" that is used when creating the custom achievement in the "Achievements" area.</td>
          </tr>
          <tr>
            <th>type</th>
            <td class="wpa_doc_desc">Whether to produce a button or trigger the achievement when the post/page loads. Example: "Button" or "Instant". If blank it defaults to Button.</td>
          </tr>
          <tr class="alternate">
            <th>text</th>
            <td class="wpa_doc_desc">If the type "Button" is choosen then this text is displayed within the button.</td>
          </tr>
        </tbody>
      </table>
      <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
      <pre class="wpa_code wpa_code_green">[wpa_custom_achievement trigger_id="unique_trigger_id" type="button" text="Click for Achievement"]</pre>
      <pre class="wpa_code wpa_code_green">[wpa_custom_achievement trigger_id="unique_trigger_id" type="instant"]</pre>
      <div class="wpa_shortcode_sep"></div>
      <h2 style="font-weight:bold;">Standard Leaderboard</h2>
      <p>Copy this to any post/page to display a standard leaderboard. <code class="wpa_code_blue">[wpa_leaderboard_widget]</code></p>
      <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
      <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Parameter</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alternate">
            <th>type</th>
            <td class="wpa_doc_desc">Whether to order the leaderboard by amount of points or achievements. Example: "Points" or "Achievements". If blank it defaults to Achievements.</td>
          </tr>
          <tr>
            <th>limit</th>
            <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
          </tr>
        </tbody>
      </table>
      <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
      <pre class="wpa_code wpa_code_green">[wpa_leaderboard_widget type="points" limit="10"]</pre>
      <pre class="wpa_code wpa_code_green">[wpa_leaderboard_widget type="achievements" limit="10"]</pre>
      <div class="wpa_shortcode_sep"></div>
      <h2 style="font-weight:bold;">Leaderboard Data Table</h2>
      <p>Copy this to any post/page to display an advanced leaderboard data table. <code class="wpa_code_blue">[wpa_leaderboard]</code></p>
      <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
      <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Parameter</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr class="alternate">
            <th>position_numbers</th>
            <td class="wpa_doc_desc">Whether to show leaderboard position numbering. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
          </tr>
          <tr>
            <th>columns</th>
            <td class="wpa_doc_desc">Select which columns to display. Available Inputs: avatar,points,rank,achievements,quests. If blank it defaults to true.</td>
          </tr>
          <tr class="alternate">
            <th>limit</th>
            <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
          </tr>
          <tr>
            <th>achievement_limit</th>
            <td class="wpa_doc_desc">Limit the number of achievements shown. If blank it will show all achievements available.</td>
          </tr>
          <tr class="alternate">
            <th>quest_limit</th>
            <td class="wpa_doc_desc">Limit the number of quests shown. If blank it will show all quests available.</td>
          </tr>
          <tr>
            <th>list_class</th>
            <td class="wpa_doc_desc">This class will be added to the leaderboard list and will allow the use of custom CSS.</td>
          </tr>
        </tbody>
      </table>
      <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
      <pre class="wpa_code wpa_code_green">[wpa_leaderboard position_numbers="true" achievement_limit="10" quest_limit="10" limit="10" columns="avatar,points,rank,achievements,quests"]</pre>
      <pre class="wpa_code wpa_code_green">[wpa_leaderboard position_numbers="false" limit="10" list_class="my_custom_class" columns="avatar,points,achievements"]</pre>
    </div>
  </div>
  <?php
}

function wpachievements_documentation(){
 ?>
 <div id="wpa_change_log_outter">
   <h1 class="wpa_main_title">WPAchievements Shortcodes</h1>
   <div id="wpa_change_log">
     <h2 style="font-weight:bold;">My Achievements</h2>
     <p>Copy this to any post/page to display a list of achievement images that the user has gained. <code class="wpa_code_blue">[wpa_myachievements]</code></p>
     <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
     <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
       <thead>
         <tr>
           <th>Parameter</th>
           <th>Description</th>
         </tr>
       </thead>
       <tbody>
         <tr class="alternate">
           <th>user_id</th>
           <td class="wpa_doc_desc">The ID of the user to list achievement images for. If blank it defaults to current logged in user.</td>
         </tr>
         <tr>
           <th>show_title</th>
           <td class="wpa_doc_desc">Whether to display the title: "My Achievements". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
         </tr>
         <tr class="alternate">
           <th>title_class</th>
           <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
         </tr>
         <tr>
           <th>image_holder_class</th>
           <td class="wpa_doc_desc">This class will be added to the achievement image holder and will allow the use of custom CSS.</td>
         </tr>
         <tr class="alternate">
           <th>image_class</th>
           <td class="wpa_doc_desc">This class will be added to the achievement images in the list and will allow the use of custom CSS.</td>
         </tr>
         <tr>
           <th>image_width</th>
           <td class="wpa_doc_desc">This is the width of each achievement image. Value needs to be in "px". Default is "30"</td>
         </tr>
         <tr class="alternate">
           <th>achievement_limit</th>
           <td class="wpa_doc_desc">Limit the number of achievement images shown. If blank it will show all achievements available.</td>
         </tr>
       </tbody>
     </table>
     <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
     <pre class="wpa_code wpa_code_green">[wpa_myachievements user_id="1" show_title="true" achievement_limit="30"]</pre>
     <pre class="wpa_code wpa_code_green">[wpa_myachievements user_id="2" show_title="false" image_width="20" achievement_limit="10"]</pre>
     <div class="wpa_shortcode_sep"></div>
     <h2 style="font-weight:bold;">Achievements by Rank</h2>
     <p>Copy this to any post/page to display a list of achievement available for the choosen rank. <code class="wpa_code_blue">[wpa_rank_achievements]</code></p>
     <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
     <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
       <thead>
         <tr>
           <th>Parameter</th>
           <th>Description</th>
         </tr>
       </thead>
       <tbody>
         <tr class="alternate">
           <th>user_id</th>
           <td class="wpa_doc_desc">The ID of the user to get the rank to list achievement images for. If blank "rank" parameter will be used.</td>
         </tr>
         <tr>
           <th>rank</th>
           <td class="wpa_doc_desc">The rank to list achievement images for. If blank achievements will not be shown.</td>
         </tr>
         <tr class="alternate">
           <th>show_title</th>
           <td class="wpa_doc_desc">Whether to display the title: "My Achievements". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
         </tr>
         <tr>
           <th>title_class</th>
           <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
         </tr>
         <tr class="alternate">
           <th>image_holder_class</th>
           <td class="wpa_doc_desc">This class will be added to the achievement image holder and will allow the use of custom CSS.</td>
         </tr>
         <tr>
           <th>image_class</th>
           <td class="wpa_doc_desc">This class will be added to the achievement images in the list and will allow the use of custom CSS.</td>
         </tr>
         <tr class="alternate">
           <th>image_width</th>
           <td class="wpa_doc_desc">This is the width of each achievement image. Value needs to be in "px". Default is "30"</td>
         </tr>
         <tr>
           <th>achievement_limit</th>
           <td class="wpa_doc_desc">Limit the number of achievement images shown. If blank it will show all achievements available.</td>
         </tr>
       </tbody>
     </table>
     <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
     <pre class="wpa_code wpa_code_green">[wpa_rank_achievements rank="Newbie" show_title="true" achievement_limit="30"]</pre>
     <pre class="wpa_code wpa_code_green">[wpa_rank_achievements user_id="1" show_title="false" image_width="20" achievement_limit="10"]</pre>
     <div class="wpa_shortcode_sep"></div>
     <h2 style="font-weight:bold;">My Quests</h2>
     <p>Copy this to any post/page to display a list of quest images that the user has gained. <code class="wpa_code_blue">[wpa_myquests]</code></p>
     <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
     <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
       <thead>
         <tr>
           <th>Parameter</th>
           <th>Description</th>
         </tr>
       </thead>
       <tbody>
         <tr class="alternate">
           <th>user_id</th>
           <td class="wpa_doc_desc">The ID of the user to list quest images for. If blank it defaults to current logged in user.</td>
         </tr>
         <tr>
           <th>show_title</th>
           <td class="wpa_doc_desc">Whether to display the title: "My Quests". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
         </tr>
         <tr class="alternate">
           <th>title_class</th>
           <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
         </tr>
         <tr>
           <th>image_holder_class</th>
           <td class="wpa_doc_desc">This class will be added to the quest image holder and will allow the use of custom CSS.</td>
         </tr>
         <tr class="alternate">
           <th>image_class</th>
           <td class="wpa_doc_desc">This class will be added to the quest images in the list and will allow the use of custom CSS.</td>
         </tr>
         <tr>
           <th>image_width</th>
           <td class="wpa_doc_desc">This is the width of each quest image. Value needs to be in "px". Default is "30"</td>
         </tr>
         <tr class="alternate">
           <th>quest_limit</th>
           <td class="wpa_doc_desc">Limit the number of quest images shown. If blank it will show all quests available.</td>
         </tr>
       </tbody>
     </table>
     <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
     <pre class="wpa_code wpa_code_green">[wpa_myquests user_id="1" show_title="false" image_width="30" quest_limit="30"]</pre>
     <pre class="wpa_code wpa_code_green">[wpa_myquests user_id="2" show_title="true" image_class="custom_image_class" quest_limit="10"]</pre>
     <div class="wpa_shortcode_sep"></div>
     <h2 style="font-weight:bold;">My Rank</h2>
     <p>Copy this to any post/page to display the current rank information of the user. <code class="wpa_code_blue">[wpa_myranks]</code></p>
     <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
     <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
       <thead>
         <tr>
           <th>Parameter</th>
           <th>Description</th>
         </tr>
       </thead>
       <tbody>
         <tr class="alternate">
           <th>user_id</th>
           <td class="wpa_doc_desc">The ID of the user to get the rank for. If blank it defaults to current logged in user.</td>
         </tr>
         <tr>
           <th>rank_image</th>
           <td class="wpa_doc_desc">Whether to show the rank image, if one is available. "True" to show or "False" to hide. If blank it defaults to true.</td>
         </tr>
         <tr class="alternate">
           <th>show_title</th>
           <td class="wpa_doc_desc">Whether to display the title: "My Rank". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
         </tr>
         <tr>
           <th>title_class</th>
           <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
         </tr>
       </tbody>
     </table>
     <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
     <pre class="wpa_code wpa_code_green">[wpa_myranks user_id="1" show_title="false" rank_image="true"]</pre>
     <pre class="wpa_code wpa_code_green">[wpa_myranks user_id="2" show_title="true" rank_image="false" title_class="custom_title_class"]</pre>
     <div class="wpa_shortcode_sep"></div>
     <h2 style="font-weight:bold;">Unformatted Leaderboard List</h2>
     <p>Copy this to any post/page to display an unformatted leaderboard list. <code class="wpa_code_blue">[wpa_leaderboard_list]</code></p>
     <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
     <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
       <thead>
         <tr>
           <th>Parameter</th>
           <th>Description</th>
         </tr>
       </thead>
       <tbody>
         <tr class="alternate">
           <th>user_position</th>
           <td class="wpa_doc_desc">Whether to show the trophy icons/place numbering. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
         </tr>
         <tr>
           <th>user_ranking</th>
           <td class="wpa_doc_desc">Whether to show the users rank information. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
         </tr>
         <tr class="alternate">
           <th>type</th>
           <td class="wpa_doc_desc">Whether to order the leaderboard by amount of points or achievements. Example: "Points" or "Achievements". If blank it defaults to Achievements.</td>
         </tr>
         <tr>
           <th>limit</th>
           <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
         </tr>
         <tr>
           <th>list_class</th>
           <td class="wpa_doc_desc">This class will be added to the leaderboard list and will allow the use of custom CSS.</td>
         </tr>
       </tbody>
     </table>
     <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
     <pre class="wpa_code wpa_code_green">[wpa_leaderboard_list user_position="true" user_ranking="false" type="points" limit="10"]</pre>
     <pre class="wpa_code wpa_code_green">[wpa_leaderboard_list user_position="false" user_ranking="true" type="achievements" limit="10"]</pre>
     <div class="wpa_shortcode_sep"></div>
     <h2 style="font-weight:bold;">Custom Achievement Trigger</h2>
     <p>Copy this to any post/page to trigger a custom achievement. <code class="wpa_code_blue">[wpa_custom_achievement]</code></p>
     <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
     <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
       <thead>
         <tr>
           <th>Parameter</th>
           <th>Description</th>
         </tr>
       </thead>
       <tbody>
          <tr class="alternate">
           <th>trigger_id</th>
           <td class="wpa_doc_desc">This is the unique "Trigger ID" that is used when creating the custom achievement in the "Achievements" area.</td>
         </tr>
         <tr>
           <th>type</th>
           <td class="wpa_doc_desc">Whether to produce a button or trigger the achievement when the post/page loads. Example: "Button" or "Instant". If blank it defaults to Button.</td>
         </tr>
         <tr class="alternate">
           <th>text</th>
           <td class="wpa_doc_desc">If the type "Button" is choosen then this text is displayed within the button.</td>
         </tr>
       </tbody>
     </table>
     <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
     <pre class="wpa_code wpa_code_green">[wpa_custom_achievement trigger_id="unique_trigger_id" type="button" text="Click for Achievement"]</pre>
     <pre class="wpa_code wpa_code_green">[wpa_custom_achievement trigger_id="unique_trigger_id" type="instant"]</pre>
     <div class="wpa_shortcode_sep"></div>
     <h2 style="font-weight:bold;">Standard Leaderboard</h2>
     <p>Copy this to any post/page to display a standard leaderboard. <code class="wpa_code_blue">[wpa_leaderboard_widget]</code></p>
     <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
     <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
       <thead>
         <tr>
           <th>Parameter</th>
           <th>Description</th>
         </tr>
       </thead>
       <tbody>
          <tr class="alternate">
           <th>type</th>
           <td class="wpa_doc_desc">Whether to order the leaderboard by amount of points or achievements. Example: "Points" or "Achievements". If blank it defaults to Achievements.</td>
         </tr>
         <tr>
           <th>limit</th>
           <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
         </tr>
       </tbody>
     </table>
     <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
     <pre class="wpa_code wpa_code_green">[wpa_leaderboard_widget type="points" limit="10"]</pre>
     <pre class="wpa_code wpa_code_green">[wpa_leaderboard_widget type="achievements" limit="10"]</pre>
     <div class="wpa_shortcode_sep"></div>
     <h2 style="font-weight:bold;">Leaderboard Data Table</h2>
     <p>Copy this to any post/page to display an advanced leaderboard data table. <code class="wpa_code_blue">[wpa_leaderboard]</code></p>
     <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
     <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
       <thead>
         <tr>
           <th>Parameter</th>
           <th>Description</th>
         </tr>
       </thead>
       <tbody>
         <tr class="alternate">
           <th>position_numbers</th>
           <td class="wpa_doc_desc">Whether to show leaderboard position numbering. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
         </tr>
         <tr>
           <th>columns</th>
           <td class="wpa_doc_desc">Select which columns to display. Available Inputs: avatar,points,rank,achievements,quests. If blank it defaults to true.</td>
         </tr>
         <tr class="alternate">
           <th>limit</th>
           <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
         </tr>
         <tr>
           <th>achievement_limit</th>
           <td class="wpa_doc_desc">Limit the number of achievements shown. If blank it will show all achievements available.</td>
         </tr>
         <tr class="alternate">
           <th>quest_limit</th>
           <td class="wpa_doc_desc">Limit the number of quests shown. If blank it will show all quests available.</td>
         </tr>
         <tr>
           <th>list_class</th>
           <td class="wpa_doc_desc">This class will be added to the leaderboard list and will allow the use of custom CSS.</td>
         </tr>
       </tbody>
     </table>
     <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
     <pre class="wpa_code wpa_code_green">[wpa_leaderboard position_numbers="true" achievement_limit="10" quest_limit="10" limit="10" columns="avatar,points,rank,achievements,quests"]</pre>
     <pre class="wpa_code wpa_code_green">[wpa_leaderboard position_numbers="false" limit="10" list_class="my_custom_class" columns="avatar,points,achievements"]</pre>
   </div>
 </div>
 <div id="wpa_latest_products">
   <h1 class="wpa_main_title">Our Latest Products</h1>
   <?php
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, 'http://marketplace.envato.com/api/v1/new-files-from-user:DigitalBuilder,codecanyon.json');
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $ch_data = curl_exec($ch);
   curl_close($ch);

   if(!empty($ch_data)){
     $prod_descriptions = array( 'WP User Management Plus' => 'WP User Management Plus is a WordPress plugin that gives you massively improved control over your users when compared to the default WordPress User Management functionality.' );
     if( get_bloginfo('version') >= 3.8 ){ $prod_descriptions = array( 'WP User Management Plus' => 'WP User Management Plus gives you massively improved control over your users when compared to the default WordPress User Management functionality.' ); }
     $json_data = json_decode($ch_data, true);
     $data_count = count($json_data['new-files-from-user']) -1;

     for($i = 0; $i <= $data_count; $i++){
       if( $json_data['new-files-from-user'][$i]['item'] != 'WPAchievements - WordPress Achievements Plugin' ){
         echo '<div class="wpa_product_item">
           <div class="wpa_product_links">
             <img src="'.$json_data['new-files-from-user'][$i]['thumbnail'].'" width="80" height="80" alt="" class="wpa_pull_left">
             <a href="'.$json_data['new-files-from-user'][$i]['url'].'" target="_blank">View Details</a>
           </div>
           <h3>'.$json_data['new-files-from-user'][$i]['item'].' <small>(WordPress Plugin)</small></h3>
           <p>'.$prod_descriptions[$json_data['new-files-from-user'][$i]['item']].'</p>
           <span><strong>Cost:</strong> $'.$json_data['new-files-from-user'][$i]['cost'].'</span>
           <span>&nbsp;-&nbsp;<strong>Released:</strong> '.date('F j, Y',strtotime($json_data['new-files-from-user'][$i]['uploaded_on'])).'</span>
           <div class="wpa_clear"></div>
         </div>';
       }
     }
   }
   echo '<div class="wpa_product_item">
      <div class="wpa_product_links">
        <img src="'.plugins_url('wpachievements/img/magazine_arcade.jpg').'" width="80" height="80" alt="" class="wpa_pull_left">
        <a href="http://exells.com/shop/arcade-themes/magazine-arcade/" target="_blank">View Details</a>
      </div>
      <h3>Magazine Arcade <small>(WordPress Theme)</small></h3>
      <p>Magazine Arcade is a great new WordPress theme for MyArcadePlugin, it is the first fully responsive design which means that it adapts perfectly for all devices.</p>
      <span><strong>Cost:</strong> €25.00</span>
      <span>&nbsp;-&nbsp;<strong>Released:</strong> September 30, 2013</span>
      <div class="wpa_clear"></div>
   </div>';
   ?>
   <?php if( get_bloginfo('version') < 3.8 ){ echo '<br/>'; } ?>
   <div id="wpa_donations">
     <h1 class="wpa_main_title">Donations</h1>
     <p>If you like WPAchievements or any of Digital Builder's products then feel free to show your support by making a donation.</p>
     <p><strong>Note:</strong> Any donations that we receive will be used to help improve our products, pay for support staff or developers, improve our server and equipment and/or help us to purchase premium plugins to integrate with our products.</p>
     <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
       <input type="hidden" name="cmd" value="_s-xclick">
       <input type="hidden" name="hosted_button_id" value="CQRANJNLUHM5Y">
       <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
       <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
     </form>
   </div>
 </div>
 <?php
}

/**
 *********************************************************************
 *   W P A C H I E V E M E N T S   U P D A T E   M E N U   T A B S   *
 *********************************************************************
 */
//*************** Update Admin Menu Tabs ***************\\
function update_wpachievements_points_menu_admin(){
echo "<script>
jQuery(document).ready(function(){
  jQuery('#wp-admin-bar-custom_ranks_menu').load('".get_bloginfo('url')." #wp-admin-bar-custom_ranks_menu > *');
});
  </script>";
}

/**
 *************************************************************
 *   W P A C H I E V E M E N T S   R A N K S   R E M O V E   *
 *************************************************************
 */
 add_action('wp_ajax_wpachievements_remove_rank_ajax', 'wpachievements_remove_rank_ajax_callback');
 function wpachievements_remove_rank_ajax_callback(){
   if (isset($_POST['wpachievements_rank_remove']) != '') {
    if((int)$_POST['wpachievements_rank_remove']==0){
      echo '<div class="error"><p><strong>'. __('A rank name is needed for users with 0 points!<br /><br />Click the edit link to edit this rank.', WPACHIEVEMENTS_TEXT_DOMAIN) .'</strong></p></div>';
    } else{
      if(function_exists('is_multisite') && is_multisite()){
        global $wpdb;
        $ranks = get_blog_option(1,'wpachievements_ranks_data');
        unset($ranks[(int)$_POST['wpachievements_rank_remove']]);
        update_blog_option(1,'wpachievements_ranks_data', $ranks);
      } else{
        $ranks = get_option('wpachievements_ranks_data');
        unset($ranks[(int)$_POST['wpachievements_rank_remove']]);
        update_option('wpachievements_ranks_data', $ranks);
      }
      echo '<div class="updated"><p><strong>'. __('Rank removed', WPACHIEVEMENTS_TEXT_DOMAIN) .'</strong></p></div>';
    }
   }
   die();
 }

/**
 *************************************************************
 *   W P A C H I E V E M E N T S   R A N K S   U P D A T E   *
 *************************************************************
 */
 add_action('wp_ajax_wpachievements_update_rank_ajax', 'wpachievements_update_rank_ajax_callback');
 function wpachievements_update_rank_ajax_callback(){
   if(isset($_POST['wpachievements_ranks_data_rank']) != ''){
     //*************** Admin Form Submission ***************\\
     $wpachievements_ranks_data_rank = trim($_POST['wpachievements_ranks_data_rank']);
     $wpachievements_ranks_data_points = (int) trim($_POST['wpachievements_ranks_data_points']);
     if(function_exists('is_multisite') && is_multisite()){
       $ranks = get_blog_option(1,'wpachievements_ranks_data');
     } else{
       $ranks = get_option('wpachievements_ranks_data');
     }
     if( $wpachievements_ranks_data_rank == '' || $_POST['wpachievements_ranks_data_points'] == '' ){
       echo '<div class="error"><p><strong>'. __('Rank name or points cannot be empty!', WPACHIEVEMENTS_TEXT_DOMAIN) .'</strong></p></div>';
     } elseif(!is_numeric($_POST['wpachievements_ranks_data_points'])||$wpachievements_ranks_data_points<0||(int)$_POST['wpachievements_ranks_data_points']!=(float)$_POST['wpachievements_ranks_data_points']){
       echo '<div class="error"><p><strong>'. __('Please enter only positive integers for the points!', WPACHIEVEMENTS_TEXT_DOMAIN) .'</strong></p></div>';
     } else{
       if( isset($ranks[$wpachievements_ranks_data_points]) ){
         echo '<div class="updated"><p><strong>'. __('Rank Updated', WPACHIEVEMENTS_TEXT_DOMAIN) .'</strong></p></div>';
       } else{
         echo '<div class="updated"><p><strong>'. __('Rank Added', WPACHIEVEMENTS_TEXT_DOMAIN) .'</strong></p></div>';
       }
       if( isset($_POST['editthis'])){unset($ranks[$_POST['editthis']]);}
       if( $_POST['wpachievements_ranks_data_image'] != '' ){
         $ranks[$wpachievements_ranks_data_points] = array($_POST['wpachievements_ranks_data_rank'], $_POST['wpachievements_ranks_data_image']);
       } else{
         $ranks[$wpachievements_ranks_data_points] = $_POST['wpachievements_ranks_data_rank'];
       }

       if(function_exists('is_multisite') && is_multisite()){
         update_blog_option(1,'wpachievements_ranks_data', $ranks);
       } else{
         update_option('wpachievements_ranks_data', $ranks);
       }
     }
   }
   die();
 }

/**
 *********************************************************************
 *   W P A C H I E V E M E N T S   A D D I T I O N A L   S T U F F   *
 *********************************************************************
 */
 //*************** License Notice ***************\\
 function wpachievements_license_notice(){
   if(function_exists('is_multisite') && is_multisite()){
     $license_key = get_blog_option(1, 'wpachievements_license_key');
   } else{
     $license_key = get_option('wpachievements_license_key');
   }
   wp_enqueue_script( 'NoticeScript', plugins_url('/js/notice-script.js', __FILE__) );
   if( empty($license_key) ){
     echo '<div class="updated"><p>
     <strong><p style="display:inline-block;">'. __('Enter Your WPAchievements Purchase Code:', WPACHIEVEMENTS_TEXT_DOMAIN) .'&nbsp;</p></strong>
     <input type="text" name="wpach_license" value="" style="display:inline-block;" />
     <a href="javascript:void(0);" id="license_save" class="button button-primary">Save</a></p>
     </div>';
   }
 }
 if(function_exists('is_multisite') && is_multisite()){
   add_action( 'network_admin_notices', 'wpachievements_license_notice' );
   global $blog_id;
   if($blog_id == 1){
     add_action( 'admin_notices', 'wpachievements_license_notice' );
   }
 } else{
   add_action( 'admin_notices', 'wpachievements_license_notice' );
 }

 function wpachievements_update_notice() {
  $update_response = get_option('external_updates-wpachievements');
  if( isset($update_response->update->license) ){
    if( $update_response->update->license->status == 'invalid' ){
      echo '&nbsp;<font color="#FF0000">'.$update_response->update->license->error.'</font>';
    }
  }
 }
 add_action( 'in_plugin_update_message-wpachievements/wpachievements.php', 'wpachievements_update_notice' );

 add_action('wp_ajax_wpachievements_notice_ajax', 'wpachievements_notice_ajax_callback');
 function wpachievements_notice_ajax_callback(){
   if(isset($_POST['wpa_license'])){
     if(function_exists('is_multisite') && is_multisite()){
       global $wpdb;
       update_blog_option(1,'wpachievements_license_key', $_POST['wpa_license']);
     } else{
       update_option('wpachievements_license_key', $_POST['wpa_license']);
     }
   }
   die();
 }

 add_action('wp_ajax_wpachievements_info_notice_ajax', 'wpachievements_info_notice_ajax_callback');
 function wpachievements_info_notice_ajax_callback(){
   if(isset($_POST['wpa_ignore'])){
     if(function_exists('is_multisite') && is_multisite()){
       global $wpdb;
       update_blog_option(1,'wpachievements_ignore_info', $_POST['wpa_ignore']);
     } else{
       update_option('wpachievements_ignore_info', $_POST['wpa_ignore']);
     }
   }
   die();
 }

?>