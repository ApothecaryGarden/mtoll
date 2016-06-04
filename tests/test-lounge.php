<?php

class M_Lounge_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'M_Lounge') );
	}

	function test_class_access() {
		$this->assertTrue( mtoll()->lounge instanceof M_Lounge );
	}

  function test_cpt_exists() {
    $this->assertTrue( post_type_exists( 'm-lounge' ) );
  }
}
