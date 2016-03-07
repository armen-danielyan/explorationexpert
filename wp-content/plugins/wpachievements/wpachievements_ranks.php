<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 ***********************************
 *    R A N K I N G   S E T U P    *
 ***********************************
 */
 //*************** Setup ranking functions ***************\\
 function wpachievements_getRank($uid){
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
      $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status')); 
    } else{
      $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status')); 
    }
  } else{
    $ranktype = strtolower(get_option('wpachievements_rank_type')); 
  }
  if( $ranktype != 'achievements' ){
    if(function_exists(WPACHIEVEMENTS_MYCRED)){
      $pointType = get_option('wpachievements_mycred_point_type');
      $points = (int)mycred_get_users_cred( $uid, $pointType );
      return wpachievements_pointsToRank($points);
    } elseif(!function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
      $points = (int)get_user_meta( $uid, 'achievements_points', true );
      return wpachievements_pointsToRank($points);
    } else{
      return wpachievements_pointsToRank(cp_getPoints($uid));
    }
  } else{
    $points = (int)get_user_meta( $uid, 'achievements_count', true );
    return wpachievements_pointsToRank($points);
  }
 }
 function wpachievements_getRankImage($uid){
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
      $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status')); 
    } else{
      $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status')); 
    }
  } else{
    $ranktype = strtolower(get_option('wpachievements_rank_type')); 
  }
  if( $ranktype != 'achievements' ){
    if(function_exists(WPACHIEVEMENTS_MYCRED)){
      $pointType = get_option('wpachievements_mycred_point_type');
      $points = (int)mycred_get_users_cred( $uid, $pointType );
    } elseif(!function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
      $points = (int)get_user_meta( $uid, 'achievements_points', true );
    } else{
      $points = (int)cp_getPoints($uid);
    }
  } else{
    $points = (int)get_user_meta( $uid, 'achievements_count', true );
  }
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    $ranks = get_blog_option(1,'wpachievements_ranks_data');
  } else{
    $ranks = get_option('wpachievements_ranks_data');
  }
  ksort($ranks);
  $ranks = array_reverse($ranks, 1);
  foreach($ranks as $p=>$r){
   if($points>=$p){
    if(is_array($r)){ return '<img src="'.$r[1].'" alt="Rank '.$r[0].' Image Icon" class="wpa_rank_badge" />'; } else{ return ''; }
   }
  }
 }
 function wpachievements_getRankImage_url($uid){
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
      $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status')); 
    } else{
      $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status')); 
    }
  } else{
    $ranktype = strtolower(get_option('wpachievements_rank_type')); 
  }
  if( $ranktype != 'achievements' ){
    if(function_exists(WPACHIEVEMENTS_MYCRED)){
      $pointType = get_option('wpachievements_mycred_point_type');
      $points = (int)mycred_get_users_cred( $uid, $pointType );
    } elseif(!function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
      $points = (int)get_user_meta( $uid, 'achievements_points', true );
    } else{
      $points = (int)cp_getPoints($uid);
    }
  } else{
    $points = (int)get_user_meta( $uid, 'achievements_count', true );
  }
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    $ranks = get_blog_option(1,'wpachievements_ranks_data');
  } else{
    $ranks = get_option('wpachievements_ranks_data');
  }
  ksort($ranks);
  $ranks = array_reverse($ranks, 1);
  foreach($ranks as $p=>$r){
   if($points>=$p){
    if(is_array($r)){ return $r[1]; } else{ return ''; }
   }
  }
 }
 function wpachievements_pointsToRank($points){
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    $ranks = get_blog_option(1,'wpachievements_ranks_data');
  } else{
    $ranks = get_option('wpachievements_ranks_data');
  }
  ksort($ranks);
  $ranks = array_reverse($ranks, 1);
  foreach($ranks as $p=>$r){
   if($points>=$p){
    if(is_array($r)){ return $r[0]; } else{ return $r; }
   }
  }
 }
 function wpachievements_rankToPoints($rank){
  if(function_exists('is_multisite') && is_multisite()){
    global $wpdb;
    $ranks = get_blog_option(1,'wpachievements_ranks_data');
  } else{
    $ranks = get_option('wpachievements_ranks_data');
  }
  return array_search($rank, $ranks);
 }
 function wpachievements_rank_track($type, $uid, $postid, $points, $usersrank, $notachievement){
  global $wpdb;
  if(!empty($uid)){
    $currentusersrank = wpachievements_getRank($uid);
    if( !empty($usersrank) && $usersrank != $currentusersrank ){
      do_action('wpachievements_new_rank_gained', $uid);
    }
  }   
 }
 add_action('wpachievements_after_new_activity', 'wpachievements_rank_track', 10, 6);
 add_action('wpachievements_after_new_custom_activity', 'wpachievements_rank_track', 10, 6);
