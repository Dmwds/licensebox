<?php if(!defined('ABSPATH')){ exit; }

add_action('admin_menu', function(){
	add_options_page(
		'LicenseBox WP Test Plugin', 
		'LicenseBox WP Test Plugin', 
		'manage_options', 
		'licensebox-test', 
		'licensebox_test_plugin_page' 
	);
});

function licensebox_test_plugin_page(){
	global $lbapi;
	global $lb_verify_res;
	$lb_activate_res = null;
	$lb_deactivate_res = null;
	if(!empty($_POST['client_name'])&&!empty($_POST['license_code'])){
		check_admin_referer('lb_update_license', 'lb_update_license_sec');
		$lb_activate_res = $lbapi->activate_license(
			strip_tags(trim($_POST['license_code'])), 
			strip_tags(trim($_POST['client_name']))
		);
		$lb_verify_res = $lbapi->verify_license();
	}
	if(!empty($_POST['lb_deactivate'])){
		check_admin_referer('lb_deactivate_license', 'lb_deactivate_license_sec');
		$lb_deactivate_res = $lbapi->deactivate_license();
		$lb_verify_res = $lbapi->verify_license();
	}
	$lb_update_data = $lbapi->check_update(); ?>

	<div class="wrap">
		<h1>LicenseBox WP Test Plugin - Settings</h1>
		<?php if($lb_verify_res['status']){ ?> 
			<div class="notice notice-info">
				<p>Activated! Your license is valid.</p>
			</div> 
		<?php }else{ ?> 
			<div class="notice notice-error">
				<p><?php echo (!empty($lb_activate_res['message']))?$lb_activate_res['message']:'No license has been provided yet or the provided license is invalid.' ?></p>
			</div> 
		<?php }?>
		<form action="" method="post">
			<?php wp_nonce_field('lb_update_license', 'lb_update_license_sec'); ?>
			<table>   
				<tr>
					<th>License code</th>
					<td>
						<input type="text" name="license_code" size="50" placeholder="<?php 
						if($lb_verify_res['status']){
							echo 'Enter the license code here to update';
						}else{
							echo 'Enter the license code here';
						} ?>" required>
					</td>
				</tr>
				<tr>
					<th>Your name</th>
					<td>
						<input type="text" name="client_name" size="50" placeholder="<?php 
						if($lb_verify_res['status']){
							echo 'Enter your name/licensee\'s name here to update';
						}else{
							echo 'Enter your name/licensee\'s name here';
						} ?>" required>
					</td>
				</tr>    
				<tr>
					<td>
						<div style="padding-top: 10px;">
							<input type="submit" value="Activate" class="button button-primary">
						</div>
					</td>
				</tr>
			</table>
		</form>
		<?php if($lb_verify_res['status']){ ?>
			<h2 class="title" style="padding-top:10px;">Deactivate License</h2>
			<p style="max-width: 450px;">
				If you wish to use this license for activating plugin on a different server, you can first release your license from this server by deactivating it below.
			</p>
			<?php if(empty($lb_deactivate_res)){ ?>
				<form action="" method="post">
					<?php wp_nonce_field('lb_deactivate_license', 'lb_deactivate_license_sec'); ?>
					<input type="hidden" name="lb_deactivate" value="yes">
					<input type="submit" value="Deactivate" class="button">
				</form>
			<?php } ?>
		<?php } ?>
		<?php if($lb_verify_res['status']){ ?>
			<h2 class="title" style="padding-top:10px;">Plugin Updates</h2>
			<p>
				<strong><?php echo esc_html($lb_update_data['message']); ?></strong>
			</p>
			<?php if($lb_update_data['status']){ ?>
				<p style="max-width: 700px;">Changelog: 
					<?php echo strip_tags($lb_update_data['changelog'], '<ol><ul><li><i><b><strong><p><br><a><blockquote>'); ?>
				</p>
				<?php if(!empty($_POST['update_id'])){
					check_admin_referer('lb_update_download', 'lb_update_download_sec');
					$lbapi->download_update(
						strip_tags(trim($_POST['update_id'])), 
						strip_tags(trim($_POST['has_sql'])), 
						strip_tags(trim($_POST['version']))
					);
					if (false !== get_transient('licensebox_next_update_check')) {
						delete_transient('licensebox_next_update_check');
					}
				?>
				<?php }else{ ?>
					<form action="" method="POST">
						<?php wp_nonce_field('lb_update_download', 'lb_update_download_sec'); ?>
						<input type="hidden" value="<?php echo esc_attr($lb_update_data['update_id']); ?>" name="update_id">
						<input type="hidden" value="<?php echo esc_attr($lb_update_data['has_sql']); ?>" name="has_sql">
						<input type="hidden" value="<?php echo esc_attr($lb_update_data['version']); ?>" name="version">
						<div style="padding-top: 10px;">
							<input type="submit" value="Download and Install Update" class="button button-secondary">
						</div>
					</form>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
<?php }
