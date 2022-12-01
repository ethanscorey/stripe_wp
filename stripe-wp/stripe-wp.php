<?php
/**
 * Plugin Name: Stripe/WordPress integration
 * Version: 0.1.0
 * Description: Backend support for integrating Stripe with WordPress.
 * Author: Ethan Corey
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


function stripe_wp_loaded() {
    return true;
}


function stripe_wp_donate_page() {
    register_post_type(
        'stripe_wp_donate',
        array(
            'labels' => array(
                'name' => 'Donate Pages',
                'singular_name' => 'Donate Page',
             ),
            'public' => true,
            'has_archive' => false,
        )
    );
}

add_action('init', 'stripe_wp_donate_page');

function stripe_wp_load_acf() {
    // Define path and URL to the ACF plugin.
    define( 'STRIPE_WP_ACF_PATH', plugin_dir_path(__FILE__) . 'includes/acf/' );
    define( 'STRIPE_WP_ACF_URL', plugins_url('stripe-wp') . '/includes/acf/' );

    // Include the ACF plugin.
    include_once( STRIPE_WP_ACF_PATH . 'acf.php' );

    // Customize the url setting to fix incorrect asset URLs.
    add_filter('acf/settings/url', 'stripe_wp_acf_settings_url');
    function stripe_wp_acf_settings_url( $url ) {
        return STRIPE_WP_ACF_URL;
    }

    // (Optional) Hide the ACF admin menu item.
    add_filter('acf/settings/show_admin', '__return_false');

    // When including the PRO plugin, hide the ACF Updates menu
    add_filter('acf/settings/show_updates', '__return_false', 100);
}
add_action('init', 'stripe_wp_load_acf');
