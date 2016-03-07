<?php

// bbPress User post count
function bur_display_counts () {
		
	
		global $bur_ranks ;
		global $bur_display ;
		global $reply_id ;
		
		$user_id=bbp_get_reply_author_id( $reply_id ) ;
		
		$topics  = bbp_get_user_topic_count_raw( $user_id);
		$replies = bbp_get_user_reply_count_raw( $user_id);
		
		
		//work out what we are counting and blank the answer if not counting
		if (empty($bur_ranks["count_topics"])) $topics = '' ;
		if (empty($bur_ranks["count_replies"])) $replies = '' ;
		$total_count   = (int) $topics + $replies;
		
		//set stars
		if ($bur_display["star_type"] == 'white' )  $star = '&#9734;' ;
		if ($bur_display["star_type"] == 'black' )  $star = '&#9733;' ;
				
		
		//now we set the variables by looping round until we reach $top (ignoring any blank rows )
		
		
		//set the levels
		$top = (!empty($bur_ranks['number_of_levels']) ? $bur_ranks['number_of_levels'] : '2') ;
		$i = 1 ;
		$stars='' ;
		//start loop
		while($i<= $top)   {
		$levelname = "level".$i.'name' ;
		$levelimage = "level".$i.'image' ;
		$levelimage_height = "level".$i.'image_height' ;
		$levelimage_width = "level".$i.'image_width' ;
		$posts = 'level'.$i.'posts' ;
		//if the level 'up to number' is blank, then pass on this level by setting it to sero
		$leveli = (!empty($bur_ranks[$posts]) ? $bur_ranks[$posts] : '0') ;
		//but then set it to 'top' if this is the last row
		if ($i == $top) $leveli = 'top' ;
			
		//increment the number of stars	
		$stars = $stars.$star ;
			
		if ( ($total_count < $leveli)    || ($leveli == 'top') ) {
		$name = (!empty($bur_ranks[$levelname]) ? $bur_ranks[$levelname] : '') ;
		$image = (!empty($bur_ranks[$levelimage]) ? $bur_ranks[$levelimage] : '') ;
		$image_height = (!empty($bur_ranks[$levelimage_height]) ? $bur_ranks[$levelimage_height] : '') ;
		$image_width = (!empty($bur_ranks[$levelimage_width]) ? $bur_ranks[$levelimage_width] : '') ;
		break ; //quit if we've found the level
		} //end of if
				
		
		//increment $i
		$i++ ;
		
		} //end of while
		
		
		echo '<ul>' ;	
		
		
		//work out the display order, and call the functions in the right order, if order for an item is not set, but the item is set to display then set level 6 for default order
		$i=1 ;
		//set the limit to 6 as we have 6 options for the order
		while($i<=6)   {
		if ((!empty($bur_display["topic_order"]) ? $bur_display["topic_order"] : '6') == $i) bur_topic_count($topics) ;
		if ((!empty($bur_display["reply_order"]) ? $bur_display["reply_order"] : '6') == $i) bur_replies_count($replies) ; ;
		if ((!empty($bur_display["total_order"]) ? $bur_display["total_order"] : '6') == $i) bur_total_count($total_count) ;
		if ((!empty($bur_display["name_order"]) ? $bur_display["name_order"] : '6') == $i) bur_display_name ($name) ;;
		if ((!empty($bur_display["level_order"]) ? $bur_display["level_order"] : '6') == $i) bur_display_level ($stars);
		if ((!empty($bur_display["image_order"]) ? $bur_display["image_order"] : '6') == $i) bur_display_image($image, $image_height, $image_width) ;
		//increments $i	
		$i++;	
		}
		echo '</ul>' ;
		
}


add_action ('bbp_theme_after_reply_author_details', 'bur_display_counts');



		function bur_topic_count($topics) {
		//display topics count
		global $bur_display ;
		if (!empty($bur_display["topic_count"])) {
		echo '<li>' ;
		echo (!empty($bur_display["topic_name"]) ? $bur_display["topic_name"] : '');
		echo $topics ;
		echo '</li>' ;
		}
		}
		
		
		function bur_replies_count($replies) {
		//display replies count
		global $bur_display ;
			if (!empty($bur_display["reply_count"])) {
			echo '<li>' ;
			echo (!empty($bur_display["reply_name"]) ? $bur_display["reply_name"] : '');
			echo $replies ;
			echo '</li>' ;
			}
		}
		
		function bur_total_count($total_count) {
		//display total count
		global $bur_display ;
			if (!empty($bur_display["total_count"])) {
			echo '<li>' ;
			echo (!empty($bur_display["total_name"]) ? $bur_display["total_name"] : '');
			echo $total_count ;
			echo '</li>' ;
			}
		}
		
		function bur_display_name($name) {
		//display name
		global $bur_display ;
			if (!empty($bur_display["display_names"])) {
			echo '<li>' ;
			echo $name ;	
			echo '</li>' ;
			}
		}
		
		function bur_display_level($stars) {
		//display stars
		global $bur_display ;
			if (!empty($bur_display["level_symbol"])) {
			echo '<li>' ;
			echo '<div class = "ur-symbol">' ;
			echo $stars ;
			echo '</div>' ;
			echo '</li>' ;
			}
		}
		
		function bur_display_image($image, $image_height, $image_width) {
		//display image
		global $bur_display ;
			if (!empty($bur_display["display_image"])) {
			echo '<li>' ;
			echo '<img src = "'.$image.'" height="'.$image_height.'" width="'.$image_width.'" >' ;	
			echo '</li>' ;
			}
		}
		
		


?>
