<?php

namespace Disco\Tests\WPUnit\App\Utility;

use Disco\App\Utility\Config;
use Disco\App\Utility\Helper;

class HelperTest extends \Codeception\TestCase\WPTestCase {
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

	public function testIsInValidDataReturnsTrueWhenNoDatesSet() {
		$config = new Config( array() );
		$this->assertTrue( Helper::is_in_valid_data( $config ) );
	}

	public function testIsInValidDataReturnsTrueWhenCurrentDateIsWithinDateRange() {
		$config = new Config(
			array(
				'discount_valid_from' => '2023-01-01 00:00:00',
				'discount_valid_to'   => '2023-12-31 23:59:59',
			)
		);
		$this->assertTrue( Helper::is_in_valid_data( $config ) );
	}

	public function testIsInValidDataReturnsFalseWhenCurrentDateIsBeforeDateRange() {
		$config = new Config(
			array(
				'discount_valid_from' => '2022-01-01 00:00:00',
				'discount_valid_to'   => '2022-12-31 23:59:59',
			)
		);
		$this->assertFalse( Helper::is_in_valid_data( $config ) );
	}

	public function testIsInValidDataReturnsFalseWhenCurrentDateIsAfterDateRange() {
		$config = new Config(
			array(
				'discount_valid_from' => '2021-01-01 00:00:00',
				'discount_valid_to'   => '2021-12-31 23:59:59',
			)
		);
		$this->assertFalse( Helper::is_in_valid_data( $config ) );
	}

	public function testIsInValidDataReturnsFalseWhenDateFormatIsIncorrect() {
		$config = new Config(
			array(
				'discount_valid_from' => '2022-01-32 00:00:00', // Invalid date
				'discount_valid_to'   => '2022-12-31 24:00:00', // Invalid time
			)
		);
		$this->assertFalse( Helper::is_in_valid_data( $config ) );

		$config = new Config(
			array(
				'discount_valid_from' => '2022-01-01 00:60:00', // Invalid time
				'discount_valid_to'   => '2022-12-31 23:59:59',
			)
		);
		$this->assertFalse( Helper::is_in_valid_data( $config ) );

		$config = new Config(
			array(
				'discount_valid_from' => '2022-01-01 00:00:00',
				'discount_valid_to'   => '2022-13-31 23:59:59', // Invalid month
			)
		);
		$this->assertFalse( Helper::is_in_valid_data( $config ) );
	}
}
