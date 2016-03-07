<?php
/**
 * Module Name: BuddyStream Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/BuddyStream
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action("buddystream_facebook_activated", "wpachievements_buddystream_facebook_activated", 10);
 add_action("buddystream_flickr_activated", "wpachievements_buddystream_flickr_activated", 10);
 add_action("buddystream_lastfm_activated", "wpachievements_buddystream_lastfm_activated", 10);
 add_action("buddystream_twitter_activated", "wpachievements_buddystream_twitter_activated", 10);
 add_action("buddystream_youtube_activated", "wpachievements_buddystream_youtube_activated", 10);
 //*************** Detect Buddysteam Facebook Activation ***************\\
 function wpachievements_buddystream_facebook_activated(){
   if( is_user_logged_in() ){
     $type='buddystream_facebook_activated'; $uid=''; $points=''; $data=''; $postid='';
     wpachievements_new_activity($type, $postid);
   }
 }
 //*************** Detect Buddysteam Flickr Activation ***************\\
 function wpachievements_buddystream_flickr_activated(){
   if( is_user_logged_in() ){
     $type='buddystream_flickr_activated'; $uid=''; $points=''; $data=''; $postid='';
     wpachievements_new_activity($type, $postid);
   }
 }
 //*************** Detect Buddysteam Lastfm Activation ***************\\
 function wpachievements_buddystream_lastfm_activated(){
   if( is_user_logged_in() ){
     $type='buddystream_lastfm_activated'; $uid=''; $points=''; $data=''; $postid='';
     wpachievements_new_activity($type, $postid);
   }
 }
 //*************** Detect Buddysteam Twitter Activation ***************\\
 function wpachievements_buddystream_twitter_activated(){
   if( is_user_logged_in() ){
     $type='buddystream_twitter_activated'; $uid=''; $points=''; $data=''; $postid='';
     wpachievements_new_activity($type, $postid);
   }
 }
 //*************** Detect Buddysteam Youtube Activation ***************\\
 function wpachievements_buddystream_youtube_activated(){
   if( is_user_logged_in() ){
     $type='buddystream_youtube_activated'; $uid=''; $points=''; $data=''; $postid='';
     wpachievements_new_activity($type, $postid);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_buddystream_desc', 10, 6);
 function achievement_buddystream_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  switch($type){
   case 'buddystream_facebook_activated': { $text = __('for connecting your Flickr account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'buddystream_flickr_activated': { $text = __('for connecting your Last.fm account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'buddystream_lastfm_activated': { $text = __('for connecting your Twitter account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'buddystream_twitter_activated': { $text = __('for connecting your Flickr account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'buddystream_youtube_activated': { $text = __('for connecting your YouTube account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_buddystream_desc', 10, 3);
 function quest_buddystream_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  switch($type){
   case 'buddystream_facebook_activated': { $text = __('Connect your Flickr account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'buddystream_flickr_activated': { $text = __('Connect your Last.fm account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'buddystream_lastfm_activated': { $text = __('Connect your Twitter account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'buddystream_twitter_activated': { $text = __('Connect your Flickr account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'buddystream_youtube_activated': { $text = __('Connect your YouTube account', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
  }
  return $text;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_buddystream_admin_events', 10);
 function achievement_buddystream_admin_events(){
   echo'<optgroup label="BuddyStream Events">
     <option value="buddystream_facebook_activated">'.__('The user connects their Facebook account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="buddystream_flickr_activated">'.__('The user connects their Flickr account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="buddystream_lastfm_activated">'.__('The user connects their Last.fm account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="buddystream_twitter_activated">'.__('The user connects their Twitter account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="buddystream_youtube_activated">'.__('The user connects their YouTube account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_buddystream_admin_triggers', 1, 10);
 function achievement_buddystream_admin_triggers($trigger){

   switch($trigger){
     case 'buddystream_facebook_activated': { $trigger = __('The user connects their Facebook account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'buddystream_flickr_activated': { $trigger = __('The user connects their Flickr account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'buddystream_lastfm_activated': { $trigger = __('The user connects their Last.fm account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'buddystream_twitter_activated': { $trigger = __('The user connects their Twitter account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'buddystream_youtube_activated': { $trigger = __('The user connects their YouTube account to BuddyStream', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }
?>