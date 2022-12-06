<?php
/**
 * Template Name: Recurring Donation Management Page
 *
 * @package Stripe WP
 * @author Ethan Corey <ethanscorey@gmail.com>
 */
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
        <article>
          <h1>Manage Your Recurring Donation</h1>
          <p>If you would like to manage your recurring donation to <?php echo get_bloginfo('name'); ?>, please enter your email below to be redirected to your Stripe billing portal.</p>
          <form action="/wp-json/stripe_wp/v1/create-portal-email" method="POST">
            <input type="text" id="stripe-customer-email" name="stripe-customer-email" value="">
           <p><button class="stripe-wp-btn stripe-wp-btn-submit" id="checkout-and-portal-button" type="submit">Manage your recurring donation</button></p>
          </form>
          <p>Your email is sent to Stripe and never stored on our servers.</p>
        </article>
      </section>
    </main>
    <?php wp_footer(); ?>
  </body>
</html>
