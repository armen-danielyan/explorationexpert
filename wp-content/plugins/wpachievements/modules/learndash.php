<?php
/**
 * Module Name: LearnDash Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/LearnDash
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('learndash_completed', 'wpachievements_ld_quiz_complete', 10, 1);
 add_action('learndash_quiz_completed', 'wpachievements_ld_quiz_complete', 10, 1);
 add_action('learndash_lesson_completed', 'wpachievements_ld_lesson_complete', 10, 1);
 add_action('learndash_course_completed', 'wpachievements_ld_course_complete', 10, 1);
 //*************** Detect Quiz Completed ***************\\
 function wpachievements_ld_quiz_complete($quiz){
   if(!empty($quiz)){
     $current_user = wp_get_current_user();
     if( isset($quiz['pro_quizid']) ){
       $postid=$quiz['pro_quizid'];
     } else{
       $postid=$quiz['quiz']->ID;
     }
     if($quiz['pass'] == '1'){
       if($quiz['score'] == $quiz['count']){
         $type='ld_quiz_perfect'; $uid=$current_user->ID;
         if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
           if(function_exists('is_multisite') && is_multisite()){
             $points = (int)get_blog_option(1, 'wpachievements_ld_quiz_perfect');
           } else{
             $points = (int)get_option('wpachievements_ld_quiz_perfect');
           }
         }
         if(empty($points)){$points=0;}
         wpachievements_new_activity($type, $uid, $postid, $points);
       }
       $type='ld_quiz_pass'; $uid=$current_user->ID;
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         if(function_exists('is_multisite') && is_multisite()){
           $points = (int)get_blog_option(1, 'wpachievements_ld_quiz_pass');
         } else{
           $points = (int)get_option('wpachievements_ld_quiz_pass');
         }
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, $points);
     } else{
       $type='ld_quiz_fail'; $uid=$current_user->ID;
       if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
         if(function_exists('is_multisite') && is_multisite()){
           $points = (int)get_blog_option(1, 'wpachievements_ld_quiz_fail');
         } else{
           $points = (int)get_option('wpachievements_ld_quiz_fail');
         }
       }
       if(empty($points)){$points=0;}
       wpachievements_new_activity($type, $uid, $postid, -$points);
     }
   }
 }
 //*************** Detect Lesson Completed ***************\\
 function wpachievements_ld_lesson_complete($lesson){
   if(!empty($lesson)){
     $type='ld_lesson_complete'; $uid=$lesson['user']->data->ID; $postid=$lesson['lesson']->ID;
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_ld_lesson_complete');
       } else{
         $points = (int)get_option('wpachievements_ld_lesson_complete');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }
 //*************** Detect Course Completed ***************\\
 function wpachievements_ld_course_complete($course){
   if(!empty($course)){
     $type='ld_course_complete'; $uid=$course['user']->data->ID; $postid=$course['course']->ID;
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_ld_course_complete');
       } else{
         $points = (int)get_option('wpachievements_ld_course_complete');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_ld_desc', 10, 6);
 function achievement_ld_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $quiz = __('quizzes', WPACHIEVEMENTS_TEXT_DOMAIN);
    $score = __('scores', WPACHIEVEMENTS_TEXT_DOMAIN);
    $lesson = __('lessons', WPACHIEVEMENTS_TEXT_DOMAIN);
    $course = __('courses', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $quiz = __('quiz', WPACHIEVEMENTS_TEXT_DOMAIN);
    $score = __('score', WPACHIEVEMENTS_TEXT_DOMAIN);
    $lesson = __('lesson', WPACHIEVEMENTS_TEXT_DOMAIN);
    $course = __('course', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'ld_quiz_pass': { $text = sprintf( __('for passing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $quiz); } break;
   case 'ld_quiz_fail': { $text = sprintf( __('for failing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $quiz); } break;
   case 'ld_quiz_perfect': { $text = sprintf( __('for getting %s perfect %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $score); } break;
   case 'ld_lesson_complete': { $text = sprintf( __('for completing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $lesson); } break;
   case 'ld_course_complete': { $text = sprintf( __('for completing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $course); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_ld_desc', 10, 3);
 function quest_ld_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $quiz = __('quizzes', WPACHIEVEMENTS_TEXT_DOMAIN);
    $score = __('scores', WPACHIEVEMENTS_TEXT_DOMAIN);
    $lesson = __('lessons', WPACHIEVEMENTS_TEXT_DOMAIN);
    $course = __('courses', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $quiz = __('quiz', WPACHIEVEMENTS_TEXT_DOMAIN);
    $score = __('score', WPACHIEVEMENTS_TEXT_DOMAIN);
    $lesson = __('lesson', WPACHIEVEMENTS_TEXT_DOMAIN);
    $course = __('course', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'ld_quiz_pass': { $text = sprintf( __('Pass %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $quiz); } break;
   case 'ld_quiz_fail': { $text = sprintf( __('Fail %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $quiz); } break;
   case 'ld_quiz_perfect': { $text = sprintf( __('Get %s perfect %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $score); } break;
   case 'ld_lesson_complete': { $text = sprintf( __('Complete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $lesson); } break;
   case 'ld_course_complete': { $text = sprintf( __('Complete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $course); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_ld_admin', 10, 2);
 function achievement_ld_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "LearnDash",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __('User Passes Quiz', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user passes a quiz.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_ld_quiz_pass",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __('User Fails Quiz', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user fails a quiz.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_ld_quiz_fail",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __('User Gets Perfect Quiz Score', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user gets 100% on a quiz.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_ld_quiz_perfect",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __('User Completes Lesson', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user completes a lesson.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_ld_lesson_complete",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __('Users Completes Course.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when a user completes a course.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_ld_course_complete",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_ld_admin_events', 10);
 function achievement_ld_admin_events(){
   echo'<optgroup label="LearnDash Events">
     <option value="ld_quiz_pass">'.__('The user passes a quiz', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="ld_quiz_fail">'.__('The user fails a quiz', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="ld_quiz_perfect">'.__('The user gets 100% on a quiz', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="ld_lesson_complete">'.__('The user completes a lesson', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="ld_course_complete">'.__('The user completes a course', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_ld_admin_triggers', 1, 10);
 function achievement_ld_admin_triggers($trigger){

   switch($trigger){
     case 'ld_quiz_pass': { $trigger = __('The user passes a quiz', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'ld_quiz_fail': { $trigger = __('The user fails a quiz', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'ld_quiz_perfect': { $trigger = __('The user gets 100% on a quiz', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'ld_lesson_complete': { $trigger = __('The user completes a lesson', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'ld_course_complete': { $trigger = __('The user completes a course', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }
?>