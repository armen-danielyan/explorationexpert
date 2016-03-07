<?php
/**
 * Module Name: MyArcadeContest Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/MyArcadeContest
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

 //*************** Actions ***************\\
 add_action('myarcadecontest_award_user', 'wpachievements_get_award', 10, 1);
 add_action('cp_log', 'wpachievements_contest_events', 10, 4);
 //*************** Detect user getting prize ***************\\
 function wpachievements_get_award($args){
   extract($args);
   $type='myarcadecontest_add'; $uid=$user_id;
   wpachievements_new_activity($type, $uid, '', '');
 }
 //*************** Detect user events ***************\\
 function wpachievements_contest_events($type, $uid, $points, $data){
   if( $type=='myarcadecontest_subcontest' ){
     $type='myarcadecontest_subcontest'; $uid=$uid;
     wpachievements_new_activity($type, $uid, '', '');
   } elseif( $type=='myarcadecontest_subgaming' ){
     $type='myarcadecontest_subgaming'; $uid=$uid;
     wpachievements_new_activity($type, $uid, '', '');
   }
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_mac_desc', 10, 6);
 function achievement_mac_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $contest = __('contests', WPACHIEVEMENTS_TEXT_DOMAIN);
    $prize = __('prizes', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $contest = __('contest', WPACHIEVEMENTS_TEXT_DOMAIN);
    $prize = __('prize', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'myarcadecontest_subgaming': { $text = sprintf( __('for playing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $contest); } break;
   case 'myarcadecontest_add': { $text = sprintf( __('for getting %s contest %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $prize); } break;
   case 'myarcadecontest_subcontest': { $text = sprintf( __('for joining %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $contest); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_mac_desc', 10, 3);
 function quest_mac_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $contest = __('contests', WPACHIEVEMENTS_TEXT_DOMAIN);
    $prize = __('prizes', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $contest = __('contest', WPACHIEVEMENTS_TEXT_DOMAIN);
    $prize = __('prize', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'myarcadecontest_subgaming': { $text = sprintf( __('Play %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $contest); } break;
   case 'myarcadecontest_add': { $text = sprintf( __('Get %s contest %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $prize); } break;
   case 'myarcadecontest_subcontest': { $text = sprintf( __('Join %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $contest); } break;
  }
  return $text;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_mac_admin_events', 10);
 function achievement_mac_admin_events(){
   echo'<optgroup label="'.__('MyArcadeContests Events', WPACHIEVEMENTS_TEXT_DOMAIN).'">
     <option value="myarcadecontest_add">'.__('The user wins a prize in a contest', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="myarcadecontest_subcontest">'.__('The user takes part in a contest', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="myarcadecontest_subgaming">'.__('The user plays a game as part of a contest', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
   </optgroup>';

 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_mac_admin_triggers', 1, 10);
 function achievement_mac_admin_triggers($trigger){

   switch($trigger){
     case 'myarcadecontest_add': { $trigger = __('The user wins a prize in a contest', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'myarcadecontest_subcontest': { $trigger = __('The user takes part in a contest', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'myarcadecontest_subgaming': { $trigger = __('The user plays a game as part of a contest', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }
?>