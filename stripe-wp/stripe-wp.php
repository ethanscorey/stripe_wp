<?php
/**
 * Plugin Name: Stripe/WordPress integration
 * Version: 0.8.0
 * Description: Backend support for integrating Stripe with WordPress.
 * Author: Ethan Corey for The Political Report Inc.,
 * License: MIT License
 */

define('STRIPE_WP_ASSET_URL', plugins_url('assets/', __FILE__));
include plugin_dir_path( __FILE__ ) . 'includes/meta-box-components.php';
include plugin_dir_path( __FILE__ ) . 'includes/donate-page-components.php';
include plugin_dir_path( __FILE__ ) . 'includes/save-post-meta.php';
include plugin_dir_path( __FILE__ ) . 'includes/stripe-api-integration.php';


function stripe_wp_template_include( $template ) {
    global $post;
    $post_type = get_post_type($post);
    $post_name = $post->post_name;
    $plugin_path = plugin_dir_path( __FILE__ ) . 'templates/';
    if ($post_type == 'stripe_wp_donate') {
        $template = $plugin_path . 'single-stripe-wp-donate-page.php';
    } else if ($post_name === 'stripe-wp-donate-thank-you') {
        $template = $plugin_path . 'stripe-wp-thank-you.php';
    } else if ($post_name === 'stripe-wp-error') {
        $template = $plugin_path . 'stripe-wp-error.php';
    } else if ($post_name === 'manage-your-recurring-donation') {
        $template = $plugin_path . 'stripe-wp-manage-recurring-donation.php';
    }
    return $template;
}

add_action( 'template_include', 'stripe_wp_template_include');


function stripe_wp_donate_page() {
    register_post_type(
        'stripe_wp_donate',
        array(
            'labels' => array(
                'name' => 'Donate Pages',
                'singular_name' => 'Donate Page',
             ),
            'rewrite' => array('slug' => 'donate-page'),
            'public' => true,
            'has_archive' => false,
            'show_in_rest' => true,
            'supports' => ['title'],
            'register_meta_box_cb' => 'stripe_wp_add_meta_boxes',
        )
    );
}
add_action('init', 'stripe_wp_donate_page');


function stripe_wp_error_page() {
    $plugin_path = plugin_dir_path( __FILE__ ) . 'templates/';
    $error_page = array(
        'post_title' => wp_strip_all_tags('Checkout Error'),
        'post_name' => wp_strip_all_tags('stripe-wp-error'),
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page',
    );
    $error_page_id = wp_insert_post( $error_page );
    update_option( 'stripe_wp_error_page_id', $error_page_id );
}


function stripe_wp_thank_you_page() {
    $plugin_path = plugin_dir_path( __FILE__ ) . 'templates/';
    $thank_you_page = array(
        'post_title' => wp_strip_all_tags('Thank You'),
        'post_name' => wp_strip_all_tags('stripe-wp-donate-thank-you'),
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page',
    );
    $thank_you_page_id = wp_insert_post( $thank_you_page );
    update_option( 'stripe_wp_thank_you_page_id', $thank_you_page_id );
}


function stripe_wp_manage_donation_page() {
    $plugin_path = plugin_dir_path( __FILE__ ) . 'templates/';
    $manage_donation_page = array(
        'post_title' => wp_strip_all_tags('Manage Your Recurring Donation'),
        'post_name' => wp_strip_all_tags('manage-your-recurring-donation'),
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page',
    );
    $manage_donation_page_id = wp_insert_post( $manage_donation_page );
    update_option( 'stripe_wp_manage_donation_page_id', $manage_donation_page_id );

}

register_activation_hook(__FILE__, 'stripe_wp_activate');

function stripe_wp_activate() {
    stripe_wp_manage_donation_page();
    stripe_wp_thank_you_page();
    stripe_wp_error_page();
    stripe_wp_donate_page();
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'stripe_wp_deactivate');


function stripe_wp_deactivate() {
    $error_page_id = get_option( 'stripe_wp_error_page_id' );
    $thank_you_page_id = get_option( 'stripe_wp_thank_you_page_id' );
    $manage_donation_page_id = get_option( 'stripe_wp_manage_donation_page_id' );
    if ($error_page_id) {
        wp_delete_post($error_page_id, true);
    }
    if ($thank_you_page_id) {
        wp_delete_post($thank_you_page_id);
    }
    if ($manage_donation_page_id) {
        wp_delete_post($manage_donation_page_id);
    }
    unregister_post_type('stripe_wp_donate');
    flush_rewrite_rules();
}


