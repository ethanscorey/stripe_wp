<?php
/**
 * Template Name: Stripe WP Error Page
 *
 * @package Stripe WP
 * @author Ethan Corey <ethanscorey@gmail.com>
 */
$support_email = get_post_meta(get_the_ID(), 'stripe_wp_support_email', true);
include(dirname(__FILE__) . '/stripe-wp-header.php');
?>
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
