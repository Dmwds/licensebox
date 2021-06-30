<?php 
/*
*
*	***** LicenseboxWoo *****
*
*	This file initializes all LICENSEBOXWOO Core components
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
// Define Our Constants
define('LICENSEBOXWOO_CORE_INC',dirname( __FILE__ ).'/includes/');

/*
*
*  Includes
*
*/ 

// Load the Functions
if ( file_exists( LICENSEBOXWOO_CORE_INC . 'class-internal-licenseboxwoo.php' ) ) {
	require_once LICENSEBOXWOO_CORE_INC . 'class-internal-licenseboxwoo.php';
}     

if ( file_exists( LICENSEBOXWOO_CORE_INC . 'class-product-licenseboxwoo.php' ) ) {
	require_once LICENSEBOXWOO_CORE_INC . 'class-product-licenseboxwoo.php';
} 

if ( file_exists( LICENSEBOXWOO_CORE_INC . 'class-settings-licenseboxwoo.php' ) ) {
	require_once LICENSEBOXWOO_CORE_INC . 'class-settings-licenseboxwoo.php';
} 
if ( file_exists( LICENSEBOXWOO_CORE_INC . 'class-account-licenseboxwoo.php' ) ) {
	require_once LICENSEBOXWOO_CORE_INC . 'class-account-licenseboxwoo.php';
}
if ( file_exists( LICENSEBOXWOO_CORE_INC . 'class-orders-licenseboxwoo.php' ) ) {
	require_once LICENSEBOXWOO_CORE_INC . 'class-orders-licenseboxwoo.php';
}