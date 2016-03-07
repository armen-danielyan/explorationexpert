<?php
/**
 * Module Name: WordPress Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WordPress
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;
 //*************** Actions ***************\\
 add_action("comment_post", "wpachievements_wordpress_comment", 10 ,2);
 add_action('comment_unapproved_to_approved', 'wpachievements_wordpress_comment_app', 10, 1);
 add_action('comment_trash_to_approved', 'wpachievements_wordpress_comment_app', 10, 1);
 add_action('comment_spam_to_approved', 'wpachievements_wordpress_comment_app', 10, 1);
 add_action('delete_comment', 'wpachievements_wordpress_comment_del', 10, 1);
 add_action("publish_post", "wpachievements_wordpress_post", 10 ,2);
 add_action('before_delete_post', 'wpachievements_wordpress_post_del', 10, 1);
 add_action("user_register", "wpachievements_wordpress_register");
 add_action("wp_login", "wpachievements_wordpress_login", 20, 2);
 add_action("deleted_user", "wpachievements_wordpress_deletion");
 if(!function_exists(WPACHIEVEMENTS_MYARCADE) && !function_exists(WPACHIEVEMENTS_MYARCADE_ALT)){
   add_action("wp_footer", "wpachievements_wordpress_post_view");
 }
 //*************** Detect Comment Added ***************\\
 function wpachievements_wordpress_comment($cid, $status){
  if( !empty($cid) ){
   $cdata = get_comment($cid);
   if( $cdata->user_id ){
     if($status == 1){
       $uid=$cdata->user_id; $postid='';
       if(function_exists('is_multisite') && is_multisite()){
         $comm_count = (int)get_blog_option(1, 'wpachievements_comm_min_count');
         $comm_deduct = (int)get_blog_option(1, 'wpachievements_comm_min_deduct');
       } else{
         $comm_count = (int)get_option('wpachievements_comm_min_count');
         $comm_deduct = (int)get_option('wpachievements_comm_min_deduct');
       }
       if( empty($comm_count) || $comm_count < 1 ){
         $comm_count = 1;
       }
       if( !empty($comm_count) ){
         if( str_word_count($cdata->comment_content) >= $comm_count ){
           $type='comment_added';
           if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
             if(function_exists('is_multisite') && is_multisite()){
               $points = (int)get_blog_option(1, 'wpachievements_comment_points');
             } else{
               $points = (int)get_option('wpachievements_comment_points');
             }
           }
           if(empty($points)){$points=0;}
           wpachievements_new_activity($type, $uid, $postid, $points);
         } else{
           $type='comment_added_bad';
           if( !empty($comm_deduct) && $comm_deduct > 0 ){
             $points=$comm_deduct;
           } else{
             $points=0;
           }
           if(empty($points)){$points=0;}
           wpachievements_new_activity($type, $uid, $postid, -$points);
         }
       } else{
         $type='comment_added';
         if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
           if(function_exists('is_multisite') && is_multisite()){
             $points = (int)get_blog_option(1, 'wpachievements_comment_points');
           } else{
             $points = (int)get_option('wpachievements_comment_points');
           }
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, $points);
       }
     }
   }
  }
 }
 //*************** Detect Comment Approved ***************\\
 function wpachievements_wordpress_comment_app($cid){
  if( !empty($cid) ){
   $cdata = get_comment($cid);
   if( $cdata->user_id ){
     $uid=$cdata->user_id; $postid='';
     if(function_exists('is_multisite') && is_multisite()){
       $comm_count = (int)get_blog_option(1, 'wpachievements_comm_min_count');
       $comm_deduct = (int)get_blog_option(1, 'wpachievements_comm_min_deduct');
     } else{
       $comm_count = (int)get_option('wpachievements_comm_min_count');
       $comm_deduct = (int)get_option('wpachievements_comm_min_deduct');
     }
     if( !empty($comm_count) && $comm_count > 0 ){
       if( str_word_count($cdata->comment_content) >= $comm_count ){
         $type='comment_added';
         if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
           if(function_exists('is_multisite') && is_multisite()){
             $points = (int)get_blog_option(1, 'wpachievements_comment_points');
           } else{
             $points = (int)get_option('wpachievements_comment_points');
           }
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, $points);
       } else{
         $type='comment_added_bad';
         if( !empty($comm_deduct) && $comm_deduct > 0 ){
           $points=$comm_deduct;
         } else{
           $points=0;
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, -$points);
       }
     } else{
       $type='comment_added';
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         if(function_exists('is_multisite') && is_multisite()){
           $points = (int)get_blog_option(1, 'wpachievements_comment_points');
         } else{
           $points = (int)get_option('wpachievements_comment_points');
         }
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   }
  }
 }
 //*************** Detect Comment Deleted ***************\\
 function wpachievements_wordpress_comment_del($cid){
  if( !empty($cid) ){
   $cdata = get_comment($cid);
   $type='comment_remove'; $uid=$cdata->user_id; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     if(function_exists('is_multisite') && is_multisite()){
       $points = (int)get_blog_option(1, 'wpachievements_comment_points');
     } else{
       $points = (int)get_option('wpachievements_comment_points');
     }
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, -$points);
  }
 }
 //*************** Detect Post Added ***************\\
 function wpachievements_wordpress_post($pid, $status){
  if( !empty($pid) ){
   $pdata = get_post( $pid );
   if( $pdata->post_author && $pdata->post_type == 'post' ){
     if(function_exists('is_multisite') && is_multisite()){
       $post_count = (int)get_blog_option(1, 'wpachievements_post_min_count');
       $post_deduct = (int)get_blog_option(1, 'wpachievements_post_min_deduct');
     } else{
       $post_count = (int)get_option('wpachievements_post_min_count');
       $post_deduct = (int)get_option('wpachievements_post_min_deduct');
     }
     if( empty($post_count) || $post_count < 1 ){
       $post_count = 1;
     }
     $uid=$pdata->post_author; $postid=$pid;
     if( !empty($post_count) && $post_count > 0 ){
       if( str_word_count($pdata->post_content) >= $post_count ){
         $type='post_added';
         if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
           if(function_exists('is_multisite') && is_multisite()){
             $points = (int)get_blog_option(1, 'wpachievements_post_points');
           } else{
             $points = (int)get_option('wpachievements_post_points');
           }
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, $points);
       } else{
         $type='post_added_bad';
         if( !empty($post_deduct) && $post_deduct > 0 ){
           $points=$post_deduct;
         } else{
           $points=0;
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, -$points);
       }
     } else{
       $type='post_added';
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         if(function_exists('is_multisite') && is_multisite()){
           $points = (int)get_blog_option(1, 'wpachievements_post_points');
         } else{
           $points = (int)get_option('wpachievements_post_points');
         }
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   }
  }
 }
 //*************** Detect Post Deleted ***************\\
 function wpachievements_wordpress_post_del($pid){
  if( !empty($pid) ){
   $pdata = get_post($pid);
   if( $pdata->post_author && $pdata->post_status == 'trash' && $pdata->post_type == 'post' ){
    $type='post_remove'; $uid=$pdata->post_author; $postid=$pid;
    if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
      if(function_exists('is_multisite') && is_multisite()){
        $points = (int)get_blog_option(1, 'wpachievements_post_points');
      } else{
        $points = (int)get_option('wpachievements_post_points');
      }
    }
    if(empty($points)){$points=0;}
    wpachievements_new_activity($type, $uid, $postid, -$points);
   }
  }
 }
 //*************** Detect User Registration ***************\\
 function wpachievements_wordpress_register($user_id){
   if( !empty($user_id) ){
     $type='user_register'; $uid=$user_id; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
        $points = (int)get_blog_option(1, 'wpachievements_reg_points');
       } else{
         $points = (int)get_option('wpachievements_reg_points');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect User Registration ***************\\
 function wpachievements_wordpress_login($login, $user){
   if( !empty($login) ){
     $points = ''; $end_time = '';
     $user = get_user_by( 'login', $login );
     $last_login = get_user_meta($user->ID, 'last_login', true);

     update_user_meta($user->ID, 'last_login', time());
     if(function_exists('is_multisite') && is_multisite()){
       $delay = (int)get_blog_option(1, 'wpachievements_log_delay');
     } else{
       $delay = (int)get_option('wpachievements_log_delay');
     }
     if( !empty($last_login) && $last_login != '' ){
       if( $delay == 1 ){
         $end_time = strtotime('+ '.$delay.' hour', $last_login);
       } else{
         $end_time = strtotime('+ '.$delay.' hours', $last_login);
       }
     } else{
       $end_time = time();
     }
     if( time() >= $end_time ){
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         if(function_exists('is_multisite') && is_multisite()){
           $points = (int)get_blog_option(1, 'wpachievements_log_points');
         } else{
           $points = (int)get_option('wpachievements_log_points');
         }
       }
       if(empty($points)){$points=0;}
       $type='user_login'; $uid=$user->ID; $postid='';
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   }
 }
 //*************** Detect User Deletion ***************\\
 function wpachievements_wordpress_deletion($user_id){
   if( !empty($user_id) ){
     global $wpdb;
     $wpdb->delete( WPACHIEVEMENTS_ACTIVITY_TABLE, array( 'uid' => $user_id ), array( '%d' ) );
   }
 }
 //*************** Detect User Viewing Post ***************\\
 if(!function_exists(WPACHIEVEMENTS_MYARCADE) && !function_exists(WPACHIEVEMENTS_MYARCADE_ALT)){
   function wpachievements_wordpress_post_view(){
     if( is_user_logged_in() && is_single() ){
       global $post, $current_user, $wpdb;
       get_currentuserinfo();
       if(function_exists('is_multisite') && is_multisite()){
         $table = $wpdb->get_blog_prefix(1).'wpachievements_activity';
         $points = (int)get_blog_option(1, 'wpachievements_post_view_points');
       } else{
         $table = $wpdb->prefix.'achievements';
         $points = (int)get_option('wpachievements_post_view_points');
       }
       if( empty($points) ){
         $points = 0;
       }
       if( get_bloginfo('version') >= '3.5' ){
         $activities = $wpdb->get_var( "SELECT COUNT(type) FROM $table WHERE type='user_post_view' AND uid=$current_user->ID AND postid=$post->ID AND points > 0" );
       } else{
         $activities = $wpdb->get_var( "SELECT COUNT(type) FROM $table WHERE type='user_post_view' AND uid=$current_user->ID AND postid=$post->ID AND points > 0" );
       }
       if( empty($activities) || $activities == '' || $activities == 0 ){
         if(function_exists('is_multisite') && is_multisite()){
           $delay = (int)get_blog_option(1, 'wpachievements_post_view_delay');
         } else{
           $delay = (int)get_option('wpachievements_post_view_delay');
         }
         if(empty($delay)){$delay='0';}
         $last_visit = get_user_meta($current_user->ID, 'wpa_last_post_visit', true);
         if( empty($last_visit) || $last_visit <= strtotime('-'.$delay.' minutes') ){
           update_user_meta($current_user->ID, 'wpa_last_post_visit', time());
           $type='user_post_view'; $uid=''; $postid=$post->ID;
           if(empty($points)){$points=0;}
           wpachievements_new_activity($type, $uid, $postid, $points);

           if($current_user->ID != $post->post_author){
             $type='user_post_viewed'; $uid=$post->post_author; $postid=$post->ID;
             if(function_exists('is_multisite') && is_multisite()){
               $points = (int)get_blog_option(1, 'wpachievements_post_viewed_points');
             } else{
               $points = (int)get_option('wpachievements_post_viewed_points');
             }
             if(empty($points)){$points=0;}
             wpachievements_new_activity($type, $uid, $postid, $points);
           }
         }
       }
     }
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_wordpress_desc', 10, 6);
 function achievement_wordpress_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $comment = __('comments', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $comment = __('comment', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'user_register': { $text = __('for registering with us', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'user_login': { $text = __('for logging in', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'user_post_view': { $text = sprintf( __('for visiting %s %s',WPACHIEVEMENTS_TEXT_DOMAIN), $times, WPACHIEVEMENTS_POST_TEXT); } break;
   case 'user_post_viewed': { $text = sprintf( __('for getting %s visits on your %s',WPACHIEVEMENTS_TEXT_DOMAIN), $times, WPACHIEVEMENTS_POST_TEXT); } break;
   case 'comment_added': { $text = sprintf( __('for adding %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $comment); } break;
   case 'comment_added_bad': { $text = sprintf( __('for adding %s bad %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $comment); } break;
   case 'comment_remove': { $text = __('for a comment being removed', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'post_remove': { $text = sprintf( __('for removing a %s', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT); } break;
   case 'post_added': { $text = sprintf( __('for adding %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt); } break;
   case 'post_added_bad': { $text = sprintf( __('for adding %s bad %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_wordpress_desc', 10, 3);
 function quest_wordpress_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $comment = __('comments', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $comment = __('comment', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'user_register': { $text = __('Register with us', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'user_login': { $text = __('Log in', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'user_post_view': { $text = sprintf( __('Visit %s %s',WPACHIEVEMENTS_TEXT_DOMAIN), $times, WPACHIEVEMENTS_POST_TEXT); } break;
   case 'user_post_viewed': { $text = sprintf( __('Get %s visits on your %s',WPACHIEVEMENTS_TEXT_DOMAIN), $times, WPACHIEVEMENTS_POST_TEXT); } break;
   case 'comment_added': { $text = sprintf( __('Add %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $comment); } break;
   case 'post_added': { $text = sprintf( __('Add %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_wordpress_admin', 10, 2);
 function achievement_wordpress_admin($options,$shortname){
  $options = $options;
  $options[] = array( "name" => __('Default WordPress',WPACHIEVEMENTS_TEXT_DOMAIN),
      "class" => "separator first",
      "type" => "separator");
  $options[] = array( "name" => __('User Logging in', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user logs in.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_log_points",
      "std" => "0",
      "type" => "text");
  $options[] = array( "name" => __('User Adding Posts', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a post.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_post_points",
      "std" => "0",
      "type" => "text");
  $options[] = array( "name" => __('User Adding Comments', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a comment.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_comment_points",
      "std" => "0",
      "type" => "text");
  $options[] = array( "name" => __('User Registering', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user first registers.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_reg_points",
      "std" => "0",
      "type" => "text");
  $options[] = array( "name" => __('User Visits Post',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user views a post.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_post_view_points",
      "std" => "0",
      "type" => "text");
  $options[] = array( "name" => __('Users Post Visited',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded to user when a post they have added is visited.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_post_viewed_points",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 add_filter('wpachievements_admin_ach_extra', 'achievement_wordpress_ach_extra', 10, 2);
 function achievement_wordpress_ach_extra($options,$shortname){
  $options = $options;
  $options[] = array( "name" => __('Login Validation',WPACHIEVEMENTS_TEXT_DOMAIN),
      "class" => "separator first",
      "type" => "separator");
   $options[] = array( "name" => __('User Login Delay',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Enter the number of hours to delay logins being counted, this helps stop users getting points by logging in and out quickly.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_log_delay",
      "std" => "1",
      "type" => "text");
  $options[] = array( "name" => __('Comment Validation',WPACHIEVEMENTS_TEXT_DOMAIN),
      "class" => "separator first",
      "type" => "separator");
  $options[] = array( "name" => __('Minimum Word Limit',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('The minimum number of words required to gain comment based achievements.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_comm_min_count",
      "std" => "1",
      "type" => "text");
  $options[] = array( "name" => __('Number of Points to Deduct',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('The amount of points to deduct if the user has not met the minimum word limit.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_comm_min_deduct",
      "std" => "0",
      "type" => "text");
  $options[] = array( "name" => __('Post Validation',WPACHIEVEMENTS_TEXT_DOMAIN),
      "class" => "separator first",
      "type" => "separator");
  $options[] = array( "name" => __('Minimum Word Limit',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('The minimum number of words required to gain post based achievements.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_post_min_count",
      "std" => "1",
      "type" => "text");
  $options[] = array( "name" => __('Number of Points to Deduct',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('The amount of points to deduct if the user has not met the minimum word limit.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_post_min_deduct",
      "std" => "0",
      "type" => "text");
  $options[] = array( "name" => __('Post Visit Validation',WPACHIEVEMENTS_TEXT_DOMAIN),
      "class" => "separator first",
      "type" => "separator");
  $options[] = array( "name" => __('Users Post Visit Delay',WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Enter the number of minutes to delay visits being counted, this helps stop users getting points by refreshing the page quickly.',WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_post_view_delay",
      "std" => "2",
      "type" => "text");

  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_wordpress_admin_events', 10);
 function achievement_wordpress_admin_events(){
   echo '<optgroup label="'.__('Default WordPress Events', WPACHIEVEMENTS_TEXT_DOMAIN).'">
     <option value="user_register">'. __('The user first registers', WPACHIEVEMENTS_TEXT_DOMAIN) .'</option>
     <option value="user_login">'. __('The user logins in', WPACHIEVEMENTS_TEXT_DOMAIN) .'</option>
     <option value="post_added">'.sprintf( __('The user adds a %s', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT) .'</option>
     <option value="comment_added">'.__('The user adds a comment', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>';
     if(!function_exists(WPACHIEVEMENTS_MYARCADE) && !function_exists(WPACHIEVEMENTS_MYARCADE_ALT)){
       echo'<option value="user_post_view">'.__('The user visits a post',WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
       <option value="user_post_viewed">'.__('The users posts get visited',WPACHIEVEMENTS_TEXT_DOMAIN).'</option>';
     }
     echo '</optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_wordpress_admin_triggers', 1, 10);
 function achievement_wordpress_admin_triggers($trigger){

   switch($trigger){
     case 'user_register': { $trigger = __('The user first registers', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'user_login': { $trigger = __('The user logins in', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'post_added': { $trigger = sprintf( __('The user adds a %s', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT); } break;
     case 'comment_added': { $trigger = __('The user adds a comment', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'user_post_view': { $trigger = __('The user visits a post',WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'user_post_viewed': { $trigger = __('The users posts get visited',WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }
?>