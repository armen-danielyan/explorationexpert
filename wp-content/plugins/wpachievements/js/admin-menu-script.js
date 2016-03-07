jQuery(document).ready(function(){
  var href = jQuery('#toplevel_page_edit-post_type-wpquests a').attr('href');
  jQuery('#toplevel_page_edit-post_type-wpquests').remove();
  jQuery('#toplevel_page_edit-post_type-wpachievements ul li.wp-first-item').after('<li><a href="'+href+'">WPQuests</a></li>');
  //jQuery('#toplevel_page_edit-post_type-wpachievements a[href="edit.php?post_type=wpachievements&page=wpachievements_ranks"],#toplevel_page_edit-post_type-wpachievements a[href="edit.php?post_type=wpachievements&page=wpachievements_documentation"]').parent().css({'border-bottom':'1px dashed #ccc','margin-bottom':8,'padding-bottom':8});
});