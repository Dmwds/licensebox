<?php 
/*
Plugin Name: LicenseboxWoo
Plugin URI: https://www.dmwds.com/licenseboxwoo
Description: Add support to woo-commerce for license box license manage and updater
Version: 1.0.0
Author: Daniel Ray
Author URI: https://www.dmwds.com
Text Domain: licenseboxwoo
*/

// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if

// Let's Initialize Everything
if ( file_exists( plugin_dir_path( __FILE__ ) . 'init.php' ) ) {
require_once( plugin_dir_path( __FILE__ ) . 'init.php' );
}