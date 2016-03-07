<?php

//general settings page

function bur_display() {

 ?>
			
						<table class="form-table">
					
					<tr valign="top">
						<th colspan="2">
						
						<h3>
						<?php _e ('Display' , 'bur-user-ranking' ) ; ?>
						</h3>


						
					
	<?php
	//show style image
	 echo '<img src="' . plugins_url( 'images/image1.JPG',dirname(__FILE__)  ) . '" > '; ?>
<p>
<?php _e ('Use the Ranks tabs to set the levels, names and images.  Use this tab to determine what is displayed' , 'bbp-user-ranking' ) ; ?>
</p>

<p>
<?php _e ('DISPLAY SETTINGS - For each item, decide if you wish to display, and labels where required' , 'bbp-user-ranking' ) ; ?>
</p>
<p>
<?php _e ('DISPLAY ORDER - decide in what order you wish to show items' , 'bbp-user-ranking' ) ; ?>
</p>
<p></p>
<?php global $bur_display ;?>
	
	<Form method="post" action="options.php">
	<?php wp_nonce_field( 'display', 'display-nonce' ) ?>
	<?php settings_fields( 'bur_display' );
	//create a style.css on entry and on saving
	generate_bur_css() ;
	?>	
	<table class="form-table">

	
	<tr><td>  </td></tr>
	<tr><td>  </td></tr>
	<tr><td> 
	<?php _e ('DISPLAY SETTINGS' , 'bbp-user-ranking' ) ; ?>
	</td></tr>
	<?php //*************Display post counts************************  ?>
	<tr>
	<td style="width:200px">
	<?php _e ('Display Topic Counts' , 'bbp-user-ranking' ) ; ?>
	</td> 
	<td style="width:200px">
	<?php $value = (!empty($bur_display["topic_count"] ) ? $bur_display["topic_count"] : '') ;  ?>
	<?php echo '<input name="bur_display[topic_count]" id="bur_display[topic_count]" type="checkbox" value="1" class="code" ' . checked( 1,$value, false ) . ' /> Show topic count';
 	?>
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Topic Count display name' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[topic_name]' ; ?>
	<?php $value = (!empty($bur_display["topic_name"]) ? $bur_display["topic_name"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="large-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter topic count name eg "Comments" , "topics" , "contributions" etc. - leave blank to just display the count', 'bbp-user-ranking' ); ?></label></br>
			<label class="description"><?php _e( 'Include any delimeter eg Count : Count - ' , 'bbp-user-ranking' ); ?></label></br>
	</td>
	</tr>
	
	
	<tr>
	<td style="width:200px">
	<?php _e ('Display Reply Counts' , 'bbp-user-ranking' ) ; ?>
	</td> 
	<td style="width:200px">
	<?php $value = (!empty($bur_display["reply_count"] ) ? $bur_display["reply_count"] : '') ;  ?>
	<?php echo '<input name="bur_display[reply_count]" id="bur_display[reply_count]" type="checkbox" value="1" class="code" ' . checked( 1,$value, false ) . ' /> Show reply count';
 	?>
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Reply Count display name' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[reply_name]' ; ?>
	<?php $value = (!empty($bur_display["reply_name"]) ? $bur_display["reply_name"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="large-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter Reply count name eg "Comments" , "Replies" , "contributions" etc. - leave blank to just display the count', 'bbp-user-ranking' ); ?></label></br>
			<label class="description"><?php _e( 'Include any delimeter eg Replies : Replies - ' , 'bbp-user-ranking' ); ?></label></br>
	</td>
	</tr>
	
	<tr>
	<td style="width:200px">
	<?php _e ('Display Total Counts' , 'bbp-user-ranking' ) ; ?>
	</td> 
	<td style="width:200px">
	<?php $value = (!empty($bur_display["total_count"] ) ? $bur_display["total_count"] : '') ;  ?>
	<?php echo '<input name="bur_display[total_count]" id="bur_display[total_count]" type="checkbox" value="1" class="code" ' . checked( 1,$value, false ) . ' /> Show total count';
 	?>
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Total Count display name' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[total_name]' ; ?>
	<?php $item2='bur_display[image_max]' ; ?>
	<?php $value = (!empty($bur_display["total_name"]) ? $bur_display["total_name"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="large-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter post count name eg "Comments" , "Totals" , "contributions" etc. - leave blank to just display the count', 'bbp-user-ranking' ); ?></label></br>
			<label class="description"><?php _e( 'Include any delimeter eg Total : Total - ' , 'bbp-user-ranking' ); ?></label></br>
			

	</td>
	</tr>
	
	
	
	
	<?php //*************Display Rank names************************  ?>
	<tr>
	<td style="vertical-align:top">
	<?php _e ('Display Rank Names' , 'bbp-user-ranking' ) ; ?>
	</td> <td>
	<?php $value = (!empty($bur_display["display_names"] ) ? $bur_display["display_names"] : '') ;  ?>
	<?php echo '<input name="bur_display[display_names]" id="bur_display[display_names]" type="checkbox" value="1" class="code" ' . checked( 1,$value, false ) . ' /> Show rank names';
 	?>
	</td>
	</tr>
	
	<?php //*************Display Level Symbols************************  ?>
	<tr>
	<td style="vertical-align:top">
	<?php _e ('Display Level Symbols' , 'bbp-user-ranking' ) ; ?>
	</td> <td>
	<?php $value = (!empty($bur_display["level_symbol"] ) ? $bur_display["level_symbol"] : '') ;  ?>
	<?php echo '<input name="bur_display[level_symbol]" id="bur_display[level_symbol]" type="checkbox" value="1" class="code" ' . checked( 1,$value, false ) . ' /> Show Level Symbols';
 	?>
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td></td>
	<td>
	<?php $value = (!empty($bur_display["star_type"] ) ? $bur_display["star_type"] : 'white') ;  ?>
	<?php echo '<input name="bur_display[star_type]" id="bur_display[star_type]" type="radio" value="white" class="code" ' . checked( "white",$value, false ) . ' /> Hollow Stars &#9734;'; ?>
	<?php echo '<input name="bur_display[star_type]" id="bur_display[star_type]" type="radio" value="black" class="code" ' . checked( "black",$value, false ) . ' /> Solid Stars &#9733;'; ?>
	</td>
	
	</tr>
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Symbol Color' , 'bbp-user-ranking' ) ; ?>
	</td>

	
	<td style="vertical-align:top">
	<?php $item='bur_display[symbol_color]' ; ?>
	<?php $value = (!empty($bur_display["symbol_color"]) ? $bur_display["symbol_color"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="large-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Default black Enter color by name or hex value eg green or #00ff00', 'bbp-user-ranking' ); ?></label></br>
			
	</td>
	
	</tr>
	
	<?php //*************Display Image************************  ?>
	<tr>
	<td style="vertical-align:top">
	<?php _e ('Display Image' , 'bbp-user-ranking' ) ; ?>
	</td> <td>
	<?php $value = (!empty($bur_display["display_image"] ) ? $bur_display["display_image"] : '') ;  ?>
	<?php echo '<input name="bur_display[display_image]" id="bur_display[display_image]" type="checkbox" value="1" class="code" ' . checked( 1,$value, false ) . ' /> Show Image';
 	?>
	</td>
	</tr>
	
	<tr><td> 
	<tr><td>  </td></tr>
	<tr><td>  </td></tr>
	<tr><td>
	<?php _e ('DISPLAY ORDER' , 'bbp-user-ranking' ) ; ?>
	</td></tr>
	<tr><td colspan = "2">
	<?php _e ('If you wish to change the default order, do this here' , 'bbp-user-ranking' ) ; ?>
	</td></tr>
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Topic Count' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[topic_order]' ; ?>
	<?php $value = (!empty($bur_display["topic_order"]) ? $bur_display["topic_order"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter the order ie a number from 1 to 6', 'bbp-user-ranking' ); ?></label></br>
			
	</td>
	</tr>
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Reply Count' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[reply_order]' ; ?>
	<?php $value = (!empty($bur_display["reply_order"]) ? $bur_display["reply_order"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter the order ie a number from 1 to 6', 'bbp-user-ranking' ); ?></label></br>
			
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Total Count' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[total_order]' ; ?>
	<?php $value = (!empty($bur_display["total_order"]) ? $bur_display["total_order"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter the order ie a number from 1 to 6', 'bbp-user-ranking' ); ?></label></br>
			
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Rank Name' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[name_order]' ; ?>
	<?php $value = (!empty($bur_display["name_order"]) ? $bur_display["name_order"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter the order ie a number from 1 to 6', 'bbp-user-ranking' ); ?></label></br>
			
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Level Symbol' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[level_order]' ; ?>
	<?php $value = (!empty($bur_display["level_order"]) ? $bur_display["level_order"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter the order ie a number from 1 to 6', 'bbp-user-ranking' ); ?></label></br>
			
	</td>
	</tr>
	
	<tr>
	<td></td>
	<td style="vertical-align:top">
	<?php _e ('Image' , 'bbp-user-ranking' ) ; ?>
	</td>
	<td style="vertical-align:top">
	<?php $item='bur_display[image_order]' ; ?>
	<?php $value = (!empty($bur_display["image_order"]) ? $bur_display["image_order"] : '') ; ?>
	<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
			<label class="description"><?php _e( 'Enter the order ie a number from 1 to 6', 'bbp-user-ranking' ); ?></label></br>
			
	</td>
	</tr>
	
	</table>
	
	
		
<!-- save the options -->
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'bbp-user-ranking' ); ?>" />
				</p>
				</form>
		</div><!--end sf-wrap-->
	</div><!--end wrap-->
	
	 
		

<?php

}




