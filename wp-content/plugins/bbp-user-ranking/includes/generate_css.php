<?php

function generate_bur_css() {
	ob_start(); // Capture all output (output buffering)
	require (BUR_PLUGIN_DIR . '/includes/styles.php');
	$css = ob_get_clean(); // Get generated CSS (output buffering)
	file_put_contents(BUR_PLUGIN_DIR . '/css/user-ranking.css', $css, LOCK_EX ); // Save it
	
	wp_enqueue_style( 'bur');
	}

function ur_enqueue_css() {
wp_register_style('bur', plugins_url('css/user-ranking.css',dirname(__FILE__) ), 'bbpress');
wp_enqueue_style( 'bur');
}
add_action('wp_print_styles', 'ur_enqueue_css');
