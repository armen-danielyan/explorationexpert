<?php
/**
 * Module Name: WooCommerce Points and Rewards Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/WooCommerce-Points-and-Rewards
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;
 //*************** Actions ***************\\
 add_filter( 'wc_points_rewards_event_description', 'wpachievements_wcpar_description', 10, 3 );

 //*************** Functions that handles the point Descriptions ***************\\
 function wpachievements_wcpar_description( $event_description, $event_type, $event ) {
   switch ( $event_type ) {
     case 'wpachievements_achievement': $event_description = sprintf( __( '%s earned for getting the achievement: %s', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points, $event->data['achievement_id'] ); break;
     case 'wpachievements_achievement_added': $event_description = sprintf( __( '%s earned for admin adding the achievement: %s', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points, $event->data['achievement_id']  ); break;
     case 'wpachievements_achievement_removed': $event_description = sprintf( __( '%s removed by admin removing the achievement: %s', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points, $event->data['achievement_id']  ); break;
     case 'wpachievements_achievement_edited_add': $event_description = sprintf( __( '%s earned because an achievements points have been increased.', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points ); break;
     case 'wpachievements_achievement_edited_remove': $event_description = sprintf( __( '%s earned because an achievements points have been decreased.', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points ); break;


     case 'wpachievements_quest': $event_description = sprintf( __( '%s earned for getting the quest: %s', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points, $event->data['achievement_id'] ); break;
     case 'wpachievements_quest_added': $event_description = sprintf( __( '%s earned for admin adding the quest: %s', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points, $event->data['achievement_id']  ); break;
     case 'wpachievements_quest_removed': $event_description = sprintf( __( '%s removed by admin removing the quest: %s', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points, $event->data['achievement_id']  ); break;
     case 'wpachievements_quest_edited_add': $event_description = sprintf( __( '%s earned because an quest points have been increased.', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points ); break;
     case 'wpachievements_quest_edited_remove': $event_description = sprintf( __( '%s earned because an quest points have been decreased.', WPACHIEVEMENTS_TEXT_DOMAIN ), $event->points ); break;
   }
   return $event_description;
 }
?>