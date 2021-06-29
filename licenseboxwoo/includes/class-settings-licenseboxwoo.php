<?php
// Create a Menu for Activate connect woocomerce to licenseBox
//add_action( 'admin_menu', 'licensebox_admin_menu' );
function licensebox_admin_menu() {
  add_submenu_page( 'woocommerce', __( 'WooCommerce LicenseBox settings', 'woocommerce' ), __( 'LicenseBox Settings', 'woocommerce' ),  'manage_options', 'license-box', 'licensebox_admin_page_contents', 'dashicons-admin-network', 10 );
}

function licensebox_admin_page_contents() {
  if ( isset( $_POST[ 'licensebox_nonce' ] ) && wp_verify_nonce( $_POST[ 'licensebox_nonce' ], basename( __FILE__ ) ) ) {
    update_option('_licensebox_url',$_POST['_licensebox_url']);
    update_option('_licensebox_api',$_POST['_licensebox_api']);
  }
  
  echo '<h1 id="add-new-user">LicenseBox Settings</h1>';
	echo '<form method="post" class="validate" novalidate="novalidate">';
  wp_nonce_field( basename( __FILE__ ), 'licensebox_nonce' );
  // add your your HTML, PHP, CSS, jQUERY here
  woocommerce_wp_text_input(  array(
    'type'          => 'text', // Add an input number Field
    'id'            => '_licensebox_url',
    'desc_tip'      => 'true',
    'label'         => __( 'LicenseBox URL ', 'woocommerce' ),
    'placeholder'   => __( 'Enter LicenseBox Url.', 'woocommerce' ),
    'description'   => __( 'enter licensebox url YourUrl.com.', 'woocommerce' ),
    'value'         => get_option('_licensebox_url')?get_option('_licensebox_url'):''
  ));

  woocommerce_wp_text_input(  array(
    'type'          => 'text', // Add an input number Field
    'id'            => '_licensebox_api',
    'label'         => __( 'LicenseBox API ', 'woocommerce' ),
    'placeholder'   => __( 'Enter Api.', 'woocommerce' ),
    'description'   => __( 'enter licensebox Api Example 9862E34D4C1530CE5A61.', 'woocommerce' ),
    'desc_tip'      => 'true',
    'value'         => get_option('_licensebox_api')?get_option('_licensebox_api'):''
  ));
  submit_button();

  echo '</form>';
  $api = new LicenseBoxAPI();
  $check_connection_response = $api->check_connection();
	echo "<p class='text-primary'><strong>LicesnseBox Connection Status: </strong>".$check_connection_response['message']."</p>";
}

class WC_Settings_Tab_LicenseBox {

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_licensebox', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_licensebox', __CLASS__ . '::update_settings' );
    }
    
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_licensebox'] = __( 'WooCommerce LicenseBox', 'woocommerce-settings-tab-licesenbox' );
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {
        $api = new LicenseBoxAPI();
        $check_connection_response = $api->check_connection();
	    $desc = "<p class='text-primary'><strong>LicenseBox Connection Status: </strong>".$check_connection_response['message']."</p>";
        $settings = array(
            'section_title' => array(
                'name'     => __( 'LicenseBox Settings', 'woocommerce-settings-tab-licesenbox' ),
                'type'     => 'title',
                'desc'     => $desc,
                'id'       => 'wc_settings_tab_licesenbox_section_title'
            ),
            '_licensebox_url' => array(
                'name' => 'LicenseBox URL',
                'type' => 'text',
                'placeholder'   => __( 'Enter LicenseBox Url.', 'woocommerce' ),
                'desc' => __( 'enter licensebox url YourUrl.com.', 'woocommerce-settings-tab-licesenbox' ),
                'id'   => '_licensebox_url'
            ),
            '_licensebox_api' => array(
                'name' => __( 'LicenseBox API', 'woocommerce-settings-tab-licesenbox' ),
                'type' => 'text',
                'placeholder'   => __( 'Enter Api.', 'woocommerce' ),
                'desc' => __( 'enter licensebox Api Example 9862E34D4C1530CE5A61.', 'woocommerce-settings-tab-licesenbox' ),
                'id'   => '_licensebox_api'
            ),
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_licesenbox_section_end'
            )
        );

        return apply_filters( 'wc_settings_tab_licensebox_settings', $settings );
    }

}

WC_Settings_Tab_LicenseBox::init();
