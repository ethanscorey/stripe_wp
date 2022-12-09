jQuery( "#stripe_wp_donate_month_options_count" ).change(
    function() {
        let numOptions = jQuery( this ).val();
        let currentRows = jQuery("table#stripe_wp_donate_month_options tbody tr").length;
        let diff = currentRows - numOptions;
        if (numOptions <= 0) {
            jQuery("table#stripe_wp_donate_month_options tbody tr").remove();
        }
        if (diff > 0) {
            jQuery("table#stripe_wp_donate_month_options tbody tr:gt(" + (numOptions - 1) + ")").remove();
        }
        if (diff < 0) {
            for (var i = 0; i < -1 * diff; i++) {
                let newRow = jQuery(
                    '<tr><td><input type="number" step="0.01" min="0.50" max="999999.99" name="stripe_wp_donate_month_option_' 
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
            jQuery('select#stripe_wp_default_interval option[value="month"]').show();
        }
        if (!jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_month_options").parent().hide();
            jQuery('select#stripe_wp_default_interval option[value="month"]').prop('selected', false).hide();
        }
    }
);


jQuery( "#stripe_wp_donate_year_options_count" ).change(
    function() {
        let numOptions = jQuery( this ).val();
        let currentRows = jQuery("table#stripe_wp_donate_year_options tbody tr").length;
        let diff = currentRows - numOptions;
        if (numOptions <= 0) {
            jQuery("table#stripe_wp_donate_year_options tbody tr").remove();
        }
        if (diff > 0) {
            jQuery("table#stripe_wp_donate_year_options tbody tr:gt(" + (numOptions - 1) + ")").remove();
        }
        if (diff < 0) {
            for (var i = 0; i < -1 * diff; i++) {
                let newRow = jQuery(
                    '<tr><td><input type="number" step="0.01" min="0.50" max="999999.99" name="stripe_wp_donate_year_option_' 
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
            jQuery('select#stripe_wp_default_interval option[value="year"]').show();
        }
        if (!jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_year_options").parent().hide();
            jQuery('select#stripe_wp_default_interval option[value="year"]').prop('selected', false).hide();
        }
    }
);


jQuery( "#stripe_wp_donate_one-time_options_count" ).change(
    function() {
        let numOptions = jQuery( this ).val();
        let currentRows = jQuery("table#stripe_wp_donate_one-time_options tbody tr").length;
        let diff = currentRows - numOptions;
        if (diff > 0) {
            jQuery("table#stripe_wp_donate_one-time_options tbody tr:gt(" + (numOptions - 1) + ")").remove();
        }
        if (diff < 0) {
            for (var i = 0; i < -1 * diff; i++) {
                let newRow = jQuery(
                    '<tr><td><input type="number" step="0.01" min="0.50" max="999999.99" name="stripe_wp_donate_one-time_option_' 
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
            jQuery('select#stripe_wp_default_interval option[value="one-time"]').show();
        }
        if (!jQuery( this ).is(":checked")) {
            jQuery("table#stripe_wp_donate_one-time_options").parent().hide();
            jQuery('select#stripe_wp_default_interval option[value="one-time"]').prop('selected', false).hide();
        }
    }
);


jQuery( "#stripe_wp_site_logo_upload_button" ).click(
    function() {
        var frame;
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( frame ) {
          frame.open();
          return;
        }

        // Create a new media frame
        frame = wp.media({
          title: 'Select or Upload Image',
          button: {
            text: 'Use this Image'
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });
        frame.render();


        // When an image is selected in the media frame...
        frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            jQuery('#stripe_wp_site_logo_input').val(attachment.url);
            jQuery('#stripe_wp_site_logo_preview').attr('src', attachment.url);

        });
        frame.open();
    });
