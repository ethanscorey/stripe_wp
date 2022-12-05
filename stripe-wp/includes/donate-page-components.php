<?php
function stripe_wp_donate_frequency_options($post_id) {
    $donate_intervals = get_post_meta($post_id, 'stripe_wp_donate_intervals', true);
    $interval_names = array('month', 'year', 'one-time');
    $default_interval = get_post_meta($post_id, 'stripe_wp_default_interval', true);
    foreach( $interval_names as $interval ) {
        if ( $donate_intervals[$interval] ) {
            $checked = ($interval == $default_interval) ? 'checked':'';
            $active = ($interval == $default_interval) ? 'active':'';
            ?>
              <label class="stripe-wp-btn stripe-wp-btn-control stripe-wp-donate-frequency-button <?php echo $active; ?>">
              <?php echo ucwords($interval); ?>
              <input id="<?php echo $interval; ?>" name="interval" class="stripe-wp-donate-frequency-option" type="radio" <?php echo $checked; ?>>
            </label>
            <?php
        }
    }
}


function stripe_wp_donate_amount_options($post_id) {
    $donate_intervals = get_post_meta($post_id, 'stripe_wp_donate_intervals', true);
    $default_interval = get_post_meta($post_id, 'stripe_wp_default_interval', true);
    $month_options = get_post_meta($post_id, 'stripe_wp_donate_month_options', true);
    $year_options = get_post_meta($post_id, 'stripe_wp_donate_year_options', true);
    $custom_amounts = get_post_meta($post_id, 'stripe_wp_allow_custom_amounts', true);
    $onetime_options = get_post_meta($post_id, 'stripe_wp_donate_one-time_options', true);
    if ($donate_intervals['month']) {
        foreach($month_options as $option) {
            stripe_wp_donate_amount_option($option['amount'], $option['price_id'], 'month', $option['default'], $default_interval == 'month');
        }
        if ( $custom_amounts ) {
            stripe_wp_custom_donate_amount_option('month', $default_interval == 'month');
        }
    }
    if ($donate_intervals['year']) {
        foreach($year_options as $option) {
            stripe_wp_donate_amount_option($option['amount'], $option['price_id'], 'year', $option['default'], $default_interval == 'year');
        }
        if ( $custom_amounts ) {
            stripe_wp_custom_donate_amount_option('year', $default_interval == 'year');
        }
    }
    if ($donate_intervals['one-time']) {
        foreach($onetime_options as $option) {
            stripe_wp_donate_amount_option($option['amount'], $option['price_id'], 'one-time', $option['default'], $default_interval == 'one-time');
        }
        if ( $custom_amounts ) {
            stripe_wp_custom_donate_amount_option('one-time', $default_interval == 'one-time');
        }
    }
}


function stripe_wp_donate_amount_option(
    $amount, $price_id, $frequency, $is_default_amount, $is_default_frequency
) {
    $disp_amount = '$' . number_format($amount, 2);
    $is_active = $is_default_amount && $is_default_frequency;
    $active = $is_active ? "active" : null;
    $checked = $is_active ? "checked" : null;
    $display_class = $is_default_frequency ? "" : "stripe-wp-d-none";
    $default_class = $is_default_amount ? "stripe-wp-default-$frequency" : "";
    $label_class_base = 'stripe-wp-btn stripe-wp-btn-control stripe-wp-donate-amount-button';
    $label_class = "$label_class_base stripe-wp-donate-$frequency $display_class $default_class $active";
    $input_class = 'stripe-wp-donate-amount-option';
    $value = round(100 * $amount, 2);
    echo <<<END
    <label class="$label_class" $active>
        <input
            type="radio"
            class="$input_class"
            autocomplete="off"
            id="$price_id"
            value="$value"
            name="donate-amount-option"
            $checked
        > $disp_amount
    </label>
    END;
}


function stripe_wp_custom_donate_amount_option($frequency, $is_default_frequency) {
    $display_class = $is_default_frequency ? "" : "stripe-wp-d-none";
    $input_class_base = 'stripe-wp-donate-amount-text';
    $input_class = "$input_class_base stripe-wp-donate-$frequency $display_class";
    echo <<<END
    <input
        name="custom-$frequency"
        placeholder="Custom"
        type="number"
        class="$input_class"
        autocomplete="off"
    >
    END;
}


function stripe_wp_default_price_id($post_id) {
    echo get_post_meta($post_id, 'stripe_wp_default_price_id', true);
}


function stripe_wp_default_unit_amount($post_id) {
    echo get_post_meta($post_id, 'stripe_wp_default_unit_amount', true);
}


function stripe_wp_default_interval($post_id) {
    echo get_post_meta($post_id, 'stripe_wp_default_interval', true);
}
