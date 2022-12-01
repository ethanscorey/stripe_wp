<?php
/**
 * Class PostTypeRegisteredTest
 *
 * @package Stripe_Wp
 */

/**
 * Test that custom post type is registered.
 */
class PostTypeRegisteredTest extends WP_UnitTestCase {

	/**
	 * A simple test to check if plugin custom post types are registered.
	 */
	public function test_post_type_registered() {
        $this->assertContains("stripe_wp_donate", get_post_types());
	}
}

