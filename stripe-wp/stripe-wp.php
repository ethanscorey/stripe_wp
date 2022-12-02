<?php
/**
 * Plugin Name: Stripe/WordPress integration
 * Version: 0.1.0
 * Description: Backend support for integrating Stripe with WordPress.
 * Author: Ethan Corey
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


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
            'show_in_rest' => true,
            'supports' => ['title'],
            'register_meta_box_cb' => 'stripe_wp_add_meta_boxes',
        )
    );
}

add_action('init', 'stripe_wp_donate_page');


function stripe_wp_add_meta_boxes( $post ) {
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
        'stripe_wp_donate_additional_info',
    );
}


function stripe_wp_donate_call_to_action( $post ) {
    $text= get_post_meta($post->ID, 'stripe_wp_donate_call_to_action' , true );
    $meta_box_id = 'stripe_wp_donate_call_to_action';
    $editor_id = 'stripe_wp_donate_call_to_action_editor';
    wp_editor(wp_kses_post($text), $editor_id, $settings = array('textarea_name' => $meta_box_id, 'media_buttons' => true, 'tinymce' => true, 'teeny' => false, 'wpautop' => true));
}


function stripe_wp_donate_options( $post ) {
    $stripe_product_name = get_post_meta($post->ID, 'stripe_wp_product_name', true);
    $allow_interval_month = get_post_meta($post->ID, 'stripe_wp_allow_interval_month', true);
    $month_checked = $allow_interval_month ? 'checked':'';
    $allow_interval_year = get_post_meta($post->ID, 'stripe_wp_allow_interval_year', true);
    $year_checked = $allow_interval_year ? 'checked':'';
    $allow_interval_onetime = get_post_meta($post->ID, 'stripe_wp_allow_interval_one-time', true);
    $onetime_checked = $allow_interval_onetime ? 'checked':'';
    $default_interval = get_post_meta($post->ID, 'stripe_wp_default_interval', true);
    $default_display_amount_options = 5;
    $month_display_amount_options = get_post_meta($post->ID, 'stripe_wp_donate_month_options_count', true);
    $year_display_amount_options = get_post_meta($post->ID, 'stripe_wp_donate_year_options_count', true); 
    $onetime_display_amount_options = get_post_meta($post->ID, 'stripe_wp_donate_one-time_options_count', true);
    stripe_wp_meta_text_box('stripe_wp_product_name', $stripe_product_name, 'Stripe Product Name', 'Choose the Stripe product name to which donations made through this form will be assigned (e.g., "NewsMatch 2022"):');
    stripe_wp_meta_checkbox_list(
        [
            array(
                'name' => 'stripe_wp_allow_interval_month',
                'checked' => $allow_interval_month,
                'label' => 'Month',
            ),
            array(
                'name' => 'stripe_wp_allow_interval_year',
                'checked' => $allow_interval_year,
                'label' => 'Year',
            ),
            array(
                'name' => 'stripe_wp_allow_interval_one-time',
                'checked' => $allow_interval_onetime,
                'label' => 'One-Time',
            ),
        ],
        'Donation Intervals',
        'Choose which donation intervals to allow'
    );
    stripe_wp_meta_option_select(
        'stripe_wp_default_interval',
        [
            array(
                'value' => 'month',
                'selected' => ($default_interval == 'month'),
                'label' => 'Month',
            ),
            array(
                'value' => 'year',
                'selected' => ($default_interval == 'year'),
                'label' => 'Year',
            ),
            array(
                'value' => 'one-time',
                'selected' => ($default_interval == 'one-time'),
                'label' => 'One-Time',
            ),
        ],
        'Default Interval',
        'Select the default interval'
    );
    stripe_wp_meta_amount_option_table(
        $post->ID,
        'stripe_wp_donate_month_options',
        $month_display_amount_options ? $month_display_amount_options:$default_display_amount_options,
        'Donation Amounts (Monthly)',
        'Set the monthly donation amount options.'
    );
    stripe_wp_meta_amount_option_table(
        $post->ID,
        'stripe_wp_donate_year_options',
        $year_display_amount_options ? $year_display_amount_options:$default_display_amount_options,
        'Donation Amounts (Yearly)',
        'Set the yearly donation amount options.'
    );
    stripe_wp_meta_amount_option_table(
        $post->ID,
        'stripe_wp_donate_one-time_options',
        $onetime_display_amount_options ? $onetime_display_amount_options:$default_display_amount_options,
        'Donation Amounts (One-Time)',
        'Set the one-time donation amount options.'
    );
    stripe_wp_meta_checkbox_list(
        [
            array(
                'name' => 'stripe_wp_allow_custom_amounts',
                'checked' => get_post_meta($post->ID, 'stripe_wp_allow_custom_amounts', true),
                'label' => '',
            ),
        ],
        'Allow Custom Amounts?',
        ''
    );
    stripe_wp_meta_text_box(
        'stripe_wp_donate_button_text',
        get_post_meta($post->ID, 'stripe_wp_donate_button_text', true),
        'Donate Button Text',
        'Choose what text to display on the donate form submit button.'
    );
    stripe_wp_meta_text_box(
        'stripe_wp_donate_disclosure_text',
        get_post_meta($post->ID, 'stripe_wp_donate_disclosure_text', true),
        'Nonprofit Disclosure',
        'This sets the nonprofit disclosure text to display beneath the donate form.',
    );
    stripe_wp_meta_text_box(
        'stripe_wp_donate_transaction_security',
        get_post_meta($post->ID, 'stripe_wp_donate_transaction_security', true),
        'Transaction Security',
        'This sets the transaction security message to display beneath the donate form.',
    );
}


function stripe_wp_donate_additional_info( $post ) {
    $text= get_post_meta($post->ID, 'stripe_wp_donate_additional_info' , true );
    $meta_box_id = 'stripe_wp_donate_additional_info';
    $editor_id = 'stripe_wp_donate_additional_info_editor';
    wp_editor(wp_kses_post($text), $editor_id, $settings = array('textarea_name' => $meta_box_id, 'media_buttons' => true, 'tinymce' => true, 'teeny' => false, 'wpautop' => true));
}


function stripe_wp_meta_text_box($name, $value, $label, $description) {
    ?>
    <div class="stripe-wp-donate-meta-field">
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <p class="description"><?php echo $description; ?></p>
        <input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo sanitize_text_field($value); ?>">
    </div>
    <?php
}


function stripe_wp_meta_checkbox_list($names, $label, $description) {
    ?>
    <div class="stripe-wp-donate-meta-field">
        <label><?php echo $label; ?></label>
        <p class="description"><?php echo $description; ?></p>
        <ul class="stripe-wp-checkbox-list">
        <?php
        foreach ($names as $name) {
            $interval_name = $name["name"];
            $interval_checked = $name["checked"] ? 'checked':'';
            $interval_label = $name["label"];
            echo "<li><input id='$interval_name' name='$interval_name' type='checkbox' $interval_checked><label for='$interval_name'>$interval_label</label></li>";
        }
        ?>
    </div>
    <?php
}


function stripe_wp_meta_option_select($name, $options, $label, $description) {
    ?>
    <div class="stripe-wp-donate-meta-field">
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <p class="description"><?php echo $description; ?></p>
        <select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
        <?php
        foreach ($options as $option) {
        ?>
            <option value="<?php echo $option['value']; ?>" <?php echo $option['selected'] ? 'selected':'' ?>><?php echo $option['label']; ?></option>
        <?php
        }
        ?>
        </select>
    </div>
    <?php
}


function stripe_wp_meta_amount_option_table($post_id, $name, $num_options, $label, $description) {
    ?>
    <div class="stripe-wp-donate-meta-field">
        <label><?php echo $label; ?></label>
        <p class="description"><?php echo $description; ?></p>
        <label for="<?php echo "{$name}_count" ?>">Number of options</label>
        <input type="number" name="<?php echo "{$name}_count"; ?>" id="<?php echo "{$name}_count"; ?>" value="<?php echo $num_options; ?>" step="1">
        <table>
            <thead>
                <th><label>Amount</label></th>
                <th>
                    <label>Default Amount?</label>
                </th>
            </thead>
            <tbody>
            <?php
                for ($i = 0; $i < $num_options; ++$i) {
                    $amount = get_post_meta($post_id, "{$name}_$i", true);
                    $checked = (get_post_meta($post_id, "{$name}_is_default", true) == "$i") ? 'checked':'';
                    echo "<tr><td><input name='{$name}_$i' type=r'number' min='0.50' max='999999.99' value='$amount'></td><td><input name='{$name}_is_default' value='$i' type='radio' $checked></td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php
}

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


function stripe_wp_donate_save_meta( $post_id ) {
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_call_to_action', 'wp_kses_post');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_product_name', 'sanitize_text_field');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_allow_interval_month', 'stripe_wp_return_true', false);
    stripe_wp_update_post_meta($post_id, 'stripe_wp_allow_interval_year', 'stripe_wp_return_true', false);
    stripe_wp_update_post_meta($post_id, 'stripe_wp_allow_interval_one-time', 'stripe_wp_return_true', false);
    stripe_wp_update_post_meta($post_id, 'stripe_wp_default_interval');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_month_options_count', 'sanitize_text_field', '0');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_year_options_count', 'sanitize_text_field', '0');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_one-time_options_count', 'sanitize_text_field', '0');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_month_options_is_default', 'sanitize_text_field', "0");
    $month_options_count = get_post_meta($post_id, 'stripe_wp_donate_month_options_count', true);
    for ($i = 0; $i < $month_options_count; ++$i) {
        stripe_wp_update_post_meta($post_id, "stripe_wp_donate_month_options_$i", 'sanitize_text_field');
    }
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_year_options_is_default', 'sanitize_text_field', "0");
    $year_options_count = get_post_meta($post_id, 'stripe_wp_donate_month_options_count', true);
    for ($i = 0; $i < $year_options_count; ++$i) {
        stripe_wp_update_post_meta($post_id, "stripe_wp_donate_year_options_$i", 'sanitize_text_field');
    }
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_one-time_options_is_default', 'sanitize_text_field', "0");
    $onetime_options_count = get_post_meta($post_id, 'stripe_wp_donate_one-time_options_count', true);
    for ($i = 0; $i < $onetime_options_count; ++$i) {
        stripe_wp_update_post_meta($post_id, "stripe_wp_donate_one-time_options_$i", 'sanitize_text_field');
    }
    stripe_wp_update_post_meta($post_id, 'stripe_wp_allow_custom_amounts', 'stripe_wp_return_true', false);
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_button_text', 'sanitize_text_field');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_disclosure_text', 'sanitize_text_field');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_transaction_security', 'sanitize_text_field');
    stripe_wp_update_post_meta($post_id, 'stripe_wp_donate_additional_info', 'wp_kses_post');
}


add_action('save_post', 'stripe_wp_donate_save_meta');


function stripe_wp_loaded() {
    return true;
}
