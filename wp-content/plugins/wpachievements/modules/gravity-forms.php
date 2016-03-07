<?php
/**
 * Module Name: Gravity Forms Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/Gravity-Forms
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action("gform_after_submission", "wpachievements_gform_submission", 10, 2);
 //*************** Detect Form Submission ***************\\
 function wpachievements_gform_submission( $entry, $form ){
   if( is_user_logged_in() ){
     $type='gform_sub'; $uid=''; $postid=$entry["form_id"];
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_gform_points');
       } else{
         $points = (int)get_option('wpachievements_gform_points');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_gform_desc', 10, 6);
 function achievement_gform_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $form = __('forms', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $form = __('form', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  if( !empty($data) ){
    switch($type){
     case 'gform_sub': { $text = sprintf( __('for submitting the form: %s', WPACHIEVEMENTS_TEXT_DOMAIN), $data); } break;
    }
  } else{
    switch($type){
     case 'gform_sub': { $text = sprintf( __('for submitting %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $form); } break;
    }
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_gform_desc', 10, 3);
 function quest_gform_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $form = __('forms', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $form = __('form', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'gform_sub': { $text = sprintf( __('Submit %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $form); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'wpachievement_gform_admin', 10, 2);
 function wpachievement_gform_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "Gravity Forms",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __('User Submitting Forms', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user submits a form.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_gform_points",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_gform_admin_events', 10);
 function achievement_gform_admin_events(){
   echo'<optgroup label="Gravity Forms Events">
     <option value="gform_sub">'.__('The user submits a form', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_gform_admin_triggers', 1, 10);
 function achievement_gform_admin_triggers($trigger){

   switch($trigger){
     case 'gform_sub': { $trigger = __('The user submits a form', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }

?>