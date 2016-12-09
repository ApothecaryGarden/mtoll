<?php

class M_Luna_Woofunnels_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'M_Luna_Woofunnels') );
	}

	function test_class_access() {
		$this->assertTrue( mtoll()->luna-woofunnels instanceof M_Luna_Woofunnels );
	}
}
