<?php
namespace Disco\Tests\WPUnit\App\Intents;

class ShippingIntentTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WpunitTester
	 */
	protected $tester;

	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests
	public function testShippingIntent() {
		$post = static::factory()->post->create_and_get();

		$this->assertInstanceOf( \WP_Post::class, $post );
	}
}