function stripe_wp_exclude_pages_from_page_list($pages) {
    if (!is_admin()) {
        $new_pages = array();
        foreach($pages as $page) {
            if (!in_array(
                $page->post_name,
                array(
                    'stripe-wp-error', 
                    'stripe-wp-donate-thank-you', 
                    'manage-your-recurring-donation',
                )) 
            ) {
                $new_pages[] = $page;
            }
        }
    }
    return $new_pages;
}

add_filter('get_pages', 'stripe_wp_exclude_pages_from_page_list');


function stripe_wp_add_meta_boxes( $post ) {
    add_meta_box(
        'stripe_wp_site_logo',
        'Site Logo',
        'stripe_wp_site_logo'
    );
    add_meta_box(
        'stripe_wp_donate_call_to_action',
        'Donate Call to Action',
        'stripe_wp_donate_call_to_action'
    );
    add_meta_box(
        'stripe_wp_donate_options',
        'Donate Options',
        'stripe_wp_donate_options'
    );
    add_meta_box(
        'stripe_wp_donate_additional_info',
        'Additional Info',
        'stripe_wp_donate_additional_info'
    );
    add_meta_box(
        'stripe_wp_additional_styles',
        'Additional Styles',
        'stripe_wp_additional_styles'
    );
}


function stripe_wp_enqueue_scripts() {
    if (
        (get_post_type() == 'stripe_wp_donate') 
        || (str_contains(get_the_title(), 'Thank You'))
        || (str_contains(get_the_title(), 'Manage Your Recurring Donation'))
        || (str_contains(get_the_title(), 'Checkout Error'))
    ) {
        wp_enqueue_style('stripe_wp_normalize', STRIPE_WP_ASSET_URL.'css/normalize.css');
        wp_enqueue_style('stripe_wp_donate', STRIPE_WP_ASSET_URL.'css/donate.css');
        wp_enqueue_script('donate', STRIPE_WP_ASSET_URL.'js/donate.js', array('jquery'), '', true);
        wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
    }
}

add_action('wp_enqueue_scripts', 'stripe_wp_enqueue_scripts');


function stripe_wp_admin_enqueue_scripts() {
    $screen = get_current_screen();
    if ($screen->base === 'post') {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_media();
        wp_enqueue_script('donate-admin', STRIPE_WP_ASSET_URL.'js/donate-admin.js', array('jquery'), '', true);
    }
}

add_action('admin_enqueue_scripts', 'stripe_wp_admin_enqueue_scripts');
        

function stripe_wp_register_api_key() {
    add_submenu_page(
        'tools.php',
        'Update Stripe API Key',
        'Update Stripe API Key',
        'manage_options',
        'stripe-api-key',
        'stripe_wp_register_api_key_cb'
   );
}

add_action('admin_menu', 'stripe_wp_register_api_key');


function stripe_wp_register_api_key_cb() { ?>
    <div class="wrap"><div id="icon-tools" class="icon32"></div>
        <h2>Update Stripe API Key</h2>
        <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
            <input type="text" name="stripe_api_key" placeholder="Enter API Key">
            <input type="hidden" name="action" value="process_form">			 
            <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Update API Key"  />
        </form> 
    </div>
    <?php
}


function stripe_wp_submit_api_key() {
    if (isset($_POST['stripe_api_key'])) {
        $api_key = sanitize_text_field( $_POST['stripe_api_key'] );
        $api_exists = get_option('stripe_api_key');
        if (!empty($api_key) && !empty($api_exists)) {
            update_option('stripe_api_key', $api_key);
        } else {
            add_option('stripe_api_key', $api_key);
        }
    }
    wp_redirect($_SERVER['HTTP_REFERER']);
}
add_action( 'admin_post_nopriv_process_form', 'stripe_wp_submit_api_key' );
add_action( 'admin_post_process_form', 'stripe_wp_submit_api_key' );


function stripe_wp_loaded() {
    return true;
}
