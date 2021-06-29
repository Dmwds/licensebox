<?php 
class LB_Functions
{

    function __construct()
    {

        self::set_filters();

    }

    function set_filters()
    {

        add_action('wp_footer', array(
            $this,
            'debug_function'
        ));

        add_action('add_meta_boxes', array(
            $this,
            'licensebox_admin_meta_box'
        ));

        add_action('woocommerce_email_order_meta', array(
            $this,
            'licensebox_add_email_order_meta'
        ) , 10, 3);

        add_action('woocommerce_thankyou', array(
            $this,
            'order_details_license'
        ) , 11);
        
        add_action('woocommerce_checkout_order_processed', array(
            $this,
            'wc_create_license'
        ));
        add_action('woocommerce_order_status_completed', array(
            $this,
            'wc_update_license'
        ));

    }

    function licensebox_admin_meta_box()
    {

        add_meta_box('lincesbox_detail', __('LicenseBox', 'woocommerce') , array(
            $this,
            'licensebox_admin_meta_box_callback'
        ) , 'shop_order', 'normal', 'core');

    }

    function licensebox_admin_meta_box_callback($post)
    {

        echo '<style>.woocommerce-MyAccount-licensebox {width:100%;text-align:left;}</style>';

        echo '<table class="woocommerce-orders-table woocommerce-MyAccount-licensebox shop_table shop_table_responsive my_account_orders account-orders-table">';

        $this->license_details_table($post->ID);

        echo '</table>';

    }

    function wc_update_license($order_id)
    {

        $order = new WC_Order($order_id);
        $api = new LicenseBoxAPI();

        $product_id = '';

        foreach ($order->get_items() as $item)
        {

            $prod_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
            if (get_post_meta($prod_id, '_licensebox_enable', true))
            {
                $lb_product_meta = $order->get_meta('_license_'.$prod_id);
                $lb_product_key = $lb_product_meta['license_code'];
                $api->unblock_license($lb_product_key);
            }

        }
        $order->add_order_note(__("Your license is activated!"));
    }

    function wc_create_license($order_id)
    {

        $order = new WC_Order($order_id);
        $api = new LicenseBoxAPI();

        $product_id = '';

        foreach ($order->get_items() as $item)
        {

            $prod_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

            if (get_post_meta($prod_id, '_licensebox_enable', true))
            {

                $product_id = get_post_meta($prod_id, '_licensebox_product_id', true);
                $expiry_date = date('Y-m-d', strtotime('+1 years'));

                $data = array(

                    "product_id" => $product_id,

                    "invoice_number" => (!empty($order_id)) ? $$order_id : null,

                    "validity" => 0,

                    "client_name" => $order->billing_first_name . ' ' . $order->billing_last_name,

                    "client_email" => $order->get_billing_email() ,

                    "expiry_date" => (!empty($expiry_date)) ? $expiry_date : null,

                );

                $create_license_response = $api->create_license($product_id, $data);
                $create_license_response = $api->create_license($product_id, $data);
                update_post_meta( $order_id, '_license_'.$prod_id, $create_license_response);
                $lb_product_key = $create_license_response['license_code'];
                $api->block_license($lb_product_key);
                $order->add_order_note(__("LicenseBox Product License Generated. Please completed your order to activate your license"));

            }

        }
    }

    function licensebox_add_email_order_meta($order_obj, $sent_to_admin, $plain_text)
    {

        if ($plain_text === false)
        {

            echo '<h2>License details</h2>';

            echo '<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="1">';

            $this->license_details_table($order_id);

            echo '</table>';

        }
        else
        {

            echo "License Information\n

            Product Name: Kunaki Woo Basic monkey

            License Key: 786C-BBE4-AD42-95FF

            Status: Active

            Expiry Time: 18 Jan, 2021, 8:00 am";

        }

    }

    function order_details_license($order_id)
    {

        echo '<section class="woocommerce-order-details">';

        echo '<h2 class="woocommerce-order-details__title">License details</h2>';

        echo '<table class="woocommerce-orders-table woocommerce-MyAccount-licensebox shop_table shop_table_responsive my_account_orders account-orders-table">';

        $this->license_details_table($order_id);

        echo '</table>';

        echo '</section>';

    }

    function license_details_table($order_id)
    {

        $order = new WC_Order($order_id);
        $api = new LicenseBoxAPI();

        echo '<thead>

			<tr>

				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Product Name</span></th>

				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><span class="nobr">License Key</span></th>

				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span class="nobr">Status</span></th>

				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr">Expiry Time</span></th>

			</tr>

		</thead>';

        foreach ($order->get_items() as $item)
        {
            $prod_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
            if (get_post_meta($prod_id, '_licensebox_enable', true))
            {
                $lb_product_meta = $order->get_meta('_license_'.$prod_id);
                $lb_product_key = $lb_product_meta['license_code'];
                $lb_product = $api->get_license($lb_product_key);
                $status = 'Active';
                if($lb_product['is_blocked'] == true){
                    $status = 'Pending';
                }
                echo '<tr>';

                echo '<td class="woocommerce-orders-table__cell" data-title="Product Name ' . $order_id . '">' . 
                $lb_product['product_name'] . '</td>';

                echo '<td class="woocommerce-orders-table__cell" data-title="License Key">' . $lb_product['license_code'] . '</td>';

                echo '<td class="woocommerce-orders-table__cell" data-title="Status">'.$status.'</td>';

                echo '<td class="woocommerce-orders-table__cell" data-title="Expiry Time">' . $lb_product['license_expiry'] . '</td>';

                echo '</tr>';
            }
        }
    }

    function debug_function()
    {

        if (isset($_GET['debug']))
        {
        $order_id = 131;
        $order = new WC_Order($order_id);
        $api = new LicenseBoxAPI();

        $product_id = '';

        foreach ($order->get_items() as $item)
        {

            $prod_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

            if (get_post_meta($prod_id, '_licensebox_enable', true))
            {

                $product_id = get_post_meta($prod_id, '_licensebox_product_id', true);
                $expiry_date = date('Y-m-d', strtotime('+1 years'));

                $data = array(

                    "product_id" => $product_id,

                    "invoice_number" => (!empty($order_id)) ? $$order_id : null,

                    "validity" => 0,

                    "client_name" => $order->billing_first_name . ' ' . $order->billing_last_name,

                    "client_email" => $order->get_billing_email() ,

                    "expiry_date" => (!empty($expiry_date)) ? $expiry_date : null,

                );

                $create_license_response = $api->create_license($product_id, $data);
                update_post_meta( $order_id, '_license_'.$prod_id, $create_license_response);
                // var_dump($create_license_response);
                // var_dump(get_post_meta($order_id, '_license_'.$prod_id, true));
                $order->add_order_note(__("LicenseBox Product License Generated."));

            }

        }

            //print ("<pre>" . print_r($create_license_response) . "</pre>");
            //print ("<pre>" . print_r(get_post_meta($order_id, '_license_'.$prod_id, true)) . "</pre>");

            exit;

        }

    }

}

new LB_Functions();

?>
