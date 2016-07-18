<?php

namespace Frozzare\Tests\WooCommerce\Export\Writers;

use Frozzare\WooCommerce\Export\Writers\CSV;

class CSV_Test extends \WP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		$this->writer = new CSV;
	}

	public function tearDown() {
		parent::tearDown();

		unset( $this->writer );
	}

	public function test_empty() {
		$this->writer->render( [] );
		$this->expectOutputString( '' );
	}

	public function test_success() {
		$this->writer->render( [
			[
				'Email' => 'hello@example.com'
			],
			null
		] );

		$this->expectOutputString( 'Email
"hello@example.com";
' );
	}
}
