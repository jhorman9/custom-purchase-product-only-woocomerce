<?php
/**
 * Plugin Name: Checkout Cart Warning
 * Description: Al momento de salirse del checkout inmediatamente le quitará el producto del carrito.
 * Version: 1.1
 * Author: Jhorman Nieto P
 * Author URI: https://jhorman-dev.netlify.app/
 * License: GPL2
 */

// Evitar el acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Función para vaciar el carrito si el usuario sale del checkout o de la página `complete-payment`
add_action('template_redirect', function() {
    // Verifica si el usuario está en la página de checkout o en `complete-payment`
    if (is_checkout() && !is_wc_endpoint_url('order-received') && !is_page('complete-payment')) {
        // Usamos una cookie para marcar que el usuario estuvo en el checkout
        setcookie('in_checkout', '1', time() + 3600, '/');
    } elseif (isset($_COOKIE['in_checkout']) && !is_checkout() && !is_page('complete-payment')) {
        // Si sale del checkout o la página `complete-payment`, vaciamos el carrito
        WC()->cart->empty_cart();
        // Eliminamos la cookie
        setcookie('in_checkout', '', time() - 3600, '/');
    }
});
