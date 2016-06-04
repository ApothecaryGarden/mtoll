<?php

class M_Theme_Settings_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'M_Theme_Settings') );
	}

	function test_class_access() {
		$this->assertTrue( mtoll()->theme-settings instanceof M_Theme_Settings );
	}
}
