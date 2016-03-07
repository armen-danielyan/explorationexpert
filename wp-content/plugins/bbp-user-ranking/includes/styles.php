
<?php 
$data = get_option('bur_display') ;
?>




/*  1 ----------------------  set the symbol color --------------------------*/
<?php 
$field=$data['symbol_color'] ;
if (!empty ($field)) {
?>
.ur-symbol {
	font-family: arial, "Times New Roman", Times, serif;
	font-size : 14px ;
	color :  <?php echo $field ;?>  ;
	
}

<?php } ?>

