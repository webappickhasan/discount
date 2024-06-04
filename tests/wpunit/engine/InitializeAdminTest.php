<?php

namespace Disco\Tests\WPUnit;

use Codeception\TestCase\WPTestCase;

class InitializeAdminTest extends WPTestCase {
	/**
	 * @var string
	 */
	protected $root_dir;

	public function setUp(): void {
		parent::setUp();

		// your set up methods here
		$this->root_dir = dirname( dirname( __DIR__ ) );

		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		set_current_screen( 'edit.php' );
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be admin
	 */
	public function it_should_be_admin() {
		add_filter( 'wp_doing_ajax', '__return_false' );
		do_action( 'plugins_loaded' );

		$classes   = array();
		$classes[] = 'Disco\Internals\PostTypes';
		$classes[] = 'Disco\Internals\Shortcode';
		$classes[] = 'Disco\Internals\Transient';
		$classes[] = 'Disco\Integrations\CMB';
		$classes[] = 'Disco\Integrations\Cron';
		$classes[] = 'Disco\Integrations\Template';
//		$classes[] = 'Disco\Integrations\Widgets\My_Recent_Posts_Widget';
		$classes[] = 'Disco\Backend\ActDeact';
		$classes[] = 'Disco\Backend\Enqueue';
		$classes[] = 'Disco\Backend\Notices';
		$classes[] = 'Disco\Backend\Pointers';
		$classes[] = 'Disco\Backend\Settings_Page';

		$all_classes = get_declared_classes();
		foreach ( $classes as $class ) {
			$this->assertTrue( in_array( $class, $all_classes ) );
		}
	}

	/**
	 * @test
	 * it should be ajax
	 */
	public function it_should_be_admin_ajax() {
		add_filter( 'wp_doing_ajax', '__return_true' );
		do_action( 'plugins_loaded' );

		$classes   = array();
		$classes[] = 'Disco\Ajax\Ajax';
		$classes[] = 'Disco\Ajax\Ajax_Admin';

		$all_classes = get_declared_classes();
		foreach ( $classes as $class ) {
			$this->assertTrue( in_array( $class, $all_classes ) );
		}
	}
}
