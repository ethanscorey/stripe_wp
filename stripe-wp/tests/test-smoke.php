<?php
/**
 * Class SmokeTest
 *
 * @package Stripe_Wp
 */

/**
 * Sample test case.
 */
class SmokeTest extends WP_UnitTestCase {

	/**
	 * A simple test to check if plugin is loaded.
	 */
	public function test_smoke() {
        $this->assertTrue(stripe_wp_loaded());
	}
}
