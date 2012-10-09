<?php
/*
Plugin Name: Better Ping
Plugin URI: http://github.com/BlaineSch/wp_betterPing
Description: Allow pings to be made on pages and posts that also include the changed url
Version: 0.1
Author: Blaine Schmeisser
Author URI: http://github.com/BlaineSch
*/

if ( ! class_exists( 'WP' ) ) {
	die();
}
require_once(__DIR__ . '/classes/betterPing.php');
new betterPing();