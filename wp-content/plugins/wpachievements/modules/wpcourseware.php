<?php
/**
 * Module Name: WP-Courseware Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WP-Courseware
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('wpcw_quiz_graded', 'wpachievements_wpcw_quiz_complete', 10, 4);
 add_action('wpcw_user_completed_module', 'wpachievements_wpcw_module_complete', 10, 3);
 add_action('wpcw_user_completed_course', 'wpachievements_wpcw_course_complete', 10, 3);
 //*************** Detect Quiz Completed ***************\\
 function wpachievements_wpcw_quiz_complete($userID, $quizDetails, $correctPercentage, $extra){
   if(!empty($userID)){
     $type='wpcw_quiz_pass'; $uid=$userID;
     if($quizDetails->quiz_id){
       $postid = $quizDetails->quiz_id;
     } else{
       $postid = '';
     }
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_wpcw_quiz_pass');
       } else{
         $points = (int)get_option('wpachievements_wpcw_quiz_pass');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
     if($correctPercentage == '100'){
       $type='wpcw_quiz_perfect';
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         if(function_exists('is_multisite') && is_multisite()){
           $points = (int)get_blog_option(1, 'wpachievements_wpcw_quiz_perfect');
         } else{
           $points = (int)get_option('wpachievements_wpcw_quiz_perfect');
         }
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     }
   }
 }
 //*************** Detect Module Completed ***************\\
 function wpachievements_wpcw_module_complete($userID, $unitID, $unitParentData){
   if(!empty($userID)){
     $type='wpcw_module_complete'; $uid=$userID; $postid=$unitID;
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_wpcw_module_complete');
       } else{
         $points = (int)get_option('wpachievements_wpcw_module_complete');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect Course Completed ***************\\
 function wpachievements_wpcw_course_complete($userID, $unitID, $unitParentData){
   if(!empty($userID)){
     $type='wpcw_course_complete'; $uid=$userID;
     if($unitParentData->course_id){
       $postid = $unitParentData->course_id;
     } else{
       $postid = '';
     }
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_wpcw_course_complete');
       } else{
         $points = (int)get_option('wpachievements_wpcw_course_complete');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_wpcw_desc', 10, 6);
 function achievement_wpcw_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $quiz = __('quizzes', WPACHIEVEMENTS_TEXT_DOMAIN);
    $score = __('scores', WPACHIEVEMENTS_TEXT_DOMAIN);
    $module = __('modules', WPACHIEVEMENTS_TEXT_DOMAIN);
    $course = __('courses', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $quiz = __('quiz', WPACHIEVEMENTS_TEXT_DOMAIN);
    $score = __('score', WPACHIEVEMENTS_TEXT_DOMAIN);
    $module = __('module', WPACHIEVEMENTS_TEXT_DOMAIN);
    $course = __('course', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'wpcw_quiz_pass': { $text = sprintf( __('for passing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $quiz); } break;
   case 'wpcw_quiz_perfect': { $text = sprintf( __('for getting %s perfect %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $score); } break;
   case 'wpcw_module_complete': { $text = sprintf( __('for completing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $module); } break;
   case 'wpcw_course_complete': { $text = sprintf( __('for completing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $course); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_wpcw_desc', 10, 3);
 function quest_wpcw_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $quiz = __('quizzes', WPACHIEVEMENTS_TEXT_DOMAIN);
    $score = __('scores', WPACHIEVEMENTS_TEXT_DOMAIN);
    $module = __('modules', WPACHIEVEMENTS_TEXT_DOMAIN);
    $course = __('courses', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $quiz = __('quiz', WPACHIEVEMENTS_TEXT_DOMAIN);
    $score = __('score', WPACHIEVEMENTS_TEXT_DOMAIN);
    $module = __('module', WPACHIEVEMENTS_TEXT_DOMAIN);
    $course = __('course', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'wpcw_quiz_pass': { $text = sprintf( __('Pass %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $quiz); } break;
   case 'wpcw_quiz_perfect': { $text = sprintf( __('Get %s perfect %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $score); } break;
   case 'wpcw_module_complete': { $text = sprintf( __('Complete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $module); } break;
   case 'wpcw_course_complete': { $text = sprintf( __('Complete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $course); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_wpcw_admin', 10, 2);
 function achievement_wpcw_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "WP-Courseware",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __('User Passes Quiz', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user passes a quiz.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_wpcw_quiz_pass",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __('User Gets Perfect Quiz Score', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user gets 100% on a quiz.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_wpcw_quiz_perfect",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __('User Completes Module', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user completes a module.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_wpcw_module_complete",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __('Users Completes Course.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user completes a course.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_wpcw_course_complete",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_wpcw_admin_events', 10);
 function achievement_wpcw_admin_events(){
   echo'<optgroup label="WP-Courseware Events">
     <option value="wpcw_quiz_pass">'.__('The user passes a quiz', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="wpcw_quiz_perfect">'.__('The user gets 100% on a quiz', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="wpcw_module_complete">'.__('The user completes a module', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="wpcw_course_complete">'.__('The user completes a course', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_wpcw_admin_triggers', 1, 10);
 function achievement_wpcw_admin_triggers($trigger){

   switch($trigger){
     case 'wpcw_quiz_pass': { $trigger = __('The user passes a quiz', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'wpcw_quiz_perfect': { $trigger = __('The user gets 100% on a quiz', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'wpcw_module_complete': { $trigger = __('The user completes a module', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'wpcw_course_complete': { $trigger = __('The user completes a course', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }
?>