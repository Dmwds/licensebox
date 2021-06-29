<?php if(count(get_included_files()) == 1) exit("No direct script access allowed");

require_once(ABSPATH . 'wp-admin/includes/file.php');

define("LB_API_DEBUG", false);

define("LB_TEXT_CONNECTION_FAILED", 'Server is unavailable at the moment, please try again.');
define("LB_TEXT_INVALID_RESPONSE", 'Server returned an invalid response, please contact support.');
define("LB_TEXT_VERIFIED_RESPONSE", 'Verified! Thanks for purchasing.');
define("LB_TEXT_PREPARING_MAIN_DOWNLOAD", 'Preparing to download main update...');
define("LB_TEXT_MAIN_UPDATE_SIZE", 'Main Update size:');
define("LB_TEXT_DONT_REFRESH", '(Please do not refresh the page).');
define("LB_TEXT_DOWNLOADING_MAIN", 'Downloading main update...');
define("LB_TEXT_UPDATE_PERIOD_EXPIRED", 'Your update period has ended or your license is invalid, please contact support.');
define("LB_TEXT_UPDATE_PATH_ERROR", 'Folder does not have write permission or the update file path could not be resolved, please contact support.');
define("LB_TEXT_MAIN_UPDATE_DONE", 'Main update files downloaded and extracted.');
define("LB_TEXT_UPDATE_EXTRACTION_ERROR", 'Update zip extraction failed.');
define("LB_TEXT_PREPARING_SQL_DOWNLOAD", 'Preparing to download SQL update...');
define("LB_TEXT_SQL_UPDATE_SIZE", 'SQL Update size:');
define("LB_TEXT_DOWNLOADING_SQL", 'Downloading SQL update...');
define("LB_TEXT_SQL_UPDATE_DONE", 'SQL update files downloaded.');
define("LB_TEXT_UPDATE_WITH_SQL_DONE", 'Application was successfully updated, please import the downloaded SQL file in your database manually.');
define("LB_TEXT_UPDATE_WITHOUT_SQL_DONE", 'Application was successfully updated, there were no SQL updates.');

class LicenseBoxAPIxt{

	private $product_id;
	private $api_url;
	private $api_key;
	private $api_language;
	private $current_version;
	private $verify_type;
	private $verification_period;
	private $current_path;
	private $root_path;
	private $license_file;

	public function __construct(){ 
		$this->product_id = '016E504E';
		$this->api_url = 'https://wp-updaters.com/';
		$this->api_key = '72A44D7A3F5D70835757';
		$this->api_language = 'english';
		$this->current_version = '1.0.0';
		$this->verify_type = 'non_envato';
		$this->verification_period = 7;
		$this->current_path = realpath(__DIR__);
		$this->root_path = realpath($this->current_path.'/../..');
		$this->license_file = $this->current_path.'/.lic';
	}

	public function check_local_license_exist(){
		return is_file($this->license_file);
	}

	public function get_current_version(){
		return $this->current_version;
	}

	private function init_wp_fs(){
		global $wp_filesystem;
		if(false === ($credentials = request_filesystem_credentials(''))){
			return false;
		}
		if(!WP_Filesystem($credentials)){ 
			request_filesystem_credentials('');
			return false;
		}
		return true;
	}

	private function write_wp_fs($file_path, $content){
		global $wp_filesystem;
		$save_file_to = $file_path;
		if($this->init_wp_fs()){    
			if($wp_filesystem->put_contents($save_file_to, $content, FS_CHMOD_FILE)){
				return true;
			}
			else{
				return false;
			}
		}
	}

	private function read_wp_fs($file_path){
		global $wp_filesystem;
		if($this->init_wp_fs()){    
			return $wp_filesystem->get_contents($file_path);
		}
	}

	private function call_api($method, $url, $data){
		$wp_args = array('body' => $data);	
		$wp_args['method'] = $method;

		$this_url = site_url();
		$this_ip = getenv('SERVER_ADDR')?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());

		$wp_args['headers'] = array(
			'Content-Type' => 'application/json', 
			'LB-API-KEY' => $this->api_key, 
			'LB-URL' => $this_url, 
			'LB-IP' => $this_ip, 
			'LB-LANG' => $this->api_language
		);
		$wp_args['timeout'] = 30;

		$result = wp_remote_request($url, $wp_args);

