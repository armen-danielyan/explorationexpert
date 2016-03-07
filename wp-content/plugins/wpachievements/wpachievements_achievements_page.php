<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if( !is_home() ){ get_header(); }
global $wpdb, $current_user;
get_currentuserinfo();
if(!function_exists(WPACHIEVEMENTS_MYCRED)){
  if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
    if(function_exists('is_multisite') && is_multisite()){
      $prefix = get_blog_option($wpdb->blogid, 'cp_prefix' );
      $suffix = get_blog_option($wpdb->blogid, 'cp_suffix' );
    } else{
      $prefix = get_option( 'cp_prefix' );
      $suffix = get_option( 'cp_suffix' );
    }
  } else{
    $prefix = '';
    $suffix = ' Points';
  }
}

wp_enqueue_style( 'wpachievements-achievements-page-style', plugins_url('/css/page-style.css', __FILE__) );

if(function_exists('is_multisite') && is_multisite()){
  switch_to_blog(1);
}
$args = array(
  'post_type' => 'wpquests',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'orderby' => 'date',
  'order' => 'ASC'
);
$quest_query = new WP_Query( $args );
if( $quest_query->have_posts() ){
  $questsShow = '';
} else{
  $questsShow = ' style="display:none;"';
}
wp_reset_postdata();
          
echo '<center>
<div id="wpa_list_holder">
  <div class="wpa_list_hold" id="wpa_list_quests"'.$questsShow.'>
    <h2 class="wpa_heading_title"><span id="quest_icon"></span>'.sprintf(__('Our %sQuests%s', WPACHIEVEMENTS_TEXT_DOMAIN), '<span>','</span>').'</h2>
    <div class="wpa_list_inner">
      <div class="wpa_list_inner_info">
        <div id="quest_info">';
          $ii=0;
          $args = array(
            'post_type' => 'wpquests',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC'
          );
          $quest_query = new WP_Query( $args );
          if( $quest_query->have_posts() ){
            while( $quest_query->have_posts() ){
              $quest_query->the_post();
              $quest_ID = get_the_ID();
              $quest_title = get_the_title();
              $quest_desc = get_the_content();
              $quest_img = get_post_meta( $quest_ID, '_quest_image', true );
              $cur_details = get_post_meta( $quest_ID, '_quest_details', true );
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $mycred = mycred();
                $quest_points = $mycred->format_creds( get_post_meta( $quest_ID, '_quest_points', true ) );
              } else{
                $quest_points = $prefix.get_post_meta( $quest_ID, '_quest_points', true ).$suffix;
              }
              $quest_type = get_post_meta( $quest_ID, '_achievement_type', true );
            
                $ii++;
                echo '<div class="achive_tab" id="quest_tab_'.$ii.'"'; if($ii==1){echo 'style="display:block;"';} echo '>
                  <div class="wpa_image_block">';
                    if( is_user_logged_in() ){
                      $userquests = get_user_meta( $current_user->ID, 'quests_gained', true );
                      if( is_array($userquests) && in_array($quest_ID, $userquests) ){
                        echo '<div class="ach_point unlocked">'.__('Unlocked', WPACHIEVEMENTS_TEXT_DOMAIN).'</div>';
                      } else{
                        echo '<div class="ach_point">+'.$quest_points.'</div>';
                      }
                    } else{
                      echo '<div class="ach_point">+'.$quest_points.'</div>';
                    }
                    echo '<img src="'.$quest_img.'" alt="'.$quest_title.' Icon" width="100" />';
                  echo '</div>';
                  echo '<div class="wpa_info_block">';
                    echo '<h2>'.$quest_title.'</h2>
                    <p class="achieve_desc">'.$quest_desc.'</p>
                    <h3>'.__('How do I get this?', WPACHIEVEMENTS_TEXT_DOMAIN).'</h3>';
                    echo '<p style="margin-top:2px;">';
                      $quests = ''; $already_counted = array();
                      $iiii=0;
                      foreach( $cur_details as $cur_detail ){
                        $iiii++;
                        if( !array_key_exists($cur_detail['type'],$already_counted) || $cur_detail['type'] == 'wpachievements_achievement' ){
                          if( $cur_detail['type'] == 'wpachievements_achievement' ){
                            if( $iiii != 1 ){
                              $quests[$cur_detail['type']] = ', '.__('Unlock achievement: ', WPACHIEVEMENTS_TEXT_DOMAIN).' '.get_achievement_name($cur_detail['ach_id']);
                            } else{
                              $quests[$cur_detail['type']] = __('Unlock achievement: ', WPACHIEVEMENTS_TEXT_DOMAIN).' '.get_achievement_name($cur_detail['ach_id']);
                            }
                          } else{
                            if( $iiii != 1 ){
                              $quests[$cur_detail['type']] = ', '.quest_Desc($cur_detail['type'],$cur_detail['occurrences']);
                            } else{
                              $quests[$cur_detail['type']] = quest_Desc($cur_detail['type'],$cur_detail['occurrences']);
                            }
                          }
                          $already_counted[$cur_detail['type']] = $cur_detail['occurrences'];
                        } elseif( $already_counted[$cur_detail['type']] < $cur_detail['occurrences'] ){
                          if( $cur_detail['type'] == 'wpachievements_achievement') {
                            if( $iiii != 1 ){
                              $quests[$cur_detail['type']] = ', '.__('Unlock achievement: ', WPACHIEVEMENTS_TEXT_DOMAIN).' '.get_achievement_name($cur_detail['ach_id']);
                            } else{
                              $quests[$cur_detail['type']] = __('Unlock achievement: ', WPACHIEVEMENTS_TEXT_DOMAIN).' '.get_achievement_name($cur_detail['ach_id']);
                            }
                          } else{
                            if( $iiii != 1 ){
                              $quests[$cur_detail['type']] = ', '.quest_Desc($cur_detail['type'],$cur_detail['occurrences']);
                            } else{
                              $quests[$cur_detail['type']] = quest_Desc($cur_detail['type'],$cur_detail['occurrences']);
                            }
                          }
                          $already_counted[$cur_detail['type']] = $cur_detail['occurrences'];
                        }
                      }
                      $quest_list='';
                      if( is_array($quests) ){
                        foreach( $quests as $quest ){
                          $quest_list .= $quest;
                        }
                      }
                      echo $quest_list;
                    echo '</p>';
                  echo '</div>';
                echo '</div>';
              
            } 
          }
          wp_reset_postdata();
          echo '<div class="clear"></div>
        </div>
        <br />
        <div id="quest_listing">
          <center>';
            $ii=0;
            $args = array(
              'post_type' => 'wpquests',
              'post_status' => 'publish',
              'posts_per_page' => -1,
              'orderby' => 'date',
              'order' => 'ASC'
            );
            $quest_query = new WP_Query( $args );
            if( $quest_query->have_posts() ){
              while( $quest_query->have_posts() ){
                $quest_query->the_post();
                $quest_ID = get_the_ID();
                $quest_title = get_the_title();
                $quest_img = get_post_meta( $quest_ID, '_quest_image', true );
                $ii++;
                echo '<a href="javascript:void(0);" id="tab_'.$ii.'"'; if($ii==1){echo 'class="acti_tab"';} echo '>
                  <div>
                    <img src="'.$quest_img.'" width="50" height="50" alt="'.$quest_title.' Icon" />
                    <p class="achievement_listing_text">'.$quest_title.'</p>
                  </div>
                </a>';
              } 
            }
            wp_reset_postdata();
            echo '<div class="clear"></div>
          </center>
        </div>
      </div>
    </div>
  </div>';
  
