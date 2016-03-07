<?php

/*
Plugin Name: bbp User Ranking 
Plugin URI: http://www.rewweb.co.uk/bbp-ranking/
Description: This Plugin lets you add ranking elements to topics and replies in bbPress.  These appear after the author details on each post.
Version: 1.2
Text Domain: bbp-user-ranking
Author: Robin Wilson
Author URI: http://www.rewweb.co.uk
License: GPL2
*/
/*  Copyright 2015  PLUGIN_AUTHOR_NAME  (email : wilsonrobine@btinternet.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

	

/*******************************************
* global variables
*******************************************/

// load the plugin options
$bur_display = get_option( 'bur_display' );
$bur_ranks = get_option( 'bur_ranks' );


if(!defined('BUR_PLUGIN_DIR'))
	define('BUR_PLUGIN_DIR', dirname(__FILE__));
	
function bbp_user_ranking_init() {
  load_plugin_textdomain('bbp-user-ranking', false, basename( dirname( __FILE__ ) ) . '/lang' );
}
add_action('plugins_loaded', 'bbp_user_ranking_init');


/*******************************************
* file includes 
*******************************************/
include(BUR_PLUGIN_DIR . '/includes/settings.php');
include(BUR_PLUGIN_DIR . '/includes/ranks.php');
include(BUR_PLUGIN_DIR . '/includes/display.php');
include(BUR_PLUGIN_DIR . '/includes/functions.php');
include(BUR_PLUGIN_DIR . '/includes/generate_css.php');

/**************************************
*Versioning 
***************************************/
$new_version = '1.2';

if (!defined('BUR_VERSION_KEY'))
    define('BUR_VERSION_KEY', 'ur_version');

if (!defined('BUR_VERSION_NUM'))
    define('BUR_VERSION_NUM', $new_version);

add_option(BUR_VERSION_KEY, BUR_VERSION_NUM);



if (get_option(BUR_VERSION_KEY) != $new_version) {
    // Execute the save to update the css file
	generate_bur_css() ;

    // Then update the version value
    update_option(BUR_VERSION_KEY, $new_version);
}




