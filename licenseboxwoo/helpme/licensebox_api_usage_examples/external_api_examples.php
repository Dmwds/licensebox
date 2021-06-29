<?php 
require_once 'includes/lb_helper.php'; // Loads the external helper file
$api = new LicenseBoxAPI(); // We create a new LicenseBoxAPI object as $api
?>
<!DOCTYPE html>
<html>
<head>
	<title>External/Client API examples - LicenseBox</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" crossorigin="anonymous"/>
	<script type="text/javascript">
		function updateProgress(percentage) {
			document.getElementById('progress').value = percentage;
		}
	</script>
</head>
<body class="bg-light">
		<div class="container">
			<div class="mt-5 py-4 text-center">
				<h2><b><a style="color: inherit;text-decoration: none" href="https://licensebox.app" target="_blank" rel="noopener">LicenseBox<sup><small>v1.4.0</small></sup></a></b> - External/Client API & Helper File usage examples</h2>
				<p class="lead pt-3 pb-2 text-justify">These are some usage examples and explanations of functions from the Client/External helper file (<strong>for PHP only</strong>) along with their sample code, you can use the functions directly in your PHP applications or if you want you can also directly access the LicenseBox API from your preferred programming language using the info about API endpoints, request headers, request parameters mentioned below.</p>
				<div class="alert alert-warning mb-0">
				<strong>Note</strong>: All API request parameters are compulsory unless specified otherwise. You need an API key of type "<b>external</b>" to access the external/client API.
				</div>
			</div>
						<div class="card">
				<div class="card-body">
					<h4 class="mb-3" id="compulsory_headers"><strong>Compulsory Request Headers</strong></h4>
					<p>All Requests made to the LicenseBox API must have the following request headers, If you are using the generated helper file these are already taken care of.</p>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">API Request Headers</th>
								<th scope="col">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Accept</th>
								<td>Response content type, LicenseBox supports JSON (<b>application/json</b>) and XML (<b>application/xml</b>).</td>
							</tr>
							<tr>
								<th>Content-Type</th>
								<td>Request content type, LicenseBox supports JSON (<b>application/json</b>) and XML (<b>application/xml</b>).</td>
							</tr>
							<tr>
								<th>LB-API-KEY</th>
								<td>LicenseBox API Internal Key, It is already defined in the constructor function of LicenseBoxAPI helper class.</td>
							</tr>
							<tr>
								<th>LB-LANG</th>
								<td>LicenseBox API Language, It is already defined in the constructor function of LicenseBoxAPI helper class.</td>
							</tr>
							<tr>
								<th>LB-URL</th>
								<td>URL of the file from where the request is being made, It is already calculated by the LicenseBoxAPI helper class.</td>
							</tr>
							<tr>
								<th>LB-IP</th>
								<td>IP of the server from where the request is being made, It is already calculated by the LicenseBoxAPI helper class.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="check_connection">1. <strong>check_connection()</strong>, API endpoint: <span class="text-primary">/api/check_connection_ext</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>Response will contain a success message if the connection to the LicenseBox server was successful and the request headers were correct otherwise it will contain the error message.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "Connection successful."
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->check_connection();</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					$check_connection_response = $api->check_connection();
					echo "<p class='text-primary'><strong>".$check_connection_response['message']."</strong></p>"; ?>
				</div>
			</div><br>
			<h4 class="mb-3" id="get_latest_version">2. <strong>get_latest_version()</strong>, API endpoint: <span class="text-primary">/api/latest_version</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>Response will contain the latest version of the provided product ID along with it's relevent details.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "Latest version of MyScript is v5.0.0",
  "product_name": "MyScript",
  "latest_version": "v5.0.0",
  "release_date": "2020-05-16"
  "summary": "Still on v1.x.x? Upgrade now."
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->get_latest_version();</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					$latest_version_response = $api->get_latest_version();
					echo "<p class='text-primary'><strong>".$latest_version_response['message']."</strong></p>"; ?>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">API Request Body</th>
								<th scope="col">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>product_id</th>
								<td>Product ID, It is already defined in the constructor function of LicenseBoxAPI class.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="activate_license">3. <strong>activate_license()</strong>, API endpoint: <span class="text-primary">/api/activate_license</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It returns the API response for <code>/activate_license</code> endpoint, you can check if the provided license code is valid or not based on the response <code>status</code> it will be either TRUE or FALSE. This function activates the license and creates its activation on the server. It also creates a local <code>.lic</code> license file based on the server response after successful activation and it deletes the existing local <code>.lic</code> file if the activation attempt fails. You can disable the creation/deletion of local <code>.lic</code> file by passing FALSE as a 3rd parameter during its function call.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "Activated! Thanks for purchasing MyScript.",
  "data": null,
  "lic_response": "lic_file"
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->activate_license($license_code, $client_name);</b></pre>
					<p><em>You can also pass FALSE as a 3rd parameter if you don't want it to create a local <code>.lic</code> file on successful activation.</em></p>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['license'])&&!empty($_POST['client'])){
						$activate_response = $api->activate_license($_POST['license'],$_POST['client']);
						echo "<p class='text-primary'><strong>".$activate_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="external_api_examples.php#activate_license" method="POST">
							<div class="form-group">
								<label for="email">License code :</label>
								<input type="text" class="form-control" name="license">
							</div>
							<div class="form-group">
								<label for="pwd"> Client name :</label>
								<input type="text" class="form-control" name="client">
							</div>
							<button type="submit" class="btn btn-primary">Activate License</button>
						</form> 
						<br>
						<?php
					}?>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">API Request Body</th>
								<th scope="col">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>product_id</th>
								<td>Product ID, It is already defined in the constructor function of LicenseBoxAPI class.</td>
							</tr>
							<tr>
								<th>verify_type</th>
								<td>It describes if LicenseBox will check the provided license with Envato first or not, verify_type can be 'envato' or 'non_envato' (default), verify_type is already defined in the constructor function of LicenseBoxAPI class.</td>
							</tr>
							<tr>
								<th>license_code</th>
								<td>License code, It is the 1st argument passed in activate_license() function during its call.</td>
							</tr>
							<tr>
								<th>client_name</th>
								<td>Client name or Envato username (if envato purchase codes are allowed I.e verify_type is set to 'envato'), It is the 2nd argument passed in activate_license() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="verify_license">4. <strong>verify_license()</strong>, API endpoint: <span class="text-primary">/api/verify_license</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It returns the API response for <code>/verify_license</code> endpoint, This function is meant to be used for background license checks. If you pass the 1st parameter as TRUE the function will check the license based on the <code>verification_period</code> (defined in the constructor function of LicenseBoxAPI class). It is meant to just check if the provided license is valid or not for the current activation without creating any logs on the server, you can check if the license is valid or not based on the response <code>status</code> if will be either TRUE or FALSE. If you call this function without passing the 2nd and 3rd parameters it will send the locally saved <code>.lic</code> license file to the server which the server will decrypt and parse to check the license if it is valid or not.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "Verified! Thanks for purchasing MyScript.",
  "data": "stuff here"
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->verify_license(false, $license_code, $client_name);</b></pre>
					<p><em>If you want it to check the license based on the <code>verification_period</code> pass the 1st parameter as TRUE, if you want it to check the license from the local <code>.lic</code> license file, don't pass the 2nd and 3rd parameters.</em></p>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['license1'])&&!empty($_POST['client1'])){
						$verify_response = $api->verify_license(false,$_POST['license1'],$_POST['client1']);
						echo "<p class='text-primary'><strong>".$verify_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="external_api_examples.php#verify_license" method="POST">
							<div class="form-group">
								<label for="email">License code:</label>
								<input type="text" class="form-control" name="license1">
							</div>
							<div class="form-group">
								<label for="pwd"> Client name :</label>
								<input type="text" class="form-control" name="client1">
							</div>
							<button type="submit" class="btn btn-primary">Verify License</button>
						</form> 
						<br>
						<?php
					}?>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">API Request Body</th>
								<th scope="col">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>product_id</th>
								<td>Product ID, It is already defined in the constructor function of LicenseBoxAPI class.</td>
							</tr>
							<tr>
								<th>license_code</th>
								<td>License code, It is the 2nd argument passed in verify_license() function during its call.</td>
							</tr>
							<tr>
								<th>client_name</th>
								<td>Client name or Envato username (if envato purchase codes are allowed i.e verify_type is set to 'envato'), It is the 3rd argument passed in verify_license() function during its call.</td>
							</tr>
							<tr>
								<th>license_file</th>
								<td>Contents of the local encrypted .lic file will be sent here if the 3rd and 4th parameters are empty in the verify_license() function call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="deactivate_license">5. <strong>deactivate_license()</strong>, API endpoint: <span class="text-primary">/api/deactivate_license</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>This function deactivates the currently activated license from this installation and marks its activation as 'inactive' on the server, It also deletes the local <code>.lic</code> license file. It is useful for clients having a license with a limited number of parallel uses so that they can deactivate the license and re-activate the same license somewhere else.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "License was deactivated successfully."
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->deactivate_license();</b></pre>
					<p><em>You can pass <code>license_code</code> and <code>client_name</code> as 1st and 2nd parameter respectively if you are not using the local <code>.lic</code> license file for storing the license information, otherwise don't pass any parameters.</em></p>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(isset($_POST['something'])){
						$deactivate_response = $api->deactivate_license();
						echo "<p class='text-primary'><strong>".$deactivate_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="external_api_examples.php#deactivate_license" method="POST">
								<input type="hidden" class="form-control" name="something">
							<button type="submit" class="btn btn-primary">Deactivate License</button>
						</form> 
						<br>
						<?php
					}?>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">API Request Body</th>
								<th scope="col">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>product_id</th>
								<td>Product ID, It is already defined in the constructor function of LicenseBoxAPI class.</td>
							</tr>
							<tr>
								<th>license_code</th>
								<td>License code, It is the 1st argument passed in deactivate_license() function during its call.</td>
							</tr>
							<tr>
								<th>client_name</th>
								<td>Client name or Envato username (if envato purchase codes are allowed i.e verify_type is set to 'envato'), It is the 2nd argument passed in deactivate_license() function during its call.</td>
							</tr>
							<tr>
								<th>license_file</th>
								<td>Contents of the local encrypted .lic file will be sent here if the 1st and 2nd parameters are empty in deactivate_license() function call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="check_update">6.1 <strong>check_update()</strong>, API endpoint : <span class="text-primary">/api/check_update</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It returns the API response for <code>/check_update</code> endpoint, It will return the next update information from published versions even if there are more latest updates available. for eg. if the current version is v1.0.0 and other released versions are v1.1.0 and v1.2.0, it will return v1.1.0 update first and once we have updated to v1.1.0 we will get the v1.2.0 update. This helps us to just push files and SQL for the current version and not worry about providing update files for each version to the latest version. This function only returns information about the next update most importantly it provides the <code>update_id</code> which is required for downloading the update using the next mentioned function.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "New version (v5.0.0) available for MyScript!",
  "version": "v5.0.0",
  "summary": "Still on v1.x.x? Upgrade now.",
  "changelog": "This is a Major Version.",
  "update_id": "1f1ab73e022e517ee210",
  "has_sql": false
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->check_update();</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(isset($_POST['versioncheck'])){
						$update_data = $api->check_update();
						echo "<p class='text-primary'><strong>".$update_data['message']."</strong></p>";
						if($update_data['status']){
							echo "<div class='text-primary'>".$update_data['changelog']."</div>"; ?>
							<?php
						}?>
						<br><br>
					<?php }
					else {
						?>
						<form action="external_api_examples.php#check_update" method="POST">
							<input type="hidden" class="form-control" name="versioncheck">
							<button type="submit" class="btn btn-primary">Check For Updates</button>
						</form><br>
					<?php } ?>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">API Request Body</th>
								<th scope="col">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>product_id</th>
								<td>Product ID, It is already defined in the constructor function of LicenseBoxAPI class.</td>
							</tr>
							<tr>
								<th>current_version</th>
								<td>Current version, It is already defined in the constructor function of LicenseBoxAPI class.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="download_update">6.2 <strong>download_update()</strong>, API endpoint: <span class="text-primary">/api/download_update/{type}/{update_id}</span> [POST] <span style="font-size: 15px;vertical-align: center;" class="badge badge-pill badge-success">Updated</span></h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It downloads and extracts/replaces main zip file contents in the application root folder (defined in the constructor function of LicenseBoxAPI class), It can also automatically import update SQL when (database connection details are provided in the function call). download_update() is meant to be used in conjunction with check_update() function as <code>update_id</code> is available in the response of the check_update() function, for the purpose of clean documentation we have separated both the functions. If you have checked the "Make license check compulsory for downloading updates?" option in the product page on LicenseBox admin panel, the local <code>.lic</code> license file will be also sent and only if the license is valid the update download files will be served by the LicenseBox server. If you are not using the local license file for storing license information you can pass the <code>license_code</code> and the <code>client_name</code> manually as 4th and 5th parameter respectively in the function call.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>It returns update files</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->download_update($update_id, $has_sql, $version, null, null, array(
                        'db_host' => '', // Database hostname for update sql auto import
                        'db_user' => '', // Database username for update sql auto import
                        'db_pass' => '', // Database password for update sql auto import
                        'db_name' => '' // Database name for update sql auto import
                      ));</b></pre>
					<p><em>Here <b>$update_id</b> is the unique update id for this version, <b>$has_sql</b> is true if the update has a sql file so that it can be also downloaded and <b>$version</b> is the version name of this next update (all of these values can be taken and passed from the check_update function call's response).</em></p>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					$update_data1 = $api->check_update();
					if(!empty($_POST['update_id'])){
						echo "<div class='text-primary'><progress id=\"prog\" value=\"0\" max=\"100.0\"></progress><br>";
						$api->download_update($_POST['update_id'],$_POST['has_sql'],$_POST['version']); echo "</div>";?>
						<br><br>
					<?php }
					else {
						?>
						<form action="external_api_examples.php#download_update" method="POST">
							<input type="hidden" class="form-control" value="<?php echo $update_data1['update_id']; ?>" name="update_id">
							<input type="hidden" class="form-control" value="<?php echo $update_data1['has_sql']; ?>" name="has_sql">
							<input type="hidden" class="form-control" value="<?php echo $update_data1['version']; ?>" name="version">
							<button type="submit" class="btn btn-primary">Download Update</button>
						</form><br>
					<?php } ?>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">API Request Body</th>
								<th scope="col">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>license_code</th>
								<td>License code, It is the 4th argument passed in download_update() function during its call.</td>
							</tr>
							<tr>
								<th>client_name</th>
								<td>Client name or Envato username (if envato purchase codes are allowed i.e verify_type is set to 'envato'), It is the 5th argument passed in download_update() function during its call.</td>
							</tr>
							<tr>
								<th>license_file</th>
								<td>Contents of the local encrypted .lic file will be sent here if the 4th and 5th parameters are empty in download_update() function call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<br>
			<center>
				<p>Need some help? Contact us at <a href="mailto:support@licensebox.app?subject=LicenseBox Feedback">support@licensebox.app</a> <br> Follow us on twitter at <a href="https://www.twitter.com/CodeMonksHQ">@CodeMonksHQ</a> for future product updates</p>
				<p>Copyright <?php echo date('Y'); ?> <a style="color: inherit;" href="https://licensebox.app" target="_blank" rel="noopener"><b>CodeMonks</b></a>, All rights reserved.</p>
			</center>
		</body>
		</html>
