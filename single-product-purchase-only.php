<?php
/**
 * Plugin Name: Checkout Cart Warning with Session Reset
 * Description: Vacía el carrito cuando el usuario está en la página de formulario y reinicia el carrito para evitar problemas con el PDF.
 * Version: 1.7
 * Author: Jhorman Nieto P
 * Author URI: https://jhorman-dev.netlify.app/
 * License: GPL2
 */

// Evitar el acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Función para vaciar el carrito cuando el usuario está en la página de formulario
add_action('template_redirect', function() {
    // Comprobar si el usuario está en la página del formulario
    if ( is_page('formulario') ) {
        // Verificar si el carrito tiene productos
        if ( WC()->cart->get_cart_contents_count() > 0 ) {
            // Vaciar el carrito
            WC()->cart->empty_cart();

            // Reiniciar la sesión del carrito para evitar problemas con el PDF
            WC()->session->__unset('cart');  // Limpiar la sesión del carrito
        }
    }
});
?>
