<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/**
 *******************************
 *   C H E C K   Q U E S T S   *
 *******************************
 */
 function wpachievements_check_quests($type='', $uid='', $postid='', $points='', $usersrank='', $userachievement=''){
  if(is_user_logged_in() || !empty($uid) && !empty($type)){
    
    if(function_exists('is_multisite') && is_multisite()){
      global $blog_id;
      $curBlog = $blog_id;
      switch_to_blog(1);
    }
    
    global $wpdb, $current_user;
    get_currentuserinfo();
    if(empty($uid)){$uid=$current_user->ID;}
    if(empty($postid)){$postid='';}
       
    if(function_exists('is_multisite') && is_multisite()){
      $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
    } else{
      $table = $wpdb->prefix.'achievements';
    }
    $userquests = get_user_meta( $uid, 'quests_gained', true );
    $usersrank_lvl = wpachievements_rankToPoints($usersrank);
    $notquest = true;
    $ii=0;
    
    global $oldtype;
    $oldtype = '';
    
    if( strpos($type,'wpachievements_achievement_') !== false ){
      global $oldtype;
      $oldtype = $type;
      $type = 'wpachievements_achievement';
    }
    
    if( !empty($userquests) ){
      $args = array(
        'post_type' => 'wpquests',
        'post_status' => 'publish',
        'post__not_in' => $userquests,
        'posts_per_page' => -1,
        'meta_query' => array(
          array(
            'key' => '_quest_details',
            'value' => $type,
            'compare' => 'LIKE'
          )
        )
      );
    } else{
      $args = array(
        'post_type' => 'wpquests',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
          array(
            'key' => '_quest_details',
            'value' => $type,
            'compare' => 'LIKE'
          )
        )
      );
    }

    $quest_query = new WP_Query( $args );
    if( $quest_query->have_posts() ){
      while( $quest_query->have_posts() ){
        $quest_query->the_post();
        $quest_ID = get_the_ID();
        
        $quest_details = get_post_meta( $quest_ID, '_quest_details', true );
        foreach( $quest_details as $quest_item ){
          $type = $quest_item['type'];
          
          if( $type == 'wpachievements_achievement' ){
            $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE type LIKE %s AND postid=%d AND uid=%d", '%'.$type.'%', $quest_item['ach_id'], $uid) );
          } else{
            $activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE type='$type' AND uid=%d", $uid) );
          }
          
          if( $activities_count == 0 || ($activities_count != 0 && $activities_count < $quest_item['occurrences']) ){
            $quest_gained = '';
            break;
          }
        
          if(function_exists('is_multisite') && is_multisite()){
            $blog_limit = $quest_item['blog_limit'];
            if( !empty($blog_limit) ){
              if( $curBlog != $blog_limit ){
                $quest_gained = '';
                break;
              }
            }
          }
        
          $quest_activity_count = $quest_item['occurrences'];
          $quest_postid = $quest_item['associated_id'];
        
          if( $type == 'cp_bp_group_joined' ){
            $quest_group = $quest_item['associated_title'];
            if( !empty($quest_group) && $quest_group != '' ){
              if( !empty($postid) && $postid != '' ){
                $group = groups_get_group( array( 'group_id' => $postid ) );
                if( !empty($group) && $group != '' ){
                  if( $group->name != $quest_group ){
                    $quest_gained = '';
                    break;
                  }
                }
              }
            }
          } elseif( !empty($quest_postid) && $quest_postid != '' ){
            if( $postid == $quest_postid ){
              $this_activities_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE type='%s' AND uid=$uid AND postid=%d", $type,$quest_postid) );
              if( $this_activities_count < $quest_activity_count ){
                $quest_gained = '';
                break;
              }
            } else{
              $quest_gained = '';
              break;
            }
          }
        
          if( $type == 'ld_quiz_perfect' ){
            $quest_first_try_only = $quest_item['ld_first_attempt_only'];
            if( $postid && ($quest_first_try_only == 'enabled' || $quest_first_try_only == 'Enabled') ){
              $attempt_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(type) FROM $table WHERE (type='ld_quiz_pass' OR type='ld_quiz_fail' OR type='ld_quiz_perfect') AND uid=%d AND postid='%d'", $uid,$postid) );
            
              if( !empty($attempt_count) && $attempt_count > 1 ){
                $quest_gained = '';
                break;
              }
            }
          }
  
          if( ($type == 'wc_order_complete' || $type == 'wc_user_spends') && !empty($postid) ){
            $quest_woo_order_limit = $quest_item['woo_order_limit'];
            if( !empty($quest_woo_order_limit) && $quest_woo_order_limit > 0 ){
              $order = new WC_Order($postid);
              $order_total = $order->get_order_total();
              if( empty($order_total) || ($order_total < $quest_woo_order_limit) ){
                $quest_gained = '';
                break;
              }
            }
          }
          
          $quest_gained = 'true';
          
        }
        
        $quest_rank = get_post_meta( $quest_ID, '_quest_rank', true );
        $quest_rank_lvl = wpachievements_rankToPoints($quest_rank);
        if( $usersrank_lvl < $quest_rank_lvl ){
          $quest_gained = '';
        }
        
        if( $quest_gained == 'true' ){
          
          $quest_title = get_the_title();
          $quest_desc = get_the_content();
          $quest_data = $quest_title.': '.$quest_desc;
          $quest_points = get_post_meta( $quest_ID, '_quest_points', true );
          $quest_woopoints = get_post_meta( $quest_ID, '_quest_woo_points', true );
          $quest_rank = get_post_meta( $quest_ID, '_quest_rank', true );
          $quest_details = get_post_meta( $quest_ID, '_quest_details', true );
          $quest_img = get_post_meta( $quest_ID, '_quest_image', true );
          $type = 'wpachievements_quest';
          
          do_action( 'wpachievements_before_new_quest', $uid, $quest_ID );
     
          if(function_exists('is_multisite') && is_multisite()){
            $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
          } else{
            $table = $wpdb->prefix.'achievements';
          }
        
          $wpdb->insert( 
            $table, 
            array( 'uid' => $uid, 'type' => $type, 'rank' => $usersrank, 'data' => $quest_data, 'points' => $quest_points, 'postid' => $postid ), 
            array( '%d', '%s', '%s', '%s', '%d', '%d' ) 
          );
        
          if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
            cp_points('wpachievements_quest_'.$quest_ID, $uid, $quest_points, $quest_data);
          }
          if(function_exists(WPACHIEVEMENTS_MYCRED)){
            $pointType = get_option('wpachievements_mycred_point_type');
            mycred_add( 'new_quest', $uid, $quest_points, '%plural% for Quest: '.$quest_title, $postid, '', $pointType );
          }
        
          wpachievements_increase_points( $uid, $quest_points, 'wpachievements_quest' );
          wpachievements_increase_wcpr_points( $uid, $quest_points, 'wpachievements_quest' );
      
          if( (class_exists(WPACHIEVEMENTS_WOOCOMMERCE) && class_exists(WPACHIEVEMENTS_WOOCOMMERCE_PAR)) && ( !empty($quest_woopoints) && $quest_woopoints > 0 ) ){
            if(function_exists('is_multisite') && is_multisite()){
              $wcpr_sync = get_blog_option(1, 'wpachievements_wcpr_sync_enabled' );
            } else{
              $wcpr_sync = get_option( 'wpachievements_wcpr_sync_enabled' );
            }
            if( $wcpr_sync != 'true' ){
              $wcdata = array( 'quest_id' => $quest_ID );
              WC_Points_Rewards_Manager::increase_points( $uid, $quest_woopoints, 'wpachievements_quest', $wcdata );
            }
          }
        
          $newmeta = array();
          $newuserquests = get_user_meta( $uid, 'quests_gained', true );
          $newmeta = $newuserquests;
          $newmeta[] = $quest_ID;
                
          update_user_meta( $uid, 'quests_gained', $newmeta );
          update_post_meta( $quest_ID, '_user_gained_'.$uid, $uid );
        
          $userquestss = get_user_meta( $uid, 'quests_gained', true );
          $size = sizeof($userquestss);
          update_user_meta($uid, 'quests_count', $size);
        
          $quest_meta = get_user_meta( $uid, 'wpachievements_got_new_quest', true );
          $quest_meta[] = array( "title" => $quest_title, "text" => $quest_desc, "image" => $quest_img);
          update_user_meta( $uid, 'wpachievements_got_new_quest', $quest_meta );
        
          $notquest = false;
          
          $quest = array( $quest_title, $quest_desc, $quest_points, '', $type, '', $quest_img, $quest_woopoints );
        
          do_action( 'wpachievements_after_new_quest', $uid, $quest_ID, $quest );
          
        }
      }
    }
    wp_reset_postdata();
                
    if(function_exists('is_multisite') && is_multisite()){
      restore_current_blog();
    }
    
  }
 }
 add_action('wpachievements_before_new_activity', 'wpachievements_check_quests', 1, 6);
 add_action('wpachievements_before_custom_achievement', 'wpachievements_check_quests', 1, 6);
 add_action('wpachievements_after_new_custom_achievement', 'wpachievements_check_quests', 1, 6);
 add_action('wpachievements_before_new_achievement', 'wpachievements_check_quests', 1, 6);
 
