// Donate page magic

jQuery( ".stripe-wp-btn input[type='radio']" ).change(
    function () {
        jQuery( '.stripe-wp-btn:has([name="' + jQuery( this ).attr("name") + '"])' ).removeClass("active");
        jQuery( this ).parent().addClass("active");
    }
);

jQuery( ".stripe-wp-donate-frequency-option" ).change(
    function () { 
        let interval = jQuery( this ).attr("id");
        jQuery( ".stripe-wp-donate-amount-button" ).addClass("stripe-wp-d-none");
        jQuery( ".stripe-wp-donate-amount-text" ).addClass("stripe-wp-d-none");
        jQuery( ".stripe-wp-donate-" + interval ).removeClass("stripe-wp-d-none");
        jQuery( ".stripe-wp-default-" + interval ).click();
        jQuery( "#interval").attr("value", interval);
    }
);

jQuery( ".stripe-wp-donate-amount-option" ).change(
    function() {
        jQuery( '#unit_amount' ).attr("value", jQuery( this ).attr("value") );
        jQuery( '#price_id' ).attr('value', jQuery( this ).attr("id") );
        jQuery( ".stripe-wp-donate-submit" ).prop("disabled", false);
    }
);

jQuery( ".stripe-wp-donate-amount-text" ).focus(
    function () {
        jQuery( ".stripe-wp-donate-amount-button" ).removeClass("active");
        jQuery( '#unit_amount' ).attr("value", "");
        jQuery( '#price_id' ).attr("value", "");
        jQuery( ".stripe-wp-donate-submit" ).prop("disabled", true);
    }
);

jQuery( ".stripe-wp-donate-amount-text" ).keyup(
    function() {
        let customDonateAmount = 100 * parseFloat(jQuery( this ).val());
        jQuery( '#unit_amount' ).attr(
            "value",
            customDonateAmount.toFixed()
        );
        let isValid = (0.5 < customDonateAmount) && (customDonateAmount < 99999999);
        jQuery( ".stripe-wp-btn-submit" ).prop("disabled", !isValid);
    }
);
