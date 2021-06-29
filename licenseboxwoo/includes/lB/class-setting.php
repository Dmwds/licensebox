<?php


class ActivateLicense {
	private $activate_license_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'activate_license_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'activate_license_page_init' ) );
		add_action( 'update_option_activate_license_option_name', array( $this, 'handle_license_checking' ), 10, 3 );
	}


	public function handle_license_checking( $old_value, $value, $option ) {

		$license_box_api = new LicenseBoxAPI();
		$posted_data     = wp_unslash( $_POST );
		$license_data    = isset( $posted_data['activate_license_option_name'] ) ? $posted_data['activate_license_option_name'] : array();
		$license_code    = isset( $license_data['license_code'] ) ? $license_data['license_code'] : '';
		$client_name     = isset( $license_data['client_name'] ) ? $license_data['client_name'] : '';
		$api_response    = $license_box_api->activate_license( $license_code, $client_name );
		$error_type      = isset( $api_response['status'] ) && $api_response['status'] ? 'success' : 'error';
		$error_message   = isset( $api_response['message'] ) ? $api_response['message'] : '';

		add_settings_error( 'activate_license_option_group', 202, $error_message, $error_type );
	}


	public function activate_license_add_plugin_page() {
		add_menu_page(
			'Activate License', // page_title
			'Activate License', // menu_title
			'manage_options', // capability
			'activate-license', // menu_slug
			array( $this, 'activate_license_create_admin_page' ), // function
			'dashicons-admin-generic', // icon_url
			5 // position
		);
	}


	public function activate_license_create_admin_page() {
		$this->activate_license_options = get_option( 'activate_license_option_name' );
		?>
        <div class="wrap">
            <h2>Automatic Updates</h2>
            <p>Please enter your License Key. AAn activate License key is needed for automatic updates and <a
                        title="License Keys" href="https://dmwds.com/My-account/">Support </a>.</p>
			<?php settings_errors(); ?>

            <form method="post" action="options.php">
				<?php
				do_settings_sections( 'activate-license-admin' );
				settings_fields( 'activate_license_option_group' );

				submit_button();
				?>
            </form>
            <p> you can find your license keys and manage your active sites <a title="License Keys"
                                                                               href="https://dmwds.com/My-account/">Dmwds.com </a>.
            </p>

        </div>
	<?php }


	public function activate_license_page_init() {
		register_setting(
			'activate_license_option_group', // option_group
			'activate_license_option_name', // option_name
			array( $this, 'activate_license_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'activate_license_setting_section', // id
			'Settings', // title
			array( $this, 'activate_license_section_info' ), // callback
			'activate-license-admin' // page
		);

		add_settings_field(
			'license_code', // id
			'license code', // title
			array( $this, 'license_code_callback' ), // callback
			'activate-license-admin', // page
			'activate_license_setting_section' // section
		);


		add_settings_field(
			'license_action', // id
			'License Status', // title
			array( $this, 'render_license_action' ), // callback
			'activate-license-admin', // page
			'activate_license_setting_section' // section
		);
	}


	public function activate_license_sanitize( $input ) {
		$sanitary_values = array();
		if ( isset( $input['license_code'] ) ) {
			$sanitary_values['license_code'] = sanitize_text_field( $input['license_code'] );
		}

		if ( isset( $input['client_name'] ) ) {
			$sanitary_values['client_name'] = sanitize_text_field( $input['client_name'] );
		}

		if ( isset( $input['license_action'] ) ) {
			$sanitary_values['license_action'] = sanitize_text_field( $input['license_action'] );
		}

		return $sanitary_values;
	}

	public function activate_license_section_info() {

	}

	public function license_code_callback() {
		printf(
			'<input class="regular-text" type="text" name="activate_license_option_name[license_code]" id="license_code" value="%s">',
			isset( $this->activate_license_options['license_code'] ) ? esc_attr( $this->activate_license_options['license_code'] ) : ''
		);
	}


	public function render_license_action() {

		$license_box_api  = new LicenseBoxAPI();
		$license_response = $license_box_api->verify_license( false, ntplugin_license_data( 'license_code' ), ntplugin_license_data( 'client_name' ) );
		$license_status   = isset( $license_response['status'] ) && $license_response['status'] ? 'activated' : 'deactivated';

		printf( '<p class="button button1 description"><i>%s</i></p><br>', ucfirst( $license_status ) );
	}
}

if ( is_admin() ) {
	$activate_license = new ActivateLicense();
}


if ( ! function_exists( 'ntplugin_license_data' ) ) {
	/**
	 * Return license data
	 *
	 * @param string $ret
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	function ntplugin_license_data( $ret = 'license_code', $default = '' ) {

		$license_data = get_option( 'activate_license_option_name', array() );
		$license_data = empty( $license_data ) && ! is_array( $license_data ) ? array() : $license_data;

		return isset( $license_data[ $ret ] ) ? $license_data[ $ret ] : $default;
	}
}



/* 
 * Retrieve this value with:
 * $activate_license_options = get_option( 'activate_license_option_name' ); // Array of All Options
 * $license_code = $activate_license_options['license_code']; // license_code
 * $client_name = $activate_license_options['client_name']; // client_name
 */