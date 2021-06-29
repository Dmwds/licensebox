<?php

class LB_Functions {
	
	function __construct() {
        add_action( 'init', array($this, 'debug_function') );
        add_action( 'add_meta_boxes', array($this, 'licensebox_admin_meta_box') );
        add_action( 'woocommerce_email_order_meta', array($this, 'licensebox_add_email_order_meta'), 10, 3 );
        add_action( 'woocommerce_thankyou', array($this, 'order_details_license'), 11 );
        add_action( 'woocommerce_checkout_order_processed', array($this, 'wc_create_license') );
        add_action( 'woocommerce_order_status_completed', array($this, 'wc_update_license') );
    }

    
    function licensebox_admin_meta_box() {
        add_meta_box( 'lincesbox_detail', __('LicenseBox','woocommerce'), array($this, 'licensebox_admin_meta_box_callback'), 'shop_order', 'normal', 'core' );
    }
    function licensebox_admin_meta_box_callback($post) {
        echo '<style>.woocommerce-MyAccount-licensebox {width:100%;text-align:left;}</style>';
        echo '<table class="woocommerce-orders-table woocommerce-MyAccount-licensebox shop_table shop_table_responsive my_account_orders account-orders-table">';
        $this->license_details_table();
        echo '</table>';
    }
    
    function wc_create_license( $order_id ) {
        $order = new WC_Order($order_id);
        $api = new LicenseBoxAPI();

        $product_id = '';
        foreach ( $order->get_items() as $item_id => $item ) {
            $type = $item->get_type();
            if($type=='simple') {
                $prod_id = $item->get_product_id();
            } else {
                $prod_id = $item->get_variation_id();
            }
            if(get_post_meta( $prod_id, '_licensebox_enable', true )) {
                $product_id = get_post_meta( $prod_id, '_licensebox_product_id', true );
                $data =  array(
                    "product_id"  => $product_id,
                    "invoice_number" => (!empty($order_id))?$$order_id:null,
                    "validity" => 0,
                    "client_name" => $order->billing_first_name.' '.$order->billing_last_name,
                    "client_email" => $order->get_billing_email(),
                    //"licensed_ips" => (!empty($data['licensed_ips']))?$data['licensed_ips']:null,
                    //"licensed_domains" => (!empty($data['licensed_domains']))?$data['licensed_domains']:null,
                    //"expiry_date" => (!empty($data['expiry_date']))?$data['expiry_date']:null,
                    //"expiry_days" => (!empty($data['expiry_days']))?$data['expiry_days']:null,
                );
                $create_license_response = $api->create_license($product_id, $data);
                //$get_license_response = $api->get_license($create_license_response['license_code']);
                $order->update_meta_data( $prod_id.'_license', $create_license_response['license_code'] );
    
                $order->add_order_note( __("LicenseBox Product License Generated.") );
            }
        }
    }
    
    function wc_update_license( $order_id ) {
        $order = new WC_Order($order_id);
        $api = new LicenseBoxAPI();
    }

    function licensebox_add_email_order_meta( $order_obj, $sent_to_admin, $plain_text ){
        if ( $plain_text === false ) {
            echo '<h2>License details</h2>';
            echo '<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="1">';
            $this->license_details_table();
            echo '</table>';
        } else {
            echo "License Information\n
            Product Name: Kunaki Woo Basic
            License Key: 786C-BBE4-AD42-95FF
            Status: Active
            Expiry Time: 18 Jan, 2021, 8:00 am";
        }
    }

    function order_details_license( $order_id ) {
        echo '<section class="woocommerce-order-details">';
        echo '<h2 class="woocommerce-order-details__title">License details</h2>';
        echo '<table class="woocommerce-orders-table woocommerce-MyAccount-licensebox shop_table shop_table_responsive my_account_orders account-orders-table">';
        $this->license_details_table();
        echo '</table>';
        echo '</section>';
    }

    function license_details_table() {
        echo '<thead>
			<tr>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Product Name</span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><span class="nobr">License Key</span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span class="nobr">Status</span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr">Expiry Time</span></th>
			</tr>
		</thead>';
		echo '<tbody>';
		echo '<td class="woocommerce-orders-table__cell" data-title="Product Name">Kunaki Woo Basic11</td>';
		echo '<td class="woocommerce-orders-table__cell" data-title="License Key">786C-BBE4-AD42-95FF</td>';
		echo '<td class="woocommerce-orders-table__cell" data-title="Status"><a href="">Active</a></td>';
		echo '<td class="woocommerce-orders-table__cell" data-title="Expiry Time">	18 Jan, 2021, 8:00 am</td>';
		echo '</tbody>';
    }

	function debug_function() {
		if(isset($_GET['debug'])) {
            $api = new LicenseBoxAPI();
            /*
            $check_connection_response = $api->get_product('525');
            print_r($check_connection_response);
            */

            //$check_connection_response = $api->add_product('test API Product');
            //print_r($check_connection_response);
            //$check_connection_response = $api->check_connection();
			//echo "<p class='text-primary'><strong>".$check_connection_response['message']."</strong></p>";

            /*
            $product_id = '5255E8B8';
            $data =  array(
                "product_id"  => $product_id,
                "invoice_number" => '123456',
                "client_name" => 'John Doe',
                "client_email" => 'madmak787@gmail.com',
                //"licensed_ips" => (!empty($data['licensed_ips']))?$data['licensed_ips']:null,
                //"licensed_domains" => (!empty($data['licensed_domains']))?$data['licensed_domains']:null,
                //"expiry_date" => (!empty($data['expiry_date']))?$data['expiry_date']:null,
                //"expiry_days" => (!empty($data['expiry_days']))?$data['expiry_days']:null,
            );
            $create_license_response = $api->create_license($product_id, $data);
            print_r($create_license_response);
            */
            $get_license_response = $api->get_license('C232-E34C-FCE0-1B81');
			print("<pre>".print_r($get_license_response,true)."</pre>");

            exit;
        }
	}

}
new LB_Functions();
?>