/**
 *****************************************************************
 *   W P A C H I E V E M E N T S   G E T   Q U E S T   N A M E   *
 *****************************************************************
 */
 function get_quest_name($quest=''){
   if(function_exists('is_multisite') && is_multisite()){
     switch_to_blog(1);
   }
   $quest_title = '';
   if( !empty($ach) ){
     $args = array(
       'post_type' => 'wpquests',
       'post_status' => 'publish',
       'posts_per_page' => -1,
       'p' => $quest,
     );
     $quest_query = new WP_Query( $args );
     if( $quest_query->have_posts() ){
       while( $quest_query->have_posts() ){
         $quest_query->the_post();
         $quest_title = get_the_title();
       }
     }
     wp_reset_postdata();
   }
   if(function_exists('is_multisite') && is_multisite()){
     restore_current_blog();
   }
   return $quest_title;
 }
 
/**
 ***********************************************************************
 *   W P A C H I E V E M E N T S   Q U E S T   D E S C R I P T I O N   *
 ***********************************************************************
 */
 function quest_Desc($type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  $text = '';
  if( strpos($type,'wpachievements_achievement_') !== false ){
    $triggerID = substr($type, 27);
    
    global $wpdb;
    
    $ach_title = $wpdb->get_var( $wpdb->prepare("SELECT post_title FROM $wpdb->posts WHERE ID = %s", $triggerID) );
  }
  switch($type){
   case 'dailypoints': { $text = sprintf( __('Visit us %s time(s)', WPACHIEVEMENTS_TEXT_DOMAIN), $times ); } break;
   case 'register': { $text = __('Register with us', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'comment': { $text = sprintf( __('Add %s comment(s)', WPACHIEVEMENTS_TEXT_DOMAIN), $times); } break;
   case 'post': { $text = sprintf( __('Add %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt); } break;
   case 'fb_loggin': { $text = __('Log in with Facebook', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'custom_achievement': { $text = __('Manually awarded by admin', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case strpos($type,'wpachievements_achievement_') !== false: { $text = sprintf( __('Gain the achievement "%s"', WPACHIEVEMENTS_TEXT_DOMAIN), $ach_title); } break;
  }
  return apply_filters('wpachievements_quest_description', $text,$type,$times );
 }
 
 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_default_admin_triggers', 1, 10);
 function achievement_default_admin_triggers($trigger){
   
   switch($trigger){
     case 'custom_achievement': { $trigger = __('Manually Awarded', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case strpos($trigger,'wpachievements_achievement_') !== false: { $trigger = __('The user gains an achievement', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case is_numeric($trigger): { $trigger = __('The user gains an achievement', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }
   
   return $trigger;
   
 }
 
/**
 *************************************************************************
 *   W P A C H I E V E M E N T S   A C H I E V E M E N T   N O T I C E   *
 *************************************************************************
 */
 add_filter( 'heartbeat_received', 'wpa_quest_respond_to_browser', 10, 3 );
 function wpa_quest_respond_to_browser( $response, $data, $screen_id ) {
   if ( isset( $data['wpachievements-quest-check'] ) ) {
   
     $umeta = get_user_meta( $data['wpachievements-quest-check'], 'wpachievements_got_new_quest' );
     if(!empty($umeta)){
       
       $html = '';
       if( function_exists('wpachievements_fb_share_achievement_filter') )
         $html = wpachievements_fb_share_achievement_filter('quest');
  
       delete_user_meta( $data['wpachievements-quest-check'], 'wpachievements_got_new_quest' );
       if(function_exists('is_multisite') && is_multisite()){
         global $wpdb;
         $pop_col = strtolower (get_blog_option(1,'wpachievements_pcol'));
         $pop_time = strtolower (get_blog_option(1,'wpachievements_ptim'));
       } else{
         $pop_col = strtolower (get_option('wpachievements_pcol'));
         $pop_time = strtolower (get_option('wpachievements_ptim'));
       }
       if( empty($pop_col) ){
         $pop_col = '#333333';
       }
       if( strpos($pop_col,'#') === false ){
         $pop_col = '#'.$pop_col;
       }
       
       foreach($umeta as $quests){
         foreach($quests as $thisquest){
           $html .= '<script type="text/javascript">
           jQuery.smallBox({
             title: "'. $thisquest['title'] .'",
             content: "'. str_replace( '"', '\'', $thisquest['text'] ) .'",
             color: "'. $pop_col .'",';
             
             if( $pop_time > 0 ){
               if( $pop_time < 1000 ){
                 $html .= 'timeout: "'.$pop_time.'000",';
               } else{
                 $html .= 'timeout: "'.$pop_time.'",';
               }
             }
             $html .='
             img: "'. $thisquest['image'] .'",
             icon: "'. plugins_url('/popup/img/medal.png', __FILE__) .'",
             extra_type: "quest"
           });
           jQuery("#wp-admin-bar-wpachievements_points_menu").load("'. home_url('').' #wp-admin-bar-wpachievements_points_menu > *");
           </script>';
         }
       }
       
       if( function_exists('wpachievements_twr_share_achievement_return') )
         $html .= wpachievements_twr_share_achievement_return();

       $response['wpachievements-quest-check'] = $html;
       
     }
   }
   return $response;
  
 }
 
/**
 *********************************************************************
 *   W P A C H I E V E M E N T S   A C H I E V E M E N T   L I S T   *
 *********************************************************************
 */
 function wpa_quest_achievement_list(){
   $args = array(
     'post_type' => 'wpachievements',
     'post_status' => 'publish',
     'posts_per_page' => -1,
   );
   $html='';
   $achievement_query = new WP_Query( $args );
   if( $achievement_query->have_posts() ){
     while( $achievement_query->have_posts() ){
       $achievement_query->the_post();
       $ach_ID = get_the_ID();
       $ach_title = get_the_title();
       
       $html .= '<option value="'.$ach_ID.'">'.$ach_title.'</option>';
       
     }
   }
   return $html;
 }
?>