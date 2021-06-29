<?php
/* 
Plugin Name: LicenseBox WP Test Plugin
Plugin URI: https://www.licensebox.app
Description: This is a very simple WP plugin to illustrate the use of <strong>LicenseBox API</strong> for checking licenses and updates. Kindly add license details in the plugin options page for testing. If the provided license is valid the plugin will work, which is it will display a simple message in all the posts footer.
Version: 1.4.0
Author: CodeMonks
Author URI: https://www.licensebox.app
*/
if(!defined('ABSPATH')){
	exit;
}

// Load the helper file to increase protection you can include some core functions of your plugin like innit() functions etc. in this file before you obfuscate it.
require 'includes/lb_helper.php';

// Create a new LicenseBoxAPI helper class.
$lbapi = new LicenseBoxAPI();

// Performs background license check, pass TRUE as 1st parameter to perform periodic verifications only.
$lb_verify_res = $lbapi->verify_license();

// Performs update check, you can easily change the duration of update checks.
if(false === ($lb_update_res = get_transient('licensebox_next_update_check'))){
	$lb_update_res = $lbapi->check_update();
	set_transient('licensebox_next_update_check', $lb_update_res, 12*HOUR_IN_SECONDS);
}


// If user has a valid license and new updates are available show the update notification in plugins page.
if(($lb_update_res['status'])&&($lb_verify_res['status'])){
	function licensebox_show_update_notice(){
		global $lb_update_res;
		$lb_update_message = esc_html($lb_update_res['message']);
		$update_notification = <<<LB_UPDATE
<tr class="active">
	<td colspan="3">
		<div class="update-message notice inline notice-warning notice-alt" style="margin: 5px 20px 10px 20px">
			<p>
				<b>$lb_update_message</b>
				<a href="options-general.php?page=licensebox-test" style="text-decoration: underline;">Update now</a>.
			</p>
		</div>
	</td>
</tr>
LB_UPDATE;
  		echo $update_notification;
	}
	add_action("after_plugin_row_".plugin_basename(__FILE__), 'licensebox_show_update_notice', 10, 3);
}

// If user doesn't have a valid license show the activation pending notification in plugins page.
if(!$lb_verify_res['status']){
	function licensebox_show_license_notice(){
		$license_notification = <<<LB_LICENSE
<tr class="active">
	<td colspan="3">
		<div class="notice notice-error inline notice-alt" style="margin: 5px 20px 10px 20px">
			<p>
				<b>License is not set yet, Please enter your license code to use the plugin.</b>
				<a href="options-general.php?page=licensebox-test" style="text-decoration: underline;">Enter License Code</a>.
			</p>
		</div>
	</td>
</tr>
LB_LICENSE;
  		echo $license_notification;
	}
	add_action("after_plugin_row_".plugin_basename(__FILE__), 'licensebox_show_license_notice', 10, 3);
}

// Add plugin settings page link.
function licensebox_add_settings_link($links){
	$settings_link = '<a href="options-general.php?page=licensebox-test">Settings</a>';
	array_push($links, $settings_link);
	return $links;
}
add_filter("plugin_action_links_".plugin_basename(__FILE__), 'licensebox_add_settings_link');

//Sample plugin stuff 
function licensebox_test_plugin_core($content_data){ 
	global $lb_verify_res;
	if($lb_verify_res['status']){
		$content_data .= '<footer><b>Sample plugin to test LicenseBox is active, Thank you for purchasing.</b></footer>';
	}else{
		$content_data .= '<footer><b>You have not activated the Sample plugin yet or your License is invalid, Please enter a valid license in the plugin options page.</b></footer>';
	}
	return $content_data;
}
add_filter('the_content', 'licensebox_test_plugin_core');

include 'licensebox-test-options.php'; // Load the settings page.
