<?php
/**
 * Module Name: GD Star Rating Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/GD-Star-Rating
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action("gdsr_vote", "wpachievements_gd_rating");
 //*************** Detect Post Rating ***************\\
 function wpachievements_gd_rating( $vote_value, $vote_id, $vote_tpl, $vote_size ){
   if( is_user_logged_in() ){
     $type='gd_rating'; $uid=$user; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_gd_star_points');
       } else{
         $points = (int)get_option('wpachievements_gd_star_points');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_gd_desc', 10, 6);
 function achievement_gd_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  switch($type){
   case 'gd_rating': { $text = sprintf( __('for rating %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_gd_desc', 10, 3);
 function quest_gd_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){$pt = WPACHIEVEMENTS_POST_TEXT."'s";}
  switch($type){
   case 'gd_rating': { $text = sprintf( __('Rate %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $pt); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'wpachievement_gd_admin', 10, 2);
 function wpachievement_gd_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "GD Star Rating",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __('User Adding Ratings', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a rating.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_gd_star_points",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_gd_admin_events', 10);
 function achievement_gd_admin_events(){
   echo'<optgroup label="GD Star Rating Events">
     <option value="gd_rating">'.__('The user adds a rating', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_gd_admin_triggers', 1, 10);
 function achievement_gd_admin_triggers($trigger){

   switch($trigger){
     case 'gd_rating': { $trigger = __('The user adds a rating', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }
?>