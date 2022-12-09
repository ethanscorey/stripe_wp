<?php
/**
 * Template Name: Recurring Donation Management Page
 *
 * @package Stripe WP
 * @author Ethan Corey <ethanscorey@gmail.com>
 */
include(dirname(__FILE__) . '/stripe-wp-header.php');
?>
    <main class="stripe-wp-donate-page-main" role="main">
      <section class="stripe-donate-page-container">
        <article>
          <h1>Manage Your Recurring Donation</h1>
          <p>If you would like to manage your recurring donation to <?php echo get_bloginfo('name'); ?>, please enter your email below to be redirected to your Stripe billing portal.</p>
          <?php 
        if ( array_key_exists('missing-email', $_GET) ) {
        ?>
        <p class="stripe-wp-error-text">No Stripe account found for <?php echo $_GET['email']; ?>.</p>
        <?php } ?>
          <form action="/wp-json/stripe_wp/v1/create-portal-email" method="POST">
            <input type="text" id="stripe-customer-email" name="stripe-customer-email" value="" required placeholder="Enter your email.">
           <p><button class="stripe-wp-btn stripe-wp-btn-submit" id="checkout-and-portal-button" type="submit">Manage your recurring donation</button></p>
          </form>
          <p>Your email is sent to Stripe and never stored on our servers.</p>
        </article>
      </section>
    </main>
    <?php wp_footer(); ?>
  </body>
</html>
