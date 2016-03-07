<?php
/**
 *********************************************************
 *    S O C I A L   M E D I A   I N T E G R A T I O N    *
 *********************************************************
 */
  $plugindir = dirname( __FILE__ );
  if(function_exists('is_multisite') && is_multisite()){
   $fbenab = get_blog_option(1,'wpachievements_pshare');
  } else{
   $fbenab = get_option('wpachievements_pshare');
  }
  if( $fbenab == 'true' ){    
    function wpachievements_fb_share_achievement(){
      if(function_exists('is_multisite') && is_multisite()){
        $appId = get_blog_option(1,'wpachievements_appID');
      } else{
        $appId = get_option('wpachievements_appID');
      }
      if( !empty($appId) ){
        ?>
        <div id="fb-root"></div>
        <script>
        function wpa_fb_sharing( title, image, text ){
          var oldCB = window.fbAsyncInit;
          if(typeof oldCB === 'function'){
            FB.getLoginStatus(function(response) {
              if (response.status === 'connected') {
                FB.ui({
                  method: 'feed',
                  display: 'iframe',
                  name: title,
                  link: '<?php echo get_bloginfo('url'); ?>',
                  picture: image,
                  caption: text,
                  description: '<?php echo sprintf( __('Come to %s and gain achievements of your own!!!', WPACHIEVEMENTS_TEXT_DOMAIN), get_bloginfo('name') ); ?>'
                });
              } else {
                FB.ui({
                  method: 'feed',
                  name: 'title',
                  link: '<?php echo get_bloginfo('url'); ?>',
                  picture: image,
                  caption: text,
                  description: '<?php echo sprintf( __('Come to %s and gain achievements of your own!!!', WPACHIEVEMENTS_TEXT_DOMAIN), get_bloginfo('name') ); ?>'
                });
              }
            });
          } else{
            window.fbAsyncInit = function(){
              FB.init({
                appId   : '<?php echo $appId; ?>',
                status  : true,
                xfbml   : true
              });
              FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                  FB.ui({
                    method: 'feed',
                    display: 'iframe',
                    name: 'title',
                    link: '<?php echo get_bloginfo('url'); ?>',
                    picture: image,
                    caption: text,
                    description: '<?php echo sprintf( __('Come to %s and gain achievements of your own!!!', WPACHIEVEMENTS_TEXT_DOMAIN), get_bloginfo('name') ); ?>'
                  });
                } else {
                  FB.ui({
                    method: 'feed',
                    name: title,
                    link: '<?php echo get_bloginfo('url'); ?>',
                    picture: image,
                    caption: text,
                    description: '<?php echo sprintf( __('Come to %s and gain achievements of your own!!!', WPACHIEVEMENTS_TEXT_DOMAIN), get_bloginfo('name') ); ?>'
                  });
                }
               });
            };
            (function(d, s, id){
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) {return;}
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/all.js";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
          }
        }
        </script>
        <?php
      }
    }
    add_action('wpachievements_before_show_achievement', 'wpachievements_fb_share_achievement');
   
    function wpachievements_fb_share_achievement_filter($type){
      if(function_exists('is_multisite') && is_multisite()){
        $appId = get_blog_option(1,'wpachievements_appID');
      } else{
        $appId = get_option('wpachievements_appID');
      }
      if( !empty($appId) ){
        
        $html ='<div id="fb-root"></div>
        <script>
        function wpa_fb_sharing( title, image, text ){
          var oldCB = window.fbAsyncInit;
          if(typeof oldCB === "function"){
            FB.getLoginStatus(function(response) {
              if (response.status === "connected") {
                FB.ui({
                  method: "feed",
                  display: "iframe",
                  name: title,
                  link: "'.get_bloginfo('url').'",
                  picture: image,
                  caption: text,
                  description: "'.sprintf( __('Come to %s and gain %s of your own!!!', WPACHIEVEMENTS_TEXT_DOMAIN), get_bloginfo('name'), $type ).'"
                });
              } else {
                FB.ui({
                  method: "feed",
                  name: "title",
                  link: "'.get_bloginfo('url').'",
                  picture: image,
                  caption: text,
                  description: "'.sprintf( __('Come to %s and gain %s of your own!!!', WPACHIEVEMENTS_TEXT_DOMAIN), get_bloginfo('name'), $type ).'"
                });
              }
            });
          } else{
            window.fbAsyncInit = function(){
              FB.init({
                appId   : "'.$appId.'",
                status  : true,
                xfbml   : true
              });
              FB.getLoginStatus(function(response) {
                if (response.status === "connected") {
                  FB.ui({
                    method: "feed",
                    display: "iframe",
                    name: "title",
                    link: "'.get_bloginfo('url').'",
                    picture: image,
                    caption: text,
                    description: "'.sprintf( __('Come to %s and gain %s of your own!!!', WPACHIEVEMENTS_TEXT_DOMAIN), get_bloginfo('name'), $type ).'"
                  });
                } else {
                  FB.ui({
                    method: "feed",
                    name: title,
                    link: "'.get_bloginfo('url').'",
                    picture: image,
                    caption: text,
                    description: "'.sprintf( __('Come to %s and gain %s of your own!!!', WPACHIEVEMENTS_TEXT_DOMAIN), get_bloginfo('name'), $type ).'"
                  });
                }
               });
            };
            (function(d, s, id){
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) {return;}
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/all.js";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, "script", "facebook-jssdk"));
          }
        }
        </script>';
        
        return $html;
      }
    }
  }
?>