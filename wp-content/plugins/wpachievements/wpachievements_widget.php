<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

 /**
   *****************************************
   *    R E G I S T E R   W I D G E T S    *
   *****************************************
   */
   if ( !function_exists('WPAchievements_register_widgets') ) {
    function WPAchievements_register_widgets() {
     register_widget('WP_Widget_WPAchievements_Widget');
     register_widget('WP_Widget_WPAchievements_Achievements_Widget');
     register_widget('WP_Widget_WPAchievements_Quests_Widget');
     register_widget('WP_Widget_WPAchievements_Ranks_Widget');
    } add_action('widgets_init', 'WPAchievements_register_widgets');
   }
 /**
   *********************************************************
   *    A C H I E V E M E N T S   L E A D E R B O A R D    *
   *********************************************************
   */
   if ( !class_exists('WP_Widget_WPAchievements_Widget') ) {
     class WP_Widget_WPAchievements_Widget extends WP_Widget {
       function __construct() {
         $widget_ops   = array('description' => 'Shows a leaderboard of achievements gained by users.');
         parent::__construct('WPAchievements_Widget', 'WPAchievements Leaderboard', $widget_ops);
       }
       function widget($args, $instance) {
         extract($args);
         $title = apply_filters('widget_title', esc_attr($instance['title']));
         $type = esc_attr($instance['type']);
         $limit = intval($instance['limit']);
         global $wpdb;
         if(function_exists('is_multisite') && is_multisite()){
           switch_to_blog(1);
         }
         $table = $wpdb->prefix.'usermeta';
         if(function_exists('is_multisite') && is_multisite()){
           restore_current_blog();
         }
         if(function_exists('is_multisite') && is_multisite()){
           $hide_admin = get_blog_option(1,'wpachievements_hide_admin');
         } else{
           $hide_admin = get_option('wpachievements_hide_admin');
         }
         if( $hide_admin == 'true' ){
           $user_query = new WP_User_Query( array( 'role' => 'Administrator' ) );
           $users = $user_query->get_results();
           $admins = array();
           foreach( $users as $user ){
             $admins[] = $user->ID;
           }
         } else{
           $admins = array();
           $admins[] = 0;
         }
         if( strtolower($type) == 'points' ){
           if(function_exists(WPACHIEVEMENTS_CUBEPOINTS)){
             $user_achievements = $wpdb->get_results( $wpdb->prepare("SELECT user_id,meta_value FROM $table WHERE meta_key='cpoints' AND user_id NOT IN (".implode(',',$admins).") ORDER BY meta_value * 1 DESC LIMIT %d", $limit) );
           } elseif( function_exists(WPACHIEVEMENTS_MYCRED) ){
             $user_achievements = $wpdb->get_results( $wpdb->prepare("SELECT user_id,meta_value FROM $table WHERE meta_key='mycred_default' AND user_id NOT IN (".implode(',',$admins).") ORDER BY meta_value * 1 DESC LIMIT %d", $limit) );
           } else{
             $user_achievements = $wpdb->get_results( $wpdb->prepare("SELECT user_id,meta_value FROM $table WHERE meta_key='achievements_points' AND user_id NOT IN (".implode(',',$admins).") ORDER BY meta_value * 1 DESC LIMIT %d", $limit) );
           }
         } else{
           $user_achievements = $wpdb->get_results( $wpdb->prepare("SELECT user_id,meta_value FROM $table WHERE meta_key='achievements_count' AND user_id NOT IN (".implode(',',$admins).") ORDER BY meta_value * 1 DESC LIMIT %d", $limit) );
         }
         $trophies = array('','gold','silver','bronze');
         $ii=0;
         if ( !empty($user_achievements) && $user_achievements!='') {
           echo $before_widget;
           if($title) {
             echo $before_title . $title . $after_title;
           }
           foreach( $user_achievements as $user_info ):
             if($user_info->meta_value > 0){
               $user_inf = get_userdata($user_info->user_id);
               $ii++;
               if($ii<4){$trophy = $trophies[$ii];} else{$trophy = 'default';}
               echo '<center>';
               global $bp;
               echo '<div class="myus_user wpach_leaderboard">'. get_avatar($user_info->user_id, $size = '50') .'<div class="myus_title">';
               if( isset($user_inf->nickname) ){
                 $showName = $user_inf->nickname;
               } elseif( isset($user_inf->display_name) ){
                 $showName = $user_inf->display_name;
               } else{
                 $showName = $user_inf->user_login;
               }
               if(defined( WPACHIEVEMENTS_BUDDYPRESS )){
                 global $bp;
                 if(bp_is_active('activity')){
                   echo '<a href="'.bp_core_get_user_domain( $user_info->user_id ).'" title="View '.$showName.' Profile">'.$showName.'</a>';
                 } else{echo $showName;}
               } else{echo $showName;}
               if( strtolower($type) == 'points' ){
                 echo '</div><div class="myus_count">'.__('Total Points', WPACHIEVEMENTS_TEXT_DOMAIN).': '.$user_info->meta_value.'</div>';
               } else{
                 echo '</div><div class="myus_count">'.__('Achievements', WPACHIEVEMENTS_TEXT_DOMAIN).': '.$user_info->meta_value.'</div>';
               }
               echo '<div class="myus_icon trophy_'.$trophy.'">';
               if($ii>3){echo '<div class="myus_num">'.$ii.'<span>th</span></div>';}
               echo '</div><div class="user_finish"></div></div></center>';
             }
           endforeach;
         }
         echo $after_widget;
       }
       function update($new_instance, $old_instance) {
         $instance = $old_instance;
         $instance['title'] = strip_tags($new_instance['title']);
         $instance['limit'] = intval($new_instance['limit']);
         $instance['type'] = strip_tags($new_instance['type']);
         return $instance;
       }
       function form($instance) {
         global $wpdb;
         $instance = wp_parse_args((array) $instance, array('title' => 'Achievements Leaderboard', 'type' => 'achievements', 'limit' => 5));
         $title = esc_attr($instance['title']);
         $limit = intval($instance['limit']);
         $type = esc_attr($instance['type']);
         echo '<p>
           <label for="'.$this->get_field_id('title').'">
             Title:
             <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('type').'">
             Type:
             <select class="widefat" id="'.$this->get_field_id('type').'" name="'.$this->get_field_name('type').'" type="text" value="'.$type.'">';
             if( strtolower($type) == 'points' ){
               echo '<option value="achievements">Achievements</option>
               <option value="points" selected>Points</option>';
             } else{
               echo '<option value="achievements" selected>Achievements</option>
               <option value="points">Points</option>';
             }
             echo '
             </select>
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('limit').'">
             Limit:
             <input class="widefat" id="'.$this->get_field_id('limit').'" name="'.$this->get_field_name('limit').'" type="text" value="'.$limit.'" />
           </label>
         </p>';
       }
     }
   }

 /**
   ***********************************************
   *    A C H I E V E M E N T S   W I D G E T    *
   ***********************************************
   */
   if ( !class_exists('WP_Widget_WPAchievements_Achievements_Widget') ) {
     class WP_Widget_WPAchievements_Achievements_Widget extends WP_Widget {
       function __construct() {
         $widget_ops   = array('description' => 'Shows a list of achievements gained by the user.');
         parent::__construct('WPAchievements_Achievements_Widget', 'WPAchievements My Achievements', $widget_ops);
       }
       function widget($args, $instance) {
         extract($args);
         $title = apply_filters('widget_title', esc_attr($instance['title']));
         $limit = intval($instance['limit']);
         $image_holder_class = esc_attr($instance['image_holder_class']);
         $image_class = esc_attr($instance['image_class']);
         $image_width = intval($instance['image_width']);
         echo $before_widget;
         if($title) {
           echo $before_title . $title . $after_title;
         }
         echo do_shortcode('[wpa_myachievements image_holder_class="'.$image_holder_class.'" image_class="'.$image_class.'" image_width="'.$image_width.'" achievement_limit="'.$limit.'"]');
         echo $after_widget;
       }
       function update($new_instance, $old_instance) {
         $instance = $old_instance;
         $instance['title'] = strip_tags($new_instance['title']);
         $instance['limit'] = intval($new_instance['limit']);
         $instance['image_holder_class'] = esc_attr($new_instance['image_holder_class']);
         $instance['image_class'] = esc_attr($new_instance['image_class']);
         $instance['image_width'] = intval($new_instance['image_width']);
         return $instance;
       }
       function form($instance) {
         global $wpdb;
         $instance = wp_parse_args((array) $instance, array('title' => 'My Achievements', 'limit' => 12, 'title_class' => '', 'image_holder_class' => '', 'image_class' => '', 'image_width' => '30'));
         $title = esc_attr($instance['title']);
         $limit = intval($instance['limit']);
         $image_holder_class = esc_attr($instance['image_holder_class']);
         $image_class = esc_attr($instance['image_class']);
         $image_width = intval($instance['image_width']);
         echo '<p>
           <label for="'.$this->get_field_id('title').'">
             Title:
             <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('limit').'">
             Limit: (Default: 12)
             <input class="widefat" id="'.$this->get_field_id('limit').'" name="'.$this->get_field_name('limit').'" type="text" value="'.$limit.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('image_holder_class').'">
             Image Holder Class: (Optional)
             <input class="widefat" id="'.$this->get_field_id('image_holder_class').'" name="'.$this->get_field_name('image_holder_class').'" type="text" value="'.$image_holder_class.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('image_class').'">
             Image Class: (Optional)
             <input class="widefat" id="'.$this->get_field_id('image_class').'" name="'.$this->get_field_name('image_class').'" type="text" value="'.$image_class.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('image_width').'">
             Image Width: (Default: 30px)
             <input class="widefat" id="'.$this->get_field_id('image_width').'" name="'.$this->get_field_name('image_width').'" type="text" value="'.$image_width.'" />
           </label>
         </p>';
       }
     }
   }

  /**
   ***********************************
   *    Q U E S T S   W I D G E T    *
   ***********************************
   */
   if ( !class_exists('WP_Widget_WPAchievements_Quests_Widget') ) {
     class WP_Widget_WPAchievements_Quests_Widget extends WP_Widget {
       function __construct() {
         $widget_ops   = array('description' => 'Shows a list of quests gained by the user.');
         parent::__construct('WPAchievements_Quests_Widget', 'WPAchievements My Quests', $widget_ops);
       }
       function widget($args, $instance) {
         extract($args);
         $title = apply_filters('widget_title', esc_attr($instance['title']));
         $limit = intval($instance['limit']);
         $image_holder_class = esc_attr($instance['image_holder_class']);
         $image_class = esc_attr($instance['image_class']);
         $image_width = intval($instance['image_width']);
         echo $before_widget;
         if($title) {
           echo $before_title . $title . $after_title;
         }
         echo do_shortcode('[wpa_myquests image_holder_class="'.$image_holder_class.'" image_class="'.$image_class.'" image_width="'.$image_width.'" achievement_limit="'.$limit.'"]');
         echo $after_widget;
       }
       function update($new_instance, $old_instance) {
         $instance = $old_instance;
         $instance['title'] = strip_tags($new_instance['title']);
         $instance['limit'] = intval($new_instance['limit']);
         $instance['image_holder_class'] = esc_attr($new_instance['image_holder_class']);
         $instance['image_class'] = esc_attr($new_instance['image_class']);
         $instance['image_width'] = intval($new_instance['image_width']);
         return $instance;
       }
       function form($instance) {
         global $wpdb;
         $instance = wp_parse_args((array) $instance, array('title' => 'My Quests', 'limit' => 12, 'title_class' => '', 'image_holder_class' => '', 'image_class' => '', 'image_width' => '30'));
         $title = esc_attr($instance['title']);
         $limit = intval($instance['limit']);
         $image_holder_class = esc_attr($instance['image_holder_class']);
         $image_class = esc_attr($instance['image_class']);
         $image_width = intval($instance['image_width']);
         echo '<p>
           <label for="'.$this->get_field_id('title').'">
             Title:
             <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('limit').'">
             Limit: (Default: 12)
             <input class="widefat" id="'.$this->get_field_id('limit').'" name="'.$this->get_field_name('limit').'" type="text" value="'.$limit.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('image_holder_class').'">
             Image Holder Class: (Optional)
             <input class="widefat" id="'.$this->get_field_id('image_holder_class').'" name="'.$this->get_field_name('image_holder_class').'" type="text" value="'.$image_holder_class.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('image_class').'">
             Image Class: (Optional)
             <input class="widefat" id="'.$this->get_field_id('image_class').'" name="'.$this->get_field_name('image_class').'" type="text" value="'.$image_class.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('image_width').'">
             Image Width: (Default: 30px)
             <input class="widefat" id="'.$this->get_field_id('image_width').'" name="'.$this->get_field_name('image_width').'" type="text" value="'.$image_width.'" />
           </label>
         </p>';
       }
     }
   }

  /**
   *********************************
   *    R A N K S   W I D G E T    *
   *********************************
   */
   if ( !class_exists('WP_Widget_WPAchievements_Ranks_Widget') ) {
     class WP_Widget_WPAchievements_Ranks_Widget extends WP_Widget {
       function __construct() {
         $widget_ops   = array('description' => 'Shows the current rank of the user.');
         parent::__construct('WPAchievements_Ranks_Widget', 'WPAchievements My Rank', $widget_ops);
       }
       function widget($args, $instance) {
         extract($args);
         $title = apply_filters('widget_title', esc_attr($instance['title']));
         $show_img = esc_attr($instance['show_img']);
         echo $before_widget;
         if($title) {
           echo $before_title . $title . $after_title;
         }
         echo do_shortcode('[wpa_myranks rank_image="'.$show_img.'"]');
         echo '<div class="clear"></div>';
         echo $after_widget;
       }
       function update($new_instance, $old_instance) {
         $instance = $old_instance;
         $instance['title'] = strip_tags($new_instance['title']);
         $instance['show_img'] = strip_tags($new_instance['show_img']);
         return $instance;
       }
       function form($instance) {
         global $wpdb;
         $instance = wp_parse_args((array) $instance, array('title' => 'My Rank', 'show_img' => 'hide'));
         $title = esc_attr($instance['title']);
         $show_img = esc_attr($instance['show_img']);

         echo '<p>
           <label for="'.$this->get_field_id('title').'">
             Title:
             <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />
           </label>
         </p>
         <p>
           <label for="'.$this->get_field_id('show_img').'">
             Show Rank Image:
             <select id="'.$this->get_field_id('show_img').'" name="'.$this->get_field_name('show_img').'" class="widefat" style="background:#fff;">';
             if( $show_img == 'show' ){
                echo '<option value="show" selected>Show</option><option value="hide">Hide</option>';
             } else{
               echo'<option value="hide" selected>Hide</option><option value="show">Show</option>';
             }
             echo'
             </select>
           </label>
         </p>';
       }
     }
   }
?>