/**
 *******************************************************
 *   W P A C H I E V E M E N T S   M E N U   T A B S   *
 *******************************************************
 */
 if(function_exists('is_multisite') && is_multisite()){
   global $wpdb;
   if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
     $rankstatus = get_blog_option(1,'wpachievements_rank_status'); 
   } else{
     $rankstatus = get_blog_option($wpdb->blogid,'wpachievements_rank_status'); 
   }
 } else{
   $rankstatus = get_option('wpachievements_rank_status'); 
 }
 //*************** Actions ***************\\
 if($rankstatus != 'Disable'){
  add_action('wp_head','update_wpachievements_ranks_menu');
  add_action('admin_bar_menu', 'custom_ranks_menu', 1000);
 }
 //*************** Update Menu Tabs ***************\\
 function update_wpachievements_ranks_menu(){
  if(is_user_logged_in()){
    if ( !is_admin_bar_showing() )
      return;
    wp_enqueue_script( 'Rank_Script', plugins_url('/js/front-ranks-script.js', __FILE__) );
  }
 }
 //*************** Create Ranks Menu ***************\\
 function custom_ranks_menu() {
  if(is_user_logged_in()){
    if ( !is_admin_bar_showing() )
      return;
      
    global $wp_admin_bar, $current_user, $wpdb, $cp_module;
    get_currentuserinfo();
    if(function_exists(WPACHIEVEMENTS_CUBEPOINTS) && function_exists(WPACHIEVEMENTS_BUDDYPRESS_INT) && defined( WPACHIEVEMENTS_BUDDYPRESS )){global $bp; $link = home_url().'/members/' . $current_user->user_login .'/'. $bp->cubepoint->slug .'/';} elseif(function_exists(WPACHIEVEMENTS_CUBEPOINTS) && cp_module_activated('mypoints')){$link = admin_url('admin.php?page=cp_modules_mypoints_admin');} else{$link = '';}
    
    if(function_exists('is_multisite') && is_multisite()){
      global $wpdb;
      if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
        $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_status')); 
      } else{
        $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_status')); 
      }
    } else{
      $ranktype = strtolower(get_option('wpachievements_rank_type')); 
    }
    if( $ranktype != 'achievements' ){
      if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
        $points = get_user_meta( $current_user->ID, 'cpoints', true );
        if(empty($points)){$points = 0;}
        if(function_exists('is_multisite') && is_multisite()){
          global $wpdb;
          $prefix = get_blog_option($wpdb->blogid, 'cp_prefix' );
          $suffix = get_blog_option($wpdb->blogid, 'cp_suffix' );
          $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
        } else{
          $prefix = get_option( 'cp_prefix' );
          $suffix = get_option( 'cp_suffix' );
          $ranks = (array)get_option('wpachievements_ranks_data');
        }
      } elseif(function_exists(WPACHIEVEMENTS_MYCRED)){
        $pointType = get_option('wpachievements_mycred_point_type');
        $points = (int)mycred_get_users_cred( $current_user->ID, $pointType );
        if(empty($points)){$points = 0;}
        if(function_exists('is_multisite') && is_multisite()){
          global $wpdb;
          $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
        } else{
          $ranks = (array)get_option('wpachievements_ranks_data');
        }
      } else{
        $points = get_user_meta( $current_user->ID, 'achievements_points', true );
        if(empty($points)){$points = 0;}
        if(function_exists('is_multisite') && is_multisite()){
          $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
        } else{
          $ranks = (array)get_option('wpachievements_ranks_data');
        }
        $prefix = '';
        if($points == 1 || $points == -1){
          $suffix = ' Point';
        } else{
          $suffix = ' Points';
        }
      }
    } else{
      $points = get_user_meta( $current_user->ID, 'achievements_count', true );
      if(empty($points)){$points = 0;}
      if(function_exists('is_multisite') && is_multisite()){
        $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
      } else{
        $ranks = (array)get_option('wpachievements_ranks_data');
      }
      $prefix = '';
      if($points == 1 || $points == -1){
        $suffix = ' Achievement';
      } else{
        $suffix = ' Achievements';
      }
    }
    ksort($ranks);
    $ii=0;
    if(function_exists(WPACHIEVEMENTS_MYCRED)){
      $mycred = mycred();
      foreach($ranks as $p=>$r){
        $ii++;
        if($points<$p){
          $np = $mycred->format_number( $p - $points );
          $iii = $ii-1;
          $nrm = sprintf( __('%s until next rank', WPACHIEVEMENTS_TEXT_DOMAIN), $np);
          break;
        }
      }
    } else{
      foreach($ranks as $p=>$r){
        $ii++;
        if($points<$p){
          $np = $prefix.number_format($p - $points).$suffix;
          $iii = $ii-1;
          $nrm = sprintf( __('%s until next rank', WPACHIEVEMENTS_TEXT_DOMAIN), $np);
          break;
        }
      }
    }
    if(empty($nrm)){$nrm='You have reached the highest rank!!';$mid='custom_ranks_menu_lim';$iii=count($ranks);}else{$mid='custom_ranks_menu';}
    if(function_exists('is_multisite') && is_multisite()){
      $rtl = get_blog_option(1,'wpachievements_rtl_lang');
    } else{
      $rtl = get_option('wpachievements_rtl_lang');
    }
    if($rtl != 'true'){
      $rank = __( 'Rank', 'wpquizzes' ).' '.$iii.': '.wpachievements_getRank($current_user->ID);  
    } else{
      $rank = wpachievements_getRank($current_user->ID).': '.$iii.' '.__( 'Rank', 'wpquizzes' );  
    }
    $wp_admin_bar->add_menu( array( 'id' => $mid, 'parent' => 'top-secondary', 'title' => $rank, 'textdomain', 'href' => $link ) );
    $wp_admin_bar->add_menu( array( 'id' => 'custom_ranks_menu_inner', 'parent' => $mid, 'title' => '<strong>'.$nrm.'</strong>', 'href' => FALSE, 'meta' => array( 'class' => 'custom_ranks_head' ) ) );
  }
 }
 function wpa_ranks_widget($cur_user='') {
  global $current_user, $wpdb;
  get_currentuserinfo();
  if( empty($cur_user) ){
    $cur_user = $current_user->ID;
  }
    
  $prefix = '';
  $suffix = '';
  if(function_exists('is_multisite') && is_multisite()){
    if( get_option('wpachievements_network_data') != '' && get_option('wpachievements_network_data') == 'Network Wide' ){
      $ranktype = strtolower(get_blog_option(1,'wpachievements_rank_type')); 
    } else{
      $ranktype = strtolower(get_blog_option($wpdb->blogid,'wpachievements_rank_type')); 
    }
  } else{
    $ranktype = strtolower(get_option('wpachievements_rank_type')); 
  }
  if( $ranktype != 'achievements' ){
    if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
      $points = get_user_meta( $cur_user, 'cpoints', true );
      if(empty($points)){$points = 0;}
      if(function_exists('is_multisite') && is_multisite()){
        global $wpdb;
        $prefix = get_blog_option($wpdb->blogid, 'cp_prefix' );
        $suffix = get_blog_option($wpdb->blogid, 'cp_suffix' );
        $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
      } else{
        $prefix = get_option( 'cp_prefix' );
        $suffix = get_option( 'cp_suffix' );
        $ranks = (array)get_option('wpachievements_ranks_data');
      }
    } elseif(function_exists(WPACHIEVEMENTS_MYCRED)){
      $pointType = get_option('wpachievements_mycred_point_type');
      $points = (int)mycred_get_users_cred( $cur_user, $pointType );
        if(empty($points)){$points = 0;}
        if(function_exists('is_multisite') && is_multisite()){
          $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
        } else{
          $ranks = (array)get_option('wpachievements_ranks_data');
        }
    } else{
      $points = get_user_meta( $cur_user, 'achievements_points', true );
      if(empty($points)){$points = 0;}
      if(function_exists('is_multisite') && is_multisite()){
        $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
      } else{
        $ranks = (array)get_option('wpachievements_ranks_data');
      }
      $prefix = '';
      if($points == 1 || $points == -1){
        $suffix = ' Point';
      } else{
        $suffix = ' Points';
      }
    }
  } else{
    $points = get_user_meta( $cur_user, 'achievements_count', true );
    if(empty($points)){$points = 0;}
    if(function_exists('is_multisite') && is_multisite()){
      $ranks = (array)get_blog_option(1,'wpachievements_ranks_data');
    } else{
      $ranks = (array)get_option('wpachievements_ranks_data');
    }
    $prefix = '';
    if($points == 1 || $points == -1){
      $suffix = ' Achievement';
    } else{
      $suffix = ' Achievements';
    }
  }
  ksort($ranks);
  $ii=0;
  foreach($ranks as $p=>$r){
    $ii++;
    if($points<$p){
      if(is_array($r)){ $nr = $r[0]; } else{ $nr = $r; }
      $tp = $p;
      $np = $prefix.number_format($p - $points).$suffix;
      $iii = $ii-1;
      $nrm = $np.' <span class="li_points_alt_col">'.__('until next rank', WPACHIEVEMENTS_TEXT_DOMAIN).'</span>';
      break;
    }
  }
  if(function_exists(WPACHIEVEMENTS_MYCRED)){
    $mycred = mycred();
    $maxpoints=0;
    foreach($ranks as $p=>$r){
      $ii++;
      if( $p > $maxpoints )
        $maxpoints = $p;
      if($points<$p){
        if(is_array($r)){ $nr = $r[0]; } else{ $nr = $r; }
        $tp = $p;
        $np = $mycred->format_number( $p - $points );
        $iii = $ii-1;
        $nrm = $np.' <span class="li_points_alt_col">'.__('until next rank', WPACHIEVEMENTS_TEXT_DOMAIN).'</span>';
        break;
      }
    }
  } else{
    $maxpoints=0;
    foreach($ranks as $p=>$r){
      $ii++;
      if( $p > $maxpoints )
        $maxpoints = $p;
      if($points<$p){
        if(is_array($r)){ $nr = $r[0]; } else{ $nr = $r; }
        $tp = $p;
        $np = $prefix.number_format($p - $points).$suffix;
        $iii = $ii-1;
        $nrm = $np.' <span class="li_points_alt_col">'.__('until next rank', WPACHIEVEMENTS_TEXT_DOMAIN).'</span>';
        break;
      }
    }
  }
  if(empty($nrm)){$nrm = __('You are the highest rank!!', WPACHIEVEMENTS_TEXT_DOMAIN); $tp = $maxpoints;$wid = 230; if($points > $maxpoints){$points = $maxpoints;}}
  else{
    if( $points < 0 ){ $points = 0; }
    $count1 = $points / $tp;
    $count2 = $count1 * 100;
    $count = number_format($count2, 0);
    $wid = 230*($count/100);
  }
  $lvlstat='<div class="user_login_points"><div class="user_current_rank">'.wpachievements_getRank($cur_user).'</div><div class="pb_hold"><div class="pb_back_user_login"></div><div class="pb_bar_user_login"></div><div class="usr_point_count">'.$points.'/'.$tp.'</div></div><div class="li_points">'.$nrm.'</div></div>';
  return array($lvlstat,$wid);
 }
 
 add_filter('wpachievements_admin_general_settings', 'achievement_rank_admin', 10, 2);
 function achievement_rank_admin($options,$shortname){
   $enabledisable = array("Enable","Disable");
   $options = $options;
   $options[] = array( "name" => __('User Ranks', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Select whether you want to use the user ranks system.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_rank_status",
      "std" => "Enable",
      "type" => "select",
      "options" => $enabledisable);
   $types = array("Points","Achievements");
   $options = $options;
   $options[] = array( "name" => __('Rank Type', WPACHIEVEMENTS_TEXT_DOMAIN),
      "desc" => __('Select whether you want to use points or achievements for the user ranks system.', WPACHIEVEMENTS_TEXT_DOMAIN),
      "id" => $shortname."_rank_type",
      "std" => "Enable",
      "type" => "select",
      "options" => $types);
   return $options;
 }
?>