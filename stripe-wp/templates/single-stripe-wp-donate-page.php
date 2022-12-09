<?php /* Template Name: Stripe-WP Donate Page */
  $post_id = get_the_ID();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <?php echo get_post_meta($post_id, 'stripe_wp_additional_styles', true); ?>
  <?php wp_head(); ?>
</head>
  <body <?php body_class(); ?>>
    <header class='stripe-wp-donate-page-header'>
        <a class="stripe-wp-navbar-brand" href="/">
            <img src="<?php echo get_post_meta($post_id, 'stripe_wp_site_logo', true); ?>">
        </a>
    </header>
    <main role="main" class="stripe-wp-donate-page-main">
      <div class="stripe-wp-container">
        <div class="stripe-wp-donate-page-title">
        <h1><?php the_title(); ?></h1>
        </div>
        <div class="stripe-wp-row">
          <div class="stripe-wp-col-md-6">
            <h4>
              Choose an Amount to Donate:
            </h4>
            <div class="stripe-wp-button-group">
              <div class="stripe-wp-row">
                <div class="stripe-wp-col-md-12" id="stripe-wp-donate-frequency-options">
                  <?php stripe_wp_donate_frequency_options($post_id); ?>
                </div>
               </div>
            </div>
            <div class="stripe-wp-button-group" id="stripe-wp-donate-amount-options">
              <?php stripe_wp_donate_amount_options($post_id); ?>
            </div>
            <form action="/wp-json/stripe_wp/v1/create-checkout" method="POST">
              <div>
                <input type="hidden" name="product_id" id="product_id" value="<?php echo get_post_meta($post_id, 'stripe_wp_product_id', true); ?>">
                <input type="hidden" name="price_id" id="price_id" value="<?php stripe_wp_default_price_id($post_id); ?>">
                <input type="hidden" name="unit_amount" id="unit_amount" value="<?php stripe_wp_default_unit_amount($post_id); ?>">
                <input type="hidden" name="interval" id="interval" value="<?php stripe_wp_default_interval($post_id); ?>">
                <input type="hidden" name="page_id" id="page_id" value="<?php echo $post_id; ?>">
                <button class="stripe-wp-btn stripe-wp-btn-submit" type="submit"><?php echo get_post_meta($post_id, 'stripe_wp_donate_button_text', true); ?></button>
              </div>
            </form>
          </div>
          <div class="stripe-wp-col-md-6" id="stripe-wp-donate-call-to-action">
            <?php echo wpautop( get_post_meta($post_id, 'stripe_wp_donate_call_to_action', true ) ); ?>
          </div>
        </div>
        <div class="stripe-wp-row">
          <div class="stripe-wp-col-md-12" id="stripe-wp-donate-disclosure-text">
            <?php echo get_post_meta($post_id, 'stripe_wp_donate_disclosure_text', true); ?>
          </div>
        </div>
        <div class="stripe-wp-row">
          <div class="stripe-wp-col-md-12" id="stripe-wp-donate-transaction-security">
            <i class="fa fa-lock"></i>&nbsp;<?php echo get_post_meta($post_id, 'stripe_wp_donate_transaction_security', true); ?>
          </div>
        </div>
        <div class="stripe-wp-row">
          <div class="stripe-wp-col-md-12" id="stripe-wp-donate-additional-info">
            <?php echo wpautop( get_post_meta($post_id, 'stripe_wp_donate_additional_info', true) ); ?>
          </div>
        </div>
      </div>
    </main>
    <?php wp_footer(); ?>
  </body>
</html>
