<?php
/**
 * Plugin Name: Single Product Purchase Only
 * Plugin URI: https://jhorman-dev.netlify.app/
 * Description: Allows only one product in the cart at a time. Automatically removes any existing product when a new product is added.
 * Version: 1.0.0
 * Author: Jhorman Nieto P
 * Author URI: https://jhorman-dev.netlify.app/
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Ensure only one product is in the cart at a time
add_action('woocommerce_before_calculate_totals', 'single_product_cart_enforce', 10, 1);
function single_product_cart_enforce($cart) {
    if (is_admin() || wp_doing_ajax()) {
        return; // Avoid affecting admin or AJAX operations.
    }

    // If the cart contains more than one product, keep only the most recently added
    if ($cart->get_cart_contents_count() > 1) {
        $last_item = end($cart->get_cart());

        // Clear the cart except the last item
        WC()->cart->empty_cart();
        WC()->cart->add_to_cart($last_item['product_id'], $last_item['quantity'], $last_item['variation_id'], $last_item['variation']);
    }
}

// Validate cart when adding a product
add_filter('woocommerce_add_to_cart_validation', 'single_product_cart_validate', 10, 3);
function single_product_cart_validate($passed, $product_id, $quantity) {
    // Clear the cart before adding a new product
    if (WC()->cart->get_cart_contents_count() > 0) {
        WC()->cart->empty_cart();
    }
    return $passed;
}
