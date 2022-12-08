<?php
/**
 * Template Name: Stripe WP Error Page
 *
 * @package Stripe WP
 * @author Ethan Corey <ethanscorey@gmail.com>
 */
$support_email = get_post_meta(get_the_ID(), 'stripe_wp_support_email', true);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <?php wp_head(); ?>
</head>
  <body <?php body_class(); ?>>
    <header class='stripe-wp-donate-page-header'>
        <?php 
        $header_image = get_post_meta($_GET['donate-page-id'], 'stripe_wp_site_logo', true);
        if (empty($header_image)) {
            $header_image = get_option('stripe_wp_default_logo');
        } ?>
        <a class="stripe-wp-navbar-brand" href="/">
            <img src="<?php echo $header_image; ?>">
        </a>
    </header>
    <main class="stripe-wp-donate-page-main" role="main">
      <section class="stripe-donate-page-container">
        <article>
          <h1>Checkout Error</h1>
          <p>We’re sorry, there has been an error processing your donation. Please email <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a> for assistance.</p>
        </article>
      </section>
    </main>
    <?php wp_footer(); ?>
  </body>
</html>
