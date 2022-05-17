<?php declare(strict_types=1);
/**
 * File for php unit testcases
 *
 * @author Richard Muvirimi <tygalive@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace Rich4rdMuvirimi\WooCustomGateway\Tests;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use Rich4rdMuvirimi\WooCustomGateway\WooCustomGateway;

/**
 * Test Cases class
 *
 * @author Richard Muvirimi <tygalive@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class ControllerLoaderTest extends TestCase {

	// Adds Mockery expectations to the PHPUnit assertions count.
	use MockeryPHPUnitIntegration;

	/**
	 * Tear Down
	 *
	 * @return void
	 */
	protected function tearDown():void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * SetUp
	 *
	 * @return void
	 */
	protected function setUp():void {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Test the loader class magic methods
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return void
	 */
	public function testHooks():void {
		$loader = WooCustomGateway::instance();
		$loader->add_action( 'init', '__return_true', 25 );
		$loader->add_filter( 'the_title', '__return_true', 25 );

		// assert added
		self::assertNotFalse( has_action( 'init', '__return_true' ) );
		self::assertNotFalse( has_filter( 'the_title', '__return_true' ) );

		// assert priority
		self::assertSame( 25, has_action( 'init', '__return_true' ) );
		self::assertSame( 25, has_filter( 'the_title', '__return_true' ) );
	}

}
