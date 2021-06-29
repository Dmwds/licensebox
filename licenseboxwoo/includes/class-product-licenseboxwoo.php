<?php

// Add a custom product setting tab to edit product pages options FOR SIMPLE PRODUCTS only
add_filter( 'woocommerce_product_data_tabs', 'licensebox_new_product_data_tab', 2, 1 );
function licensebox_new_product_data_tab( $tabs ) {
    $tabs['licensebox'] = array(
        'label' => __( 'LicenseBox', 'woocommerce' ),
        'target' => 'licensebox_product_data', // <== to be used in the <div> class of the content
        'class' => array('show_if_simple','show_if_variable'), // or 'hide_if_simple' or 'show_if_variable'…
    );

    return $tabs;
}

// Add/display custom Fields in the custom product settings tab
add_action( 'woocommerce_product_data_panels', 'add_custom_fields_product_options_licensebox', 2,1 );
function add_custom_fields_product_options_licensebox() {
    global $post;

    echo '<div id="licensebox_product_data" class="panel woocommerce_options_panel hidden">'; // <== Here we use the target attribute
    
    woocommerce_wp_checkbox( array( 
        'id'            => '_licensebox_enable', 
        'label'         => __('Enable LicenseBox', 'woocommerce' ), 
        'description'   => __( '', 'woocommerce' ),
        //'value'         => get_post_meta( $post->ID, '_licensebox_enable', true ), 
    ));
    
    woocommerce_wp_text_input(  array(
        'type'          => 'text', // Add an input number Field
        'id'            => '_licensebox_product_id',
        'label'         => __( 'LicenseBox Product ID ', 'woocommerce' ),
        'placeholder'   => __( 'Enter Product ID.', 'woocommerce' ),
        'description'   => __( 'enter the product id of licensebox product.', 'woocommerce' ),
        'desc_tip'      => 'true',
    ) );

    echo '</div>';
}

// Save the data value from the custom fields for simple products
add_action( 'woocommerce_process_product_meta_simple', 'save_custom_fields_product_options_licensebox', 2, 1 );
function save_custom_fields_product_options_licensebox( $post_id ) {
    $lb_enable = $_POST['_licensebox_enable'];
    if( ! empty( $lb_enable ) ) {
        update_post_meta( $post_id, '_licensebox_enable', esc_attr( $lb_enable ) );
    }
    $product_id = $_POST['_licensebox_product_id'];
    if( ! empty( $product_id ) ) {
        update_post_meta( $post_id, '_licensebox_product_id', esc_attr( $product_id ) );
    }
}