<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 ***************************************************************
 *    W P A C H I E V E M E N T S   C O N T E N T   L O C K    *
 ***************************************************************
 */
 //*************** Actions ***************\\
 if(function_exists('is_multisite') && is_multisite()){
   $rankstatus = get_blog_option(1,'wpachievements_rank_status'); 
 } else{
   $rankstatus = get_option('wpachievements_rank_status'); 
 }
 if($rankstatus != 'Disable'){
  add_action('add_meta_boxes', 'lock_content_custom_box', 1);
  add_action('save_post', 'lock_content_save_postdata');
  add_filter('single_template', 'get_custom_single_template');
  add_filter('wpachievements_check_userlevel_is_ok', 'lock_contest');
  add_action('wpachievements_contest_detail_after_user_level', 'contest_locked_temp');
 }
 //*************** Setup lock functions ***************\\
 function lock_content_custom_box() {
    add_meta_box( 
        'lock_content_sectionid',
        'Lock ' . WPACHIEVEMENTS_POST_TEXT,
        'lock_content_inner_custom_box', 'post', 'side', 'high'
    );
    add_meta_box( 
        'lock_content_sectionid',
        'Lock ' . WPACHIEVEMENTS_POST_TEXT,
        'lock_content_inner_custom_box', 'contest', 'side', 'high'
    );
 }
 function lock_content_inner_custom_box( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'lock_content_noncename' );
  global $post;
  $locked = get_post_meta( $post->ID, 'game_locked', true );
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
  } else{
    $ranks = (array)get_option('wpachievements_ranks_data');
  }
  ksort($ranks);
  echo '<center>
  <label for="lock_content"><strong>Minimum Rank: </strong></label><select id="lock_content" name="lock_content">';
  if(!empty($locked) && $locked!='any'){
    foreach($ranks as $p=>$r){
      if($locked<=$p){
        echo '<option value="'.$p.'"">'; if(is_array($r)){ echo $r[0]; } else{ echo $r; } echo '</option>';
        break;
      }
    }
  }
  else{echo '<option value="any" selected="selected">Any Rank</option>';}
  echo '<option value="">--------------------------</option>';
  if(!empty($locked)){echo '<option value="any">Any Rank</option>';}
  foreach($ranks as $p=>$r){
    echo '<option value="'.$p.'"">'; if(is_array($r)){ echo $r[0]; } else{ echo $r; } echo '</option>';
  }
  echo '</select>
  </center>
  <style>
  #lock_content_holder label{font-weight:bold;}
  </style>';
 }
 function lock_content_save_postdata( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;
  if ( isset($_POST['lock_content_noncename']) ){
    if ( !wp_verify_nonce( $_POST['lock_content_noncename'], plugin_basename( __FILE__ ) ) )
      return;
    if ( !current_user_can( 'edit_post', $post_id ) )
      return;
    if($_POST['lock_content']){$new_meta_value = $_POST['lock_content'];}
    $meta_key = 'game_locked';
    $meta_value = get_post_meta( $post_id, $meta_key, true );
    if ( $new_meta_value && '' == $meta_value )
      add_post_meta( $post_id, $meta_key, $new_meta_value, true );
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
      update_post_meta( $post_id, $meta_key, $new_meta_value );
    elseif ( '' == $new_meta_value && $meta_value )
      delete_post_meta( $post_id, $meta_key, $meta_value );
  }
 }
 function locate_plugin_template($template_names, $load = false, $require_once = true ){
    if ( !is_array($template_names) )
      return '';
    $located = '';
    $this_plugin_dir = WP_PLUGIN_DIR.'/'.str_replace( basename( __FILE__), "", plugin_basename(__FILE__) );
    foreach ( $template_names as $template_name ) {
      if ( !$template_name )
        continue;
      if ( file_exists(get_stylesheet_directory() . '/' . $template_name)) {
        $located = get_stylesheet_directory() . '/' . $template_name;
        break;
      } else if ( file_exists(get_template_directory() . '/' . $template_name) ) {
        $located = get_template_directory() . '/' . $template_name;
        break;
      } else if ( file_exists( $this_plugin_dir .  $template_name) ) {
        $located =  $this_plugin_dir . $template_name;
        break;
      }
    }
    if ( $load && '' != $located )
      load_template( $located, $require_once );
    return $located;
  }
 function get_custom_single_template($template){
  global $post,$current_user,$wpdb;
  get_currentuserinfo();
  $locked = get_post_meta( $post->ID, 'game_locked', true );
  if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
    $rank = get_user_meta( $current_user->ID, 'cpoints', true );
    if(empty($rank)){$rank = 0;}
  } elseif(function_exists(WPACHIEVEMENTS_MYCRED)){
    $pointType = get_option('wpachievements_mycred_point_type');
    $rank = (int)mycred_get_users_cred( $current_user->ID, $pointType );
    if(empty($rank)){$rank = 0;}
  } else{
    $rank = get_user_meta( $current_user->ID, 'achievements_points', true );
    if(empty($rank)){$rank = 0;}
  }
  $type = get_post_type( $post->ID );
  if(!empty($locked) && $locked!='any'){
    if($locked>$rank || !is_user_logged_in()){
      if($type!='contest'){
        $templates = array('wpachievements_template_locked.php', 'single.php');
        $template = locate_plugin_template($templates);
      } else{
        lock_contest(true);
      }
    }
  }
  return $template;
 }
 function lock_contest($lock=false){
  if($lock==true){
    return true;
  }
  return false;
 }
 function contest_locked_temp($postID='', $gameID=''){
   $locked = get_post_meta( $postID, 'game_locked', true );
   echo '<tr>
     <td>Required User Rank</td>
     <td>';
       if($locked!='any'){echo wpachievements_pointsToRank($locked);}
       else{echo 'Open to All';}
     echo '</td>
   </tr>';
 }
?>