<?php
/**
 * Module Name: Simple:Press Integration
 * @author Powerfusion <contact@wpachievements.net>
 * @copyright (c) 2013, Digital Builder
 * @license http://wpachievements.net
 * @package WPAchievements/Modules/SimplePress
 *
 * Copyright @ Digital Builder 2013 - contact@wpachievements.net
 *
 * Do not modify! Do not sell! Do not distribute!
 *
 */
 // Exit if accessed directly
 if( !defined( 'ABSPATH' ) ) exit;
  //*************** Actions ***************\\
  add_action('sph_post_create', 'wpachievements_sp_add_post_topic', 10, 1);
  add_action('sph_post_delete', 'wpachievements_sp_remove_post', 10, 1);
  add_action('sph_topic_delete', 'wpachievements_sp_remove_topic', 10, 1);
  if(sp_is_plugin_active('polls/sp-polls-plugin.php')){
    add_action('sph_poll_created', 'wpachievements_sp_poll_created', 10, 2);
    add_action('sph_poll_voted', 'wpachievements_sp_do_poll_voted', 10, 3);
  }
  if(sp_is_plugin_active('post-rating/sp-rating-plugin.php')){
    add_action('sph_post_rating_add', 'wpachievements_sp_rate_post', 10, 4);
    add_action('sph_post_rating_removed', 'wpachievements_sp_rate_post_removed', 10, 3);
  }
  //*************** Detect New Post/Topic ***************\\
  function wpachievements_sp_add_post_topic($newpost){
    if($newpost['userid'] > 0){
      if($newpost['poststatus'] == 0){
        if($newpost['action'] == "post"){
          $type='Forum_Post'; $uid=$newpost['userid']; $postid='';
          if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
            if(function_exists('is_multisite') && is_multisite()){
              $points = (int)get_blog_option(1, 'wpachievements_sp_post_points');
            } else{
              $points = (int)get_option('wpachievements_sp_post_points');
            }
          }
          if(empty($points)){$points=0;}
          wpachievements_new_activity($type, $uid, $postid, $points);
        } elseif($newpost['action'] == "topic"){
          $type='Forum_Topic'; $uid=$newpost['userid']; $postid='';
          if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
            if(function_exists('is_multisite') && is_multisite()){
              $points = (int)get_blog_option(1, 'wpachievements_sp_topic_points');
            } else{
              $points = (int)get_option('wpachievements_sp_topic_points');
            }
          }
          if(empty($points)){$points=0;}
          wpachievements_new_activity($type, $uid, $postid, $points);
        }
      }
    }
  }
  //*************** Detect Forum Topic Deleted ***************\\
  function wpachievements_sp_remove_topic($posts){
    $thisTopic = (is_array($posts)) ? $posts : array(0 => $posts);
    foreach($thisTopic as $k => $post){
      if($post->user_id > 0){
        if($k == 0){
          $type='Forum_Topic_delete'; $uid=$post->user_id; $postid='';
          if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
            if(function_exists('is_multisite') && is_multisite()){
              $points = (int)get_blog_option(1, 'wpachievements_sp_topic_points');
            } else{
              $points = (int)get_option('wpachievements_sp_topic_points');
            }
          }
          if(empty($points)){$points=0;}
          wpachievements_new_activity($type, $uid, $postid, -$points);
        } else {
          $type='Forum_Post_delete'; $uid=$post->user_id; $postid='';
          if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
            if(function_exists('is_multisite') && is_multisite()){
              $points = (int)get_blog_option(1, 'wpachievements_sp_post_points');
            } else{
              $points = (int)get_option('wpachievements_sp_post_points');
            }
          }
          if(empty($points)){$points=0;}
          wpachievements_new_activity($type, $uid, $postid, -$points);
        }
      }
    }
  }
  //*************** Detect Forum Post Deleted ***************\\
  function wpachievements_sp_remove_post($oldpost){
    if($oldpost->user_id > 0){
      $type='Forum_Post_delete'; $uid=$oldpost->user_id; $postid='';
      if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
        if(function_exists('is_multisite') && is_multisite()){
          $points = (int)get_blog_option(1, 'wpachievements_sp_post_points');
        } else{
          $points = (int)get_option('wpachievements_sp_post_points');
        }
      }
      if(empty($points)){$points=0;}
      wpachievements_new_activity($type, $uid, $postid, -$points);
    }
  }
  //*************** Detect New Rating ***************\\
  function wpachievements_sp_rate_post($postid, $count, $sum, $user_id){
    if(sp_is_plugin_active('post-rating/sp-rating-plugin.php') && !empty($user_id)){
      $type='Forum_Post_rating'; $uid=$user_id; $postid='';
      if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
        if(function_exists('is_multisite') && is_multisite()){
          $points = (int)get_blog_option(1, 'wpachievements_sp_rate_post_points');
        } else{
          $points = (int)get_option('wpachievements_sp_rate_post_points');
        }
      }
      if(empty($points)){$points=0;}
      wpachievements_new_activity($type, $uid, $postid, $points);

      $type='Forum_Post_rated'; $uid=$user_id; $postid='';
      if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
        if(function_exists('is_multisite') && is_multisite()){
          $points = (int)get_blog_option(1, 'wpachievements_sp_rated_post_points');
        } else{
          $points = (int)get_option('wpachievements_sp_rated_post_points');
        }
      }
      if(empty($points)){$points=0;}
      wpachievements_new_activity($type, $uid, $postid, $points);
    }
  }
  //*************** Detect Rating Deleted ***************\\
  function wpachievements_cp_sp_rate_post_removed($postid, $user_id){
    if(sp_is_plugin_active('post-rating/sp-rating-plugin.php') && !empty($user_id)){
      $type='Forum_Post_rating_delete'; $uid=$user_id; $postid='';
      if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
        if(function_exists('is_multisite') && is_multisite()){
          $points = (int)get_blog_option(1, 'wpachievements_sp_rate_post_points');
        } else{
          $points = (int)get_option('wpachievements_sp_rate_post_points');
        }
      }
      if(empty($points)){$points=0;}
      wpachievements_new_activity($type, $uid, $postid, -$points);

      $type='Forum_Post_rated_delete'; $uid=$user_id; $postid='';
      if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
        if(function_exists('is_multisite') && is_multisite()){
          $points = (int)get_blog_option(1, 'wpachievements_sp_rated_post_points');
        } else{
          $points = (int)get_option('wpachievements_sp_rated_post_points');
        }
      }
      if(empty($points)){$points=0;}
      wpachievements_new_activity($type, $uid, $postid, -$points);
    }
  }
  //*************** Detect New Poll ***************\\
  function wpachievements_sp_poll_created($pollid, $userid){
    if(sp_is_plugin_active('polls/sp-polls-plugin.php') && !empty($userid)){
      $type='Forum_Poll_create'; $uid=$user_id; $postid='';
      if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
        if(function_exists('is_multisite') && is_multisite()){
          $points = (int)get_blog_option(1, 'wpachievements_sp_create_poll_points');
        } else{
          $points = (int)get_option('wpachievements_sp_create_poll_points');
        }
      }
      if(empty($points)){$points=0;}
      wpachievements_new_activity($type, $uid, $postid, $points);
    }
  }
  //*************** Detect New Poll Voting ***************\\
  function wpachievements_sp_do_poll_voted($pollid, $userid, $creator){
    if(sp_is_plugin_active('polls/sp-polls-plugin.php') && !empty($userid)){
      $type='Forum_Poll_vote'; $uid=$user_id; $postid='';
      if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
        if(function_exists('is_multisite') && is_multisite()){
          $points = (int)get_blog_option(1, 'wpachievements_sp_vote_poll_points');
        } else{
          $points = (int)get_option('wpachievements_sp_vote_poll_points');
        }
      }
      if(empty($points)){$points=0;}
      wpachievements_new_activity($type, $uid, $postid, $points);

      $type='Forum_Poll_voted'; $uid=$creator; $postid='';
      if( !function_exists(WPACHIEVEMENTS_CUBEPOINTS) && !function_exists(WPACHIEVEMENTS_MYCRED) ){
        if(function_exists('is_multisite') && is_multisite()){
          $points = (int)get_blog_option(1, 'wpachievements_sp_rated_post_points');
        } else{
          $points = (int)get_option('wpachievements_sp_rated_post_points');
        }
      }
      if(empty($points)){$points=0;}
      wpachievements_new_activity($type, $uid, $postid, $points);
    }
  }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_activity_description', 'achievement_sp_desc', 10, 6);
 function achievement_sp_desc($text='',$type='',$points='',$times='',$title='',$data=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $fpost = __('forum posts', WPACHIEVEMENTS_TEXT_DOMAIN);
    $ftopic = __('forum topics', WPACHIEVEMENTS_TEXT_DOMAIN);
    $rating = __('ratings', WPACHIEVEMENTS_TEXT_DOMAIN);
    $poll = __('polls', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $fpost = __('forum post', WPACHIEVEMENTS_TEXT_DOMAIN);
    $fpost = __('forum topic', WPACHIEVEMENTS_TEXT_DOMAIN);
    $rating = __('rating', WPACHIEVEMENTS_TEXT_DOMAIN);
    $poll = __('poll', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'Forum_Post': { $text = sprintf( __('for adding %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $fpost); } break;
   case 'Forum_Post_delete': { $text = sprintf( __('for deleting %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $fpost); } break;
   case 'Forum_Topic': { $text = sprintf( __('for adding %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $ftopic); } break;
   case 'Forum_Topic_delete': { $text = sprintf( __('for deleting %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $ftopic); } break;
   case 'Forum_Post_rating': { $text = sprintf( __('for rating %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $fpost); } break;
   case 'Forum_Post_rating_delete': { $text = sprintf( __('for removing %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $fpost); } break;
   case 'Forum_Post_rated': { $text = sprintf( __('for getting %s %s on your %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $rating, $fpost); } break;
   case 'Forum_Post_rated_delete': { $text = sprintf( __('for losing %s %s on your %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $rating, $fpost); } break;
   case 'Forum_Poll_create': { $text = sprintf( __('for adding %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $poll); } break;
   case 'Forum_Poll_vote': { $text = sprintf( __('for voting on %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $poll); } break;
   case 'Forum_Poll_voted': { $text = sprintf( __('for getting %s votes on your %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $poll); } break;
  }
  return $text;
 }

 //*************** Descriptions ***************\\
 add_filter('wpachievements_quest_description', 'quest_sp_desc', 10, 3);
 function quest_sp_desc($text='',$type='',$times=''){
  $pt = WPACHIEVEMENTS_POST_TEXT;
  if($times>1){
    $pt = WPACHIEVEMENTS_POST_TEXT."'s";
    $fpost = __('forum posts', WPACHIEVEMENTS_TEXT_DOMAIN);
    $ftopic = __('forum topics', WPACHIEVEMENTS_TEXT_DOMAIN);
    $rating = __('ratings', WPACHIEVEMENTS_TEXT_DOMAIN);
    $poll = __('polls', WPACHIEVEMENTS_TEXT_DOMAIN);
  } else{
    $fpost = __('forum post', WPACHIEVEMENTS_TEXT_DOMAIN);
    $fpost = __('forum topic', WPACHIEVEMENTS_TEXT_DOMAIN);
    $rating = __('rating', WPACHIEVEMENTS_TEXT_DOMAIN);
    $poll = __('poll', WPACHIEVEMENTS_TEXT_DOMAIN);
  }
  switch($type){
   case 'Forum_Post': { $text = sprintf( __('Add %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $fpost); } break;
   case 'Forum_Post_delete': { $text = sprintf( __('Delete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $fpost); } break;
   case 'Forum_Topic': { $text = sprintf( __('Add %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $ftopic); } break;
   case 'Forum_Topic_delete': { $text = sprintf( __('Delete %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $ftopic); } break;
   case 'Forum_Post_rating': { $text = sprintf( __('Rate %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $fpost); } break;
   case 'Forum_Post_rating_delete': { $text = sprintf( __('Remove %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $fpost); } break;
   case 'Forum_Post_rated': { $text = sprintf( __('Get %s %s on your %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $rating, $fpost); } break;
   case 'Forum_Post_rated_delete': { $text = sprintf( __('Lose %s %s on your %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $rating, $fpost); } break;
   case 'Forum_Poll_create': { $text = sprintf( __('Add %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $poll); } break;
   case 'Forum_Poll_vote': { $text = sprintf( __('Vote on %s %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $poll); } break;
   case 'Forum_Poll_voted': { $text = sprintf( __('Get %s votes on your %s', WPACHIEVEMENTS_TEXT_DOMAIN), $times, $poll); } break;
  }
  return $text;
 }

 //*************** Admin Settings ***************\\
 add_filter('wpachievements_admin_settings', 'achievement_sp_admin', 10, 2);
 function achievement_sp_admin($options,$shortname){
  $options = $options;
    $options[] = array( "name" => "Simple:Press",
      "class" => "separator",
      "type" => "separator");
    $options[] = array( "name" => __('User Adding Topics', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a topic.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_sp_topic_points",
      "std" => "0",
      "type" => "text");
    $options[] = array( "name" => __('User Adding Reply', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Points awarded when the user adds a topic reply.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_sp_post_points",
      "std" => "0",
      "type" => "text");
    if(sp_is_plugin_active('post-rating/sp-rating-plugin.php')){
      $options[] = array( "name" => __('User Adding Rating', WPACHIEVEMENTS_TEXT_DOMAIN),
        "desc" => __('Points awarded when the user adds a rating.', WPACHIEVEMENTS_TEXT_DOMAIN),
        "id" => $shortname."_sp_rate_post_points",
        "std" => "0",
        "type" => "text");
      $options[] = array( "name" => __("User's Post Rated", WPACHIEVEMENTS_TEXT_DOMAIN),
        "desc" => __('Points awarded when a post by the user gets a rating.', WPACHIEVEMENTS_TEXT_DOMAIN),
        "id" => $shortname."_sp_rated_post_points",
        "std" => "0",
        "type" => "text");
    }
    if(sp_is_plugin_active('polls/sp-polls-plugin.php')){
      $options[] = array( "name" => __("User Adding Poll", WPACHIEVEMENTS_TEXT_DOMAIN),
        "desc" => __('Points awarded when the user adds a poll.', WPACHIEVEMENTS_TEXT_DOMAIN),
        "id" => $shortname."_sp_create_poll_points",
        "std" => "0",
        "type" => "text");
      $options[] = array( "name" => __("User Adding Poll Voting", WPACHIEVEMENTS_TEXT_DOMAIN),
        "desc" => __('Points awarded when the user votes on a poll.', WPACHIEVEMENTS_TEXT_DOMAIN),
        "id" => $shortname."_sp_vote_poll_points",
        "std" => "0",
        "type" => "text");
      $options[] = array( "name" => __("User's Poll Voted", WPACHIEVEMENTS_TEXT_DOMAIN),
        "desc" => __('Points awarded when a poll by the user gets a vote.', WPACHIEVEMENTS_TEXT_DOMAIN),
        "id" => $shortname."_sp_voted_poll_points",
        "std" => "0",
        "type" => "text");
    }
  return $options;
 }

 //*************** Admin Events ***************\\
 add_filter('wpachievements_admin_events', 'achievement_sp_admin_events', 10);
 function achievement_sp_admin_events(){
   echo'<optgroup label="Simple:Press Events">
     <option value="Forum_Post">'.__('The user adds a forum post', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
     <option value="Forum_Topic">'.__('The user creates a forum topic', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>';
     if(sp_is_plugin_active('post-rating/sp-rating-plugin.php')){
       echo '<option value="Forum_Post_rating">'.__('The user rates a forum post', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
       <option value="Forum_Post_rated">'.__('The users forum post is rated by another user', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>';
     }
     if(sp_is_plugin_active('polls/sp-polls-plugin.php')){
       echo '<option value="Forum_Poll_create">'.__('The user creates a forum poll', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
       <option value="Forum_Poll_vote">'.__('The user votes in a forum poll', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>
       <option value="Forum_Poll_voted">'.__('The users forum poll receives a vote', WPACHIEVEMENTS_TEXT_DOMAIN).'</option>';
     }
   echo '</optgroup>';
 }

 //*************** Admin Trigger Naming ***************\\
 add_filter('wpachievements_trigger_description', 'achievement_sp_admin_triggers', 1, 10);
 function achievement_sp_admin_triggers($trigger){

   switch($trigger){
     case 'Forum_Post': { $trigger = __('The user adds a forum post', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'Forum_Topic': { $trigger = __('The user creates a forum topic', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'Forum_Post_rating': { $trigger = __('The user rates a forum post', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'Forum_Post_rated': { $trigger = __('The users forum post is rated by another user', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'Forum_Poll_create': { $trigger = __('The user creates a forum poll', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'Forum_Poll_vote': { $trigger = __('The user votes in a forum poll', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
     case 'Forum_Poll_voted': { $trigger = __('The users forum poll receives a vote', WPACHIEVEMENTS_TEXT_DOMAIN); } break;
   }

   return $trigger;

 }
?>