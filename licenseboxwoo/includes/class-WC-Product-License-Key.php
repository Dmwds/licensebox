<?php

/**
 * "license_code" WooCommerce product type class.

 */
class WC_Product_License_code extends WC_Product_Simple
{
    /**
     * Product constat TYPE.
     */
    const TYPE = 'license_code';
    /**
     * Default constructor.
     * @since 1.0.0
     *
     * @param mixed $product
     */
    public function __construct( $product )
    {
        $this->product_type = self::TYPE;
        parent::__construct( $product );
    }
    /**
     * Returns product type.
     * @since 1.0.0
     *
     * @return string
     */
    public function get_type()
    {
        return $this->product_type;
    }
}