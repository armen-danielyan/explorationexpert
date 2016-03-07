var spinnerMouseDown = false,
    spinnerLoop = false,
    spinnerSpeed = 0;
function RotateSpinner(spinnerId, up){
  if( spinnerMouseDown ){
    document.getElementById(spinnerId).value = up ? parseInt(document.getElementById(spinnerId).value) + 1 : parseInt(document.getElementById(spinnerId).value) - 1;
    if( document.getElementById(spinnerId).value < 1 )
      document.getElementById(spinnerId).value = 1;
    spinnerSpeed = spinnerSpeed + 1;

    if( spinnerSpeed == 10 ){
      clearInterval(spinnerLoop);
      spinnerLoop = setInterval( function(){ RotateSpinner(spinnerId, up); }, 100);
    }
    if( spinnerSpeed == 30 ){
      clearInterval(spinnerLoop);
      spinnerLoop = setInterval( function(){ RotateSpinner(spinnerId, up); }, 50);
    }
  }
}
jQuery(document).ready(function(){

  if( jQuery('#wpachievements_ranks_data_points').length ){
    jQuery('#wpachievements_ranks_data_points').spinner({ min: 0, max: 1000000, increment: 'fast' });
  }

  if(jQuery('#wpachievements_achievements_data_event,select.trigger_select').is(":disabled") ){
    jQuery('#event_details,.event_details_holder').show();
  }
  jQuery('#wpachievements_achievements_data_event').change(function(){
    if(jQuery(this).val() != 'none'){
      jQuery('#event_details,.event_details_holder').fadeIn('slow');
    } else{
      jQuery('#event_details,.event_details_holder').hide();
    }
    if(jQuery(this).val() == 'custom_trigger'){
      jQuery('#custom_event_details').fadeIn('slow');
    } else{
      jQuery('#custom_event_details').hide();
    }
    if(jQuery(this).val() == 'gform_sub'){
      jQuery('#post_id').hide();
      jQuery('#post_id label').text('Form ID:');
      jQuery('#post_id').fadeIn('slow');
    } else if( jQuery(this).val().indexOf("ld_quiz_pass") >= 0 || jQuery(this).val().indexOf("wpcw_quiz") >= 0 ){
      jQuery('#post_id').hide();
      jQuery('#post_id label').html('Quiz ID: <small>(optional)</small>');
      jQuery('#post_id').fadeIn('slow');
    } else if(jQuery(this).val() == 'ld_lesson_complete'){
      jQuery('#post_id').hide();
      jQuery('#post_id label').html('Lesson ID: <small>(optional)</small>');
      jQuery('#post_id').fadeIn('slow');
    } else if(jQuery(this).val() == 'ld_course_complete'){
      jQuery('#post_id').hide();
      jQuery('#post_id label').html('Course ID: <small>(optional)</small>');
      jQuery('#post_id').fadeIn('slow');
    } else if(jQuery(this).val() == 'wpcw_module_complete'){
      jQuery('#post_id').hide();
      jQuery('#post_id label').html('Module ID: <small>(optional)</small>');
      jQuery('#post_id').fadeIn('slow');
    } else if(jQuery(this).val() == 'wpcw_course_complete'){
      jQuery('#post_id').hide();
      jQuery('#post_id label').html('Course ID: <small>(optional)</small>');
      jQuery('#post_id').fadeIn('slow');
    } else{
      jQuery('#post_id').hide();
    }
    if(jQuery(this).val() == 'cp_bp_group_joined'){
      jQuery('#ass_title').fadeIn('slow');
    } else{
      jQuery('#ass_title').hide();
    }
    if(jQuery(this).val() == 'wc_order_complete' || jQuery(this).val() == 'wc_user_spends'){
      jQuery('#woo_order_limit').hide();
      if( jQuery(this).val() == 'wc_user_spends' ){
        jQuery('#woo_order_limit label small').hide();
      } else{
        jQuery('#woo_order_limit label small').show();
      }
      jQuery('#woo_order_limit').fadeIn('slow');
    } else{
      jQuery('#woo_order_limit').hide();
    }
    if(jQuery(this).val() == 'ld_quiz_perfect'){
      jQuery('#first_try').hide();
      jQuery('#first_try').fadeIn('slow');
    } else{
      jQuery('#first_try').hide();
    }
    jQuery('#achievement_details .wpachievements-error').removeClass('wpachievements-error');
    jQuery('#achievement_details .wpachievements-error-border').removeClass('wpachievements-error-border');
    jQuery('#achievement_details .wpachievements-error-background').removeClass('wpachievements-error-background');
  });

  jQuery('select.trigger_select').live('change', function(){
    var parentID = jQuery(this).parent().parent().attr('id');
    if(jQuery(this).val() != 'none'){
      jQuery('#event_details,.event_details_holder').fadeIn('slow');
    } else{
      jQuery('#event_details,.event_details_holder').hide();
    }
    if(jQuery(this).val() == 'wpachievements_achievement'){
      jQuery('#'+parentID+' #custom_event_details').fadeIn('slow');
    } else{
      jQuery('#'+parentID+' #custom_event_details').hide();
    }
    if(jQuery(this).val() == 'gform_sub'){
      jQuery('#'+parentID+' #post_id').hide();
      jQuery('#'+parentID+' #post_id label').text('Form ID:');
      jQuery('#'+parentID+' #post_id').fadeIn('slow');
    } else if( jQuery(this).val().indexOf("ld_quiz_pass") >= 0 || jQuery(this).val().indexOf("wpcw_quiz") >= 0 ){
      jQuery('#'+parentID+' #post_id').hide();
      jQuery('#'+parentID+' #post_id label').html('Quiz ID: <small>(optional)</small>');
      jQuery('#'+parentID+' #post_id').fadeIn('slow');
    } else if(jQuery(this).val() == 'ld_lesson_complete'){
      jQuery('#'+parentID+' #post_id').hide();
      jQuery('#'+parentID+' #post_id label').html('Lesson ID: <small>(optional)</small>');
      jQuery('#'+parentID+' #post_id').fadeIn('slow');
    } else if(jQuery(this).val() == 'ld_course_complete'){
      jQuery('#'+parentID+' #post_id').hide();
      jQuery('#'+parentID+' #post_id label').html('Course ID: <small>(optional)</small>');
      jQuery('#'+parentID+' #post_id').fadeIn('slow');
    } else if(jQuery(this).val() == 'wpcw_module_complete'){
      jQuery('#'+parentID+' #post_id').hide();
      jQuery('#'+parentID+' #post_id label').html('Module ID: <small>(optional)</small>');
      jQuery('#'+parentID+' #post_id').fadeIn('slow');
    } else if(jQuery(this).val() == 'wpcw_course_complete'){
      jQuery('#'+parentID+' #post_id').hide();
      jQuery('#'+parentID+' #post_id label').html('Course ID: <small>(optional)</small>');
      jQuery('#'+parentID+' #post_id').fadeIn('slow');
    } else{
      jQuery('#'+parentID+' #post_id').hide();
    }
    if(jQuery(this).val() == 'cp_bp_group_joined'){
      jQuery('#'+parentID+' #ass_title').fadeIn('slow');
    } else{
      jQuery('#'+parentID+' #ass_title').hide();
    }
    if(jQuery(this).val() == 'wc_order_complete' || jQuery(this).val() == 'wc_user_spends'){
      jQuery('#'+parentID+' #woo_order_limit').hide();
      if( jQuery(this).val() == 'wc_user_spends' ){
        jQuery('#'+parentID+' #woo_order_limit label small').hide();
      } else{
        jQuery('#'+parentID+' #woo_order_limit label small').show();
      }
      jQuery('#'+parentID+' #woo_order_limit').fadeIn('slow');
    } else{
      jQuery('#'+parentID+' #woo_order_limit').hide();
    }
    if(jQuery(this).val() == 'ld_quiz_perfect'){
      jQuery('#'+parentID+' #first_try').hide();
      jQuery('#'+parentID+' #first_try').fadeIn('slow');
    } else{
      jQuery('#'+parentID+' #first_try').hide();
    }
    jQuery('#achievement_details .wpachievements-error').removeClass('wpachievements-error');
    jQuery('#achievement_details .wpachievements-error-border').removeClass('wpachievements-error-border');
    jQuery('#achievement_details .wpachievements-error-background').removeClass('wpachievements-error-background');
  });

  jQuery('.spinner-holder input[type="text"]').live('input propertychange', function(){
    this.value = this.value.replace(/[^0-9+]/g, '');
    while (this.value.substr(0,1) == '0' && this.value.length>1){ this.value = this.value.substr(1,9999); }
    if( this.value == '' || this.value < 1 ){
      if( jQuery('#wpachievements_achievements_data_event').val() != 'wc_order_complete' ){
        this.value = 1;
      } else{
        this.value = 0;
      }
    }
  });
  jQuery('.wpump_spinner_increase').live('click',function(){
    if( !jQuery(this).parent().parent().hasClass('disabled') ){
      spinnerMouseDown = true; RotateSpinner( jQuery(this).parent().parent().prev('input[type="text"]').attr('id'), true );
    }
  }).live('mousedown',function(){
    if( !jQuery(this).parent().parent().hasClass('disabled') ){
      var thisid = jQuery(this).parent().parent().prev('input[type="text"]').attr('id');
      spinnerMouseDown = true; spinnerLoop = setInterval( function(){ RotateSpinner( thisid, true ); }, 150);
    }
  }).live('mouseup',function(){
    spinnerMouseDown = false; clearInterval(spinnerLoop);
  }).live('mouseleave',function(){
    spinnerMouseDown = false; clearInterval(spinnerLoop);
  });
  jQuery('.wpump_spinner_decrease').live('click',function(){
    if( !jQuery(this).parent().parent().hasClass('disabled') ){
      spinnerMouseDown = true; RotateSpinner( jQuery(this).parent().parent().prev('input[type="text"]').attr('id'), false );
    }
  }).live('mousedown',function(){
    if( !jQuery(this).parent().parent().hasClass('disabled') ){
      var thisid = jQuery(this).parent().parent().prev('input[type="text"]').attr('id');
      spinnerMouseDown = true; spinnerLoop = setInterval( function(){ RotateSpinner( thisid, false ) }, 150);
    }
  }).live('mouseup',function(){
    spinnerMouseDown = false; clearInterval(spinnerLoop);spinnerSpeed = 0;
  }).live('mouseleave',function(){
    spinnerMouseDown = false; clearInterval(spinnerLoop);spinnerSpeed = 0;
  });


  jQuery('#achievement_image_remove').live('click',function(event){
    event.preventDefault();
    jQuery('#achievement_image #image_preview_holder').empty();
    jQuery('#achievement_image #no-image-links').show();
    jQuery('#upload_image').val('');
  });

  jQuery('#achievement_image_pick').live('click',function(event){
    event.preventDefault();
    jQuery('#image_preview_holder').hide();
    jQuery('#default-image-selection').show();
  });
  jQuery('.radio_btn').live('click',function(event){
    jQuery('#selected_btn').attr('id','');
    jQuery(this).attr('id','selected_btn');
    jQuery('input[name=achievement_badge]:checked').attr('checked',false);
    jQuery(this).parent().find('input[name=achievement_badge]').attr('checked',true);
    jQuery('#image_preview_holder #image_preview_inner').empty();
    jQuery('#upload_image').val( jQuery(this).attr('src') );
  });


  var custom_uploader;
  jQuery('#upload_image_button').live('click',function(event){
    event.preventDefault();
    jQuery('#default-image-selection').hide();
    jQuery('#default-image-selection input[name=achievement_badge]:checked').attr('checked', false);
    jQuery('#default-image-selection input[type="radio"]:checked').prop('checked', false);
    jQuery('#selected_btn').attr('id','');
    if(custom_uploader){
      custom_uploader.open();
      return;
    }
    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
        text: 'Choose Image'
      },
      multiple: false
    });
    custom_uploader.on('select', function(){
      attachment = custom_uploader.state().get('selection').first().toJSON();
      jQuery('#upload_image').val(attachment.url);

      jQuery('#image_preview_holder').empty();
      jQuery('#image_preview_holder').append('<img src="'+attachment.url+'" alt="Uploaded Achievement Image" /><br/><a href="#" id="achievement_image_remove">Remove</a>');

      jQuery('#achievement_image #no-image-links').hide();
      jQuery('#achievement_image #image_preview_holder').fadeIn();

    });
    custom_uploader.open();
  });
  jQuery('#titlewrap #title').bind('keyup',function(){
    jQuery(this).removeClass('wpachievements-error-border');
  });
  jQuery('.post-type-wpachievements input[type="submit"]').live('click',function(event){
    event.preventDefault();
    var error = '';
    var thisevent = jQuery('#wpachievements_achievements_data_event').val();

    if( jQuery('#titlewrap #title').val() == '' ){
      jQuery('#titlewrap #title').addClass('wpachievements-error-border');
      error = 'error';
    }
    if( thisevent == '' ){
      jQuery('#wpachievements_achievements_data_event').addClass('wpachievements-error-border');
      jQuery('label[for="wpachievements_achievements_data_event"').addClass('wpachievements-error');
      error = 'error';
    }
    if( thisevent == 'gform_sub' && jQuery('#wpachievements_achievements_data_post_id').val() <= 0 ){
      jQuery('#wpachievements_achievements_data_post_id').addClass('wpachievements-error-border');
      jQuery('label[for="wpachievements_achievements_data_post_id"').addClass('wpachievements-error');
      error = 'error';
    }
    if( thisevent == 'wc_user_spends' && jQuery('#wpachievements_achievement_woo_order_limit').val() <= 0 ){
      jQuery('#wpachievements_achievement_woo_order_limit').addClass('wpachievements-error-border');
      jQuery('#wpachievements_achievement_woo_order_limit').next().find('input[type="button"]').addClass('wpachievements-error-border');
      jQuery('label[for="wpachievements_achievement_woo_order_limit"').addClass('wpachievements-error');
      error = 'error';
    }
    if( jQuery('#upload_image').val() == '' ){
      jQuery('#achievement_image').addClass('wpachievements-error-border');
      jQuery('#achievement_image .hndle').addClass('wpachievements-error-background');
      error = 'error';
    }
    if( thisevent == 'custom_trigger' && jQuery('#wpachievements_achievements_custom_trigger_id').val() == '' ){
      jQuery('#wpachievements_achievements_custom_trigger_id').addClass('wpachievements-error-border');
      jQuery('label[for="wpachievements_achievements_custom_trigger_id"').addClass('wpachievements-error');
      error = 'error';
    }
    if( thisevent == 'custom_trigger' && jQuery('#wpachievements_achievements_custom_trigger_desc').val() == '' ){
      jQuery('#wpachievements_achievements_custom_trigger_desc').addClass('wpachievements-error-border');
      jQuery('label[for="wpachievements_achievements_custom_trigger_desc"').addClass('wpachievements-error');
      error = 'error';
    }

    if( error == '' ){
      jQuery('#wpachievements_achievements_data_event:disabled').prop('disabled',false).fadeTo(0, 0.5);
      jQuery('.trigger_select:disabled').prop('disabled',false).fadeTo(0, 0.5);
      jQuery('form#post').submit();
    }

  });


  jQuery('.post-type-wpquests input[type="submit"]').live('click',function(event){
    event.preventDefault();
    var error = '';

    if( jQuery('#titlewrap #title').val() == '' ){
      jQuery('#titlewrap #title').addClass('wpachievements-error-border');
      error = 'error';
    }
    if( jQuery('#upload_image').val() == '' ){
      jQuery('#achievement_image').addClass('wpachievements-error-border');
      jQuery('#achievement_image .hndle').addClass('wpachievements-error-background');
      error = 'error';
    }
    var count = jQuery('#quest_item_counter').val();
    for(var i = 1, limit = count; i <= limit; i++){

      if( jQuery('#wpachievements_achievements_data_event_'+i).val() == '' ){
        jQuery('#wpachievements_achievements_data_event_'+i).addClass('wpachievements-error-border');
        jQuery('label[for="wpachievements_achievements_data_event_'+i+'"').addClass('wpachievements-error');
        error = 'error';
      }
      if( jQuery('#wpachievements_achievements_data_event_'+i).val() == 'gform_sub' && jQuery('#wpachievements_achievements_data_post_id_'+i).val() <= 0 ){
        jQuery('#wpachievements_achievements_data_post_id_'+i).addClass('wpachievements-error-border');
        jQuery('label[for="wpachievements_achievements_data_post_id_'+i+'"').addClass('wpachievements-error');
        error = 'error';
      }
      if( jQuery('#wpachievements_achievements_data_event_'+i).val() == 'wc_user_spends' && jQuery('#wpachievements_achievement_woo_order_limit_'+i).val() <= 0 ){
        jQuery('#wpachievements_achievement_woo_order_limit_'+i).addClass('wpachievements-error-border');
        jQuery('#wpachievements_achievement_woo_order_limit_'+i).next().find('input[type="button"]').addClass('wpachievements-error-border');
        jQuery('label[for="wpachievements_achievement_woo_order_limit_'+i+'"').addClass('wpachievements-error');
        error = 'error';
      }
      if( jQuery('#wpachievements_achievements_data_event_'+i).val() == 'custom_trigger' && jQuery('#wpachievements_achievements_custom_trigger_id_'+i).val() == '' ){
        jQuery('#wpachievements_achievements_custom_trigger_id_'+i).addClass('wpachievements-error-border');
        jQuery('label[for="wpachievements_achievements_custom_trigger_id_'+i+'"').addClass('wpachievements-error');
        error = 'error';
      }
      if( jQuery('#wpachievements_achievements_data_event_'+i).val() == 'wpachievements_achievement' && jQuery('#wpachievements_achievements_data_ach_id_'+i).val() == '' ){
        jQuery('#wpachievements_achievements_data_ach_id_'+i).addClass('wpachievements-error-border');
        jQuery('label[for="wpachievements_achievements_data_ach_id_'+i+'"').addClass('wpachievements-error');
        error = 'error';
      }

    }

    if( error == '' ){
      jQuery('#wpachievements_achievements_data_event:disabled').prop('disabled',false).fadeTo(0, 0.5);
      jQuery('.trigger_select:disabled').prop('disabled',false).fadeTo(0, 0.5);
      var count = jQuery('#quest_item_counter').val();
      for(var i = 1, limit = count; i <= limit; i++){
        jQuery('#wpachievements_achievements_data_ach_id_'+i+':disabled').prop('disabled',false).fadeTo(0, 0.5);
        jQuery('#wpachievements_achievements_custom_trigger_id_'+i+':disabled').prop('disabled',false).fadeTo(0, 0.5);
      }
      jQuery('form#post').submit();
    }

  });

  jQuery('#add_rank_form #rank_save').click(function(event){
    event.preventDefault();
    var wpachievements_ranks_data_rank = jQuery('#wpachievements_ranks_data_rank').val();
    var wpachievements_ranks_data_points = jQuery('#wpachievements_ranks_data_points').val();
    var wpachievements_ranks_data_image = jQuery('#upload_image').val();
    jQuery.post(ajaxurl, { 'action': 'wpachievements_update_rank_ajax', 'wpachievements_ranks_data_rank': wpachievements_ranks_data_rank, 'wpachievements_ranks_data_points': wpachievements_ranks_data_points, 'wpachievements_ranks_data_image': wpachievements_ranks_data_image },function(data){
      var data = data.replace(/<\/div>\d+/g, '');
      jQuery('#error_holder').empty().append(data);
      if(jQuery('#error_holder .error').length == 0){location.reload();}
    });
  });
  jQuery('.wpachievements_rank_remove').click(function(){
var thisrank = jQuery(this).attr('id').substring(20);
    var wpachievements_rank_remove = thisrank;
    jQuery.post(ajaxurl, { 'action': 'wpachievements_remove_rank_ajax', 'wpachievements_rank_remove': wpachievements_rank_remove },function(data){
      var data = data.replace(/<\/div>\d+/g, '');
      jQuery('#error_holder').empty().append(data);
      if(jQuery('#error_holder .error').length == 0){jQuery('tr#rank_'+thisrank).remove();}
    });
  });
  jQuery('.rank_edit_link').click(function(){
    var editthis = jQuery(this).attr('id').substring(33);
    jQuery('#wpachievements_ranks_action_edit_'+editthis).hide();
    jQuery('#ranks_action_remove_'+editthis).hide();
    jQuery('#wpachievements_ranks_action_save_'+editthis).show();
    jQuery('#rank_cancel_link_'+editthis).show();
    jQuery('#rank_edit_'+editthis).html("<input type='text' class='inputbox' id='rank_input_"+editthis+"' value=\""+jQuery('#rank_edit_'+editthis).text()+"\">");
    if( jQuery('#image_edit_'+editthis+' img').length > 0 ){
      jQuery('#image_edit_'+editthis).html("<input type='text' class='inputbox' id='image_input_"+editthis+"' value=\""+jQuery('#image_edit_'+editthis+' img').attr('src')+"\">");
    } else{
      jQuery('#image_edit_'+editthis).html("<input type='text' class='inputbox' id='image_input_"+editthis+"' value=\"\">");
    }
    if(editthis!=0){jQuery('#points_edit_'+editthis).html("<input type='text' class='inputbox' id='points_input_"+editthis+"' value=\""+jQuery('#points_edit_'+editthis).text()+"\">");}
  });
  jQuery('.rank_save_link').click(function(){
    var editthis = jQuery(this).attr('id').substring(33);
    var thisrank = jQuery('#rank_input_'+editthis).val();
    var thispoint = jQuery('#points_input_'+editthis).val();
    if(thisrank==''){alert('The Rank name cannot be empty!!');}
    else if(thispoint==''){alert('The Rank points cannot be empty!!');}
    else{
      if(editthis==0){thispoint=0;}
    jQuery('#wpachievements_ranks_action_edit_'+editthis).show();
      jQuery('#wpachievements_ranks_action_save_'+editthis).hide();
      jQuery('#ranks_action_remove_'+editthis).show();
      jQuery('#rank_cancel_link_'+editthis).hide();
      jQuery('#rank_edit_'+editthis).html(thisrank);
      jQuery('#points_edit_'+editthis).html(thispoint);
      var wpachievements_ranks_data_rank = thisrank;
      var wpachievements_ranks_data_points = thispoint;
      var wpachievements_ranks_data_image = jQuery('#image_input_'+editthis).val();
      jQuery.post(ajaxurl, { 'action': 'wpachievements_update_rank_ajax', 'wpachievements_ranks_data_rank': wpachievements_ranks_data_rank, 'wpachievements_ranks_data_points': wpachievements_ranks_data_points, 'wpachievements_ranks_data_image': wpachievements_ranks_data_image, 'editthis': editthis }, function(data){
        var data = data.replace(/<\/div>\d+/g, '');
        jQuery('#error_holder').empty().append(data);
        location.reload();
      });
    }
  });
  jQuery('.rank_cancel_link,.achievement_cancel_link').click(function(){location.reload();});

  jQuery('#quest_add').click(function(event){
    event.preventDefault();
    jQuery('.small_loader_icon').css('display','inline-block');
    var count = jQuery('#quest_item_counter').val();

    jQuery.post(ajaxurl, { 'action': 'wpachievements_quest_html', 'quest_count': count }, function(data){
      jQuery('.event_details_holder').before(data);
      jQuery('.small_loader_icon').hide();
      jQuery('#quest_item_counter').val( count + 1 );
      var triggerCount = jQuery('.button_quest_remove').length;
      if( triggerCount > 2 ){
        jQuery('.button_quest_remove').removeClass('disabled');
      } else{
        jQuery('.button_quest_remove').addClass('disabled');
      }
    });

  });

  jQuery('a.button_quest_remove').live('click',function(event){
    event.preventDefault();
    if( !jQuery(this).hasClass('disabled') ){
      var count = jQuery('#quest_item_counter').val();
      var thisID = jQuery(this).parent().attr('id');
      thisID = parseInt(thisID.replace('quest_item_',''));

      jQuery('#quest_item_'+thisID).fadeOut('fast',function(){
        jQuery('#quest_item_'+thisID).remove();
        jQuery('.button_quest_remove').each(function() {
          var oldID = jQuery(this).parent().attr('id');
          oldID = parseInt(oldID.replace('quest_item_',''));
          if( oldID > thisID ){
            var NewID = oldID - 1;
            jQuery(this).parent().attr('id','quest_item_'+NewID);
          }
        });
        jQuery('#quest_item_counter').val( count - 1 );
        var triggerCount = jQuery('.button_quest_remove').length;
        if( triggerCount <= 2 ){
          jQuery('.button_quest_remove').addClass('disabled');
        } else{
          jQuery('.button_quest_remove').removeClass('disabled');
        }
      });
    }

    return false;
  });

});