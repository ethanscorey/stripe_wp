<?php
/**
 * Template Name: Thank You Page
 *
 * @package Stripe WP
 * @author Ethan Corey <ethanscorey@gmail.com>
 */
include(dirname(__FILE__) . '/stripe-wp-header.php');
?>
<!doctype html>
    <main class="stripe-wp-donate-page-main" role="main">
      <section class="stripe-donate-page-container">
        <article>
          <h1>Thank You for Your Support!</h1>
          <p>Your donation to <?php echo get_bloginfo('name'); ?> was successful.</p>
          <?php if ( !isset($_GET["one-time"]) ) { ?>
          <form action="/wp-json/stripe_wp/v1/create-portal" method="POST">
            <input type="hidden" id="session-id" name="session_id" value="<?php echo $_GET["session_id"]; ?>" />
           <p> <button class="stripe-wp-btn stripe-wp-btn-submit" id="checkout-and-portal-button" type="submit">Manage your billing information</button></p>
          </form>
          <?php } ?>
        </article>
      </section>
    </main>
    <?php wp_footer(); ?>
  </body>
</html>
