<?php
require_once dirname(plugin_dir_path( __FILE__)) . '/vendor/autoload.php';

function stripe_wp_update_post_meta($post_id, $meta_field_name, $input_filter = null, $default_val = null) {
    if (!empty($_POST[$meta_field_name])) {
        if (!is_null($input_filter)) {
            $update_data = call_user_func($input_filter, $_POST[$meta_field_name]);
        } else {
            $update_data = $_POST[$meta_field_name];
        }
        update_post_meta($post_id, $meta_field_name, $update_data);
    } else if (!is_null($default_val)) {
        update_post_meta($post_id, $meta_field_name, $default_val);
    }
}


function stripe_wp_return_true( $val ) {
    /* Input filter that always returns true. */
    return true;
}


function stripe_wp_set_donate_page_product_id($post_id) {
    /* Set/create the product ID for this post. 
     * @param $post_id: The ID for the post/page
     * @return string: The product ID just set/created.
     */
    $STRIPE_API_KEY = get_option('stripe_api_key');
    $product_name = get_post_meta($post_id, 'stripe_wp_product_name', true);
    if (!empty($product_name)) {
        $stripe_product = stripe_wp_create_or_retrieve_stripe_product($STRIPE_API_KEY, $product_name);
        update_post_meta($post_id, 'stripe_wp_product_id', $stripe_product->id);
        return $stripe_product->id;
    }
}


function stripe_wp_update_interval_amount_options($post_id, $interval, $default_interval, $product_id) {
    $interval_default = get_post_meta($post_id, "stripe_wp_donate_{$interval}_options_is_default", true);
    $interval_options_count = get_post_meta($post_id, "stripe_wp_donate_{$interval}_options_count", true);
    $interval_options = array();
    for ($i = 0; $i < $interval_options_count; ++$i) {
        if (!empty( $option = $_POST["stripe_wp_donate_{$interval}_options_$i"] )) {
            $price = stripe_wp_create_or_retrieve_stripe_price($product_id, 100 * $option, $interval);
            if (
                ($i == $interval_default)
                && ($default_interval == $interval)
            ) {
                update_post_meta($post_id, 'stripe_wp_default_unit_amount', sanitize_text_field($option));
                update_post_meta($post_id, 'stripe_wp_default_price_id', $price->id);
            }
            $interval_options[] = array(
                'amount' => sanitize_text_field($option),
                'default' => $i == $interval_default,
                'price_id' => $price->id,
             );
        }
    }
    update_post_meta($post_id, "stripe_wp_donate_{$interval}_options", $interval_options);
}


function stripe_wp_donate_save_meta( $post_id ) {
    if (get_post_type($post_id) == 'stripe_wp_donate') {
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_call_to_action', 'wp_kses_post');
        stripe_wp_update_post_meta($post_id, 'stripe_wp_product_name', 'sanitize_text_field');
        $donate_intervals = array();
        $donate_intervals['month'] = (!empty($_POST['stripe_wp_allow_interval_month']));
        $donate_intervals['year'] = (!empty($_POST['stripe_wp_allow_interval_year']));
        $donate_intervals['one-time'] = (!empty($_POST['stripe_wp_allow_interval_one-time']));
        update_post_meta($post_id, 'stripe_wp_donate_intervals', $donate_intervals);
        stripe_wp_update_post_meta($post_id, 'stripe_wp_default_interval');
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_month_options_count', 'sanitize_text_field', '0');
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_year_options_count', 'sanitize_text_field', '0');
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_one-time_options_count', 'sanitize_text_field', '0');
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_month_options_is_default', 'sanitize_text_field', "0");
        $product_id = stripe_wp_set_donate_page_product_id($post_id);
        $default_interval = get_post_meta($post_id, 'stripe_wp_default_interval', true);
        stripe_wp_update_interval_amount_options($post_id, 'month', $default_interval, $product_id);
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_year_options_is_default', 'sanitize_text_field', "0");
        stripe_wp_update_interval_amount_options($post_id, 'year', $default_interval, $product_id);
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_one-time_options_is_default', 'sanitize_text_field', "0");
        stripe_wp_update_interval_amount_options($post_id, 'one-time', $default_interval, $product_id);
        stripe_wp_update_post_meta($post_id, 'stripe_wp_allow_custom_amounts', 'stripe_wp_return_true', false);
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_button_text', 'sanitize_text_field');
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_disclosure_text', 'sanitize_text_field');
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_transaction_security', 'sanitize_text_field');
        stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_additional_info', 'wp_kses_post');
    }
}


add_action('save_post', 'stripe_wp_donate_save_meta');
