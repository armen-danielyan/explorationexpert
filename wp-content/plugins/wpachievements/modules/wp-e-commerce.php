<?php
/**
 * Module Name: WP e-Commerce Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WP-e-Commerce
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action("wpsc_activate_subscription", "wpachievements_wpsc_activate_subscription", 10);
 add_action("wpsc_payment_successful", "wpachievements_wpsc_payment_successful", 10);
 //*************** Detect Paypal Subscription Setup ***************\\
 function wpachievements_wpsc_activate_subscription(){
   if( is_user_logged_in() ){
	   $type='wpsc_activate_subscription'; $uid=''; $points=''; $data=''; $postid='';
	   wpachievements_new_activity($type, $uid, $postid, $points);
	 }
 }
 //*************** Detect Product Purchase ***************\\
 function wpachievements_wpsc_payment_successful(){
   if( is_user_logged_in() ){
     $type='wpsc_payment_successful'; $uid=''; $postid='';
     if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
       if(function_exists('is_multisite') && is_multisite()){
         $points = (int)get_blog_option(1, 'wpachievements_wpsc_payment');
       } else{
         $points = (int)get_option('wpachievements_wpsc_payment');
       }
     }
     if(empty($points)){$points=0;}
     wpachievements_new_activity($type, $uid, $postid, $points);
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_wpsc_desc', 10, 6);
 function achievement_wpsc_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $checkout = __('checkouts', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $checkout = __('checkout', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'wpsc_activate_subscription': { $text = __('for setting up a PayPal Subscription', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'wpsc_payment_successful': { $text = sprintf( __('for completing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $checkout); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_wpsc_desc', 10, 3);
 function quest_wpsc_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $checkout = __('checkouts', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $checkout = __('checkout', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'wpsc_activate_subscription': { $text = __('Set up a PayPal Subscription', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   case 'wpsc_payment_successful': { $text = sprintf( __('Complete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $checkout); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_wpsc_admin', 10, 2);
 function achievement_wpsc_admin($options,$shortname){
  $options = $options;
  $options[] = array( "name" => "WP e-Commerce",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __('User Making Purchases', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user purchases goods.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_wpsc_payment",
      "std" => "0",
      "type" => "text");
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_wpsc_admin_events', 10);
 function achievement_wpsc_admin_events(){
   echo'<optgroup label="WP e-Commerce Events">
     <option value="wpsc_activate_subscription">'.__('The user sets up a PayPal Subscription', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="wpsc_payment_successful">'.__('The user completes checkout', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_wpsc_admin_triggers', 1, 10);
 function achievement_wpsc_admin_triggers($trigger){

   switch($trigger){
     case 'wpsc_activate_subscription': { $trigger = __('The user sets up a PayPal Subscription', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'wpsc_payment_successful': { $trigger = __('The user completes checkout', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }

?>