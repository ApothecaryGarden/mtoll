<?php

class M_Points_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'M_Points') );
	}

	function test_class_access() {
		$this->assertTrue( mtoll()->points instanceof M_Points );
	}
}
