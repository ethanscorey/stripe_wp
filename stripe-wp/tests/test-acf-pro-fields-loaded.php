<?php
/**
 * Class ACFIntegretationTest
 *
 * @package Stripe_Wp
 */

/**
 * Test that we can use ACF Pro functions within plugin
 */
class ACFIntegrationTest extends WP_UnitTestCase {

	/**
	 * A simple test to check if ACF Pro functions are available
	 */
	public function test_acf_pro_loaded() {
        $this->assertEquals(dirname(__FILE__, 2) . '/includes/acf/', STRIPE_WP_ACF_PATH);
        $this->assertEquals(plugins_url('stripe-wp') . '/includes/acf/', STRIPE_WP_ACF_URL);
	}
}
