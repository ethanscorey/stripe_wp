<?php /* Template Name: Stripe-WP Donate Page */
  $post_id = get_the_ID();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <?php wp_head(); ?>
</head>
  <body <?php body_class(); ?>>
    <header class='stripe-wp-donate-page-header'>
    </header>
    <main role="main">
      <section>
        <div>
          <div>
            <div>
              <h1>Donate</h1>
            </div>
          </div>
          <div>
            <div>
              <h4>
                Choose an Amount to Donate:
              </h4>
              <div>
                <div>
                  <div>
                    <?php stripe_wp_donate_frequency_options($post_id); ?>
                  </div>
                 </div>
              </div>
              <div>
                <?php stripe_wp_donate_amount_options($post_id); ?>
              </div>
              <form action="/wp-json/stripe_wp/v1/create-checkout" method="POST">
                <div>
                  <input type="hidden" name="product_id" id="product_id" value="<?php echo get_post_meta($post_id, 'stripe_wp_product_id', true); ?>">
                  <input type="hidden" name="price_id" id="price_id" value="<?php stripe_wp_default_price_id($post_id); ?>">
                  <input type="hidden" name="unit_amount" id="unit_amount" value="<?php stripe_wp_default_unit_amount($post_id); ?>">
                  <input type="hidden" name="interval" id="interval" value="<?php stripe_wp_default_interval($post_id); ?>">
                  <button class="stripe-wp-btn stripe-wp-btn-submit" type="submit"><?php echo get_post_meta($post_id, 'stripe_wp_donate_button_text', true); ?></button>
                </div>
              </form>
            </div>
            <div>
              <?php echo get_post_meta($post_id, 'stripe_wp_donate_call_to_action', true ); ?>
            </div>
          </div>
          <div>
            <div>
              <?php echo get_post_meta($post_id, 'stripe_wp_donate_disclosure_text', true); ?>
            </div>
          </div>
          <div>
            <div>
              <?php echo get_post_meta($post_id, 'stripe_wp_donate_transaction_security', true); ?>
            </div>
          </div>
          <div>
            <div>
              <?php echo get_post_meta($post_id, 'stripe_wp_donate_additional_info', true); ?>
            </div>
          </div>
        </div>
      </section>
    </main>
    <?php wp_footer(); ?>
  </body>
</html>
