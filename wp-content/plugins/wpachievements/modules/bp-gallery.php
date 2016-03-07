<?php
/**
 * Module Name: BP Gallery Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/BP-Gallery
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('gallery_media_upload_complete','my_bp_gallery_upload_add_cppoints');
 //*************** Detect Gallery Upload ***************\\
 function my_bp_gallery_upload_add_cppoints(){
   $type='cp_bp_galery_upload'; $uid=''; $postid='';
   if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
     if(function_exists('is_multisite') && is_multisite()){
       $points = (int)get_blog_option(1, 'wpachievements_bp_galery_upload');
     } else{
       $points = (int)get_option('wpachievements_bp_galery_upload');
     }
   }
   if(empty($points)){$points=0;}
   wpachievements_new_activity($type, $uid, $postid, $points);
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_bpg_desc', 10, 6);
 function achievement_bpg_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $time = __('times', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $time = __('time', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'cp_bp_galery_upload': { $text = sprintf( __('for uploading to a gallery %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $time); } break;
   case 'cp_bp_galery_delete': { sprintf( __('for deleting from a gallery %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $time); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_bpg_desc', 10, 3);
 function quest_bpg_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $time = __('times', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $time = __('time', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'cp_bp_galery_upload': { $text = sprintf( __('Upload to a gallery %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $time); } break;
   case 'cp_bp_galery_delete': { sprintf( __('Delete from a gallery %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $time); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_bpg_admin', 10, 2);
 function achievement_bpg_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "BuddyPress Gallery",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __("User Uploads Image", WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user uploads an image.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_bp_galery_upload",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_bpg_admin_triggers', 1, 10);
 function achievement_bpg_admin_triggers($trigger){

   switch($trigger){
     case 'cp_bp_galery_upload': { $trigger = __('The user uploads to a gallery', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'cp_bp_galery_delete': { $trigger = __('The user deletes from a gallery', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_bpg_admin_events', 10);
 function achievement_bpg_admin_events(){
   echo'<optgroup label="BuddyPress Gallery Events">
     <option value="cp_bp_galery_upload">'.__('The user uploads to a gallery', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="cp_bp_galery_delete">'.__('The user deletes from a gallery', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }
?>