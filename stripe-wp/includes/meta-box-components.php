<?php
function stripe_wp_site_logo( $post ) {
    stripe_wp_meta_file_upload(
        $post->ID,
        'stripe_wp_site_logo',
        'Site Logo',
        'Select the logo to display on the donation page.'
    );
}


function stripe_wp_additional_styles( $post ) {
    stripe_wp_meta_additional_styles(
        $post->ID,
        'stripe_wp_additional_styles',
        'Additonal Styles',
        'Paste any additional code you would like to include in the head tag for this page.',
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
    $donate_intervals = get_post_meta($post->ID, 'stripe_wp_donate_intervals', true);
    $allow_interval_month = $donate_intervals['month'] ?? true;
    $month_checked = $allow_interval_month ? 'checked':'';
    $allow_interval_year = $donate_intervals['year'] ?? true;
    $year_checked = $allow_interval_year ? 'checked':'';
    $allow_interval_onetime = $donate_intervals['one-time'] ?? true;
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
    $interval_options = array();
    if ($allow_interval_month) {
        $interval_options[] = array(
            'value' => 'month',
            'selected' => ($default_interval == 'month'),
            'label' => 'Month',
        );
    }
    if ($allow_interval_year) {
        $interval_options[] = array(
            'value' => 'year',
            'selected' => ($default_interval == 'year'),
            'label' => 'Year',
        );
    }
    if ($allow_interval_onetime) {
        $interval_options[] = array(
            'value' => 'one-time',
            'selected' => ($default_interval == 'one-time'),
            'label' => 'One-Time',
        );
    }

    stripe_wp_meta_option_select(
        'stripe_wp_default_interval',
        $interval_options,
        'Default Interval',
        'Select the default interval'
    );
    stripe_wp_meta_amount_option_table(
        $post->ID,
        $allow_interval_month,
        'stripe_wp_donate_month_options',
        $month_display_amount_options ? $month_display_amount_options:$default_display_amount_options,
        'Donation Amounts (Monthly)',
        'Set the monthly donation amount options.',
        'Monthly'
    );
    stripe_wp_meta_amount_option_table(
        $post->ID,
        $allow_interval_year,
        'stripe_wp_donate_year_options',
        $year_display_amount_options ? $year_display_amount_options:$default_display_amount_options,
        'Donation Amounts (Yearly)',
        'Set the yearly donation amount options.',
        'Yearly'
    );
    stripe_wp_meta_amount_option_table(
        $post->ID,
        $allow_interval_onetime,
        'stripe_wp_donate_one-time_options',
        $onetime_display_amount_options ? $onetime_display_amount_options:$default_display_amount_options,
        'Donation Amounts (One-Time)',
        'Set the one-time donation amount options.',
        'One-Time'
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


function stripe_wp_meta_amount_option_table($post_id, $allowed, $name, $num_options, $label, $description, $default_display_name) {
    $display = $allowed ? '':'style="display: none;"';
    $display_name = get_post_meta($post_id, "{$name}_display_name", true);
    $display_name = !empty($display_name) ? $display_name:$default_display_name;
    ?>
    <div class="stripe-wp-donate-meta-field" <?php echo $display; ?>>
        <label><?php echo $label; ?></label>
        <p class="description"><?php echo $description; ?></p>
        <p><label for="<?php echo "{$name}_count"; ?>">Number of options</label>
        <input type="number" name="<?php echo "{$name}_count"; ?>" id="<?php echo "{$name}_count"; ?>" value="<?php echo $num_options; ?>" step="1"></p>
        <p><label for="<?php echo "{$name}_display_name"; ?>">Interval display name</label>
        <input type="text" name="<?php echo "{$name}_display_name"; ?>" id="<?php echo "{$name}_display_name"; ?>" value="<?php echo $display_name; ?>"></p>
        <table id="<?php echo $name; ?>">
            <thead>
                <th><label>Amount</label></th>
                <th>
                    <label>Default Amount?</label>
                </th>
            </thead>
            <tbody>
            <?php
                $amount_options = get_post_meta($post_id, $name, true);
                $num_defined_options = is_array($amount_options) ? count($amount_options):0;
                for ($i = 0; $i < $num_options; ++$i) {
                    $amount = '';
                    $checked = '';
                    if ($i < $num_defined_options) {
                        $amount = $amount_options[$i]['amount'];
                        $checked = $amount_options[$i]['default'] ? 'checked':'';
                    }
                    echo "<tr><td><input name='{$name}_$i' type='number' min='0.50' max='999999.99' step='0.01' value='$amount'></td><td><input name='{$name}_is_default' value='$i' type='radio' $checked></td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php
}


function stripe_wp_meta_file_upload($post_id, $field_name, $label, $description) {
    $file_link = get_post_meta($post_id, $field_name, true);
    ?>
    <label for="<?php echo $field_name; ?>"><?php echo $label; ?></label>
    <p class="description"><?php echo $description; ?></p>
    <input id="<?php echo $field_name; ?>_input" name="<?php echo $field_name; ?>" type="hidden" value="<?php echo $file_link; ?>">
    <img height="100" id="<?php echo $field_name; ?>_preview" src="<?php echo $file_link; ?>">
    <input id="<?php echo $field_name; ?>_upload_button" type="button" value="Upload">
    <input id="make_header_default" name="make_header_default" type="checkbox">
    <?php
}


function stripe_wp_meta_additional_styles($post_id, $field_name, $label, $description) {
    ?>
    <label for="<?php echo $field_name; ?>"><?php echo $label; ?></label>
    <p class="description"><?php echo $description; ?></p>
    <textarea id="<?php echo $field_name; ?>_input" name="<?php echo $field_name; ?>"><?php echo get_post_meta($post_id, $field_name, true); ?></textarea>
    <?php
}
