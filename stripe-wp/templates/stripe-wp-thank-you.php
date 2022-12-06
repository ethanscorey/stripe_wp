<?php
/**
 * Template Name: Thank You Page
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
        <a class="stripe-wp-navbar-brand" href="/">
            <img src="<?php echo get_post_meta(get_the_ID(), 'stripe_wp_site_logo', true); ?>">
        </a>
    </header>
    <main role="main">
      <section>
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
