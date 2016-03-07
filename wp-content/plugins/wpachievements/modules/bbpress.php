<?php
/**
 * Module Name: bbPress Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/bbPress
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;
 //*************** Actions ***************\\
 add_action("bbp_new_forum", "wpachievements_bbp_new_forum", 10, 1);
 add_action("bbp_edit_forum", "wpachievements_bbp_edit_forum", 10, 1);
 add_action("bbp_new_topic", "wpachievements_bbp_new_topic", 10, 4);
 add_action("bbp_closed_topic", "wpachievements_bbp_closed_topic", 10, 1);
 add_action("bbp_merged_topic", "wpachievements_bbp_merged_topic", 10, 3);
 add_action("bbp_post_split_topic", "wpachievements_bbp_post_split_topic", 10, 3);
 add_action("bbp_sticked_topic", "wpachievements_bbp_sticked_topic", 10, 3);
 add_action("bbp_unsticked_topic", "wpachievements_bbp_unsticked_topic", 10, 2);
 add_action("bbp_new_reply", "wpachievements_bbp_new_reply", 10, 5);
 add_action("bbp_deleted_reply", "wpachievements_bbp_deleted_reply", 10, 1);
 if( !function_exists(WPACHIEVEMENTS_USERPRO) ){
   add_action("bbp_theme_after_reply_author_details", "wpachievements_bbp_user_badges", 10);
 }
 //*************** Detect New Forum Added ***************\\
 function wpachievements_bbp_new_forum( $forum ){
   if( !empty($forum['forum_author']) ){
     $type='bbp_new_forum'; $uid=$forum['forum_author']; $postid=''; $points=0;
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect Forum Edited ***************\\
 function wpachievements_bbp_edit_forum( $forum ){
   if( !empty($forum['forum_author']) ){
     $type='bbp_edit_forum'; $uid=$forum['forum_author']; $postid=''; $points=0;
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect New Topic Added ***************\\
 function wpachievements_bbp_new_topic( $topic_id, $forum_id, $anonymous_data, $topic_author ){
   if( !empty($topic_author) ){
     $type='bbp_new_topic'; $uid=$topic_author; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_bbp_new_topic_points');
       } else{
         $points = (int)get_option('wpachievements_bbp_new_topic_points');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect Topic Closed ***************\\
 function wpachievements_bbp_closed_topic($topic_id){
   if( !empty($topic_id) ){
    $topic = get_post($topic_id);
    if( !empty($topic->post_author) ){
     $type='bbp_closed_topic'; $uid=$topic->post_author; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_bbp_new_topic_points');
       } else{
         $points = (int)get_option('wpachievements_bbp_new_topic_points');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, -$points);
    }
   }
 }
 //*************** Detect Topics Merged ***************\\
 function wpachievements_bbp_merged_topic( $destination_topic, $source_topic, $source_topic ){
   if( !empty($source_topic) ){
    $topic = get_post($source_topic);
    if( !empty($topic->post_author) ){
     $type='bbp_merged_topic'; $uid=$topic->post_author; $postid=''; $points=0;
     wpachievements_new_activity($type, $uid, $postid, $points);
    }
   }
 }
 //*************** Detect Topic Split ***************\\
 function wpachievements_bbp_post_split_topic( $destination_topic, $source_topic, $source_topic ){
   if( !empty($source_topic) ){
    $topic = get_post($source_topic);
    if( !empty($topic->post_author) ){
     $type='bbp_post_split_topic'; $uid=$topic->post_author; $postid=''; $points=0;
     wpachievements_new_activity($type, $uid, $postid, $points);
    }
   }
 }
 //*************** Detect Topic Marked as Sticky ***************\\
 function wpachievements_bbp_sticked_topic( $topic_id, $super, $success ){
   if( !empty($topic_id) ){
    $topic = get_post($topic_id);
    if( !empty($topic->post_author) ){
     $type='bbp_sticked_topic'; $uid=$topic->post_author; $postid=''; $points=0;
     wpachievements_new_activity($type, $uid, $postid, $points);
    }
   }
 }
 //*************** Detect Topic Made Unsticky ***************\\
 function wpachievements_bbp_unsticked_topic( $topic_id, $success ){
   if( !empty($topic_id) ){
    $topic = get_post($topic_id);
    if( !empty($topic->post_author) ){
     $type='bbp_unsticked_topic'; $uid=$topic->post_author; $postid=''; $points=0;
     wpachievements_new_activity($type, $uid, $postid, $points);
    }
   }
 }
 //*************** Detect New Reply Added ***************\\
 function wpachievements_bbp_new_reply( $reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author ){
   if( !empty($reply_author) ){
     $type='bbp_new_reply'; $uid=$reply_author; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_bbp_new_reply_points');
       } else{
         $points = (int)get_option('wpachievements_bbp_new_reply_points');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect Reply Removed ***************\\
 function wpachievements_bbp_deleted_reply($reply_id){
   if( !empty($reply_id) ){
    $reply = get_post($topic_id);
    if( !empty($reply->post_author) ){
     $type='bbp_deleted_reply'; $uid=$reply->post_author; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_bbp_new_reply_points');
       } else{
         $points = (int)get_option('wpachievements_bbp_new_reply_points');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, -$points);
    }
   }
 }
 //*************** Add Badges to Forum ***************\\
 function wpachievements_bbp_user_badges(){
   $user_id = bbp_get_reply_author_id( bbp_get_reply_id() );
   if( $user_id ){
     echo do_shortcode( '[wpa_myachievements user_id="'.$user_id.'" title_class="hide_title" image_width="30"]' );
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_bbp_desc', 10, 6);
 function achievement_bbp_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $topic = __('topics', WPACHIEVEMENTS_TEXT_DOMAIN);
    $reply = __('replys', WPACHIEVEMENTS_TEXT_DOMAIN);
    $forum = __('forums', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $topic = __('topic', WPACHIEVEMENTS_TEXT_DOMAIN);
    $reply = __('reply', WPACHIEVEMENTS_TEXT_DOMAIN);
    $forum = __('forum', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'bbp_new_topic': { $text = sprintf( __('for creating %s new forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_new_reply': { $text = sprintf( __('for creating %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $reply); } break;
   case 'bbp_new_forum': { $text = sprintf( __('for creating %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $forum); } break;
   case 'bbp_edit_forum': { $text = sprintf( __('for editting %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $forum); } break;
   case 'bbp_deleted_reply': { $text = sprintf( __('for deleting %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $reply); } break;
   case 'bbp_deleted_forum': { $text = sprintf( __('for deleting %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $forum); } break;
   case 'bbp_closed_topic': { $text = sprintf( __('for deleting %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_merged_topic': { $text = sprintf( __('for merging %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_post_split_topic': { $text = sprintf( __('for splitting %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_sticked_topic': { $text = sprintf( __('for marking %s %s as sticky', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_unsticked_topic': { $text = sprintf( __('for marking %s %s unsticky', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_bbp_desc', 10, 3);
 function quest_bbp_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $topic = __('topics', WPACHIEVEMENTS_TEXT_DOMAIN);
    $reply = __('replys', WPACHIEVEMENTS_TEXT_DOMAIN);
    $forum = __('forums', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $topic = __('topic', WPACHIEVEMENTS_TEXT_DOMAIN);
    $reply = __('reply', WPACHIEVEMENTS_TEXT_DOMAIN);
    $forum = __('forum', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'bbp_new_topic': { $text = sprintf( __('Create %s new forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_new_reply': { $text = sprintf( __('Create %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $reply); } break;
   case 'bbp_new_forum': { $text = sprintf( __('Create %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $forum); } break;
   case 'bbp_edit_forum': { $text = sprintf( __('Edit %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $forum); } break;
   case 'bbp_deleted_reply': { $text = sprintf( __('Delete %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $reply); } break;
   case 'bbp_deleted_forum': { $text = sprintf( __('Delete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $forum); } break;
   case 'bbp_closed_topic': { $text = sprintf( __('Delete %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_merged_topic': { $text = sprintf( __('Merge %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_post_split_topic': { $text = sprintf( __('Split %s forum %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_sticked_topic': { $text = sprintf( __('Mark %s %s as sticky', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
   case 'bbp_unsticked_topic': { $text = sprintf( __('Mark %s %s unsticky', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $topic); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_bbp_admin', 10, 2);
 function achievement_bbp_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "bbPress",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __("User Adding Topics", WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a topic.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_bbp_new_topic_points",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __("User Adding Reply", WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a topic reply.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_bbp_new_reply_points",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_bbp_admin_events', 10);
 function achievement_bbp_admin_events(){
   echo'<optgroup label="BBPress Events">
     <option value="bbp_new_forum">'.__('The users creates a new forum', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_edit_forum">'.__('The users edits a forum', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_deleted_forum">'.__('The users deletes a forum', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_new_topic">'.__('The users opens a new topic', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_closed_topic">'.__('The users closes a topic', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_merged_topic">'.__('The users merges two topics together', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_post_split_topic">'.__('The users splits a topic into multiple topics', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_sticked_topic">'.__('The users marks a topic as sticky', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_unsticked_topic">'.__('The users stops a topic being sticky', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_new_reply">'.__('The users adds a new reply', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="bbp_deleted_reply">'.__('The users deletes a reply', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_bbp_admin_triggers', 1, 10);
 function achievement_bbp_admin_triggers($trigger){

   switch($trigger){
     case 'bbp_new_forum': { $trigger = __('The users creates a new forum', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_edit_forum': { $trigger = __('The users edits a forum', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_deleted_forum': { $trigger = __('The users deletes a forum', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_new_topic': { $trigger = __('The users opens a new topic', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_closed_topic': { $trigger = __('The users closes a topic', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_merged_topic': { $trigger = __('The users merges two topics together', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_post_split_topic': { $trigger = __('The users splits a topic into multiple topics', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_sticked_topic': { $trigger = __('The users marks a topic as sticky', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_unsticked_topic': { $trigger = __('The users stops a topic being sticky', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_new_reply': { $trigger = __('The users adds a new reply', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'bbp_deleted_reply': { $trigger = __('The users deletes a reply', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }

 //*************** Show stats in profile ***************\\
 if( !function_exists(WPACHIEVEMENTS_USERPRO) ){
   add_filter('bbp_template_after_user_profile', 'wpachievements_bbpress_profile_edit', 10);
 }
 function wpachievements_bbpress_profile_edit(){

   $profile_user = bbp_get_user_id( 0, true, false );

   echo '<span class="wpa_profile_rank"><strong>'.__('Rank', WPACHIEVEMENTS_TEXT_DOMAIN).':</strong> '.wpachievements_getRank($profile_user).'</span>';

   $userachievement = get_user_meta( $profile_user, 'achievements_gained', true );
   echo '<span><h2 style="clear:none !important;margin-bottom:5px !important;">'.__('My Achievements', WPACHIEVEMENTS_TEXT_DOMAIN).':</h2></span>';
   $already_counted = array();
   $wpa_bp_achievements = array();
   $sim_ach = get_option('wpachievements_sim_ach');
   $ii=0;
   if( !empty($userachievement) && $userachievement != '' ){
     $args = array(
      'post_type' => 'wpachievements',
      'post_status' => 'publish',
      'post__in' => $userachievement,
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
        $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );
        $ach_points = get_post_meta( $ach_ID, '_achievement_points', true );
        $ach_woopoints = get_post_meta( $ach_ID, '_achievement_woo_points', true );
        $ach_rank = get_post_meta( $ach_ID, '_achievement_rank', true );
        $ach_occurences = get_post_meta( $ach_ID, '_achievement_occurrences', true );
        $type = 'wpachievements_achievement_'.get_post_meta( $ach_ID, '_achievement_type', true );
        if($sim_ach == 'true'){
          if( !array_key_exists($type,$already_counted) ){
            if( $type != 'wpachievements_achievement_custom_achievement' ){
              $already_counted[$type] = $ach_occurences;
            }
            echo '<img src="'.$ach_img.'" width="30" class="achieves_gained_badge" alt="'.stripslashes($ach_title).__(' Icon',WPACHIEVEMENTS_TEXT_DOMAIN).'" title="'.stripslashes($ach_title).': '.stripslashes(strip_tags($ach_desc)).'" />';
          } elseif( $already_counted[$type] <= $ach_occurences ){
            if( $type != 'wpachievements_achievement_custom_achievement' ){
              $already_counted[$type] = $ach_occurences;
            }
            echo '<img src="'.$ach_img.'" width="30" class="achieves_gained_badge" alt="'.stripslashes($ach_title).': '.$ach_desc.'" title="'.stripslashes($ach_title).': '.stripslashes(strip_tags($ach_desc)).'" />';
          }
        } else{
          echo '<img src="'.$ach_img.'" width="30" class="achieves_gained_badge" alt="'.stripslashes($ach_title).__(' Icon',WPACHIEVEMENTS_TEXT_DOMAIN).'" title="'.stripslashes($ach_title).': '.stripslashes(strip_tags($ach_desc)).'" />';
        }
      }
     }
     wp_reset_postdata();
   }
 }

?>