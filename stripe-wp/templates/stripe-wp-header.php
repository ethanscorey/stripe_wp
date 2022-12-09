<?php /* Template Name: Stripe-WP Donate Page Header */
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
        <?php 
        $header_image = get_post_meta($post_id, 'stripe_wp_site_logo', true);
        if (empty($header_image)) {
            $header_image = get_post_meta($_GET['donate-page-id'], 'stripe_wp_site_logo', true);
        }
        if (empty($header_image)) {
            $header_image = get_option('stripe_wp_default_logo');
        } ?>
        <img src="<?php echo $header_image; ?>">
        </a>
    </header>
