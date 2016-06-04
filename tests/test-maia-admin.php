<?php

class M_Maia_Admin_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'M_Maia_Admin') );
	}

	function test_class_access() {
		$this->assertTrue( mtoll()->maia-admin instanceof M_Maia_Admin );
	}
}
