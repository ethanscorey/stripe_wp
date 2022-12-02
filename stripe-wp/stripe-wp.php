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

    ?>
    <label for="stripe_wp_default_interval">Default Interval</label>
    <p class="description">Select the default interval</p>
    <select name="stripe_wp_default_interval" id="stripe_wp_default_interval">
        <option value="month" <?php echo ($default_interval == 'month') ? 'selected':''; ?>>Month</option>
        <option value="year" <?php echo ($default_interval == 'year') ? 'selected':''; ?>>Year</option>
        <option value="one-time" <?php echo($default_interval == 'one-time') ? 'selected':''; ?> >One-Time</option>
    </select>
    <label for="stripe_wp_donate_month_options">Donation Amounts (Monthly)</label>
    <p class="description">Choose which amounts to offer for monthly donations.</p>
    <table>
        <thead>
            <th></th>
            <th><label>Amount</label></th>
            <th>
                <label>Default Amount?</label>
                <p class="description">Is this the default amount for monthly donations?</p>
            </th>
        </thead>
        <tbody>
            <tr><td>1</td><td><input name="stripe_wp_donate_month_option_1" type="number" min="0.50" max="999999.99"></td><td><input name="stripe_wp_donate_month_is_default" value="1" type="radio"></td></tr>
            <tr><td>2</td><td><input name="stripe_wp_donate_month_option_2" type="number" min="0.50" max="999999.99"></td><td><input name="stripe_wp_donate_month_is_default" value="2" type="radio"></td></tr>
            <tr><td>3</td><td><input name="stripe_wp_donate_month_option_3" type="number" min="0.50" max="999999.99"></td><td><input name="stripe_wp_donate_month_is_default" value="3" type="radio"></td></tr>
            <tr><td>4</td><td><input name="stripe_wp_donate_month_option_4" type="number" min="0.50" max="999999.99"></td><td><input name="stripe_wp_donate_month_is_default" value="4" type="radio"></td></tr>
            <tr><td>5</td><td><input name="stripe_wp_donate_month_option_5" type="number" min="0.50" max="999999.99"></td><td><input name="stripe_wp_donate_month_is_default" value="5" type="radio"></td></tr>
       </tbody>
    </table>
    <?php
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
}


add_action('save_post', 'stripe_wp_donate_save_meta');


function stripe_wp_loaded() {
    return true;
}
