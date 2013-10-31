<?php

/*
Plugin Name: Read / Read
Plugin URI: http://github.com/twittem/read-read
Description: A simple plugin to generate the minutes to read for WordPress posts. Increase blog readership by qualifying the time commitment needed to read your post.
Version: 0.1
Author: Edward McIntyre @twittem
Author URI: http://github.com/twittem/
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-read-read.php' );

Read_Read::get_instance();