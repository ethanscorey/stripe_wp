<?php
require_once dirname(plugin_dir_path( __FILE__)) . '/vendor/autoload.php';

function stripe_wp_create_or_retrieve_stripe_product( string $stripe_api_key, $product_name ) {
    /* Retrieve Stripe product by name, or create one if it doesn't exist.
     * @param $stripe_api_key: The API key for Stripe
     * @param $product_name: The name of the product
     * @return \Stripe\Product: Return the matching/created product
     */
    \Stripe\Stripe::setApiKey($stripe_api_key);
    $matching_product = \Stripe\Product::search([ 'query' => "name:'$product_name'" ])->data;
    if ( count($matching_product) ) {
        return $matching_product[0];
    }
    return \Stripe\Product::create([ 'name' => $product_name ]);
}


function stripe_wp_create_or_retrieve_stripe_price(
    string $product_id,
    int $unit_amount,
    string $interval = ''
) {
    /* Retrieve Stripe price or create one if it doesn't exist.
     * @param $product_id: The ID for the product associated with this price.
     * @param $unit_amount: The value of the price in cents (e.g., $10 = 1000)
     * @param $interval: The interval for recurring payments.
     * @return \Stripe\Price: The matching/created price.
     */
    \Stripe\Stripe::setApiKey(get_option('stripe_api_key'));
    $lookup_key = "$product_id-$unit_amount-$interval";
    $price = \Stripe\Price::all([
        'lookup_keys' => [$lookup_key],
        'product' => $product_id,
        'limit' => 1,
    ]);
    if ( count($price) ) {
        return $price->data[0];
    }
    if ( in_array($interval, array('day', 'week', 'month', 'year')) ) {
        // Create a recurring price
        $price = \Stripe\Price::create([
            'unit_amount' => $unit_amount,
            'currency' => 'usd',
            'product' => $product_id,
            'recurring' => ['interval' => $interval],
            'lookup_key' => $lookup_key,
        ]);
        return $price;
    }
    $price = \Stripe\Price::create([
        'unit_amount' => $unit_amount,
        'currency' => 'usd',
        'product' => $product_id,
        'lookup_key' => $lookup_key,
    ]);
    return $price;
}


function stripe_wp_create_checkout_session( WP_REST_Request $request ) {
    /* Create a checkout session for the requested price.
     * @param $request: A WP_REST_Request with a price lookup_key value
     * @return WP_REST_Response: A response containing redirect information
     */
    $STRIPE_API_KEY = get_option('stripe_api_key');
    try {
        \Stripe\Stripe::setApiKey(
            $STRIPE_API_KEY
        );
        $product_id = $request['product_id'];
        $price_id = $request['price_id'];
        $unit_amount = (int) $request['unit_amount'];
        $interval = $request['interval'];
        // If price ID is missing, we need to create a new price
        if ( empty($price_id) ) {
            $price = stripe_wp_create_or_retrieve_stripe_price(
                $product_id,
                $unit_amount,
                $interval
            );
            $price_id = $price->id;
            $is_recurring = str_contains($price->type, 'recurring');
        } else {
            $is_recurring = in_array($interval, array('day', 'week', 'month', 'year'));
        }
        $mode = $is_recurring ? 'subscription':'payment';
        $thank_you_url_args = $is_recurring ? 'session_id={CHECKOUT_SESSION_ID}':'one-time=true';
        $checkout_session = \Stripe\Checkout\Session::create([
          'line_items' => [[
              'price' => $price_id,
              'quantity' => 1,
          ]],
          'mode' => $mode,
          'success_url' => get_site_url() . "/stripe-wp-donate-thank-you?$thank_you_url_args",
          'cancel_url' => $request->get_header('Referer'),
        ]);
        $response = new WP_REST_Response();
        $response->set_status(303);
        $response->header( 'Location', $checkout_session->url);
        return $response;
    } catch (Exception $e) {
        $error_response = new WP_REST_Response();
        $error_response->set_status(303);
        $error_response->header( 'Location', '/stripe-wp-checkout-error/' );
        return $error_response;
    }
}


function stripe_wp_create_portal_session_from_email( WP_REST_REQUEST $request ) {
    try {
        $STRIPE_API_KEY = get_option('stripe_api_key');
        \Stripe\Stripe::setApiKey(
            $STRIPE_API_KEY
        );
        $customer_id = \Stripe\Customer::all(['email' => $request['stripe-customer-email']])->data[0]->id;
        $subscription_portal = \Stripe\BillingPortal\Session::create([
          'customer' => $customer_id,
          'return_url' => get_site_url() . '/manage-your-recurring-donation/',
        ]);
        $response = new WP_REST_Response();
        $response->set_status(303);
        $response->header( 'Location', $subscription_portal->url );
        return $response;
    } catch (Exception $e) {
        $error_response = new WP_REST_Response();
        $error_response->set_status(303);
        $error_response->header( 'Location', '/manage-your-recurring-donation/' );
        return $error_response;
    }
}

function stripe_wp_create_portal_session( WP_REST_REQUEST $request ) {
    try {
        $STRIPE_API_KEY = get_option('stripe_api_key');
        \Stripe\Stripe::setApiKey(
            $STRIPE_API_KEY
        );
        $checkout_session = \Stripe\Checkout\Session::retrieve($request['session_id']);
        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $checkout_session->customer,
            'return_url' => get_site_url(),
        ]);
        $response = new WP_REST_Response();
        $response->set_status(303);
        $response->header( 'Location', $session->url);
        return $response;
    } catch (Exception $e) {
        $error_response = new WP_REST_Response();
        $error_response->set_status(303);
        $error_response->header( 'Location', '/manage-your-recurring-donation/' );
        return $error_response;
    }
}


add_action('rest_api_init', function() {
        register_rest_route( 'stripe_wp/v1', '/create-checkout', array(
            'methods' => 'POST',
            'callback' => 'stripe_wp_create_checkout_session',
            'permission_callback' => '__return_true',
        ) );
        register_rest_route( 'stripe_wp/v1', '/create-portal', array(
            'methods' => 'POST',
            'callback' => 'stripe_wp_create_portal_session',
            'permission_callback' => '__return_true',
        ) );
        register_rest_route( 'stripe_wp/v1', '/create-portal-email', array(
            'methods' => 'POST',
            'callback' => 'stripe_wp_create_portal_session_from_email',
            'permission_callback' => '__return_true',
        ) );
    }
);
