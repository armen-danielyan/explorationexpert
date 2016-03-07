<?php
/**
 * Module Name: Invite Anyone Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/Invite-Anyone
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('sent_email_invite', 'wpachievements_invite_sent', 10, 3);
 add_action('accepted_email_invite', 'wpachievements_invite_accepted', 10, 2);
 //*************** Detect adding of favorite ***************\\
 function wpachievements_invite_sent($user_id, $email, $group){
   $type='sentinvite'; $uid=$user_id; $postid=''; $points=0;
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect removal of favorite ***************\\
 function wpachievements_invite_accepted($invited_user_id, $inviters){
   if( is_array($inviters) ){
     foreach($inviters as $inviter_id){
       $type='inviteacceptance'; $uid=$inviter_id; $postid='';
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         if(function_exists('is_multisite') && is_multisite()){
           $points = (int)get_blog_option(1, 'wpachievements_iv_invite_acceptance_points');
         } else{
           $points = (int)get_option('wpachievements_iv_invite_acceptance_points');
         }
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   } else{
     $type='inviteacceptance'; $uid=$inviters; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_iv_invite_acceptance_points');
       } else{
         $points = (int)get_option('wpachievements_iv_invite_acceptance_points');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_ia_desc', 10, 6);
 function achievement_ia_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $invite = __('invites', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $invite = __('invite', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'sentinvite': { $text = sprintf( __('for sending %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $invite); } break;
   case 'inviteacceptance': { $text = sprintf( __('for %s %s being accepted', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $invite); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_ia_desc', 10, 3);
 function quest_ia_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $invite = __('invites', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $invite = __('invite', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'sentinvite': { $text = sprintf( __('Send %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $invite); } break;
   case 'inviteacceptance': { $text = sprintf( __('Have %s %s accepted', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $invite); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'wpachievement_ia_admin', 10, 2);
 function wpachievement_ia_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "Invite Anyone",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __("User Invite Accepted", WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the users invite is accepted.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_iv_invite_acceptance_points",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_ia_admin_events', 10);
 function achievement_ia_admin_events(){
   echo'<optgroup label="Invite Anyone Events">
     <option value="sentinvite">'.__('The user invites someone', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="inviteacceptance">'.__('The users invitation is accepted', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_ia_admin_triggers', 1, 10);
 function achievement_ia_admin_triggers($trigger){

   switch($trigger){
     case 'sentinvite': { $trigger = __('The user invites someone', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'inviteacceptance': { $trigger = __('The users invitation is accepted', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }
?>