<?php
/**
 * Module Name: BuddyPress Links Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/BuddyPress-Links
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('bp_links_create_complete','my_bp_bplink_add_cppoints');
 add_action('bp_links_cast_vote_success','my_bp_bplink_vote_add_cppoints');
 add_action('bp_links_posted_update','my_bp_bplink_comment_add_cppoints');
 //*************** Detect New Link Creation ***************\\
 function my_bp_bplink_add_cppoints(){
   $type='cp_bp_link_added'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     if(function_exists('is_multisite') && is_multisite()){
       $points = (int)get_blog_option(1, 'wpachievements_bp_gift_given');
     } else{
       $points = (int)get_option('wpachievements_bp_gift_given');
     }
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect New Link Vote ***************\\
 function my_bp_bplink_vote_add_cppoints(){
   $type='cp_bp_link_voted'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     if(function_exists('is_multisite') && is_multisite()){
       $points = (int)get_blog_option(1, 'wpachievements_bp_link_voted');
     } else{
       $points = (int)get_option('wpachievements_bp_link_voted');
     }
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect New Link Comment ***************\\
 function my_bp_bplink_comment_add_cppoints(){
   $type='cp_bp_link_comment'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     if(function_exists('is_multisite') && is_multisite()){
       $points = (int)get_blog_option(1, 'wpachievements_bp_link_comment');
     } else{
       $points = (int)get_option('wpachievements_bp_link_comment');
     }
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_bplink_desc', 10, 6);
 function achievement_bplink_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $link = __('links', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $link = __('link', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'cp_bp_link_added': { $text = sprintf( __('for adding %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $link); } break;
   case 'cp_bp_link_voted': { $text = sprintf( __('for voting on %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $link); } break;
   case 'cp_bp_link_comment': { $text = sprintf( __('for commenting on %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $link); } break;
   case 'cp_bp_link_delete': { $text = sprintf( __('for deleting %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $link); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_bplink_desc', 10, 3);
 function quest_bplink_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $link = __('links', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $link = __('link', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'cp_bp_link_added': { $text = sprintf( __('Add %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $link); } break;
   case 'cp_bp_link_voted': { $text = sprintf( __('Vote on %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $link); } break;
   case 'cp_bp_link_comment': { $text = sprintf( __('Comment on %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $link); } break;
   case 'cp_bp_link_delete': { $text = sprintf( __('Delete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $link); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_bplink_admin', 10, 2);
 function achievement_bplink_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "BuddyPress Links",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __("User Adding Links", WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a link.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_bp_link_added",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __("User Link Voting", WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user votes on a link.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_bp_link_voted",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __("User Link Comments", WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user comments on a link.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_bp_link_comment",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_bplink_admin_events', 10);
 function achievement_bplink_admin_events(){
   echo'<optgroup label="BuddyPress Links Events">
     <option value="cp_bp_link_added">'.__('The user adds a link', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="cp_bp_link_voted">'.__('The user votes on a link', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="cp_bp_link_comment">'.__('The user comments on a link', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="cp_bp_link_delete">'.__('The user delets a link', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_bplink_admin_triggers', 1, 10);
 function achievement_bplink_admin_triggers($trigger){

   switch($trigger){
     case 'cp_bp_link_added': { $trigger = __('The user adds a link', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'cp_bp_link_voted': { $trigger = __('The user votes on a link', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'cp_bp_link_comment': { $trigger = __('The user comments on a link', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'cp_bp_link_delete': { $trigger = __('The user delets a link', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }

?>