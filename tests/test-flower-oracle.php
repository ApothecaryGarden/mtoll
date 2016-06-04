<?php

class M_Flower_Oracle_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'M_Flower_Oracle') );
	}

	function test_class_access() {
		$this->assertTrue( mtoll()->flower-oracle instanceof M_Flower_Oracle );
	}

  function test_cpt_exists() {
    $this->assertTrue( post_type_exists( 'm-flower-oracle' ) );
  }
}
