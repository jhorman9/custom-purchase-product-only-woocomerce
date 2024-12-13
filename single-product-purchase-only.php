<?php
/**
 * Plugin Name: Checkout Cart Warning
 * Description: Vacía el carrito si el usuario abandona el checkout, confirmar o no paga en 10 minutos.
 * Version: 1.4
 * Author: Jhorman Nieto P
 * Author URI: https://jhorman-dev.netlify.app/
 * License: GPL2
 */

// Evitar el acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Función para manejar la sesión del carrito
add_action('template_redirect', function() {
    $cookie_name = 'in_checkout';
    $checkout_timeout = 600; // 10 minutos en segundos (600 segundos)

    // Verificar si el usuario está en el checkout o en la página de confirmación
    if ((is_checkout() || is_page('confirmar')) && !is_wc_endpoint_url('order-received') && !is_page('complete-payment')) {
        // Si el usuario entra al checkout o confirmación, se establece la cookie con el tiempo actual
        if (!isset($_COOKIE[$cookie_name])) {
            setcookie($cookie_name, time(), time() + $checkout_timeout, '/');
        }
    }

    // Verificar si la cookie de checkout existe
    if (isset($_COOKIE[$cookie_name])) {
        $checkout_start_time = $_COOKIE[$cookie_name];
        
        // Verificar si han pasado más de 10 minutos desde que se estableció la cookie
        if (time() - $checkout_start_time > $checkout_timeout) {
            // Si han pasado más de 10 minutos, vaciar el carrito
            WC()->cart->empty_cart();
            // Eliminar la cookie
            setcookie($cookie_name, '', time() - 3600, '/');
        }
    }

    // Verificar si el usuario está fuera del checkout, confirmación o order-received
    if (!is_checkout() && !is_wc_endpoint_url('order-received') && !is_page('complete-payment') && !is_page('confirmar')) {
        // Si la cookie de checkout está activa, vaciar el carrito
        if (isset($_COOKIE[$cookie_name])) {
            WC()->cart->empty_cart();
            setcookie($cookie_name, '', time() - 3600, '/');
        }
    }
});
