jQuery( "#stripe_wp_donate_month_options_count" ).change(
    function() {
        let numOptions = jQuery( this ).val();
        let currentRows = jQuery("table#stripe_wp_donate_month_options tbody tr").length;
        let diff = currentRows - numOptions;
        if (diff > 0) {
            console.log(diff);
            jQuery("table#stripe_wp_donate_month_options tbody tr:gt(" + (numOptions - 1) + ")").remove();
        }
        if (diff < 0) {
            for (var i = 0; i < -1 * diff; i++) {
                let newRow = jQuery(
                    '<tr><td><input type="number" min="0.50" max="999999.99" name="stripe_wp_donate_month_option_' 
                    + (i + currentRows) 
                    +'"></td><td><input type="radio" name="stripe_wp_donate_month_options_is_default"></td></tr>'
                );
                jQuery("table#stripe_wp_donate_month_options tbody").append(newRow.clone());
            }
        }
    }
);


jQuery( "#stripe_wp_allow_interval_month" ).change(
    function() {
        if (jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_month_options").parent().show();
        }
        if (!jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_month_options").parent().hide();
        }
    }
);


jQuery( "#stripe_wp_donate_year_options_count" ).change(
    function() {
        let numOptions = jQuery( this ).val();
        let currentRows = jQuery("table#stripe_wp_donate_year_options tbody tr").length;
        let diff = currentRows - numOptions;
        if (diff > 0) {
            console.log(diff);
            jQuery("table#stripe_wp_donate_year_options tbody tr:gt(" + (numOptions - 1) + ")").remove();
        }
        if (diff < 0) {
            for (var i = 0; i < -1 * diff; i++) {
                let newRow = jQuery(
                    '<tr><td><input type="number" min="0.50" max="999999.99" name="stripe_wp_donate_year_option_' 
                    + (i + currentRows) 
                    +'"></td><td><input type="radio" name="stripe_wp_donate_year_options_is_default"></td></tr>'
                );
                jQuery("table#stripe_wp_donate_year_options tbody").append(newRow.clone());
            }
        }
    }
);


jQuery( "#stripe_wp_allow_interval_year" ).change(
    function() {
        if (jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_year_options").parent().show();
        }
        if (!jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_year_options").parent().hide();
        }
    }
);


jQuery( "#stripe_wp_donate_one-time_options_count" ).change(
    function() {
        let numOptions = jQuery( this ).val();
        let currentRows = jQuery("table#stripe_wp_donate_one-time_options tbody tr").length;
        let diff = currentRows - numOptions;
        if (diff > 0) {
            console.log(diff);
            jQuery("table#stripe_wp_donate_one-time_options tbody tr:gt(" + (numOptions - 1) + ")").remove();
        }
        if (diff < 0) {
            for (var i = 0; i < -1 * diff; i++) {
                let newRow = jQuery(
                    '<tr><td><input type="number" min="0.50" max="999999.99" name="stripe_wp_donate_one-time_option_' 
                    + (i + currentRows) 
                    +'"></td><td><input type="radio" name="stripe_wp_donate_one-time_options_is_default"></td></tr>'
                );
                jQuery("table#stripe_wp_donate_one-time_options tbody").append(newRow.clone());
            }
        }
    }
);


jQuery( "#stripe_wp_allow_interval_one-time" ).change(
    function() {
        if (jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_one-time_options").parent().show();
        }
        if (!jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_one-time_options").parent().hide();
        }
    }
);
