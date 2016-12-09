<?php

class M_Premium_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'M_Premium') );
	}

	function test_class_access() {
		$this->assertTrue( mtoll()->premium instanceof M_Premium );
	}

  function test_cpt_exists() {
    $this->assertTrue( post_type_exists( 'm-premium' ) );
  }
}