		if(is_wp_error($result)&&!LB_API_DEBUG){
			$rs = array(
				'status' => FALSE, 
				'message' => LB_TEXT_CONNECTION_FAILED
			);
			return json_encode($rs);
		}
		$http_status = $result['response']['code'];
		if($http_status != 200){
			if(LB_API_DEBUG){
				$temp_decode = json_decode($result['body'], true);
				$rs = array(
					'status' => FALSE, 
					'message' => ((!empty($temp_decode['error']))?
						$temp_decode['error']:
						$temp_decode['message'])
				);
				return json_encode($rs);
			}else{
				$rs = array(
					'status' => FALSE, 
					'message' => LB_TEXT_INVALID_RESPONSE
				);
				return json_encode($rs);
			}
		}
		return $result['body'];
	}

	public function check_connection(){
		$data_array =  array();
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/check_connection_ext', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function get_latest_version(){
		$data_array =  array(
			"product_id"  => $this->product_id
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/latest_version', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function activate_license($license, $client, $create_lic = true){
		$data_array =  array(
			"product_id"  => $this->product_id,
			"license_code" => $license,
			"client_name" => $client,
			"verify_type" => $this->verify_type
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/activate_license', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		if(!empty($create_lic)){
			if($response['status']){
				$licfile = trim($response['lic_response']);
				$this->write_wp_fs($this->license_file, $licfile);
			}else{
				if(is_writeable($this->license_file)){
					unlink($this->license_file);
				}
			}
		}
		return $response;
	}

	public function verify_license($time_based_check = false, $license = false, $client = false){
		if(!empty($license)&&!empty($client)){
			$data_array =  array(
				"product_id"  => $this->product_id,
				"license_file" => null,
				"license_code" => $license,
				"client_name" => $client
			);
		}else{
			if(is_file($this->license_file)){
				$data_array =  array(
					"product_id"  => $this->product_id,
					"license_file" => $this->read_wp_fs($this->license_file),
					"license_code" => null,
					"client_name" => null
				);
			}else{
				$data_array =  array();
			}
		} 
		$res = array('status' => TRUE, 'message' => LB_TEXT_VERIFIED_RESPONSE);
		if($time_based_check && $this->verification_period > 0){
			ob_start();
			if(session_status() == PHP_SESSION_NONE){
				session_start();
			}
			$type = (int) $this->verification_period;
			$today = date('d-m-Y');
			if(empty($_SESSION["4f3b86236ace2aa"])){
				$_SESSION["4f3b86236ace2aa"] = '00-00-0000';
			}
			if($type == 1){
				$type_text = '1 day';
			}elseif($type == 3){
				$type_text = '3 days';
			}elseif($type == 7){
				$type_text = '1 week';
			}elseif($type == 30){
				$type_text = '1 month';
			}elseif($type == 90){
				$type_text = '3 months';
			}elseif($type == 365) {
				$type_text = '1 year';
			}else{
				$type_text = $type.' days';
			}
			if(strtotime($today) >= strtotime($_SESSION["4f3b86236ace2aa"])){
				$get_data = $this->call_api(
					'POST',
					$this->api_url.'api/verify_license', 
					json_encode($data_array)
				);
				$res = json_decode($get_data, true);
				if($res['status']==true){
					$tomo = date('d-m-Y', strtotime($today. ' + '.$type_text));
					$_SESSION["4f3b86236ace2aa"] = $tomo;
				}
			}
			ob_end_clean();
		}else{
			$get_data = $this->call_api(
				'POST',
				$this->api_url.'api/verify_license', 
				json_encode($data_array)
			);
			$res = json_decode($get_data, true);
		}
		return $res;
	}

	public function deactivate_license($license = false, $client = false){
		if(!empty($license)&&!empty($client)){
			$data_array =  array(
				"product_id"  => $this->product_id,
				"license_file" => null,
				"license_code" => $license,
				"client_name" => $client
			);
		}else{
			if(is_file($this->license_file)){
				$data_array =  array(
					"product_id"  => $this->product_id,
					"license_file" => $this->read_wp_fs($this->license_file),
					"license_code" => null,
					"client_name" => null
				);
			}else{
				$data_array =  array();
			}
		}
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/deactivate_license', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		if($response['status']){
			if(is_writeable($this->license_file)){
				unlink($this->license_file);
			}
		}
		return $response;
	}

	public function check_update(){
		$data_array =  array(
			"product_id"  => $this->product_id,
			"current_version" => $this->current_version
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/check_update', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function download_update($update_id, $type, $version, $license = false, $client = false){ 
		if(!empty($license)&&!empty($client)){
			$data_array =  array(
				"license_file" => null,
				"license_code" => $license,
				"client_name" => $client
			);
		}else{
			if(is_file($this->license_file)){
				$data_array =  array(
					"license_file" => $this->read_wp_fs($this->license_file),
					"license_code" => null,
					"client_name" => null
				);
			}else{
				$data_array =  array();
			}
		}
		ob_end_flush(); 
		ob_implicit_flush(true);  
		$version = str_replace(".", "_", $version);
		ob_start();
		$source_size = $this->api_url."api/get_update_size/main/".$update_id; 
		echo LB_TEXT_PREPARING_MAIN_DOWNLOAD."<br>";
		ob_flush();
		echo LB_TEXT_MAIN_UPDATE_SIZE." ".$this->get_remote_filesize($source_size)." ".LB_TEXT_DONT_REFRESH."<br>";
		ob_flush();
		$temp_progress = '';
		$source = $this->api_url."api/download_update/main/".$update_id; 
		$wp_args = array('body' => json_encode($data_array));	
		$wp_args['method'] = 'POST';
		$this_url = site_url();
		$this_ip = getenv('SERVER_ADDR')?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		$wp_args['headers'] = array(
			'Content-Type' => 'application/json', 
			'LB-API-KEY' => $this->api_key, 
			'LB-URL' => $this_url, 
			'LB-IP' => $this_ip, 
			'LB-LANG' => $this->api_language
		);
		$wp_args['timeout'] = 30;
		echo LB_TEXT_DOWNLOADING_MAIN."<br>";
		ob_flush();
		$result = wp_remote_request($source, $wp_args);
		if(is_wp_error($result)){
			exit("<br>".LB_TEXT_CONNECTION_FAILED);
		}
		$data = $result['body'];
		$http_status = $result['response']['code'];
		if($http_status != 200){
			if($http_status == 401){
				exit("<br>".LB_TEXT_UPDATE_PERIOD_EXPIRED);
			}else{
				exit("<br>".LB_TEXT_INVALID_RESPONSE);
			}
		}
		$destination = $this->root_path."/update_main_".$version.".zip"; 
		$file = $this->write_wp_fs($destination, $data);
		if(!$file){
			exit("<br>".LB_TEXT_UPDATE_PATH_ERROR);
		}
		ob_flush();
		$zip = new ZipArchive;
		$res = $zip->open($destination);
		if($res === TRUE){
			$zip->extractTo($this->root_path."/"); 
			$zip->close();
			unlink($destination);
			echo LB_TEXT_MAIN_UPDATE_DONE."<br><br>";
			ob_flush();
		}else{
			echo LB_TEXT_UPDATE_EXTRACTION_ERROR."<br><br>";
			ob_flush();
		}
		if($type == true){
			$source_size = $this->api_url."api/get_update_size/sql/".$update_id; 
			echo LB_TEXT_PREPARING_SQL_DOWNLOAD."<br>";
			ob_flush();
			echo LB_TEXT_SQL_UPDATE_SIZE." ".$this->get_remote_filesize($source_size)." ".LB_TEXT_DONT_REFRESH."<br>";
			ob_flush();
			$temp_progress = '';
			$source = $this->api_url."api/download_update/sql/".$update_id;
			$wp_args = array('body' => json_encode($data_array));	
			$wp_args['method'] = 'POST';
			$this_url = site_url();
			$this_ip = getenv('SERVER_ADDR')?:
				$this->get_ip_from_third_party()?:
				gethostbyname(gethostname());
			$wp_args['headers'] = array(
				'Content-Type' => 'application/json', 
				'LB-API-KEY' => $this->api_key, 
				'LB-URL' => $this_url, 
				'LB-IP' => $this_ip, 
				'LB-LANG' => $this->api_language
			);
			$wp_args['timeout'] = 30;
			echo LB_TEXT_DOWNLOADING_SQL."<br>";
			ob_flush();
			$result = wp_remote_request($source, $wp_args);
			if(is_wp_error($result)){
				exit(LB_TEXT_CONNECTION_FAILED);
			}
			$data = $result['body'];
			$http_status = $result['response']['code'];
			if($http_status!=200){
				exit(LB_TEXT_INVALID_RESPONSE);
			}
			$destination = $this->root_path."/update_sql_".$version.".sql"; 
			$file = $this->write_wp_fs($destination, $data);
			if(!$file){
				exit(LB_TEXT_UPDATE_PATH_ERROR);
			}
			echo LB_TEXT_SQL_UPDATE_DONE."<br><br>";
			echo LB_TEXT_UPDATE_WITH_SQL_DONE;
			ob_flush();
		}else{
			echo LB_TEXT_UPDATE_WITHOUT_SQL_DONE;
			ob_flush();
		}
		ob_end_flush(); 
	}

	private function get_ip_from_third_party(){
		$wp_args = array('method' => 'GET');	
		$wp_args['timeout'] = 30;
		$result = wp_remote_request('http://ipecho.net/plain', $wp_args);
		if(is_wp_error($result)){
			return false;
		}
		return $result['body'];
	}

	private function get_remote_filesize($url){
		$wp_args = array('method' => 'HEAD');	
		$this_url = site_url();
		$this_ip = getenv('SERVER_ADDR')?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		$wp_args['headers'] = array(
			'Content-Type' => 'application/json', 
			'LB-API-KEY' => $this->api_key, 
			'LB-URL' => $this_url, 
			'LB-IP' => $this_ip, 
			'LB-LANG' => $this->api_language
		);
		$wp_args['timeout'] = 30;
		$result = wp_remote_request($url, $wp_args);
		if(is_wp_error($result)){
			return false;
		}
		$filesize = $result['headers']['content-length'];
		if ($filesize){
			switch ($filesize){
				case $filesize < 1024:
					$size = $filesize .' B'; break;
				case $filesize < 1048576:
					$size = round($filesize / 1024, 2) .' KB'; break;
				case $filesize < 1073741824:
					$size = round($filesize / 1048576, 2) . ' MB'; break;
				case $filesize < 1099511627776:
					$size = round($filesize / 1073741824, 2) . ' GB'; break;
			}
			return $size; 
		}
	}
}