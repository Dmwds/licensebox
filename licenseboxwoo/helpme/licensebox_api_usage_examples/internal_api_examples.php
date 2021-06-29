<?php 
require_once 'includes/lb_helper_internal.php'; // Loads the internal helper file
$api = new LicenseBoxAPI(); // We create a new LicenseBoxAPI object as $api
?>
<!DOCTYPE html>
<html>
<head>
	<title>Internal API examples - LicenseBox</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" crossorigin="anonymous"/>
</head>
<body class="bg-light">
		<div class="container">
			<div class="mt-5 py-4 text-center">
				<h2><b><a style="color: inherit;text-decoration: none" href="https://licensebox.app" target="_blank" rel="noopener">LicenseBox<sup><small>v1.4.0</small></sup></a></b> - Internal API & Helper File usage examples</h2>
				<p class="lead pt-3 pb-2 text-justify">These are some usage examples and explanations of functions from the Internal helper file (<strong>for PHP only</strong>) along with their sample code, you can use the functions directly in your PHP applications or if you want you can also directly access the LicenseBox API from your preferred programming language using the info about API endpoints, request headers, request parameters mentioned below.</p>
				<div class="alert alert-warning mb-0">
				<strong>Note</strong>: All API request parameters are compulsory unless specified otherwise. You need an API key of type "<b>internal</b>" to access the internal API.
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
			<h4 class="mb-3" id="check_connection">1. <strong>check_connection()</strong>, API endpoint: <span class="text-primary">/api/check_connection_int</span> [POST]</h4>
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
			<h4 class="mb-3" id="add_product">2. <strong>add_product()</strong>, API endpoint: <span class="text-primary">/api/add_product</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It adds a new product in LicenseBox, If you don't want the system to use a random <code>product_id</code> and want to provide your own custom <code>product_id</code> you can pass it as the 2nd parameter in the function call.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "New product test with ID 3D1D6F03 was successfully added.",
  "product_id": "3D1D6F03"
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->add_product($product_name, $product_id);</b></pre>
					<p><em>Here, If the <b>$product_id</b> is empty or is not passed, the system will generate a random id and use it as the product ID.</em></p>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['product_name'])){
						$add_product_response = $api->add_product($_POST['product_name']);
						echo "<p class='text-primary'><strong>".$add_product_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#add_product" method="POST">
							<div class="form-group">
								<label for="email">Product Name :</label>
								<input type="text" class="form-control" name="product_name">
							</div>
							<button type="submit" class="btn btn-primary">Add Product</button>
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
								<th>product_name</th>
								<td>Product Name, It is the 1st argument passed in add_product() function during its call.</td>
							</tr>
							<tr>
								<th>product_id (optional)</th>
								<td>Product ID, It is the 2nd argument passed in add_product() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="create_license">3. <strong>create_license()</strong>, API endpoint: <span class="text-primary">/api/create_license</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It creates a new license with the provided details in LicenseBox, If you don't want the system to use a random <code>license_code</code> and want to provide your own custom <code>license_code</code> you can pass it as a 3rd parameter in the function call. You can pass the rest of the license information (like client, email, uses, expiry etc.) as an associative array in the 2nd parameter.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "New MyScript license 1234-1234-1234-1234 was successfully added.",
  "license_code": "1234-1234-1234-1234"
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre>
  <b>$data = array(
    'license_type' => 'Regular License',
    'invoice_number' => '#244583',
    'client_name' => 'John Snow',
    'client_email' => 'jon@example.com',
    'comments' => null,
    'licensed_ips' => null,
    'licensed_domains' => null,
    'support_end_date' => null,
    'updates_end_date' => null,
    'expiry_date' => null,
    'expiry_days' => '7',
    'license_uses' => null,
    'license_parallel_uses' => 1
  );
  $api->create_license($product_id, $data, $license_code);</b></pre>
					<p><em>Here, If the <b>$license_code</b> is empty or is not passed, the system will generate a random key and use it as the license code.</em></p>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['product_id2'])&&!empty($_POST['license_code2'])){
						$create_license_response = $api->create_license($_POST['product_id2'],null,$_POST['license_code2']);
						echo "<p class='text-primary'><strong>".$create_license_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#create_license" method="POST">
							<div class="form-group">
								<label for="email">Product ID  :</label>
								<input type="text" class="form-control" name="product_id2">
							</div>
							<div class="form-group">
								<label for="pwd">License Code :</label>
								<input type="text" class="form-control" name="license_code2">
							</div>
							<button type="submit" class="btn btn-primary">Create License</button>
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
								<td>Product ID, It is the 1st argument passed in create_license() function during its call.</td>
							</tr>
							<tr>
								<th>license_code</th>
								<td>License code, It is the 3rd argument passed in create_license() function during its call.</td>
							</tr>
							<tr>
								<th>license_type (optional)</th>
								<td>The type of license (e.g Regular License).</td>
							</tr>
							<tr>
								<th>invoice_number (optional)</th>
								<td>Order ID or Invoice number, useful for binding the license to a invoice number.</td>
							</tr>
							<tr>
								<th>client_name (optional)</th>
								<td>License holder's name or username.</td>
							</tr>
							<tr>
								<th>client_email (optional)</th>
								<td>License holder's email address, useful for sending license expiry emails etc.</td>
							</tr>
							<tr>
								<th>comments (optional)</th>
								<td>Extra field for putting any relevant comments for this license.</td>
							</tr>
							<tr>
								<th>licensed_ips (optional)</th>
								<td>Comma separeted licensed IPs for limiting license to work only on specific IPs.</td>
							</tr>
							<tr>
								<th>licensed_domains (optional)</th>
								<td>Comma separeted licensed domains for limiting license to work only on specific domains.</td>
							</tr>
							<tr>
								<th>support_end_date (optional)</th>
								<td>License support expiry datetime, format is (<b>Y-m-d H:i:s</b>).</td>
							</tr>
							<tr>
								<th>updates_end_date (optional)</th>
								<td>License updates expiry datetime, format is (<b>Y-m-d H:i:s</b>).</td>
							</tr>
							<tr>
								<th>expiry_date (optional)</th>
								<td>License expiration datetime, format is (<b>Y-m-d H:i:s</b>).</td>
							</tr>
							<tr>
								<th>expiry_days (optional)</th>
								<td>License expiration days, the number of days in which the license expires after 1st activation.</td>
							</tr>
							<tr>
								<th>license_uses (optional)</th>
								<td>Total number of license uses allowed.</td>
							</tr>
							<tr>
								<th>license_parallel_uses (optional)</th>
								<td>Total number of parallel/simultaneous license uses allowed.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="edit_license">4. <strong>edit_license()</strong>, API endpoint: <span class="text-primary">/api/edit_license</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It modifies the provided license details in LicenseBox, You must pass the <code>license_code</code> (which you want to modify) as the first parameter and you can pass the rest of the license information (like client, email, uses, expiry etc.) as an associative array in the 2nd parameter.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "License 1234-5678-9101 was successfully edited.",
  "license_code": "1234-5678-9101"
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre>
  <b>$data = array(
    'license_type' => 'Extended License',
    'invoice_number' => '#123456',
    'client_email' => 'stark@example.com'
  );
  $api->edit_license($license_code, $data);</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['invoice_number'])&&!empty($_POST['client_email'])&&!empty($_POST['license_type'])&&!empty($_POST['license_code9'])){
						$data = array(
							'license_type' => $_POST['license_type'],
							'invoice_number' => $_POST['invoice_number'],
							'client_email' => $_POST['client_email']
						);
						$edit_license_response = $api->edit_license($_POST['license_code9'], $data);
						echo "<p class='text-primary'><strong>".$edit_license_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#edit_license" method="POST">
							<div class="form-group">
								<label for="pwd">License Code (to-be-modified) :</label>
								<input type="text" class="form-control" name="license_code9">
							</div>
							<div class="form-group">
								<label for="email">License Type  :</label>
								<input type="text" class="form-control" name="license_type">
							</div>
							<div class="form-group">
								<label for="email">Invoice Number  :</label>
								<input type="text" class="form-control" name="invoice_number">
							</div>
							<div class="form-group">
								<label for="email">Client Email  :</label>
								<input type="email" class="form-control" name="client_email">
							</div>
							<button type="submit" class="btn btn-primary">Edit License</button>
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
								<th>product_id (optional)</th>
								<td>Product ID, It is the ID of the product this license belongs to.</td>
							</tr>
							<tr>
								<th>license_code</th>
								<td>License code, Which is being edited.</td>
							</tr>
							<tr>
								<th>license_type (optional)</th>
								<td>The type of license (e.g Regular License).</td>
							</tr>
							<tr>
								<th>invoice_number (optional)</th>
								<td>Order ID or Invoice number, useful for binding the license to a invoice number.</td>
							</tr>
							<tr>
								<th>client_name (optional)</th>
								<td>License holder's name or username.</td>
							</tr>
							<tr>
								<th>client_email (optional)</th>
								<td>License holder's email address, useful for sending license expiry emails etc.</td>
							</tr>
							<tr>
								<th>comments (optional)</th>
								<td>Extra field for putting any relevant comments for this license.</td>
							</tr>
							<tr>
								<th>licensed_ips (optional)</th>
								<td>Comma separeted licensed IPs for limiting license to work only on specific IPs.</td>
							</tr>
							<tr>
								<th>licensed_domains (optional)</th>
								<td>Comma separeted licensed domains for limiting license to work only on specific domains.</td>
							</tr>
							<tr>
								<th>support_end_date (optional)</th>
								<td>License support expiry datetime, format is (<b>Y-m-d H:i:s</b>).</td>
							</tr>
							<tr>
								<th>updates_end_date (optional)</th>
								<td>License updates expiry datetime, format is (<b>Y-m-d H:i:s</b>).</td>
							</tr>
							<tr>
								<th>expiry_date (optional)</th>
								<td>License expiration datetime, format is (<b>Y-m-d H:i:s</b>).</td>
							</tr>
							<tr>
								<th>expiry_days (optional)</th>
								<td>License expiration days, the number of days in which the license expires after 1st activation.</td>
							</tr>
							<tr>
								<th>license_uses (optional)</th>
								<td>Total number of license uses allowed.</td>
							</tr>
							<tr>
								<th>license_parallel_uses (optional)</th>
								<td>Total number of parallel/simultaneous license uses allowed.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
				<h4 class="mb-3" id="get_product">5. <strong>get_product()</strong>, API endpoint: <span class="text-primary">/api/get_product</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It returns all the relevant information about the specified product based on the <code>product_id</code>.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "product_id": "BE33582A",
  "envato_item_id": null,
  "product_name": "MyScript",
  "product_details": "test",
  "latest_version": "v5.0.0",
  "latest_version_release_date": "2020-05-16",
  "is_product_active": true,
  "requires_license_for_downloading_updates": true
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->get_product($product_id);</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['product_id3'])){
						$get_product_response = $api->get_product($_POST['product_id3']);
						print("<pre>".print_r($get_product_response,true)."</pre>"); ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#get_product" method="POST">
							<div class="form-group">
								<label for="email">Product ID :</label>
								<input type="text" class="form-control" name="product_id3">
							</div>
							<button type="submit" class="btn btn-primary">Get Product Information</button>
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
								<td>Product ID, It is the 1st argument passed in get_product() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="get_license">6. <strong>get_license()</strong>, API endpoint: <span class="text-primary">/api/get_license</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It returns all the relevant information about the specified license code.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "license_code": "85B7-172F-9082-AFAA",
  "product_id": "B582AE33",
  "product_name": "MyScript",
  "license_type": null,
  "client_name": null,
  "client_email": null,
  "invoice_number": null,
  "license_comments": null,
  "licensed_ips": null,
  "licensed_domains": null,
  "uses": null,
  "uses_left": null,
  "parallel_uses": "1",
  "parallel_uses_left": 1,
  "license_expiry": null,
  "support_expiry": null,
  "updates_expiry": null,
  "date_modified": "2020-05-16 10:12:57",
  "is_blocked": false,
  "is_a_envato_purchase_code": false,
  "is_valid_for_future_activations": true
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->get_license($license_code);</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['license_code4'])){
						$get_license_response = $api->get_license($_POST['license_code4']);
						print("<pre>".print_r($get_license_response,true)."</pre>");?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#get_license" method="POST">
							<div class="form-group">
								<label for="email">License Code :</label>
								<input type="text" class="form-control" name="license_code4">
							</div>
							<button type="submit" class="btn btn-primary">Get License Information</button>
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
								<th>license_code</th>
								<td>License Code, It is the 1st argument passed in get_license() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="delete_license">7. <strong>delete_license()</strong>, API endpoint: <span class="text-primary">/api/delete_license</span> [POST] <span style="font-size: 15px;vertical-align: center;" class="badge badge-pill badge-primary">New</span></h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It deletes the provided license and it's activation logs in LicenseBox.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "License BCB9-9C03-E535-A947 was successfully deleted."
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->delete_license($license_code);</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['license_code_id8'])){
						$delete_license_response = $api->delete_license($_POST['license_code_id8']);
						echo "<p class='text-primary'><strong>".$delete_license_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#delete_license" method="POST">
							<div class="form-group">
								<label for="email">License Code :</label>
								<input type="text" class="form-control" name="license_code_id8">
							</div>
							<button type="submit" class="btn btn-primary">Delete License</button>
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
								<th>license_code</th>
								<td>License Code, It is the 1st argument passed in delete_license() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="mark_product_active">8. <strong>mark_product_active()</strong>, API endpoint: <span class="text-primary">/api/mark_product_active</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It marks the provided product as active in LicenseBox.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "Product MyScript marked as active."
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->mark_product_active($product_id);</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['product_id5'])){
						$mark_product_active_response = $api->mark_product_active($_POST['product_id5']);
						echo "<p class='text-primary'><strong>".$mark_product_active_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#mark_product_active" method="POST">
							<div class="form-group">
								<label for="email">Product ID :</label>
								<input type="text" class="form-control" name="product_id5">
							</div>
							<button type="submit" class="btn btn-primary">Mark Product Active</button>
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
								<td>Product ID, It is the 1st argument passed in mark_product_active() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<h4 class="mb-3" id="mark_product_inactive">9. <strong>mark_product_inactive()</strong>, API endpoint: <span class="text-primary">/api/mark_product_inactive</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It marks the provided product as inactive in LicenseBox.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "Product MyScript marked as inactive."
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->mark_product_inactive($product_id);</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['product_id6'])){
						$mark_product_inactive_response = $api->mark_product_inactive($_POST['product_id6']);
						echo "<p class='text-primary'><strong>".$mark_product_inactive_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#mark_product_inactive" method="POST">
							<div class="form-group">
								<label for="email">Product ID :</label>
								<input type="text" class="form-control" name="product_id6">
							</div>
							<button type="submit" class="btn btn-primary">Mark Product Inactive</button>
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
								<td>Product ID, It is the 1st argument passed in mark_product_inactive() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			 <h4 class="mb-3" id="block_license">10. <strong>block_license()</strong>, API endpoint: <span class="text-primary">/api/block_license</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It blocks the provided license code in LicenseBox.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "License 85B7-172F-9082-AFAA was successfully blocked."
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->block_license($license_code);</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['license_code7'])){
						$block_license_response = $api->block_license($_POST['license_code7']);
						echo "<p class='text-primary'><strong>".$block_license_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#block_license" method="POST">
							<div class="form-group">
								<label for="email">License Code :</label>
								<input type="text" class="form-control" name="license_code7">
							</div>
							<button type="submit" class="btn btn-primary">Block License</button>
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
								<th>license_code</th>
								<td>License Code, It is the 1st argument passed in block_license() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			 <h4 class="mb-3" id="unblock_license">11. <strong>unblock_license()</strong>, API endpoint: <span class="text-primary">/api/unblock_license</span> [POST]</h4>
			<div class="card">
				<div class="card-body">
					<h5 class="mb-2">Description: </h5>
					<p>It unblocks the provided license code in LicenseBox.</p>
					<h5 class="mb-2">Sample API Response: </h5>
<pre>{
  "status": true,
  "message": "License 85B7-172F-9082-AFAA was successfully unblocked."
}</pre>
					<h5 class="mb-2">Usage and arguments: </h5>
					<pre><b>$api->unblock_license($license_code);</b></pre>
					<h5 class="mb-2">Live Example: </h5>
					<?php
					if(!empty($_POST['license_code8'])){
						$block_license_response = $api->unblock_license($_POST['license_code8']);
						echo "<p class='text-primary'><strong>".$block_license_response['message']."</strong></p>"; ?>
						<br><br>
					<?php }
					else {
						?>
						<form action="internal_api_examples.php#unblock_license" method="POST">
							<div class="form-group">
								<label for="email">License Code :</label>
								<input type="text" class="form-control" name="license_code8">
							</div>
							<button type="submit" class="btn btn-primary">Unblock License</button>
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
								<th>license_code</th>
								<td>License Code, It is the 1st argument passed in unblock_license() function during its call.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><br>
			<center>
				<p>Need some help? Contact us at <a href="mailto:support@licensebox.app?subject=LicenseBox Feedback">support@licensebox.app</a> <br> Follow us on twitter at <a href="https://www.twitter.com/CodeMonksHQ">@CodeMonksHQ</a> for future product updates</p>
				<p>Copyright <?php echo date('Y'); ?> <a style="color: inherit;" href="https://licensebox.app" target="_blank" rel="noopener"><b>CodeMonks</b></a>, All rights reserved.</p>
			</center>
		</body>
		</html>
