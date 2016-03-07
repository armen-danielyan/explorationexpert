<?php
/**
 * Module Name: WP Favorite Posts Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WP-Favorite-Posts
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('wpfp_after_add', 'wpachievements_add_game_favorite');
 add_action('wpfp_after_remove', 'wpachievements_remove_game_favorite');
 //*************** Detect adding of favorite ***************\\
 function wpachievements_add_game_favorite($post_id){
   $type='addfavor'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     if(function_exists('is_multisite') && is_multisite()){
       $points = (int)get_blog_option(1, 'wpachievements_favor_points');
     } else{
       $points = (int)get_option('wpachievements_favor_points');
     }
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }
 //*************** Detect removal of favorite ***************\\
 function wpachievements_remove_game_favorite($post_id){
   $type='removefavor'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     if(function_exists('is_multisite') && is_multisite()){
       $points = (int)get_blog_option(1, 'wpachievements_favor_points');
     } else{
       $points = (int)get_option('wpachievements_favor_points');
     }
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, -$points);
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_wfp_desc', 10, 6);
 function achievement_wfp_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  switch($type){
   case 'addfavor': { $text = sprintf( __('for favoriting %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt ); } break;
   case 'removefavor': { $text = sprintf( __('for unfavoriting %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_wfp_desc', 10, 3);
 function quest_wfp_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  switch($type){
   case 'addfavor': { $text = sprintf( __('Favorite %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt ); } break;
   case 'removefavor': { $text = sprintf( __('Unfavorite %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_wfp_admin', 10, 2);
 function achievement_wfp_admin($options,$shortname){
  $options = $options;
  $options[] = array( "name" => "WP Favorite Posts",
      "class" => "separator",
      "type" => "separator");
  $options[] = array( "name" => __('User Adding Favorites', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a favorite.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_favor_points",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_wfp_admin_events', 10);
 function achievement_wfp_admin_events(){
   echo'<optgroup label="'.__('WP Favorite Posts Events', WPACHIEVEMENTS_TEXT_DOMAIN).'">
     <option value="addfavor">'.sprintf( __('The user add a %s to favorites', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT).'</option>
     <option value="removefavor">'. sprintf( __('The user removes a %s from favorites', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_wfp_admin_triggers', 1, 10);
 function achievement_wfp_admin_triggers($trigger){

   switch($trigger){
     case 'addfavor': { $trigger = sprintf( __('The user add a %s to favorites', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT); } break;
     case 'removefavor': { $trigger = sprintf( __('The user removes a %s from favorites', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT); } break;
   }

   return $trigger;

 }
?>