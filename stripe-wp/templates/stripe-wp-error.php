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
        <a class="stripe-wp-navbar-brand" href="/">
            <img src="<?php echo get_post_meta(get_the_ID(), 'stripe_wp_site_logo', true); ?>">
        </a>
    </header>
    <main role="main">
      <section>
        <article>
          <h1>Checkout Error</h1>
          <p>We’re sorry, there has been an error processing your donation. Please email <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a> for assistance.</p>
        </article>
      </section>
    </main>
    <?php wp_footer(); ?>
  </body>
</html>
