jQuery(document).ready(function(){
  jQuery('#wpa_listing a').click(function(event){
    event.preventDefault();
    var tab = jQuery(this).attr('id');
    jQuery('#wpa_listing .acti_tab').removeClass('acti_tab');
    jQuery(this).addClass('acti_tab');
    jQuery('#wpa_info .achive_tab').hide();
    jQuery('#wpa_info #ach_'+tab).show();
  });
  jQuery('#quest_listing a').click(function(event){
    event.preventDefault();
    var tab = jQuery(this).attr('id');
    jQuery('#quest_listing .acti_tab').removeClass('acti_tab');
    jQuery(this).addClass('acti_tab');
    jQuery('#quest_info .achive_tab').hide();
    jQuery('#quest_info #quest_'+tab).show();
  });
});