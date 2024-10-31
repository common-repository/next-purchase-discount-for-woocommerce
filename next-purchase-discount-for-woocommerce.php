<?php

/**
* Plugin Name: Next Purchase Discount for WooCommerce
* Description: Let your customers gain bonuses with their purchases that will make the next purchase cheaper.
* Author: OnlineVagyok.hu
* Version: 1.0.1
* Author URI: http://onlinevagyok.hu
* License: GPL2
* Text Domain: next-purchase-discount
* 
**/



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require('function.php');
require('settings.php');
require('display.php');

function npd_enqueue() {
    wp_enqueue_style( 'npd-style', plugin_dir_url(__FILE__) . 'style.css' );
}
add_action( 'wp_enqueue_scripts', 'npd_enqueue' );