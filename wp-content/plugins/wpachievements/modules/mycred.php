<?php
/**
 * Module Name: myCRED Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/myCRED
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;
 //*************** Actions ***************\\
 add_action('mycred_update_user_balance','wpa_mycred_update_user_balance');
 //*************** Functions that handles the point Descriptions ***************\\
 function myCRED_Desc($ref,$creds,$entry,$log_entry){
   $mycred = mycred();
   return $mycred->parse_template_tags( $entry, $log_entry );
 }
 function wpa_mycred_update_user_balance( $user_id='', $current_balance='', $amount='', $cred_id='' ){
   if( !empty($user_id) && !empty($amount) && $amount == 0 ){
     if( $amount > 0 ){
       wpachievements_increase_points( $user_id, abs($amount) );
     } else{
       wpachievements_decrease_points( $user_id, abs($amount) );
     }
   }
 }
?>