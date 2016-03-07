<?php

/*-----------------------------------------------------------------------------------*/
/* Add default options after activation */
/*-----------------------------------------------------------------------------------*/
if (is_admin()) {
  //Call action that sets
  add_action('admin_head','wpach_tt_option_setup');
}

function wpach_tt_option_setup(){


}





/*-----------------------------------------------------------------------------------*/
/* Admin Backend */
/*-----------------------------------------------------------------------------------*/
function wpach_siteoptions_admin_head() { ?>

<script type="text/javascript">
jQuery(function(){
var message = '<p><strong>Activation Successful!</strong> This theme\'s settings are located under <a href="<?php echo admin_url('admin.php?page=wpachievements_admin.php'); ?>">Appearance > Site Options</a>.</p>';
jQuery('.themes-php #message2').html(message);
});
</script>
    
    
    <?php }

add_action('admin_head', 'wpach_siteoptions_admin_head');
?>