<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

get_header();
global $post;
$locked = get_post_meta( $post->ID, 'game_locked', true );
if(function_exists('is_multisite') && is_multisite()){
	global $wpdb;
	$ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
} else{
	$ranks = (array)get_option('wpachievements_ranks_data');
}
ksort($ranks);
$ii='';
if(!empty($locked) && $locked!='any'){
 foreach($ranks as $p=>$r){
  $ii++;
  if($locked<=$p){
   if(is_array($r)){ $locked = $r[0]; } else{ $locked = $r; }
   break;
  }
 }
}
echo '<center>';
 if(is_user_logged_in()){
  echo '<div id="content_locked">
   <div id="locked_icon_holder">';
   
     list($lvlstat,$wid) = wpa_ranks_widget();
     echo $lvlstat;
     echo "<script>
     jQuery(document).ready(function(){
       jQuery('.pb_bar_user_login').animate({width:'".$wid."px'},1500);
     });
     </script>";
   
   echo '</div>
   <div style="clear:both;height:1px;"></div>
   <p style="font-weight:bold;line-height:22px;margin-top:5px;">'. sprintf( __('To unlock this %s you must reach', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT ) .':<br />Rank '.$ii.': '.$locked.'</p>
  </div>';
 } else{
  echo '<div id="content_locked">
   <div id="locked_icon_holder">
    <img src="'.plugins_url('wpachievements/img/locked.png').'" alt="Content Locked Icon" height="58" />
    <a href="'.get_bloginfo('url').'/wp-login.php?action=register" id="locked_register">'. __('Register to View', WPACHIEVEMENTS_TEXT_DOMAIN) .'</a>
   </div>
   <div style="clear:both;height:1px;"></div>
   <p style="font-weight:bold;line-height:22px;margin-top:5px;">'. sprintf( __('To unlock this %s you must reach', WPACHIEVEMENTS_TEXT_DOMAIN), WPACHIEVEMENTS_POST_TEXT ) .':<br />Rank '.$ii.': '.$locked.'</p>
  </div>';
 }
echo '</center>';
get_footer(); ?>