$args = array(
  'post_type' => 'wpachievements',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'orderby' => 'date',
  'order' => 'ASC'
);
$achievement_query = new WP_Query( $args );
if( $achievement_query->have_posts() ){
  $achievementsShow = '';
} else{
  $achievementsShow = ' style="display:none;"';
}
wp_reset_postdata();
            
  echo '<div class="wpa_list_hold" id="wpa_list_quests"'.$achievementsShow.'>
  
    <h2 class="wpa_heading_title"><span id="achievement_icon"></span>'.sprintf(__('Our %sAchievements%s', WPACHIEVEMENTS_TEXT_DOMAIN), '<span>','</span>').'</h2>
    <div class="wpa_list_inner">
      <div class="wpa_list_inner_info">
        <div id="wpa_info">';
          $ii=0;
          $args = array(
            'post_type' => 'wpachievements',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC'
          );
          $achievement_query = new WP_Query( $args );
          if( $achievement_query->have_posts() ){
            while( $achievement_query->have_posts() ){
              $achievement_query->the_post();
              $ach_ID = get_the_ID();
              $ach_title = get_the_title();
              $ach_desc = get_the_content();
              $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );
              if(function_exists(WPACHIEVEMENTS_MYCRED)){
                $mycred = mycred();
                $ach_points = $mycred->format_creds( get_post_meta( $ach_ID, '_achievement_points', true ) );
              } else{
                $ach_points = $prefix.get_post_meta( $ach_ID, '_achievement_points', true ).$suffix;
              }
              $ach_occurences = get_post_meta( $ach_ID, '_achievement_occurrences', true );
              $ach_type = get_post_meta( $ach_ID, '_achievement_type', true );
              $ach_trigger_desc = get_post_meta( $ach_ID, '_achievement_trigger_desc', true );
              
              if( $ach_type == 'gform_sub' ){
                $formID = get_post_meta( $ach_ID, '_achievement_associated_id', true );
                if( $formID > 0 ){
                  global $wpdb;
                  if(function_exists('is_multisite') && is_multisite()){
                    $table = $wpdb->get_blog_prefix(1).'rg_form';
                  } else{
                    $table = $wpdb->prefix.'rg_form';
                  }
                  $form_title = $wpdb->get_var( $wpdb->prepare("SELECT title FROM $table WHERE id=%d", $formID) );
                  $ach_data = $form_title;
                } else{
                  $ach_data = '';
                }
              } else{
                $ach_data = '';
              }
            
              if( $ach_type != 'custom_achievement' ){
                $ii++;
                echo '<div class="achive_tab" id="ach_tab_'.$ii.'"'; if($ii==1){echo 'style="display:block;"';} echo '>
                  <div class="wpa_image_block">';
                    if( is_user_logged_in() ){
                      $userachievements = get_user_meta( $current_user->ID, 'achievements_gained', true );
                      if( is_array($userachievements) && in_array($ach_ID, $userachievements) ){
                        echo '<div class="ach_point unlocked">'.__('Unlocked', WPACHIEVEMENTS_TEXT_DOMAIN).'</div>';
                      } else{
                        echo '<div class="ach_point">+'.$ach_points.'</div>';
                      }
                    } else{
                      echo '<div class="ach_point">+'.$ach_points.'</div>';
                    }
                    echo '<img src="'.$ach_img.'" alt="'.$ach_title.' Icon" width="100" />
                  </div>
                  <div class="wpa_info_block">
                    <h2>'.$ach_title.'</h2>
                    <p class="achieve_desc">'.$ach_desc.'</p>
                    <h3>'.__('How do I get this?', WPACHIEVEMENTS_TEXT_DOMAIN).'</h3>';
                    if( $ach_type == 'custom_trigger' ){
                      echo '<p style="margin-top:0;">'.__('Get this achievement for', WPACHIEVEMENTS_TEXT_DOMAIN).' '. $ach_trigger_desc .'</p>';
                    } else{
                      echo '<p style="margin-top:0;">'.__('Get this achievement', WPACHIEVEMENTS_TEXT_DOMAIN).' '. achievement_Desc($ach_type,'',$ach_occurences,'',$ach_data) .'</p>';
                    }
                  echo '</div>';
                echo '</div>';
              } else{
                $ii++;
                echo '<div class="achive_tab" id="ach_tab_'.$ii.'"'; if($ii==1){echo 'style="display:block;"';} echo '>
                  <div class="wpa_image_block">';
                    if( is_user_logged_in() ){
                      $userachievements = get_user_meta( $current_user->ID, 'achievements_gained', true );
                      if( is_array($userachievements) && in_array($ach_ID, $userachievements) ){
                        echo '<div class="ach_point unlocked">'.__('Unlocked', WPACHIEVEMENTS_TEXT_DOMAIN).'</div>';
                      } else{
                        echo '<div class="ach_point">+'.$ach_points.'</div>';
                      }
                    } else{
                      echo '<div class="ach_point">+'.$ach_points.'</div>';
                    }
                    echo '<img src="'.$ach_img.'" alt="'.$ach_title.' Icon" width="100" />
                  </div>';
                  echo '<h2>'.$ach_title.'</h2>
                  <p class="achieve_desc">'.$ach_desc.'</p>';
                echo '</div>';
              }
            }
          }
          wp_reset_postdata();
          echo '<div class="clear"></div>
        </div>
        <br />
        <div id="wpa_listing">
          <center>';
            $ii=0;
            $args = array(
              'post_type' => 'wpachievements',
              'post_status' => 'publish',
              'posts_per_page' => -1,
              'orderby' => 'date',
              'order' => 'ASC'
            );
            $achievement_query = new WP_Query( $args );
            if( $achievement_query->have_posts() ){
              while( $achievement_query->have_posts() ){
                $achievement_query->the_post();
                $ach_ID = get_the_ID();
                $ach_title = get_the_title();
                $ach_img = get_post_meta( $ach_ID, '_achievement_image', true );
                $ii++;
                echo '<a href="javascript:void(0);" id="tab_'.$ii.'"'; if($ii==1){echo 'class="acti_tab"';} echo '>
                  <div>
                    <img src="'.$ach_img.'" width="50" height="50" alt="'.$ach_title.' Icon" />
                    <p class="achievement_listing_text">'.$ach_title.'</p>
                  </div>
                </a>';
              }
            }
            wp_reset_postdata();
            echo '<div class="clear"></div>
          </center>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div></center>';
if(function_exists('is_multisite') && is_multisite()){
  restore_current_blog();
}
if( !is_home() ){ get_footer(); } ?>