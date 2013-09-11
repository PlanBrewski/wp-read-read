<?php
/**
 * Read / Read
 *
 * A simple plugin to generate the minutes to read for WordPress posts. Increase blog readership by qualifying the time commitment needed to read your post.
 *
 * @package   Read_Read
 * @author    Edward McIntyre <edward@edwardmcintyre.com>
 * @license   GPL-2.0+
 * @link      https://github.com/twittem/
 * @copyright 2013 Edward McIntyre
 *
 * @wordpress-plugin
 * Plugin Name: Read / Read
 * Plugin URI:  https://github.com/twittem/read-read
 * Description: A simple plugin to generate the minutes to read for WordPress posts. Increase blog readership by qualifying the time commitment needed to read your post.
 * Version:     1.0.1
 * Author:      Edward McIntyre @twittem
 * Author URI:  https://github.com/twittem/
 * Text Domain: WP_MinsToRead
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-read-read.php' );

Read_Read::get_instance();