<?php
header('Content-type: text/javascript');
require_once('../../../../../wp-load.php');

$update_response = get_option('external_updates-wpachievements');
if( isset($update_response->update->license) ){
  if( $update_response->update->license->status == 'invalid' ){
    echo "
    jQuery(document).ready(function(){  
      jQuery('#update-plugins-table .plugins tr').each(function(){
        var plugin = jQuery(this).find('strong').text();
        if( plugin == 'WPAchievements' ){
          jQuery(this).find('input').remove();
          jQuery(this).find('strong').html('WPAchievements - <font color=\"#FF0000\">".$update_response->update->license->error."</font>')
        }    
      });
    });";
  }
}
?>